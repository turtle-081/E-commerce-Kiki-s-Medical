<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

	<?php

		propharm_enovathemes_global_variables();

	    $product_gap            = "false";
		$product_single_sidebar = (isset($GLOBALS['propharm_enovathemes']['product-single-sidebar']) && $GLOBALS['propharm_enovathemes']['product-single-sidebar']) ? $GLOBALS['propharm_enovathemes']['product-single-sidebar'] : "none";
	    $class = array();

	    $size = 'medium';

	    if ($product_single_sidebar != "none") {
	        $class[] = 'sidebar-active';
	        $size = 'large';
	    }

	    $class[] = 'post-layout';
	    $class[] = 'product-layout';
	    $class[] = $size;
	    $class[] = 'gap-'.$product_gap;
	    $class[] = 'layout-sidebar-'.$product_single_sidebar;

	?>

	<div class="related-products">

		<section class="up-sells upsells grid products <?php echo implode(' ', $class); ?>">

			<h4><?php esc_html_e( 'You may also like&hellip;', 'enovathemes-addons' ) ?></h4>

			<?php woocommerce_product_loop_start(); ?>

				<?php foreach ( $upsells as $upsell ) : ?>

					<?php
						$post_object = get_post( $upsell->get_id() );

						setup_postdata( $GLOBALS['post'] =& $post_object );

						include(ENOVATHEMES_ADDONS.'woocommerce/content-product.php');
					?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>

		</section>

	</div>

<?php endif;

wp_reset_postdata();
