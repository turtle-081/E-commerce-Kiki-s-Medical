<?php
/**
 * Phase 8 -- render the homepage's product and post grids inline instead of
 * over AJAX.
 *
 * The front page ("Home 5", #373) carries five builder elements set to load
 * their contents over `admin-ajax.php` after the page has rendered: four
 * `[et_woo_products]` grids and one `[et_posts]`. Measured on this machine each
 * of those requests takes ~2.5 s, and they are *uncached PHP* -- the nginx page
 * cache serves the HTML in ~24 ms and then the browser goes back to the origin
 * four more times to fill in the parts that matter.
 *
 * Same reasoning as `inline-header-megamenu.php`, and the same fix. Loading a
 * grid over AJAX is a sensible default with no page cache: it keeps the initial
 * response small. With a full-page cache in front it is exactly backwards --
 * inline markup is baked into the cached response and costs nothing per view,
 * while the AJAX version costs a full WordPress bootstrap every single time.
 *
 * It also removes a JavaScript dependency from the part of the page that
 * actually sells things, which is the lesson Phase 6.6 paid for: anything that
 * paints via JavaScript can stop painting.
 *
 * This is a builder element setting, not vendor code -- the same toggle is in
 * the WPBakery element editor. The original content is stored in post meta so
 * the change is reversible without a database restore.
 *
 * Usage:  php tools/inline-grid-ajax.php [--dry-run] [--revert]
 */

require dirname( __DIR__ ) . '/app/public/wp-load.php';

const META_BACKUP = '_safi_grid_ajax_backup';

// Only these. `ajax="true"` on some other element may mean something entirely
// different, and a blind string replace across post content is how you break a
// page you were not looking at.
const TAGS = array( 'et_woo_products', 'et_posts' );

$dry    = in_array( '--dry-run', $argv, true );
$revert = in_array( '--revert', $argv, true );

global $wpdb;

if ( $revert ) {
	$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s", META_BACKUP ) );

	foreach ( $ids as $id ) {
		$original = get_post_meta( $id, META_BACKUP, true );
		if ( '' === (string) $original ) {
			continue;
		}
		if ( ! $dry ) {
			wp_update_post( array( 'ID' => $id, 'post_content' => $original ) );
			delete_post_meta( $id, META_BACKUP );
		}
		printf( "  %s #%s (%s)\n", $dry ? 'would revert' : 'reverted', $id, get_the_title( $id ) );
	}

	if ( ! $ids ) {
		echo "  nothing to revert\n";
	}
	exit;
}

$rows = $wpdb->get_results(
	"SELECT ID FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_content LIKE '%ajax=\"true\"%'"
);

$changed = 0;

foreach ( $rows as $row ) {
	$post    = get_post( $row->ID );
	$content = $post->post_content;

	// Rewrite only inside the opening tag of the shortcodes we know about.
	$updated = preg_replace_callback(
		'/\[(' . implode( '|', TAGS ) . ')\b[^\]]*\]/i',
		static function ( $m ) {
			return str_replace( 'ajax="true"', 'ajax="false"', $m[0] );
		},
		$content
	);

	if ( $updated === $content ) {
		continue;
	}

	$before = substr_count( $content, 'ajax="true"' );
	$after  = substr_count( $updated, 'ajax="true"' );

	printf(
		"  #%-6s %-24s %d grid(s) now inline%s\n",
		$post->ID,
		get_the_title( $post->ID ),
		$before - $after,
		$after ? sprintf( ' (%d left on other elements, untouched)', $after ) : ''
	);

	if ( $dry ) {
		continue;
	}

	// Store the original once, so re-running is idempotent and never clobbers
	// the true original with an already-modified copy.
	if ( '' === (string) get_post_meta( $post->ID, META_BACKUP, true ) ) {
		update_post_meta( $post->ID, META_BACKUP, $content );
	}

	wp_update_post( array( 'ID' => $post->ID, 'post_content' => $updated ) );
	++$changed;
}

if ( ! $changed && ! $dry ) {
	echo "  nothing to do (already inline)\n";
}

echo "\n  Flush the theme cache and the page cache before checking:\n";
echo "      php tools/flush-theme-caches.php\n";
