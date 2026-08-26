<?php
/**
 * Phase 5 — instant navigation via the Speculation Rules API.
 *
 * WordPress 6.8+ ships speculative loading in core, and it is already active on
 * this site — but at its most timid setting: `prefetch` with `conservative`
 * eagerness, which only fires on pointerdown, i.e. after the visitor has already
 * committed to the click. That saves a little latency; it does not feel instant.
 *
 * This raises it to `prerender` / `moderate`: Chrome renders the whole next page
 * in the background once a link has been hovered for ~200 ms, so the navigation
 * itself is close to zero. Combined with the Phase 2 page cache the server cost
 * is a cached HIT (~30 ms), which is what makes this affordable at all.
 *
 * The important half of this file is the exclusions. Core excludes wp-admin,
 * wp-*.php and anything with a query string, but it knows nothing about
 * WooCommerce, and WooCommerce registers no exclusions of its own (checked).
 * Prerendering a store without them is genuinely unsafe.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Raise core's default from prefetch/conservative to prerender/moderate.
 *
 * Core already returns null for logged-in users and for sites without pretty
 * permalinks, so this only ever applies to the anonymous, cacheable case — the
 * same audience the page cache serves.
 */
add_filter(
	'wp_speculation_rules_configuration',
	function ( $config ) {
		// Respect core's decision to disable entirely; only change the settings
		// when it has already decided speculation is appropriate.
		if ( null === $config ) {
			return $config;
		}

		$config['mode']      = 'prerender';
		$config['eagerness'] = 'moderate';

		return $config;
	}
);

/**
 * Paths that must never be speculatively loaded.
 *
 * Page slugs are resolved through WooCommerce rather than hardcoded, so renaming
 * the cart page in settings does not silently reopen the hole.
 */
add_filter(
	'wp_speculation_rules_href_exclude_paths',
	function ( $paths, $mode ) {
		$exclude = array();

		if ( function_exists( 'wc_get_page_id' ) ) {
			foreach ( array( 'cart', 'checkout', 'myaccount' ) as $wc_page ) {
				$page_id = wc_get_page_id( $wc_page );
				if ( $page_id > 0 ) {
					$path = wp_parse_url( get_permalink( $page_id ), PHP_URL_PATH );
					if ( $path ) {
						$path      = '/' . trim( $path, '/' );
						$exclude[] = $path;
						$exclude[] = $path . '/*'; // account endpoints, order-received, order-pay
					}
				}
			}
		}

		// Belt and braces: the defaults, in case the pages are unset or the
		// permalinks have not been flushed.
		$exclude[] = '/cart';
		$exclude[] = '/cart/*';
		$exclude[] = '/checkout';
		$exclude[] = '/checkout/*';
		$exclude[] = '/my-account';
		$exclude[] = '/my-account/*';

		/*
		 * Under `prerender` the page actually executes: scripts run, the Store
		 * API is called, WooCommerce starts a session. That is fine for a product
		 * or archive page and wrong for anything transactional, so these stay
		 * excluded in both modes rather than only under prerender.
		 *
		 * $mode is unused for that reason, but kept in the signature because the
		 * distinction matters if this is ever revisited.
		 */
		unset( $mode );

		return array_merge( (array) $paths, $exclude );
	},
	10,
	2
);

/**
 * Belt-and-braces on the markup side.
 *
 * Core supports a `.no-prefetch` opt-out selector. Add-to-cart links carry a
 * query string and so are already excluded by core, but the theme's AJAX
 * add-to-cart buttons and any "clear cart" style links are worth marking
 * explicitly — a link that mutates state should never be speculatively followed,
 * whatever its URL shape happens to be.
 */
add_action(
	'wp_head',
	function () {
		if ( is_admin() || is_user_logged_in() ) {
			return;
		}
		?>
<script>
(function () {
	var SELECTOR = 'a.add_to_cart_button, a.ajax_add_to_cart, a.remove_from_cart_button, a.et-add-to-cart, a[href*="add-to-cart="], a[href*="remove_item="], a[href*="customer-logout"]';
	function mark(root) {
		(root || document).querySelectorAll(SELECTOR).forEach(function (a) {
			a.classList.add('no-prefetch');
		});
	}
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { mark(); });
	} else {
		mark();
	}
	// Product grids are injected after load on some templates.
	document.addEventListener('et_ajax_loaded', function () { mark(); });
	if (window.jQuery) { window.jQuery(document.body).on('added_to_cart updated_wc_div', function () { mark(); }); }
})();
</script>
		<?php
	},
	5
);
