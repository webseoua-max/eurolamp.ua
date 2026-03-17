<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.9 (23-12-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_sale_price` methods.
 * 
 * This method allows you to return the `sale_price` tags.
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
trait XFGMC_T_Simple_Get_Sale_Price {

	/**
	 * Get `sale_price` tags.
	 * 
	 * @see https://support.google.com/merchants/answer/6324471
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:sale_price>15.00 USD</g:sale_price>`.
	 */
	public function get_sale_price( $tag_name = 'g:sale_price', $result_xml = '' ) {

		$sale_price = common_option_get(
			'xfgmc_sale_price',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $sale_price === 'disabled' ) {
			return $result_xml;
		}
		$sale_price_value = (float) $this->get_product()->get_sale_price();
		if ( $sale_price_value > 0 ) {
			$sale_price_value = $this->get_product()->get_price();
			$sale_price_value = apply_filters(
				'xfgmc_f_simple_price',
				$sale_price_value,
				[
					'product' => $this->get_product(),
					'product_category_id' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			$default_currency = common_option_get(
				'xfgmc_default_currency',
				'USD',
				$this->get_feed_id(),
				'xfgmc'
			);
			$sale_price_value = number_format( (float) $sale_price_value, wc_get_price_decimals(), '.', '' );
			$result_xml .= new XFGMC_Get_Paired_Tag(
				$tag_name,
				sprintf( '%s %s', $sale_price_value, $default_currency )
			);

			$sales_price_from = $this->get_product()->get_date_on_sale_from();
			$sales_price_to = $this->get_product()->get_date_on_sale_to();
			if ( ! empty( $sales_price_from ) && ! empty( $sales_price_to ) ) {
				$sales_price_from = date( DATE_ISO8601, strtotime( $sales_price_from ) );
				$sales_price_to = date( DATE_ISO8601, strtotime( $sales_price_to ) );
				$result_xml .= new XFGMC_Get_Paired_Tag(
					'g:sale_price_effective_date',
					sprintf( '%s/%s', $sales_price_from, $sales_price_to )
				);
			}
		}
		return $result_xml;

	}

}