<?php
/**
 * Phase 7 — stop the theme's caches being autoloaded on every request.
 *
 * Discovery found 2.2 MB of autoloaded options. The heaviest by far were the
 * theme's own product-grid caches:
 *
 *     _transient_et_products__KESgridarrowstopfalsetru…   211.5 KB
 *     _transient_et_products__USDgridarrowstopfalsetru…   211.3 KB
 *     …a dozen more in the 70–140 KB range
 *
 * Cause: WordPress stores a transient created with an expiry of 0 as a normal
 * autoloaded option. enovathemes-addons creates every one of its caches that
 * way — `set_transient( $key, $value, apply_filters( 'null_*_cache_time', 0 ) )`
 * — so all of them load into memory on every request, including wp-admin and
 * every AJAX call, whether or not the page renders a product grid.
 *
 * Fix: the plugin exposes a filter for each expiry, so returning a real TTL is
 * enough. Any non-zero expiry makes WordPress store the transient un-autoloaded.
 * No vendor code is touched.
 *
 * The TTLs are deliberately long. Every one of these caches is already purged
 * explicitly when the underlying content changes (product save, menu save, theme
 * options save), so the expiry is a backstop rather than the real invalidation
 * mechanism. Long TTLs keep the rebuild cost — around 2 s of bootstrap for the
 * layout caches — as rare as it was before.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Layout and asset caches: expensive to rebuild, invalidated on save.
 */
foreach ( array(
	'null_headers_cache_time',
	'null_footers_cache_time',
	'null_megamenu_cache_time',
	'null_banners_cache_time',
	'null_title_sections_cache_time',
	'null_icons_cache_time',
	'null_icon_cache_time',
	'null_social_cache_time',
	'null_categories_cache_time',
	'null_filter_cache_time',
	'null_product_filter_cache_time',
	'null_dynamic_css_cache_time',
) as $safi_filter ) {
	add_filter(
		$safi_filter,
		function ( $ttl ) {
			// Only override the "never expire" default; respect any explicit value.
			return ( 0 === (int) $ttl ) ? WEEK_IN_SECONDS : $ttl;
		}
	);
}
unset( $safi_filter );

/**
 * Query result caches: cheaper to rebuild and more likely to go stale (stock,
 * price, publish status), so a shorter backstop.
 */
foreach ( array(
	'null_product_query_cache_time',
	'null_post_query_cache_time',
) as $safi_filter ) {
	add_filter(
		$safi_filter,
		function ( $ttl ) {
			return ( 0 === (int) $ttl ) ? DAY_IN_SECONDS : $ttl;
		}
	);
}
unset( $safi_filter );
