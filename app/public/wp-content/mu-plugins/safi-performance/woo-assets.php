<?php
/**
 * Phase 4.3 / 4.4 — stop WooCommerce loading where it is not needed.
 *
 * The brief warns that `is_woocommerce()` returns false on a page that merely
 * *displays* products, and that dequeuing there breaks add-to-cart. That is
 * exactly this site: the homepage carries product carousels and has 35
 * add-to-cart links, and several other pages pull product grids in through the
 * theme's own shortcodes.
 *
 * So the check is not "is this a WooCommerce page" but "will this page render
 * any products". That is answered by looking for the theme's product shortcodes
 * in the post content, which is cheap and precise, rather than by maintaining a
 * hand-written page allowlist that will rot.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Does the current request need WooCommerce front-end assets?
 */
function safi_page_needs_woo() {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return true; // Woo not loaded; nothing to dequeue anyway
	}

	// The obvious cases.
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		return true;
	}

	// Anything with a product in the loop (search results, some archives).
	if ( is_search() || is_post_type_archive( 'product' ) || is_tax( get_object_taxonomies( 'product' ) ) ) {
		return true;
	}

	$post = get_post();
	if ( $post instanceof WP_Post ) {
		// Theme and WooCommerce shortcodes that render products, plus the
		// blocks WooCommerce ships.
		$markers = array(
			'et_woo_products',
			'et_product',
			'et_cart_toggle',
			'[products',
			'[product_',
			'[add_to_cart',
			'[woocommerce_',
			'wp:woocommerce/',
		);
		foreach ( $markers as $marker ) {
			if ( false !== stripos( $post->post_content, $marker ) ) {
				return true;
			}
		}
	}

	/**
	 * Last word, so a template that renders products in a way this cannot see
	 * can force the assets back on.
	 */
	return (bool) apply_filters( 'safi_page_needs_woo', false );
}

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( safi_page_needs_woo() ) {
			return;
		}

		foreach ( array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'wc-blocks-style', 'brands-styles' ) as $handle ) {
			wp_dequeue_style( $handle );
		}

		foreach ( array( 'woocommerce', 'wc-add-to-cart', 'js-cookie', 'wc-cart-fragments', 'sourcebuster-js', 'wc-order-attribution' ) as $handle ) {
			wp_dequeue_script( $handle );
		}
	},
	100
);

/* -------------------------------------------------------------------------
 * 4.4 — background load
 * ---------------------------------------------------------------------- */

// Marketplace suggestions and background image regeneration are pure overhead
// for this store; neither affects anything the client uses.
add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false' );
add_filter( 'woocommerce_background_image_regeneration', '__return_false' );

/**
 * NOT enabled: `woocommerce_admin_disabled`.
 *
 * The brief says to confirm before turning WC Admin off because some clients
 * rely on the analytics dashboard, and that confirmation has not been given.
 * It is the single biggest remaining admin-side win, so it is worth asking.
 *
 * To enable, uncomment:
 *
 *     add_filter( 'woocommerce_admin_disabled', '__return_true' );
 */
