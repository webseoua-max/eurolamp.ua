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
 * The trait adds `get_size_type` methods.
 * 
 * This method allows you to return the `size_type` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *                          get_feed_category_id
 *             functions:   common_option_get
 */
trait XFGMC_T_Simple_Get_Size_Type {

	/**
	 * Get `size_type` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324497
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:size_type>petite</g:size_type>`
	 */
	public function get_size_type( $tag_name = 'g:size_type', $result_xml = '' ) {

		$size_type = common_option_get(
			'xfgmc_size_type',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $size_type === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = '';
			if ( empty( get_term_meta( $this->get_feed_category_id(), 'xfgmc_size_type', true ) )
				|| get_term_meta( $this->get_feed_category_id(), 'xfgmc_size_type', true ) === 'default' ) {
				$tag_value = $this->get_simple_global_attribute_value( $size_type );
			} else {
				$size_type = (int) get_term_meta( $this->get_feed_category_id(), 'xfgmc_size_type', true );
				$size_type_xml = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $size_type ) );
				if ( ! empty( $size_type_xml ) ) {
					$tag_value = ucfirst( xfgmc_replace_decode( $size_type_xml ) );
				} else {
					$size_type_alt = get_term_meta( $this->get_feed_category_id(), 'xfgmc_size_type_alt', true );
					if ( $size_type_alt !== 'default' ) {
						$tag_value = $size_type_alt;
					}
				}
			}
		}

		if ( empty( $tag_value ) ) {
			$size_type_default_value = common_option_get(
				'xfgmc_size_type_default_value',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $size_type_default_value ) && $size_type_default_value !== 'disabled' ) {
				$tag_value = $size_type_default_value;
			}
		}

		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}