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
 * The trait adds `get_size_system` methods.
 * 
 * This method allows you to return the `size_system` tag.
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
trait XFGMC_T_Variable_Get_Size_System {

	/**
	 * Get `size_system` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324502
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:size_system>US</g:size_system>`
	 */
	public function get_size_system( $tag_name = 'g:size_system', $result_xml = '' ) {

		$size_system = common_option_get(
			'xfgmc_size_system',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $size_system === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_variable_global_attribute_value( $size_system );
		}

		if ( empty( $tag_value ) ) {
			$size_system_default_value = common_option_get(
				'xfgmc_size_system_default_value',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $size_system_default_value ) && $size_system_default_value !== 'disabled' ) {
				$tag_value = $size_system_default_value;
			}
		}

		$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		return $result_xml;
	}

}