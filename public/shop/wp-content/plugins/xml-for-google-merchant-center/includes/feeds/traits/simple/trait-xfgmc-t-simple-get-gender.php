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
 * The trait adds `get_gender` methods.
 * 
 * This method allows you to return the `gender` tag.
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
trait XFGMC_T_Simple_Get_Gender {

	/**
	 * Get `gender` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324479
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:gender>male</g:gender>`
	 */
	public function get_gender( $tag_name = 'g:gender', $result_xml = '' ) {

		$gender = common_option_get(
			'xfgmc_gender',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $gender === 'disabled' ) {
			return $result_xml;
		} else {
			$tag_value = $this->get_simple_global_attribute_value( $gender );
		}

		if ( empty( $tag_value ) ) {
			$gender_default_value = common_option_get(
				'xfgmc_gender_default_value',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $gender_default_value ) && $gender_default_value !== 'disabled' ) {
				$tag_value = $gender_default_value;
			}
		}

		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}