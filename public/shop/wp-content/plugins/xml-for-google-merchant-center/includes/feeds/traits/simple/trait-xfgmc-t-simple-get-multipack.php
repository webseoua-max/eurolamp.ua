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
 * The trait adds `get_multipack` method.
 * 
 * This method allows you to return the `multipack` tag.
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
trait XFGMC_T_Simple_Get_Multipack {

	/**
	 * Get `multipack` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324488
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:multipack>6</g:multipack>`.
	 */
	public function get_multipack( $tag_name = 'g:multipack', $result_xml = '' ) {

		$multipack = common_option_get(
			'xfgmc_multipack',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $multipack === 'enabled' ) {
			$tag_value = $this->get_simple_product_post_meta( 'multipack' );
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}