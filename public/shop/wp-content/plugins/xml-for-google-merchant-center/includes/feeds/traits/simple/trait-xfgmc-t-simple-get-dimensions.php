<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.1.1 (27-03-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_dimensions` method.
 * 
 * This method allows you to return the `dimensions` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_product
 *                          get_feed_id
 *             functions:   common_option_get
 */
trait XFGMC_T_Simple_Get_Dimensions {

	/**
	 * Get `dimensions` tag or `<g:product_length>20 in</g:product_length>`, `<g:product_width>40 in</g:product_width>`,
	 * `<g:product_height>10 in</g:product_height>`, `<g:product_weight>3.5 lb</g:product_weight>`.
	 * 
	 * @see https://support.google.com/merchants/answer/11018531
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:product_length>20 in</g:product_length>`
	 */
	public function get_dimensions( $tag_name = 'dimensions', $result_xml = '' ) {

		// * к сожалению wc_get_dimension не всегда возвращает float и юзер может передать в размер что-то типа '13-18'
		$length_xml = 0;
		$width_xml = 0;
		$height_xml = 0;
		$product_weight_xml = 0;
		$length = common_option_get(
			'xfgmc_length',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $length ) || $length === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$length_xml = $this->get_product()->get_length();
				if ( ! empty( $length_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$length_xml = round( wc_get_dimension( $length_xml, 'cm' ), 3 );
				}
			}
		} else {
			$length = (int) $length;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $length ) );
			$length_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$width = common_option_get(
			'xfgmc_width',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $width ) || $width === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$width_xml = $this->get_product()->get_width();
				if ( ! empty( $width_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$width_xml = round( wc_get_dimension( $width_xml, 'cm' ), 3 );
				}
			}
		} else {
			$width = (int) $width;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $width ) );
			$width_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$height = common_option_get(
			'xfgmc_height',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $height ) || $height === 'woo_shippings' ) {
			if ( $this->get_product()->has_dimensions() ) {
				$height_xml = $this->get_product()->get_height();
				if ( ! empty( $height_xml ) && get_option( 'woocommerce_dimension_unit' ) !== 'cm' ) {
					$height_xml = round( wc_get_dimension( $height_xml, 'cm' ), 3 );
				}
			}
		} else {
			$height = (int) $height;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $height ) );
			$height_xml = round( wc_get_dimension( (float) $tag_value, 'cm' ), 3 );
		}

		$product_weight = common_option_get(
			'xfgmc_product_weight',
			'woo_shippings',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( empty( $product_weight ) || $product_weight === 'woo_shippings' ) {
			$product_weight_xml = $this->get_product()->get_weight();
			if ( ! empty( $product_weight_xml ) && get_option( 'woocommerce_weight_unit' ) !== 'kg' ) {
				$product_weight_xml = round( wc_get_weight( $product_weight_xml, 'kg' ), 3 );
			}
		} else {
			$product_weight = (int) $product_weight;
			$tag_value = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $product_weight ) );
			$product_weight_xml = round( wc_get_weight( (float) $tag_value, 'kg' ), 3 );
		}

		if ( $length_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:product_length', sprintf( '%s cm', $length_xml ) );
		}
		if ( $width_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:product_width', sprintf( '%s cm', $width_xml ) );
		}
		if ( $height_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:product_height', sprintf( '%s cm', $height_xml ) );
		}
		if ( $product_weight_xml > 0 ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:product_weight', sprintf( '%s kg', $product_weight_xml ) );
		}

		$result_xml = apply_filters(
			'xfgmc_f_simple_tag_dimensions',
			$result_xml,
			[
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}