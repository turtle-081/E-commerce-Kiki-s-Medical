<?php
	propharm_enovathemes_global_variables();
	$product_sidebar        = (isset($GLOBALS['propharm_enovathemes']['product-sidebar']) && $GLOBALS['propharm_enovathemes']['product-sidebar']) ? $GLOBALS['propharm_enovathemes']['product-sidebar'] : "none";
    $product_sidebar_toggle = (isset($GLOBALS['propharm_enovathemes']['product-sidebar-toggle']) && $GLOBALS['propharm_enovathemes']['product-sidebar-toggle'] == 1) ? 'true' : 'false';

	if (is_active_sidebar('shop-widgets') && $product_sidebar  == "none" && !defined('ENOVATHEMES_ADDONS')) {
		$product_sidebar = 'left';
	}

?>
<div class="container et-clearfix">
	<?php if ($product_sidebar == "left"): ?>
		<div class="layout-sidebar product-sidebar et-clearfix">	
			<?php get_sidebar('shop'); ?>
		</div>
		<div class="layout-content product-content et-clearfix">
			<?php if ($product_sidebar != "none"): ?>
				<?php if ($product_sidebar_toggle == "false"): ?>
					<a href="#" title="<?php echo esc_attr__("Toggle sidebar","propharm"); ?>" class="content-sidebar-toggle"><?php echo propharm_enovathemes_svg_icon('filter'); ?></a>
				<?php endif ?>
				<div class="product-filter-overlay"></div>
			<?php endif ?>
			<?php woocommerce_content(); ?>
		</div>
	<?php elseif ($product_sidebar == "right"): ?>
		<div class="layout-content product-content et-clearfix">
			<?php if ($product_sidebar != "none"): ?>
				<?php if ($product_sidebar_toggle == "false"): ?>
					<a href="#" title="<?php echo esc_attr__("Toggle sidebar","propharm"); ?>" class="content-sidebar-toggle"><?php echo propharm_enovathemes_svg_icon('filter'); ?></a>
				<?php endif ?>
				<div class="product-filter-overlay"></div>
			<?php endif ?>
			<?php woocommerce_content(); ?>
		</div>
		<div class="layout-sidebar product-sidebar et-clearfix">
			<?php get_sidebar('shop'); ?>
		</div>
	<?php else: ?>
		<?php woocommerce_content(); ?>
	<?php endif ?>
</div>
