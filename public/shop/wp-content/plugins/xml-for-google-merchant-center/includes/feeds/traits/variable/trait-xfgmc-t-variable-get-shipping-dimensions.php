<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      4.0.4
 * @version    4.1.0 (22-03-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_shipping_dimensions` method.
 * 
 * This method allows you to return the `shipping_dimensions` tag.
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
trait XFGMC_T_Variable_Get_Shipping_Dimensions {

	/**
	 * Get `dimensions` tag or `<g:shipping_length>20 in</g:shipping_length>`, `<g:shipping_width>40 in</g:shipping_width>`,
	 * `<g:shipping_height>10 in</g:shipping_height>`, `<g:shipping_weight>3.5 lb</g:shipping_weight>`.
	 * 
	 * @see https://support.google.com/merchants/answer/6324498
	 *      https://support.google.com/merchants/answer/6324503
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:shipping_length>20 in</g:shipping_length>`
	 */
	public function get_shipping_dimensions( $tag_name = 'shipping_dimensions', $result_xml = '' ) {

		// * к сожалению wc_get_dimension не всегда возвращает float и юзер может передать в размер что-то типа '13-18'
		$length_xml = 0;
		$width_xml = 0;
		$height_xml = 0;
		$shipping_weight_xml = 0;
		$length = common_option_get(
			'xfgmc_shipping_length',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $length ) || $length === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$length_xml = $this->get_offer()->get_length();
				if ( ! empty( $length_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$length_xml = round( wc_get_dimension( $length_xml, 'cm' ), 3 );
				}
			}
		} else {
			$length = (int) $length;
			$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $length ) );
			if ( empty( $tag_value ) ) {
				$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $length ) );
			}

			$length_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$width = common_option_get(
			'xfgmc_shipping_width',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $width ) || $width === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$width_xml = $this->get_offer()->get_width();
				if ( ! empty( $width_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$width_xml = round( wc_get_dimension( $width_xml, 'cm' ), 3 );
				}
			}
		} else {
			$width = (int) $width;
			$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $width ) );
			if ( empty( $tag_value ) ) {
				$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $width ) );
			}
			$width_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$height = common_option_get(
			'xfgmc_shipping_height',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $height ) || $height === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$height_xml = $this->get_offer()->get_height();
				if ( ! empty( $height_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$height_xml = round( wc_get_dimension( $height_xml, 'cm' ), 3 );
				}
			}
		} else {
			$height = (int) $height;
			$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $height ) );
			if ( empty( $tag_value ) ) {
				$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $height ) );
			}
			$height_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$shipping_weight = common_option_get(
			'xfgmc_shipping_weight',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( empty( $shipping_weight ) || $shipping_weight === 'woo_shippings' ) {
			$shipping_weight_xml = $this->get_offer()->get_height();
			if ( ! empty( $shipping_weight_xml ) && get_option( 'woocommerce_weight_unit' ) !== 'kg' ) {
				$shipping_weight_xml = round( wc_get_weight( $shipping_weight_xml, 'kg' ), 3 );
			}
		} else {
			$shipping_weight = (int) $shipping_weight;
			$tag_value = $this->get_offer()->get_attribute( wc_attribute_taxonomy_name_by_id( $shipping_weight ) );
			if ( empty( $tag_value ) ) {
				$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $shipping_weight ) );
			}
			$shipping_weight_xml = round( wc_get_weight( (float) $tag_value, 'kg' ), 3 );
		}

		if ( $length_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:shipping_length', sprintf( '%s cm', $length_xml ) );
		}
		if ( $width_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:shipping_width', sprintf( '%s cm', $width_xml ) );
		}
		if ( $height_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:shipping_height', sprintf( '%s cm', $height_xml ) );
		}
		if ( $shipping_weight_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:shipping_weight', sprintf( '%s kg', $shipping_weight_xml ) );
		}

		$result_xml = apply_filters(
			'xfgmc_f_variable_tag_dimensions',
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