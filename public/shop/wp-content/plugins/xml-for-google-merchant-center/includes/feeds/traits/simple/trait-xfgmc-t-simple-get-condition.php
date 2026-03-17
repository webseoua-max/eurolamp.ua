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
 * The trait adds `get_condition` methods.
 * 
 * This method allows you to return the `condition` tag.
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
trait XFGMC_T_Simple_Get_Condition {

	/**
	 * Get `condition` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324469
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:condition>used</g:condition>`
	 */
	public function get_condition( $tag_name = 'g:condition', $result_xml = '' ) {

		$condition = common_option_get(
			'xfgmc_condition',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $condition === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_simple_product_post_meta( 'condition' );
			if ( $tag_value === 'disabled' ) {
				return $result_xml;
			}
			if ( empty( $tag_value ) || $tag_value === 'default' ) {
				$condition_default_value = common_option_get(
					'xfgmc_condition_default_value',
					'disabled',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( $condition_default_value !== 'disabled' ) {
					$tag_value = $condition_default_value;
				}
			}
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}