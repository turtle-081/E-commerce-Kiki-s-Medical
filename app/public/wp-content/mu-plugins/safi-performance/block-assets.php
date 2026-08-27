<?php
/**
 * Drop the block-editor stylesheets on pages that contain no blocks.
 *
 * Phase 6.9. This site runs Classic Editor with WPBakery and Elementor; its
 * pages are built from shortcodes and builder markup, not blocks. WordPress
 * enqueues the block library stylesheet unconditionally all the same, and
 * WooCommerce adds its own block styles alongside it.
 *
 * Measured on the homepage: 0 elements carrying a `wp-block-*` class, and
 * `wp-block-library` still loaded at 17 KB and render-blocking. So this is
 * 17 KB and one fewer round trip in the critical path.
 *
 * WHY `wc-blocks-style` IS NOT IN THE LIST
 *
 * It looks like an obvious companion and it is not. WooCommerce enqueues it
 * from `get_notices_template()` (src/Blocks/Domain/Services/Notices.php:116),
 * a `wc_get_template` filter that fires while templates render -- far too late
 * for a `wp_enqueue_scripts` dequeue to catch, which is how the first version
 * of this file appeared to work while quietly doing nothing. More to the
 * point, it *is* used: WooCommerce renders its notices ("added to cart",
 * validation errors) with block-based templates on classic pages, so removing
 * it would leave the transactional flow's messaging unstyled. Not worth 3 KB.
 *
 * WHY THIS IS GATED RATHER THAN UNCONDITIONAL
 *
 * "The site does not use blocks" is true today and is exactly the kind of
 * statement that stops being true the moment someone adds a block to one post.
 * So rather than trusting it, every request checks: if the content actually
 * being rendered contains a block delimiter, the stylesheets stay.
 *
 * `has_blocks()` is a substring test for `<!-- wp:` against content already in
 * memory, so checking every post in the loop costs nothing measurable.
 *
 * Cart, checkout and my-account are exempt unconditionally. If any of those is
 * ever switched to WooCommerce's Cart or Checkout block, removing the block
 * styles would break the one flow on this site that must not break, and the
 * saving there is worthless anyway because those pages bypass the page cache.
 *
 * Delete this file to revert. See ROLLBACK.md.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function () {
		if ( is_admin() ) {
			return;
		}

		// The transactional flow keeps its styles, no questions asked.
		if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
			return;
		}

		if ( safi_page_uses_blocks() ) {
			return;
		}

		foreach ( array( 'wp-block-library', 'wp-block-library-theme' ) as $handle ) {
			wp_dequeue_style( $handle );
		}
	},
	// After everything has registered, so there is something to dequeue.
	100
);

/**
 * Does anything being rendered on this request contain block markup?
 *
 * @return bool
 */
function safi_page_uses_blocks() {
	if ( is_singular() ) {
		return has_blocks( get_queried_object_id() );
	}

	// An archive renders many posts; any one of them is enough to keep the CSS.
	global $wp_query;
	if ( ! empty( $wp_query->posts ) ) {
		foreach ( $wp_query->posts as $post ) {
			if ( has_blocks( $post ) ) {
				return true;
			}
		}
	}

	return safi_active_block_widget_exists();
}

/**
 * Is there a block widget actually placed in a sidebar?
 *
 * Widgets can hold blocks even when no post does, but only ones that are going
 * to be rendered matter. WordPress ships five default block widgets (search,
 * group, heading, latest-posts, latest-comments, archives, categories) and on
 * this install all five sit in `wp_inactive_widgets`, where nothing displays
 * them. Testing `widget_block` alone treats those as a reason to keep 20 KB of
 * CSS on every page, which is how the shop page kept its block stylesheet on
 * the first attempt.
 *
 * @return bool
 */
function safi_active_block_widget_exists() {
	$sidebars = get_option( 'sidebars_widgets' );
	if ( ! is_array( $sidebars ) ) {
		return false;
	}

	$widgets = (array) get_option( 'widget_block' );
	if ( ! $widgets ) {
		return false;
	}

	foreach ( $sidebars as $sidebar_id => $widget_ids ) {
		// `wp_inactive_widgets` is the holding pen; `array_version` is bookkeeping.
		if ( 'wp_inactive_widgets' === $sidebar_id || ! is_array( $widget_ids ) ) {
			continue;
		}

		foreach ( $widget_ids as $widget_id ) {
			if ( ! str_starts_with( (string) $widget_id, 'block-' ) ) {
				continue;
			}

			$index = (int) substr( (string) $widget_id, 6 );
			if ( ! empty( $widgets[ $index ]['content'] ) && has_blocks( $widgets[ $index ]['content'] ) ) {
				return true;
			}
		}
	}

	return false;
}
