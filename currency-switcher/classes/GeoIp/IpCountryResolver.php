<?php

namespace Pluginus\CurrencySwitcher\GeoIp;

/**
 * GeoIp
 *
 * @author Pavlo
 */
abstract class IpCountryResolver {
	abstract function getCountryCode() : string;
	abstract function getCountryName() : string;
	//abstract function getCountriesList() : array;
	static public function getOpenIpCountryResolver() : IpCountryResolver {
		return new OpenIpCountryResolver();
	}
	static public function getGeoIp2IpCountryResolver($licenseKey) : IpCountryResolver {
		return new GeoIp2IpCountryResolver($licenseKey);
	}
	protected static function getIpAddress() {
		//If is local host
		if ( $_SERVER['REMOTE_ADDR'] === '::1' ||  $_SERVER['REMOTE_ADDR'] === '127.0.0.1') {
			return '8.8.8.8';
		}		
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
