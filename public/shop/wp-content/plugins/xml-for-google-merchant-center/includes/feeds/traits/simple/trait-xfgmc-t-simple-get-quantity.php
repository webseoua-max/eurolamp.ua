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
 * The trait adds `get_quantity` methods.
 * 
 * This method allows you to return the `quantity` tag.
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
trait XFGMC_T_Simple_Get_Quantity {

	/**
	 * Get `quantity` tag.
	 * 
	 * @see 
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:quantity>7</g:quantity>`.
	 */
	public function get_quantity( $tag_name = 'g:quantity', $result_xml = '' ) {

		$quantity = common_option_get(
			'xfgmc_quantity',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $quantity === 'enabled' ) {
			$tag_value = '';
			if ( true === $this->get_product()->get_manage_stock() ) {
				// включено управление запасом на уровне товара
				$stock_quantity = $this->get_product()->get_stock_quantity();
				if ( $stock_quantity > -1 ) {
					$tag_value = $stock_quantity;
				} else {
					$tag_value = (int) 0;
				}
			}
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}

		return $result_xml;

	}

}