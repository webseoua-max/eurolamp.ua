<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 *
 * @package    XFGMC
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( is_multisite() ) {
	$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );

	if ( ! empty( $settings_arr ) ) {
		$feed_ids_arr = array_keys( $settings_arr );
		if ( ! empty( $feed_ids_arr ) ) {
			for ( $i = 0; $i < count( $feed_ids_arr ); $i++ ) {
				$feed_id_str = (string) $feed_ids_arr[ $i ];
				delete_blog_option( get_current_blog_id(), 'xfgmc_last_element_feed_' . $feed_id_str );
			}
		}
	}

	delete_blog_option( get_current_blog_id(), 'xfgmc_version' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_keeplogs' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_plugin_notifications' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_feed_content' );

	delete_blog_option( get_current_blog_id(), 'xfgmc_settings_arr' );
	delete_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id' );
} else {
	$settings_arr = get_option( 'xfgmc_settings_arr', [] );

	if ( ! empty( $settings_arr ) ) {
		$feed_ids_arr = array_keys( $settings_arr );
		if ( ! empty( $feed_ids_arr ) ) {
			for ( $i = 0; $i < count( $feed_ids_arr ); $i++ ) {
				$feed_id_str = (string) $feed_ids_arr[ $i ];
				delete_option( 'xfgmc_last_element_feed_' . $feed_id_str );
			}
		}
	}

	delete_option( 'xfgmc_version' );
	delete_option( 'xfgmc_keeplogs' );
	delete_option( 'xfgmc_plugin_notifications' );
	delete_option( 'xfgmc_feed_content' );

	delete_option( 'xfgmc_settings_arr' );
	delete_option( 'xfgmc_last_feed_id' );
}
wp_cache_flush();