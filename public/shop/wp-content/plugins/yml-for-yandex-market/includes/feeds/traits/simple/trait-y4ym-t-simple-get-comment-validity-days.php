<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      5.0.23
 * @version    5.0.23 (15-11-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_comment_validity_days` method.
 * 
 * This method allows you to return the `comment-validity-days` tag.
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
trait Y4YM_T_Simple_Get_Comment_Validity_Days {

	/**
	 * Get `comment-validity-days` tag.
	 * 
	 * @see https://yandex.ru/support/merchants/ru/offers
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<comment-validity-days>Хранить в сухом помещении.</comment-validity-days>`.
	 */
	public function get_comment_validity_days( $tag_name = 'comment-validity-days', $result_xml = '' ) {

		$comment_validity_days = common_option_get(
			'y4ym_comment_validity_days',
			false,
			$this->get_feed_id(),
			'y4ym'
		);
		if ( $comment_validity_days === 'enabled' ) {
			$tag_value = $this->get_simple_product_post_meta( 'comment_validity_days' );
			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		}
		return $result_xml;

	}

}