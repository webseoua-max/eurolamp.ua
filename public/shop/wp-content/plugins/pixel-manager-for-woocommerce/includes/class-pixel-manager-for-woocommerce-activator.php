<?php

/**
 * Fired during plugin activation
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/includes
 * @author     GrowCommerce
 */
class Pixel_Manager_For_Woocommerce_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
    $PMW_API = new PMW_AdminAPIHelper();
    $PMW_API->save_product_store(array(), 1);
	}
}