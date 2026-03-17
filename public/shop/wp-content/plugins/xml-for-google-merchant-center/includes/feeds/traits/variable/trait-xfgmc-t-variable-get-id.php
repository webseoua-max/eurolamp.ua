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
 * The trait adds `get_id` method.
 * 
 * This method allows you to return the `id` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *                          get_variable_tag
 *             functions:   common_option_get
 */
trait XFGMC_T_Variable_Get_Id {

	/**
	 * Get `id` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324405
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:id>542</g:id>`.
	 */
	public function get_id( $tag_name = 'g:id', $result_xml = '' ) {

		$source_id = common_option_get(
			'xfgmc_source_id',
			'default',
			$this->get_feed_id(),
			'xfgmc'
		);
		switch ( $source_id ) {
			case 'sku':

				$sku_xml = $this->get_offer()->get_sku();
				if ( empty( $sku_xml ) ) {
					$this->add_skip_reason( [ 
						'offer_id' => $this->get_offer()->get_id(),
						'reason' => 'The SKU containing the product ID is missing or empty',
						'post_id' => $this->get_product()->get_id(),
						'file' => 'trait-xfgmc-t-variable-get-id.php',
						'line' => __LINE__
					] );
					return '';
				} else {
					$tag_value = $sku_xml;
				}

				break;
			case 'post_meta':

				$post_meta = common_option_get(
					'xfgmc_source_id_post_meta',
					'',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( $post_meta === '' ) {
					$this->add_skip_reason( [ 
						'offer_id' => $this->get_offer()->get_id(),
						'reason' => 'The meta field containing the product ID is missing or empty',
						'post_id' => $this->get_product()->get_id(),
						'file' => 'trait-xfgmc-t-variable-get-id.php',
						'line' => __LINE__
					] );
					return '';
				}

				if ( get_post_meta( $this->get_offer()->get_id(), $post_meta, true ) !== '' ) {
					$tag_value = get_post_meta( $this->get_offer()->get_id(), $post_meta, true );
				} else {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $post_meta, true );
				}
				if ( empty( $tag_value ) ) {
					$this->add_skip_reason( [ 
						'offer_id' => $this->get_offer()->get_id(),
						'reason' => 'The meta field containing the product ID is missing or empty',
						'post_id' => $this->get_product()->get_id(),
						'file' => 'trait-xfgmc-t-variable-get-id.php',
						'line' => __LINE__
					] );
					return '';
				}

				break;
			case 'germanized':

				$tag_value = '';
				// TODO: добавить поддержку плагина germanized

				break;
			default:

				$tag_value = $this->get_offer()->get_id();
		}
		$result_xml = $this->get_variable_tag( $tag_name, htmlspecialchars( $tag_value ) );
		return $result_xml;

	}

}