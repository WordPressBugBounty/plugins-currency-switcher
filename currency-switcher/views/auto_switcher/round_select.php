<?php if (!defined('ABSPATH')) die('No direct access allowed'); 
global $WPCS;
$currencies=apply_filters('wpcs_currency_manipulation_before_show', $WPCS->get_currencies());
?>
<style>
    
.wpcs_auto_switcher.cd-stretchy-nav.nav-is-visible ul a.wpcs_curr_curr {
    color: <?php echo  esc_attr($hover_color)?>;
}
.wpcs_auto_switcher.cd-stretchy-nav.nav-is-visible ul a:hover{
     color: <?php echo  esc_attr($hover_color)?>;
}
.wpcs_auto_switcher.cd-stretchy-nav {
  top: <?php echo  esc_attr($top)?>;
}
.wpcs_auto_switcher.cd-stretchy-nav .stretchy-nav-bg {
  background: <?php echo  esc_attr($color)?>;
}
<?php 
if(stripos($basic_field,"__FLAG__")!== false):?>
.wpcs_auto_switcher .wpcs_base_text{
    top: 30%;   
}
<?php endif ?>
</style>


<nav class="wpcs_auto_switcher  cd-stretchy-nav <?php  echo  esc_attr($side) ?>" data-view="round_select">
    <a class="cd-nav-trigger" href="#">
      <span class="wpcs_current_text"><?php echo  wp_kses_post($this->prepare_field_text($currencies[$WPCS->current_currency],$basic_field)); ?></span> 
    </a>

    <ul>
        <?php
        foreach ($currencies as $key => $item):
        $current = "";
        if($key==$WPCS->current_currency){
        $current = "wpcs_curr_curr";
        }
        $base_text = $this->prepare_field_text($item, $basic_field);
        $add_text = $this->prepare_field_text($item, $add_field);
        ?>  
        <li>
            <a data-currency="<?php echo esc_attr($key) ?>" href="#" class="<?php echo esc_attr($current) ?> wpcs_auto_switcher_link">
               <?php if($side=='left'):?>
                <span class="wpcs_base_text"><?php echo  wp_kses_post($base_text) ?></span>                  
                <span class="wpcs_add_field"> <?php echo  wp_kses_post($add_text) ?> </span>              
                <?php else: ?>
                <span class="wpcs_add_field"> <?php echo  wp_kses_post($add_text) ?> </span>
                <span class="wpcs_base_text"><?php echo  wp_kses_post($base_text) ?></span>
                <?php endif;?>
            </a>
        </li>
        <?php endforeach;?>
    </ul>

    <span aria-hidden="true" class="stretchy-nav-bg"></span>
</nav>