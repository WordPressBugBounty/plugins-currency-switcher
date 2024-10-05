<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
global $WPCS;
$currencies = $WPCS->get_currencies();
//***
if (isset($exclude)) {
    $exclude_string = $exclude;
    $exclude = explode(',', $exclude);
} else {
    $exclude_string = "";
    $exclude = array();
}
//***
if (!isset($current_currency)) {
    $current_currency = $WPCS->current_currency;
}
//***
if (!isset($precision)) {
    $precision = 2;
}
?>

<div class="wpcs_rates_shortcode">

<?php if (!empty($currencies)): ?>
        <select class="wpcs_rates_current_currency" data-precision="<?php echo esc_attr($precision) ?>" data-exclude="<?php echo esc_attr($exclude_string) ?>">
    <?php
    if (!empty($currencies)) {
        foreach ($currencies as $key => $c) {
            if (in_array($key, $exclude)) {
                continue;
            }
            ?>
                    <option <?php selected($current_currency, $key) ?> value="<?php echo esc_attr($key) ?>"><?php printf(esc_html__('1 %s is', 'currency-switcher'), esc_html($c['name'])) ?></option>
                    <?php
                }
            }
            ?>
        </select><br />
        <ul class="wpcs_currency_rates">            
            <?php foreach ($currencies as $key => $c) : ?>
                <?php
                if ($key == $current_currency OR in_array($key, $exclude)) {
                    continue;
                }
                ?>
                <li>
                <?php if (!empty($c['flag'])): ?>
                        <img src="<?php echo esc_html($c['flag']) ?>" width="30" alt="<?php echo esc_attr($c['name']) ?>" />&nbsp;
                <?php endif; ?>
                    <strong><?php echo esc_html($key) ?></strong>:&nbsp;<?php
                $v = 0;
                if ($c['rate'] / $currencies[$current_currency]['rate'] > 0) {
                    $v = $c['rate'] / $currencies[$current_currency]['rate'];
                }
                echo number_format($v, intval($precision), $this->decimal_sep, '');
                ?><br />
                </li>
                <?php endforeach; ?>
        </ul>
            <?php endif; ?>

</div>

