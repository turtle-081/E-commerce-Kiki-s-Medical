<?php
	propharm_enovathemes_global_variables();
	$shop_sidebar = (isset($GLOBALS['propharm_enovathemes']['shop-sidebar']) && $GLOBALS['propharm_enovathemes']['shop-sidebar']) ? $GLOBALS['propharm_enovathemes']['shop-sidebar'] : "right";
	$data_shop  = (isset($_GET["data_shop"]) && !empty($_GET["data_shop"])) ? $_GET["data_shop"] : "default";
?>

<?php if ($data_shop != 'default' && has_action('efp_filter_demo')): ?>
	<?php do_action('efp_filter_demo',$data_shop,$shop_sidebar); ?>
<?php else: ?>
	<?php if (is_active_sidebar('shop-widgets')): ?>
		<aside class='shop-widgets widget-area'>  
			<?php if ($shop_sidebar != "none"): ?>
				<a href="#" title="<?php echo esc_attr__("Toggle sidebar","propharm"); ?>" class="content-sidebar-toggle active"><?php echo propharm_enovathemes_svg_icon('close'); ?></a>
			<?php endif ?>
			<?php if ( function_exists( 'dynamic_sidebar' )){dynamic_sidebar('shop-widgets');} ?>
		</aside>
	<?php endif ?>
<?php endif ?>	
