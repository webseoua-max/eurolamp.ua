<?php

/**
 * Fired during plugin deactivation.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (02-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Deactivator {

	/**
	 * Triggered when the plugin is deactivated (called once).
	 *
	 * @since    0.1.0
	 * 
	 * @return   void
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( is_multisite() ) {
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
		} else {
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
		}
		if ( ! empty( $settings_arr ) ) {
			$feed_ids_arr = array_keys( $settings_arr );
			if ( ! empty( $feed_ids_arr ) ) {
				for ( $i = 0; $i < count( $feed_ids_arr ); $i++ ) {
					$feed_id_int = $feed_ids_arr[ $i ];
					$feed_id_str = (string) $feed_ids_arr[ $i ];

					wp_clear_scheduled_hook( 'xfgmc_cron_start_feed_creation', [ $feed_id_str ] );
					wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $feed_id_str ] );

					if ( isset( $settings_arr[ $feed_id_int ]['xfgmc_run_cron'] ) ) {
						$settings_arr[ $feed_id_int ]['xfgmc_run_cron'] = 'disabled';
					}
					if ( isset( $settings_arr[ $feed_id_int ]['xfgmc_status_sborki'] ) ) {
						$settings_arr[ $feed_id_int ]['xfgmc_status_sborki'] = '-1';
					}

					if ( is_multisite() ) {
						update_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', $settings_arr );
						update_blog_option( get_current_blog_id(), 'xfgmc_last_element_feed_' . $feed_id_str, 0 );
					} else {
						update_option( 'xfgmc_settings_arr', $settings_arr );
						update_option( 'xfgmc_last_element_feed_' . $feed_id_str, 0 );
					}
				}
			}
		}
	}

}
