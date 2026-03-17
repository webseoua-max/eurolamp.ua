<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.1 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_mpn` method.
 * 
 * This method allows you to return the `mpn` tag.
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
trait XFGMC_T_Variable_Get_Mpn {

	/**
	 * Get `mpn` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324482
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:mpn>GO12345OOGLE</g:mpn>`.
	 */
	public function get_mpn( $tag_name = 'g:mpn', $result_xml = '' ) {

		$mpn = common_option_get(
			'xfgmc_mpn',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( 'disabled' === $mpn ) {
			return $result_xml;
		}
		switch ( $mpn ) {
			case "no":

				$tag_value = '';
				$identifier_exists = get_post_meta(
					$this->get_product()->get_id(),
					'_xfgmc_identifier_exists',
					true
				);
				if ( $identifier_exists !== 'no' ) {
					$result_xml .= "<g:mpn></g:mpn>" . PHP_EOL;
				}

				break;
			case "sku": // выгружать из артикула

				$tag_value = $this->get_offer()->get_sku();
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_sku();
				}

				break;
			case "products_id": // выгружать из id вариации

				$tag_value = $this->get_offer()->get_id();

				break;
			default:

				$tag_value = apply_filters(
					'xfgmc_f_variable_tag_value_switch_mpn',
					'',
					[ 
						'product' => $this->get_product(),
						'offer' => $this->get_offer(),
						'switch_value' => $mpn
					],
					$this->get_feed_id()
				);
				if ( $tag_value == '' ) {
					$tag_value = $this->get_variable_global_attribute_value( $mpn );
				}
		}

		// ! обернул $tag_value в htmlspecialchars т.к у нас могут быть амперсанды
		$result_xml = $this->get_variable_tag( $tag_name, htmlspecialchars( $tag_value ) );
		return $result_xml;

	}

}