<?php 

namespace Pluginus\CurrencySwitcher\Storage;

class SessionStorage extends Storage {
	private function __construct() {}
	
	public static function create()  {
		if (!session_id()) {
			try {
				session_start();
				if (!session_id()) {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
		}
		
		return new self();
	}
	public function setValue(string $key, $value) {
		$_SESSION[$key] = $value;
	}
	
	public function getValue(string $key) {
		if ($this->isIsset($key)) {
			return $_SESSION[$key];
		}
		return null;	
	}
	
	public function isIsset(string $key) : bool {
		return isset($_SESSION[$key]);
	}
}
