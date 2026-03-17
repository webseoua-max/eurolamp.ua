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
 * The trait adds `get_shipping` methods.
 * 
 * This method allows you to return the `shipping` tag.
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
trait XFGMC_T_Simple_Get_Shipping {

	/**
	 * Get `shipping` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324484
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:shipping>Ударная дрель Makita HP1630, 710 Вт</g:shipping>`
	 */
	public function get_shipping( $tag_name = 'g:shipping', $result_xml = '' ) {

		$shipping = common_option_get(
			'xfgmc_shipping',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $shipping === 'disabled' ) {
			return $result_xml;
		}

		$default_currency = common_option_get(
			'xfgmc_default_currency',
			'RUB',
			$this->get_feed_id(),
			'xfgmc'
		);
		$default_currency = apply_filters(
			'xfgmc_f_default_currency',
			$default_currency,
			[ 
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);

		$shipping_country = common_option_get(
			'xfgmc_shipping_country',
			'',
			$this->get_feed_id(),
			'xfgmc'
		);

		$delivery_area_type = common_option_get(
			'xfgmc_delivery_area_type',
			'region',
			$this->get_feed_id(),
			'xfgmc'
		);

		$delivery_area_value = common_option_get(
			'xfgmc_delivery_area_value',
			'',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( $shipping_country !== '' && $delivery_area_type !== '' && $delivery_area_value !== '' ) {

			$result_xml .= new XFGMC_Get_Open_Tag( 'g:shipping' );
			$result_xml .= new XFGMC_Get_Paired_Tag( 'g:country', $shipping_country );
			$result_xml .= new XFGMC_Get_Paired_Tag(
				'g:' . $delivery_area_type,
				$delivery_area_value
			);

			$shipping_price = common_option_get(
				'xfgmc_shipping_price',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $shipping_service ) ) {
				$result_xml .= new XFGMC_Get_Paired_Tag( '<g:service>', $shipping_service );
			}

			$shipping_service = common_option_get(
				'xfgmc_shipping_service',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $shipping_price ) ) {
				$result_xml .= new XFGMC_Get_Paired_Tag( 'g:price', $shipping_price );
			}

			$result_xml .= new XFGMC_Get_Closed_Tag( 'g:shipping' );

		}

		$tag_value = $this->get_simple_product_post_meta( 'min_handling_time' );
		$result_xml .= $this->get_simple_tag( 'g:min_handling_time', $tag_value );

		$tag_value = $this->get_simple_product_post_meta( 'max_handling_time' );
		$result_xml .= $this->get_simple_tag( 'g:max_handling_time', $tag_value );

		$tag_value = $this->get_simple_product_post_meta( 'shipping_label' );
		$result_xml .= $this->get_simple_tag( 'g:shipping_label', $tag_value );

		return $result_xml;

	}

}