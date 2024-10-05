<?php if (!defined('ABSPATH')) die('No direct access allowed');?>


<p>
    <label for="<?php echo esc_attr($widget->get_field_id('title')); ?>"><?php esc_html_e('Title', 'currency-switcher') ?>:</label>
    <input class="widefat" type="text" id="<?php echo esc_attr($widget->get_field_id('title')); ?>" name="<?php echo esc_attr($widget->get_field_name('title')); ?>" value="<?php echo esc_html($instance['title']); ?>" />
</p>


<p>
    <label for="<?php echo esc_attr($widget->get_field_id('width')); ?>"><?php esc_html_e('Width', 'currency-switcher') ?>:</label>
    <input class="widefat" type="text" id="<?php echo esc_attr($widget->get_field_id('width')); ?>" name="<?php echo esc_attr($widget->get_field_name('width')); ?>" value="<?php echo esc_html($instance['width']); ?>" />
    <br /><i><?php esc_html_e('Examples: 300px,100%,auto', 'currency-switcher') ?></i>
</p>


<p>
    <?php
    $checked = "";
    if ($instance['show_flags'] == 'true')
    {
        $checked = 'checked="checked"';
    }
    ?>
    <input type="checkbox" id="<?php echo esc_attr($widget->get_field_id('show_flags')); ?>" name="<?php echo esc_attr($widget->get_field_name('show_flags')); ?>" value="true" <?php echo esc_attr($checked); ?> />
    <label for="<?php echo esc_attr($widget->get_field_id('show_flags')); ?>"><?php esc_html_e('Show flags', 'currency-switcher') ?>:</label>
</p>



<p>
    <label for="<?php echo esc_attr($widget->get_field_id('flag_position')); ?>"><?php esc_html_e('Flag position', 'currency-switcher') ?>:</label>
    <?php
    $sett = array(
        'right' => esc_html__('right', 'currency-switcher'),
        'left' => esc_html__('left', 'currency-switcher'),
    );
    ?>
    <select class="widefat" id="<?php echo esc_attr($widget->get_field_id('flag_position')) ?>" name="<?php echo esc_attr($widget->get_field_name('flag_position')) ?>">
        <?php foreach ($sett as $k => $val) : ?>
            <option <?php selected($instance['flag_position'], $k) ?> value="<?php echo esc_attr($k) ?>" class="level-0"><?php echo esc_html($val) ?></option>
        <?php endforeach; ?>
    </select>
    <i><?php esc_html_e('For ddslick skin only!', 'currency-switcher') ?></i>
</p>



<p>
    <label for="<?php echo esc_attr($widget->get_field_id('txt_type')); ?>"><?php esc_html_e('Drop-down options text type', 'currency-switcher') ?>:</label>
    <?php
    $sett = array(
        'code' => esc_html__('code', 'currency-switcher'),
        'desc' => esc_html__('description', 'currency-switcher'),
    );
    ?>
    <select class="widefat" id="<?php echo esc_attr($widget->get_field_id('txt_type')) ?>" name="<?php echo esc_attr($widget->get_field_name('txt_type')) ?>">
        <?php foreach ($sett as $k => $val) : ?>
            <option <?php selected($instance['txt_type'], $k) ?> value="<?php echo esc_attr($k) ?>" class="level-0"><?php echo esc_html($val) ?></option>
        <?php endforeach; ?>
    </select>
    <i><?php esc_html_e('Which text display in the drop-down options - currency code OR description text. Looks good for all dropdowns except ddslick.', 'currency-switcher') ?></i>
</p>

