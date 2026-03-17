<?php
/*
Plugin Name: Payment Gateway for City24 for Woocommerce
Plugin URI:
Description: Plugin for paying for products through the LiqPay service. Works in conjunction with the Woocommerce plugin
Version: 1.4
Requires at least: 5.7.2
Requires PHP: 7.4
Author: info@arudenko.com
License: GPL v2 or later
Text Domain: wc-city24
Domain Path: /languages
Author URI: https://arudenko.com/
*/

if (!defined('ABSPATH')) exit;

add_action('plugins_loaded', 'city24_payment_gateway_init', 0);

function city24_payment_gateway_init() {

    /** dir path plugin */

    define("WC_CITY24_DIR", plugin_dir_url( __FILE__ )); 

    if (!class_exists('WC_Payment_Gateway')) return;

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), function($links ){
        array_unshift( $links, '<a href="admin.php?page=wc-settings&tab=checkout&section=city24">' . __( 'Settings', 'wc-city24' ) . '</a>' );
        return $links;
    });

    add_action( 'admin_enqueue_scripts','kmnd_city24_admin_enqueue_scripts');

    function kmnd_city24_admin_enqueue_scripts(){
        wp_register_style('kmnd-city24-style', plugins_url( '/assets/css/styles.css', __FILE__ ), false);
        wp_enqueue_style( 'kmnd-city24-style'); 

        wp_register_script("kmnd-city24-js", plugins_url( '/assets/js/main.js', __FILE__ ), '', '1.3',  true);
        wp_enqueue_script( "kmnd-city24-js");
    }

    load_plugin_textdomain( 'wc-city24', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    add_action( 'muplugins_loaded', 'mu_kmnd_city24_init' );

    function mu_kmnd_city24_init() {

        load_muplugin_textdomain( 'wc-city24', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    }

    require_once plugin_dir_path(__FILE__) . 'includes/WC_Gateway_kmnd_City24.php';
    require_once plugin_dir_path(__FILE__) . 'includes/class-wc-city24-page-redirect.php';

      /** redirect to error page city24 */
      $redirect_error = new Wc_City24_Page_Redirect();
      
    add_action('template_redirect', function() use ($redirect_error){
     
          $redirect_error->redirect_to_error();
    });

    function kmnd_city24($methods) {

        $methods[] = 'WC_Gateway_kmnd_City24';

        return $methods;

    }

    add_filter('woocommerce_payment_gateways', 'kmnd_city24');

}

