<?php

namespace Pluginus\CurrencySwitcher\Storage;

class ClientStorage extends Storage {
	private $guest_id = '';

	private function __construct() {
		$this->initGuestId();
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
        if (!isset($_COOKIE['guest_id'])) {
            $this->guest_id = wp_generate_uuid4();
            setcookie('guest_id', $this->guest_id, time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
        } else {
            $this->guest_id = sanitize_text_field($_COOKIE['guest_id']);
        }
    }

	private function buildKey(string $key) : string {
		return $this->guest_id . '_' . $key;
	}

}