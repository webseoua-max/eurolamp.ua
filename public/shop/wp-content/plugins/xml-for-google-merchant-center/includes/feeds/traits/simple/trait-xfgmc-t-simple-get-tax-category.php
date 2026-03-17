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
 * The trait adds `get_tax_category` method.
 * 
 * This method allows you to return the `tax` tag.
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
trait XFGMC_T_Simple_Get_Tax_Category {

	/**
	 * Get `tax_category` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/7569847
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:tax_category>apparel</g:tax_category>`.
	 */
	public function get_tax_category( $tag_name = 'g:tax_category', $result_xml = '' ) {

		$tax_category = common_option_get(
			'xfgmc_tax_category',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $tax_category === 'disabled' ) {
			return $result_xml;
		}

		if ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_tax_category', true ) == '' ) {
			if ( get_term_meta( $this->get_feed_category_id(), 'xfgmc_tax_category', true ) !== '' ) {
				$tag_value = get_term_meta( $this->get_feed_category_id(), 'xfgmc_tax_category', true );
				$tag_value = htmlspecialchars( $tag_value );
				$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
			} else {
				$result_xml = '';
			}
		} else {
			$tag_value = get_post_meta( $this->get_product()->get_id(), '_xfgmc_tax_category', true );
			$tag_value = htmlspecialchars( $tag_value );
			$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'xfgmc_f_simple_tag_tax_category',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'feed_category_id' => $this->get_feed_category_id(),
				'input_data_arr' => $this->get_input_data_arr()
			],
			$this->get_feed_category_id(),
		);

		return $result_xml;

	}

}