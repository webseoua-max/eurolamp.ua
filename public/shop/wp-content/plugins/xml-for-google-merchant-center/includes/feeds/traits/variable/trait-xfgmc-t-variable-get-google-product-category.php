<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.1 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_google_product_category` methods.
 * 
 * This method allows you to return the `google_product_type` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *             functions:   common_option_get
 */

trait XFGMC_T_Variable_Get_Google_Product_Category {

	/**
	 * Get `google_product_type` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324406
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:product_type>Главная > Женская одежда > Платья > Длинные платья</g:product_type>`.
	 */
	public function get_google_product_category( $tag_name = 'g:google_product_type', $result_xml = '' ) {

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
		$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}