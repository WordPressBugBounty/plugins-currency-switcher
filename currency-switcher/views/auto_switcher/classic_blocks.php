<?php if (!defined('ABSPATH')) die('No direct access allowed'); 
global $WPCS;
$currencies=apply_filters('wpcs_currency_manipulation_before_show', $WPCS->get_currencies());
?>
<style>
.wpcs_auto_switcher {
    top: <?php echo  esc_attr($top)?>;
} 
.wpcs_auto_switcher li a {
  background:<?php echo  esc_attr($color)?>;
}
.wpcs_auto_switcher li a.wpcs_curr_curr {
 background:<?php echo  esc_attr($hover_color)?>;
}
.wpcs_auto_switcher li  a:hover {
    background:<?php echo  esc_attr($hover_color)?>;
}
.wpcs_auto_switcher li  a span {
    background:<?php echo  esc_attr($hover_color)?>;
}
</style>

<ul class='wpcs_auto_switcher <?php  echo  esc_attr($side)?>' data-view="classic_blocks">
    <?php foreach ($currencies as $key=>$item):
    $current="";
    if($key==$WPCS->current_currency){
        $current="wpcs_curr_curr";
    }
    $base_text=$this->prepare_field_text($item,$basic_field);
    $add_text=$this->prepare_field_text($item,$add_field);
        ?>  
  <li>
    <a data-currency="<?php echo esc_attr($key) ?>" class="  <?php echo  esc_attr($current)?> wpcs_auto_switcher_link" href="#"> 
		<?php echo  wp_kses_post($base_text) ?> 
		<span><?php echo  wp_kses_post($add_text) ?> </span>
    </a> 
  </li>
    <?php endforeach;?>
  
</ul>
