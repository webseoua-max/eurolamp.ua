<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.1 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_google_product_category` methods.
 * 
 * This method allows you to return the `google_product_category` tag.
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

trait XFGMC_T_Simple_Get_Google_Product_Category {

	/**
	 * Get `google_product_category` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324436
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:google_product_category>2271</g:google_product_category>`.
	 */
	public function get_google_product_category( $tag_name = 'g:google_product_category', $result_xml = '' ) {

		$google_product_category = common_option_get(
			'xfgmc_google_product_category',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $google_product_category === 'disabled' ) {
			return $result_xml;
		}

		if ( get_post_meta( $this->get_product()->get_id(), 'xfgmc_google_product_category', true ) == '' ) {
			if ( get_term_meta( $this->get_feed_category_id(), 'xfgmc_google_product_category', true ) !== '' ) {
				$tag_value = get_term_meta( $this->get_feed_category_id(), 'xfgmc_google_product_category', true );
				$tag_value = htmlspecialchars( $tag_value );
			} else {
				$tag_value = '';
			}
		} else {
			$tag_value = get_post_meta( $this->get_product()->get_id(), 'xfgmc_google_product_category', true );
			$tag_value = htmlspecialchars( $tag_value );
		}
		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}