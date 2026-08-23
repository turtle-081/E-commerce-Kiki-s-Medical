<?php

function propharm_enovathemes_child_scripts() {
    wp_enqueue_style( 'propharm_enovathemes-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'propharm_enovathemes_child_scripts' );

/**
 * Slider Revolution: load the v6->v7 migration script, and make the SR7 script
 * load order deterministic.
 *
 * Two upstream problems this works around (revslider 6.7.54):
 *
 * 1. All sliders on this site live in the v6 tables (wp_revslider_sliders /
 *    _slides); the v7 tables are empty. The front end runs the SR7 engine
 *    (revslider-global-settings -> getTec.engine = "SR7"), so the page ships
 *    slider JSON in v6 format and relies on public/js/migration.js to define
 *    SR7.migrate, which translates it at runtime.
 *
 *    RevSliderFront::add_scripts() only enqueues that file when $this->v6_slider
 *    is true (public/revslider-front.class.php:33), but $v6_slider is not set
 *    until load_v7_slider() runs (same file:205), which is called from
 *    includes/output.sr7.class.php:1927 while the slider is being output --
 *    i.e. long after wp_enqueue_scripts has already fired. The flag is therefore
 *    always false at enqueue time and migration.js is never loaded, leaving the
 *    module stuck on its preloader over the background colour.
 *
 * 2. tp-tools and sr7 are both enqueued with 'strategy' => 'async' and an empty
 *    dependency array (same file:28-29), even though sr7.js needs _tpt from
 *    tptools.js. async gives no ordering guarantee, so whichever downloads first
 *    executes first -- an intermittent failure. Declaring the real dependency and
 *    using defer keeps the scripts non-blocking while preserving execution order.
 *
 * Remove this once RevSlider fixes either issue upstream.
 */
function propharm_child_fix_revslider_sr7() {
	$scripts = wp_scripts();

	// Only act when the SR7 front-end engine is actually on the page.
	if ( ! isset( $scripts->registered['sr7'] ) || ! wp_script_is( 'sr7', 'enqueued' ) ) {
		return;
	}

	$ver = defined( 'RS_REVISION' ) ? RS_REVISION : null;
	$url = defined( 'RS_PLUGIN_URL_CLEAN' )
		? RS_PLUGIN_URL_CLEAN
		: plugins_url( '/', WP_PLUGIN_DIR . '/revslider/revslider.php' );

	// 2. Give sr7 its real dependency so execution order is guaranteed.
	if ( isset( $scripts->registered['tp-tools'] ) && ! in_array( 'tp-tools', $scripts->registered['sr7']->deps, true ) ) {
		$scripts->registered['sr7']->deps[] = 'tp-tools';
	}

	// 1. Load the migration layer. It self-guards (returns early if SR7.migrate
	//    is already defined), so it is harmless if RevSlider also enqueues it.
	if ( ! wp_script_is( 'sr7migration', 'enqueued' ) && file_exists( WP_PLUGIN_DIR . '/revslider/public/js/migration.js' ) ) {
		wp_enqueue_script( 'sr7migration', $url . 'public/js/migration.js', array( 'tp-tools', 'sr7' ), $ver, true );
	}

	// async cannot preserve order; defer runs in document order, still non-blocking.
	foreach ( array( 'tp-tools', 'sr7', 'sr7migration' ) as $handle ) {
		if ( isset( $scripts->registered[ $handle ] ) ) {
			$scripts->registered[ $handle ]->extra['strategy'] = 'defer';
		}
	}
}
add_action( 'wp_enqueue_scripts', 'propharm_child_fix_revslider_sr7', 20 );

/**
 * Fix "Warning: Array to string conversion in wp-includes/formatting.php on line 1128"
 * on single product pages.
 *
 * enovathemes-addons disables responsive images like this
 * (enovathemes-addons.php:1858-1859, inside enovathemes_addons_disable_responsive_images(),
 * hooked to init):
 *
 *     add_filter( 'wp_calculate_image_sizes',  '__return_empty_array', PHP_INT_MAX );
 *     add_filter( 'wp_calculate_image_srcset', '__return_empty_array', PHP_INT_MAX );
 *
 * The srcset one is harmless -- that filter really does receive an array of sources, and
 * core turns an empty one into false. But 'wp_calculate_image_sizes' filters a *string*
 * (e.g. "(max-width: 100px) 100vw, 100px"), so returning array() makes
 * wp_get_attachment_image_sizes() hand an array back to WooCommerce, which passes it
 * straight to esc_attr() -> wp_check_invalid_utf8() -> (string) $array.
 * See woocommerce/includes/wc-template-functions.php:1858 (data-thumb-sizes attribute).
 *
 * Swap it for the correctly typed equivalent. Responsive images stay disabled -- the
 * plugin's wp_get_attachment_image_attributes filter already strips sizes/srcset from
 * the markup -- we only change array() to ''.
 *
 * Remove this once enovathemes-addons corrects the callback upstream.
 */
function propharm_child_fix_image_sizes_filter() {
	if ( false !== has_filter( 'wp_calculate_image_sizes', '__return_empty_array' ) ) {
		remove_filter( 'wp_calculate_image_sizes', '__return_empty_array', PHP_INT_MAX );
		add_filter( 'wp_calculate_image_sizes', '__return_empty_string', PHP_INT_MAX );
	}
}
// The plugin registers its filters on init at the default priority, so run just after.
add_action( 'init', 'propharm_child_fix_image_sizes_filter', 11 );

/**
 * Repair add-to-cart URLs in product grids rendered through the theme's AJAX endpoints.
 *
 * WooCommerce builds WC_Product::add_to_cart_url() with add_query_arg(), which falls back
 * to $_SERVER['REQUEST_URI'] when given no base URL — the intent being "stay on the current
 * page and add the product". But the theme loads its product grids and mega-menu panels via
 * its own endpoints (/ajax-api/product-query/<ids>, /ajax-api/megamenu-query/<ids>, see
 * propharm/js/controller.js:4992), so during those requests REQUEST_URI *is* the endpoint.
 * The theme also concatenates $('body').data('url') — which already ends in "/" — with
 * "/ajax-api/...", producing a leading double slash. The result is hrefs like:
 *
 *     //ajax-api/megamenu-query/392406496?add-to-cart=77
 *
 * which a browser reads as protocol-relative and resolves to the non-existent host
 * http://ajax-api/... . Page JS normally intercepts these clicks (the links carry
 * .ajax_add_to_cart) so it usually goes unnoticed, but the href is followed with JS
 * disabled, on middle-click, and on "open in new tab".
 *
 * Rebuild anything that is not an absolute URL on this site from the product permalink.
 */
function propharm_child_fix_add_to_cart_url( $url, $product ) {
	if ( ! $product instanceof WC_Product ) {
		return $url;
	}

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$url_host  = $url ? wp_parse_url( $url, PHP_URL_HOST ) : null;

	// Already an absolute URL pointing at this site — leave it alone.
	if ( $url_host && strtolower( $url_host ) === strtolower( $site_host ) ) {
		return $url;
	}

	// Anything else (relative, protocol-relative, or a foreign host) is rebuilt.
	if ( $product->is_purchasable() && $product->is_in_stock() ) {
		return add_query_arg( 'add-to-cart', $product->get_id(), $product->get_permalink() );
	}

	return $product->get_permalink();
}
add_filter( 'woocommerce_product_add_to_cart_url', 'propharm_child_fix_add_to_cart_url', 10, 2 );
