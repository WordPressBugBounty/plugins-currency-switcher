<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>
<?php
//*** hide if there is checkout page
global $post;

if (!isset($sd_settings)) {

    $drop_down_view = $this->get_drop_down_view();

//for specials separated skins (style-1, style-2, etc...)
    if (isset($style) AND intval($style) > 0) {
        $drop_down_view = 'style-' . intval($style);
    }

    if (substr($drop_down_view, 0, 5) === 'style') {
        $num = intval(substr(strrev($drop_down_view), 0, 1));
        $styles_link = WPCS_LINK . 'views/shortcodes/styles/';
        $styles_path = WPCS_PATH . 'views/shortcodes/styles/';
        $shortcode_params = array();
        wp_enqueue_style('wpcs-style-' . $num, $styles_link . "style-{$num}/styles.css", array(), WPCS_VERSION);
        wp_enqueue_script('wpcs-style-' . $num, $styles_link . "style-{$num}/actions.js", array('jquery'), WPCS_VERSION);
        $this->render_html_e($styles_path . "style-{$num}/index.php", $shortcode_params);

        return FALSE;
    }

    $all_currencies = apply_filters('wpcs_currency_manipulation_before_show', $this->get_currencies());

//***
    if ($drop_down_view == 'flags') {
        foreach ($all_currencies as $key => $currency) {
            if (!empty($currency['flag'])) {
                ?>
                <a href="#" class="wpcs_flag_view_item <?php if ($this->current_currency == $key): ?>wpcs_flag_view_item_current<?php endif; ?>" data-currency="<?php echo esc_attr($currency['name']) ?>" title="<?php echo esc_html($currency['name'] . ', ' . $currency['symbol'] . ' ' . $currency['description']) ?>"><img src="<?php echo esc_html($currency['flag']) ?>" alt="<?php echo esc_html($currency['name'] . ', ' . $currency['symbol']) ?>" /></a>
                <?php
            }
        }
    } else {
        $empty_flag = WPCS_LINK . 'img/no_flag.png';
        $show_money_signs = $this->get_option('wpcs_show_money_signs', 1);
//***
        if (!isset($show_flags)) {
            $show_flags = $this->get_option('wpcs_show_flags', 1);
        }



        if (!isset($width)) {
            $width = '100%';
        }

        if (!isset($flag_position)) {
            $flag_position = 'right';
        }
        ?>


        <?php if ($drop_down_view == 'wselect'): ?>
            <style type="text/css">
                .currency-switcher-form .wSelect, .currency-switcher-form .wSelect-options-holder {
                    width: <?php echo esc_html($width) ?> !important;
                }
                <?php if (!$show_flags): ?>
                    .currency-switcher-form .wSelect-option-icon{
                        padding-left: 5px !important;
                    }
                <?php endif; ?>
            </style>
        <?php endif; ?>





        <form method="post" action="" class="currency-switcher-form <?php if ($show_flags): ?>wpcs_show_flags<?php endif; ?>">
            <input type="hidden" name="currency-switcher" value="<?php echo esc_attr($this->current_currency) ?>" />
            <select name="currency-switcher" style="width: <?php echo esc_attr($width) ?>;" data-width="<?php echo esc_attr($width) ?>" data-flag-position="<?php echo esc_attr($flag_position) ?>" class="currency-switcher" onchange="wpcs_redirect(this.value);
                    void(0);">
                        <?php foreach ($all_currencies as $key => $currency) : ?>

                    <?php
                    $option_txt = $currency['name'];

                    if ($show_money_signs) {
                        $option_txt .= ', ' . $currency['symbol'];
                    }
                    //***
                    if (isset($txt_type)) {
                        if ($txt_type == 'desc') {
                            if (!empty($currency['description'])) {
                                $option_txt = $currency['description'];
                            }
                        }
                    }
                    ?>

                    <option <?php if ($show_flags) : ?>style="background: url('<?php echo(!empty($currency['flag']) ? esc_html($currency['flag']) : esc_html($empty_flag)); ?>') no-repeat 99% 0; background-size: 30px 20px;"<?php endif; ?> value="<?php echo esc_attr($key) ?>" <?php selected($this->current_currency, $key) ?> data-imagesrc="<?php if ($show_flags) echo esc_html((!empty($currency['flag']) ? $currency['flag'] : $empty_flag)); ?>" data-icon="<?php if ($show_flags) echo esc_html((!empty($currency['flag']) ? $currency['flag'] : $empty_flag)); ?>" data-description="<?php echo esc_html($currency['description']) ?>"><?php echo esc_html($option_txt) ?></option>
                <?php endforeach; ?>
            </select>
            <div style="display: none;">WPCS <?php echo esc_html(WPCS_VERSION) ?></div>
        </form>
        <?php
    }
} else {
    if (intval($sd_settings) !== -1) {
        global $WPCS_SD;
        $currencies = $WPCS_SD->get_currencies();
        $title_value = isset($sd_settings['title_value']) ? $sd_settings : '__CODE__';
        if (isset($_GET['action']) AND $_GET['action'] === 'elementor') {
            echo "[wpcs sd=" . esc_html($sd_id) . "]"; //fix for elementor to avoid showing loader
        } else {
            ?>
            <div data-wpcs-sd='<?php echo json_encode($sd_settings) ?>' data-wpcs-ver='<?php echo esc_html(WPCS_VERSION) ?>' style="width: <?php echo (isset($sd_settings['width']) ? esc_attr($sd_settings['width']) . 'px' : 'auto') ?>; max-width: 100%;" data-wpcs-sd-currencies='<?php echo json_encode($currencies) ?>'><div class="wpcs-lds-ellipsis"><div></div><div></div><div></div><div></div></div></div>
            <?php
        }
    }
}

