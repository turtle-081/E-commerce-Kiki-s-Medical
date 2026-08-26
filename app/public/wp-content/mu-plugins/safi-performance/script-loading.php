<?php
/**
 * Phase 6.4 — stop JavaScript blocking the first render.
 *
 * Lighthouse's render-blocking insight put ~1,950 ms on this page, and the
 * largest single entry was jQuery at 918 ms — loaded synchronously in the
 * `<head>`, where the parser stops dead until it has downloaded and executed.
 *
 * Deferring jQuery on a WordPress site is usually dangerous, because any inline
 * `<script>` that calls `jQuery(...)` will run before it exists. That was checked
 * rather than assumed: of 28 inline script blocks on the product page, 15 are
 * WordPress-managed (`-js-after` / `-js-before` / `-js-extra`, which WordPress
 * keeps correctly ordered), and of the remaining 13 exactly **one** touches
 * jQuery — the `.no-prefetch` marker from Phase 5, which already guards with
 * `if (window.jQuery)`. The theme has no raw inline jQuery.
 *
 * The approach is to ask for `defer` broadly and let WordPress decide. Since 6.3
 * core computes an *eligible* loading strategy: a script is only deferred if
 * every script depending on it is also deferrable. Anything that would break
 * ordering is silently left blocking. That built-in safety net is why this is a
 * broad opt-in with a small denylist rather than a hand-maintained allowlist.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handles that must keep loading synchronously.
 *
 * Kept deliberately short. Anything relying on `document.write`, or which must
 * execute before the parser reaches the markup it modifies, belongs here.
 */
function safi_blocking_script_handles() {
	return apply_filters(
		'safi_blocking_script_handles',
		array(
			// Consent/analytics style bootstrappers that other inline code calls
			// into synchronously would go here. None on this site today.
		)
	);
}

/**
 * Mark everything currently queued as deferrable.
 */
function safi_mark_queued_scripts_deferred() {
	if ( is_admin() ) {
		return;
	}

	$wp_scripts = wp_scripts();
	$blocking   = safi_blocking_script_handles();

	foreach ( $wp_scripts->queue as $handle ) {
		safi_defer_script_tree( $handle, $blocking, $wp_scripts );
	}
}

// First pass: everything enqueued the normal way.
add_action( 'wp_enqueue_scripts', 'safi_mark_queued_scripts_deferred', PHP_INT_MAX - 10 );

/*
 * Second pass, and the one that actually matters here.
 *
 * `wp_enqueue_scripts` fires before the page body renders, so anything enqueued
 * *during* rendering — widgets, shortcodes, template parts — is not in the queue
 * yet when the first pass runs. On this site the search widget enqueues
 * `widget-product-search` that way.
 *
 * That mattered more than it sounds: WordPress will only defer a script when
 * every script depending on it can also be deferred. One un-marked dependent of
 * jQuery was therefore enough to keep jQuery itself blocking in the head, which
 * was the single largest render-blocking item on the page. Running the pass
 * again just before the footer scripts print catches those late arrivals.
 */
add_action( 'wp_print_footer_scripts', 'safi_mark_queued_scripts_deferred', 1 );

/**
 * Mark a handle and everything it depends on as deferrable.
 *
 * Dependencies are walked because a script is only eligible for `defer` when its
 * whole dependency chain is too — marking only the queued handles would leave
 * jQuery itself blocking, which is the entire point of the exercise.
 */
function safi_defer_script_tree( $handle, array $blocking, $wp_scripts, array &$seen = array() ) {
	if ( isset( $seen[ $handle ] ) || in_array( $handle, $blocking, true ) ) {
		return;
	}
	$seen[ $handle ] = true;

	if ( ! isset( $wp_scripts->registered[ $handle ] ) ) {
		return;
	}

	$script = $wp_scripts->registered[ $handle ];

	// Never override an explicit async: it is a stronger, deliberate choice.
	$existing = $script->extra['strategy'] ?? '';
	if ( 'async' !== $existing ) {
		wp_script_add_data( $handle, 'strategy', 'defer' );
	}

	foreach ( (array) $script->deps as $dep ) {
		safi_defer_script_tree( $dep, $blocking, $wp_scripts, $seen );
	}
}
