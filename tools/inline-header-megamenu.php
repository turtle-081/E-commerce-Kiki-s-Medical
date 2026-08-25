<?php
/**
 * Phase 4.5 — render the header megamenu inline instead of over AJAX.
 *
 * The theme's header-builder button carries megamenu_ajax="true", which defers a
 * ~62 KB megamenu to an admin-ajax POST on every single page view. That is a
 * sensible default with no page cache: it keeps the initial HTML small.
 *
 * With the nginx FastCGI cache in front it is exactly backwards. The inline
 * markup costs nothing per view because it is baked into the cached response,
 * whereas the AJAX version is an uncached PHP request on every page load -- and
 * it appends markup after load, which shifts layout.
 *
 * This is a header-builder setting, not vendor code: the same toggle exists in
 * the theme's header editor. Original content is stored in post meta so the
 * change is reversible without a database restore.
 *
 * Usage:  php tools/inline-header-megamenu.php [--revert]
 */

require dirname( __DIR__ ) . '/app/public/wp-load.php';

const META_BACKUP = '_safi_megamenu_ajax_backup';

$revert = in_array( '--revert', $argv, true );

global $wpdb;

if ( $revert ) {
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s", META_BACKUP ) );
	foreach ( $ids as $id ) {
		$original = get_post_meta( $id, META_BACKUP, true );
		if ( '' === $original ) {
			continue;
		}
		wp_update_post( array( 'ID' => $id, 'post_content' => $original ) );
		delete_post_meta( $id, META_BACKUP );
		printf( "  reverted #%s (%s)\n", $id, get_the_title( $id ) );
	}
	if ( ! $ids ) {
		echo "  nothing to revert\n";
	}
	exit;
}

$posts = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE '%megamenu_ajax=\"true\"%'" );

foreach ( $posts as $row ) {
	$post = get_post( $row->ID );

	// Store the original once, so re-running is idempotent and never clobbers
	// the true original with an already-modified copy.
	if ( '' === (string) get_post_meta( $post->ID, META_BACKUP, true ) ) {
		update_post_meta( $post->ID, META_BACKUP, $post->post_content );
	}

	$updated = str_replace( 'megamenu_ajax="true"', 'megamenu_ajax="false"', $post->post_content );

	if ( $updated === $post->post_content ) {
		printf( "  #%-6s %-24s no change\n", $post->ID, get_the_title( $post->ID ) );
		continue;
	}

	wp_update_post( array( 'ID' => $post->ID, 'post_content' => $updated ) );
	printf( "  #%-6s %-24s megamenu now inline\n", $post->ID, get_the_title( $post->ID ) );
}

if ( ! $posts ) {
	echo "  nothing to do (already inline)\n";
}
