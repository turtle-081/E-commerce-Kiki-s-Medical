<?php
/**
 * Stop lazy-loading the main product image.
 *
 * Phase 8. WooCommerce marks the single product page's gallery image
 * `loading="lazy"`, and it is both the largest element on the page and the one
 * the customer came to look at. Lighthouse names it as a cause of the
 * intermittent 0.114 layout shift on that page ("media element lacking an
 * explicit size"), and lazy-loading the LCP element is a well-known way to
 * delay LCP for no benefit.
 *
 * WHY THIS IS NOT THE PHASE 6.5 MISTAKE
 *
 * 6.5 failed because it *applied a heuristic* -- core's "the first large image
 * in source order is the LCP" -- to a theme where that is false, and so
 * lazy-loaded the element that actually paints. The direction matters: this
 * makes a specific, known element eager, and eagerness cannot delay anything.
 *
 * There is no guessing here. The target is identified structurally, not
 * positionally: `is_product()`, the attachment is that product's own featured
 * image, and the tag carries WooCommerce's `wp-post-image` class. Nothing else
 * on the site is touched, and only the first match is promoted, so a gallery
 * with five images still lazy-loads the four the customer has not scrolled to.
 *
 * Delete this file to revert. See ROLLBACK.md.
 */

defined( 'ABSPATH' ) || exit;

/*
 * Rewrite the finished tag rather than the attribute array.
 *
 * `wp_get_attachment_image_attributes` looks like the right hook and is not:
 * unsetting `loading` there did not survive to the output, while a `decoding`
 * set in the same callback did, so something downstream reinstates it. Setting
 * `fetchpriority` in the array also produced the attribute *twice* in the
 * markup. `wp_get_attachment_image` receives the assembled tag and is the last
 * word, and WP_HTML_Tag_Processor cannot produce a duplicate attribute.
 */
add_filter(
	'wp_get_attachment_image',
	static function ( $html, $attachment_id ) {
		static $promoted = false;

		if ( $promoted || is_admin() || ! $html ) {
			return $html;
		}

		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return $html;
		}

		// This product's own featured image, not a related product's thumbnail
		// that happens to reuse the class.
		if ( (int) $attachment_id !== (int) get_post_thumbnail_id( get_queried_object_id() ) ) {
			return $html;
		}

		$tags = new WP_HTML_Tag_Processor( $html );
		if ( ! $tags->next_tag( array( 'tag_name' => 'IMG' ) ) ) {
			return $html;
		}

		$class = (string) $tags->get_attribute( 'class' );
		if ( ! str_contains( $class, 'wp-post-image' ) ) {
			return $html;
		}

		$promoted = true;

		/*
		 * `loading="eager"`, not `remove_attribute( 'loading' )`.
		 *
		 * A later core pass runs over this tag and fills in whatever loading
		 * attributes are missing, so simply removing the attribute achieves
		 * nothing -- core puts `lazy` straight back, which is what the first
		 * version of this file did. An explicit value is what core treats as
		 * already decided and leaves alone.
		 *
		 * `fetchpriority` is deliberately *not* set here. Core adds it once it
		 * sees an eager image, and setting it as well produced the attribute
		 * twice in the markup.
		 */
		$tags->set_attribute( 'loading', 'eager' );
		$tags->set_attribute( 'decoding', 'sync' );

		return $tags->get_updated_html();
	},
	PHP_INT_MAX,
	2
);
