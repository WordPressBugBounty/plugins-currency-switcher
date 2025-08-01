<?php

namespace Pluginus\CurrencySwitcher\Storage;

abstract class Storage {

	abstract public function setValue(string $key, $value);
	abstract public function getValue(string $key);
	abstract public function isIsset(string $key): bool;
	abstract public static function create();

}