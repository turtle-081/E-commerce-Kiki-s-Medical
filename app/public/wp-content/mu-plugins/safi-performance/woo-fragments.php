<?php
/**
 * Phase 4.1 / 4.2 — stop cart fragments defeating the page cache.
 *
 * WooCommerce enqueues `wc-cart-fragments` on every page. It fires
 * `?wc-ajax=get_refreshed_fragments` on each load, which is a POST-like
 * uncached request to PHP whose entire job is to tell the header how many items
 * are in the cart. With a full-page cache in front, that request is the single
 * biggest remaining source of uncached PHP per page view.
 *
 * Removing it means the cached HTML always shows an empty cart, because the
 * cached copy was generated for an anonymous visitor. 4.2 fixes that by
 * hydrating the count client-side — but only for visitors who actually have a
 * cart, which is a small minority. Everyone else makes zero extra requests.
 */

defined( 'ABSPATH' ) || exit;

/**
 * 4.1 — drop the fragments script everywhere except cart and checkout, where
 * live totals genuinely matter and the pages are uncached anyway.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! function_exists( 'is_cart' ) ) {
			return;
		}
		if ( is_cart() || is_checkout() ) {
			return;
		}
		wp_dequeue_script( 'wc-cart-fragments' );
		wp_deregister_script( 'wc-cart-fragments' );
	},
	100
);

/**
 * 4.2 — hydrate the cart count on demand.
 *
 * The script is inlined rather than shipped as a file: it is ~1 KB, and an
 * extra HTTP request to save a request would be self-defeating.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! function_exists( 'is_cart' ) ) {
			return;
		}
		if ( is_cart() || is_checkout() ) {
			return; // fragments still run here
		}

		// Attach to a handle that is always present so ordering is predictable.
		$handle = wp_script_is( 'jquery-core', 'enqueued' ) ? 'jquery-core' : 'jquery';
		if ( ! wp_script_is( $handle, 'enqueued' ) && ! wp_script_is( $handle, 'registered' ) ) {
			wp_register_script( 'safi-mini-cart', '', array(), null, true );
			wp_enqueue_script( 'safi-mini-cart' );
			$handle = 'safi-mini-cart';
		}

		wp_add_inline_script( $handle, safi_mini_cart_js() );
	},
	110
);

/**
 * The hydration script.
 *
 * The cookie check first is the whole point: WooCommerce sets
 * `woocommerce_items_in_cart` only once something is in the cart, so a visitor
 * browsing the shop never touches the network for this.
 */
function safi_mini_cart_js() {
	$endpoint = esc_url_raw( rest_url( 'wc/store/v1/cart' ) );

	return <<<JS
(function () {
	var ENDPOINT = '{$endpoint}';

	function targets() {
		// The theme renders the badge as .cart-contents; [data-cart-count] is
		// supported too so a future template can opt in without changing this.
		return document.querySelectorAll('[data-cart-count], .cart-contents');
	}

	function paint(count) {
		targets().forEach(function (el) {
			el.textContent = count > 0 ? String(count) : '';
			el.hidden = count === 0;
		});
	}

	function hasCartCookie() {
		return /(^|;\\s*)woocommerce_items_in_cart=1/.test(document.cookie);
	}

	function refresh() {
		if (!hasCartCookie()) {
			paint(0);
			return;
		}
		fetch(ENDPOINT, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
			.then(function (r) { return r.ok ? r.json() : null; })
			.then(function (cart) {
				if (cart && typeof cart.items_count === 'number') { paint(cart.items_count); }
			})
			.catch(function () { /* leave the cached value alone */ });
	}

	// Archive add-to-cart is AJAX, so re-read the count once Woo says it is done.
	//
	// This has to wait for DOM ready: the handle this script is attached to is
	// enqueued in the head, so document.body is still null when it first runs.
	// Touching it directly here threw a TypeError that aborted the rest of the
	// script, and took the jQuery binding below with it.
	function bind() {
		refresh();
		if (!document.body) { return; }
		document.body.addEventListener('added_to_cart', refresh);
		if (window.jQuery) {
			window.jQuery(document.body).on('added_to_cart removed_from_cart', refresh);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', bind);
	} else {
		bind();
	}
})();
JS;
}
