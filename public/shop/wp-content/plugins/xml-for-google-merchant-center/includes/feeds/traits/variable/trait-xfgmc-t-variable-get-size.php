<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_size` methods.
 * 
 * This method allows you to return the `size` tag.
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
trait XFGMC_T_Variable_Get_Size {

	/**
	 * Get `size` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324492
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:size>XL</g:size>`
	 */
	public function get_size( $tag_name = 'g:size', $result_xml = '' ) {

		$size = common_option_get(
			'xfgmc_size',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $size === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_variable_global_attribute_value( $size );
			$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}