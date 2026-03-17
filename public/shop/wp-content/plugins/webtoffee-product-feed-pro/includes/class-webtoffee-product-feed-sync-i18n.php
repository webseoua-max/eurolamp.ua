<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync_Pro
 * @subpackage Webtoffee_Product_Feed_Sync_Pro/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Webtoffee_Product_Feed_Sync_Pro
 * @subpackage Webtoffee_Product_Feed_Sync_Pro/includes
 * @author     WebToffee <info@webtoffee.com>
 */
class Webtoffee_Product_Feed_Sync_Pro_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'webtoffee-product-feed-pro',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
