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
 * The trait adds `get_is_bundle` method.
 * 
 * This method allows you to return the `is_bundle` tag.
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
trait XFGMC_T_Simple_Get_Is_Bundle {

	/**
	 * Get `is_bundle` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324449
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:is_bundle>yes</g:is_bundle>`.
	 */
	public function get_is_bundle( $tag_name = 'g:is_bundle', $result_xml = '' ) {

		$is_bundle = common_option_get(
			'xfgmc_is_bundle',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $is_bundle === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_simple_product_post_meta( 'is_bundle' );
			if ( $tag_value === 'disabled' ) {
				return $result_xml;
			}
			if ( empty( $tag_value ) || $tag_value === 'default' ) {
				$is_bundle_default_value = common_option_get(
					'xfgmc_is_bundle_default_value',
					'disabled',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( $is_bundle_default_value == 'alltrue' ) {
					$tag_value = 'true';
				} else if ( $is_bundle_default_value == 'allfalse' ) {
					$tag_value = 'false';
				}
			}
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}