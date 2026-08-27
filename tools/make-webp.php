<?php
/**
 * Phase 6.8 -- generate WebP siblings for the images in wp-content/uploads.
 *
 * Lighthouse's image-delivery insight attributes 290 KB of the homepage's
 * 608 KB to "using a modern image format (WebP, AVIF) or increasing this
 * image's compression". Unlike the responsive-images route tried and reverted
 * in 6.7, this does not depend on the browser resolving a layout width, does
 * not touch any markup, and cannot change which element paints first: the URL
 * stays exactly the same and nginx decides what to put behind it based on the
 * request's Accept header.
 *
 * For each foo.png / foo.jpg this writes foo.png.webp beside it. The suffix is
 * appended rather than replacing the extension so that nginx can find the
 * candidate with a plain `try_files $uri$webp_suffix $uri` and no rewriting.
 * See conf/nginx/site.conf.hbs.
 *
 * A converted file is kept only if it is meaningfully smaller than the
 * original -- there is no point serving a WebP that saves 2%, and for
 * already-efficient JPEGs that is a common outcome.
 *
 * Usage (needs Local's PHP *with Local's php.ini*, see PLATFORM.md):
 *
 *   php tools/make-webp.php            # convert, skipping up-to-date files
 *   php tools/make-webp.php --dry-run  # report what would change
 *   php tools/make-webp.php --revert   # delete every generated .webp
 *
 * Idempotent: a .webp newer than its source is left alone, so re-running is
 * cheap and will not re-encode the whole library.
 */

declare( strict_types=1 );

const QUALITY       = 82;
// Below this, the request overhead dominates and the saving is noise.
const MIN_SOURCE    = 8 * 1024;
// Keep the WebP only if it saves at least this share of the original.
const MIN_SAVING    = 0.15;

$root    = dirname( __DIR__ ) . '/app/public/wp-content/uploads';
$dry     = in_array( '--dry-run', $argv, true );
$revert  = in_array( '--revert', $argv, true );

if ( ! is_dir( $root ) ) {
	fwrite( STDERR, "uploads directory not found: $root\n" );
	exit( 1 );
}

if ( ! function_exists( 'imagewebp' ) ) {
	fwrite( STDERR, "this PHP has no WebP support in GD -- check you are using Local's php.ini\n" );
	exit( 1 );
}

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS )
);

$made = 0;
$skipped = 0;
$rejected = 0;
$deleted = 0;
$bytes_before = 0;
$bytes_after = 0;

foreach ( $iterator as $file ) {
	/** @var SplFileInfo $file */
	if ( ! $file->isFile() ) {
		continue;
	}

	$path = str_replace( '\\', '/', $file->getPathname() );
	$ext  = strtolower( $file->getExtension() );

	if ( $revert ) {
		// Only ever remove the double-extension files this script creates, so
		// a genuine foo.webp uploaded by hand is never touched.
		if ( 'webp' === $ext && preg_match( '/\.(png|jpe?g)\.webp$/i', $path ) ) {
			if ( ! $dry ) {
				unlink( $path );
			}
			++$deleted;
		}
		continue;
	}

	if ( ! in_array( $ext, array( 'png', 'jpg', 'jpeg' ), true ) ) {
		continue;
	}

	$size = $file->getSize();
	if ( $size < MIN_SOURCE ) {
		continue;
	}

	$out = $path . '.webp';

	// Already converted and still current.
	if ( file_exists( $out ) && filemtime( $out ) >= $file->getMTime() ) {
		++$skipped;
		continue;
	}

	$image = 'png' === $ext ? @imagecreatefrompng( $path ) : @imagecreatefromjpeg( $path );
	if ( ! $image ) {
		fwrite( STDERR, "  cannot decode: $path\n" );
		continue;
	}

	// Palette PNGs must be promoted before they can carry alpha into WebP.
	imagepalettetotruecolor( $image );
	imagealphablending( $image, false );
	imagesavealpha( $image, true );

	$tmp = tempnam( sys_get_temp_dir(), 'webp' );
	if ( ! imagewebp( $image, $tmp, QUALITY ) ) {
		imagedestroy( $image );
		@unlink( $tmp );
		fwrite( STDERR, "  cannot encode: $path\n" );
		continue;
	}
	imagedestroy( $image );

	$new = filesize( $tmp );

	if ( $new >= $size * ( 1 - MIN_SAVING ) ) {
		// Not worth a second file on disk.
		@unlink( $tmp );
		// Remove a stale conversion that is no longer worth keeping.
		if ( file_exists( $out ) && ! $dry ) {
			unlink( $out );
		}
		++$rejected;
		continue;
	}

	$bytes_before += $size;
	$bytes_after  += $new;
	++$made;

	printf(
		"  %-58s %7d -> %7d  (-%d%%)\n",
		substr( str_replace( $root . '/', '', $path ), -58 ),
		$size,
		$new,
		100 - intdiv( $new * 100, $size )
	);

	if ( $dry ) {
		@unlink( $tmp );
		continue;
	}

	if ( ! rename( $tmp, $out ) ) {
		// rename() across volumes can fail on Windows; fall back to a copy.
		copy( $tmp, $out );
		@unlink( $tmp );
	}
}

echo "\n";

if ( $revert ) {
	printf( "%s %d generated .webp file(s)\n", $dry ? 'would delete' : 'deleted', $deleted );
	exit( 0 );
}

printf(
	"%s %d file(s); %d already current, %d rejected as not worth it\n",
	$dry ? 'would convert' : 'converted',
	$made,
	$skipped,
	$rejected
);

if ( $made ) {
	printf(
		"payload for those files: %.0f KB -> %.0f KB, saving %.0f KB (%d%%)\n",
		$bytes_before / 1024,
		$bytes_after / 1024,
		( $bytes_before - $bytes_after ) / 1024,
		100 - intdiv( $bytes_after * 100, $bytes_before )
	);
}

echo "\nnginx serves these only when the request Accept header allows it;\n";
echo "see conf/nginx/nginx.conf.hbs (\$webp_suffix) and conf/nginx/site.conf.hbs.\n";
