<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @package    Pixel_Manager_For_Woocommerce/admin/partials
 * Pixel Tag Manager For Woocommerce
 */

if(!defined('ABSPATH')){
	exit; // Exit if accessed directly
}
if(!class_exists('PMW_Pixels')){
	class PMW_Pixels extends PMW_AdminHelper{
    protected $is_pro_version;
    protected $api_store;
    protected $plan_name;
    public function __construct( ) {
      $this->api_store = (object)$this->get_pmw_api_store();
      $this->is_pro_version = $this->pmw_is_pro_version($this->api_store);
      $this->plan_name = $this->get_plan_name($this->api_store);
      //$this->req_int();
      $this->load_html();
    }
    public function req_int(){
    }
    protected function load_html(){
      $this->page_html();
      $this->page_js();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      /**
       * Tabs
       **/
      ?>
      <div class="pmw_side_menu">
        <ul class="pmw_side_menu_list">
          <li class="active" data-id="sec-pmw-pixels"><i class="pmw_icon pmw_icon-setting"></i></li>
          <li data-id="sec-pmw-pixels-integration"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/integration.png"); ?>" alt="integration"></li>
          <li data-id="sec-pmw-pixels-cookies"><i class="pmw_icon pmw_icon-cookies"></i></li>
        </ul>
      </div>
      <?php
      $current_user = wp_get_current_user();
      do_action("pmw_before_pixel_settings", $this->is_pro_version);
      $pixels_option = $this->get_pmw_pixels_option();
      $email_id = isset($pixels_option['user']['email_id'])?$pixels_option['user']['email_id']:$current_user->user_email;
      //Google
      $gtm_container_load_mode = isset($pixels_option['gtm_container']['load_mode']) ? $pixels_option['gtm_container']['load_mode'] : "default_ptm";
      $gtm_container_custom_id = isset($pixels_option['gtm_container']['custom_container_id']) ? $pixels_option['gtm_container']['custom_container_id'] : "";
      
      $gtm_container_id = "GTM-P3DXNCNZ";
      if($gtm_container_load_mode == "use_own" && $gtm_container_custom_id != ""){
        $gtm_container_id = $gtm_container_custom_id;
      }else if($gtm_container_load_mode == "default_ptm"){
        if(isset($pixels_option["axeptio"]["project_id"]) && isset($pixels_option["axeptio"]["is_enable"]) && $pixels_option["axeptio"]["project_id"] != "" && $pixels_option["axeptio"]["is_enable"]){
          $gtm_container_id = "GTM-58V46ZS3";
        }
      }

      $google_tag_for = isset($pixels_option['google_tag']['for']) ? $pixels_option['google_tag']['for'] : '';
      $google_tag_id = isset($pixels_option['google_tag']['id']) ? $pixels_option['google_tag']['id'] : "";
      $google_tag_is_enable = isset($pixels_option['google_tag']['is_enable']) ? $pixels_option['google_tag']['is_enable'] : "";
      
      $google_analytics_4_pixel_id = isset($pixels_option['google_analytics_4_pixel']['pixel_id'])?$pixels_option['google_analytics_4_pixel']['pixel_id']:"";
      $google_analytics_4_pixel_is_enable = isset($pixels_option['google_analytics_4_pixel']['is_enable'])?$pixels_option['google_analytics_4_pixel']['is_enable']:"";

      $generate_lead_from = isset($pixels_option['generate_lead_from'])?$pixels_option['generate_lead_from']:"";

      $google_ads_conversion_id = isset($pixels_option['google_ads_conversion']['id'])?$pixels_option['google_ads_conversion']['id']:"";
      $google_ads_conversion_label = isset($pixels_option['google_ads_conversion']['label'])?$pixels_option['google_ads_conversion']['label']:"";
      $google_ads_conversion_is_enable = isset($pixels_option['google_ads_conversion']['is_enable'])?$pixels_option['google_ads_conversion']['is_enable']:"";

      $google_ads_enhanced_conversion_is_enable = isset($pixels_option['google_ads_enhanced_conversion']['is_enable'])?$pixels_option['google_ads_enhanced_conversion']['is_enable']:"";
      $google_ads_dynamic_remarketing_is_enable = isset($pixels_option['google_ads_dynamic_remarketing']['is_enable'])?$pixels_option['google_ads_dynamic_remarketing']['is_enable']:"";

      // Google Ads Form Conversion
      $google_ads_form_conversion_id = isset($pixels_option['google_ads_form_conversion']['id']) ? $pixels_option['google_ads_form_conversion']['id'] : "";
      $google_ads_form_conversion_label = isset($pixels_option['google_ads_form_conversion']['label']) ? $pixels_option['google_ads_form_conversion']['label'] : "";
      $google_ads_form_conversion_is_enable = isset($pixels_option['google_ads_form_conversion']['is_enable']) ? $pixels_option['google_ads_form_conversion']['is_enable'] : "";
      $google_ads_form_conversion_selector = isset($pixels_option['google_ads_form_conversion']['selector']) ? $pixels_option['google_ads_form_conversion']['selector'] : "";

      //Pixels
      $facebook_pixel_id = isset($pixels_option['facebook_pixel']['pixel_id'])?$pixels_option['facebook_pixel']['pixel_id']:"";
      $facebook_pixel_is_enable = isset($pixels_option['facebook_pixel']['is_enable'])?$pixels_option['facebook_pixel']['is_enable']:"";

      $fb_conversion_api_token = isset($pixels_option['fb_conversion_api']['api_token'])?$pixels_option['fb_conversion_api']['api_token']:"";
      $fb_conversion_api_is_enable = isset($pixels_option['fb_conversion_api']['is_enable'])?$pixels_option['fb_conversion_api']['is_enable']:"";
      $test_event_code = isset($pixels_option['fb_conversion_api']['test_event_code'])?$pixels_option['fb_conversion_api']['test_event_code']:"";

      $pinterest_pixel_id = isset($pixels_option['pinterest_pixel']['pixel_id'])?$pixels_option['pinterest_pixel']['pixel_id']:"";
      $pinterest_pixel_is_enable = isset($pixels_option['pinterest_pixel']['is_enable'])?$pixels_option['pinterest_pixel']['is_enable']:"";

      $snapchat_pixel_id = isset($pixels_option['snapchat_pixel']['pixel_id'])?$pixels_option['snapchat_pixel']['pixel_id']:"";
      $snapchat_pixel_is_enable = isset($pixels_option['snapchat_pixel']['is_enable'])?$pixels_option['snapchat_pixel']['is_enable']:"";

      $bing_pixel_id = isset($pixels_option['bing_pixel']['pixel_id'])?$pixels_option['bing_pixel']['pixel_id']:"";
      $bing_pixel_is_enable = isset($pixels_option['bing_pixel']['is_enable'])?$pixels_option['bing_pixel']['is_enable']:"";

      $twitter_pixel_id = isset($pixels_option['twitter_pixel']['pixel_id'])?$pixels_option['twitter_pixel']['pixel_id']:"";
      $twitter_pixel_is_enable = isset($pixels_option['twitter_pixel']['is_enable'])?$pixels_option['twitter_pixel']['is_enable']:"";

      $tiktok_pixel_id = isset($pixels_option['tiktok_pixel']['pixel_id'])?$pixels_option['tiktok_pixel']['pixel_id']:"";
      $tiktok_pixel_is_enable = isset($pixels_option['tiktok_pixel']['is_enable'])?$pixels_option['tiktok_pixel']['is_enable']:"";
      
      // TikTok Conversion API
      $tiktok_conversion_api_token = isset($pixels_option['tiktok_conversion_api']['api_token']) ? $pixels_option['tiktok_conversion_api']['api_token'] : "";
      $tiktok_conversion_api_is_enable = isset($pixels_option['tiktok_conversion_api']['is_enable']) ? $pixels_option['tiktok_conversion_api']['is_enable'] : "";

      // Pinterest Conversion API
      $pinterest_conversion_api_token = isset($pixels_option['pinterest_conversion_api']['api_token']) ? $pixels_option['pinterest_conversion_api']['api_token'] : "";
      $pinterest_conversion_api_ad_account_id = isset($pixels_option['pinterest_conversion_api']['ad_account_id']) ? $pixels_option['pinterest_conversion_api']['ad_account_id'] : "";
      $pinterest_conversion_api_is_enable = isset($pixels_option['pinterest_conversion_api']['is_enable']) ? $pixels_option['pinterest_conversion_api']['is_enable'] : "";
      
      // Twitter Conversion API - Commented out as requested
      // $twitter_conversion_api_token = isset($pixels_option['twitter_conversion_api']['api_token']) ? $pixels_option['twitter_conversion_api']['api_token'] : "";
      // $twitter_conversion_api_is_enable = isset($pixels_option['twitter_conversion_api']['is_enable']) ? $pixels_option['twitter_conversion_api']['is_enable'] : "";
      
      // Snapchat Conversion API - Commented out as requested
      // $snapchat_conversion_api_token = isset($pixels_option['snapchat_conversion_api']['api_token']) ? $pixels_option['snapchat_conversion_api']['api_token'] : "";
      //$snapchat_conversion_api_is_enable = isset($pixels_option['snapchat_conversion_api']['is_enable']) ? $pixels_option['snapchat_conversion_api']['is_enable'] : "";

      /**
       * Cookies settings
       **/
      //axeptio
      $axeptio_project_id = isset($pixels_option['axeptio']['project_id'])?$pixels_option['axeptio']['project_id']:"";
      $axeptio_is_enable = isset($pixels_option['axeptio']['is_enable'])?$pixels_option['axeptio']['is_enable']:"";
      $axeptio_cookies_version = isset($pixels_option['axeptio']['cookies_version'])?$pixels_option['axeptio']['cookies_version']:"";

      $privecy_policy = isset($pixels_option['privecy_policy']['privecy_policy'])?$pixels_option['privecy_policy']['privecy_policy']:"";
      
      /**
       * Advance settings
       **/
      $exclude_tax_ordertotal = isset($pixels_option['integration']['exclude_tax_ordertotal'])?$pixels_option['integration']['exclude_tax_ordertotal']:"";
      $exclude_shipping_ordertotal = isset($pixels_option['integration']['exclude_shipping_ordertotal'])?$pixels_option['integration']['exclude_shipping_ordertotal']:"";
      $exclude_fee_ordertotal = isset($pixels_option['integration']['exclude_fee_ordertotal'])?$pixels_option['integration']['exclude_fee_ordertotal']:"";
      $send_product_sku = isset($pixels_option['integration']['send_product_sku'])?$pixels_option['integration']['send_product_sku']:"";
      $stop_send_user_data_ptm = isset($pixels_option['integration']['stop_send_user_data_ptm'])?$pixels_option['integration']['stop_send_user_data_ptm']:"";
      $roles_exclude_tracking = isset($pixels_option['integration']['roles_exclude_tracking'])?$pixels_option['integration']['roles_exclude_tracking']:"";
      $options_roles = $this->get_pmw_roles_list();
      $purchase_event_trigger = isset($pixels_option['tracking']['purchase_event_trigger']) ? $pixels_option['tracking']['purchase_event_trigger'] : 'url_based';
      $conversion_api_logs = isset($pixels_option['integration']['conversion_api_logs'])?$pixels_option['integration']['conversion_api_logs']:"";
      $conversion_api_logs_payload = isset($pixels_option['integration']['conversion_api_logs_payload'])?$pixels_option['integration']['conversion_api_logs_payload']:"";
      $debug_logs_is_active = (!empty($conversion_api_logs) || !empty($conversion_api_logs_payload));
      
      $fields = [
        "tab_pixels" =>[
          "type" => "tab",
          "name" => "pmw-pixels"
        ],
        "section_account" => [    
          [
            "type" => "section",
            "label" => __("Connect Account", "pixel-manager-for-woocommerce"),
            "class" => "google_section_setting",
          ]
        ],
        "user" => [    
          [
            "type" => "text",
            "label" => __("Email ID", "pixel-manager-for-woocommerce"),
            "name" => "email_id",
            "id" => "email_id",
            "value" => $email_id,
            "placeholder" => __("Enter Your Email", "pixel-manager-for-woocommerce"),
            "class" => "email_id",
            "tooltip" =>[
              "title" => __("Enter your email.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "section_freevspro" => [    
          [
            "type" => "section",
            "label" => __("Comparison between free and pro events tracking", "pixel-manager-for-woocommerce"),
            "class" => "freevspro_section_setting",
          ]
        ],
        "section_freevspro_features" => [    
          [
            "type" => "freevspro_features",
            "class" => "freevspro_features_setting",
          ]
        ],
        "ptm_upgrade_banner" =>  [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice facebook-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Unlock the complete PRO experience', 'pixel-manager-for-woocommerce'),
              esc_html__('Access every premium feature including support for all major eCommerce events, conversion api tracking and priority support.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=GA4+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "section_google" => [    
          [
            "type" => "section",
            "label" => __("Analytics & Pixel settings", "pixel-manager-for-woocommerce"),
            "class" => "google_section_setting",
          ]
        ],
        "sub_section_gtm_container" => [
          [
            "type" => "sub_section",
            "label" => __("GTM Container Settings", "pixel-manager-for-woocommerce"),
            "label_img" => "gtm.png",
            "is_tongal" => true,
            "is_new_feature" => true,
            "info" => __("Container ID: ", "pixel-manager-for-woocommerce").$gtm_container_id,
            "class" => "gtm_container_sub_section_setting",
          ]
        ],
        "gtm_container_load_mode" => [
          [
            "type" => "select",
            "label" => __("Load GTM Container", "pixel-manager-for-woocommerce"),
            "label_img" => "gtm.png",
            "name" => "gtm_container_load_mode",
            "id" => "gtm_container_load_mode",
            "value" => $gtm_container_load_mode,
            "options" => [
              "default_ptm" => __("Default PTM - GTM-XXXXXXXX","pixel-manager-for-woocommerce"),
              "use_own" => __("Use Own GTM Container", "pixel-manager-for-woocommerce"),
              "stop" => __("Stop GTM Container Loading", "pixel-manager-for-woocommerce"),
            ],
            "class" => "gtm_container_load_mode",
            "tooltip" =>[
              "title" => __("Choose the GTM container loading mode that best fits your setup.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "gtm_container_custom_id" => [
          [
            "type" => "text",
            "label" => __("Custom GTM Container ID", "pixel-manager-for-woocommerce"),
            "label_img" => "gtm.png",
            "note"  => __("Provide your GTM container ID (e.g., GTM-XXXXXXX) when using your own container.", "pixel-manager-for-woocommerce"),
            "name" => "gtm_container_custom_id",
            "id" => "gtm_container_custom_id",
            "value" => $gtm_container_custom_id,
            "placeholder" => __("GTM-XXXXXXX", "pixel-manager-for-woocommerce"),
            "class" => "gtm_container_custom_id",
            "tooltip" =>[
              "title" => __("Used when 'Use Own' is selected. Leave blank to continue using the default container.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "sub_section_gtm_container_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "sub_section_google_tag" => [    
          [
            "type" => "sub_section",
            "label" => __("Google Tag (Optional)", "pixel-manager-for-woocommerce"),
            "label_img" => "google_tag.svg",
            "is_tongal" => true,
            "is_new_feature" => true,
            "info" => ($google_tag_is_enable) ? $google_tag_id : "",
            "is_active" => $google_tag_is_enable,
            "class" => "google_tag_sub_section_setting",
          ]
        ],
        "google_tag_id" => [
          [
            "type" => "text_with_switch",
            "label_img" => "google_tag.svg",
            "label" => __("Google Tag ID", "pixel-manager-for-woocommerce"),
            "note"  => __("Accepted format: GT-XXXXXXXX. In the Google section below, you should also add the GA4 Measurement ID G-XXXXXXXX for GA4 tracking and Google ads conversion id for Google Ads tracking.", "pixel-manager-for-woocommerce"),
            "name" => "google_tag_id",
            "id" => "google_tag_id",
            "value" => $google_tag_id,
            "placeholder" => __("Enter Google Tag ID", "pixel-manager-for-woocommerce"),
            "class" => "google_tag_id"
          ],
          [
            "type" => "switch_with_text",
            "name" => "google_tag_is_enable",
            "id" => "google_tag_is_enable",
            "value" => $google_tag_is_enable,
            "class" => "google_tag_is_enable",
            "tooltip" =>[
              "title" => __("Switch to the new Google Tag that accepts GT- IDs and powers can be used for GA4, Google Ads and both as well with single tag implementation.", "pixel-manager-for-woocommerce"),
              "link_title" => __("Find the Google tag ID", "pixel-manager-for-woocommerce"),
              "link" => "https://support.google.com/google-ads/answer/15107467"
            ]
          ]
        ],
        "google_tag_for" => [    
          [
            "type" => "radio",
            "label" => __("Use Google Tag for", "pixel-manager-for-woocommerce"),
            "name" => "google_tag_for",
            "id" => "google_tag_for",
            "options" => [
              [
                "value" => "ga4",
                "label" => __("GA4", "pixel-manager-for-woocommerce"),
                "checked" => ($google_tag_for === 'ga4')
              ],
              [
                "value" => "google_ads",
                "label" => __("Google Ads", "pixel-manager-for-woocommerce"),
                "checked" => ($google_tag_for === 'google_ads')
              ],
              [
                "value" => "both",
                "label" => __("Both", "pixel-manager-for-woocommerce"),
                "checked" => ($google_tag_for === 'both')
              ]
            ],
            "class" => "google_tag_for",
            "tooltip" =>[
              "title" => __("Select the Google Tag for GA4 or Google Ads or both.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "sub_section_google_tag_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "sub_section_google_analytics" => [    
          [
            "type" => "sub_section",
            "label" => __("Google Analytics", "pixel-manager-for-woocommerce"),
            "label_img" => "google_analytics.svg",
            "is_tongal" => true,
            "is_active" => $google_analytics_4_pixel_is_enable,
            "info" => !empty($google_analytics_4_pixel_is_enable)?$google_analytics_4_pixel_id:"",
            "class" => "google_analytics_sub_section_setting",
          ]
        ],
        "google_analytics_4_pixel" => [    
          [
            "type" => "text_with_switch",
            "label" => __("GA4- Measurement ID", "pixel-manager-for-woocommerce"),
            "label_img" => "google_analytics.svg",
            "note"  => __("Ex. Measurement ID: G-QCX3G9KSPC", "pixel-manager-for-woocommerce"),
            "name" => "google_analytics_4_pixel_id",
            "id" => "google_analytics_4_pixel_id",
            "value" => $google_analytics_4_pixel_id,
            "placeholder" => __("Measurement ID", "pixel-manager-for-woocommerce"),
            "class" => "google_analytics_4_pixel_id"
          ],[
            "type" => "switch_with_text",
            "name" => "google_analytics_4_pixel_is_enable",
            "id" => "google_analytics_4_pixel_is_enable",
            "value" => $google_analytics_4_pixel_is_enable,
            "class" => "google_analytics_4_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Google Analytics 4 Measurement ID?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://support.google.com/analytics/answer/12270356?hl=en"
            ]
          ]
        ],
        "generate_lead_from" => [    
          [
            "type" => "text",
            "label" => __("Form Submission Tracking", "pixel-manager-for-woocommerce"),
            "note"  => __("Enter Form IDs or Class - Ex. .user,#registration,.contact_form", "pixel-manager-for-woocommerce"),
            "name" => "generate_lead_from",
            "id" => "generate_lead_from",
            "value" => $generate_lead_from,
            "placeholder" => __(".user,#registration,.contact_form", "pixel-manager-for-woocommerce"),
            "class" => "generate_lead_from",
            "tooltip" =>[
              "title" => __("Specify the form elements you want to track by entering their IDs or classes. You can track multiple forms by separating their selectors with commas. Ex. .user,#registration,.contact_form", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "google_analytics_growinsights_highlight" => [
          [
            "type"  => "html",
            "class" => "pmw-growinsights-highlight",
            "value" => sprintf(
              '<div class="pmw-growinsights-card"><span class="pmw-growinsights-label">%1$s</span><h4>%2$s</h4><p>%3$s</p><a class="button button-secondary" href="%4$s">%5$s</a></div>',
              esc_html__('Included: GrowInsights360 GA4 dashboard', 'pixel-manager-for-woocommerce'),
              esc_html__('Visualize your GA4 performance without leaving WordPress', 'pixel-manager-for-woocommerce'),
              esc_html__('Connect to GrowInsights360 to unlock GA4 360 view reports, and product journey reports that complement your GA4 measurement.', 'pixel-manager-for-woocommerce'),
              esc_url(admin_url('admin.php?page=pixel-manager-growinsights360')),
              esc_html__('Open GrowInsights360 GA4 dashboard', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "google_analytics_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice google-analytics-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Unlock Google Analytics 4 eCommerce tracking', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to auto-track checkout steps, eCommerce events, and show enhanced data in GA4 dashboards.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=GA4+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_google_analytics_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "sub_section_google_ads" => [    
          [
            "type" => "sub_section",
            "label" => __("Google Ads", "pixel-manager-for-woocommerce"),
            "label_img" => "google_ads.png",
            "is_tongal" => true,
            "is_active" => (
              !empty($google_ads_conversion_is_enable) ||
              !empty($google_ads_form_conversion_is_enable) ||
              !empty($google_ads_enhanced_conversion_is_enable) ||
              !empty($google_ads_dynamic_remarketing_is_enable)
            ),
            "class" => "google_ads_sub_section_setting",
          ]
        ],
        "sub_section_google_ads_conversion" => [
          [
            "type" => "sub_section",
            "label" => __("Google Ads Conversion", "pixel-manager-for-woocommerce"),
            "is_tongal" => true,
            "is_active" => !empty($google_ads_conversion_is_enable),
            "info" => !empty($google_ads_conversion_is_enable)?"AW-".$google_ads_conversion_id."/".$google_ads_conversion_label:"",
            "class" => "google_ads_sub_section_setting section_sub_setting",
          ]
        ],
        "google_ads_conversion" => [    
          [
            "type" => "multi_text",
            "text_fields" =>[
              [
                "label" => __("Google Ads Conversion ID", "pixel-manager-for-woocommerce"),           
                "note"  => __("Ex. Conversion ID: 11074736289", "pixel-manager-for-woocommerce"),
                "name" => "google_ads_conversion_id",
                "id" => "google_ads_conversion_id",
                "value" => $google_ads_conversion_id,
                "placeholder" => __("Conversion ID", "pixel-manager-for-woocommerce"),
                "class" => "google_ads_conversion_id"
              ],
              [ 
                "label" => __("Conversion Label", "pixel-manager-for-woocommerce"),          
                "note"  => __("Ex. Conversion Label: C3znCNLp84gYEKGh7KAp", "pixel-manager-for-woocommerce"),
                "name" => "google_ads_conversion_label",
                "id" => "google_ads_conversion_label",
                "value" => $google_ads_conversion_label,
                "placeholder" => __("Conversion Label", "pixel-manager-for-woocommerce"),
                "class" => "google_ads_conversion_label"
              ]
            ]
          ]
        ],
        "google_ads_conversion_is_enable" => [    
          [
            "type" => "checkbox",
            "label" => __("Enable Google Ads Conversion tracking", "pixel-manager-for-woocommerce"),
            "name" => "google_ads_conversion_is_enable",
            "id" => "google_ads_conversion_is_enable",
            "value" => $google_ads_conversion_is_enable,
            "class" => "google_ads_conversion_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Google Ads Conversion ID and Conversion Label?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://support.google.com/tagmanager/answer/6105160?hl=en"
            ]
          ]
        ],
        "google_ads_enhanced_conversion_is_enable" => [    
          [
            "type" => "checkbox",
            "label" => __("Enable Google Ads Enhanced Conversions tracking", "pixel-manager-for-woocommerce"),
            "name" => "google_ads_enhanced_conversion_is_enable",
            "id" => "google_ads_enhanced_conversion_is_enable",
            "value" => $google_ads_enhanced_conversion_is_enable,
            "class" => "google_ads_enhanced_conversion_is_enable",
            "tooltip" =>[
              "title" => __("Enable Google Ads Enhanced Conversions tracking.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "google_ads_dynamic_remarketing_is_enable" => [    
          [
            "type" => "checkbox",
            "label" => __("Enable Google Ads dynamic remarketing tracking", "pixel-manager-for-woocommerce"),
            "name" => "google_ads_dynamic_remarketing_is_enable",
            "id" => "google_ads_dynamic_remarketing_is_enable",
            "value" => $google_ads_dynamic_remarketing_is_enable,
            "class" => "google_ads_dynamic_remarketing_is_enable",
            "tooltip" =>[
              "title" => __("Enable Google Ads dynamic remarketing tracking.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "sub_section_google_ads_conversion_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_google_ads_form_conversion" => [    
          [
            "type" => "sub_section",
            "label" => __("Google Ads Form Conversion", "pixel-manager-for-woocommerce"),
            // "label_img" => "google_ads.png",
            "is_tongal" => true,
            "is_active" => !empty($google_ads_form_conversion_is_enable),
            "info" => !empty($google_ads_form_conversion_is_enable)?"AW-".$google_ads_form_conversion_id."/".$google_ads_form_conversion_label:"",
            "class" => "google_ads_sub_section_setting section_sub_setting",
          ]
        ],
        "google_ads_form_conversion" => [    
          [
            "type" => "multi_text",
            "text_fields" =>[
              [
                "label" => __("Google Ads Form Conversion ID", "pixel-manager-for-woocommerce"),           
                "note"  => __("Ex. Conversion Form ID: 11338938599", "pixel-manager-for-woocommerce"),
                "name" => "google_ads_form_conversion_id",
                "id" => "google_ads_form_conversion_id",
                "value" => $google_ads_form_conversion_id,
                "placeholder" => __("Conversion ID", "pixel-manager-for-woocommerce"),
                "class" => "google_ads_form_conversion_id"
              ],
              [ 
                "label" => __("Conversion Form Label", "pixel-manager-for-woocommerce"),
                "note"  => __("Ex. Conversion Form Label: vVf2CN2drc0aEOfx6Z4q", "pixel-manager-for-woocommerce"),
                "name" => "google_ads_form_conversion_label",
                "id" => "google_ads_form_conversion_label",
                "value" => $google_ads_form_conversion_label,
                "placeholder" => __("Conversion Label", "pixel-manager-for-woocommerce"),
                "class" => "google_ads_form_conversion_label"
              ]
            ]
          ]
        ],
        "google_ads_form_conversion_selector" => [    
          [
            "type" => "text_with_switch",
            "label" => __("Google Ads Form Conversion Selector", "pixel-manager-for-woocommerce"),
            //"label_img" => "facebook_pixel.png",
            "note"  => __("Enter Form IDs or Class - Ex. .user,#registration,.contact_form", "pixel-manager-for-woocommerce"),
            "name" => "google_ads_form_conversion_selector",
            "id" => "google_ads_form_conversion_selector",
            "value" => $google_ads_form_conversion_selector,
            "placeholder" => __(".user,#registration,.contact_form", "pixel-manager-for-woocommerce"),
            "class" => "google_ads_form_conversion_selector"
          ],[
            "type" => "switch_with_text",
            "name" => "google_ads_form_conversion_is_enable",
            "id" => "google_ads_form_conversion_is_enable",
            "value" => $google_ads_form_conversion_is_enable,
            "class" => "google_ads_form_conversion_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Google Ads Conversion ID and Conversion Label?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://support.google.com/tagmanager/answer/6105160?hl=en"
            ]
          ]
        ],
        "sub_section_google_ads_form_conversion_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "google_ads_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice google-ads-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Supercharge Google Ads tracking', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to unlock to send more data to Google Ads enhanced conversions, and dynamic remarketing.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=Google+Ads+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_google_ads_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_facebook" => [    
          [
            "type" => "sub_section",
            "label" => __("Facebook", "pixel-manager-for-woocommerce"),
            "label_img" => "facebook_pixel.png",
            "is_tongal" => true,
            "is_active" => (!empty($facebook_pixel_is_enable) || !empty($fb_conversion_api_is_enable)),
            "info" => !empty($facebook_pixel_is_enable)?$facebook_pixel_id:"",
            "class" => "facebook_sub_section_setting",
          ]
        ],
        "facebook_pixel" => [    
          [
            "type" => "text_with_switch",
            "label" => __("Facebook pixel ID(s)", "pixel-manager-for-woocommerce"),
            "label_img" => "facebook_pixel.png",
            "note"  => __("Ex. Facebook pixel ID(s): 590022289301578,558158472945205", "pixel-manager-for-woocommerce"),
            "name" => "facebook_pixel_id",
            "id" => "facebook_pixel_id",
            "value" => $facebook_pixel_id,
            "placeholder" => __("Facebook Pixel ID(s)", "pixel-manager-for-woocommerce"),
            "class" => "facebook_pixel_id"
          ],[
            "type" => "switch_with_text",
            "name" => "facebook_pixel_is_enable",
            "id" => "facebook_pixel_is_enable",
            "value" => $facebook_pixel_is_enable,
            "class" => "facebook_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Facebook pixel id?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://www.facebook.com/business/help/952192354843755?id=1205376682832142"
            ]
          ]
        ],
        "fb_conversion_api" => [    
          [
            "type" => "textarea_with_switch",
            "label" => __("Meta (Facebook) Conversion API token", "pixel-manager-for-woocommerce"),
            //"label_img" => "facebook_pixel.png",
            "note"  => __("Send events directly from your web server to Facebook through the Conversion API.", "pixel-manager-for-woocommerce"),
            "name" => "fb_conversion_api_token",
            "id" => "fb_conversion_api_token",
            "value" => $fb_conversion_api_token,
            "placeholder" => __("Conversion API token", "pixel-manager-for-woocommerce"),
            "class" => "fb_conversion_api_token"
          ],
          [
            "type" => "switch_with_text",
            "name" => "fb_conversion_api_is_enable",
            "id" => "fb_conversion_api_is_enable",
            "value" => $fb_conversion_api_is_enable,
            "class" => "fb_conversion_api_is_enable",
            "tooltip" =>[
              "title" => __("How to find Meta (Facebook) Conversion API token?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://developers.facebook.com/docs/marketing-api/conversions-api/get-started#access-token"
            ]
          ]
        ],
        "test_event_code" => [    
          [
            "type" => "text",
            "label" => __("Test Event Code", "pixel-manager-for-woocommerce"),
            "name" => "test_event_code",
            "id" => "ematest_event_codeil_id",
            "value" => $test_event_code,
            "placeholder" => __("TEST12345", "pixel-manager-for-woocommerce"),
            "class" => "test_event_code",
            "tooltip" =>[
              "title" => __("Enter your Test Event Code.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],       
        "facebook_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice facebook-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Scale Meta (Facebook) tracking', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to support more events with multiple pixel IDs, Conversion API, and advanced matching support.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=Facebook+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_facebook_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_tiktok" => [    
          [
            "type" => "sub_section",
            "label" => __("TikTok", "pixel-manager-for-woocommerce"),
            "label_img" => "tiktok_pixel.png",
            "is_tongal" => true,
            "is_new_feature" => true,
            "is_active" => (!empty($tiktok_pixel_is_enable) || !empty($tiktok_conversion_api_is_enable)),
            "info" => !empty($tiktok_pixel_is_enable)?$tiktok_pixel_id:"",
            "class" => "tiktok_sub_section_setting",
          ]
        ],
        "tiktok_pixel" => [    
          [
            "type" => "text_with_switch",
            "label" => __("Tiktok pixel ID", "pixel-manager-for-woocommerce"),
            "label_img" => "tiktok_pixel.png",
            "note"  => __("Ex. Tiktok pixel ID: CBEE743C77U5BM7P378G", "pixel-manager-for-woocommerce"),
            "name" => "tiktok_pixel_id",
            "id" => "tiktok_pixel_id",
            "value" => $tiktok_pixel_id,
            "placeholder" => __("Tiktok Pixel ID", "pixel-manager-for-woocommerce"),
            "class" => "tiktok_pixel_id"
          ],[
            "type" => "switch_with_text",
            "name" => "tiktok_pixel_is_enable",
            "id" => "tiktok_pixel_is_enable",
            "value" => $tiktok_pixel_is_enable,
            "class" => "tiktok_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Tiktok pixel id?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://ads.tiktok.com/help/article?aid=10021"
            ]
          ]
        ],
        "tiktok_conversion_api" => [    
          [
            "type" => "textarea_with_switch",
            "label" => __("TikTok Events API token", "pixel-manager-for-woocommerce"),
            "note"  => __("Send events directly from your server to TikTok via the Events API.", "pixel-manager-for-woocommerce"),
            "name" => "tiktok_conversion_api_token",
            "id" => "tiktok_conversion_api_token",
            "value" => $tiktok_conversion_api_token,
            "placeholder" => __("Events API token", "pixel-manager-for-woocommerce"),
            "class" => "tiktok_conversion_api_token"
          ],[
            "type" => "switch_with_text",
            "name" => "tiktok_conversion_api_is_enable",
            "id" => "tiktok_conversion_api_is_enable",
            "value" => $tiktok_conversion_api_is_enable,
            "class" => "tiktok_conversion_api_is_enable",
            "tooltip" =>[
              "title" => __("How to find TikTok Events API token?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://ads.tiktok.com/help/article/getting-started-events-api"
            ]
          ]
        ],
        "tiktok_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice tiktok-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Enable complete TikTok Events tracking', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to track more events with website pixel and push server-side events, advanced events, and audiences to TikTok.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=TikTok+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_tiktok_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_pinterest" => [    
          [
            "type" => "sub_section",
            "label" => __("Pinterest", "pixel-manager-for-woocommerce"),
            "label_img" => "pinterest_pixel.png",
            "is_tongal" => true,
            "is_new_feature" => true,
            "is_active" => (!empty($pinterest_pixel_is_enable) || !empty($pinterest_conversion_api_is_enable)),
            "info" => !empty($pinterest_pixel_is_enable)?$pinterest_pixel_id:"",
            "class" => "pinterest_sub_section_setting",
          ]
        ],
        "pinterest_pixel" => [
          [
            "type" => "text_with_switch",
            "label" => __("Pinterest Pixel ID", "pixel-manager-for-woocommerce"),
            "label_img" => "pinterest_pixel.png",
            "note"  => __("Ex. Pinterest pixel ID: 2613257208392", "pixel-manager-for-woocommerce"),
            "name" => "pinterest_pixel_id",
            "id" => "pinterest_pixel_id",
            "value" => $pinterest_pixel_id,
            "placeholder" => __("Pinterest Pixel ID", "pixel-manager-for-woocommerce"),
            "class" => "pinterest_pixel_id"
          ],[
            "type" => "switch_with_text",
            "name" => "pinterest_pixel_is_enable",
            "id" => "pinterest_pixel_is_enable",
            "value" => $pinterest_pixel_is_enable,
            "class" => "pinterest_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Pinterest pixel id?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://developers.pinterest.com/docs/tag/conversion/#basecode"
            ]
          ]
        ],
        "pinterest_conversion_api" => [
          [
            "type" => "textarea_with_switch",
            "label" => __("Pinterest Conversion API Token", "pixel-manager-for-woocommerce"),
            "note"  => __("Send events directly from your server to Pinterest via the Conversion API.", "pixel-manager-for-woocommerce"),
            "name" => "pinterest_conversion_api_token",
            "id" => "pinterest_conversion_api_token",
            "value" => $pinterest_conversion_api_token,
            "placeholder" => __("Pinterest Conversion API Token", "pixel-manager-for-woocommerce"),
            "class" => "pinterest_conversion_api_token"
          ],
          [ 
            "type" => "switch_with_text",
            "name" => "pinterest_conversion_api_is_enable",
            "id" => "pinterest_conversion_api_is_enable",
            "value" => $pinterest_conversion_api_is_enable,
            "class" => "pinterest_conversion_api_is_enable",
            "tooltip" => [
              "title" => __("How to find Pinterest Conversion API token?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Documentation", "pixel-manager-for-woocommerce"),
              "link" => "https://help.pinterest.com/en/business/article/getting-started-with-the-conversions-api"
            ]
          ]
        ],
        "pinterest_conversion_api_ad_account_id" => [
          [
            "type" => "text",
            "label" => __("Pinterest Ad Account ID", "pixel-manager-for-woocommerce"),
            "note"  => __("Your Pinterest Ad Account ID (e.g., 1234567890) for Conversion API.", "pixel-manager-for-woocommerce"),
            "name" => "pinterest_conversion_api_ad_account_id",
            "id" => "pinterest_conversion_api_ad_account_id",
            "value" => $pinterest_conversion_api_ad_account_id,
            "placeholder" => __("1234567890", "pixel-manager-for-woocommerce"),
            "class" => "pinterest_conversion_api_ad_account_id"
          ]
        ],
        "pinterest_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice pinterest-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Power up Pinterest Conversion tracking', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to track more events, send Conversion API, product metadata, and enhanced tracking capabilities.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=Pinterest+Section+Upgrade&utm_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_pinterest_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_twitter" => [    
          [
            "type" => "sub_section",
            "label" => __("Twitter Pixel", "pixel-manager-for-woocommerce"),
            "label_img" => "twitter_pixel.png",
            "is_tongal" => true,
            "is_new_feature" => false,
            "is_active" => (!empty($twitter_pixel_is_enable)),
            "info" => !empty($twitter_pixel_is_enable)?$twitter_pixel_id:"",
            "class" => "twitter_sub_section_setting",
          ]
        ],
        "twitter_pixel" => [    
          [
           "type" => "text_with_switch",
            "label" => __("Twitter pixel ID", "pixel-manager-for-woocommerce"),
            "label_img" => "twitter_pixel.png",
            "note"  => __("Ex. Twitter pixel ID: o9e1c", "pixel-manager-for-woocommerce"),
            "name" => "twitter_pixel_id",
            "id" => "twitter_pixel_id",
            "value" => $twitter_pixel_id,
            "placeholder" => __("Twitter Pixel ID", "pixel-manager-for-woocommerce"),
            "class" => "twitter_pixel_id"
          ],
          [
            "type" => "switch_with_text",
            "name" => "twitter_pixel_is_enable",
            "id" => "twitter_pixel_is_enable",
            "value" => $twitter_pixel_is_enable,
            "class" => "twitter_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Twitter pixel id?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://business.twitter.com/en/help/campaign-measurement-and-analytics/conversion-tracking-for-websites.html"
            ]
          ]
        ],
        /*"twitter_conversion_api" => [    
          [
            "type" => "textarea_with_switch",
            "is_pro_featured" => true,
            "is_pro_text" => "Upgrade to Pro",
            "pro_utm_text"=> "PRO+Twitter+Conversion+API+Settings",
            "label" => __("Twitter Conversion API Token", "pixel-manager-for-woocommerce"),
            "note"  => __("Send events directly from your server to Twitter via the Conversion API.", "pixel-manager-for-woocommerce"),
            "name" => "twitter_conversion_api_token",
            "id" => "twitter_conversion_api_token",
            "value" => isset($twitter_conversion_api_token) ? $twitter_conversion_api_token : '',
            "placeholder" => __("Twitter Conversion API Token", "pixel-manager-for-woocommerce"),
            "class" => "twitter_conversion_api_token"
          ],
          [
            "type" => "switch_with_text",
            "name" => "twitter_conversion_api_is_enable",
            "id" => "twitter_conversion_api_is_enable",
            "value" => isset($twitter_conversion_api_is_enable) ? $twitter_conversion_api_is_enable : '0',
            "class" => "twitter_conversion_api_is_enable",
            "tooltip" => [
              "title" => __("How to find Twitter Conversion API token?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Documentation", "pixel-manager-for-woocommerce"),
              "link" => "https://business.twitter.com/en/help/campaign-measurement-and-analytics/conversion-tracking-for-websites.html"
            ]
          ]
        ],*/
        "twitter_upgrade_banner" => [
          [
            "type"  => "html",
            "class" => "pmw-pro-upgrade-notice twitter-upgrade-banner",
            "value" => sprintf(
              '<div class="pmw-upgrade-callout"><h4>%1$s</h4><p>%2$s</p><a class="button button-primary" target="_blank" href="%3$s">%4$s</a></div>',
              esc_html__('Unlock Twitter Ads pixel', 'pixel-manager-for-woocommerce'),
              esc_html__('Upgrade to the PRO plan to enable support all eCommerce events tracking, and priority support for Twitter Ads.', 'pixel-manager-for-woocommerce'),
              esc_url_raw($this->get_price_plan_link().'&utm_source=Plugin+WordPress+Screen&utm_medium=Twitter+Section+Upgrade&m_campaign=Upsell+at+PixelTagManager+Plugin'),
              esc_html__('Upgrade to Pro', 'pixel-manager-for-woocommerce')
            )
          ]
        ],
        "sub_section_twitter_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_snapchat" => [
          [
            "type" => "sub_section",
            "label" => __("Snapchat Pixel", "pixel-manager-for-woocommerce"),
            "label_img" => "snapchat_pixel.png",
            "is_tongal" => true,
            "is_new_feature" => false,
            "is_active" => (!empty($snapchat_pixel_is_enable)),
            "info" => !empty($snapchat_pixel_is_enable)?$snapchat_pixel_id:"",
            "class" => "snapchat_sub_section_setting",
          ]
        ],
        "snapchat_pixel" => [
          [
            "type" => "text_with_switch",
            "label" => __("Snapchat Pixel ID", "pixel-manager-for-woocommerce"),
            "label_img" => "snapchat_pixel.png",
            "note"  => __("Ex. Snapchat pixel ID: 12e1ec0a-91aa-4267-b1a3-182c355710e7", "pixel-manager-for-woocommerce"),
            "name" => "snapchat_pixel_id",
            "id" => "snapchat_pixel_id",
            "value" => $snapchat_pixel_id,
            "placeholder" => __("Snapchat Pixel ID", "pixel-manager-for-woocommerce"),
            "class" => "snapchat_pixel_id"
          ],
          [
            "type" => "switch_with_text",
            "name" => "snapchat_pixel_is_enable",
            "id" => "snapchat_pixel_is_enable",
            "value" => $snapchat_pixel_is_enable,
            "class" => "snapchat_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Snapchat pixel id?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://businesshelp.snapchat.com/s/article/pixel-website-install?language=en_US"
            ]
          ]
        ],
        /*"snapchat_conversion_api" => [    
          [
            "type" => "textarea_with_switch",
            "label" => __("Snapchat Conversion API Token", "pixel-manager-for-woocommerce"),
            "note"  => __("Send events directly from your server to Snapchat via the Conversion API.", "pixel-manager-for-woocommerce"),
            "name" => "snapchat_conversion_api_token",
            "id" => "snapchat_conversion_api_token",
            "value" => isset($snapchat_conversion_api_token) ? $snapchat_conversion_api_token : '',
            "placeholder" => __("Snapchat Conversion API Token", "pixel-manager-for-woocommerce"),
            "class" => "snapchat_conversion_api_token"
          ],
          [
            "type" => "switch_with_text",
            "name" => "snapchat_conversion_api_is_enable",
            "id" => "snapchat_conversion_api_is_enable",
            "value" => isset($snapchat_conversion_api_is_enable) ? $snapchat_conversion_api_is_enable : '0',
            "class" => "snapchat_conversion_api_is_enable",
            "tooltip" => [
              "title" => __("How to find Snapchat Conversion API token?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Documentation", "pixel-manager-for-woocommerce"),
              "link" => "https://businesshelp.snapchat.com/s/article/conversion-api"
            ]
          ]
        ],*/
        "sub_section_snapchat_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "sub_section_bing" => [    
          [
            "type" => "sub_section",
            "label" => __("Bing Pixel", "pixel-manager-for-woocommerce"),
            "label_img" => "bing_pixel.png",
            "is_tongal" => true,
            "is_active" => !empty($bing_pixel_is_enable),
            "info" => !empty($bing_pixel_is_enable)?$bing_pixel_id:"",
            "class" => "bing_sub_section_setting",
          ]
        ],
        "bing_pixel" => [
          [
            "type" => "text_with_switch",
            "label" => __("Bing Ads pixel ID", "pixel-manager-for-woocommerce"),
            "label_img" => "bing_pixel.png",
            "note"  => __("Ex. Microsoft Ads - The Bing Ads pixel ID (UET tag ID): 136018753", "pixel-manager-for-woocommerce"),
            "name" => "bing_pixel_id",
            "id" => "bing_pixel_id",
            "value" => $bing_pixel_id,
            "placeholder" => __("Bing Ads Pixel ID (UET tag ID)", "pixel-manager-for-woocommerce"),
            "class" => "bing_pixel_id"
          ],[
            "type" => "switch_with_text",
            "name" => "bing_pixel_is_enable",
            "id" => "bing_pixel_is_enable",
            "value" => $bing_pixel_is_enable,
            "class" => "bing_pixel_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Bing Ads pixel id (UET tag id)?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://help.ads.microsoft.com/#apex/ads/en/56682/-1"
            ]
          ]
        ],
        "sub_section_other_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "hidden" => [
          [
            "type" => "hidden",
            "name" => "privecy_policy",
            "id" => "privecy_policy",
            "value" => $privecy_policy
          ],[
            "type" => "hidden",
            "id" => "pixels_save_action",
            "name" => "action",
            "value" => "pmw_check_privecy_policy"
          ]
        ],
        "tab_end_pixels" => [ 
          "type" => "tab_end"          
        ],
        "tab_integration" => [
          "type" => "tab",
          "name" => "pmw-pixels-integration"
        ],
        "section_pixels_integration" => [    
          [
            "type" => "section",
            "label" => __("Advanced Settings", "pixel-manager-for-woocommerce"),
            "class" => "pixel_section_setting",
          ]
        ],
        "sub_section_pixels_integration" => [    
          [
            "type" => "sub_section",
            "label" => __("Advanced Options", "pixel-manager-for-woocommerce"),
            "is_tongal" => true,
            "is_new_feature" => true,
            //"is_active" => (bool) $advanced_options_is_active,
            "class" => "pixels_integration_sub_section_setting",
          ]
        ],
        "send_product_sku" => [    
          [
            "type" => "switch",
            "label" => __("Send Product SKU instead of ID", "pixel-manager-for-woocommerce"),
            "name" => "send_product_sku",
            "id" => "send_product_sku",
            "value" => $send_product_sku,
            "class" => "send_product_sku",
            "tooltip" =>[
              "title" => __("Activate this feature to send product SKU information for remarketing and eCommerce tracking.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "exclude_tax_ordertotal" => [    
          [
            "type" => "switch",
            "label" => __("Exclude tax from order revenue", "pixel-manager-for-woocommerce"),
            "name" => "exclude_tax_ordertotal",
            "id" => "exclude_tax_ordertotal",
            "value" => $exclude_tax_ordertotal,
            "class" => "exclude_tax_ordertotal",
            "tooltip" =>[
              "title" => __("Activate this feature to exclude tax from the order total variable.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "exclude_shipping_ordertotal" => [    
          [
            "type" => "switch",
            "label" => __("Exclude shipping from order revenue", "pixel-manager-for-woocommerce"),
            "name" => "exclude_shipping_ordertotal",
            "id" => "exclude_shipping_ordertotal",
            "value" => $exclude_shipping_ordertotal,
            "class" => "exclude_shipping_ordertotal",
            "tooltip" =>[
              "title" => __("Activate this feature to exclude shipping from the order total variable.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "exclude_fee_ordertotal" => [    
          [
            "type" => "switch",
            "label" => __("Exclude fee from order revenue", "pixel-manager-for-woocommerce"),
            "name" => "exclude_fee_ordertotal",
            "id" => "exclude_fee_ordertotal",
            "value" => $exclude_fee_ordertotal,
            "class" => "exclude_fee_ordertotal",
            "tooltip" =>[
              "title" => __("Activate this feature to exclude fee from the order total variable.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "roles_exclude_tracking" => [    
          [
            "type" => "multi_checkbox",
            "label" => __("Exclude User Roles from Event Tracking", "pixel-manager-for-woocommerce"),
            "name" => "roles_exclude_tracking",
            "id" => "roles_exclude_tracking",
            "options" => $options_roles,
            "value" => $roles_exclude_tracking,
            "class" => "roles_exclude_tracking",
            "tooltip" =>[
              "title" => __("Select user roles to exclude from event tracking.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "section_pixels_send_user_data" => [    
          [
            "type" => "section",
            "label" => __("User Data", "pixel-manager-for-woocommerce"),
            "class" => "pixel_section_setting",
          ]
        ],        
        "stop_send_user_data_ptm" => [    
          [
            "type" => "switch",
            "label" => __("Stop Send User Data to GTM tracking (Stop Sending Advanced Matching Data)", "pixel-manager-for-woocommerce"),
            "name" => "stop_send_user_data_ptm",
            "id" => "stop_send_user_data_ptm",
            "value" => $stop_send_user_data_ptm,
            "class" => "stop_send_user_data_ptm",
            "tooltip" => [
              "title" => __("Enable this to stop send user data tracking.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "sub_section_pixels_integration_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "section_pixels_tracking" => [
          [
            "type" => "section",
            "label" => __("Tracking Settings", "pixel-manager-for-woocommerce"),
            "class" => "pixel_section_setting",
          ]
        ],
        "sub_section_pixels_tracking_trigger" => [
          [
            "type" => "sub_section",
            "label" => __("Tracking trigger", "pixel-manager-for-woocommerce"),
            "is_tongal" => true,
            "is_new_feature" => true,
            "class" => "pixels_tracking_trigger_sub_section_setting",
          ]
        ],
        "purchase_event_trigger" => [
          [
            "type" => "select",
            "label" => __("Purchase Event Trigger", "pixel-manager-for-woocommerce"),
            "name" => "purchase_event_trigger",
            "id" => "purchase_event_trigger",
            "value" => $purchase_event_trigger,
            "options" => [
              "url_based" => __("URL Based", "pixel-manager-for-woocommerce"),
              "woocommerce_thankyou" => __("WooCommerce Thank You Hook", "pixel-manager-for-woocommerce"),
              "ptm_woocommerce_thankyou" => __("Custom Hook (ptm_woocommerce_thankyou)", "pixel-manager-for-woocommerce"),
            ],
            "class" => "purchase_event_trigger",
            "tooltip" => [
              "title" => __("Please select how the purchase event should be triggered. If the URL-based method doesn’t work, use the WooCommerce Thank You hook instead. (If you’re using an Elementor template, then the hook-based method is recommended.)", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "purchase_event_trigger_notice" => [
          [
            "type" => "html",
            "class" => "purchase_event_trigger_notice",
            "value" => sprintf(
              '<div class="pmw-notice pmw-notice-info">%1$s<pre><code>%2$s</code></pre></div>',
              esc_html__("If you choose the custom hook option, add the ptm_woocommerce_thankyou do action hook inside your Thank You page template.", "pixel-manager-for-woocommerce"),
              esc_html__('<?php do_action(\'ptm_woocommerce_thankyou\', $order_id); ?>', "pixel-manager-for-woocommerce")
            ),
            "dependency" => [
              "field" => "purchase_event_trigger",
              "value" => "custom_ptm_thankyou_hook"
            ]
          ]
        ],
        "sub_section_pixels_tracking_trigger_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "section_pixels_debug_logs" => [    
          [
            "type" => "section",
            "label" => __("Debug Logs Settings", "pixel-manager-for-woocommerce"),
            "class" => "pixel_section_setting",
          ]
        ],
        "sub_section_pixels_debug_logs" => [    
          [
            "type" => "sub_section",
            "label" => __("Debug Logs", "pixel-manager-for-woocommerce"),
            "is_tongal" => true,
            "is_new_feature" => true,
            "is_active" => (bool) $debug_logs_is_active,
            "class" => "pixels_debug_logs_sub_section_setting",
          ]
        ],
        "conversion_api_logs" => [    
          [
            "type" => "switch",
            "label" => __("Enable Conversion API Logs", "pixel-manager-for-woocommerce"),
            "name" => "conversion_api_logs",
            "id" => "conversion_api_logs",
            "value" => $conversion_api_logs,
            "class" => "conversion_api_logs",
            "tooltip" =>[
              "title" => __("Activate this feature to enable conversion API log of last 10 events.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "conversion_api_logs_payload" => [    
          [
            "type" => "checkbox",
            "label" => __("Add API Payload to Conversion API Logs", "pixel-manager-for-woocommerce"),
            "name" => "conversion_api_logs_payload",
            "id" => "conversion_api_logs_payload",
            "value" => $conversion_api_logs_payload,
            "class" => "conversion_api_logs_payload",
            "tooltip" =>[
              "title" => __("This feature must be enabled to Conversion API logs. Once turned on, you’ll be able to see the API payload in the log section below.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "conversion_api_logs_viewer" => [    
          [
            "type" => "logs_viewer",
            "label" => __("Recent Conversion API Logs", "pixel-manager-for-woocommerce"),
            "name" => "conversion_api_logs_view",
            "id" => "conversion_api_logs_view"
          ]
        ],
        "sub_section_pixels_debug_logs_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true,
          ]
        ],
        "tab_end_integration" => [
          "type" => "tab_end"
        ],
        "tab_cookies" => [
          "type" => "tab",
          "name" => "pmw-pixels-cookies"
        ],
        "section_axeptio" => [    
          [
            "type" => "section",
            "label" => __("Cookies Consents Settings", "pixel-manager-for-woocommerce"),
            "class" => "consents_section_setting",
          ]
        ],
        "sub_section_axeptio" => [    
          [
            "type" => "sub_section",
            "is_tongal" => true,
            "label" => __("Axeptio", "pixel-manager-for-woocommerce"),
            "label_img" => "axeptio.png",
            "class" => "axeptio_sub_section_setting",
          ]
        ],
        "axeptio" => [    
          [
            "type" => "text_with_switch",
            "is_pro_featured" => true,
            "is_pro_text" => "Upgrade to Pro",
            "pro_utm_text"=> "PRO+Axeptio+Cookies+Pixel+Settings",
            "label" => __("Project ID", "pixel-manager-for-woocommerce"),
            //"label_img" => "facebook_pixel.png",
            "note"  => __("Enable Google Consent Mode v2 and provide Project ID (Ex.: 65ebb7949d4cb03e6e037a17).", "pixel-manager-for-woocommerce"),
            "name" => "axeptio_project_id",
            "id" => "axeptio_project_id",
            "value" => $axeptio_project_id,
            "placeholder" => __("Project ID", "pixel-manager-for-woocommerce"),
            "class" => "axeptio_project_id"
          ],[
            "type" => "switch_with_text",
            "name" => "axeptio_is_enable",
            "id" => "axeptio_is_enable",
            "value" => $axeptio_is_enable,
            "class" => "axeptio_is_enable",
            "tooltip" =>[
              "title" => __("How do I create a Axeptio Project ID?", "pixel-manager-for-woocommerce"),
              "link_title" => __("Installation Manual", "pixel-manager-for-woocommerce"),
              "link" => "https://admin.axeptio.eu/projects"
            ]
          ]
        ],
        "axeptio_cookies_version" => [    
          [
            "type" => "text",
            "label" => __("Cookies Version(optional)", "pixel-manager-for-woocommerce"),
            "note"  => __("Cookies Version", "pixel-manager-for-woocommerce"),
            "name" => "axeptio_cookies_version",
            "id" => "axeptio_cookies_version",
            "value" => $axeptio_cookies_version,
            "is_pro_featured" => true,
            "is_pro_text" => "Upgrade to Pro",
            "pro_utm_text"=> "PRO+Axeptio+Cookies+Version+Pixel+Settings",
            "placeholder" => __(".", "pixel-manager-for-woocommerce"),
            "class" => "axeptio_cookies_version",
            "tooltip" =>[
              "title" => __("String identifier of the version of Cookie configuration that should be loaded. If this parameter is omitted, then it's the \"pages\" property in the configuration that gets parsed in case of multiple cookies configurations.", "pixel-manager-for-woocommerce")
            ]
          ]
        ],
        "axeptio_setting" => [    
          [
            "type" => "axeptio_setting",
            "label" => __("Axeptio setting", "pixel-manager-for-woocommerce"),
            "class" => "axeptio_setting",
            "value" => isset($pixels_option["axeptio"])?$pixels_option["axeptio"]:[]
          ]
        ],
        "sub_section_axeptio_end" => [
          [
            "type" => "sub_section_end",
            "is_tongal" => true
          ]
        ],
        "tab_end_cookies" => [
          "type" => "tab_end"
        ],
        "button" => [
          [
            "type" => "button",
            "name" => "pixels_save",
            "id" => "pixels_save",
            "class" => "pixels_save"
          ]
        ]
      ];
      ?>
      <div class="pmw-left-wrapper">
        <?php
        $form = array("name" => "pmw-pixels", "id" => "pmw-pixels", "method" => "post", "class" => "pmw-pixels-from");
        $this->add_form_fields($fields, $form);
        
        /**
         * Sidebar
         */
        echo $this->get_sidebar_html($this->is_pro_version, $this->plan_name);
        ?>
      </div>
      <div id="pmw_privacy_popup" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
          <!-- Modal content -->
          <div class="modal-content">
            <div class="modal-header">
              <span id="close" class="close">&times;</span>
            </div>
            <div class="modal-body">
              <div class="modal-top-area">
                <div class="logo-section">
                  <div class="logo_section-img"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/wp.png"); ?>" alt="img"></div>
                  <div class="logo_section-img"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/pixel-icon.png"); ?>" alt="img"></div>
                </div>
              </div>
              <div class="modal-middle-area">
                <p><strong>Hey <?php echo esc_attr(get_bloginfo()); ?>,</strong></p>
               <p><?php echo esc_attr__('Never miss an important update - opt in to our security and feature updates notifications, and non-sensitive diagnostic tracking with', 'pixel-manager-for-woocommerce'); ?> <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/"); ?>">GrowCommerce</a></p>
                <p><a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/privacy-terms/"); ?>"><?php echo esc_attr__('Privacy & Terms', 'pixel-manager-for-woocommerce'); ?></a></p>
                <div class="modal_button-area">
                  <button class="pmw_btn pmw_btn-fill" id="pmw_accept_privecy_policy"><?php echo esc_attr__('Allow & Continue', 'pixel-manager-for-woocommerce'); ?></button>
                </div>
              </div>
              <div class="modal-bottom-area">
                <h2 class="toggle_title-text"><?php echo esc_attr__('What Permissions are being Granted?', 'pixel-manager-for-woocommerce'); ?></h2>
                <div class="pmw_slide-down-area">
                  <ul>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/icon-plugin.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Allow This Tool Only', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Only this plugin and store basic info like domain, currency, language, and country to improve functionality.', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-profile.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Your Profile Overview', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Email address', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-site-overview.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Your Site Overview', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Site URL, country, currency, WP version, PHP info', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                    <li>
                      <div class="pmw_slide-area-image"><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/Icon-notice.png"); ?>" alt="img"></div>
                      <div class="pmw_slide-area-content">
                        <span class="pmw_slide-area-title"><?php echo esc_attr__('Admin Notice', 'pixel-manager-for-woocommerce'); ?></span>
                        <p><?php echo esc_attr__('Updates, announcements, marketing, no spam', 'pixel-manager-for-woocommerce'); ?></p>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <svg version="1.1" class="svg-filters" style="display:none;">
        <defs>
          <filter id="marker-shape">
            <feTurbulence type="fractalNoise" baseFrequency="0 0.15" numOctaves="1" result="warp" />
            <feDisplacementMap xChannelSelector="R" yChannelSelector="G" scale="30" in="SourceGraphic" in2="warp" />
          </filter>
        </defs>
      </svg>
      <?php
    }
    /**
     * Page JS
     **/
    protected function page_js(){
      $gtmConfirmMessage = __("If you select \"Use Own GTM Container\" you should set up all tags, triggers, and variables in your GTM container. Continue?", "pixel-manager-for-woocommerce");
      $gtmStopConfirmMessage = __("If you select \"Stop GTM Container Loading\" the plugin will stop injecting GTM, and you must load the container outside the plugin. Continue?", "pixel-manager-for-woocommerce");
      ?>
      <script type="text/javascript">
        (function($){ 
          jQuery(document).ready(function(){
            var hash = window.location.hash;
            if(hash!= ""){
              jQuery('html, body').animate({
                  scrollTop: jQuery(hash).offset().top-200
              }, 1000);
            }
            jQuery(".pmw_show").on("click", function () {
              jQuery(this).next("#show-all-features").toggleClass("active");
              let line_title = jQuery(this).attr("data-title");
              if(jQuery(this).next("#show-all-features").hasClass('active')){
                jQuery(this).addClass("active");
                jQuery(this).html("Hide "+line_title);
              }else{
                jQuery(this).removeClass("active");
                jQuery(this).html(line_title);
              }
            });
            
            const google_form_fied_ids = [
              "google_ads_conversion_id",
              "google_ads_conversion_label",
              "google_ads_form_conversion_id",
              "google_ads_form_conversion_label"
            ];
            jQuery(".pmw_side_menu_list li").on("click", function () {
              var id = jQuery(this).attr("data-id");
              jQuery(".pmw_side_menu_list li").removeClass("active");
              jQuery(".pmw_form-wrapper").removeClass("active");              
              jQuery(this).addClass("active");
              document.getElementById(id).classList.add("active");
            });
            jQuery("#sec-pmw-pixels").toggleClass("active");
            
            /* Collapsible sub-sections (Material-like) - only for data-tongal="1" */
            jQuery('.pmw-sub-section[data-tongal="1"]').each(function(){
              var $header = jQuery(this);
              // add chevron if missing
              if($header.find('.pmw-chevron').length === 0){
                $header.append('<span class="pmw-chevron" aria-hidden="true"></span>');
              }
              // Set default state to collapsed
              var key = 'pmw_collapse_' + $header.text().trim().toLowerCase().replace(/\s+/g,'_');
              var savedState = sessionStorage.getItem(key);
              var isCollapsed = savedState === null ? true : savedState === '1';
              var $rows = $header.closest('.pmw-sub-section-row').nextUntil('.pmw-sub-section-row, .pmw-section-row');
              if(isCollapsed || savedState === null) {
                $rows.hide();
                $header.addClass('collapsed');
                if (savedState === null) {
                  sessionStorage.setItem(key, '1');
                }
              }
              // click toggle
              $header.off('click.pmwToggle').on('click.pmwToggle', function(){
                var $rows = $header.closest('.pmw-sub-section-row').nextUntil('.pmw-sub-section-row, .pmw-section-row');
                $rows.slideToggle(160);
                $header.toggleClass('collapsed');
                var collapsed = $header.hasClass('collapsed') ? '1' : '0';
                sessionStorage.setItem(key, collapsed);
              });
            });

            jQuery("#pmw-pixels .pmw_form-control").on("focus", function () {
              if( google_form_fied_ids.includes(jQuery(this).attr("id")) ){
                jQuery(this).parent().parent().addClass("active");
              }else{
                jQuery(this).parent().parent().parent().addClass("active");
              }
            });
            jQuery("#pmw-pixels .pmw_form-control").on("focusout", function (event) {
              if(jQuery(this).val() == "" && google_form_fied_ids.includes(jQuery(this).attr("id")) ){
                jQuery(this).parent().parent().removeClass("active");
              }else if(jQuery(this).val() == ""){
                jQuery(this).parent().parent().parent().removeClass("active");
              }
            });

            /*jQuery("#pmw-pixels .pmw_form-control").on("input", function (event) {
              event.preventDefault();
              if(jQuery(this).val() == "" && google_form_fied_ids.includes(jQuery(this).attr("id")) ){
                jQuery(this).parent().parent().removeClass("active");
              }else if(jQuery(this).val() == "" && ( jQuery(this).attr("id") == "fb_conversion_api_token")){
                jQuery("#fb_conversion_api_is_enable").prop('checked', false);                 
              }else if(jQuery(this).val() == "" && ( jQuery(this).attr("id") == "tiktok_conversion_api_token")){
                jQuery("#tiktok_conversion_api_is_enable").prop('checked', false);
              }else if(jQuery(this).val() == ""){
                jQuery(this).parent().parent().parent().removeClass("active");
                var id = jQuery(this).attr("id").replace("selector","is_enable").replace("project_id","is_enable").replace("id","is_enable");
                jQuery("#"+id).prop('checked', false);
              }else if(jQuery(this).val() != "" && ( jQuery(this).attr("id") == "fb_conversion_api_token")){
                //var id = jQuery(this).attr("id").replace("id","is_enable");
                jQuery("#fb_conversion_api_is_enable").prop('checked', true);                 
              }else if(jQuery(this).val() != "" && ( jQuery(this).attr("id") == "tiktok_conversion_api_token")){
                jQuery("#tiktok_conversion_api_is_enable").prop('checked', true);
              }else if(jQuery(this).val() != ""){
                var id = jQuery(this).attr("id").replace("selector","is_enable").replace("project_id","is_enable").replace("id","is_enable");
                jQuery("#"+id).prop('checked', true);                 
              }
            });*/

            jQuery(document).on('change', '#conversion_api_logs', function() {
              if (!jQuery(this).is(':checked')) {
                jQuery('#conversion_api_logs_payload').prop('checked', false);
              }
            });

            jQuery("#pmw-pixels .pmw_form-control").on("input", function (event) {
              event.preventDefault();
              const $this = jQuery(this);
              const inputId = $this.attr("id");
              const inputVal = $this.val().trim();
              const isGoogleField = google_form_fied_ids.includes(inputId);
              
              // Only include API tokens that don't follow the standard pattern
              const apiFields = {
                "fb_conversion_api_token": "fb_conversion_api_is_enable",
                "tiktok_conversion_api_token": "tiktok_conversion_api_is_enable",
                "pinterest_conversion_api_token": "pinterest_conversion_api_is_enable",
                "pinterest_conversion_api_ad_account_id": "pinterest_conversion_api_is_enable",
                "twitter_conversion_api_token": "twitter_conversion_api_is_enable",
                "snapchat_conversion_api_token": "snapchat_conversion_api_is_enable"
              };

              // Handle empty values
              if (inputVal === "") {
                if (isGoogleField) {
                  $this.parent().parent().removeClass("active");
                } else if (inputId in apiFields) {
                  jQuery("#" + apiFields[inputId]).prop('checked', false);
                } else {
                  $this.parent().parent().parent().removeClass("active");
                  const toggleId = inputId.replace(/(selector|project_id|id)/g, "is_enable");
                  jQuery("#" + toggleId).prop('checked', false);
                }
              } 
              // Handle non-empty values
              else {
                if (inputId in apiFields) {
                  jQuery("#" + apiFields[inputId]).prop('checked', true);
                } else {
                  const toggleId = inputId.replace(/(selector|project_id|id)/g, "is_enable");
                  jQuery("#" + toggleId).prop('checked', true);
                }
                
                // Update active state
                if (isGoogleField) {
                  $this.parent().parent().addClass("active");
                } else {
                  $this.parent().parent().parent().addClass("active");
                }
              }
            });

            jQuery('#pmw-pixels .pmw_form-control').each(function(){
              if(jQuery(this).val() != "" && google_form_fied_ids.includes(jQuery(this).attr("id")) ){
                jQuery(this).parent().parent().addClass("active");
              }else if(jQuery(this).val() != ""){
                jQuery(this).parent().parent().parent().addClass("active");
              }
            });

            //purchase_event_trigger_notice
            const $purchaseTriggerSelect = jQuery('#purchase_event_trigger');
            const $customHookNotice = jQuery('.purchase_event_trigger_notice');
            function toggleCustomHookNotice(){
              if(!$purchaseTriggerSelect.length || !$customHookNotice.length){
                return;
              }
              const selectedVal = $purchaseTriggerSelect.val();
              if(selectedVal === 'ptm_woocommerce_thankyou'){
                $customHookNotice.show();
              }else{
                $customHookNotice.hide();
              }
            }
            if($purchaseTriggerSelect.length && $customHookNotice.length){
              $purchaseTriggerSelect.on('change', toggleCustomHookNotice);
              toggleCustomHookNotice();
            }
            //gtm_container_load_mode
            function pmwToggleCustomGtmContainerField(isUseOwn){
              var $customRow = jQuery('.gtm_container_custom_id');
              if($customRow.length){
                $customRow.toggle(isUseOwn);
              }
            }
            
            var $gtmLoadModeSelect = jQuery('#gtm_container_load_mode');
            if($gtmLoadModeSelect.length){
              var gtmConfirmMessage = <?php echo wp_json_encode($gtmConfirmMessage); ?>;
              var gtmStopConfirmMessage = <?php echo wp_json_encode($gtmStopConfirmMessage); ?>;
              var lastGtmModeValue = $gtmLoadModeSelect.val();
              pmwToggleCustomGtmContainerField(lastGtmModeValue === 'use_own');

              $gtmLoadModeSelect.on('change', function(){
                var newValue = jQuery(this).val();

                if(newValue === 'use_own' && lastGtmModeValue !== 'use_own'){
                  var isConfirmed = window.confirm(gtmConfirmMessage);
                  if(!isConfirmed){
                    jQuery(this).val(lastGtmModeValue);
                    pmwToggleCustomGtmContainerField(lastGtmModeValue === 'use_own');
                    return;
                  }
                }else if(newValue === 'stop' && lastGtmModeValue !== 'stop'){
                  var isStopConfirmed = window.confirm(gtmStopConfirmMessage);
                  if(!isStopConfirmed){
                    jQuery(this).val(lastGtmModeValue);
                    pmwToggleCustomGtmContainerField(lastGtmModeValue === 'use_own');
                    return;
                  }
                }

                lastGtmModeValue = newValue;
                pmwToggleCustomGtmContainerField(newValue === 'use_own');

                if(newValue !== 'use_own'){
                  jQuery('#gtm_container_custom_id').val('');
                }
              });
            }

          });
        })( jQuery );
      </script>
      <?php
    }
	}
}