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
 * The trait adds `get_link` method.
 * 
 * This method allows you to return the `link` tag.
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
trait XFGMC_T_Simple_Get_Link {

	/**
	 * Get `link` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324416
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:link>http://best.seller.ru/product_page.asp?pid=12346</g:link>`
	 */
	public function get_link( $tag_name = 'g:link', $result_xml = '' ) {

		$link = common_option_get(
			'xfgmc_link',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( $link === 'disabled' ) {
			return $result_xml;
		}

		$tag_value = htmlspecialchars( get_permalink( $this->get_product()->get_id() ) );
		$clear_get = common_option_get(
			'xfgmc_clear_get',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $clear_get === 'enabled' ) {
			$tag_value = get_from_url( $tag_value, 'url' );
		}

		// ? это избавляет от двойного кодирования, но отдука она пока хз 
		// ? @see https://wordpress.org/support/topic/работает-отлично-после-небольшой-дор/
		$tag_value = urldecode( $tag_value );

		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		$result_xml = xfgmc_replace_domain( $result_xml, $this->get_feed_id() );

		return $result_xml;

	}

}