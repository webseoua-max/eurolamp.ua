<?php

/**
 * Get unit for Simple Products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.1.0 (22-03-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds
 */

/**
 * Get unit for Simple Products.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     WC_Product_Variation
 *                          XFGMC_Get_Closed_Tag
 */
class XFGMC_Get_Unit_Offer_Simple extends XFGMC_Get_Unit_Offer {

	use XFGMC_T_Common_Get_CatId;
	use XFGMC_T_Common_Skips;

	use XFGMC_T_Simple_Get_Ads_Redirect;
	use XFGMC_T_Simple_Get_Adult;
	use XFGMC_T_Simple_Get_Age_Group;
	use XFGMC_T_Simple_Get_Availability_Date;
	use XFGMC_T_Simple_Get_Availability;
	use XFGMC_T_Simple_Get_Brand;
	use XFGMC_T_Simple_Get_Color;
	use XFGMC_T_Simple_Get_Condition;
	use XFGMC_T_Simple_Get_Custom_Label;
	use XFGMC_T_Simple_Get_Description;
	use XFGMC_T_Simple_Get_Dimensions;
	use XFGMC_T_Simple_Get_Fb_Product_Category;
	use XFGMC_T_Simple_Get_Gender;
	use XFGMC_T_Simple_Get_Google_Product_Category;
	use XFGMC_T_Simple_Get_Gtin;
	use XFGMC_T_Simple_Get_Id;
	use XFGMC_T_Simple_Get_Identifier_Exists;
	use XFGMC_T_Simple_Get_Image_Link;
	use XFGMC_T_Simple_Get_Is_Bundle;
	use XFGMC_T_Simple_Get_Item_Group_Id;
	use XFGMC_T_Simple_Get_Link;
	use XFGMC_T_Simple_Get_Material;
	use XFGMC_T_Simple_Get_Mobile_Link;
	use XFGMC_T_Simple_Get_Mpn;
	use XFGMC_T_Simple_Get_Multipack;
	use XFGMC_T_Simple_Get_Open_Item_Tag;
	use XFGMC_T_Simple_Get_Pattern;
	use XFGMC_T_Simple_Get_Price;
	use XFGMC_T_Simple_Get_Product_Type;
	use XFGMC_T_Simple_Get_Quantity;
	use XFGMC_T_Simple_Get_Sale_Price;
	use XFGMC_T_Simple_Get_Shipping;
	use XFGMC_T_Simple_Get_Shipping_Dimensions;
	use XFGMC_T_Simple_Get_Size_System;
	use XFGMC_T_Simple_Get_Size;
	use XFGMC_T_Simple_Get_Size_Type;
	use XFGMC_T_Simple_Get_Size;
	use XFGMC_T_Simple_Get_Store_Code;
	use XFGMC_T_Simple_Get_Tax_Category;
	use XFGMC_T_Simple_Get_Tax;
	use XFGMC_T_Simple_Get_Title;
	use XFGMC_T_Simple_Get_Unit_Pricing_Base_Measure;
	use XFGMC_T_Simple_Get_Unit_Pricing_Measure;

	/**
	 * Generating the `offer` tag with all the contents.
	 * 
	 * @param string $result_xml
	 * 
	 * @return string `<offer...>...tags...</offer>` or empty string.
	 */
	public function generation_product_xml( $result_xml = '' ) {

		$this->set_category_id();
		$this->get_skips();

		$feed_xml_rules = common_option_get(
			'xfgmc_xml_rules',
			false,
			$this->get_feed_id(),
			'xfgmc'
		);
		switch ( $feed_xml_rules ) {
			case "merchant_center":

				// Google Merchant Center
				$result_xml = $this->merchant_center();

				break;
			case "facebook":

				// Facebook
				$result_xml = $this->facebook();

				break;
			default:

				// Нет правил (Для опытных пользователей)
				$result_xml = $this->get_tags( $feed_xml_rules, $result_xml );
		}

		$result_xml = apply_filters(
			'xfgmc_f_append_simple_offer',
			$result_xml,
			[
				'product' => $this->get_product(),
				'feed_category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $result_xml ) ) {
			$result_xml .= new XFGMC_Get_Closed_Tag( 'item' );
			$result_xml = apply_filters(
				'xfgmc_f_after_simple_offer',
				$result_xml,
				[
					'product' => $this->get_product(),
					'feed_category_id' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
		}
		return $result_xml;

	}

	/**
	 * Google Merchant Center.
	 * 
	 * @see https://support.google.com/merchants/answer/7052112
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	private function merchant_center( $result_xml = '' ) {

		$result_xml .= $this->get_tags( 'merchant_center', $result_xml );
		return $result_xml;

	}

	/**
	 * Facebook.
	 * 
	 * @see https://www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	private function facebook( $result_xml = '' ) {

		$result_xml .= $this->get_tags( 'facebook', $result_xml );
		return $result_xml;

	}

	/**
	 * Нет правил (Для опытных пользователей).
	 * 
	 * @see 
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	private function all_elements( $result_xml = '' ) {

		$result_xml .= $this->get_tags( 'all_elements', $result_xml );
		return $result_xml;

	}

	/**
	 * Gets the value of the global attribute for the product.
	 * 
	 * @param int|string $attribute_id
	 * 
	 * @return string
	 */
	private function get_simple_global_attribute_value( $attribute_id ) {

		$attribute_id = (int) $attribute_id;
		if ( $attribute_id > 0 ) {
			$attr_val = $this->get_product()->get_attribute( wc_attribute_taxonomy_name_by_id( $attribute_id ) );
			if ( empty( $attr_val ) ) {
				$tag_value = '';
			} else {
				$tag_value = xfgmc_replace_decode( $attr_val );
			}
		} else {
			$tag_value = '';
		}
		return $tag_value;

	}

	/**
	 * Get product post meta.
	 * 
	 * @param string $key
	 * @param string $prefix
	 * 
	 * @return string
	 */
	public function get_simple_product_post_meta( $key, $prefix = '_xfgmc_' ) {

		if ( empty( $key ) ) {
			return '';
		} else {
			$key = $prefix . $key;
		}
		if ( get_post_meta( $this->get_product()->get_id(), $key, true ) !== '' ) {
			$value = get_post_meta( $this->get_product()->get_id(), $key, true );
		} else {
			$value = '';
		}
		return $value;

	}

	/**
	 * Get paired XML tag for feed. Example: `<tag_name>tag_value</tag_name>`.
	 * 
	 * This function processes the tag value using filters:
	 *    - `xfgmc_f_simple_tag_value_{tag_name}`
	 *    - `xfgmc_f_simple_tag_name_{tag_name}`
	 *    - `xfgmc_f_simple_tag_{tag_name}`
	 * 
	 * @param string $tag_name
	 * @param mixed $tag_value
	 * @param array $tag_attributes_arr 
	 * 
	 * @return string
	 */
	private function get_simple_tag( $tag_name, $tag_value, $tag_attributes_arr = [] ) {

		$hook_suffix = strtolower( str_replace( 'g:', '', $tag_name ) );

		$tag_value = apply_filters(
			'xfgmc_f_simple_tag_value_' . $hook_suffix,
			$tag_value,
			[
				'product' => $this->get_product(),
				'feed_category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);

		if ( $tag_value === (float) 0 || $tag_value === (int) 0 || ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'xfgmc_f_simple_tag_name_' . $hook_suffix,
				$tag_name,
				[
					'product' => $this->get_product(),
					'feed_category_id' => $this->get_feed_category_id()
				],
				$this->get_feed_id()
			);
			$result_xml = new XFGMC_Get_Paired_Tag(
				$tag_name,
				$tag_value,
				$tag_attributes_arr
			);
		} else {
			$result_xml = '';
		}

		$result_xml = apply_filters(
			'xfgmc_f_simple_tag_' . $hook_suffix,
			$result_xml,
			[
				'product' => $this->get_product(),
				'feed_category_id' => $this->get_feed_category_id()
			],
			$this->get_feed_id()
		);

		return $result_xml;

	}

}