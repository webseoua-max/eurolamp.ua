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
 * The trait adds `get_availability` method.
 * 
 * This method allows you to return the `availability` tag.
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
 *                          univ_option_get
 */
trait XFGMC_T_Variable_Get_Availability {

	/**
	 * Get `availability` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324448
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:availability>in_stock</g:availability>`.
	 */
	public function get_availability( $tag_name = 'g:availability', $result_xml = '' ) {

		$availability = common_option_get(
			'xfgmc_availability',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $availability === 'disabled' ) {
			return $result_xml;
		}

		$xml_rules = common_option_get(
			'xfgmc_xml_rules',
			'merchant_center',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $xml_rules === 'facebook' ) {
			$in_stock = 'in stock';
			$out_of_stock = 'out of stock';
			$onbackorder = 'available for order';
		} else {
			$in_stock = 'in_stock';
			$out_of_stock = 'out_of_stock';
			$onbackorder = 'preorder';
		}

		if ( true === $this->get_offer()->get_manage_stock() ) { // включено управление запасом
			if ( $this->get_offer()->get_stock_quantity() > 0 ) {
				$tag_value = 'in_stock';
			} else {
				if ( $this->get_offer()->get_backorders() === 'no' ) { // предзаказ запрещен
					$tag_value = 'out_of_stock';
				} else {
					$behavior_onbackorder = common_option_get(
						'xfgmc_behavior_onbackorder',
						'true',
						$this->get_feed_id(),
						'xfgmc'
					);
					switch ( $behavior_onbackorder ) {
						case "out_of_stock":
							$tag_value = $out_of_stock;
							break;
						case "in_stock":
							$tag_value = $in_stock;
							break;
						case "onbackorder":
							$tag_value = $onbackorder;
							break;
						default:
							$tag_value = $onbackorder;
					}
				}
			}
		} else { // отключено управление запасом
			if ( $this->get_offer()->get_stock_status() === 'instock' ) {
				$tag_value = 'in_stock';
			} else if ( $this->get_offer()->get_stock_status() === 'outofstock' ) {
				$tag_value = 'out_of_stock';
			} else {
				$behavior_onbackorder = common_option_get(
					'xfgmc_behavior_onbackorder',
					'true',
					$this->get_feed_id(),
					'xfgmc'
				);
				switch ( $behavior_onbackorder ) {
					case "out_of_stock":
						$tag_value = $out_of_stock;
						break;
					case "in_stock":
						$tag_value = $in_stock;
						break;
					case "onbackorder":
						$tag_value = $onbackorder;
						break;
					default:
						$tag_value = $onbackorder;
				}
			}
		}

		$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}