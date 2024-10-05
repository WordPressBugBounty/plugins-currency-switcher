<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class WPCS_SMART_DESIGNER {

    public function __construct() {

        add_action('wp_ajax_wpcs_sd_create', array($this, 'create'));
        add_action('wp_ajax_wpcs_sd_delete', function () {
			if ( !current_user_can( 'manage_options' ) ) {
				die();
			}
			if (isset($_REQUEST['nonce']) && wp_verify_nonce( $_REQUEST['nonce'], 'wpcs_sd_nonce' )) {

				$id = intval($_REQUEST['id']);
				delete_option('wpcs_sd_' . $id);
				$designs = $this->get_designs();
				if (($key = array_search($id, $designs, true)) !== false) {
					unset($designs[$key]);
				}
				update_option('wpcs_sd', $designs);
			}
        });
        add_action('wp_ajax_wpcs_sd_save', array($this, 'save'));
        add_action('wp_ajax_wpcs_sd_get', function () {
			if ( !current_user_can( 'manage_options' ) ) {
				die();
			}
			if ( isset($_REQUEST['nonce']) && wp_verify_nonce( $_REQUEST['nonce'], 'wpcs_sd_nonce' )) {		
				die(json_encode($this->get(intval($_REQUEST['id']))));
			}
        });

        add_action('admin_enqueue_scripts', function () {
            if (isset($_GET['page']) AND $_GET['page'] == 'currency-switcher-settings') {

                wp_enqueue_style('wp-color-picker');
                wp_enqueue_script('wp-color-picker');

                wp_enqueue_style('wpcs-sd', WPCS_LINK . 'css/sd/styles.css', [], WPCS_VERSION);
                wp_enqueue_style('wpcs-sd-selectron23', WPCS_LINK . 'css/sd/selectron23.css', [], WPCS_VERSION);
                wp_enqueue_style('wpcs-sd-ranger-23', WPCS_LINK . 'css/sd/ranger-23.css', [], WPCS_VERSION);
                wp_enqueue_style('wpcs-switcher23', WPCS_LINK . 'css/switcher23.css', [], WPCS_VERSION);

                wp_enqueue_script('wpcs-sd-selectron23', WPCS_LINK . 'js/sd/selectron23.js', [], WPCS_VERSION);
                wp_enqueue_script('wpcs-sd-ranger-23', WPCS_LINK . 'js/sd/ranger-23.js', [], WPCS_VERSION);
                wp_enqueue_script('wpcs-sd-switcher23', WPCS_LINK . 'js/sd/switcher23.js', [], WPCS_VERSION);
                wp_enqueue_script('wpcs-sd-options', WPCS_LINK . 'js/sd/options.js', [], WPCS_VERSION);
                wp_enqueue_script('wpcs-sd-drop-down', WPCS_LINK . 'js/sd/controllers/drop-down.js', [], WPCS_VERSION);

                wp_enqueue_script('wpcs-sd', WPCS_LINK . 'js/sd/smart-designer.js', ['wpcs-sd-drop-down'], WPCS_VERSION);

                wp_localize_script('wpcs-sd', 'wpcs_sd', [
					'nonce' => wp_create_nonce( 'wpcs_sd_nonce'),
                    'lang' => [
                        'loading' => esc_html__('Loading ...', 'currency-switcher'),
                        'loaded' => esc_html__('Loaded!', 'currency-switcher'),
                        'smth_wrong' => esc_html__('Something wrong!', 'currency-switcher'),
                        'saving' => esc_html__('saving ...', 'currency-switcher'),
                        'saved' => esc_html__('Saved!', 'currency-switcher'),
                        'are_you_sure' => esc_html__('Are you sure?', 'currency-switcher'),
                        'created' => esc_html__('Created!', 'currency-switcher'),
                        'creating' => esc_html__('Creating ...', 'currency-switcher'),
                        'select_currency' => esc_html__('Select currency', 'currency-switcher'),
                        'width' => esc_html__('Width', 'currency-switcher'),
                        'width100p' => esc_html__('Width 100%', 'currency-switcher'),
                        'scale' => esc_html__('Scale', 'currency-switcher'),
                        'description_font_size' => esc_html__('Description font size', 'currency-switcher'),
                        'title_show' => esc_html__('Show title', 'currency-switcher'),
                        'title_value' => esc_html__('Title value', 'currency-switcher'),
                        'title_font_size' => esc_html__('Title font size', 'currency-switcher'),
                        'title_color' => esc_html__('Title color', 'currency-switcher'),
                        'title_font' => esc_html__('Title font', 'currency-switcher'),
                        'border_radius' => esc_html__('Border radius', 'currency-switcher'),
                        'border_color' => esc_html__('Border color', 'currency-switcher'),
                        'show_flag' => esc_html__('Show flag', 'currency-switcher'),
                        'flag_pos' => esc_html__('Flag position', 'currency-switcher'),
                        'flag_height' => esc_html__('Flag height', 'currency-switcher'),
                        'flag_v_pos' => esc_html__('Flag vertical position', 'currency-switcher'),
                        'show_description' => esc_html__('Show description', 'currency-switcher'),
                        'description_color' => esc_html__('Description color', 'currency-switcher'),
                        'description_font' => esc_html__('Description font', 'currency-switcher'),
                        'bg_color' => esc_html__('Background color', 'currency-switcher'),
                        'pointer_color' => esc_html__('Pointer color', 'currency-switcher'),
                        'divider_color' => esc_html__('Options divider color', 'currency-switcher'),
                        'divider_size' => esc_html__('Options divider size', 'currency-switcher'),
                        'border_width' => esc_html__('Border width', 'currency-switcher'),
                        'max_opheight' => esc_html__('Max open height', 'currency-switcher'),
                        'title_bold' => esc_html__('Title Bold', 'currency-switcher'),
                        'deleting' => esc_html__('Deleting ...', 'currency-switcher'),
                        'deleted' => esc_html__('Deleted!', 'currency-switcher'),
                        'delete' => esc_html__('delete', 'currency-switcher'),
                        'edit' => esc_html__('edit', 'currency-switcher'),
                        'signs_using' => esc_html__('Use special keywords and their combination: __CODE__, __SIGN__, __DESCR__. Also you can use usual static text. Example: __CODE__ - __SIGN__', 'currency-switcher'),
                    ]]
                );
            }
        });
    }

    public function get_designs() {
        $res = get_option('wpcs_sd', []);

        if (empty($res)) {
            $res = [];
        }

        return $res;
    }

    //ajax
    public function create() {
		if ( !current_user_can( 'manage_options' ) ) {
			die();
		}
		if ( isset($_REQUEST['nonce']) && wp_verify_nonce( $_REQUEST['nonce'], 'wpcs_sd_nonce' )) {	
			$designs = $this->get_designs();

			if (empty($designs)) {
				$id = 1;
			} else {
				//$id = max($designs) + 1;
				$id = intval(get_option('wpcs_sd_max')) + 1;
			}

			add_option('wpcs_sd_' . $id, []);
			$designs[] = $id;
			update_option('wpcs_sd', $designs);
			update_option('wpcs_sd_max', $id);
			die("" . esc_attr($id));
		}
    }

    //ajax
    public function save() {
		if ( !current_user_can( 'manage_options' ) ) {
			die();
		}
		if ( isset($_REQUEST['nonce']) && wp_verify_nonce( $_REQUEST['nonce'], 'wpcs_sd_nonce' )) {		
			$data = json_decode(stripslashes($_REQUEST['options']), true);
			update_option('wpcs_sd_' . intval($_REQUEST['id']), $data);
		}
    }

    public function get($id) {
        if (get_option('wpcs_sd_' . $id, -1) === -1) {
            return -1;
        }

        return get_option('wpcs_sd_' . $id, []);
    }

    public function get_currencies() {
        global $WPCS;
        $all_currencies = apply_filters('wpcs_currency_manipulation_before_show', $WPCS->get_currencies());

        if (!empty($all_currencies)) {
            foreach ($all_currencies as $key => $currency) {
                if (isset($currency['hide_on_front']) AND $currency['hide_on_front']) {
                    unset($all_currencies[$key]);
                }
            }
        }

        return array_map(function ($c) {
            $title = apply_filters('wpcs_currname_in_option', $c['name']);
            return [
        'value' => $c['name'],
        'sign' => $c['symbol'],
        'title' => $title,
        'text' => $c['description'],
        'img' => $c['flag'],
        'title_attributes' => [
            'data-sign' => $c['symbol'],
            'data-name' => $title,
            'data-desc' => $c['description']
            ]];
        }, array_values($all_currencies));
    }

}

$GLOBALS['WPCS_SD'] = new WPCS_SMART_DESIGNER();

