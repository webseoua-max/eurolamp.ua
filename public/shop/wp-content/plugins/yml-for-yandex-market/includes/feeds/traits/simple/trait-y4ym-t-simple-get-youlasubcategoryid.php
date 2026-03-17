<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      5.0.22
 * @version    5.0.22 (15-10-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_youlasubcategoryid` methods.
 * 
 * This method allows you to return the `youlaCategoryId` tag.
 *
 * @since      5.0.22
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     Y4YM_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *             functions:   common_option_get
 */

trait Y4YM_T_Simple_Get_Youlasubcategoryid {

	/**
	 * Get `youlaSubcategoryId` tag.
	 * 
	 * @see https://cloud.mail.ru/public/rRMD/V66Ywbmy6?weblink=rRMD/V66Ywbmy6
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<youlaSubcategoryId>407</youlaSubcategoryId>`.
	 */
	public function get_youlasubcategoryid( $tag_name = 'youlaSubcategoryId', $result_xml = '' ) {

		$tag_value = $this->get_simple_product_post_meta( 'youlasubcategoryid' );
		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}