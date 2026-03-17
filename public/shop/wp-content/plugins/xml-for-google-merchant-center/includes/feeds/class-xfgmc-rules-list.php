<?php

/**
 * Sets the list of tags depending on the selected feed rules.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.4 (20-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * Sets the list of tags depending on the selected feed rules.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Rules_List {

	/**
	 * The rules of feeds array.
	 *
	 * @var array
	 */
	private $rules_arr;

	/**
	 * Сonstructor.
	 * 
	 * @param array $rules_arr
	 */
	public function __construct( $rules_arr = [] ) {

		if ( empty( $rules_arr ) ) {
			$this->rules_arr = [ 
				'merchant_center' => [ 
					// Merchant Center
					// https://yastatic.net/s3/doc-binary/src/support/market/ru/XML_sample_sales_terms.zip
					// https://support.google.com/merchants/answer/7052112?
					'open_item_tag', 'id', 'title', 'description', 'link', 'image_link',
					// TODO: 'additional_image_link', 'virtual_model_link',
					'mobile_link', 'availability', 'availability_date',
					// TODO: 'cost_of_goods_sold', 'expiration_date'
					'price', 'sale_price', /* //'sale_price_effective_date', внтури тега sale_price */
					'unit_pricing_measure', 'unit_pricing_base_measure', // TODO: 'installment',
					// TODO: 'subscription_cost', 'loyalty_program', 'auto_pricing_min_price',
					'google_product_category', 'product_type',
					'brand', 'gtin', 'mpn', 'identifier_exists',
					'condition', 'adult', 'multipack', 'is_bundle', // TODO: 'certification', 'energy_efficiency_class',
					// TODO: 'min_energy_efficiency_class', 'max_energy_efficiency_class',
					'age_group', 'color', 'gender', 'material', 'pattern',
					'size', 'size_type', 'size_system', 'item_group_id',
					'dimensions', // включает g:product_length, g:product_width, g:product_height, g:product_weight
					// TODO: 'product_detail', 'product_highlight',
					// TODO: 'ads_redirect',
					'custom_label', // TODO: 'promotion_id', 'lifestyle_image_link', 
					// TODO: 'external_seller_id', 'excluded_destination', 'included_destination', 'shopping_ads_excluded_country',
					// TODO: 'pause',
					'shipping', // включает g:min_handling_time, g:max_handling_time, g:shipping_label
					'shipping_dimensions', // включает g:shipping_length, g:shipping_width, g:shipping_height, g:shipping_weight
					'tax', 'tax_category',
					'quantity', 'store_code'
				],
				'facebook' => [ 
					// Facebook
					// https://www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944
					'open_item_tag', 'id', 'title', 'description', 'availability', 'condition',
					'price', 'sale_price', 'link', 'image_link', 'brand',
					// TODO: 'quantity_to_sell_on_facebook', 'sale_price_effective_date'
					'size', 'item_group_id', // TODO: 'status', 'additional_image_link',
					'gtin', 'mpn', 'google_product_category', 'fb_product_category',
					'color', 'gender', 'size', 'age_group', 'material', 'pattern',
					// TODO 'rich_text_description', 'video[0-19].url',
					'shipping',
					'shipping_dimensions', // включает g:shipping_length, g:shipping_width, g:shipping_height, g:shipping_weight
					// TODO 'internal_label',
					'custom_label', // TODO 'custom_number'
				],
				'all_elements' => [ 
					// All elements
					// https://yastatic.net/s3/doc-binary/src/support/market/ru/XML_sample_sales_terms.zip
					// https://support.google.com/merchants/answer/7052112?
					'open_item_tag', 'id', 'title', 'description', 'link', 'image_link',
					// TODO: 'additional_image_link', 'virtual_model_link', 'mobile_link',
					'availability', 'availability_date',
					// TODO: 'cost_of_goods_sold', 'expiration_date'
					'price', 'sale_price', /* //'sale_price_effective_date', внтури тега sale_price */
					'unit_pricing_measure', 'unit_pricing_base_measure', // TODO: 'installment',
					// TODO: 'subscription_cost', 'loyalty_program', 'auto_pricing_min_price',
					'google_product_category', 'product_type',
					'brand', 'gtin', 'mpn', 'identifier_exists',
					'condition', 'adult', 'multipack', 'is_bundle', // TODO: 'certification', 'energy_efficiency_class',
					// TODO: 'min_energy_efficiency_class', 'max_energy_efficiency_class',
					'age_group', 'color', 'gender', 'material', 'pattern',
					'size', 'size_type', 'size_system', 'item_group_id',
					'dimensions', // включает g:product_length, g:product_width, g:product_height, g:product_weight
					// TODO: 'product_detail', 'product_highlight',
					// TODO: 'ads_redirect',
					'custom_label', // TODO: 'promotion_id', 'lifestyle_image_link', 
					// TODO: 'external_seller_id', 'excluded_destination', 'included_destination', 'shopping_ads_excluded_country',
					// TODO: 'pause',
					'shipping', // включает g:min_handling_time, g:max_handling_time, g:shipping_label
					'shipping_dimensions', // включает g:shipping_length, g:shipping_width, g:shipping_height, g:shipping_weight
					'tax', 'tax_category',
					'quantity', 'store_code'
				]
			];
		} else {
			$this->rules_arr = $rules_arr;
		}

		$this->rules_arr = apply_filters( 'xfgmc_f_set_rules_arr', $this->get_rules_arr() );

	}

	/**
	 * Get the rules of feeds array.
	 * 
	 * @return array
	 */
	public function get_rules_arr() {
		return $this->rules_arr;
	}

}