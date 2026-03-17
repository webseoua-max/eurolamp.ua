<?php

if (!defined('WPINC')) {
    exit;
}

if (!class_exists('Webtoffee_Product_Feed_Google_PromotionsExport')) {

    class Webtoffee_Product_Feed_Google_PromotionsExport extends Webtoffee_Product_Feed_Pro_Product {

        public $parent_module = null;
        public $product;
        public $current_product_id;
        public $form_data;

        public function __construct($parent_object) {

            $this->parent_module = $parent_object;
        }

        public function prepare_header() {

            $export_columns = $this->parent_module->get_selected_column_names();

            return apply_filters('wt_pf_alter_product_feed_csv_columns', $export_columns);
        }

        /**
         * Prepare data that will be exported.
         */
        public function prepare_data_to_export($form_data, $batch_offset, $step) {

            $this->form_data = $form_data;

            
            $include_variations_type = !empty($form_data['post_type_form_data']['wt_pf_include_variations_type']) ? $form_data['post_type_form_data']['wt_pf_include_variations_type'] : '';
            
            $exc_stock_status = !empty($form_data['post_type_form_data']['item_outofstock']) ? $form_data['post_type_form_data']['item_outofstock'] : '';
            
            if('' === $exc_stock_status){
                $exc_stock_status = !empty($form_data['post_type_form_data']['wt_pf_exclude_outofstock']) ? $form_data['post_type_form_data']['wt_pf_exclude_outofstock'] : '';
            }
            
            $item_parentonly = !empty($form_data['post_type_form_data']['item_parentonly']) ? $form_data['post_type_form_data']['item_parentonly'] : '';

            if( '' === $item_parentonly ){
                $item_parentonly = !empty($form_data['post_type_form_data']['wt_pf_include_parent_only']) ? $form_data['post_type_form_data']['wt_pf_include_parent_only'] : '';                
            }
             if( '' !== $item_parentonly ){
                 $include_variations_type = 'default';
             }
            
            $prod_exc_categories = !empty($form_data['post_type_form_data']['item_exc_cat']) ? $form_data['post_type_form_data']['item_exc_cat'] : array();            
            $prod_inc_categories = !empty($form_data['post_type_form_data']['item_inc_cat']) ? $form_data['post_type_form_data']['item_inc_cat'] : array();

            $cat_filter_type = !empty($form_data['post_type_form_data']['cat_filter_type']) ? $form_data['post_type_form_data']['cat_filter_type'] : '';
            if( '' === $cat_filter_type ){
                $cat_filter_type = !empty($form_data['post_type_form_data']['wt_pf_export_cat_filter_type']) ? $form_data['post_type_form_data']['wt_pf_export_cat_filter_type'] : 'include_cat';
            }
            
            $inc_exc_category = !empty($form_data['post_type_form_data']['inc_exc_cat']) ? $form_data['post_type_form_data']['inc_exc_cat'] : array();
            if( empty($inc_exc_category) ){
                $inc_exc_category = !empty($form_data['post_type_form_data']['wt_pf_inc_exc_category']) ? $form_data['post_type_form_data']['wt_pf_inc_exc_category'] : array();
            }
            
            
            if ('include_cat' === $cat_filter_type) {
                $prod_inc_categories = $inc_exc_category;
            } else {
                $prod_exc_categories = $inc_exc_category;
            }

            
            $brand_filter_type = !empty($form_data['post_type_form_data']['wt_pf_export_brand_filter_type']) ? $form_data['post_type_form_data']['wt_pf_export_brand_filter_type'] : 'include_brand';
            $inc_exc_brand = !empty($form_data['post_type_form_data']['wt_pf_inc_exc_brand']) ? $form_data['post_type_form_data']['wt_pf_inc_exc_brand'] : array();

            if ('include_brand' === $brand_filter_type) {
                $prod_inc_brands = $inc_exc_brand;
            } else {
                $prod_exc_brands = $inc_exc_brand;
            }            

            $prod_exc = !empty($form_data['post_type_form_data']['item_exc_prd']) ? $form_data['post_type_form_data']['item_exc_prd'] : array();

            if( empty($prod_exc) ){
                $prod_exc = !empty($form_data['post_type_form_data']['wt_pf_exclude_products']) ? $form_data['post_type_form_data']['wt_pf_exclude_products'] : array();
            }
            
            $tag_filter_type = !empty($form_data['post_type_form_data']['wt_pf_export_tag_filter_type']) ? $form_data['post_type_form_data']['wt_pf_export_tag_filter_type'] : 'include_tag';
            $inc_exc_tag = !empty($form_data['post_type_form_data']['wt_pf_inc_exc_tag']) ? $form_data['post_type_form_data']['wt_pf_inc_exc_tag'] : array();            
            /* WPML
             * 
             */
            $item_post_lang = !empty($form_data['post_type_form_data']['wt_pf_export_post_language']) ? $form_data['post_type_form_data']['wt_pf_export_post_language'] : '';
            
            $prod_types = !empty($form_data['post_type_form_data']['item_product_type']) ? $form_data['post_type_form_data']['item_product_type'] : array();
            
            if( empty( $prod_types ) ){
                $prod_types = !empty($form_data['post_type_form_data']['wt_pf_product_types']) ? $form_data['post_type_form_data']['wt_pf_product_types'] : array();
            }
            
            $prod_status = !empty($form_data['filter_form_data']['wt_pf_product_status']) ? $form_data['filter_form_data']['wt_pf_product_status'] : array();

            $export_sortby = !empty($form_data['filter_form_data']['wt_pf_sort_columns']) ? $form_data['filter_form_data']['wt_pf_sort_columns'] : 'ID';
            $export_sort_order = !empty($form_data['filter_form_data']['wt_pf_order_by']) ? $form_data['filter_form_data']['wt_pf_order_by'] : 'ASC';

            $export_limit = !empty($form_data['filter_form_data']['wt_pf_limit']) ? intval($form_data['filter_form_data']['wt_pf_limit']) : 999999999; //user limit
            $current_offset = !empty($form_data['filter_form_data']['wt_pf_offset']) ? intval($form_data['filter_form_data']['wt_pf_offset']) : 0; //user offset

            $batch_count = !empty($form_data['advanced_form_data']['wt_pf_batch_count']) ? $form_data['advanced_form_data']['wt_pf_batch_count'] : Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_advanced_settings('default_export_batch');
            $batch_count = apply_filters('wt_product_feed_limit_per_request', $batch_count); //ajax batch limit

            $real_offset = ($current_offset + $batch_offset);

            if ($batch_count <= $export_limit) {
                if (($batch_offset + $batch_count) > $export_limit) { //last offset
                    $limit = $export_limit - $batch_offset;
                } else {
                    $limit = $batch_count;
                }
            } else {
                $limit = $export_limit;
            }

            $product_array = array();
            $total_products = 0;
            if ($batch_offset < $export_limit) {
                $args = array(
                    'status' => array('publish'),
                    'type' => array_keys(wc_get_product_types()),
                    'limit' => $limit,
                    'offset' => $real_offset,
                    'orderby' => $export_sortby,
                    'order' => $export_sort_order,
                    'return' => 'ids',
                    'paginate' => true,
                );


                if (!empty($prod_status)) {
                    $args['status'] = $prod_status;
                }

                if (!empty($prod_types)) {
                    $args['type'] = $prod_types;
                }

                if ( '' === $include_variations_type ) {
                    array_push($args['type'], 'variation');
                }                
                
                if (!empty($prod_exc_categories)) {
                    $args['exclude_category'] = $prod_exc_categories;
                }
                
                if( 'include_tag' === $tag_filter_type && !empty($inc_exc_tag) ){
                    $args['tag'] = $inc_exc_tag;
                }                
                if( 'exclude_tag' === $tag_filter_type && !empty($inc_exc_tag) ){
                    $args['exclude_tag'] = $inc_exc_tag;
                }                

                if (!empty($prod_inc_brands)) {
                    $args['include_brands'] = $prod_inc_brands;
                }
                if (!empty($prod_exc_brands)) {
                    $args['exclude_brands'] = $prod_exc_brands;
                }                
                
                if (!empty($prod_inc_categories)) {
                    $args['category'] = $prod_inc_categories;
                }

                if (!empty($prod_exc)) {
                    $args['exclude'] = $prod_exc;
                }

                if (!empty($exc_stock_status)) {
                    $available_status = wc_get_product_stock_status_options();
                    unset($available_status['outofstock']);                    
                    $args['stock_status'] = array_keys($available_status);
                }

                // Export all language products if WPML is active and the language selected is all.
                if (function_exists('icl_object_id') && isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], 'lang=all') !== false) {
                    $args['suppress_filters'] = true;
                }


                $args['exclude_discarded'] = '_wt_feed_discard'; // To exclude individual excluded from product fetching.

                $args = apply_filters("wt_feed_product_catalog_args", $args);


                $advanced_filter_options = array();
                
                $wt_pf_adv_filter_fields = !empty($form_data['post_type_form_data']['wt_pf_adv_filter_if[]']) ? (array)$form_data['post_type_form_data']['wt_pf_adv_filter_if[]'] : array();
                $wt_pf_adv_filter_condition = !empty($form_data['post_type_form_data']['wt_pf_adv_filter_condition[]']) ? (array)$form_data['post_type_form_data']['wt_pf_adv_filter_condition[]'] : array();
                $wt_pf_adv_filter_val = !empty($form_data['post_type_form_data']['wt_pf_adv_filter_val[]']) ? (array)$form_data['post_type_form_data']['wt_pf_adv_filter_val[]'] : array();
                $wt_pf_adv_filter_then = !empty($form_data['post_type_form_data']['wt_pf_adv_filter_then[]']) ? (array)$form_data['post_type_form_data']['wt_pf_adv_filter_then[]'] : array();
                      
                if( !empty($wt_pf_adv_filter_fields) ){
                    $advanced_filter_options['fields'] = $wt_pf_adv_filter_fields;
                    $advanced_filter_options['condition'] = $wt_pf_adv_filter_condition;
                    $advanced_filter_options['val'] = $wt_pf_adv_filter_val;
                    $advanced_filter_options['then'] = $wt_pf_adv_filter_then;       
                }

                /*
                 * WPML - Swicth language to selected language for temparory export
                 */
                if (class_exists('SitePress') && !empty($item_post_lang)) {
                    //$args['suppress_filters'] = true;
                    global $sitepress;
                    $current_lang = $sitepress->get_current_language(); // Take the current language to a variable to swicthback later.
                    $default_language = $sitepress->get_default_language();
                    $sitepress->switch_lang($item_post_lang);
                }

                $products = wc_get_products($args);

                $total_products = 0;
                if (0 == $batch_offset) { //first batch
                    $total_item_args = $args;
                    $total_item_args['limit'] = $export_limit; //user given limit
                    $total_item_args['offset'] = $current_offset; //user given offset
                    $total_products_count = wc_get_products($total_item_args);
                    $total_products = count($total_products_count->products);
                }

                /*
                 * WPML - Swicth language back to the previous site language after the batch reading.
                 */
                if (class_exists('SitePress') && !empty($item_post_lang)) {
                    //$args['suppress_filters'] = true;
                    global $sitepress;
                    $sitepress->switch_lang($current_lang); // Current language is previously stored
                }

                $products_ids = $products->products;

                // If include category is selected and variable products are under those category, the variations will not be returned by the WC query
                if (!empty($prod_inc_categories)) {
                    $temp_prod_ids = $products_ids;
                    foreach ($temp_prod_ids as $key => $product_id) {
                        $product = wc_get_product($product_id);
                        if ($product->is_type('variable')) {
                            $variations = $product->get_available_variations();
                            $variations_ids = wp_list_pluck($variations, 'variation_id');
                            foreach ($variations_ids as $variations_id) {
                                $products_ids[] = $variations_id;
                            }
                        }
                    }
                }

                foreach ($products_ids as $key => $product_id) {
                    $product = wc_get_product($product_id);

                    // Skip variations that belongs to a specific categories that is excluded in filter
                    if ($product->is_type('variation') && !empty($prod_exc_categories)) {
                        $parent_id = $product->get_parent_id();
                        if (has_term($prod_exc_categories, 'product_cat', $parent_id)) {
                            continue;
                        }
                    }
                    
                    //Skip variation other than default when Only include default product variation is checked.
                    if ($product->is_type('variation') && 'default' === $include_variations_type ) {
                        continue;
                    }                    

                    if ($product->is_type('variable') && 'default' === $include_variations_type ) {
                        $default_variation_id = $this->get_default_variation($product);
                        if ($default_variation_id) {
                            $product = wc_get_product($default_variation_id);
                        }
                    }
                    if ($product->is_type('variable') && 'lowest' === $include_variations_type ) {
                        $lowest_variation_id = $this->get_lowest_priced_variation_id($product);
                        if ($lowest_variation_id) {
                            $product = wc_get_product($lowest_variation_id);
                        }
                    }                                        
                    
                    if ($product->is_type('variable') && 'highest' === $include_variations_type ) {
                        $highest_variation_id = $this->get_highest_priced_variation_id($product);
                        if ($highest_variation_id) {
                            $product = wc_get_product($highest_variation_id);
                        }
                    }                    
                    
                    if ($product->is_type('variable') && '' === $include_variations_type ) {
                        continue;
                    }
                    if( $product->is_type( 'variation' ) ){
                        $parent_id = $product->get_parent_id();
                        $parent_post = get_post( $parent_id );
                        if( !is_object( $parent_post ) || ( is_object( $parent_post ) && ( 'draft' == $parent_post->post_status || 'private' == $parent_post->post_status || 'pending' == $parent_post->post_status ) ) ){
                            continue;
                        }
                    }
                    $this->parent_product = $product;
                    $this->product = $product;
                    $this->current_product_id = $product->get_id();

                    if(!$this->check_advanced_creteria($product, $advanced_filter_options)){
                        continue;
                    }                 
                    
                    $product_array[] = $this->generate_row_data_wc_lower($product);
                }
            }

            $return_products = array(
                'total' => $total_products,
                'data' => $product_array,
            );
            if (0 == $batch_offset && 0 == $total_products) {
                $return_products['no_post'] = __('Nothing to export under the selected criteria. Please try adjusting the filters.', 'webtoffee-product-feed-pro');
            }
            return $return_products;
        }

        public function get_default_variation($product) {

            $variation_id = false;

            foreach ($product->get_available_variations() as $variation_values) {
                foreach ($variation_values['attributes'] as $key => $attribute_value) {
                    $attribute_name = str_replace('attribute_', '', $key);
                    $default_value = $product->get_variation_default_attribute($attribute_name);
                    if ($default_value == $attribute_value) {
                        $is_default_variation = true;
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

        protected function generate_row_data_wc_lower($product_object) {

            $export_columns = $this->parent_module->get_selected_column_names();

            $product_id = $product_object->get_id();            

            $csv_columns = $export_columns;

            $export_columns = !empty($csv_columns) ? $csv_columns : array();

            $row = array();

            foreach ($export_columns as $key => $value) {
                if (method_exists($this, $value)) {
                    $row[$key] = $this->$value($key, $value, $export_columns);
                }elseif (strpos($value, 'meta:') !== false) {
                    $mkey = str_replace('meta:', '', $value);
                    $row[$key] = get_post_meta($product_id, $mkey, true);
                    // TODO
                    // wt_image_ function can be replaced with key exist check
                }elseif (strpos($value, 'wt_pf_pa_') !== false) {
                    $atr_key = str_replace('wt_pf_pa_', '', $value);
                    if($product_object->is_type('variation')){
                        $product_object = wc_get_product($product_object->get_parent_id());
                    }
                    $value = '';
                    if(is_object($product_object)){
                        $value = $product_object->get_attribute( $atr_key );
                    }
                    if ( ! empty( $value ) ) {
				$value = trim( $value );
			}
                    $row[$key] = $value;
                }elseif (strpos($value, 'wt_pf_cattr_') !== false) {
                    $atr_key = str_replace('wt_pf_cattr_', '', $value);
                    if($product_object->is_type('variation')){
                        $product_object = wc_get_product($product_object->get_parent_id());
                    }
                    $value = '';
                    if(is_object($product_object)){
                        $value = $product_object->get_attribute( $atr_key );

                    }
                    if ( ! empty( $value ) ) {
				$value = trim( $value );
                                $value = str_replace('|', ',', $value);
			}
                    $row[$key] = $value;
                } elseif (strpos($value, 'wt_static_map_vl:') !== false) { // Static value.
                    $static_feed_value = str_replace('wt_static_map_vl:', '', $value);
                    $row[$key] = $static_feed_value;
                } elseif (strpos($value, 'wt_compute_map_vl:') !== false) { // Computed value.
                    $compute_feed_value = str_replace('wt_compute_map_vl:', '', $value);
                    $compute_feed_value = trim($compute_feed_value);
                    $mode = substr($compute_feed_value, 0, 3);
                    $do_arithmatic = true;
                    if ($mode == '(+)') {                        
                        $amount = substr($compute_feed_value, 3);
                        $row[$key] = $this->wt_pf_product_field_calc($key, $amount, 'increase', $product_object);
                        $do_arithmatic = false;
                    }
                    if ($mode == '(-)') {
                        $amount = substr($compute_feed_value, 3);
                        $row[$key] = $this->wt_pf_product_field_calc($key, $amount, 'decrease', $product_object);
                        $do_arithmatic = false;
                    }
                    if ($mode == '(*)') {                        
                        $amount = substr($compute_feed_value, 3);
                        $row[$key] = $this->wt_pf_product_field_calc($key, $amount, 'multiply', $product_object);
                        $do_arithmatic = false;
                    }                    
                    if($do_arithmatic){
                        $row[$key] = $this->do_arithmetic($compute_feed_value);
                    }
                }else {
                    $row[$key] = '';
                }
            }

            return apply_filters("wt_batch_product_export_row_data_{$this->parent_module->module_base}", $row, $product_object);
        }

        /**
         * Get product store code.
         *
         * @return mixed|void
         */
        public function store_code($catalog_attr, $product_attr, $export_columns) {

            $store_code = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_advanced_settings('glpi_store_code');
            if ('' === $store_code) {
                $store_code = wp_strip_all_tags(self::get_store_name());
            }
            return apply_filters('wt_feed_filter_product_store_code', $store_code, $this->product);
        }

        /**
         * Get Packing method.
         *
         * @return mixed|void
         */
        public function pickup_method($catalog_attr, $product_attr, $export_columns) {


            $packing_method = get_post_meta($this->product->get_id(), '_wt_feed_glpi_pickup_method', true);
            if ('' == $packing_method) {
                $packing_method = get_post_meta($this->product->get_id(), '_wt_google_glpi_pickup_method', true);
            }
            return apply_filters('wt_feed_filter_product_packing_method', $packing_method, $this->product);
        }

        /**
         * Get Packing SLA.
         *
         * @return mixed|void
         */
        public function pickup_sla($catalog_attr, $product_attr, $export_columns) {


            $packing_sla = get_post_meta($this->product->get_id(), '_wt_feed_glpi_pickup_sla', true);
            if ('' == $packing_sla) {
                $packing_sla = get_post_meta($this->product->get_id(), '_wt_google_glpi_pickup_sla', true);
            }
            return apply_filters('wt_feed_filter_product_packing_sla', $packing_sla, $this->product);
        }

        /**
         * Get product id.
         *
         * @return mixed|void
         */
        public function promotion_id($catalog_attr, $product_attr, $export_columns) {

            return apply_filters('wt_feed_filter_product_promotion_id', $this->product->get_id(), $this->product);
        }

        public function sku($catalog_attr, $product_attr, $export_columns) {

            return apply_filters('wt_feed_filter_product_sku', $this->product->get_sku(), $this->product);
        }

        /**
         * Get product name.
         *
         * @return mixed|void
         */
        public function long_title($catalog_attr, $product_attr, $export_columns) {

            $title = $this->product->get_name();

            // Add all available variation attributes to variation title.
            if ($this->product->is_type('variation') && !empty($this->product->get_attributes())) {
                $title = $this->parent_product->get_name();
                $attributes = [];
                foreach ($this->product->get_attributes() as $slug => $value) {
                    $attribute = $this->product->get_attribute($slug);
                    if (!empty($attribute)) {
                        $attributes[$slug] = $attribute;
                    }
                }

                // set variation attributes with separator
                $separator = ',';

                $variation_attributes = implode($separator, $attributes);

                //get product title with variation attribute
                $get_with_var_attributes = apply_filters("wt_feed_get_product_title_with_variation_attribute", true, $this->product);

                if ($get_with_var_attributes) {
                    $title .= " - " . $variation_attributes;
                }
            }

            return apply_filters('wt_feed_filter_product_title', $title, $this->product);
        }

        public static function get_store_name() {

            $url = get_bloginfo('name');
            return ( $url) ? ( $url ) : 'My Store';
        }

        public function item_group_id($catalog_attr, $product_attr, $export_columns) {

            $id = ( $this->product->is_type('variation') ? $this->product->get_parent_id() : $this->product->get_id() );

            return apply_filters('wt_feed_filter_product_item_group_id', $id, $this->product);
        }

        public function availability($catalog_attr, $product_attr, $export_columns) {
            $status = $this->product->get_stock_status();
            if ('instock' === $status) {
                $status = 'in_stock';
            } elseif ('outofstock' === $status) {
                $status = 'out_of_stock';
            } elseif ('onbackorder' === $status) {
                $status = 'backorder';
            } elseif ('preorder' === $status) {
                $status = 'preorder';
            }


            return apply_filters('wt_feed_filter_product_availability', $status, $this->product);
        }

        public function availability_date($catalog_attr, $product_attr, $export_columns) {

            $availability_date = get_post_meta($this->product->get_id(), '_wt_feed_availability_date', true);

            if ( $availability_date ) {
                $availability_date = gmdate('c', strtotime($availability_date));
            }

            return apply_filters('wt_feed_filter_product_availability_date', $availability_date, $this->product);
        }

        public function quantity($catalog_attr, $product_attr, $export_columns) {
            $quantity = $this->product->get_stock_quantity();
            $status = $this->product->get_stock_status();

            //when product is outofstock , and it's quantity is empty, set quantity to 0
            if ('outofstock' === $status && $quantity === null) {
                $quantity = 0;
            }

            if ($this->product->is_type('variable') && $this->product->has_child()) {
                $visible_children = $this->product->get_visible_children();
                $qty = array();
                foreach ($visible_children as $child) {
                    $childQty = get_post_meta($child, '_stock', true);
                    $qty[] = (int) $childQty;
                }

                $quantity = array_sum($qty);
            }
            if ($this->product->is_type('variation')) {
                $parent_variations_qty = !empty($this->form_data['post_type_form_data']['wt_pf_parent_qty']) ? $this->form_data['post_type_form_data']['wt_pf_parent_qty'] : '';
                if( 'sumof_variation_qty' == $parent_variations_qty ){   
                    $parent_product = wc_get_product($this->product->get_parent_id());
                    $visible_children = $parent_product->get_visible_children();
                    $qty = array();
                    foreach ($visible_children as $child) {
                        $childQty = get_post_meta($child, '_stock', true);
                        $qty[] = (int) $childQty;
                    }
                    $quantity = array_sum($qty);
                }     
            }

            return apply_filters('wt_feed_filter_product_quantity', $quantity, $this->product);
        }

        /**
         * Get Product Sale Price start date.
         *
         * @return mixed|void
         */
        public function sale_price_sdate($catalog_attr, $product_attr, $export_columns) {
            $startDate = $this->product->get_date_on_sale_from();
            if (is_object($startDate)) {
                $sale_price_sdate = $startDate->date_i18n();
            } else {
                $sale_price_sdate = '';
            }

            return apply_filters('wt_feed_filter_product_sale_price_sdate', $sale_price_sdate, $this->product);
        }

        /**
         * Get Product Sale Price End Date.
         *
         * @return mixed|void
         */
        public function sale_price_edate($catalog_attr, $product_attr, $export_columns) {
            $endDate = $this->product->get_date_on_sale_to();
            if (is_object($endDate)) {
                $sale_price_edate = $endDate->date_i18n();
            } else {
                $sale_price_edate = "";
            }

            return apply_filters('wt_feed_filter_product_sale_price_edate', $sale_price_edate, $this->product);
        }

        public function first_variation_price() {

            $children = $this->product->get_visible_children();
            $price = $this->product->get_variation_price();
            if (isset($children[0]) && !empty($children[0])) {
                $variation = wc_get_product($children[0]);
                $price = $variation->get_price();
            }

            return apply_filters('wt_feed_filter_product_first_variation_price', $price, $this->product);
        }        

        public function price_with_tax($catalog_attr, $product_attr, $export_columns) {

            $tprice = $this->product->get_regular_price();
            $price = wc_get_price_including_tax($this->product, array('price' => $tprice));
            if ($price > 0) {
                                
                // woo-discount-rules plugin compatiblity                
                //$price = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $this->product, 1, $price, 'discounted_price', true, true);
                
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_price_with_tax', $price, $this->product);
        }

        public function current_price_with_tax($catalog_attr, $product_attr, $export_columns) {
            $cprice = $this->product->get_price();
            $price = wc_get_price_including_tax($this->product, array('price' => $cprice));
            if ($price > 0) {
                                
                // woo-discount-rules plugin compatiblity                
                //$price = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $this->product, 1, $price, 'discounted_price', true, true);
                
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_current_price_with_tax', $price, $this->product);
        }

        public function sale_price_with_tax($catalog_attr, $product_attr, $export_columns) {
            $sprice = $this->product->get_sale_price();
            $price = wc_get_price_including_tax($this->product, array('price' => $sprice));
            if ($price > 0) {
                                
                // woo-discount-rules plugin compatiblity                
                //$price = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $this->product, 1, $price, 'discounted_price', true, true);
                
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_sale_price_with_tax', $price, $this->product);
        }

        /** Get Google Sale Price effective date.
         *
         * @return string
         */
        public function promotion_effective_dates($catalog_attr, $product_attr, $export_columns) {
            $effective_date = '';
            $from = $this->sale_price_sdate($catalog_attr, $product_attr, $export_columns);
            $to = $this->sale_price_edate($catalog_attr, $product_attr, $export_columns);
            if (!empty($from) && !empty($to)) {
                $from = gmdate('c', strtotime($from));
                $to = gmdate('c', strtotime($to));

                $effective_date = $from . '/' . $to;
            }

            return $effective_date;
        }

    }

}
