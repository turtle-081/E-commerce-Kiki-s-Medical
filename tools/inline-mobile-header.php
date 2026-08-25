<?php
/**
 * Phase 4.6 — render the mobile header inline instead of over AJAX.
 *
 * `et_header_mobile_container` has an `async` attribute. When true the shortcode
 * emits an empty container and the theme fetches the contents through
 * admin-ajax (`mobile_load`) on every page view. Same reasoning as
 * tools/inline-header-megamenu.php: with a full-page cache the inline copy is
 * free, the AJAX copy is an uncached PHP request every time.
 *
 * Scoped to the `header` post type so it cannot touch page or product content.
 *
 * Usage:  php tools/inline-mobile-header.php [--revert]
 */

require dirname( __DIR__ ) . '/app/public/wp-load.php';

const META_BACKUP = '_safi_mobile_async_backup';

$revert = in_array( '--revert', $argv, true );

global $wpdb;

if ( $revert ) {
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s", META_BACKUP ) );
	foreach ( $ids as $id ) {
		$original = get_post_meta( $id, META_BACKUP, true );
		if ( '' === $original ) { continue; }
		wp_update_post( array( 'ID' => $id, 'post_content' => $original ) );
		delete_post_meta( $id, META_BACKUP );
		printf( "  reverted #%s (%s)\n", $id, get_the_title( $id ) );
	}
	if ( ! $ids ) { echo "  nothing to revert\n"; }
	exit;
}

$posts = $wpdb->get_results( "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = 'header' AND post_status = 'publish'" );

$changed = 0;
foreach ( $posts as $post ) {
	if ( false === strpos( $post->post_content, 'et_header_mobile_container' ) ) {
		continue;
	}

	// Only rewrite `async` inside the mobile container tag, never elsewhere.
	$updated = preg_replace_callback(
		'/\[et_header_mobile_container\b[^\]]*\]/',
		function ( $m ) {
			return str_replace( 'async="true"', 'async="false"', $m[0] );
		},
		$post->post_content
	);

	if ( $updated === $post->post_content ) {
		printf( "  #%-6s %-24s already inline\n", $post->ID, get_the_title( $post->ID ) );
		continue;
	}

	if ( '' === (string) get_post_meta( $post->ID, META_BACKUP, true ) ) {
		update_post_meta( $post->ID, META_BACKUP, $post->post_content );
	}

	wp_update_post( array( 'ID' => $post->ID, 'post_content' => $updated ) );
	printf( "  #%-6s %-24s mobile header now inline\n", $post->ID, get_the_title( $post->ID ) );
	$changed++;
}

printf( "  %d header(s) changed\n", $changed );
