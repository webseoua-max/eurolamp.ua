<?php

/**
 * Fired during plugin activation.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.3 (17-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Activator {

	/**
	 * Triggered when the plugin is activated (called once).
	 *
	 * @since    0.1.0
	 * 
	 * @return   void
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		if ( ! is_dir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
			if ( ! mkdir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
				error_log(
					sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-activator.php; %5$s: %6$s',
						__( 'Folder creation error', 'xml-for-google-merchant-center' ),
						XFGMC_PLUGIN_UPLOADS_DIR_PATH,
						__( 'at the time of plugin activation', 'xml-for-google-merchant-center' ),
						__( 'Line', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						__LINE__
					),
					0
				);
			}
		}

		$name_dir = XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/feed1';
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log(
					sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-activator.php; %5$s: %6$s',
						__( 'Folder creation error', 'xml-for-google-merchant-center' ),
						$name_dir,
						__( 'at the time of plugin activation', 'xml-for-google-merchant-center' ),
						__( 'Line', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						__LINE__
					),
					0
				);
			}
		}

		if ( is_multisite() ) {
			add_blog_option( get_current_blog_id(), 'xfgmc_version', XFGMC_PLUGIN_VERSION );
			add_blog_option( get_current_blog_id(), 'xfgmc_keeplogs', 'disabled' );
			add_blog_option( get_current_blog_id(), 'xfgmc_plugin_notifications', 'enabled' );
			add_blog_option( get_current_blog_id(), 'xfgmc_feed_content', '' ); // kejo

			add_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
			add_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id', '0' );
			// * в процессе работы плагина будут созданы опции типа `xfgmc_last_element_feed_{1}`
		} else {
			add_option( 'xfgmc_version', XFGMC_PLUGIN_VERSION, '', true ); // без автозагрузки
			add_option( 'xfgmc_keeplogs', 'disabled' );
			add_option( 'xfgmc_plugin_notifications', 'enabled' );
			add_option( 'xfgmc_feed_content', '' ); // kejo

			add_option( 'xfgmc_settings_arr', [] );
			add_option( 'xfgmc_last_feed_id', '0' );
			// * в процессе работы плагина будут созданы опции типа `xfgmc_last_element_feed_{1}`
		}
	}

}
