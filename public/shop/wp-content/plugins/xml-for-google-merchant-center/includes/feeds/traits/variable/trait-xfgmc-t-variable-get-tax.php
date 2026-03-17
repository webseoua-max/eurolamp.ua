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
 * The trait adds `get_tax` method.
 * 
 * This method allows you to return the `tax` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *                          get_variable_product_post_meta
 *                          get_variable_tag
 *             functions:   common_option_get
 */
trait XFGMC_T_Variable_Get_Tax {

	/**
	 * Get `tax` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324454
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:tax>6</g:tax>`.
	 */
	public function get_tax( $tag_name = 'g:tax', $result_xml = '' ) {

		$tax = common_option_get(
			'xfgmc_tax',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $tax === 'disabled' ) {
			return $result_xml;
		}

		$result_xml .= new XFGMC_Get_Open_Tag( 'g:tax' );
		$result_xml .= new XFGMC_Get_Paired_Tag( 'g:country', 'US' );

		$tax_region = common_option_get(
			'xfgmc_tax_region',
			'Washington',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( ! empty( $tax_region ) ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:region', $tax_region );
		}

		$tax_rate = common_option_get(
			'xfgmc_tax_rate',
			'',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( ! empty( $tax_rate ) ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:rate', $tax_rate );
		}

		$sipping_tax = common_option_get(
			'xfgmc_sipping_tax',
			'no',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( ! empty( $sipping_tax ) ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:tax_ship', $sipping_tax );
		}
		$result_xml .= new XFGMC_Get_Closed_Tag( 'g:tax' );

		return $result_xml;

	}

}