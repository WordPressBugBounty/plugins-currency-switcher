<?php

$welcome_curr_options = array();
if (!empty($currencies) AND is_array($currencies)) {
    foreach ($currencies as $key => $currency) {
        $welcome_curr_options[$currency['name']] = $currency['name'];
    }
}

if ($this->is_use_geo_rules()) {
    $gi = $this->get_geoip_object();
    //include_once WPCS_PATH .'lib/geo-ip/geoip.inc';
    //$gi = geoip_open(WPCS_PATH .'lib/GeoIP.dat', GEOIP_MEMORY_CACHE);
    $countries = array();
    foreach ($gi->GEOIP_COUNTRY_CODE_TO_NUMBER as $key => $var) {
        if ($var === 0 OR empty($key))
            continue;
        $countries[$key] = $gi->GEOIP_COUNTRY_NAMES[$var];
    }
    geoip_close($gi);
}

$aggregators = array(
    'yahoo' => __('http://finance.yahoo.com', 'currency-switcher'),
	'currencyapi' => 'Сurrencyapi',
    //'google' => __('http://google.com/finance', 'currency-switcher'),
    'free_ecb' => 'The Free Currency Converter by European Central Bank',
    'micro' => 'Micro pyramid',
    'rf' => __('http://www.cbr.ru - russian centrobank', 'currency-switcher'),
    'privatbank' => 'api.privatbank.ua - ukrainian privatbank',
    'ukrnatsbank' => 'Ukrainian national bank',
    'bank_polski' => 'Narodowy Bank Polsky',
    'free_converter' => 'The Free Currency Converter',
    'fixer' => 'Fixer',
    'cryptocompare' => 'CryptoCompare',
    //'xe' => 'XE Currency Converter'
    'ron' => 'www.bnr.ro',
    'currencylayer' => 'Сurrencylayer',
    'openexchangerates' => 'Open exchange rates',
	'bnm' => 'National Bank of Moldova',
	'mnb' => 'Magyar Nemzeti Bank',
);
$aggregators = apply_filters('wpcs_announce_aggregator', $aggregators);
//+++
$options = array(
    array(
        'name' => __('Drop-down view', 'currency-switcher'),
        'desc' => __('How to display currency switcher drop-down on the front of your site', 'currency-switcher'),
        'id' => 'wpcs_drop_down_view',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            'style-1' => __('Style #1', 'currency-switcher'),
            'style-2' => __('Style #2', 'currency-switcher'),
            'style-3' => __('Style #3', 'currency-switcher'),
            'ddslick' => __('ddslick', 'currency-switcher'),
            'chosen' => __('chosen', 'currency-switcher'),
            'chosen_dark' => __('chosen dark', 'currency-switcher'),
            'wselect' => __('wSelect', 'currency-switcher'),
            'no' => __('simple drop-down', 'currency-switcher'),
            'flags' => __('show as flags', 'currency-switcher'),
        ),
        'default' => 'ddslick'
    ),
    array(
        'name' => __('Show flags by default', 'currency-switcher'),
        'desc' => __('Show/hide flags on the front drop-down', 'currency-switcher'),
        'id' => 'wpcs_show_flags',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher')
        ),
        'default' => 1
    ),
    array(
        'name' => __('Show money signs', 'currency-switcher'),
        'desc' => __('Show/hide money signs on the front drop-down', 'currency-switcher'),
        'id' => 'wpcs_show_money_signs',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher')
        ),
        'default' => 1
    ),
    array(
        'name' => __('Show price info icon', 'currency-switcher'),
        'desc' => __('Show info icon near the price of the product which while its under hover shows prices of products in all currencies', 'currency-switcher'),
        'id' => 'wpcs_price_info',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher')
        ),
        'default' => 0
    ),
    array(
        'name' => __('Welcome currency', 'currency-switcher'),
        'desc' => __('In wich currency show prices for first visit of your customer on your site', 'currency-switcher'),
        'id' => 'wpcs_welcome_currency',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => $welcome_curr_options,
        'default' => 1
    ),
    array(
        'name' => __('Currency aggregator', 'currency-switcher'),
        'desc' => __('Currency aggregators. Note: XE Currency Converter doesnt work with crypto-currency such as BTC!', 'currency-switcher'),
        'id' => 'wpcs_currencies_aggregator',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => $aggregators,
        'default' => 'free_converter'
    ),
    array(
        'name' => esc_html__('Aggregator API key', 'currency-switcher'),
        'desc' => esc_html__('Some aggregators require an API key. See the hint below how to get it!', 'currency-switcher'),
        'id' => 'wpcs_aggregator_key',
        'type' => 'text',
        'std' => '', // WooCommerce < 2.0
        'default' => '', // WooCommerce >= 2.0
        'css' => 'min-width:300px;',
        'desc_tip' => true
    ),
    /*
      array(
      'name' => __('CURL for aggregators', 'currency-switcher'),
      'desc' => __('You can use it if aggregators doesn works with file_get_contents function because of security reasons. If all is ok leave it No!', 'currency-switcher'),
      'id' => 'wpcs_use_curl',
      'type' => 'select',
      'class' => 'chosen_select',
      'css' => 'min-width:300px;',
      'options' => array(
      0 => __('No', 'currency-switcher'),
      1 => __('Yes', 'currency-switcher')
      ),
      'default' => 1
      ),
     */
    array(
        'name' => __('Currency storage', 'currency-switcher'),
        'desc' => __('In some servers there is troubles with sessions, and after currency selecting its reset to welcome currency or geo ip currency. In such case use transient!', 'currency-switcher'),
        'id' => 'wpcs_storage',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            'session' => __('session', 'currency-switcher'),
            'transient' => __('transient', 'currency-switcher')
        ),
        'default' => 'transient'
    ),
    array(
        'name' => __('No GET data in link', 'currency-switcher'),
        'desc' => __('Switches currency without GET properties (?currency=USD) in the link.', 'currency-switcher'),
        'id' => 'wpcs_special_ajax_mode',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher')
        ),
        'default' => 0
    ),
//              array(
//              'name' => __('Use GeoLocation', 'currency-switcher'),
//              'desc' => __('Use GeoLocation rules for your currencies.', 'currency-switcher'),
//              'id' => 'wpcs_use_geo_rules',
//              'type' => 'select',
//              'class' => 'chosen_select',
//              'css' => 'min-width:300px;',
//              'options' => array(
//              0 => __('No', 'currency-switcher'),
//              1 => __('Yes', 'currency-switcher')
//              ),
//              'default' => 0
//              ),
    array(
        'name' => __('Rate auto update', 'currency-switcher'),
        'desc' => __('Currencies rate auto update by wp cron', 'currency-switcher'),
        'id' => 'wpcs_currencies_rate_auto_update',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            'no' => __('no auto update', 'currency-switcher'),
            'hourly' => __('hourly', 'currency-switcher'),
            'twicedaily' => __('twicedaily', 'currency-switcher'),
            'daily' => __('daily', 'currency-switcher'),
            'week' => __('weekly', 'currency-switcher'),
            'month' => __('monthly', 'currency-switcher')
        ),
        'default' => 'twicedaily'
    ),
    array(
        'name' => __('I am using cache plugin on my site', 'currency-switcher'),
        'desc' => __('Set Yes here ONLY if you are REALLY use cache plugin for your site, for example like Super cache or Hiper cache (doesn matter). After enabling this feature - clean your cache to make it works. It will allow show prices in selected currency on all pages of site. Fee for this feature - additional AJAX queries for products prices redrawing.', 'currency-switcher'),
        'id' => 'wpcs_shop_is_cached',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher'),
        ),
        'default' => 0
    ),
    array(
		'name' => __('Custom money signs.', 'currency-switcher'),
        'desc' => __('Add your money symbols in your shop.<br />Example: $USD,AAA,AUD$,DDD - separated by commas', 'currency-switcher'),
        'id' => 'wpcs_customer_signs',
        'type' => 'textarea',
        'css' => 'min-width:500px;',
        'default' => ''
    ),
    array(
        'name' => __('Custom price format', 'currency-switcher'),
        'desc' => __('Set your format how to display price on front.<br />Use keys: __CODE__,__PRICE__. Leave it empty to use default format. Example: __PRICE__ (__CODE__)', 'currency-switcher'),
        'id' => 'wpcs_customer_price_format',
        'type' => 'text',
        'css' => 'min-width:500px;',
        'default' => ''
    ),
    array(
        'name' => __('Decimal separator', 'currency-switcher'),
        'desc' => __('Decimal separator', 'currency-switcher'),
        'id' => 'wpcs_decimal_separator',
        'type' => 'text',
        'css' => 'min-width:500px;',
        'default' => '.'
    ),
    array(
        'name' => __('Thousandth separator', 'currency-switcher'),
        'desc' => __('Thousandth separator', 'currency-switcher'),
        'id' => 'wpcs_thousandth_separator',
        'type' => 'text',
        'css' => 'min-width:500px;',
        'default' => ','
    ),
    array(
        'name' => __('Show options button on top admin bar', 'currency-switcher'),
        'desc' => __('Show options button on top admin bar.', 'currency-switcher'),
        'id' => 'wpcs_show_top_button',
        'type' => 'select',
        'class' => 'chosen_select',
        'css' => 'min-width:300px;',
        'options' => array(
            0 => __('No', 'currency-switcher'),
            1 => __('Yes', 'currency-switcher')
        ),
        'default' => 1
    )
);
