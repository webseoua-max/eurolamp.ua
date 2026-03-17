<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.8 (19-11-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_image_link` and `skip_gif` methods.
 * 
 * This method allows you to return the `image_link` tags.
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
trait XFGMC_T_Variable_Get_Image_Link {

	/**
	 * Get `image_link` tags.
	 * 
	 * @see https://support.google.com/merchants/answer/6324350
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:image_link>http://best.seller.ru/img/device12346-front.jpg</g:image_link>`
	 */
	public function get_image_link( $tag_name = 'g:image_link', $result_xml = '' ) {

		$image_link = common_option_get(
			'xfgmc_image_link',
			'full',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $image_link === 'disabled' ) {
			return $result_xml;
		} else {
			$size_pic = $image_link;
		}

		$thumb_xml = get_the_post_thumbnail_url( $this->get_offer()->get_id(), $size_pic );
		if ( empty( $thumb_xml ) ) {
			if ( has_post_thumbnail( $this->get_product()->get_id() ) ) {
				$thumb_id = get_post_thumbnail_id( $this->get_product()->get_id() );
				$thumb_url = wp_get_attachment_image_src( $thumb_id, $size_pic, true );
				$tag_value = $thumb_url[0]; // урл оригинал миниатюры товара
				$tag_value = get_from_url( $tag_value );
				$result_xml = $this->skip_gif( $tag_name, $tag_value );
			}
		} else {
			$result_xml = $this->skip_gif( $tag_name, $thumb_xml );
		}
		$no_default_png_products = common_option_get(
			'xfgmc_no_default_png_products',
			'disabled',
			$this->get_feed_id(), 'xfgmc'
		);
		if ( $no_default_png_products === 'enabled' ) {
			// включён пропуск default.png из фида
			if ( false !== strpos( $result_xml, 'default.' ) ) {
				$result_xml = '';
			}
		}

		$result_xml = apply_filters(
			'xfgmc_f_variable_tag_image_link',
			$result_xml,
			[
				'product' => $this->get_product(),
				'offer' => $this->get_offer(),
				'size_pic' => $size_pic
			],
			$this->get_feed_id()
		);

		// пропускаем вариации без картинок
		$skip_products_without_pic = common_option_get(
			'xfgmc_skip_products_without_pic',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( ( $skip_products_without_pic === 'enabled' ) && ( empty( $result_xml ) ) ) {
			$this->add_skip_reason( [
				'offer_id' => $this->get_offer()->get_id(),
				'reason' => __( 'Product has no images', 'xml-for-google-merchant-center' ),
				'post_id' => $this->get_offer()->get_id(),
				'file' => 'trait-xfgmc-t-variable-get-image-link.php',
				'line' => __LINE__
			] );
			return '';
		} else {
			$result_xml = xfgmc_replace_domain( $result_xml, $this->get_feed_id() );
		}
		return $result_xml;

	}

	/**
	 * Skip `gif` and `svg` files.
	 * 
	 * @param string $tag_name
	 * @param string $tag_value
	 * 
	 * @return string
	 */
	public function skip_gif( $tag_name, $tag_value ) {

		// удаляем из фида gif и svg картинки
		if ( false === strpos( $tag_value, '.gif' )
			&& false === strpos( $tag_value, '.svg' ) ) {
			$tag_value = get_from_url( $tag_value, 'url' ); // ? оправдано ли
			$image_link_xml = new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
		} else {
			$image_link_xml = '';
		}
		return $image_link_xml;

	}

}