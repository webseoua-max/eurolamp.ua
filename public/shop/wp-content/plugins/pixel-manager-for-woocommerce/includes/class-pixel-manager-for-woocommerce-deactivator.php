<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/includes
 * @author     GrowCommerce
 */
class Pixel_Manager_For_Woocommerce_Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
    $PMW_API = new PMW_AdminAPIHelper();
    $PMW_API->save_product_store(array(), 0);
	}
}