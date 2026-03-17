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
 * The trait adds `get_identifier_exists` methods.
 * 
 * This method allows you to return the `identifier_exists` tag.
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

trait XFGMC_T_Variable_Get_Identifier_Exists {

	/**
	 * Get `identifier_exists` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324478
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:identifier_exists>no [нет]</g:identifier_exists>`.
	 */
	public function get_identifier_exists( $tag_name = 'g:identifier_exists', $result_xml = '' ) {

		$identifier_exists = common_option_get(
			'xfgmc_identifier_exists',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( 'disabled' === $identifier_exists ) {
			return $result_xml;
		}

		$intermediate_result_xml = '';
		if ( ( ! empty( get_post_meta( $this->get_product()->get_id(), '_xfgmc_identifier_exists', true ) ) )
			&& ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_identifier_exists', true ) !== 'off' )
			&& ( get_post_meta( $this->get_product()->get_id(), '_xfgmc_identifier_exists', true ) !== 'disabled' )
		) {
			$identifier_exists_val = get_post_meta( $this->get_product()->get_id(), '_xfgmc_identifier_exists', true );

			if ( $identifier_exists_val === 'no' ) {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, $identifier_exists_val );
				$intermediate_result_xml .= "<g:gtin></g:gtin>" . PHP_EOL;
				$intermediate_result_xml .= "<g:mpn></g:mpn>" . PHP_EOL;
			}

			if ( $identifier_exists_val === 'default' ) {
				$gtin = $this->get_gtin();
				$mpn = $this->get_mpn();
				if ( empty( $gtin ) && empty( $mpn ) ) {
					$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'no' );
				} else {
					$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'yes' );
					$intermediate_result_xml .= $gtin;
					$intermediate_result_xml .= $mpn;
				}
			} else {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, $identifier_exists_val );
			}
		} else if ( empty( get_post_meta( $this->get_product()->get_id(), '_xfgmc_identifier_exists', true ) ) ) {
			// условие сработает в тогда, когда тупо нет метаполя xfgmc_identifier_exists
			$gtin = $this->get_gtin();
			$mpn = $this->get_mpn();
			if ( empty( $gtin ) && empty( $mpn ) ) {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'no' );
			} else {
				$intermediate_result_xml .= new XFGMC_Get_Paired_Tag( $tag_name, 'yes' );
				$intermediate_result_xml .= $gtin;
				$intermediate_result_xml .= $mpn;
			}
		}

		$result_xml = apply_filters(
			'x4gmc_f_variable_tag_identifier_exists',
			$intermediate_result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer(),
				'result_xml' => $result_xml
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}