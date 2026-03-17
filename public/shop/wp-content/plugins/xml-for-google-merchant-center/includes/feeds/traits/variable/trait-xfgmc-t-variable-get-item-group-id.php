<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (02-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_item_group_id` methods.
 * 
 * This method allows you to return the `item_group_id` tag.
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
trait XFGMC_T_Variable_Get_Item_Group_Id {

	/**
	 * Get `item_group_id` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324507
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:item_group_id>153</g:item_group_id>`
	 */
	public function get_item_group_id( $tag_name = 'item_group_id', $result_xml = '' ) {

		$item_group_id = common_option_get(
			'xfgmc_item_group_id',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( $item_group_id === 'enabled' ) {
			$tag_value = $this->get_product()->get_id();
			$result_xml = $this->get_variable_tag( $tag_name, $tag_value );
		}

		return $result_xml;

	}

}
