<?php
/**
 * ONE-TIME SETTINGS FIX
 * =====================
 * The TagCloud display type settings stored in the database may be missing
 * the custom keys (required_container_ids, related_tag_maxcount, etc.) if
 * install() ran before those defaults were defined.
 *
 * HOW TO USE:
 *   1. Paste this entire file's code into your theme's functions.php
 *   2. Load any wp-admin page once
 *   3. Remove the code from functions.php immediately after
 *
 * It is safe to run multiple times — install() is idempotent when $reset=true
 * for our custom keys (it won't touch other settings).
 */
add_action( 'admin_init', function () {
    if ( class_exists( '\\Imagely\\NGGPro\\DisplayTypes\\TagCloud' ) ) {
        ( new \Imagely\NGGPro\DisplayTypes\TagCloud() )->install( true );
        error_log( 'NextGEN Pro TagCloud: settings reinstalled.' );
    }
} );
