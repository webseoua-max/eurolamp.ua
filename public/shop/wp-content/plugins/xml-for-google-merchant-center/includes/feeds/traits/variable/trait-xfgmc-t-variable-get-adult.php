<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.1.1 (27-03-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_adult` methods.
 * 
 * This method allows you to return the `adult` tag.
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
trait XFGMC_T_Variable_Get_Adult {

	/**
	 * Get `adult` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324508
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:adult>yes</g:adult>`.
	 */
	public function get_adult( $tag_name = 'g:adult', $result_xml = '' ) {

		$adult = common_option_get(
			'xfgmc_adult',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $adult === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_variable_product_post_meta( 'adult' );
			if ( $tag_value === 'disabled' ) {
				return $result_xml;
			}
			if ( $tag_value === 'default' ) {
				$tag_value = '';
			}
			if ( empty( $tag_value ) ) {
				$adult_default_value = common_option_get(
					'xfgmc_adult_default_value',
					'disabled',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( $adult_default_value === 'alltrue' ) {
					$tag_value = 'true';
				} else if ( $adult_default_value === 'allfalse' ) {
					$tag_value = 'false';
				}
			}
			$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}