<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

propharm_enovathemes_global_variables();

$product_navigation = (isset($GLOBALS['propharm_enovathemes']['product-navigation']) && $GLOBALS['propharm_enovathemes']['product-navigation']) ? $GLOBALS['propharm_enovathemes']['product-navigation'] : "pagination";
$display            = woocommerce_get_loop_display_mode();

$class   = array();
$class[] = 'loop-posts';
$class[] = 'loop-products';
$class[] = $display;
$class[] = 'nav-'.$product_navigation;

?>
<ul id="loop-products" class="<?php echo esc_attr(implode(' ', $class)); ?>" data-nav="<?php echo esc_attr($product_navigation); ?>">
