<?php
/**
 * Product section of the plugin
 *
 * @link          
 *
 * @package  Webtoffee_Product_Feed_Sync_Pro_Facebook 
 */
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('Webtoffee_Product_Feed_Sync_Pro_Facebook')) {

	class Webtoffee_Product_Feed_Sync_Pro_Facebook {

		public $module_id = '';
		public static $module_id_static = '';
		public $module_base = 'facebook';
		public $module_name = 'Webtoffee Product Feed Catlaog for Facebook';
		public $min_base_version = '1.0.0'; /* Minimum `Import export plugin` required to run this add on plugin */
		private $importer = null;
		private $exporter = null;
		private $product_categories = null;
		private $product_tags = null;
		private $product_taxonomies = array();
		private $all_meta_keys = array();
		private $product_attributes = array();
		private $exclude_hidden_meta_columns = array();
		private $found_product_meta = array();
		private $found_product_hidden_meta = array();
		private $selected_column_names = null;
        public $sync_description_type;
		private $log = null;
		

        public function __construct() {
			/**
			 *   Checking the minimum required version of `Import export plugin` plugin available
			 */
			if (!Webtoffee_Product_Feed_Sync_Pro_Common_Helper::check_base_version($this->module_base, $this->module_name, $this->min_base_version)) {
				return;
			}
			if (!function_exists('is_plugin_active')) {
				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			if (!is_plugin_active('woocommerce/woocommerce.php')) {
				return;
			}

			$this->module_id = Webtoffee_Product_Feed_Sync_Pro::get_module_id($this->module_base);
			self::$module_id_static = $this->module_id;

			add_filter('wt_pf_exporter_post_types_basic', array($this, 'wt_pf_exporter_post_types_basic'), 10, 1);

			add_filter('wt_pf_exporter_alter_filter_fields_basic', array($this, 'exporter_alter_filter_fields'), 10, 3);

			add_filter('wt_pf_exporter_alter_mapping_fields_basic', array($this, 'exporter_alter_mapping_fields'), 10, 3);

			add_filter('wt_pf_exporter_alter_advanced_fields_basic', array($this, 'exporter_alter_advanced_fields'), 10, 3);

			add_filter('wt_pf_exporter_alter_meta_mapping_fields_basic', array($this, 'exporter_alter_meta_mapping_fields'), 10, 3);

			add_filter('wt_pf_exporter_alter_mapping_enabled_fields_basic', array($this, 'exporter_alter_mapping_enabled_fields'), 10, 3);

			add_filter('wt_pf_exporter_do_export_basic', array($this, 'exporter_do_export'), 10, 7);

			
			add_filter('wt_pf_feed_category_mapping', array($this, 'map_fb_category'), 10, 1);
                        
			if ( version_compare( get_bloginfo( 'version' ), '6.1', '>=' ) ) {
				add_action('saved_product_cat', array($this, 'wt_fbfeed_category_form_save_pro_ajx_new'), 10, 4);
			}else{
				add_action('saved_product_cat', array($this, 'wt_fbfeed_category_form_save_pro_ajx'), 10, 3);
			}
	
		}
		
                
        public function wt_fbfeed_category_form_save_pro_ajx_new( $term_id, $tt_id, $update, $args ) {

            if ( isset( $args[ 'wt_fb_category' ] ) ) {
                        if(! wp_verify_nonce( $args['wt_category_edit_nonce'], 'wt_category_edit_nonce' )){
                                return false;
                        }

                        $wt_google_category = absint( $args[ 'wt_fb_category' ] );
                        if(0 == $wt_google_category){
                                delete_term_meta($term_id, 'wt_fb_category');
                        }else{
                                update_term_meta( $term_id, 'wt_fb_category', $wt_google_category );
                        }
                }

        }                
        public function wt_fbfeed_category_form_save_pro_ajx( $term_id, $tt_id, $update ) {

            if ( isset( $_POST[ 'wt_fb_category' ] ) ) {
                        if(! wp_verify_nonce( $_POST['wt_category_edit_nonce'], 'wt_category_edit_nonce' )){
                                return false;
                        }

                        $wt_google_category = absint( $_POST[ 'wt_fb_category' ] );
                        if(0 == $wt_google_category){
                                delete_term_meta($term_id, 'wt_fb_category');
                        }else{
                                update_term_meta( $term_id, 'wt_fb_category', $wt_google_category );
                        }
                }

        } 

	public function map_fb_category($form_data) {

			if ( ( isset($form_data['post_type_form_data']['item_type']) &&  $form_data['post_type_form_data']['item_type'] != $this->module_base ) || ( isset($form_data['post_type_form_data']['wt_pf_export_post_type']) &&  $form_data['post_type_form_data']['wt_pf_export_post_type'] != $this->module_base )) {
				return $form_data;
			} else {


				foreach ($form_data['category_mapping_form_data'] as $local_cat => $merchant_cat) {
					if (!empty($merchant_cat)) {
						$term_id = absint(str_replace('cat_mapping_', '', $local_cat));
						$wt_fb_category = absint($merchant_cat);
						update_term_meta($term_id, 'wt_fb_category', $wt_fb_category);
					}
				}
				return $form_data;
			}
		}


		public function exporter_do_export($export_data, $base, $step, $form_data, $selected_template_data, $method_export, $batch_offset) {
			if ($this->module_base != $base) {
				return $export_data;
			}

			$this->set_selected_column_names($form_data);
                        
			include WT_PRODUCT_FEED_PRO_PLUGIN_PATH . '/admin/modules/export/wt-product.php'; 
			include plugin_dir_path(__FILE__) . 'export/export.php';
			$export = new Webtoffee_Product_Feed_Sync_Pro_Facebook_Export($this);

			$header_row = $export->prepare_header();
			
			$data_row = $export->prepare_data_to_export($form_data, $batch_offset, $step);

			$export_data = array(
				'head_data' => $header_row,
				'body_data' => $data_row['data'],
				'total' => $data_row['total'],
			);

			if (isset($data_row['no_post'])) {
				$export_data['no_post'] = $data_row['no_post'];
			}


			return $export_data;
		}

		/**
		 * Adding current post type to export list
		 *
		 */
		public function wt_pf_exporter_post_types_basic($arr) {


			$arr['facebook'] = __('Facebook / Instagram catalog', 'webtoffee-product-feed-pro');
			return $arr;
		}

		public static function get_age_group(){
					$fb_age_group	 = array(
			'all ages' => __( 'All ages', 'webtoffee-product-feed-pro' ),
			'adult' => __( 'Adult', 'webtoffee-product-feed-pro' ),
			'teen' => __( 'Teen', 'webtoffee-product-feed-pro' ),
			'kids' => __( 'Kids', 'webtoffee-product-feed-pro' ),
			'toddler' => __( 'Toddler', 'webtoffee-product-feed-pro' ),
			'infant' => __( 'Infant', 'webtoffee-product-feed-pro' ),
			'newborn' => __( 'Newborn', 'webtoffee-product-feed-pro' )
		);

		return apply_filters( 'wt_feed_facebook_product_agegroup', $fb_age_group );
		}

				/**
	 * Read txt file which contains facebook taxonomy list
	 *
	 * @return array
	 */
	public static function get_category_array() {
			// Get All Facebook Taxonomies


			$taxonomy = wp_cache_get('wt_iew_feed_fb_categories');

			if (false === $taxonomy) {

				$fileName = WT_PRODUCT_FEED_PRO_PLUGIN_PATH . '/admin/modules/facebook/data/fb_taxonomy.txt';
				$customTaxonomyFile = fopen($fileName, 'r');  // phpcs:ignore
				$taxonomy = array();
				$taxonomy[''] = 'Do not map';
				if ($customTaxonomyFile) {
					// First line contains metadata, ignore it
					fgets($customTaxonomyFile);  // phpcs:ignore
					while ($line = fgets($customTaxonomyFile)) {  // phpcs:ignore
						list( $catId, $cat ) = explode(',', $line);
						$cat_key = absint(trim($catId));
						$cat_val = trim($cat);
						$taxonomy[$cat_key] = $cat_val;
					}
				}
				wp_cache_set('wt_iew_feed_fb_categories', $taxonomy, '', WEEK_IN_SECONDS);
			}



			return $taxonomy;
		}

		/**
		 * Get product categories
		 * @return array $categories 
		 */
		private function get_product_categories() {
			if (!is_null($this->product_categories)) {
				return $this->product_categories;
			}
			$out = array();
			$product_categories = get_terms('product_cat', array('hide_empty' => false));
			if (!is_wp_error($product_categories)) {
				$version = get_bloginfo('version');
				foreach ($product_categories as $category) {
					$out[$category->slug] = (( $version < '4.8') ? $category->name : get_term_parents_list($category->term_id, 'product_cat', array('separator' => ' -> ')));
				}
			}
			$this->product_categories = $out;
			return $out;
		}

		private function get_product_tags() {
			if (!is_null($this->product_tags)) {
				return $this->product_tags;
			}
			$out = array();
			$product_tags = get_terms('product_tag');
			if (!is_wp_error($product_tags)) {
				foreach ($product_tags as $tag) {
					$out[$tag->slug] = $tag->name;
				}
			}
			$this->product_tags = $out;
			return $out;
		}

		public static function get_product_statuses() {
			$product_statuses = array('publish', 'private', 'draft', 'pending', 'future');
			return apply_filters('wt_pf_allowed_product_statuses', array_combine($product_statuses, $product_statuses));
		}

		public static function get_product_post_columns() {
			return include plugin_dir_path(__FILE__) . 'data/data-product-post-columns.php';
		}


		public function exporter_alter_mapping_enabled_fields($mapping_enabled_fields, $base, $form_data_mapping_enabled_fields) {
			if ($base == $this->module_base) {
				$mapping_enabled_fields = array();
				$mapping_enabled_fields['availability_price'] = array(__('Availability & Price', 'webtoffee-product-feed-pro'), 1);
				$mapping_enabled_fields['tax_shipping'] = array(__('Tax & Shipping', 'webtoffee-product-feed-pro'), 1);
				$mapping_enabled_fields['unique_product_identifiers'] = array(__('Unique Product Identifiers', 'webtoffee-product-feed-pro'), 1);
				$mapping_enabled_fields['detailed_product_attributes'] = array(__('Detailed Product Attributes', 'webtoffee-product-feed-pro'), 1);
				$mapping_enabled_fields['custom_label_identifiers'] = array(__('Custom Label Attributes', 'webtoffee-product-feed-pro'), 1);
				$mapping_enabled_fields['additional_attributes'] = array(__('Additional Attributes', 'webtoffee-product-feed-pro'), 1);
			}
			return $mapping_enabled_fields;
		}

		public function exporter_alter_meta_mapping_fields($fields, $base, $step_page_form_data) {
			if ($base != $this->module_base) {
				return $fields;
			}

			foreach ($fields as $key => $value) {
				switch ($key) {
					case 'availability_price':
						$fields[$key]['fields']['availability'] = 'Stock Status[availability]';
						$fields[$key]['fields']['availability_date'] = 'Availability Date[availability_date]';
						$fields[$key]['fields']['price'] = 'Regular Price[price]';
						$fields[$key]['fields']['sale_price'] = 'Sale Price[sale_price]';
						$fields[$key]['fields']['sale_price_effective_date'] = 'Sale Price Effective Date[sale_price_effective_date]';
						break;

					case 'tax_shipping':
						$fields[$key]['fields']['tax'] = 'Tax[tax]';
						$fields[$key]['fields']['tax_country'] = 'Tax Country[tax_country]';
						$fields[$key]['fields']['tax_region'] = 'Tax Region[tax_region]';
						$fields[$key]['fields']['tax_rate'] = 'Tax Rate[tax_rate]';
						$fields[$key]['fields']['tax_ship'] = 'Tax Ship[tax_ship]';
						$fields[$key]['fields']['tax_category'] = 'Tax[tax_category]';
						$fields[$key]['fields']['shipping'] = 'Shipping';
						$fields[$key]['fields']['shipping_weight'] = 'Shipping Weight[shipping_weight]';

						break;

					case 'unique_product_identifiers':

						$fields[$key]['fields']['brand'] = 'Manufacturer[brand]';
                                                $fields[$key]['fields']['gtin'] = 'GTIN';
                                                $fields[$key]['fields']['mpn'] = 'MPN';
						$fields[$key]['fields']['identifier_exists'] = 'Identifier Exist[identifier_exists]';
                                                

						break;
					case 'detailed_product_attributes':

						$fields[$key]['fields']['item_group_id'] = 'Item Group Id[item_group_id]';
						$fields[$key]['fields']['color'] = 'Color[color]';
						$fields[$key]['fields']['gender'] = 'Gender[gender]';
						$fields[$key]['fields']['age_group'] = 'Age Group[age_group]';
						$fields[$key]['fields']['material'] = 'Material[material]';
						$fields[$key]['fields']['pattern'] = 'Pattern[pattern]';
						$fields[$key]['fields']['size'] = 'Size of the item[size]';
						break;

					case 'custom_label_identifiers':
						$fields[$key]['fields']['custom_label_0'] = 'Custom label 0 [custom_label_0]';
						$fields[$key]['fields']['custom_label_1'] = 'Custom label 1 [custom_label_1]';
						$fields[$key]['fields']['custom_label_2'] = 'Custom label 2 [custom_label_2]';
						$fields[$key]['fields']['custom_label_3'] = 'Custom label 3 [custom_label_3]';
						$fields[$key]['fields']['custom_label_4'] = 'Custom label 4 [custom_label_4]';
						break;

					case 'additional_attributes':
						$fields[$key]['fields']['inventory'] = 'Facebook Inventory[inventory]';
						$fields[$key]['fields']['override'] = 'Facebook Override[override]';
						$fields[$key]['fields']['status'] = 'Status [status]';
						$fields[$key]['fields']['video'] = 'Video [video]';
						$fields[$key]['fields']['unit_price_value'] = 'Unit Price > Value [unit_price_value]';
						$fields[$key]['fields']['unit_price_currency'] = 'Unit Price > Currency [unit_price_currency]';
						$fields[$key]['fields']['unit_price_unit'] = 'Unit Price > Unit [unit_price_unit]';
						$fields[$key]['fields']['quantity_to_sell_on_facebook'] = 'Quantity to Sell on Facebook [quantity_to_sell_on_facebook]';
						$fields[$key]['fields']['commerce_tax_category'] = 'Commerce Tax Category [commerce_tax_category]';
						$fields[$key]['fields']['expiration_date'] = 'Expiration Date[expiration_date]';
						$fields[$key]['fields']['marked_for_product_launch'] = 'Marked for Product Launce [marked_for_product_launch]';
						$fields[$key]['fields']['rich_text_description'] = 'Rich Text Description [rich_text_description]';
						$fields[$key]['fields']['visibility'] = 'Visibility [visibility]';
						$fields[$key]['fields']['additional_variant_label'] = 'Additional Variant Attribute > Label [Variant Label]';
						$fields[$key]['fields']['additional_variant_value'] = 'Additional Variant Attribute > Value [Variant Value]';
						$fields[$key]['fields']['applink'] = 'Applink [applink]';
						$fields[$key]['fields']['origin_country'] = 'Origin Country [origin_country]';
						$fields[$key]['fields']['importer_name'] = 'Importer Name [importer_name]';
						$fields[$key]['fields']['importer_address'] = 'Importer Address [importer_address]';
						$fields[$key]['fields']['manufacturer_info'] = 'Manufacturer Info [manufacturer_info]';
						$fields[$key]['fields']['return_policy_info'] = 'Return Policy Info [return_policy_info]';
                                                $fields[$key]['fields']['additional_variant_attribute'] = 'additional_variant_attribute';
						break;

					default:
						break;
				}
			}

			return $fields;
		}

		public function importer_alter_meta_mapping_fields($fields, $base, $step_page_form_data) {
			if ($base != $this->module_base) {
				return $fields;
			}

			$fields = $this->exporter_alter_meta_mapping_fields($fields, $base, $step_page_form_data);
			$out = array();
			foreach ($fields as $key => $value) {
				$value['fields'] = array_map(function ($vl) {
					$meta_mapping_temp = array('title' => $vl, 'description' => $vl);

					// For fileds other than default fields, the alternates slect firlds cannot be set as of now
					// Its called after loading the default fields so need to load head again in backend to set from similar array
					// Here user alternate field as single value. ( For defaults, its array )
					if ('tax:product_type' === $vl) {
						$meta_mapping_temp['field_type'] = 'alternates';
						$meta_mapping_temp['similar_fields'] = 'Type';
					}
					if ('tax:product_tag' === $vl) {
						$meta_mapping_temp['field_type'] = 'alternates';
						$meta_mapping_temp['similar_fields'] = 'Tags';
					}
					if ('tax:product_cat' === $vl) {
						$meta_mapping_temp['field_type'] = 'alternates';
						$meta_mapping_temp['similar_fields'] = 'Categories';
					}
					if ('tax:product_shipping_class' === $vl) {
						$meta_mapping_temp['field_type'] = 'alternates';
						$meta_mapping_temp['similar_fields'] = 'Shipping class';
					}

					return $meta_mapping_temp;
				}, $value['fields']);
				$out[$key] = $value;
			}
			return $out;
		}

		public function set_selected_column_names($full_form_data) {

			if (is_null($this->selected_column_names)) {
				$this->selected_column_names = array();
				if (isset($full_form_data['mapping_form_data']['mapping_selected_fields']) && !empty($full_form_data['mapping_form_data']['mapping_selected_fields'])) {
					$selected_mapped_fields = array();
					foreach ($full_form_data['mapping_form_data']['mapping_selected_fields'] as $key => $value) {
						if( "" !=  $value){
							$this->selected_column_names[$key] = $value;
						}
					}
				}
				if (isset($full_form_data['meta_step_form_data']['mapping_selected_fields']) && !empty($full_form_data['meta_step_form_data']['mapping_selected_fields'])) {
					$export_additional_columns = $full_form_data['meta_step_form_data']['mapping_selected_fields'];

					foreach ($export_additional_columns as $value) {
						foreach ($value as $key => $vl) {
							if( "" !=  $vl){
								$this->selected_column_names[$key] = $vl;
							}
						}
					}

				}
				$this->selected_column_names = ($this->selected_column_names);

			}


			return $full_form_data;
		}

		public function get_selected_column_names() {

			return $this->selected_column_names;
		}

		public function exporter_alter_mapping_fields($fields, $base, $mapping_form_data) {
			if ($base == $this->module_base) {
				$fields = self::get_product_post_columns();
			}
			return $fields;
		}

		public function exporter_alter_advanced_fields($fields, $base, $advanced_form_data) {
			if ($this->module_base != $base) {
				return $fields;
			}
			$out = array();
			$out['header_empty_row'] = array(
				'tr_html' => '<tr id="header_empty_row"><th></th><td></td></tr>'
			);
			foreach ($fields as $fieldk => $fieldv) {
				$out[$fieldk] = $fieldv;
			}
                        $out['file_as']['sele_vals'] = array(
                            'xml'=>__('XML', 'webtoffee-product-feed-pro'), 
                            'csv'=>__('CSV', 'webtoffee-product-feed-pro'),
                            'tsv'=>__('TSV', 'webtoffee-product-feed-pro'),
                            'xlsx'=>__('XLSX', 'webtoffee-product-feed-pro')
                        );
                        $out['delimiter']['sele_vals'] = array(
                            'comma' => array('value' => __('Comma', 'webtoffee-product-feed-pro'), 'val' => ","),
                            'tab' => array('value' => __('Tab', 'webtoffee-product-feed-pro'), 'val' => "\t"),
                            'semicolon' => array('value' => __('Semicolon', 'webtoffee-product-feed-pro'), 'val' => ";"),
                        );

			return $out;
		}

		/**
		 *  Customize the items in filter export page
		 */
		public function exporter_alter_filter_fields($fields, $base, $filter_form_data) {
			if ($this->module_base != $base) {
				return $fields;
			}

			/* altering help text of default fields */
			$fields['limit']['label'] = __('Total number of products to export', 'webtoffee-product-feed-pro');
			$fields['limit']['help_text'] = __('Exports specified number of products. e.g. Entering 500 with a skip count of 10 will export products from 11th to 510th position.', 'webtoffee-product-feed-pro');
			$fields['offset']['label'] = __('Skip first <i>n</i> products', 'webtoffee-product-feed-pro');
			$fields['offset']['help_text'] = __('Skips specified number of products from the beginning of the database. e.g. Enter 10 to skip first 10 products from export.', 'webtoffee-product-feed-pro');

			$fields['product'] = array(
				'label' => __('Products', 'webtoffee-product-feed-pro'),
				'placeholder' => __('All products', 'webtoffee-product-feed-pro'),
				'attr' => array('data-exclude_type' => 'variable,variation'),
				'field_name' => 'product',
				'sele_vals' => array(),
				'help_text' => __('Export specific products. Keyin the product names to export multiple products.', 'webtoffee-product-feed-pro'),
				'type' => 'multi_select',
				'css_class' => 'wc-product-search',
				'validation_rule' => array('type' => 'text_arr')
			);
			$fields['stock_status'] = array(
				'label' => __('Stock status', 'webtoffee-product-feed-pro'),
				'placeholder' => __('All status', 'webtoffee-product-feed-pro'),
				'field_name' => 'stock_status',
				'sele_vals' => array('' => __('All status', 'webtoffee-product-feed-pro'), 'instock' => __('In Stock', 'webtoffee-product-feed-pro'), 'outofstock' => __('Out of Stock', 'webtoffee-product-feed-pro'), 'onbackorder' => __('On backorder', 'webtoffee-product-feed-pro')),
				'help_text' => __('Export products based on stock status.', 'webtoffee-product-feed-pro'),
				'type' => 'select',
				'validation_rule' => array('type' => 'text_arr')
			);
			$fields['exclude_product'] = array(
				'label' => __('Exclude products', 'webtoffee-product-feed-pro'),
				'placeholder' => __('Exclude products', 'webtoffee-product-feed-pro'),
				'attr' => array('data-exclude_type' => 'variable,variation'),
				'field_name' => 'exclude_product',
				'sele_vals' => array(),
				'help_text' => __('Use this if you need to exclude a specific or multiple products from your export list.', 'webtoffee-product-feed-pro'),
				'type' => 'multi_select',
				'css_class' => 'wc-product-search',
				'validation_rule' => array('type' => 'text_arr')
			);

			$fields['product_categories'] = array(
				'label' => __('Product categories', 'webtoffee-product-feed-pro'),
				'placeholder' => __('Any category', 'webtoffee-product-feed-pro'),
				'field_name' => 'product_categories',
				'sele_vals' => $this->get_product_categories(),
				'help_text' => __('Export products belonging to a particular or from multiple categories. Just select the respective categories.', 'webtoffee-product-feed-pro'),
				'type' => 'multi_select',
				'css_class' => 'wc-enhanced-select',
				'validation_rule' => array('type' => 'sanitize_title_with_dashes_arr')
			);

			$fields['product_tags'] = array(
				'label' => __('Product tags', 'webtoffee-product-feed-pro'),
				'placeholder' => __('Any tag', 'webtoffee-product-feed-pro'),
				'field_name' => 'product_tags',
				'sele_vals' => $this->get_product_tags(),
				'help_text' => __('Enter the product tags to export only the respective products that have been tagged accordingly.', 'webtoffee-product-feed-pro'),
				'type' => 'multi_select',
				'css_class' => 'wc-enhanced-select',
				'validation_rule' => array('type' => 'sanitize_title_with_dashes_arr')
			);

			$fields['product_status'] = array(
				'label' => __('Product status', 'webtoffee-product-feed-pro'),
				'placeholder' => __('Any status', 'webtoffee-product-feed-pro'),
				'field_name' => 'product_status',
				'sele_vals' => self::get_product_statuses(),
				'help_text' => __('Filter products by their status.', 'webtoffee-product-feed-pro'),
				'type' => 'multi_select',
				'css_class' => 'wc-enhanced-select',
				'validation_rule' => array('type' => 'text_arr')
			);

			return $fields;
		}

		
		

public static function wt_feed_get_product_conditions() {
	$conditions = array(
		'new'           => _x( 'New', 'product condition', 'webtoffee-product-feed-pro' ),
		'refurbished'   => _x( 'Refurbished', 'product condition', 'webtoffee-product-feed-pro' ),
		'used'          => _x( 'Used', 'product condition', 'webtoffee-product-feed-pro' ),
		'used_like_new' => _x( 'Used like new', 'product condition', 'webtoffee-product-feed-pro' ),
		'used_good'     => _x( 'Used good', 'product condition', 'webtoffee-product-feed-pro' ),
		'used_fair'     => _x( 'Used fair', 'product condition', 'webtoffee-product-feed-pro' ),
	);

	return apply_filters( 'wt_feed_facebook_product_conditions', $conditions );
}	
	
		
		
	/**
	* 	Do the FB sync process
	*/
	public function process_action($cron_data, $offset=0)
	{
		$out=array(
			'response'=>false,
			'new_offset'=>0,
			'total_records'=>0,
			'finished'=>0,
			'msg'=>'',
		);

		if(is_null($cron_data)) //no record found so it may be an error
		{
			return $out;
		}

		$batch_count = $cron_data['wt_sync_batch_count'];	

		$export_data=  $this->send_products_to_facebook($cron_data, $offset, $batch_count);
		$total_records=intval(isset($export_data['total']) ? $export_data['total'] : 0);
		
		/* checking action is finshed */
		$is_last_offset=false;
		$new_offset=$offset+$batch_count; //increase the offset
		if($new_offset>=$total_records) //finished
		{
			$is_last_offset=true;
		}


		/* no data from corresponding module */
		if(!$export_data) //error !!!
		{
			//return $out;
		}
		
		/* updating output parameters */
		$out['total_records']=$total_records;

		$out['response']=true;

		/* updating action is finshed */	
		if($is_last_offset) //finished
		{

				$out['finished']=1; //finished
				$out['msg']= 'processed';
		}
			
		$out['new_offset']=$new_offset;
		
		return $out;
	}
	
	public function send_products_to_facebook($cron_data, $offset, $batch_count){
		
		$product_data	 = [];
		$wc_fbfeed		 = new WT_Facebook_Catalog_Product();
		$wc_fbfeed->sync_description_type = $cron_data['wt_sync_product_desc_type'];

		$args			 = array(
			'post_status'	 => array( 'publish' ),
			'posts_per_page' => $batch_count,
			'offset'		 => $offset,
			'fields'		 => 'ids',
		);

                $wt_sync_include_variations_type = isset($cron_data['wt_sync_include_variations_type']) ? $cron_data['wt_sync_include_variations_type'] : '';
                
                if( '' == $wt_sync_include_variations_type ){
                    $args['post_type'] = array('product', 'product_variation');
                }else{
                    $args['post_type'] = array('product');
                }

		if ( !empty( $cron_data['wt_sync_exclude_category'] ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_cat',
				'terms'		 => $cron_data['wt_sync_exclude_category'], // Term ids to be excluded
				'operator'	 => 'NOT IN' // Excluding terms
			);
		}
		
		if ( !empty( $cron_data['wt_sync_include_category'] ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_cat',
				'terms'		 => $cron_data['wt_sync_include_category'], // Term ids to be included
				'operator'	 => 'IN' // Including terms
			);
		}		


		if ( !empty( $cron_data['wt_sync_exclude_tags'] ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_tag',
				'terms'		 => $cron_data['wt_sync_exclude_tags'], // Term ids to be excluded
				'operator'	 => 'NOT IN' // Excluding terms
			);
		}
		if ( !empty( $cron_data['wt_sync_exclude_category'] ) || !empty( $cron_data['wt_sync_exclude_tags'] ) ) {
			$args[ 'tax_query' ][ 'relation' ] = 'AND';
		}
		
		
		if($cron_data['wt_sync_exclude_outofstock']){
		$args['meta_query'] = array( array(
				'key' => '_stock_status',
				'value' => 'outofstock',
				'compare' => '!='
			) );
		}


		$loop = new WP_Query( $args );
		$process_products = apply_filters('wt_facebook_sync_products', $loop->posts);
		
                    foreach ($process_products as $product_id) {
                                                                        
                        if( '' !== $wt_sync_include_variations_type ){
                            $product = wc_get_product( $product_id );
                            if( $product->is_type( 'variable' ) ){                        
                                $product_id = $this->get_filtered_variant_id($product_id, $wt_sync_include_variations_type);
                            }
                        }
                        
                        $product_item_data = $wc_fbfeed->process_item_update($product_id);                        
                        if (!empty($product_item_data['data']['price'])) {
                            $product_data[] = $product_item_data;
                        }

                    }
		
		$access_token = get_option( WT_Fb_Catalog_Manager_Pro_Settings::OPTION_ACCESS_TOKEN, '' );
		$catalog_access_token = $access_token; //$this->get_access_token();

		$request_body	 = [
			"headers"	 => [
				"Authorization"	 => "Bearer {$catalog_access_token}",
				"Content-type"	 => "application/json",
				"accept"		 => "application/json" ],
			"body"		 => json_encode( [
				"allow_upsert"	 => true,
				"item_type"		 => "PRODUCT_ITEM",
				"requests"		 => json_encode( $product_data )
			] ),
		];
		$catalog_id		 = $cron_data['wt_sync_selected_catalog'];
		// Each bacth process the batch_limit
                $wt_batch_hash_key = md5('wtfbfeedcron');
                
		if ( !empty( $product_data ) ) {
			
			$this->wt_log_data_change( 'wt-feed-upload', 'Requested Product Data-Cron:' );
			$this->wt_log_data_change( 'wt-feed-upload', print_r( $product_data, 1 ) );

			$items_batch			 = "https://graph.facebook.com/v17.0/$catalog_id/items_batch";
			$batch_response			 = wp_remote_post( $items_batch, $request_body );
			$this->wt_log_data_change( 'wt-feed-upload', 'Batch Response-Cron:' );
			$this->wt_log_data_change( 'wt-feed-upload', print_r( $batch_response, 1 ) );
			$batch_response_details	 = wp_remote_retrieve_body( $batch_response );
			$batch_response_details	 = json_decode( $batch_response_details );                       
                        
                        if ( isset( $batch_response_details->handles[ 0 ] ) ) {
                            global $wpdb;
                            $table_name = $wpdb->prefix.'wt_pf_fbsync_log';

                            // First batch insert log
                            $batch_pocess_log = array();
                            if( 0 == $offset ){
                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $insert_data=array(
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        'start_time'=>date( 'Y-m-d H:i:s' ), 

                                );
                                $insert_data_type=array('%s', '%s', '%s');

                                $wpdb->insert($table_name, $insert_data, $insert_data_type);
                            }else{
                                // All other batch update last log row
                                $last_log = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' WHERE catalog_id = ' . $catalog_id . ' ORDER BY id DESC LIMIT 1');
                                $batch_pocess_log = maybe_unserialize($last_log->data);

                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $update_data=array(
                                        'id' => $last_log->id,
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        //'start_time'=>date( 'Y-m-d H:i:s' )

                                );
                                $wpdb->update($table_name, $update_data, array('id' => $last_log->id));
                            }    				
				
			}
                        
		}
		

		return array(
			'total' => $loop->found_posts
		);

		//$percentage_completed = $this->get_percent_complete( $loop->found_posts, $step, $wt_sync_batch_count );

	}

        
        public function get_filtered_variant_id($product_id, $wt_sync_include_variations_type) {


            $product = wc_get_product($product_id);
            if ('lowest' == $wt_sync_include_variations_type) {

                // Initialize variables
                $lowest_price = null;
                $lowest_price_variation_id = null;

                // Loop through the variations
                foreach ($product->get_available_variations() as $variation) {
                    // Get the variation price
                    $variation_price = floatval($variation['display_price']);

                    // Compare with the lowest price found so far
                    if ($lowest_price === null || $variation_price < $lowest_price) {
                        $lowest_price = $variation_price;
                        $lowest_price_variation_id = $variation['variation_id'];
                    }
                }

                return $lowest_price_variation_id;
            } else {
                $variation_id = false;
                foreach ($product->get_available_variations() as $variation_values) {
                    foreach ($variation_values['attributes'] as $key => $attribute_value) {
                        $attribute_name = str_replace('attribute_', '', $key);
                        $default_value = $product->get_variation_default_attribute($attribute_name);
                        if ($default_value == $attribute_value) {
                            $is_default_variation = true;
                            break;
                        } else {
                            $is_default_variation = false;
                            break; // Stop this loop to start next main lopp
                        }
                    }
                    if ($is_default_variation) {
                        $variation_id = $variation_values['variation_id'];
                        break; // Stop the main loop
                    }
                }
                return $variation_id;
            }
        }        
        
        public function wt_log_data_change($content = 'wt-feed-upload', $data = '') {


            if (version_compare(WC()->version, '2.7.0', '<')) {
                $this->log = new WC_Logger();
            } else {
                $this->log = wc_get_logger();
            }

            if (version_compare(WC()->version, '2.7.0', '<')) {
                $this->log->add($content, $data);
            } else {
                $context = array('source' => $content);
                $this->log->log("debug", $data, $context);
            }
        }		
		

	}

}

new Webtoffee_Product_Feed_Sync_Pro_Facebook();
