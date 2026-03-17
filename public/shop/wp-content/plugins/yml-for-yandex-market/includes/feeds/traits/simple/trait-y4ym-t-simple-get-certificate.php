<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      5.0.23
 * @version    5.0.23 (15-11-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_certificate` method.
 * 
 * This method allows you to return the `certificate` tag.
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     Y4YM_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *                          get_simple_tag
 *             functions:   common_option_get
 */
trait Y4YM_T_Simple_Get_Certificate {

	/**
	 * Get `certificate` tag.
	 * 
	 * @see https://yandex.ru/support/merchants/ru/offers
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<certificate>6241421</certificate>`.
	 */
	public function get_certificate( $tag_name = 'certificate', $result_xml = '' ) {

		$certificate = common_option_get(
			'y4ym_certificate',
			false,
			$this->get_feed_id(),
			'y4ym'
		);
		if ( $certificate === 'enabled' ) {
			$tag_value = $this->get_simple_product_post_meta( 'certificate' );
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}