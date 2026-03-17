<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_custom_label` methods.
 * 
 * This method allows you to return the `custom_label` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *             functions:   common_option_get
 */
trait XFGMC_T_Variable_Get_Custom_Label {

	/**
	 * Get `custom_label` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324473
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:custom_label_0>summer</g:custom_label_0>`.
	 */
	public function get_custom_label( $tag_name = 'g:custom_label', $result_xml = '' ) {

		$custom_label = common_option_get(
			'xfgmc_custom_labels',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $custom_label === 'enabled' ) {
			for ( $i = 0; $i < 5; $i++ ) {
				$meta_name = sprintf( '_xfgmc_custom_label_%s', $i );
				if ( get_post_meta( $this->get_product()->get_id(), $meta_name, true ) !== '' ) {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $meta_name, true );
					$result_xml .= $this->get_variable_tag( $tag_name, $tag_value );
				}
			}
		}
		return $result_xml;

	}

}