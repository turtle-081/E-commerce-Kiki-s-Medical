<?php
/**
 * Flush the enovathemes-addons layout transients (headers, footers, megamenus,
 * banners, icons, filters, dynamic css).
 *
 * These are normally purged by the theme when the underlying content changes,
 * but a direct post_content edit made outside the header builder does not fire
 * those hooks, so they are cleared explicitly here.
 *
 * The nginx page cache is emptied afterwards, and that is not a convenience --
 * it is required for correctness. The theme rebuilds these transients lazily on
 * the next render, and a render that happens while the site is under load can
 * emit the page *without* its megamenus. nginx will then store that incomplete
 * page and serve it as a HIT for the full 24-hour TTL.
 *
 * That is not hypothetical: it happened during Phase 6.8 measurement, where the
 * homepage was cached at 276 KB instead of 622 KB, with every megamenu and the
 * mobile header missing, and four Lighthouse runs measured the wrong page
 * before the document size gave it away. Flushing one cache without the other
 * is what makes it possible.
 */
require dirname( __DIR__ ) . '/app/public/wp-load.php';

global $wpdb;

$like = $wpdb->esc_like( '_transient_' ) . '%';
$rows = $wpdb->get_col( $wpdb->prepare( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) );

$patterns = array( 'header', 'footer', 'megamenu', 'banner', 'icon', 'social', 'categor', 'filter', 'dynamic', 'title_section', 'product_query', 'post_query' );

$deleted = 0;
foreach ( $rows as $name ) {
	$key = preg_replace( '/^_transient_(timeout_)?/', '', $name );
	foreach ( $patterns as $p ) {
		if ( false !== stripos( $key, $p ) ) {
			if ( delete_transient( $key ) ) {
				$deleted++;
			}
			break;
		}
	}
}

printf( "  deleted %d theme transient(s)\n", $deleted );

/*
 * Empty the nginx page cache, so the next render cannot promote a
 * half-rebuilt page into it. See the note at the top of this file.
 */
$cache_dir = dirname( __DIR__ ) . '/app/nginx-cache';
$removed   = 0;

if ( is_dir( $cache_dir ) ) {
	$items = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $cache_dir, FilesystemIterator::SKIP_DOTS ),
		RecursiveIteratorIterator::CHILD_FIRST
	);

	foreach ( $items as $item ) {
		if ( $item->isDir() ) {
			@rmdir( $item->getPathname() );
		} elseif ( @unlink( $item->getPathname() ) ) {
			++$removed;
		}
	}

	printf( "  emptied the nginx page cache (%d file(s))\n", $removed );
} else {
	printf( "  no nginx cache directory at %s -- nothing to empty\n", $cache_dir );
}

echo "\n  Re-request the pages you changed and check the response size before\n";
echo "  trusting anything measured against them.\n";
