<?php

use Pluginus\CurrencySwitcher\Storage\Storage;
use Pluginus\CurrencySwitcher\Storage\TransientStorage;
use Pluginus\CurrencySwitcher\Storage\CookieStorage;
use Pluginus\CurrencySwitcher\Storage\SessionStorage;


if (!defined('ABSPATH'))
    die('No direct access allowed');

//keeps current user data
class WPCS_STORAGE {

    public $type = 'transient'; //session, transient, cookie

	private $storage = null;

    public function __construct( string $type = 'transient') {

		$this->storage = $this->get_storage_object($type);
		if ($this->storage === false) {
			$this->storage = $this->get_storage_object( $this->type );
		}

    }

    public function set_val($key, $value) {
        $value = sanitize_text_field(esc_html($value));
		$this->storage->setValue($key, $value);
    }

    public function get_val($key) {
		return $this->storage->getValue($key);
    }

    public function is_isset($key) {
		return $this->storage->isIsset($key);	
    }

	private function get_storage_object(string $type)  {
		$class = 'Pluginus\CurrencySwitcher\Storage\\' . ucfirst($type) . 'Storage';
		return  $class::create();
	}

}
