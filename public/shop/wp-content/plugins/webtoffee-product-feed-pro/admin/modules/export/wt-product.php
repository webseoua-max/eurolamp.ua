<?php

if (!defined('WPINC')) {
    exit;
}

if (!class_exists('Webtoffee_Product_Feed_Pro_Product')) {

    class Webtoffee_Product_Feed_Pro_Product {

        public $parent_product;
        public $current_product_id;
        public $product;
        public $form_data;

        public function __construct($product) {
            $this->parent_product = $product;
            $this->current_product_id = $product->get_id();
            $this->product = $product;
        }

        /*
         * Translate press transalte.
         * @product_data   Product data string.
         */
        public function translate_press_e($product_data){
            
            // Translatepress
            $item_post_lang = !empty($this->form_data['post_type_form_data']['wt_pf_export_post_language']) ? $this->form_data['post_type_form_data']['wt_pf_export_post_language'] : '';
            if ( class_exists( 'TRP_Settings' ) && class_exists( 'TRP_Translation_Render' ) && '' !==  $item_post_lang) {
                $settings   = ( new TRP_Settings() )->get_settings();
                $trp_render = new TRP_Translation_Render( $settings );
                global $TRP_LANGUAGE;
                $default_language = $TRP_LANGUAGE;
                $TRP_LANGUAGE     = $item_post_lang;

                $product_data = strip_tags( $trp_render->translate_page( $product_data ) );                
                //reset trp_language
		$TRP_LANGUAGE = $default_language;                
                
            }
            return $product_data;
            
        }


        /**
         * Get product name.
         *
         * @return mixed|void
         */
        public function title($catalog_attr, $product_attr, $export_columns) {

            $title = $this->product->get_name();            
            
            $title = $this->translate_press_e($title);            
            
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_title", $title, $this->product, $this->form_data);
        }

        /**
         * Get parent product title for variation.
         *
         * @return mixed|void
         */
        public function parent_title($catalog_attr, $product_attr, $export_columns) {
            $title = '';
            if ($this->product->is_type('variation')) {
                $parent_product = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_product)) {
                    $title = $parent_product->get_name();
                }
            } else {
                $title = $this->product->get_name();
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_parent_title", $title, $this->product, $this->form_data);
        }

        /**
         * Get parent product description for variation.
         *
         * @return mixed|void
         */
        public function parent_description($catalog_attr, $product_attr, $export_columns) {
            $description = '';
            if ($this->product->is_type('variation')) {
                $parent_product = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_product)) {
                    $description = $parent_product->get_description();
                }
            } else {
                $description = $this->product->get_description();
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_parent_description", $description, $this->product, $this->form_data);
        }        
        
        /**
         * Get product description.
         *
         * @return mixed|void
         */
        public function description($catalog_attr, $product_attr, $export_columns) {

            $description = $this->product->get_description();

            // Get Variation Description
            if ('' === $description && $this->product->is_type('variation')) {
                $description = '';
                $parent_product = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_product)) {
                    $description = $parent_product->get_description();
                }
            }

            if ('' === $description) {
                $description = $this->product->get_short_description();
            }

            // Add variations attributes after description to prevent Google error
            if ($this->product->is_type('variation') && ( '' === $description )) {
                $variationInfo = explode('-', $this->product->get_name());
                if (isset($variationInfo[1])) {
                    $extension = $variationInfo[1];
                } else {
                    $extension = $this->product->get_id();
                }
                $description .= ' ' . $extension;
            }

            
            $description = $this->translate_press_e($description); 
            
            $do_shortcode = apply_filters("wt_feed_{$this->parent_module->module_base}_product_description_do_shortcode", true);
            
            if($do_shortcode){
                $description = do_shortcode($description);
            }
            
            //strip tags and special characters
            $description = trim( strip_tags($description) );

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_description", $description, $this->product, $this->form_data);
        }

        
        
        /**
         * Get product description with HTML tags.
         *
         * @return mixed|void
         */
        public function description_with_html($catalog_attr, $product_attr, $export_columns) {
            $description = $this->product->get_description();

            // Get Variation Description
            if (empty($description) && $this->product->is_type('variation')) {
                $description = '';
                if (!is_null($this->parent_product)) {
                    $description = $this->parent_product->get_description();
                }
            }

            if (empty($description)) {
                $description = $this->product->get_short_description();
            }

            //$description = CommonHelper::remove_shortcodes( $description );
            // Add variations attributes after description to prevent Facebook error
            if ($this->product->is_type('variation')) {
                $variationInfo = explode('-', $this->product->get_name());
                if (isset($variationInfo[1])) {
                    $extension = $variationInfo[1];
                } else {
                    $extension = $this->product->get_id();
                }
                $description .= ' ' . $extension;
            }

            //remove spacial characters
            $description = wp_check_invalid_utf8(wp_specialchars_decode($description), true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_description_with_html", $description, $this->product, $this->form_data);
        }

        /**
         * Get product short description.
         *
         * @return mixed|void
         */
        public function short_description($catalog_attr, $product_attr, $export_columns) {

            $short_description = $this->product->get_short_description();

            // Get Variation Short Description
            if (empty($short_description) && $this->product->is_type('variation')) {
                $parent_product = wc_get_product($this->product->get_parent_id());
                if(is_object($parent_product)){
                    $short_description = $parent_product->get_short_description();
                }
            }
 
            // Strip tags and special characters
            $short_description = strip_tags($short_description);            

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_short_description", $short_description, $this->product, $this->form_data);
        }

        /**
         * Get product type.
         *
         * @return mixed|void
         */
        public function product_type($catalog_attr, $product_attr, $export_columns) {
            $id = $this->product->get_id();
            if ($this->product->is_type('variation')) {
                $id = $this->product->get_parent_id();
            }
            $item_post_lang = !empty($this->form_data['post_type_form_data']['wt_pf_export_post_language']) ? $this->form_data['post_type_form_data']['wt_pf_export_post_language'] : '';

            
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
                                  
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $product_categories = '';
            $term_list = get_the_terms($id, 'product_cat');

            if (is_array($term_list)) {
                $col = array_column($term_list, "term_id");
                array_multisort($col, SORT_ASC, $term_list);
                $term_list = array_column($term_list, "name");   
                foreach ($term_list as $key => $term_name){
                    $term_list[$key] = $this->translate_press_e($term_name); 
                }
                $product_categories = implode($separator, $term_list);
            }
            /*
             * WPML - Swicth language back to the previous site language after the DB reading.
             */
            if (class_exists('SitePress') && !empty($item_post_lang)) {
                global $sitepress;
                $sitepress->switch_lang($current_lang); // Current language is previously stored
            }

            
            
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_local_category", $product_categories, $this->product, $this->form_data);
        }        
        
        
        /**
         * Get product URL.
         *
         * @return mixed|void
         */
        public function link($catalog_attr, $product_attr, $export_columns) {            
            
            $item_post_lang = !empty($this->form_data['post_type_form_data']['wt_pf_export_post_language']) ? $this->form_data['post_type_form_data']['wt_pf_export_post_language'] : '';            
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
                      
            $link = $this->product->get_permalink();
            
            /*
             * WPML - Swicth language back to the previous site language after the DB reading.
             */
            if (class_exists('SitePress') && !empty($item_post_lang)) {
                global $sitepress;
                $sitepress->switch_lang($current_lang); // Current language is previously stored
            }
            
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_link", $link, $this->product, $this->form_data);
        }        
        
        /**
         * Get product primary category.
         *
         * @return mixed|void
         */
        public function primary_category($catalog_attr, $product_attr, $export_columns) {
            $parent_category = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');

            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $parent_category = $full_category_array[0];
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_primary_category", $parent_category, $this->product, $this->form_data);
        }

        /**
         * Get product primary category id.
         *
         * @return mixed|void
         */
        public function primary_category_id($catalog_attr, $product_attr, $export_columns) {
            $parent_category_id = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $parent_category_obj = get_term_by('name', $full_category_array[0], 'product_cat');
                $parent_category_id = isset($parent_category_obj->term_id) ? $parent_category_obj->term_id : "";
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_primary_category_id", $parent_category_id, $this->product, $this->form_data);
        }

        /**
         * Get product child category.
         *
         * @return mixed|void
         */
        public function child_category($catalog_attr, $product_attr, $export_columns) {
            $child_category = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $child_category = end($full_category_array);
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_child_category", $child_category, $this->product, $this->form_data );
        }
        
        /**
         * Get product child category id.
         *
         * @return mixed|void
         */
        public function child_category_id($catalog_attr, $product_attr, $export_columns) {
            $child_category_id = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $child_category_obj = get_term_by('name', end($full_category_array), 'product_cat');
                $child_category_id = isset($child_category_obj->term_id) ? $child_category_obj->term_id : "";
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_child_category_id", $child_category_id, $this->product, $this->form_data);
        }
        
        
        public function price($catalog_attr, $product_attr, $export_columns) {
            
            $rprice = $this->product->get_regular_price();                           

            // If fox switcher is used, the price returned is current viewing currency price.
            if(class_exists('WOOCS') ){
                $rprice = get_post_meta($this->product->get_id(), '_regular_price', true);
            }

            if ($this->product->is_type('variable')) {
                $rprice = $this->first_variation_price();
            }      
            
            $price = $rprice;
            if( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) || is_plugin_active( 'woo-discount-rules-pro/woo-discount-rules-pro.php' ) ){
                // woo-discount-rules plugin compatiblity                        
                $discounted_price = apply_filters( 'advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $this->product, 1, $rprice, 'discounted_price', true, true );
                if ( !empty( $discounted_price ) && $this->product->is_on_sale() ) {
                    $price = $discounted_price;
                }else{
                    $price = $rprice;
                }
                if($this->product->is_on_sale() && !empty( $discounted_price ) ){
                    $price = $this->product->get_sale_price();
                }
            }else{
                 $price = $rprice;
            }       
            
            if(class_exists( 'YITH_WC_Dynamic_Pricing_Discounts' ) ){
                if($this->product->is_on_sale()){
                    $price = $this->product->get_sale_price();
                }
            }            

            $selected_currency = get_woocommerce_currency();
            if (( class_exists('WCML_Multi_Currency') || class_exists('WOOCS') || class_exists('WOOMULTI_CURRENCY_F') || class_exists('WC_Aelia_CurrencySwitcher') ) && !empty($this->form_data['post_type_form_data']['wt_pf_export_post_currency'])) {
                $selected_currency = $this->form_data['post_type_form_data']['wt_pf_export_post_currency'];
                $price = $this->get_converted_price($price, $selected_currency);
            }

            // On auto-refresh feed, the product get_price getting as zero when WCML is active. So we need to get the price from the database.
            if( wp_doing_cron() && class_exists('WCML_Multi_Currency') ){
                if( wp_doing_cron() && class_exists('WCML_Multi_Currency') ){				
                    global $woocommerce_wpml;
                    $price = $woocommerce_wpml->multi_currency->prices->get_product_price_in_currency( $this->product->get_id(), $this->form_data['post_type_form_data']['wt_pf_export_post_currency'] );
                    
                }
			}

			// Use static transalted price instead of currency conversion if its set statically.
			if(isset($this->form_data['post_type_form_data']['wt_pf_export_post_currency']) && class_exists('WCML_Multi_Currency') ){
				$pid = apply_filters( 'wpml_object_id', $this->product->get_id(), 'post' );	
				$feed_currency = $this->form_data['post_type_form_data']['wt_pf_export_post_currency'];
				$tr_price = get_post_meta($pid, '_regular_price_' . $feed_currency, true);
				if($tr_price){
					$price = $tr_price;
				}
			}       
            
            if(class_exists('Alg_WC_Global_Shop_Discount')){
                $core = new Alg_WC_Global_Shop_Discount_Core();
                $price = get_post_meta($this->product->get_id(), '_regular_price', true);
                $price = $core->add_global_shop_discount($price, $this->product, 'regular');
            }

            if ($price > 0) {                            
                
                $need_decimal_format = apply_filters('wt_wc_format_decimal_needed', true);
                if($need_decimal_format){
                    $price = wc_format_decimal($price, 2);
                }
                $price = $price . ' ' . $selected_currency;
            }
            
            $price = apply_filters('wt_feed_filter_product_price', $price, $this->product, $this->form_data);
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_price", $price, $this->product, $this->form_data);
        }        
        

        public function sale_price($catalog_attr, $product_attr, $export_columns) {
            $price = $this->product->get_sale_price();
            
            // If fox switcher is used, the price returned is current viewing currency price.
			if(class_exists('WOOCS') ){
                $price = get_post_meta($this->product->get_id(), '_sale_price', true);
            }

            // On auto-refresh feed, the product get_sale_price getting as zero when WCML is active. So we need to get the price from the database.
            if( wp_doing_cron() && class_exists('WCML_Multi_Currency') ){
                $price = get_post_meta($this->product->get_id(), '_sale_price', true);
			}            

            if(!$price && ( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) || is_plugin_active( 'woo-discount-rules-pro/woo-discount-rules-pro.php' ) ) ){
                $price = $this->product->get_regular_price();
            }            

            if(is_plugin_active( 'yaypricing/yaypricing.php' )  || is_plugin_active( 'yaypricing-pro/yaypricing.php' ) ){
                $product_sale             = new \YAYDP\Core\Sale_Display\YAYDP_Product_Sale( $this->product );
                $min_max_discounted_price = $product_sale->get_min_max_discounted_price();
                    if(is_array($min_max_discounted_price)){    
                        $min_discounted_price               = $min_max_discounted_price['min'];
                        $max_discounted_price               = $min_max_discounted_price['max'];
                        $discounted_prices = array_unique( array( $min_discounted_price, $max_discounted_price ) );
                    if ( !empty( $discounted_prices ) ) {
                        $price = $discounted_prices[0];
                    }
                }
            }

            $selected_currency = get_woocommerce_currency();
            if (( class_exists('WCML_Multi_Currency') || class_exists('WOOCS') || class_exists('WOOMULTI_CURRENCY_F') || class_exists('WC_Aelia_CurrencySwitcher') ) && !empty($this->form_data['post_type_form_data']['wt_pf_export_post_currency'])) {
                $selected_currency = $this->form_data['post_type_form_data']['wt_pf_export_post_currency'];
                $price = $this->get_converted_price($price, $selected_currency);
            }

            if(class_exists( 'YITH_WC_Dynamic_Pricing_Discounts' ) ){
                if($this->product->is_on_sale()){
                    $price = $this->product->get_sale_price();
                }else{
                    $price = $this->product->get_regular_price();
                }
                $product_manager = YWDPD_Frontend::get_instance();
                $price = $product_manager->get_dynamic_price($price, $this->product, 1);
            }               
            
            // RightPress dynamic pricing support.
            if(class_exists('RP_WCDPD_Product_Pricing')){
             $price = RP_WCDPD_Product_Pricing::apply_simple_product_pricing_rules_to_product_price($price, $this->product);
            }

			// Use static transalted price instead of currency conversion if its set statically.
			if(isset($this->form_data['post_type_form_data']['wt_pf_export_post_currency']) && class_exists('WCML_Multi_Currency') ){
				$pid = apply_filters( 'wpml_object_id', $this->product->get_id(), 'post' );	
				$feed_currency = $this->form_data['post_type_form_data']['wt_pf_export_post_currency'];
				$tr_price = get_post_meta($pid, '_sale_price_' . $feed_currency, true);
				if($tr_price){
					$price = $tr_price;
				}
			}

            if(class_exists('Alg_WC_Global_Shop_Discount')){
                $core = new Alg_WC_Global_Shop_Discount_Core();
                $price = get_post_meta($this->product->get_id(), '_sale_price', true);
                $price = $core->add_global_shop_discount($price, $this->product, 'sale');
            }

            if ($price > 0) {
                
                // woo-discount-rules plugin compatiblity                                                              
                $discounted_price = apply_filters( 'advanced_woo_discount_rules_get_product_discount_price_from_custom_price', false, $this->product, 1, $price, 'discounted_price', true, true );

                if ( !empty( $discounted_price ) ) {
                        $price = $discounted_price;
                }
                
                $need_decimal_format = apply_filters('wt_wc_format_decimal_needed', true);
                if($need_decimal_format){
                    $price = wc_format_decimal($price, 2);
                }
                $price = $price . ' ' . $selected_currency;
            }
            $price = apply_filters('wt_feed_filter_product_sale_price', $price, $this->product, $this->form_data);
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_sale_price", $price, $this->product, $this->form_data);
        }        
        

        public function google_product_category($catalog_attr, $product_attr, $export_columns) {


            $custom_google_category = get_post_meta($this->current_product_id, '_wt_google_google_product_category', true);

            if ('' == $custom_google_category) {

                $product_id = $this->current_product_id;
                // If variation, take the category from the parent.
                if($this->product->is_type('variation')){
                    $product_id = $this->product->get_parent_id();
                }
                
                $category_path = wp_get_post_terms( $product_id, 'product_cat', array('fields' => 'all'));

                $google_product_category = [];
                foreach ($category_path as $category) {
                    $google_category_id = get_term_meta($category->term_id, 'wt_google_category', true);
                    if ($google_category_id) {

                        $google_category_list = wp_cache_get('wt_fbfeed_google_product_categories_array');

                        if (false === $google_category_list) {
                            $google_category_list = Webtoffee_Product_Feed_Sync_Pro_Google::get_category_array();
                            wp_cache_set('wt_fbfeed_google_product_categories_array', $google_category_list, '', WEEK_IN_SECONDS);
                        }


                        $google_category = isset($google_category_list[$google_category_id]) ? $google_category_list[$google_category_id] : '';
                        if ('' !== $google_category) {
                            $google_product_category[] = $google_category;
                        }
                    }
                }


                $google_product_category = empty($google_product_category) ? '' : $google_product_category[0];
            } else {

                if( is_string($custom_google_category) || is_numeric($custom_google_category) ){
                $google_category_list = wp_cache_get('wt_fbfeed_google_product_categories_array');

                if (false === $google_category_list) {
                    $google_category_list = Webtoffee_Product_Feed_Sync_Pro_Google::get_category_array();
                    wp_cache_set('wt_fbfeed_google_product_categories_array', $google_category_list, '', WEEK_IN_SECONDS);
                }

                $google_product_category = $google_category_list[$custom_google_category];
                }else{
                    $google_product_category = '';
                }
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_google_category", $google_product_category, $this->product, $this->form_data);
        }        
        
        public function parent_sku($catalog_attr, $product_attr, $export_columns) {

            $parent_sku = $this->product->get_sku();
            if ($this->product->is_type('variation')) {
                $parent_prod = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_prod)) {
                    $parent_sku = $parent_prod->get_sku();
                }
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_parent_sku", $parent_sku, $this->product, $this->form_data);
        }        
        
        public function gender($catalog_attr, $product_attr, $export_columns) {

            $gender = get_post_meta($this->product->get_id(), '_wt_feed_gender', true);

            if ('' == $gender and $this->product->is_type('variation')) {

                $attributes = $this->product->get_variation_attributes();

                if (!$attributes) {
                    return apply_filters("wt_feed_{$this->parent_module->module_base}_product_gender", $gender, $this->product, $this->form_data);
                }

                $variant_names = array_keys($attributes);

                foreach ($variant_names as $original_variant_name) {

                    $label = wc_attribute_label($original_variant_name, $this->product);

                    $new_name = str_replace('custom_data:', '', self::sanitize_variant_name($original_variant_name));
                    if ('gender' === $new_name || 'geschlecht' === $new_name || 'Geschlecht' === $new_name) {
                        if ($options = $this->get_variant_option_name($this->product->get_id(), $label, $attributes[$original_variant_name])) {

                            if (is_array($options)) {

                                $option_values = array_values($options);
                            } else {

                                $option_values = [$options];

                                if (count($option_values) === 1 && empty($option_values[0])) {
                                    $option_values[0] = 'any';
                                }
                            }

                            switch ($new_name) {

                                case 'gender':
                                case 'geschlecht':
                                case 'Geschlecht':
                                    $gender = $option_values[0];

                                    break;

                                default:
                                    break;
                            }
                        }
                    }
                }
                if ('' == $gender) {
                    $parent = wc_get_product($this->product->get_parent_id());
                    $product_attributes = $parent->get_attributes();

                    if (isset($product_attributes['gender'])) {
                        $gender = $product_attributes['gender']['options']['0'];
                    }
                    if (isset($product_attributes['geschlecht'])) {
                        $gender = $product_attributes['geschlecht']['options']['0'];
                    }
                    if (isset($product_attributes['Geschlecht'])) {
                        $gender = $product_attributes['Geschlecht']['options']['0'];
                    }
                }
                return apply_filters("wt_feed_{$this->parent_module->module_base}_product_gender", $gender, $this->product, $this->form_data);
            } elseif ('' == $gender) {
                $product_attributes = $this->product->get_attributes();
                if (isset($product_attributes['gender'])) {
                    $gender = $product_attributes['gender']['options']['0'];
                }
                if (isset($product_attributes['geschlecht'])) {
                    $gender = $product_attributes['geschlecht']['options']['0'];
                }
                if (isset($product_attributes['Geschlecht'])) {
                    $gender = $product_attributes['Geschlecht']['options']['0'];
                }
            }
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_gender", $gender, $this->product, $this->form_data);
        }

        public function size($catalog_attr, $product_attr, $export_columns) {

            $size = get_post_meta($this->product->get_id(), '_wt_feed_size', true);

            if ('' == $size and $this->product->is_type('variation')) {


                $attributes = $this->product->get_variation_attributes();

                if (!$attributes) {
                    return apply_filters("wt_feed_{$this->parent_module->module_base}_product_size", $size, $this->product, $this->form_data);
                }

                $variant_names = array_keys($attributes);

                foreach ($variant_names as $original_variant_name) {

                    $label = wc_attribute_label($original_variant_name, $this->product);

                    $new_name = str_replace('custom_data:', '', self::sanitize_variant_name($original_variant_name));
                    if ('size' === $new_name || 'sizes' === $new_name || 'größe' === $new_name || 'grose' === $new_name || 'groesse' === $new_name || 'groessen' === $new_name) {
                        if ($options = $this->get_variant_option_name($this->product->get_id(), $label, $attributes[$original_variant_name])) {

                            if (is_array($options)) {

                                $option_values = array_values($options);
                            } else {

                                $option_values = [$options];

                                if (count($option_values) === 1 && empty($option_values[0])) {
                                    $option_values[0] = 'any';
                                }
                            }
                            switch ($new_name) {

                                case 'size':
                                case 'sizes':
                                case 'grose':
                                case 'groesse':
                                case 'größe':
                                case 'groessen':
                                    $size = $option_values[0];
                                    break;

                                default:
                                    break;
                            }
                        }
                    }
                }
                if ('' == $size) {
                    $parent = wc_get_product($this->product->get_parent_id());
                    $product_attributes = $parent->get_attributes();
                    if (isset($product_attributes['size'])) {
                        $size = $product_attributes['size']['options']['0'];
                    }
                    if (isset($product_attributes['größe'])) {
                        $size = $product_attributes['größe']['options']['0'];
                    }
                    if (isset($product_attributes['groesse'])) {
                        $size = $product_attributes['groesse']['options']['0'];
                    }
                    if (isset($product_attributes['groessen'])) {
                        $size = $product_attributes['groessen']['options']['0'];
                    }
                    if (isset($product_attributes['sizes'])) {
                        $size = $product_attributes['sizes']['options']['0'];
                    }
                }
                return apply_filters("wt_feed_{$this->parent_module->module_base}_product_size", $size, $this->product, $this->form_data);
            } elseif ('' == $size) {
                $product_attributes = $this->product->get_attributes();
                if (isset($product_attributes['size'])) {
                    $size = $product_attributes['size']['options']['0'];
                }
                if (isset($product_attributes['größe'])) {
                    $size = $product_attributes['größe']['options']['0'];
                }
                if (isset($product_attributes['groesse'])) {
                    $size = $product_attributes['groesse']['options']['0'];
                }
                if (isset($product_attributes['groessen'])) {
                    $size = $product_attributes['groessen']['options']['0'];
                }
                if (isset($product_attributes['sizes'])) {
                    $size = $product_attributes['sizes']['options']['0'];
                }
            }
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_size", $size, $this->product, $this->form_data);
        }

        public function color($catalog_attr, $product_attr, $export_columns) {

            $color = get_post_meta($this->product->get_id(), '_wt_feed_color', true);

            if ('' == $color and $this->product->is_type('variation')) {


                $attributes = $this->product->get_variation_attributes();

                if (!$attributes) {
                    return apply_filters("wt_feed_{$this->parent_module->module_base}_product_color", $color, $this->product, $this->form_data);
                }

                $variant_names = array_keys($attributes);

                foreach ($variant_names as $original_variant_name) {

                    $label = wc_attribute_label($original_variant_name, $this->product);

                    $new_name = str_replace('custom_data:', '', self::sanitize_variant_name($original_variant_name));
                    if ('color' === $new_name || 'colors' === $new_name || 'farbe' === $new_name || 'farben' === $new_name) {
                        if ($options = $this->get_variant_option_name($this->product->get_id(), $label, $attributes[$original_variant_name])) {

                            if (is_array($options)) {

                                $option_values = array_values($options);
                            } else {

                                $option_values = [$options];

                                if (count($option_values) === 1 && empty($option_values[0])) {
                                    $option_values[0] = 'any';
                                }
                            }

                            switch ($new_name) {

                                case 'color':
                                case 'colors':    
                                case 'farbe':
                                case 'farben':
                                    $color = $option_values[0];

                                    break;

                                default:
                                    break;
                            }
                        }
                    }
                }
                if ('' == $color) {
                    $parent = wc_get_product($this->product->get_parent_id());
                    $product_attributes = $parent->get_attributes();
                    if (isset($product_attributes['color'])) {
                        $color = $product_attributes['color']['options']['0'];
                    }
                    if (isset($product_attributes['farbe'])) {
                        $color = $product_attributes['farbe']['options']['0'];
                    }
                    if (isset($product_attributes['farben'])) {
                        $color = $product_attributes['farben']['options']['0'];
                    }
                    if (isset($product_attributes['Farbe'])) {
                        $color = $product_attributes['Farbe']['options']['0'];
                    }
                    if (isset($product_attributes['colors'])) {
                        $color = $product_attributes['colors']['options']['0'];
                    }
                }
                return apply_filters("wt_feed_{$this->parent_module->module_base}_product_color", $color, $this->product, $this->form_data);
            } elseif ('' == $color) {
                $product_attributes = $this->product->get_attributes();
                if (isset($product_attributes['color'])) {
                    $color = $product_attributes['color']['options']['0'];
                }
                if (isset($product_attributes['farbe'])) {
                    $color = $product_attributes['farbe']['options']['0'];
                }
                if (isset($product_attributes['farben'])) {
                    $color = $product_attributes['farben']['options']['0'];
                }
                if (isset($product_attributes['Farbe'])) {
                    $color = $product_attributes['Farbe']['options']['0'];
                }
                if (isset($product_attributes['colors'])) {
                    $color = $product_attributes['colors']['options']['0'];
                }
            }
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_color", $color, $this->product, $this->form_data);
        }

        public function get_variant_option_name($product_id, $label, $default_value) {

            $meta = get_post_meta($product_id, $label, true);
            $attribute_name = str_replace('attribute_', '', $label);
            $term = get_term_by('slug', $meta, $attribute_name);
            return ( $term && $term->name ) ? $term->name : $default_value;
        }

        public static function sanitize_variant_name($name) {

            $name = str_replace(array('attribute_', 'pa_'), '', strtolower($name));

            if ('colour' === $name) {
                $name = 'color';
            }

            switch ($name) {
                case 'size':
                case 'color':
                case 'gender':
                case 'pattern':
                    break;
                default:
                    $name = 'custom_data:' . strtolower($name);
                    break;
            }

            return $name;
        }
        
        


        public function custom_attr_data($product, $attribute_name) {


                $attributes = $product->get_variation_attributes();
                $attr_value = '';

                if (!$attributes) {
                    return apply_filters("wt_feed_{$attribute_name}_product_data", $attribute_name, $product, $this->form_data);
                }

                $variant_names = array_keys($attributes);

                foreach ($variant_names as $original_variant_name) {

                    $label = wc_attribute_label($original_variant_name, $product);

                    $new_name = str_replace('custom_data:', '', self::sanitize_variant_name($original_variant_name));
                    
                        if ($options = $this->get_variant_option_name($product->get_id(), $label, $attributes[$original_variant_name])) {

                            if (is_array($options)) {

                                $option_values = array_values($options);
                            } else {

                                $option_values = [$options];

                                if (count($option_values) === 1 && empty($option_values[0])) {
                                    $option_values[0] = 'any';
                                }
                            }

                            switch ($new_name) {

                                case $attribute_name:
                                    $attr_value = $option_values[0];

                                    break;

                                default:
                                    break;
                            }
                        }
                    
                }
                if ('' == $attr_value) {
                    $parent = wc_get_product($product->get_parent_id());
                    $product_attributes = $parent->get_attributes();
                    if (isset($product_attributes[$attribute_name])) {
                        $attr_value = $product_attributes[$attribute_name]['options']['0'];
                    }
                }
                if ('' == $attr_value) {
                    $product_attributes = $product->get_attributes();
                    if (isset($product_attributes[$attribute_name])) {
                        $attr_value = $product_attributes[$attribute_name]['options']['0'];
                    }
                }
                return apply_filters("wt_feed_{$attribute_name}_product_data", $attr_value, $product, $this->form_data);
        }
        

        
        
        public function check_advanced_creteria($product, $advanced_filter_options){
            

            $include = false;
            $filter_gate = apply_filters('wt_pf_advanced_feed_filter_gate', 'AND');
            if (!empty($advanced_filter_options['fields'])) {
                $number_of_conditions = count($advanced_filter_options['fields']);
                for( $i = 0; $i < $number_of_conditions; $i++ ) {
                   $post_field = '';
                   $filter_field = $advanced_filter_options['fields'][$i];

                   $method_name = "get_".$filter_field;
                   if(isset($filter_field) && method_exists($product, $method_name)){
                        $post_field = $product->$method_name();
                   }elseif(method_exists($this, $method_name)) {
                        $post_field = $this->$method_name($product);
                    }elseif(strpos($filter_field, 'meta:') !== false) {
                        $mkey = str_replace('meta:', '', $filter_field);
                        $post_field = get_post_meta($product->get_id(), $mkey, true);
                    }elseif (strpos($filter_field, 'wt_pf_cattr_') !== false) {
                        $atr_key = str_replace('wt_pf_cattr_', '', $filter_field);
                        if($product->is_type('variation')){
                            $product = wc_get_product($product->get_parent_id());
                        }
                        $post_field = '';
                        if(is_object($product)){
                            $value = $product->get_attribute( $atr_key );
                        }
                        if ( ! empty( $value ) ) {
                                    $value = trim( $value );
                                    $value = str_replace('|', ',', $value);
                        }
                        $post_field = $value;
                    }elseif (strpos($filter_field, 'wt_pf_pa_') !== false) { // Global product attribute case.
                                                
                        if($product->is_type('variation')){
                            $product = wc_get_product($product->get_parent_id());
                        }
                        $pa_attr_key = str_replace('wt_pf_pa_', ' ', $filter_field);
                        $pa_attr_val = $product->get_attribute( $pa_attr_key );
                        $post_field = strtolower($pa_attr_val);
                    }
                    
                    if(!$post_field){
                            $post_field = get_post_meta($product->get_id(), '_wt_feed_'.$filter_field, true);
                    } 
                    
                   $filter_condition = $advanced_filter_options['condition'][$i];
                   $filter_val = isset($advanced_filter_options['val'][$i]) ? $advanced_filter_options['val'][$i] : '';
                   $filter_then = isset( $advanced_filter_options['then'][$i] ) ? $advanced_filter_options['then'][$i] : 'include';

                    if( is_int($post_field) ){
                        $filter_val = intval( $filter_val );
                    }       

                    $is_condition_satisfied = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::product_advanced_filter($post_field,$filter_condition, $filter_val, $filter_field );

                   if( $is_condition_satisfied && 'include' === $filter_then ){
                        $include = true;                        
                   }elseif( $is_condition_satisfied && 'exclude' === $filter_then ){
                       $include = false;
                   }elseif( !$is_condition_satisfied && 'exclude' === $filter_then ){
                       $include = true;
                   }else{
                       $include = false;
                   } 
                   if( 'AND' !== $filter_gate && $is_condition_satisfied ){
                        $include = true;
                        break;
                   }
                   if( 'AND' === $filter_gate && $include === false ){
                       return $include;
                   }
                }
            }else{
                $include = true;
            }

            return $include;
        }
        
        
        public function get_lowest_priced_variation_id($product) {

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
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_lowest_variation", $lowest_price_variation_id, $product, $this->form_data );
        }     
        
        /**
         * Get highest priced variation id.
         * 
         */
        public function get_highest_priced_variation_id($product) {

            // Initialize variables
            $highest_price = null;
            $highest_price_variation_id = null;
            
            // Loop through the variations
            foreach ($product->get_available_variations() as $variation) {
                // Get the variation price
                $variation_price = floatval($variation['display_price']);

                // Compare with the lowest price found so far
                if ($highest_price === null || $variation_price > $highest_price) {
                    $highest_price = $variation_price;
                    $highest_price_variation_id = $variation['variation_id'];
                }
            }
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_highest_variation", $highest_price_variation_id, $product, $this->form_data );
        }         
        
        /**
         * Get additional attributes for variation other than color, size, gender and pattern.
         *
         * @return mixed|void
         */
        public function additional_variant_attribute($catalog_attr, $product_attr, $export_columns) {

            if ( !$this->product->is_type( 'variation' ) ) {
				return '';
			}

			$attributes = $this->product->get_variation_attributes();


			if ( !$attributes ) {
				return [];
			}
                        $product_data = [];
			$variant_names	 = array_keys( $attributes );
			$variant_data	 = [];

			foreach ( $variant_names as $original_variant_name ) {


				$label = wc_attribute_label( $original_variant_name, $this->product );

				$new_name = str_replace( 'custom_data:', '', self::sanitize_variant_name( $original_variant_name ) );


				if ( $options = $this->get_variant_option_name( $this->product->get_id(), $label, $attributes[ $original_variant_name ] ) ) {

					if ( is_array( $options ) ) {

						$option_values = array_values( $options );
					} else {

						$option_values = [ $options ];

						if ( count( $option_values ) === 1 && empty( $option_values[ 0 ] ) ) {
							$option_values[ 0 ]				 = 'any';
							$product_data[ 'checkout_url' ]	 = $product_data[ 'url' ];
						}
					}

					if ( 'gender' === $new_name ) {

						$product_data[ $new_name ] = $option_values[ 0 ];
					}

					switch ( $new_name ) {

						case 'size':
						case 'color':
                                                    break;
						case 'pattern':

							$variant_data[] = [
								'product_field'	 => $new_name,
								'label'			 => $label,
								'options'		 => $option_values,
							];

							$product_data[ $new_name ] = $option_values[ 0 ];

							break;

						case 'gender':

							if ( $product_data[ $new_name ] ) {

								$variant_data[] = [
									'product_field'	 => $new_name,
									'label'			 => $label,
									'options'		 => $option_values,
								];
							}

							break;

						default:

                                                        if(!isset($product_data)){
                                                            $product_data = [];
                                                        }
                                                        $variant_details = array(
                                                            'label' => ucwords( $new_name ) ,
                                                            'value' => urldecode( $option_values[ 0 ] ),
                                                        );
                                                        
                                                        array_push($product_data, $variant_details);//							
							break;
					}
                                    } else {

					continue;
				}
			}
                        if( 'xml' !== $this->form_data['advanced_form_data']['wt_pf_file_as'] ){
                            $product_data_str = '';
                            foreach($product_data as $product_data_single){
                                $product_data_str.= $product_data_single['label'].':'.$product_data_single['value'].',';
                            }
                            $product_data = rtrim(trim($product_data_str), ',');
                        }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_additional_variant_attributes", $product_data, $this->product, $this->form_data);
        }    
        
        
        
        /**
         * Get Date Created.
         *
         * @return mixed|void
         */
        public function date_created($catalog_attr, $product_attr, $export_columns) {
            $date_created = gmdate('Y-m-d', strtotime($this->product->get_date_created()));

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_date_created", $date_created, $this->product, $this->form_data);
        }

        /**
         * Get Date updated.
         *
         * @return mixed|void
         */
        public function date_updated($catalog_attr, $product_attr, $export_columns) {
            $date_updated = gmdate('Y-m-d', strtotime($this->product->get_date_modified()));

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_date_updated", $date_updated, $this->product, $this->form_data);
        }        
        /**
         * Get product type.
         *
         * @return mixed|void
         */
        public function categoryPath($catalog_attr, $product_attr, $export_columns) {

            $id = ( $this->product->is_type('variation') ? $this->product->get_parent_id() : $this->product->get_id() );

            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $product_categories = '';
            $term_list = get_the_terms($id, 'product_cat');

            if (is_array($term_list)) {
                $col = array_column($term_list, "term_id");
                array_multisort($col, SORT_ASC, $term_list);
                $term_list = array_column($term_list, "name");                
                $product_categories = implode(' > ', $term_list);
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_local_category", $product_categories, $this->product, $this->form_data);
        }  
        
        /**
         * Get Product weight with unit.
         *
         * @return mixed|void
         */
        public function weightnunit($catalog_attr, $product_attr, $export_columns) {
            $weight = ($this->product->get_weight()) ? $this->product->get_weight().' '.get_option('woocommerce_weight_unit') : '';
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_weight", $weight, $this->product, $this->form_data);
        }
        
        /**
         * Get Product length with unit.
         *
         * @return mixed|void
         */
        public function lengthnunit($catalog_attr, $product_attr, $export_columns) {
            $length = ($this->product->get_length()) ? $this->product->get_length().' '.get_option('woocommerce_dimension_unit') : '';
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_weight", $length, $this->product, $this->form_data);
        }     
        
        /**
         * Get Product height with unit.
         *
         * @return mixed|void
         */
        public function heightnunit($catalog_attr, $product_attr, $export_columns) {
            $height = ($this->product->get_height()) ? $this->product->get_height().' '.get_option('woocommerce_dimension_unit') : '';
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_weight", $height, $this->product, $this->form_data);
        }

        /**
         * Get Product width with unit.
         *
         * @return mixed|void
         */
        public function widthnunit($catalog_attr, $product_attr, $export_columns) {
            $width = ($this->product->get_width()) ? $this->product->get_width().' '.get_option('woocommerce_dimension_unit') : '';
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_weight", $width, $this->product, $this->form_data);
        } 
        
      /**
         * Get Product tax.
         *
         * @return mixed|void
         */
        public function vat($catalog_attr, $product_attr, $export_columns) {

            $tax_value = 0;
            // Tax rate for a product
            $tax_rates = WC_Tax::get_rates();
            if (!empty($tax_rates)) {
                // Getting the first rate percentage
                $tax_rate = reset($tax_rates);
                $tax_value = $tax_rate['rate']; // Returns the tax rate percentage
            }

            $tax_value = '::'.$tax_value;
            
            /*
             *  Tax amount for a product
              if( is_object( $this->product ) ){
              $price_excl_tax = wc_get_price_excluding_tax($this->product);
              $price_incl_tax = wc_get_price_including_tax($this->product);
              $tax_amount = $price_incl_tax - $price_excl_tax;
              $tax_value =  $tax_amount;
              }
             * 
             */

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_tax", $tax_value, $this->product, $this->form_data);
        }        
        
        /**
         * Do the evaluation.
         * 
         * @param string $str The expression.
         * @return string The evaluated value.
         */
       	protected function do_arithmetic($str)
	{

		$re = '/\[([0-9()+\-*\/. ]+)\]/m';
		$matches=array();
		$find=array();
		$replace=array();
		if(preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0))
		{

			foreach ($matches as $key => $value) 
			{
				if(is_array($value) && count($value)>1)
				{
					$synatx=$this->validate_syntax($value[1]);
					if($synatx)
					{
						$replace[]=eval('return '.$synatx.';');
					}else
					{
						$replace[]='';
					}
					$find[]=$value[0];
					unset($synatx);
				}
			}
		}

		return str_replace($find, $replace, $str);
	}
        /**
         * Apply the syntax validation on the string.
         * 
         * @param string $val Evaluation expression.
         * @return bool|string
         */
	protected function validate_syntax($val)
	{

		$open_bracket=substr_count($val, '(');
		$close_bracket=substr_count($val, ')');
		if($close_bracket!=$open_bracket)
		{
			return false; //invalid
		}

		//remove whitespaces 
		$val=str_replace(' ', '', $val);
		$re_after='/\b[\+|*|\-|\/]([^0-9\+\-\(])/m';
		$re_before='/([^0-9\+\-\)])[\+|*|\-|\/]/m';
		
		$match_after=array();
		$match_before=array();
		if(preg_match_all($re_after, $val, $match_after, PREG_SET_ORDER, 0) || preg_match_all($re_before, $val, $match_before, PREG_SET_ORDER, 0))
		{
			return false; //invalid
		}

		unset($match_after, $match_before, $re_after, $re_before);

		/* process + and - symbols */
		$val=preg_replace(array('/\+{2,}/m', '/\-{2,}/m'), array('+', '- -'), $val);

		return $val;
	}
        /**
         * Apply computation logic on product.
         * 
         * @param string $field Product field to apply the evaluation.
         * @param string $amount The evaluation expression amount.
         * @param string $mode Mode for operation - increase or decrease.
         * @param object $product_object - Product object.
         * @return string Processed value. 
         */
        public function wt_pf_product_field_calc($field, $amount, $mode, $product_object) {


                    $percent = false;

                    if (strpos($amount, '%') !== false) {
                        $amount = str_replace( '%', '', $amount);
                        $percent = true;
                    }

                    $possible_method = "get_$field";                    

                    if (method_exists($product_object, $possible_method)) {
                        $old_field_value = $product_object->$possible_method();
                    }elseif (method_exists($this, $possible_method)) {
                        $old_field_value = $this->$possible_method($product_object);
                    }
                    else{                    
                        $old_field_value = get_post_meta($product_object->get_id(), $field, true);
                    }
                    $new_field_value = '';
                    $old_field_value = ( '' === $old_field_value || null === $old_field_value ) ? 0 : $old_field_value;
                  
                    if ($mode == 'increase' ) {
                        if($percent){
                            $new_field_value = $old_field_value + ($amount / 100 ) * $old_field_value;
                        }else{                        
                            $new_field_value = $old_field_value + $amount;
                        }
                    }

                    if ($mode == 'decrease' ) {
                        if($percent){
                            $new_field_value = $old_field_value - ($amount / 100 ) * $old_field_value;
                        }else{
                            $new_field_value = $old_field_value - $amount;
                        }
                    }
                    
                    if ($mode == 'multiply' ) {
                        if($percent){
                            $new_field_value = $old_field_value * ($amount / 100 ) * $old_field_value;
                        }else{
                            $new_field_value = $old_field_value * $amount;
                        }
                    }

                    $currency_fields = apply_filters( 'wt_product_feed_currency_fields', array( 'price', 'regular_price', 'sale_price', 'price_with_tax' ) );                    
                    if(in_array($field, $currency_fields)){                        
                        $woo_currency = get_woocommerce_currency();
                        
                        if ( ( class_exists('WCML_Multi_Currency') || class_exists('WOOCS') || class_exists('WOOMULTI_CURRENCY_F') || class_exists('WC_Aelia_CurrencySwitcher') ) && !empty( $this->form_data['post_type_form_data']['wt_pf_export_post_currency'] ) ) {
                            $selected_currency = $this->form_data['post_type_form_data']['wt_pf_export_post_currency'];
                            $new_field_value = $this->get_converted_price($new_field_value, $selected_currency);
                        }

                        if ($new_field_value > 0) {
                            $new_field_value = wc_format_decimal($new_field_value, 2);
                        }                        
                        
                        $new_field_value = $new_field_value . ' ' . $woo_currency;                        
                    }
                    
                    return $new_field_value;
        }     
        
        
        /**
         * Get author name.
         *
         * @return string
         */
        public function author_name($catalog_attr, $product_attr, $export_columns) {
            
            $post = get_post($this->product->get_id());

            $author_name =  get_the_author_meta('user_login', $post->post_author);
            
            return apply_filters( "wt_feed_{$this->parent_module->module_base}_author_name", $author_name, $this->product, $this->form_data );
            
        }

        /**
         * Get Author Email.
         *
         * @return string
         */
        public function author_email($catalog_attr, $product_attr, $export_columns) {
            
            $post = get_post($this->product->get_id());

            $author_email = get_the_author_meta('user_email', $post->post_author);
            
            return apply_filters( "wt_feed_{$this->parent_module->module_base}_author_email", $author_email, $this->product, $this->form_data );
        }
        
        /**
         * Function to get inventory -- Create function for the fields, that are not builtin with product object.
         * 
         * @param object $product Product object.
         * @return type
         */
        public function get_inventory( $product ) {
                if ( $product->is_type( 'variation' ) ) {

                        $variation_obj	 = new WC_Product_variation( $product->get_id() );
                        $stock			 = $variation_obj->get_stock_quantity();
                } else {
                        $stock = $product->get_stock_quantity();
                }
                return apply_filters("wt_feed_{$this->parent_module->module_base}_product_get_inventory", $stock, $product, $this->form_data);
        }
        
        public function get_price_with_tax($product) {

            $tprice = $product->get_regular_price();
            $price = wc_get_price_including_tax($product, array('price' => $tprice));

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_price_with_tax", $price, $product, $this->form_data);
        }
        
        
        /**
         * Price without tax
         */
        public function get_price_without_tax($product){
            // Get the prices
            $price_excl_tax = wc_get_price_excluding_tax( $this->product ); // price without VAT
            
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_price_without_vat", $price_excl_tax, $this->product, $this->form_data);
        }        
        /*
         * In mapping screen this field is mentioned as current_price, price.
         */
        
        public function get_current_price($product) {

            $price = $product->get_price();  
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_current_price", $price, $product, $this->form_data);
        }        
        
        public function get_categoryPath($product) {

            $id = ( $this->product->is_type('variation') ? $this->product->get_parent_id() : $this->product->get_id() );

            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $product_categories = '';
            $term_list = get_the_terms($id, 'product_cat');

            if (is_array($term_list)) {
                $col = array_column($term_list, "term_id");
                array_multisort($col, SORT_ASC, $term_list);
                $term_list = array_column($term_list, "name");                
                $product_categories = implode(' > ', $term_list);
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_category", $product_categories, $this->product, $this->form_data);
        }
        
        public function get_eans($product) {

            $custom_ean = get_post_meta($this->product->get_id(), '_wt_feed_ean', true);
            $ean = ('' == $custom_ean) ? '' : $custom_ean;           
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_ean", $ean, $this->product, $this->form_data);
        }
        public function get_mpns($product) {

            $custom_mpn = get_post_meta($this->product->get_id(), '_wt_feed_mpn', true);
            $mpn = ('' == $custom_mpn) ? '' : $custom_mpn;           
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_mpn", $mpn, $this->product, $this->form_data);
        }
        public function get_hans($product) {

            $custom_han = get_post_meta($this->product->get_id(), '_wt_feed_han', true);
            $han = ('' == $custom_han) ? '' : $custom_han;           
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_han", $han, $this->product, $this->form_data);
        } 
        
        
        
        public function get_converted_price($price, $selected_currency) {

            $currencies = array();
            if( class_exists( 'WC_Aelia_CurrencySwitcher' ) && $price > 0 ){
                $settings_controller = WC_Aelia_CurrencySwitcher::settings();
                $aelia_currencies = $settings_controller->get_exchange_rates();
                foreach ($aelia_currencies as $currency_code => $currency_rate) {
                        $currencies[$currency_code]['rate'] = $currency_rate;
                }
                $from_frontend_cur = isset($_COOKIE['aelia_cs_selected_currency']) ? $_COOKIE['aelia_cs_selected_currency'] : get_woocommerce_currency();					
                $to_base_currency = get_option('woocommerce_currency');

                $price = apply_filters('wc_aelia_cs_convert', $price, $from_frontend_cur, $to_base_currency);
            }
            if( $selected_currency !== get_option('woocommerce_currency') && $price > 0 ) {
                if ( class_exists('WOOMULTI_CURRENCY_F') ) {
                    $wcf_settings = WOOMULTI_CURRENCY_F_Data::get_ins();
                    $currencies = $wcf_settings->get_list_currencies();
                }                  
                if( class_exists('WOOCS') ){
                    global $WOOCS;
                    $currencies = $WOOCS->get_currencies();

                }
                if( class_exists('WCML_Multi_Currency') ){
                    $wcml_mc = new WCML_Multi_Currency();
                    $currencies = $wcml_mc->get_currencies(true);
                } 

                $woo_currencies = get_woocommerce_currencies();

                if (!empty($woo_currencies[$selected_currency]) && !empty($currencies[$selected_currency])) {
                    $price = round( $price * $currencies[$selected_currency]['rate'], 2 );
                }               
            }
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_converted_price", $price, $this->product, $currencies, $selected_currency, $this->form_data);

        }    
        

        public static function clean_string($string) {
            $string = do_shortcode($string);
            $string = str_replace(array('&amp%3B', '&amp;'), '&', $string);
            $string = str_replace(array("\r", '&nbsp;', "\t"), ' ', $string);
            $string = wp_strip_all_tags($string, false); // true == remove line breaks
            return $string;
        }
        public static function get_store_name() {

            $url = get_bloginfo('name');
            return ( $url) ? ( $url ) : 'My Store';
        }

        public function get_brand($product) {

            $id = ( $product->is_type('variation') ? $product->get_parent_id() : $product->get_id() );
            $custom_brand = get_post_meta($product->get_id(), '_wt_feed_brand', true);

            if (!$custom_brand) {
                $custom_brand = get_post_meta($product->get_id(), '_wt_facebook_brand', true);
            }

            if (!$custom_brand) {

                $brand = get_the_term_list($id, 'product_brand', '', ', ');

                $has_brand = true;
                if (is_wp_error($brand) || false === $brand) {
                    $has_brand = false;
                }

                if (!$has_brand && is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php')) {
                    $brand = get_the_term_list($id, 'pwb-brand', '', ', ');
                }
                if (!$has_brand && (is_plugin_active('yith-woocommerce-brands-add-on/init.php') || is_plugin_active('yith-woocommerce-brands-add-on-premium/init.php'))) {
                    $brand = get_the_term_list($id, 'yith_product_brand', '', ', ');
                }   

                $string = is_wp_error($brand) || !$brand ? wp_strip_all_tags(self::get_store_name()) : self::clean_string($brand);
                $length = 100;
                if (extension_loaded('mbstring')) {

                    if (mb_strlen($string, 'UTF-8') <= $length) {
                        return apply_filters('wt_feed_advanced_filter_product_brand', $string, $product);
                    }

                    $length -= mb_strlen('...', 'UTF-8');

                    $brand_string = mb_substr($string, 0, $length, 'UTF-8') . '...';
                    return apply_filters('wt_feed_advanced_filter_product_brand', $brand_string, $this->product);
                } else {

                    $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                    $string = filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);

                    if (strlen($string) <= $length) {
                        return apply_filters('wt_feed_advanced_filter_product_brand', $string, $product);
                    }

                    $length -= strlen('...');

                    $brand_string = substr($string, 0, $length) . '...';
                    return apply_filters('wt_feed_advanced_filter_product_brand', $brand_string, $product);
                }
            } else {
                return apply_filters('wt_feed_advanced_filter_product_brand', $custom_brand, $product);
            }
        }

    }

}

if (is_plugin_active('wc-dynamic-pricing-and-discounts/wc-dynamic-pricing-and-discounts.php')) {
        //RightPress dynamic pricing support.
        add_filter( 'rightpress_product_price_shop_change_prices_in_backend', '__return_true', 9999 );
        add_filter( 'rightpress_product_price_shop_change_prices_before_cart_is_loaded', '__return_true', 9999 );
}

// Custom taxonomy filter for get product query

add_filter('woocommerce_product_data_store_cpt_get_products_query', 'wt_exclude_product_taxonomy_query', 10, 2);
if (!function_exists('wt_exclude_product_taxonomy_query')) {

    function wt_exclude_product_taxonomy_query($query, $query_vars) {
        if (!empty($query_vars['exclude_category'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $query_vars['exclude_category'],
                'operator' => 'NOT IN',
            );
        }
        if (!empty($query_vars['exclude_discarded'])) {
            $query['meta_query'][] = array(
                array(
                    'key' => '_wt_feed_discard',
                    'compare' => 'NOT EXISTS' // this should exclude all exclude from feed checked products
                ),
            );
        }

        if (!empty($query_vars['exclude_brands'])) {
            if ( defined('WC_VERSION') && version_compare(WC_VERSION, '9.6', '>')) {
                $query['tax_query'][] = array(
                    'taxonomy' => 'product_brand',
                    'field' => 'slug',
                    'terms' => $query_vars['exclude_brands'],
                    'operator' => 'NOT IN',
                );
            } else {
                $query['tax_query'][] = array(
                    'taxonomy' => 'pwb-brand',
                    'field' => 'slug',
                    'terms' => $query_vars['exclude_brands'],
                    'operator' => 'NOT IN',
                );
            }
        }
        if (!empty($query_vars['include_brands'])) {

            if ( defined('WC_VERSION') && version_compare(WC_VERSION, '9.6', '>')) {
                $query['tax_query'][] = array(
                    'taxonomy' => 'product_brand',
                    'field' => 'slug',
                    'terms' => $query_vars['include_brands'],
                    'operator' => 'IN',
                );
            } else {
                $query['tax_query'][] = array(
                    'taxonomy' => 'pwb-brand',
                    'field' => 'slug',
                    'terms' => $query_vars['include_brands'],
                    'operator' => 'IN',
                );
            }
         }

        if (!empty($query_vars['exclude_tag'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => $query_vars['exclude_tag'],
                'operator' => 'NOT IN',
            );
        }
        return $query;
    }

}
