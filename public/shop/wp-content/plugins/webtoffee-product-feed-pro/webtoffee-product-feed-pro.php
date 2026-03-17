<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.webtoffee.com
 * @since             1.0.0
 * @package           Webtoffee_Product_Feed_Sync_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       WebToffee WooCommerce Product Feed & Sync Manager(Pro)
 * Plugin URI:        https://www.webtoffee.com/product/woocommerce-product-feed/
 * Description:       Integrate your WooCommerce store with popular sale channels including Google Merchant Center, Facebook/Instagram shops, Bing, Pinterest, TikTok Ads, and much more. Also supports on-demand sync for Facebook catalog.
 * Version:           1.2.2
 * Author:            WebToffee
 * Author URI:        https://www.webtoffee.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       webtoffee-product-feed-pro
 * Domain Path:       /languages
 * WC tested up to:   10.1.1
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION', '1.2.2' );
define( 'WEBTOFFEE_PRODUCT_FEED_PRO_ID', 'webtoffee_product_feed_pro' );
define( 'WT_PRODUCT_FEED_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define( 'WT_PRODUCT_FEED_PRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'WT_PRODUCT_FEED_PRO_PLUGIN_FILENAME', __FILE__);
if (!defined('WT_PRODUCT_FEED_PRO_BASE_NAME')) {
    define('WT_PRODUCT_FEED_PRO_BASE_NAME', plugin_basename(__FILE__));
}

if ( !defined( 'WEBTOFFEE_PRODUCT_FEED_MAIN_PRO_ID' ) ) {
	define( 'WEBTOFFEE_PRODUCT_FEED_MAIN_PRO_ID', 'webtoffee_product_feed_main_pro' );
}

if ( !defined( 'WT_PF_DEBUG_PRO' ) ) {
	define( 'WT_PF_DEBUG_PRO', false );
}


/** @since 1.3.5 */
if (!defined('WT_PF_PLUGIN_NAME'))
{
    define('WT_PF_PLUGIN_NAME','webtoffee-product-feed-pro');
    define('WT_PF_PLUGIN_ID','webtoffee_product_feed');
    define('WT_PF_SETTINGS_FIELD', WT_PF_PLUGIN_NAME); /* option name to store settings */
    define('WT_PF_ACTIVATION_ID','webtoffee-product-feed-pro'); 
    define('WT_PF_EDD_ACTIVATION_ID','196720'); 
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-webtoffee-product-feed-sync-activator.php
 */
function activate_webtoffee_product_feed_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync-activator.php';
	Webtoffee_Product_Feed_Sync_Pro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-webtoffee-product-feed-sync-deactivator.php
 */
function deactivate_webtoffee_product_feed_pro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync-deactivator.php';
	Webtoffee_Product_Feed_Sync_Pro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_webtoffee_product_feed_pro' );
register_deactivation_hook( __FILE__, 'deactivate_webtoffee_product_feed_pro' );



/* Checking WC is actived or not */
if ( !function_exists( 'is_plugin_active' ) ) {
	include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

add_action( 'plugins_loaded', 'wt_feed_check_for_woocommerce' );

if ( !function_exists( 'wt_feed_check_for_woocommerce' ) ) {

	function wt_feed_check_for_woocommerce() {


		if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) || !defined( 'WC_VERSION' ) ) {
			add_action( 'admin_notices', 'wt_wc_missing_warning_for_feed' );
		}
		if ( !function_exists( 'wt_wc_missing_warning_for_feed' ) ) {

			function wt_wc_missing_warning_for_feed() {

				$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install-plugin', 'plugin' => 'woocommerce', ), admin_url( 'update.php' ) ), 'install-plugin_woocommerce' );
				$class		 = 'notice notice-error';
				$post_type	 = 'product';
				$message	 = sprintf( __( 'The <b>WooCommerce</b> plugin must be active for <b> WebToffee WooCommerce %s Feed & Sync Manager Pro</b> plugin to work.  Please <a href="%s" target="_blank">install & activate WooCommerce</a>.' ), ucfirst( $post_type ), esc_url( $install_url ) );
				printf( '<div class="%s"><p>%s</p></div>', esc_attr( $class ), ( $message ) );
			}

		}
	}
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-webtoffee-product-feed-sync.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wt-productfeed-uninstall-feedback.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-wt-productfeed-feature-request.php';


// WooCommerce HPOS compatibility decleration
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_webtoffee_product_feed_pro() {

	$plugin = new Webtoffee_Product_Feed_Sync_Pro();
	$plugin->run();
}

run_webtoffee_product_feed_pro();
