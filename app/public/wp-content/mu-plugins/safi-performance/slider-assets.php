<?php
/**
 * Phase 6.1 — stop Slider Revolution loading on pages that have no slider.
 *
 * Slider Revolution ships `tptools.js` (73 KB) and `sr7.js` (94 KB) plus
 * `migration.js` and `sr7.css` on **every** page of this site, including
 * `/cart/`. Together they are the single largest block of JavaScript in the
 * payload — larger than jQuery, the theme's combined plugin bundle and
 * WooCommerce put together.
 *
 * Exactly one page uses a slider: the front page (#373, "Home 5"), which
 * renders Slider 5. Everywhere else the scripts load, run, and build nothing.
 *
 * Worth recording how that was established, because the obvious check gives the
 * wrong answer. Slider Revolution 6.7 builds its markup **client-side** — the
 * server sends no `<rs-module>` element, and the `SR7-MODULE` node only exists
 * after `sr7.js` runs. Parsing the HTML for slider elements therefore reports
 * zero sliders on every page including the homepage, which would have made
 * dequeuing everywhere look safe. The homepage was confirmed to genuinely use a
 * slider by inspecting the live DOM (`SR7-MODULE#SR7_5_1`, `window.revapi5`),
 * not the source.
 *
 * So detection here is based on the shortcode in the content, which is what
 * actually drives the render, rather than on the rendered output.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Markers that mean "this content renders a slider".
 *
 * Covers the classic shortcode, the WPBakery element and the block.
 */
function safi_slider_markers() {
	return array(
		'[rev_slider',
		'rev_slider_vc',
		'revslider',
		'wp:themepunch/revslider',
		'sr7-module',
	);
}

/**
 * Does the current request render a Slider Revolution slider?
 */
function safi_page_needs_revslider() {
	// Never strip assets in the admin or the WPBakery editor frame, where the
	// slider element has to be editable.
	if ( is_admin() ) {
		return true;
	}

	$content = '';

	$post = get_post();
	if ( $post instanceof WP_Post ) {
		$content .= $post->post_content;
	}

	/*
	 * The header and footer are built from their own post types on this theme,
	 * and either can embed a slider. They are cheap to read and would otherwise
	 * be a silent way for this check to be wrong.
	 */
	foreach ( array( 'header', 'footer' ) as $builder_type ) {
		$ids = get_posts(
			array(
				'post_type'              => $builder_type,
				'post_status'            => 'publish',
				'posts_per_page'         => 20,
				'fields'                 => 'ids',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);
		foreach ( $ids as $id ) {
			$content .= get_post_field( 'post_content', $id );
		}
	}

	foreach ( safi_slider_markers() as $marker ) {
		if ( false !== stripos( $content, $marker ) ) {
			return true;
		}
	}

	/**
	 * Escape hatch, so a template that injects a slider in a way this cannot see
	 * can force the assets back on.
	 *
	 * @param bool $needs_revslider Whether Slider Revolution assets are required.
	 */
	return (bool) apply_filters( 'safi_page_needs_revslider', false );
}

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( safi_page_needs_revslider() ) {
			return;
		}

		foreach ( array( 'tp-tools', 'sr7', 'sr7migration' ) as $handle ) {
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}

		foreach ( array( 'sr7css', 'rs-font-awesome', 'rs-icons' ) as $handle ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}
	},
	// Late, so Slider Revolution has definitely enqueued before this runs.
	PHP_INT_MAX
);
