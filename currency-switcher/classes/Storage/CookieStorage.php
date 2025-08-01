<?php

namespace Pluginus\CurrencySwitcher\Storage;

class CookieStorage extends Storage {
	private $guest_id = '';
	private $cookie_name = 'wpcs_guest_id';

	private function __construct() {
		$this->initGuestId();

		add_action('wp_ajax_wpcs_set_cookie', [$this, 'ajax_set_cookie']);
		add_action('wp_ajax_nopriv_wpcs_set_cookie', [$this, 'ajax_set_cookie']);
	}

	public static function create() {
		return new self();
	}

	public function setValue(string $key, $value) {
		set_transient($this->buildKey($key), $value, 1 * 24 * 3600); //1 day
	}

	public function getValue(string $key) {
		return get_transient($this->buildKey($key));
	}

	public function isIsset(string $key) : bool {
		return $this->getValue($key) !== null;
	}

	protected function initGuestId() {
        if (isset($_COOKIE[$this->cookie_name])) {
            $this->guest_id = sanitize_text_field($_COOKIE[$this->cookie_name]);
            return;
        }
        
        $this->guest_id = wp_generate_uuid4();

        if (!headers_sent()) {
            setcookie($this->cookie_name, $this->guest_id, time() + 24 * 3600, COOKIEPATH, COOKIE_DOMAIN);
        } else {
            $this->enqueue_ajax_cookie_script();
        }
    }

	private function enqueue_ajax_cookie_script() {
		add_action('wp_footer', function() {
			?>
			<script type="text/javascript">
			(function() {
				function getCookie(name) {
					const value = "; " + document.cookie;
					const parts = value.split("; " + name + "=");
					if (parts.length === 2) return parts.pop().split(";").shift();
					return null;
				}
				
				if (!getCookie('<?php echo $this->cookie_name; ?>')) {

					const formData = new FormData();
					formData.append('action', 'wpcs_set_cookie');
					formData.append('guest_id', '<?php echo $this->guest_id; ?>');
					formData.append('nonce', '<?php echo wp_create_nonce('wpcs_cookie_nonce'); ?>');
					
					fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
						method: 'POST',
						body: formData
					})
					.then(response => response.text())
					.then(data => {
						console.log('WPCS cookie set:', data);
					})
					.catch(error => {
						console.warn('WPCS cookie error:', error);
					});
				}
			})();
			</script>
			<?php
		});
	}

	public function ajax_set_cookie() {
		if (!wp_verify_nonce($_POST['nonce'], 'wpcs_cookie_nonce')) {
			wp_die('Security check failed');
		}
		
		$guest_id = sanitize_text_field($_POST['guest_id']);
		setcookie($this->cookie_name, $guest_id, time() + 24 * 3600, COOKIEPATH, COOKIE_DOMAIN);
		
		wp_die('OK');
	}

	private function buildKey(string $key) : string {
		return $this->guest_id . '_' . $key;
	}

}