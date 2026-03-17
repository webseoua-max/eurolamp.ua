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
 * The trait adds `get_unit_pricing_measure` methods.
 * 
 * This method allows you to return the `unit_pricing_measure` tag.
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
trait XFGMC_T_Variable_Get_Unit_Pricing_Measure {

	/**
	 * Get `unit_pricing_measure` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324455
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:unit_pricing_measure>750 ml</g:unit_pricing_measure>`
	 */
	public function get_unit_pricing_measure( $tag_name = 'g:unit_pricing_measure', $result_xml = '' ) {

		$unit_pricing_measure = common_option_get(
			'xfgmc_unit_pricing_measure',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $unit_pricing_measure === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_variable_product_post_meta( 'unit_pricing_measure' );
			if ( class_exists( 'WooCommerce_Germanized' ) ) {
				if ( empty( wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit() ) ) {
					$unit_germanized = '';
				} else {
					$unit_germanized = ' ' . wc_gzd_get_gzd_product( $this->get_product()->get_id() )->get_unit();
				}

				// https://plugintests.com/plugins/wporg/woocommerce-germanized/latest/structure/classes/WC_GZD_Product 
				if ( ! empty( wc_gzd_get_gzd_product( $this->get_offer()->get_id() )->get_unit_product() ) ) {
					$tag_value = wc_gzd_get_gzd_product( $this->get_offer()->get_id() )->get_unit_product() . $unit_germanized;
				}
			}
			$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}