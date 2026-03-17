<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.0.18 (31-07-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_tn_ved_codes` method.
 * 
 * This method allows you to return the `tn-ved-codes`, `tn-ved-code` tags.
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     Y4YM_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *             functions:   common_option_get
 *                          get_nested_tag
 */
trait Y4YM_T_Variable_Get_Tn_Ved_Codes {

	/**
	 * Get `tn-ved-codes`, `tn-ved-code` tags.
	 * 
	 * @see https://yandex.ru/support/marketplace/assortment/fields/index.html
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<tn-ved-codes><tn-ved-code>8517610008</tn-ved-code></tn-ved-codes>`.
	 */
	public function get_tn_ved_codes( $wrapper_tag_name = 'tn-ved-codes', $result_xml = '' ) {

		$tn_ved_code = common_option_get(
			'y4ym_tn_ved_code',
			'disabled',
			$this->get_feed_id(),
			'y4ym'
		);
		if ( $tn_ved_code === 'enabled' ) {
			$tag_value = $this->get_variable_product_post_meta( 'tn_ved_code' );
			if ( ! empty( $tag_value ) ) {
				$yml_rules = common_option_get(
					'y4ym_yml_rules',
					'yandex_market_assortment',
					$this->get_feed_id(),
					'y4ym'
				);
				if ( $yml_rules === 'aliexpress' ) {
					$result_xml = $this->get_variable_tag( 'tnved', $tag_value );
				} else {
					$result_xml = get_nested_tag( $wrapper_tag_name, 'tn-ved-code', $tag_value );
				}
			}
		}
		$result_xml = apply_filters(
			'y4ym_f_variable_tag_tn_ved_code',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}