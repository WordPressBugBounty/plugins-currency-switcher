<?php

namespace Pluginus\CurrencySwitcher\Country;



/**
 * CountryRepository
 *
 * @author Pavlo
 */
class CountryRepository {
	private $countryRepository = null;
	private $lang = 'en';
	
	public function getCountriesList(){
		return $this->getRepository();
	}
	
	private function getRepository(): array {
		if ( null === $this->countryRepository ) {
			$lang = $this->getLang();
			if(file_exists(WPCS_PATH . 'data/Countries/' . $lang . '/country.php')) {	
				$lang = 'en';   		
			} 
			$this->countryRepository = include( WPCS_PATH . 'data/Countries/' . $lang . '/country.php');	
		}
		return $this->countryRepository;
	}
	private function getLang() {
	
		if (function_exists('get_locale')) {
			$locale = \get_locale(); 
			$this->lang = substr($locale, 0, 2);			
		}
		return $this->lang;
	}
}
