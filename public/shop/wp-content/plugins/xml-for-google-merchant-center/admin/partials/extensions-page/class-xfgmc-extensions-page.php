<?php

/**
 * The class return the Extensions page of the plugin XML for Google Merchant Center.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (02-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin
 */

/**
 * The class return the Extensions page of the plugin XML for Google Merchant Center.
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/extensions_page
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Extensions_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Render the extensions page.
	 * 
	 * @return void
	 */
	public function render() {

		include_once __DIR__ . '/views/html-admin-extensions-page.php';
	}

}
