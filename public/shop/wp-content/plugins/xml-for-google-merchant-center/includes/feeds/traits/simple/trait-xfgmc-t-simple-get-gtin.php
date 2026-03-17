<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_gtin` methods.
 * 
 * This method allows you to return the `gtin` tag.
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
trait XFGMC_T_Simple_Get_Gtin {

	/**
	 * Get `gtin` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324482
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:gtin>3234567890126</g:gtin>`.
	 */
	public function get_gtin( $tag_name = 'g:gtin', $result_xml = '' ) {

		$tag_value = '';

		$gtin = common_option_get(
			'xfgmc_gtin',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( 'disabled' === $gtin ) {
			return $result_xml;
		}

		switch ( $gtin ) {
			// disabled, no, sku, post_meta, germanized, upc-ean-generator, ean-for-woocommerce, id
			case "no":

				$identifier_exists = get_post_meta(
					$this->get_product()->get_id(),
					'_xfgmc_identifier_exists',
					true
				);
				if ( $identifier_exists !== 'no' ) {
					$result_xml .= "<g:gtin></g:gtin>" . PHP_EOL;
				}

				break;
			case "sku": // выгружать из артикула

				$tag_value = $this->get_product()->get_sku();

				break;
			case "post_meta":

				$gtin_post_meta_id = common_option_get(
					'xfgmc_gtin_post_meta',
					false,
					$this->get_feed_id(),
					'xfgmc'
				);
				$gtin_post_meta_id = trim( $gtin_post_meta_id );
				if ( get_post_meta( $this->get_product()->get_id(), $gtin_post_meta_id, true ) !== '' ) {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $gtin_post_meta_id, true );
				} else {
					$tag_value = '';
				}

				break;
			case "germanized":
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					if ( get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true ) !== '' ) {
						$tag_value = get_post_meta( $this->get_product()->get_id(), '_ts_gtin', true );
					}
				}

				break;
			case "upc-ean-generator":

				if ( get_post_meta( $this->get_product()->get_id(), 'usbs_gtin_field', true ) !== '' ) {
					$tag_value = get_post_meta( $this->get_product()->get_id(), 'usbs_gtin_field', true );
				}

				break;
			case "ean-for-woocommerce":

				if ( class_exists( 'Alg_WC_EAN' ) ) {
					if ( get_post_meta( $this->get_product()->get_id(), '_alg_ean', true ) !== '' ) {
						$tag_value = get_post_meta( $this->get_product()->get_id(), '_alg_ean', true );
					}
				}

				break;
			default:
				$tag_value = apply_filters(
					'xfgmc_f_simple_tag_value_switch_gtin',
					$tag_value,
					[ 
						'product' => $this->get_product(),
						'switch_value' => $gtin
					],
					$this->get_feed_id()
				);
				if ( $tag_value == '' ) {
					$tag_value = $this->get_simple_global_attribute_value( $gtin );
				}
		}

		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}