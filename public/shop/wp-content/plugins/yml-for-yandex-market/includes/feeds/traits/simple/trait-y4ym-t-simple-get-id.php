<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.0.23 (15-11-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_id` method.
 * 
 * This method allows you to return the `id` tag.
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     Y4YM_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *                          get_simple_tag
 *             functions:   common_option_get
 */
trait Y4YM_T_Simple_Get_Id {

	/**
	 * Get `id` tag.
	 * 
	 * @see
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<id>542</id>`.
	 */
	public function get_id( $tag_name = 'id', $result_xml = '' ) {

		$source_id = common_option_get(
			'y4ym_source_id',
			'default',
			$this->get_feed_id(),
			'y4ym'
		);
		switch ( $source_id ) {
			case 'sku':

				$sku_xml = $this->get_product()->get_sku();
				if ( empty( $sku_xml ) ) {
					$tag_value = htmlspecialchars( $sku_xml );
				} else {
					// ? возможно тут нужно делать пропуск товара тк нет источника ID
					$tag_value = $this->get_product()->get_id();
				}

				break;
			case 'post_meta':

				$post_meta = common_option_get(
					'y4ym_source_id_post_meta',
					'',
					$this->get_feed_id(),
					'y4ym'
				);
				if ( empty( $post_meta ) || get_post_meta( $this->get_product()->get_id(), $post_meta, true ) == '' ) {
					$tag_value = '';
					// ? возможно тут нужно делать пропуск товара тк нет источника ID
				} else {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $post_meta, true );
				}

				break;
			case 'germanized':

				$tag_value = '';
				if ( class_exists( 'WooCommerce_Germanized' ) ) {
					if ( get_post_meta( $this->get_product->get_id(), '_ts_gtin', true ) !== '' ) {
						$tag_value = get_post_meta( $this->get_product->get_id(), '_ts_gtin', true );
					}
				}

				break;
			default:
		}
		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}