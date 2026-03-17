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
 * The trait adds `get_store_code` method.
 * 
 * This method allows you to return the `store_code` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *                          get_simple_product_post_meta
 *                          get_simple_tag
 *             functions:   common_option_get
 */
trait XFGMC_T_Simple_Get_Store_Code {

	/**
	 * Get `store_code` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/13869896
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:store_code>Магазин123</g:store_code>`.
	 */
	public function get_store_code( $tag_name = 'g:store_code', $result_xml = '' ) {

		$store_code = common_option_get(
			'xfgmc_store_code',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $store_code === 'enabled' ) {
			$tag_value = $this->get_simple_product_post_meta( 'store_code' );
			if ( empty( $tag_value ) ) {
				$tag_value = common_option_get(
					'xfgmc_store_code_default_value',
					'',
					$this->get_feed_id(),
					'xfgmc'
				);
			}
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}