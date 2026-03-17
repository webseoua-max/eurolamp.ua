<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (02-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_fb_product_category` methods.
 * 
 * This method allows you to return the `fb_product_category` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *             functions:   common_option_get
 */

trait XFGMC_T_Simple_Get_Fb_Product_Category {

	/**
	 * Get `fb_product_category` tag.
	 * 
	 * @see https://www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:fb_product_category>2271</g:fb_product_category>`.
	 */
	public function get_fb_product_category( $tag_name = 'g:fb_product_category', $result_xml = '' ) {

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_fb_product_category', true ) == '' ) {
			if ( get_term_meta( $this->get_feed_category_id(), 'xfgmc_fb_product_category', true ) !== '' ) {
				$tag_value = get_term_meta( $this->get_feed_category_id(), 'xfgmc_fb_product_category', true );
				$tag_value = htmlspecialchars( $tag_value );
			}
		} else {
			$tag_value = get_post_meta( $this->get_product()->get_id(), '_xfgmc_fb_product_category', true );
			$tag_value = htmlspecialchars( $tag_value );
		}
		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}