<?php
/**
 * Phase 6.6 — render the footer inline instead of over AJAX.
 *
 * The theme can load a footer through admin-ajax (`footer_load`), leaving a
 * fixed-height placeholder in the markup until the response arrives. That is
 * controlled per footer by the `enovathemes_addons_footer_async` meta, with
 * per-context exemptions in `enovathemes_addons_dis_async_shop` and
 * `..._dis_async_page` (enovathemes-addons.php:624-658).
 *
 * On this site the shop and product exemptions were already on, which is why
 * those pages had a real footer while the **homepage rendered an empty
 * placeholder**. Deferring scripts in Phase 6.4 turned that latent split into a
 * visible bug: the hydration never ran, so the homepage footer stayed blank.
 *
 * Turning async off for the footer fixes it at the source and is the same
 * decision already taken for the header megamenu and the mobile header:
 *
 *   - it removes another uncached PHP request per page view,
 *   - the markup lands in the Phase 2 page cache, so it costs nothing per visit,
 *   - and it cannot break, because there is no client-side step left to fail.
 *
 * The same toggle exists in the theme's footer editor, so the client can flip it
 * back without the CLI.
 *
 * Usage:  php tools/inline-footer.php [--revert]
 */

require dirname( __DIR__ ) . '/app/public/wp-load.php';

const META_KEY    = 'enovathemes_addons_footer_async';
const META_BACKUP = '_safi_footer_async_backup';

$revert = in_array( '--revert', $argv, true );

global $wpdb;

$footer_ids = $wpdb->get_col(
	"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'footer' AND post_status = 'publish'"
);

if ( ! $footer_ids ) {
	echo "  no published footers found\n";
	exit;
}

foreach ( $footer_ids as $id ) {
	$title = get_the_title( $id );

	if ( $revert ) {
		$backup = get_post_meta( $id, META_BACKUP, true );
		if ( '' === $backup ) {
			printf( "  #%-6s %-20s nothing to revert\n", $id, $title );
			continue;
		}
		if ( '__unset__' === $backup ) {
			delete_post_meta( $id, META_KEY );
		} else {
			update_post_meta( $id, META_KEY, $backup );
		}
		delete_post_meta( $id, META_BACKUP );
		printf( "  #%-6s %-20s reverted\n", $id, $title );
		continue;
	}

	$current = get_post_meta( $id, META_KEY, true );

	if ( 'false' === $current ) {
		printf( "  #%-6s %-20s already inline\n", $id, $title );
		continue;
	}

	// Record the original exactly once, distinguishing "was empty" from "unset".
	if ( '' === (string) get_post_meta( $id, META_BACKUP, true ) ) {
		update_post_meta( $id, META_BACKUP, '' === (string) $current ? '__unset__' : $current );
	}

	update_post_meta( $id, META_KEY, 'false' );
	printf( "  #%-6s %-20s now renders inline (was %s)\n", $id, $title, '' === (string) $current ? 'unset' : $current );
}
