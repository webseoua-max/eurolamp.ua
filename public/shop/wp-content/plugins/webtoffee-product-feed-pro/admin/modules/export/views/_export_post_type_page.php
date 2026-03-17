<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt_pf_export_main">
    <p><?php echo $step_info['description']; ?></p>
    <div class="wt_pf_warn wt_pf_post_type_wrn" style="display:none;">
        <?php _e('Please select a post type', 'webtoffee-product-feed-pro'); ?>
    </div>
    <form class="wt_pf_feed_filter_form">
    <table class="form-table wt-pfd-form-table">
        <tr class="wt-pfd-settings-header-cap">
            <th><label style="font-size: 16px;"><?php _e('Configuration', 'webtoffee-product-feed-pro'); ?></label></th>
        </tr>
        <tr><td></td></tr>
        
        <tr>
            <th><label><?php _e('Country', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>

                <?php
                global $woocommerce;
                if (class_exists('WC_Countries')) {
                    $countries_obj = new WC_Countries();
                    $countries = $countries_obj->__get('countries');
                } else {
                    $countries = array();
                }
                ?>


                <select name="wt_pf_export_catalog_country" id="wt_pf_export_catalog_country">
                    <?php
                    foreach ($countries as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo ($item_country == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose the country for which you want to generate the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>

        <tr>
            <th><label><?php _e('Channel', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>
                <select name="wt_pf_export_post_type">
                    <?php
                    foreach ($post_types as $key => $value) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo ($item_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose the sales channel for which you\'d like to generate the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>        
        
        <tr>
            <th><label><?php _e('File name', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>
                <input required type="text" name="wt_pf_export_catalog_name" value="<?php echo $item_filename; ?>" id="wt_pf_export_catalog_name"/>
                <span class="wt-pf_form_help"><?php esc_html_e('Enter a unique file name.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
        </tr>        

        <?php 
        $multi_lingual = false;
        $langs = array('' => array('native_name' => _x('All', 'setting option', 'webtoffee-product-feed-pro')));     
        
        // WPML
        if (apply_filters('wpml_setting', false, 'setup_complete')) { 
            
            $wpml_site_languages = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');
            $langs = $langs + $wpml_site_languages;
            $multi_lingual = true;
            
            
        }    
        // Translatepress
        if ( is_plugin_active( 'translatepress-multilingual/index.php' ) ) {
                if ( class_exists( 'TRP_Translate_Press' ) ) {
                        $tr_press_languages = trp_get_languages( 'default' );
                        if ( ! empty( $tr_press_languages ) ) {
                                foreach ( $tr_press_languages as $key => $value ) {
                                        $trp_site_languages[ $key ] = array('native_name' => $value);   ;
                                }
                             $langs = $langs + $trp_site_languages;   
                             $multi_lingual = true;
                        }
                        
                    }
        }

        // when polylang plugin is activated
        if ( defined( 'POLYLANG_BASENAME' ) || function_exists( 'PLL' ) ) {
            $pl_langs = PLL()->model->get_languages_list();
            foreach ($pl_langs as $key => $value) {
                $langs[$value->slug] = array('native_name' => $value->name);
            }
            $multi_lingual = true;
        }
        ?>
        <?php if($multi_lingual) : ?>
        <tr>
            <th><label><?php _e('Language', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>
                <select name="wt_pf_export_post_language" id="wt_pf_export_post_language">
                    <?php       
                            foreach ($langs as $key => $value) {
                        ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_lang == $key ? 'selected' : ''); ?>><?php echo esc_html($value['native_name']); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose feed language', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <?php endif; ?>


        <?php
        $multi_currency = false;        
        
        if ( class_exists('WOOMULTI_CURRENCY_F') ) {
            $currency_list = [];
            $wcf_settings = WOOMULTI_CURRENCY_F_Data::get_ins();
            $wcf_currencies = $wcf_settings->get_list_currencies();
            foreach ($wcf_currencies as $currency_key => $currency_name ){
                $currency_list[$currency_key] = $currency_key;
            }
            $multi_currency = true;
        }        
        
        if ( class_exists('WOOCS') ) {
            global $WOOCS;
            $currency_list = [];
            $woocs_currency_list = $WOOCS->get_currencies();
            foreach ($woocs_currency_list as $currency_key => $currency_name ){
                $currency_list[$currency_key] = $currency_name;
            }
            $multi_currency = true;
        }
        
        if ( class_exists('WCML_Multi_Currency') && class_exists('woocommerce') ) {
            $wcml_mc = new WCML_Multi_Currency();
            $currency_list = $wcml_mc->get_currencies(true);
            $multi_currency = true;
        }
        
        if( class_exists('WC_Aelia_CurrencySwitcher') ){
		$currency_list = [];
		$settings_controller = WC_Aelia_CurrencySwitcher::settings();
		$enabled_currencies = $settings_controller->get_enabled_currencies(); 
                foreach ($enabled_currencies as $currency_key => $currency_name ){
                    $currency_list[$currency_name] = $currency_name;
                }                
                $multi_currency = true;
        }
                        
        if($multi_currency){
            ?>
            <tr>
                <th><label><?php _e('Currency', 'webtoffee-product-feed-pro'); ?></label></th>
                <td>
                    <select name="wt_pf_export_post_currency" id="wt_pf_export_post_currency">
                        <?php
                        foreach ($currency_list as $key => $value) {
                            ?>
                            <option value="<?php echo esc_html($key) ?>" <?php echo ($item_currency == $key ? 'selected' : ''); ?>><?php echo esc_html($key); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="wt-pf_form_help"><?php esc_html_e('Choose currency', 'webtoffee-product-feed-pro'); ?></span>
                </td>
                <td></td>
            </tr>

        <?php } ?>
            
        <?php
        $vendor_active  = false;
        if( is_plugin_active( 'wc-vendors/class-wc-vendors.php') ){
            $args = array(
                            'meta_query' => array(
                                array(
                                    'key' => '_wcv_vendor_status',
                                    'value' => 'active',
                                    'compare' => '='
                                )
                            )
                        );
            $users = get_users( $args );
            $vendor_active = 1;
        }
        
        // If dokan active
        if (is_plugin_active('dokan-lite/dokan.php')) {
            
            $args = array(
                'meta_query' => array(
                    array(
                        'key' => 'dokan_enable_selling',
                        'value' => 'yes',
                        'compare' => '='
                    )
                )
            );
            $users = get_users( $args );
            $vendor_active = 1;
        }
            ?>
            <?php if($vendor_active){ ?>
            <tr>
                <th><label><?php _e('Vendor', 'webtoffee-product-feed-pro'); ?></label></th>
                <td>
                    <select name="wt_pf_export_post_author" id="wt_pf_export_post_author" multiple="true" class="wc-enhanced-select">
                        <?php
                        $item_author = is_scalar($item_author) ? explode(',', $item_author) : $item_author;
                        if(is_array($item_author) && 1 === count($item_author) && '' === $item_author[0] ){
                            $item_author = array();
                        }
                        
                        ?>
                        <option value="" <?php echo ( empty($item_author) ) ? 'selected' : ''; ?>> <?php _e('All', 'webtoffee-product-feed-pro'); ?></option>
                        <?php    
                                                
                        foreach ($users as $user) {
                            ?>
                            <option value="<?php echo esc_html($user->ID) ?>" <?php echo ( in_array($user->ID , $item_author) ) ? 'selected' : ''; ?>><?php echo esc_html($user->display_name); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <span class="wt-pf_form_help"><?php esc_html_e('Choose a vendor', 'webtoffee-product-feed-pro'); ?></span>
                </td>
                <td></td>
            </tr>
        <?php } ?>            
        <tr class="wt-pfd-settings-header-cap">
            <th><label style="font-size: 16px;"><?php _e('Automation', 'webtoffee-product-feed-pro'); ?></label></th>
        </tr>
        <tr><td></td></tr>            
        <tr>
            <th><label><?php _e('Auto-refresh interval', 'webtoffee-product-feed-pro'); ?>
                    <span class="dashicons dashicons-editor-help wt-pf-tips" 
                          data-wt-pf-tip="
                          <span class='wt_pf_tooltip_span'><?php echo sprintf(__(' Choose a suitable interval for refreshing the feed. Select %s No Refresh %s to disable the feed\'s auto-refresh.', 'webtoffee-product-feed-pro'), '<b>', '</b>'); ?></span><br />
                          ">			
                    </span>
                </label>
            </th>
            <td>
                <?php
                $regenerate_intervals = apply_filters('wt_pf_catalog_regenerate_interval', array(
                    'hourly' => __('Hourly', 'webtoffee-product-feed-pro'),
                    'daily' => __('Daily', 'webtoffee-product-feed-pro'),
                    'weekly' => __('Weekly', 'webtoffee-product-feed-pro'),
                    'monthly' => __('Monthly', 'webtoffee-product-feed-pro'),
                    '12hour' => __('Every 12 hours', 'webtoffee-product-feed-pro'),
                    '6hour' => __('Every 6 hours', 'webtoffee-product-feed-pro'),
                    '30minute' => __('Every 30 minutes', 'webtoffee-product-feed-pro'),
                    'manual' => __('No Refresh', 'webtoffee-product-feed-pro'),
                ));
                ?>
                <select name="wt_pf_export_catalog_interval" id="wt_pf_export_catalog_interval">
                    <?php
                    foreach ($regenerate_intervals as $key => $value) {
                        ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_gen_interval == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>                
            </td>
            <td></td>
        </tr>
        <tr class="wt_feed_schedule_options wt_feed_schedule_options_days" style="display:none;">
            <th><label style="margin-left:10px;"><?php _e('Choose day', 'webtoffee-product-feed-pro'); ?>								
                </label></th>
            <td>                            
                <select name="wt_pf_schedule_cron_day" id="wt_pf_schedule_cron_day" >
                    <?php
                    $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                    foreach ($days as $day) {
                        $day_vl = strtolower($day);
                        ?>
                        <option value="<?php echo esc_attr($day_vl); ?>" <?php echo ($item_gen_cron_day == $day_vl ? 'selected' : ''); ?> ><?php esc_html_e($day, 'webtoffee-product-feed-pro'); ?></option>                                        
                        <?php
                    }
                    ?>
                </select>				
            </td>
            <td></td>
        </tr> 
        <tr class="wt_feed_schedule_options wt_feed_schedule_options_dayofmonth" style="display:none;">
            <th><label style="margin-left:10px;"><?php _e('Day of the Month', 'webtoffee-product-feed-pro'); ?>								
                </label></th>
            <td>                                    			
                <select name="wt_pf_cron_interval_date" id="wt_pf_cron_interval_date">
                    <?php
                    for ($i = 1; $i <= 28; $i++) {
                        ?>
                        <option value="<?php echo $i; ?>" <?php echo ($item_gen_cron_date == $i ? 'selected' : ''); ?>><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                    <option value="last_day"><?php _e('Last day', 'webtoffee-product-feed-pro'); ?></option>
                </select>				
            </td>
            <td></td>
        </tr>                
        <tr class="wt_feed_schedule_options wt_feed_schedule_options_time" style="display:none;">
            <th><label style="margin-left:10px;"><?php _e('Time', 'webtoffee-product-feed-pro'); ?>								
                </label></th>
            <td>
                <div class="wt_iew_schedule_now_interval_sub_block wt_iew_schedule_starttime_block">                            
                    <div style="float:left;margin-right:5px;">
                        <input  type="number" step="1" min="1" max="12" name="wt_pf_cron_start_val" id="wt_pf_cron_start_val" value="<?php echo $item_gen_cron_start_val; ?>" style="width:75px;padding:5px;" />
                        <span class="wt-iew_form_help" style="display:block; margin-top: 1px"><?php esc_html_e('Hour', 'webtoffee-product-feed-pro'); ?></span>
                    </div>
                    <div style="float:left;">                                    
                        <input type="number" step="1" min="0" max="59" name="wt_pf_cron_start_val_min" id="wt_pf_cron_start_val_min" value="<?php echo $item_gen_cron_start_val_min; ?>" onchange="if (parseInt(this.value, 10) < 10)
                                                this.value = '0' + this.value;" style="width:75px;padding:5px;" />
                        <span class="wt-iew_form_help" style="display:block;  margin-top: 1px"><?php esc_html_e('Minute', 'webtoffee-product-feed-pro'); ?></span>
                    </div>
                    <div style="float:left;padding-left:5px;">
                        <select name="wt_pf_cron_start_ampm_val" id="wt_pf_cron_start_ampm_val" style="width:75px;">
                            <?php
                            $am_pm = array('AM', 'PM');
                            foreach ($am_pm as $apvl) {
                                ?>
                                <option value="<?php echo esc_html(strtolower($apvl)); ?>" <?php echo ($item_gen_cron_ampm == strtolower($apvl) ? 'selected' : ''); ?> ><?php echo esc_html($apvl); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </td>
            <td></td>
        </tr>
        <tr class="wt_feed_schedule_options_cron_type" style="display:none;">
            <th><label><?php _e('Cron Type', 'webtoffee-product-feed-pro'); ?>
                    <span class="dashicons wtdashicons-editor-help wt-pf-tips" 
                          data-wt-pf-tip="
                          <span class='wt_pf_tooltip_span'><?php echo sprintf(__(' Choose a suitable interval for refreshing the feed. Choose %s Manual %s to disable auto-refresh for the feed.'), '<b>', '</b>'); ?></span><br />
                          ">			
                    </span>

                </label></th>
            <td>
                <?php
                $cron_types = apply_filters('wt_pf_catalog_cron_type', array(
                    'wordpress_cron' => __('WordPress cron', 'webtoffee-product-feed-pro'),
                    'server_cron' => __('Server cron', 'webtoffee-product-feed-pro'),
                ));
                ?>
                <select name="wt_pf_export_catalog_cron_type" id="wt_pf_export_catalog_cron_type">
                <?php
                foreach ($cron_types as $key => $value) {
                    ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_gen_cron_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose a cron type for the feed refresh', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <tr class="wt-pfd-settings-header-cap">
            <th>
                <label style="font-size: 16px;">
                    <span class="wt-feed-simple-filter wt-filter-active" style="margin-right: 20px; padding:10px; border-radius: 15px;">
                <?php _e('Filtering', 'webtoffee-product-feed-pro'); ?></span>
                    <span class="wt-feed-advanced-filter wt-filter-inactive" style="margin-right: 20px; padding:10px; border-radius: 15px;">
                        <?php _e('Advanced Filtering', 'webtoffee-product-feed-pro'); ?>
                    </span>
                </label>
            </th>           
        </tr>
        <tr>
            <td></td>
        </tr>          
        <table class="wt-feed-basic-filter-section form-table"> 
        <tr class="wt-feed-filter-section">
            <th><label><?php esc_html_e('Categories', 'webtoffee-product-feed-pro'); ?></label>
            </th>
            <td>
                <?php
                $cat_filter_type = array(
                    'include_cat' => __('Include', 'webtoffee-product-feed-pro'),
                    'exclude_cat' => __('Exclude', 'webtoffee-product-feed-pro'),
                );
                ?>
                <select name="wt_pf_export_cat_filter_type" id="wt_pf_export_cat_filter_type">
                <?php
                foreach ($cat_filter_type as $key => $value) {
                    ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_cat_filter_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose a category filter', 'webtoffee-product-feed-pro'); ?></span>
            </td>                
            <td class="wt-feed-filter-section-td" style="padding-top: 20px;">
                <select name="wt_pf_inc_exc_category" id="wt_pf_inc_exc_category" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Select product category&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                <?php
                $product_categories = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_product_categories_sluged();
                foreach ($product_categories as $category_id => $category_name) {
                    ?>
                     <option value="<?php echo $category_id; ?>" <?php echo (in_array($category_id, $inc_exc_cat) ? 'selected' : ''); ?> ><?php echo esc_attr($category_name); ?></option>								
                    <?php
                }
                ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more categories.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
        </tr>
        <tr class="wt-feed-filter-section">
            <th><label><?php esc_html_e('Tags', 'webtoffee-product-feed-pro'); ?></label>
            </th>
            <td>
                <?php
                $tag_filter_type = array(
                    'include_tag' => __('Include', 'webtoffee-product-feed-pro'),
                    'exclude_tag' => __('Exclude', 'webtoffee-product-feed-pro'),
                );
                ?>
                <select name="wt_pf_export_tag_filter_type" id="wt_pf_export_tag_filter_type">
                <?php
                foreach ($tag_filter_type as $key => $value) {
                    ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_tag_filter_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose a tag filter', 'webtoffee-product-feed-pro'); ?></span>
            </td>                
            <td class="wt-feed-filter-section-td" style="padding-top: 20px;">
                <select name="wt_pf_inc_exc_tag" id="wt_pf_inc_exc_tag" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Select product tag&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                <?php
                $product_tags = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_product_tags();
                foreach ($product_tags as $tag_id => $tag_name) {
                    ?>
                    <option value="<?php echo $tag_id; ?>" <?php echo (in_array($tag_id, $inc_exc_tag) ? 'selected' : ''); ?> ><?php echo esc_attr($tag_name); ?></option>								
                    <?php
                }
                ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more tags.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
        </tr>        
        <?php 
        $wc_has_brands = false;
        if ( defined('WC_VERSION') && version_compare(WC_VERSION, '9.6', '>')) {
            $wc_has_brands = true;
        }
        if(is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php') || $wc_has_brands ){ ?>
        
        <tr class="wt-feed-filter-section">
            <th><label><?php esc_html_e('Brands', 'webtoffee-product-feed-pro'); ?></label>
            </th>
            <td>
                <?php
                $brand_filter_type = array(
                    'include_brand' => __('Include', 'webtoffee-product-feed-pro'),
                    'exclude_brand' => __('Exclude', 'webtoffee-product-feed-pro'),
                );
                ?>
                <select name="wt_pf_export_brand_filter_type" id="wt_pf_export_brand_filter_type">
                <?php
                foreach ($brand_filter_type as $key => $value) {
                    ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($item_brand_filter_type == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose a brand filter', 'webtoffee-product-feed-pro'); ?></span>
            </td>                
            <td class="wt-feed-filter-section-td" style="padding-top: 20px;">
                <select name="wt_pf_inc_exc_brand" id="wt_pf_inc_exc_brand" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Select product brand&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                <?php
                $product_brands = $wc_has_brands ? Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_wc_product_brands_sluged() : Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_product_brands_sluged();
                foreach ($product_brands as $product_brand_id => $product_brand_name) {
                    ?>
                <option value="<?php echo $product_brand_id; ?>" <?php echo (in_array($product_brand_id, $inc_exc_brand) ? 'selected' : ''); ?> ><?php echo esc_attr($product_brand_name); ?></option>								
                    <?php
                }
                ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more brands.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <th><label><?php esc_html_e('Exclude products', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>
                <select name="wt_pf_exclude_products" id="wt_pf_exclude_products" class="wc-product-search" multiple="multiple" data-placeholder ="<?php echo __('Search for a product &hellip;', 'webtoffee-product-feed-pro'); ?>" >
<?php
foreach ($excl_prods as $single_vl) {
    $single_vl = (int) $single_vl;
    if ($single_vl > 0) {
        $product = wc_get_product($single_vl);
        ?>
                            <option value="<?php echo esc_html($single_vl); ?>" selected><?php echo $product->get_title(); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more products to be excluded from the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <tr>
            <th><label><?php esc_html_e('Exclude out-of-stock product', 'webtoffee-product-feed-pro'); ?></label></th>
            <td>
                <input type="checkbox" name="wt_pf_exclude_outofstock" id="wt_pf_exclude_outofstock" <?php echo (1 == $item_outofstock) ? ' checked="checked"' : ''; ?> >
                <span class="wt-pf_form_help"><?php esc_html_e('Enable to exclude out of stock products from the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <tr>
            <th><label><?php esc_html_e('Product Variations', 'webtoffee-product-feed-pro'); ?>
                    <span class="dashicons dashicons-editor-help wt-pf-tips" 
                          data-wt-pf-tip="
                          <span class='wt_pf_tooltip_span'><?php esc_html_e('Select the product variation that should be included in the feed.', 'webtoffee-product-feed-pro'); ?></span><br />">			
                    </span>
                </label></th>
            <td>
                <?php
                $include_variations_type = apply_filters('wt_pf_catalog_include_variations_type', array(
                    '' => __('All variations', 'webtoffee-product-feed-pro'),                    
                    'default' => __('Default variation', 'webtoffee-product-feed-pro'),
                    'lowest' => __('Lowest priced variation', 'webtoffee-product-feed-pro'),
                    'highest' => __('Highest priced variation', 'webtoffee-product-feed-pro'),
                ));
                ?>
                <select name="wt_pf_include_variations_type" id="wt_pf_include_variations_type">
                    <?php
                    foreach ($include_variations_type as $key => $value) {
                        ?>
                        <option value="<?php echo esc_html($key); ?>" <?php echo ($variations_type_selected == $key ? 'selected' : ''); ?>><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Include selected product variations in the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <tr class="wt_feed_parent_options wt_feed_parent_options_qty" style="display:none;">
            <th><label style="margin-left:10px;"><?php _e('Quantity of', 'webtoffee-product-feed-pro'); ?>								
                </label></th>
            <td>                            
                <select name="wt_pf_parent_qty" id="wt_pf_parent_qty" >
                    <?php
                    $qty_options = array(
                        '' => __( 'Selected Variation Quantity ' ),
                        'sumof_variation_qty' => __( 'Sum of Variation Quantity' )
                        );
                    foreach ($qty_options as $key => $value) {
                        ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php echo ($wt_pf_parent_qty == $key ? 'selected' : ''); ?> ><?php esc_html_e($value, 'webtoffee-product-feed-pro'); ?></option>                                        
                        <?php
                    }
                    ?>
                </select>				
            </td>
            <td></td>
        </tr> 
        <tr>
            <th><label><?php esc_html_e('Product types', 'webtoffee-product-feed-pro'); ?>
                </label></th>
            <td>
                <select name="wt_pf_product_types" id="wt_pf_product_types" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Select product type&hellip;', 'webtoffee-product-feed-pro'); ?>" >
<?php
$product_types = function_exists('wc_get_product_types') ? wc_get_product_types() : array();
if (!empty($product_types)) {
    $product_types['variation'] = __('Variations', 'webtoffee-product-feed-pro ');
}
foreach ($product_types as $key => $product_type) {
    ?>
                        <option value="<?php echo $key; ?>" <?php echo (in_array($key, $item_product_type) ? 'selected' : ''); ?> ><?php echo esc_attr($product_type); ?></option>								
                        <?php
                    }
                    ?>
                </select>
                <span class="wt-pf_form_help"><?php esc_html_e('Choose product types that need to be included in the feed.', 'webtoffee-product-feed-pro'); ?></span>
            </td>
            <td></td>
        </tr>
        <table>
        <?php include dirname(plugin_dir_path(__FILE__)).'/views/_advanced_filter_options.php'; ?>
    </table>
    </form>
    <br/>
</div>