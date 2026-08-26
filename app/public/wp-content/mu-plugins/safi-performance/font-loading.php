<?php
/**
 * Phase 6.2 — stop the web font reflowing the header.
 *
 * This is the cause of the intermittent full-viewport layout shift documented in
 * Phase 4, and it took a trace to find. Lighthouse's `unsized-images` audit
 * passes and every image has its box reserved by CSS, so the usual suspect was
 * ruled out. The trace tells a different story:
 *
 *     node 124  [0, 64, 412, 759]  ->  [0, 128, 412, 695]   page content
 *     node 121  [324, 0, 32, 64]   ->  [343, 64, 32, 64]    header icon wraps
 *
 * The header is not growing because something loaded into it. It is growing
 * because its contents **re-wrap onto a second row**, doubling it from 64 px to
 * 128 px and pushing the entire page down by 64 px.
 *
 * What re-wraps them is the font. The theme loads PT Sans from Google Fonts with
 * `display=swap`, and the font files are not preloaded — only the stylesheet is.
 * So the header first renders in a fallback face, the real font arrives ~150 ms
 * later, every string in the header changes width, and the row wraps.
 *
 * `swap` is the right default for body copy, where a reflow is invisible. It is
 * the wrong default for a fixed-height header bar, where it moves the whole page.
 *
 * `optional` fixes it properly rather than papering over it: the browser gives
 * the font a very short window, and if it does not arrive in time it keeps the
 * fallback **for that page view and does not swap**. There is no second layout,
 * so there is no shift. The font is still cached for every subsequent view, and
 * with the Phase 2 page cache and Phase 5 prerendering, subsequent views are the
 * common case.
 *
 * The alternative — preloading the woff2 files directly — was rejected because
 * Google serves them from hashed, versioned URLs that would silently rot into
 * dead preloads.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Rewrite `display=swap` to `display=optional` on Google Fonts stylesheets.
 *
 * Done on the URL rather than by re-registering the style, so it keeps working
 * if the theme changes which families it asks for.
 */
function safi_font_display_optional( $src ) {
	if ( ! is_string( $src ) || '' === $src ) {
		return $src;
	}

	if ( false === strpos( $src, 'fonts.googleapis.com' ) ) {
		return $src;
	}

	if ( false !== strpos( $src, 'display=' ) ) {
		return preg_replace( '/([?&]display=)[^&]*/', '${1}optional', $src );
	}

	return add_query_arg( 'display', 'optional', $src );
}

add_filter( 'style_loader_src', 'safi_font_display_optional', 20 );

/**
 * Preconnect to the font host.
 *
 * The stylesheet comes from fonts.googleapis.com but the font files come from
 * fonts.gstatic.com, which is a separate origin and therefore a separate DNS +
 * TLS handshake that cannot start until the stylesheet has been parsed. Under
 * `optional` that delay is the difference between the brand font being used on
 * the first view and being skipped, so it matters more here than it usually
 * would.
 *
 * `crossorigin` is required: font fetches are CORS requests, and a preconnect
 * without it opens a connection the font fetch cannot reuse.
 */
add_filter(
	'wp_resource_hints',
	function ( $urls, $relation_type ) {
		if ( 'preconnect' !== $relation_type ) {
			return $urls;
		}

		// Drop any existing non-crossorigin entry for the font host so it is not
		// requested twice on two different connections.
		$urls = array_values(
			array_filter(
				$urls,
				function ( $u ) {
					$href = is_array( $u ) ? ( $u['href'] ?? '' ) : $u;
					return false === strpos( (string) $href, 'fonts.gstatic.com' );
				}
			)
		);

		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);

		return $urls;
	},
	10,
	2
);
