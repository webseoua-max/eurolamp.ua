<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      4.0.0
 * @version    4.0.2 (08-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_open_item_tag` methods.
 * 
 * This method allows you to return the open `item` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Open_Tag
 *             methods:     
 *             functions:   
 */
trait XFGMC_T_Simple_Get_Open_Item_Tag {

	/**
	 * Get open `item` tag.
	 * 
	 * @see 
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<item>`
	 */
	public function get_open_item_tag( $tag_name = 'item', $result_xml = '' ) {

		$result_xml .= new XFGMC_Get_Open_Tag( $tag_name );
		return $result_xml;

	}

}