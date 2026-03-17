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
 * The trait adds `get_title` methods.
 * 
 * This method allows you to return the `title` tag.
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
trait XFGMC_T_Simple_Get_Title {

	/**
	 * Get `title` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324415
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:title>Ударная дрель Makita HP1630, 710 Вт</g:title>`
	 */
	public function get_title( $tag_name = 'g:title', $result_xml = '' ) {

		$title = common_option_get(
			'xfgmc_product_title',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( 'disabled' === $title ) {
			return '';
		}

		$result_xml_title = $this->get_product()->get_title();
		$result_xml_title = apply_filters(
			'xfgmc_f_simple_tag_value_title',
			$result_xml_title,
			[ 
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);
		$result_xml = new XFGMC_Get_Paired_Tag(
			$tag_name,
			htmlspecialchars( $result_xml_title, ENT_NOQUOTES )
		);
		$result_xml = apply_filters(
			'xfgmc_f_simple_tag_title',
			$result_xml,
			[ 
				'product' => $this->get_product()
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}