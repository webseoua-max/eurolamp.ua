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
 * The trait adds `get_price` methods.
 * 
 * This method allows you to return the `price` tags.
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
trait XFGMC_T_Simple_Get_Price {

	/**
	 * Get `price` tags.
	 * 
	 * @see https://support.google.com/merchants/answer/6324371
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:price>1500.00 RUB</g:price>`.
	 */
	public function get_price( $tag_name = 'g:price', $result_xml = '' ) {

		$price = common_option_get(
			'xfgmc_price',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $price === 'disabled' ) {
			return $result_xml;
		}
		/**
		 * $product->get_price() - актуальная цена (равна sale_price или regular_price если sale_price пуст)
		 * $product->get_regular_price() - обычная цена
		 * $product->get_sale_price() - цена скидки
		 */
		$tag_value = $this->get_product()->get_regular_price();
		$tag_value = apply_filters(
			'xfgmc_f_simple_price',
			$tag_value,
			[
				'product' => $this->get_product(),
				'product_category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);

		$xml_rules = common_option_get(
			'xfgmc_xml_rules',
			'merchant_center',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $xml_rules !== 'all_elements' ) {
			// если цены нет - пропускаем товар. Работает для всех правил кроме "Без правил"
			if ( $tag_value == 0 || empty( $tag_value ) ) {
				$this->add_skip_reason( [
					'offer_id' => $this->get_product()->get_id(),
					'reason' => __( 'The product has no price', 'xfgmc' ),
					'post_id' => $this->get_product()->get_id(),
					'file' => 'trait-xfgmc-t-simple-get-price.php',
					'line' => __LINE__
				] );
				return '';
			}
		}

		$skip_price_reason = apply_filters(
			'xfgmc_f_simple_skip_price_reason',
			false,
			[
				'tag_value' => $tag_value,
				'product_category_id' => $this->get_feed_category_id(),
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);
		if ( false === $skip_price_reason ) {
			$default_currency = common_option_get(
				'xfgmc_default_currency',
				'USD',
				$this->get_feed_id(),
				'xfgmc'
			);
			$tag_value = number_format( (float) $tag_value, wc_get_price_decimals(), '.', '' );
			$result_xml .= new XFGMC_Get_Paired_Tag(
				$tag_name,
				sprintf( '%s %s', $tag_value, $default_currency )
			);
		} else {
			$this->add_skip_reason( [
				'offer_id' => $this->get_product()->get_id(),
				'reason' => $skip_price_reason,
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-xfgmc-t-simple-get-price.php',
				'line' => __LINE__
			] );
			return '';
		}
		return $result_xml;

	}

}