<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.1 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_mpn` methods.
 * 
 * This method allows you to return the `mpn` tag.
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
trait XFGMC_T_Simple_Get_Mpn {

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

				$tag_value = $this->get_product()->get_sku();
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_sku();
				}

				break;
			case "products_id": // выгружать из id вариации

				$tag_value = $this->get_product()->get_id();

				break;
			case "post_meta":

				$mpn_post_meta = common_option_get(
					'xfgmc_mpn_post_meta',
					'',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( get_post_meta( $this->get_product()->get_id(), $mpn_post_meta, true ) == '' ) {
					$tag_value = '';
				} else {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $mpn_post_meta, true );
				}

				break;
			default:

				$tag_value = apply_filters(
					'xfgmc_f_simple_tag_value_switch_mpn',
					'',
					[ 
						'product' => $this->get_product(),
						'switch_value' => $mpn
					],
					$this->get_feed_id()
				);
			// if ( $tag_value == '' ) {
			// 	$tag_value = $this->get_simple_global_attribute_value( $mpn );
			// }
		}
		// ! обернул $tag_value в htmlspecialchars т.к у нас могут быть амперсанды
		$result_xml = $this->get_simple_tag( $tag_name, htmlspecialchars( $tag_value ) );
		return $result_xml;

	}

}