<?php

namespace Pluginus\CurrencySwitcher\Storage;

class TransientStorage extends Storage {
	private $transient_key = null;
	private function __construct() {
		$this->transient_key = md5(filter_var($this->getIp(), FILTER_VALIDATE_IP));
	}

	public static function create() {
		return new self();
	}
	
	public function setValue(string $key, $value) {
		$data = $this->getData();
		$data[$key] = $value;
		set_transient($this->transient_key, $data, 1 * 24 * 3600); //1 day
	}
	
	public function getValue(string $key) {
		$data = $this->getData();
		if (isset($data[$key])) {
			return $data[$key];
		}
		return null;
	}

	public function isIsset(string $key): bool {
		return $this->getValue($key) !== null;
	}

	private function getData() : array {
		$data = get_transient($this->transient_key);
		if (is_array($data)) {
			return $data;
		}
		return array();
	}

	private function getIp() : string
	 {
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			return (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		return '';
	}
}