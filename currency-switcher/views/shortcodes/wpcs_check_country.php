<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<div class="wpcs_check_country">
    <span class="wpcs_text_country"><?php echo esc_html__('Your current country is:', 'currency-switcher') ?> </span><span class="wpcs_country_name"><?php echo esc_html($name) ?></span>,
    <span class="wpcs_text_code"><?php echo esc_html__(' Code: ', 'currency-switcher') ?></span> <span class="wpcs_country_code"><?php echo esc_html($code) ?></span> 
</div>
