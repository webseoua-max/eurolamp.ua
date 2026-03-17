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
 * The trait adds `get_unit_pricing_base_measure` method.
 * 
 * This method allows you to return the `unit_pricing_base_measure` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *             functions:   
 */
trait XFGMC_T_Variable_Get_Unit_Pricing_Base_Measure {

	/**
	 * Get `unit_pricing_base_measure` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324490
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * @param string $depricated
	 * 
	 * @return string Example: `<g:unit_pricing_base_measure>100oz</g:unit_pricing_base_measure>`.
	 */
	public function get_unit_pricing_base_measure( $tag_name = 'g:unit_pricing_base_measure', $result_xml = '' ) {

		$tag_value = '';
		$tag_value = $this->get_variable_product_post_meta( 'unit_pricing_base_measure' );
		if ( class_exists( 'WooCommerce_Germanized' ) ) {
			if ( empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit() ) ) {
				$unit_germanized = '';
			} else {
				$unit_germanized = wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit();
			}
			if ( ! empty( wc_gzd_get_gzd_product( $this->get_offer()->get_id() )->get_unit_base() ) ) {
				$tag_value = trim( sprintf( '%s %s',
					wc_gzd_get_gzd_product( $this->get_offer()->get_id() )->get_unit_base(),
					$unit_germanized
				) );
			}
		}
		if ( ! empty( $tag_value ) ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
		}
		$result_xml = apply_filters(
			'xgfmc_f_variable_tag_unit_pricing_base_measure',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}