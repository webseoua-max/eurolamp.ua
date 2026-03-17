<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://growcommerce.io/
 * @since             1.0.0
 * @package           Pixel_Manager_For_Woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Pixel Tag Manager for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/pixel-manager-for-woocommerce/
 * Description:       Pixel Tag Manager for WooCommerce helps you track key eCommerce events with ease. It supports GA4, Google Ads, Facebook Pixel, TikTok, Pinterest, Snapchat, Bing Ads, and more. With seamless integration, you get accurate data for better targeting and remarketing. Plus, it comes with GrowInsights360 – a built-in GA4 analytics dashboard that gives you clear reports on traffic, conversions, and product performance.
 * Version:           2.1
 * Author:            GrowCommerce
 * Author URI:        https://growcommerce.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pixel-manager-for-woocommerce
 * Domain Path:       /languages
 * WC requires at least: 3.7.0
 * WC tested up to: 10.4.3
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
/**
 * First check the PRO plugin and need to remove it
 **/
if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION' ) ){
	define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION', '2.1' );
}
if( ! defined( 'PMW_API_URL' ) ){
  define( 'PMW_API_URL', 'https://growcommerceapi.com/api/' );
}
/**
 * For HPOS - WooCommerce 
 **/
add_action('before_woocommerce_init', function(){
  if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
      \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
  }
});

function PMW_Check_Pro() {
  if( ! defined( 'PRO_PIXEL_MANAGER_FOR_WOOCOMMERCE' )  && !class_exists( 'PRO_Pixel_Manager_For_Woocommerce' ) ){
		define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_PREFIX', 'pixel-manager-for-woocommerce' );
		if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE' ) ){
		  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE', basename(__DIR__) );
		}
		if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR' ) ){
		  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR', plugin_dir_path( __FILE__ ) );
		}
		if( ! defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_URL' ) ) {
		  define( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_URL', plugins_url() . '/'.PIXEL_MANAGER_FOR_WOOCOMMERCE );
		}
		if( ! defined( 'PMW_PRODUCT_ID' ) ){
		  define( 'PMW_PRODUCT_ID', '1' );
		}
		if ( ! class_exists( 'PMW_AdminHelper' ) ) {
		  require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-helper.php');
		}

		/**
		 * The code that runs during plugin deactivation.
		 * This action is documented in includes/class-pixel-manager-for-woocommerce-deactivator.php
		 */
		function deactivate_pixel_manager_for_woocommerce() {
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce-deactivator.php';
			Pixel_Manager_For_Woocommerce_Deactivator::deactivate();
		}
		register_deactivation_hook( __FILE__, 'deactivate_pixel_manager_for_woocommerce' );

		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce.php';

		/**
		 * Begins execution of the plugin.
		 *
		 * @since    1.0.0
		 */
		function run_pixel_manager_for_woocommerce() {
			$plugin = new Pixel_Manager_For_Woocommerce();
			$plugin->run();
		}
		run_pixel_manager_for_woocommerce();
	}
}
add_action( 'init', 'PMW_Check_Pro' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pixel-manager-for-woocommerce-activator.php
 */
function activate_pixel_manager_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'admin/helper/class-pmw-admin-api-helper.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pixel-manager-for-woocommerce-activator.php';
	Pixel_Manager_For_Woocommerce_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_pixel_manager_for_woocommerce' );

function pixel_manager_for_woocommerce_activation_redirect( $plugin ) {
  if( $plugin == plugin_basename( __FILE__ ) ) {
    exit( wp_redirect( admin_url( 'admin.php?page=pixel-manager' ) ) );
  }
}
add_action( 'activated_plugin', 'pixel_manager_for_woocommerce_activation_redirect' );