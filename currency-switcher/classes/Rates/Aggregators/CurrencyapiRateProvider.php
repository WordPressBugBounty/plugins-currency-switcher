<?php

namespace Pluginus\CurrencySwitcher\Rates\Aggregators;
/**
 * CurrencyApiRateProvider
 *
 * @author Pavlo
 */
class CurrencyapiRateProvider  extends RateProvider{
	
	public function __construct( string $base, string $key = '') {
		parent::__construct($base, $key);
		$this->name = \esc_html__('Currency API', 'currency-switcher');
	}
	
	public function getRate(string $to): float {
		$this->resetError();
		if (empty($this->key)) {
			$this->setError(\esc_html__('This aggregator requires a license code', 'currency-switcher'));
			return -1;
		}
		return parent::getRate($to);		
	}
	
	protected function getApiUrl( string $to ) : string {
		$urlFormat = 'https://api.currencyapi.com/v3/latest?apikey=%1$s&base_currency=%2$s&currencies=%3$s';
		return sprintf($urlFormat, $this->key, $this->base, $to);
	}
	
	protected function parseResponse( $data, string $to): float {
		$rate = -1;
		try {	
			if (isset($data['data']) && isset($data['data'][$to])) {
				$rate = $data['data'][$to]['value'];
				
			} else {
				if (isset($data['message']) && $data['message'] ) {
					$this->setError(\sprintf(\esc_html__("Aggregator Error: %s", 'currency-switcher'), $data['message']));
				} else {
					$this->setError(\esc_html__('Invalid API key or no data for these currencies:', 'currency-switcher') . $this->base . "/" . $to  );
				}

			}		
		} catch (Exception $e) {
			$this->setError(\esc_html__('It looks like the aggregator server sent an incorrect response.', 'currency-switcher') . $to );
		}
		return $rate;
	}
}
