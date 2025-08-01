<?php

namespace Pluginus\CurrencySwitcher\GeoIp;

/**
 * OpenGeoIp
 *
 * @author Pavlo
 */
class OpenIpCountryResolver extends IpCountryResolver {
	private $gi = null;
	private $cache = [];
	public function __construct() {
		include_once WPCS_PATH . 'lib/geo-ip/geoip.inc';
	}
	private function getGiInstance() {
		return \geoip_open(WPCS_PATH . 'lib/GeoIP.dat', GEOIP_MEMORY_CACHE);
	}
	public function getCountryCode() : string {
		if (isset($_SERVER["HTTP_CF_IPCOUNTRY"])) {
			return ( $_SERVER["HTTP_CF_IPCOUNTRY"]) ;
		}
		$data = $this->getCountryData();
		return $data['code'];
	}
	public function getCountryName() : string {			
			$data = $this->getCountryData();
			return $data['name'];
	}
	private function getCountryData() : array {
		$ip = $this->getIpAddress();
		if(isset($this->cache[$ip]) &&  isset($this->cache[$ip]['code'])  &&  isset($this->cache[$ip]['name']) ){
			return $this->cache[$ip];
		}
		$data = [];
		$gi = $this->getGiInstance();
		$name = \geoip_country_name_by_addr($gi, $ip);
		$pd = \geoip_country_code_by_addr($gi, $ip);
        \geoip_close($gi);
		
		$data['code'] = $pd;
		$data['name'] = $name;	
				
		$this->cache[$ip] = $data;
		
		return $data;		
	}
}
