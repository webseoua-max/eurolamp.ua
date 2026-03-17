<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_AdminHelper')):
  require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-setting-helper.php');
  class PMW_AdminHelper extends PMW_SettingHelper{
    protected $store_id;
    protected $screen_id;
    public function __construct() {
      //$this->includes();
      add_action('init',array($this, 'init'));
      add_action('pmw_before_pixel_settings', array($this, 'pmw_before_pixel_settings'));
      $this->screen_id = isset($_GET['page'])?sanitize_text_field($_GET['page']):"";
    }
    public function init(){
      add_filter('sanitize_option_pmw_pixels_option', array($this, 'sanitize_option_pmw_general'), 10, 2);
      add_filter('sanitize_option_pmw_api_store', array($this, 'sanitize_option_pmw_general'), 10, 2);
      add_filter('sanitize_option_pmw_migration', array($this, 'sanitize_option_pmw_general'), 10, 2);
      add_filter('sanitize_option_pmw_conversion_api_logs', array($this, 'sanitize_option_pmw_general'), 10, 2);
    }
    /**
     * sanitize options fields
     **/
    public function sanitize_option_pmw_general($value, $option){
      global $wpdb;
      $value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );
      if ( is_wp_error( $value ) ) {
        $error = $value->get_error_message();
      }
      if ( ! empty( $error ) ) {
        $value = get_option( $option );
        if ( function_exists( 'add_settings_error' ) ) {
          add_settings_error( $option, "invalid_{$option}", $error );
        }
      }
      return $value;
    }
    public function pmw_before_pixel_settings($is_pro_version){
      if(!$is_pro_version){
        //$pixels_option = $this->get_pmw_pixels_option();
      }      
    }
    /**
     * Pixels options
     **/
    public function save_pmw_pixels_option($pixels_option){
      return update_option("pmw_pixels_option", serialize( $pixels_option ));
    }
    public function get_pmw_pixels_option(){
      return unserialize( get_option("pmw_pixels_option"));
    }
    /**
     * Conversion API logs
     **/
    public function get_mw_conversion_api_logs(){
      return maybe_unserialize( get_option("pmw_conversion_api_logs", []));
    }
    /**
     * Admin Notices
     **/
    public function save_pmw_admin_notices($pmw_admin_notices){
      return update_option("pmw_admin_notices", serialize( $pmw_admin_notices ));
    }
    public function get_pmw_admin_notices(){
      return unserialize( get_option("pmw_admin_notices"));
    }
    
    /**
     * API options save
     **/
    public function save_pmw_api_store($data){
      //if(pmw_is_pro_version && isset($data["plan_id"]) && $data["plan_id"] == 1){
        //$this->update_plan_paid_to_free();
      //}
      update_option("pmw_api_store", serialize( $data ));
    }

    public function get_pmw_api_store(){
      return unserialize( get_option("pmw_api_store"));
    }
    public function get_pmw_roles_list() {
      global $wp_roles;
  
      // Get all WordPress roles
      if (!isset($wp_roles)) {
          $wp_roles = new WP_Roles();
      }
  
      $roles = $wp_roles->get_names();
      return $roles;
    }
    /**
     * validate pixels function
     **/
    protected function is_facebook_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^\d{14,16}(?:,\d{14,16})*$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_pinterest_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^\d{13}$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_snapchat_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^[a-z0-9\-]*$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_bing_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^\d{7,9}$/m';
      return $this->validate_with_regex( $re, $string );
    }

    protected function is_twitter_pixel_id( $string ){
      if( empty($string) ){
        return true;
      }
      $re = '/^[a-z0-9]{5,7}$/m';
      return $this->validate_with_regex( $re, $string );
    }

    public function is_tiktok_pixel_id( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^[A-Z0-9]{20,20}$/m';
      return $this->validate_with_regex($re, $string);
    }

    /*Google ids*/
    public function is_gads_conversion_id( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^\d{8,11}$/m';
      return self::validate_with_regex($re, $string);
    }

    public function is_gads_conversion_label( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^[-a-zA-Z_0-9]{17,20}$/m';
      return self::validate_with_regex($re, $string);
    }

    public function is_google_analytics_4_measurement_id( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^G-[A-Z0-9]{10,12}$/m';
      return self::validate_with_regex($re, $string);
    }

    public function is_google_tag_id( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^(G|AW|GT)-[A-Z0-9]{6,20}$/m';
      return self::validate_with_regex($re, $string);
    }

    public function is_google_analytics_4_api_secret( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^[a-zA-Z\d_-]{18,26}$/m';
      return self::validate_with_regex($re, $string);
    }

    public function is_facebook_capi_token( $string ) {
      if (empty($string)) {
        return true;
      }
      $re = '/^[a-zA-Z\d_-]{150,250}$/m';
      return self::validate_with_regex($re, $string);
    }

    protected function validate_with_regex( string $re, $string ){
      preg_match_all( $re, $string, $matches, PREG_SET_ORDER, 0 );      
      if( isset( $matches[0] ) ){
        return true;
      }else{
        return false;
      }    
    }

    /* API store ID */
    public function get_store_id(){
      if($this->store_id != ""){
        return $this->store_id;
      }else{
        $api_store = (object)$this->get_pmw_api_store();
        return $this->store_id = isset($api_store->store_id)?$api_store->store_id:"";
      }
    }
    /* Store License Key */
    public function get_license_key($api_store = array()){
      if(isset($api_store->license_key) && $api_store->license_key){
        return $api_store->license_key;
      }else{
        $api_store = (object)$this->get_pmw_api_store();
        return isset($api_store->license_key)?$api_store->license_key:"";
      }     
    }

    public function get_price_plan_link(){
      return "https://growcommerce.io/pricings?product=pixel-tag-manager-for-woocommerce";
    }
    public function get_support_page_link(){
      return "https://growcommerce.io/support?utm_source=Plugin+WordPress+Screen&utm_medium=Support+Page&m_campaign=Upsell+at+PixelTagManager+Plugin";
    }
    public function get_pmw_website_link(){
      return "https://growcommerce.io/";
    }
    public function pmw_is_pro_version($api_store = array()){
      $plan_id = 1;
      if(isset($api_store->plan_id) && $api_store->plan_id){
        $plan_id = $api_store->plan_id;
      }else{
        $api_store = (object)$this->get_pmw_api_store();
        $plan_id = isset($api_store->plan_id)?$api_store->plan_id:1;
      }
      if($plan_id == 1){
        if( ! defined( 'pmw_is_pro_version' ) ){
          define('pmw_is_pro_version', false);
        }
        return false;        
      }else{
        if( ! defined( 'pmw_is_pro_version' ) ){
          define('pmw_is_pro_version', true);
        }
        return true;
      }
    }
    public function is_disable_pro_featured(){
      if(!pmw_is_pro_version){
        return 'disabled data-action="pmw_upgrade_pro"';
      }
    }
    public function display_proplan_with_link($btn_text = "PRO" ,$utm = "Pro+Button+Link"){
      if(!pmw_is_pro_version){
        if($btn_text ==""){$btn_text = "PRO";}
        if($utm ==""){$utm = "Pro+Button+Link";}
        echo "<a target='_blank' class='pmw_pro_paln_link' href='".esc_url_raw($this->get_price_plan_link()."&utm_source=Plugin+WordPress+Screen&utm_medium=".$utm."&m_campaign=Upsell+at+PixelTagManager+Plugin")."'>(".$btn_text.")</a>";
      }
    }
    public function get_plan_name($api_store = array()){
      if(isset($api_store->plan_name) && $api_store->plan_name){
        return isset($api_store->plan_name)?$api_store->plan_name:"FREE";
      }else{
        $api_store = (object)$this->get_pmw_api_store();
        return isset($api_store->plan_name)?$api_store->plan_name:"FREE";
      }
    }
    public function update_plan_paid_to_free(){
      $active_pixels = 0;
      $pixels_option = $this->get_pmw_pixels_option();
      $plan_id = isset($api_store->plan_id)?$api_store->plan_id:1;
      $api_store = (object)$this->get_pmw_api_store();
      $max_pixels_free = (isset($api_store->pixels_allow->max_free_pixels) && $api_store->pixels_allow->max_free_pixels >= 2 )?$api_store->pixels_allow->max_free_pixels:2;
      if( !empty($pixels_option) && $plan_id == 1 ){
        foreach($pixels_option as $key => $val){
          if(isset($api_store->pixels_allow->pixels->$key) && isset($val["is_enable"]) && $val["is_enable"] != null){
            if(isset($val["is_enable"]) && !$api_store->pixels_allow->pixels->$key->is_free){
              $pixels_option[$key]["is_enable"] = false;
            }else{
              $active_pixels++;
              if($active_pixels > $max_pixels_free){
                $pixels_option[$key]["is_enable"] = false;
              }
            }
          }
        }
        $this->save_pmw_pixels_option($pixels_option);
      }
    }
    public function validate_pixels_plan($pixels_option){
      $active_pixels = 0;
      if(!empty($pixels_option)){
        unset($pixels_option["user"]);
        unset($pixels_option["privecy_policy"]);
        /*$api_store = (object)$this->get_pmw_api_store();
        $max_pixels_free = (isset($api_store->pixels_allow->max_free_pixels) && $api_store->pixels_allow->max_free_pixels >= 3 )?$api_store->pixels_allow->max_free_pixels:3;
        $plan_id = isset($api_store->plan_id)?$api_store->plan_id:1;
        if($plan_id == 1){
          foreach($pixels_option as $key => $val){
            if( isset($val["is_enable"]) && $val["is_enable"] != null){
              if(isset($api_store->pixels_allow->pixels->$key) && isset($val["is_enable"]) && !$api_store->pixels_allow->pixels->$key->is_free){
                return array("error" => true, "message" => __("Free plan  ".str_replace("_"," ", $key)." not allow.", "pixel-manager-for-woocommerce"));
                break;
              }else{
                $active_pixels++;
                if($active_pixels > $max_pixels_free){
                  return array("error" => true, "message" => __("Free plan max ".$max_pixels_free." pixels allow.", "pixel-manager-for-woocommerce"));
                  break;
                }  
              }          
            }
          }
        }*/
      }
    }

    public function pmw_is_enable_ga3_or_ga4($pixels_option = array()){
      if(!empty($pixels_option)){
        if( isset($pixels_option['google_analytics_4_pixel']['is_enable']) && $pixels_option['google_analytics_4_pixel']['is_enable'] ){
          return true;
        }else{
          return false;
        }
      }
    }

    public function pmw_display_admin_notices(){
      $max_limite = 1; //max limite to display
      $notices = $this->get_pmw_admin_notices();
      $last_hide_notice_date = isset($notices["last_hide_notice_date"])?$notices["last_hide_notice_date"]:"";
      if(!empty($notices)){
        $i = 1;
         foreach($notices as $key => $notice){
            if(isset($notice["is_active"]) && isset($notice["html"]) && $notice["is_active"] && $notice["html"] && $i <= $max_limite && ($last_hide_notice_date == "" || $last_hide_notice_date < date("Ymd")) ){
              echo wp_kses_post($notice["html"]);
              $i++;
            }
         }
        ?>
        <script type="text/javascript">
          (function( $ ) {
            jQuery( function() {
              jQuery(".pmw-admin-notice").on( 'click', '.notice-dismiss', function( event ) {
                event.preventDefault();
                var notice_id = jQuery(this).parent().attr("id");
                jQuery.ajax({
                  type: "POST",
                  dataType: "json",
                  url: pmw_ajax_url,
                  data:{
                    action: "pmw_notice_dismiss",
                    notice_id:notice_id, 
                    pmw_ajax_nonce : "<?php echo wp_create_nonce('pmw_ajax_nonce'); ?>"
                  }
                },function( response ){                            
                });
              });
            });
          })( jQuery );
        </script>
        <?php
      }
    }
    public function pmw_notice_dismiss(){
      $ajax_nonce = isset($_POST["pmw_ajax_nonce"])?sanitize_text_field($_POST["pmw_ajax_nonce"]):"";
      if($this->admin_safe_ajax_call($ajax_nonce, 'pmw_ajax_nonce')){
        $notice_id = isset($_POST["notice_id"])?sanitize_text_field($_POST["notice_id"]):"";
        $notices = $this->get_pmw_admin_notices();
        if(isset($notices[$notice_id])){
          $notices[$notice_id]["is_active"] = false;
          $notices["last_hide_notice_date"] = date("Ymd");
          $this->save_pmw_admin_notices($notices);
        }
        echo wp_send_json( array("error" => false, 'message'=> "success") );
        exit;
      }else{
        echo wp_send_json( array("error"=>true, 'message'=> __("Your admin nonce is not valid.", "pixel-manager-for-woocommerce")) );
        exit;
      }
    }

    public function pmw_add_admin_notices(){
      $notice_css = '
        /* Custom CSS for WordPress Admin Notice */
        .pmw-admin-notice{
          border-left: 4px solid #0073aa; 
          display: flex;
          align-items: center;
          padding: 10px;
        }
        .pmw-admin-notice img {
            max-width: 120px; /* Adjust the image width as needed */
            margin-right: 20px; /* Add spacing between image and text */
        }
        .pmw-admin-notice h3 {
            font-size: 20px;
            margin: 0;
            font-weight: bold;
        }
        .pmw-admin-notice span.text {
            line-height: 1.4;
            display: block;
            font-size: 16px;
            margin-top: 8px;
        }
        .pmw-admin-notice span.small-text {
            line-height: 1.3;
            display: inline-flex;
            font-size: 13px;
            margin: 0;
        } .pmw-admin-notice a.offer-btn{
          display: block;
          text-align: center;
          background: #2271b1;
          color: #fff;
          max-width: 133px;
          border-radius: 10px;
          padding: 7px 5px;
          margin-top: 5px;
          text-decoration: none;
        }
        .pmw-release-features{
          margin: 8px 0 12px 18px;
          font-size: 13px;
          line-height: 1.6;
          padding-left: 12px;
        }
        .pmw-release-features li{
          margin-bottom: 4px;
          list-style: disc;
        }
        .pmw-release-note{
          margin: 0 0 12px;
          font-size: 13px;
          font-weight: 600;
          color: #c2410c;
        }';
      wp_add_inline_style('wp-admin', $notice_css);
      $notices = $this->get_pmw_admin_notices();
      if(empty($notices)){
        $notices = array();
      }
      $pixels_option = $this->get_pmw_pixels_option();

      /*** Mega Release announcement ***/
      $notice_id = "mega_release_2025";
      if(!isset($notices[$notice_id])){
        $feature_list = array(
          esc_html__('Google Tag: Easily add and manage your Google Tag with choose options to use it for GA4, Google Ads, or both.', 'pixel-manager-for-woocommerce'),
          esc_html__('Enhanced Facebook Conversion API tracking.', 'pixel-manager-for-woocommerce'),
          esc_html__('Added TikTok Conversion API tracking.', 'pixel-manager-for-woocommerce'),
          esc_html__('Added Pinterest Conversion API tracking.', 'pixel-manager-for-woocommerce'),
          esc_html__('Advanced Matching data now sent to Facebook, TikTok, Twitter, Bing, and Pinterest.', 'pixel-manager-for-woocommerce'),
          esc_html__('Enable and desable Advanced Matching data sent option.', 'pixel-manager-for-woocommerce'),
          esc_html__('Added Control to manage the Thank You page purchase event triggers.', 'pixel-manager-for-woocommerce'),
          esc_html__('View the latest 10 Conversion API logs directly inside the plugin.', 'pixel-manager-for-woocommerce'),
        );
        $feature_html = '<ul class="pmw-release-features">';
        foreach ($feature_list as $feature_text) {
          $feature_html .= '<li>'.$feature_text.'</li>';
        }
        $feature_html .= '</ul>';
        $html = sprintf(
          '<div style="color:#1d2327;">
            <p style="margin:0; font-size:16px; font-weight:600;">%1$s</p>
            <p style="margin:8px 0 10px; font-size:14px; line-height:1.5;">%2$s</p>
            %3$s
            <p class="pmw-release-note">%4$s</p>
            <a class="button button-primary" style="text-decoration:none;" href="%5$s">%6$s</a>
          </div>',
          esc_html__('🎉 Mega Release: Pixel Tag Manager 2.1 Highlights', 'pixel-manager-for-woocommerce'),
          esc_html__('Here’s what shipped in this release:', 'pixel-manager-for-woocommerce'),
          $feature_html,
          esc_html__('We enhanced GTM containers and in-plugin tracking—review the updates and verify your setup. Reach out if you need any help.', 'pixel-manager-for-woocommerce'),
          esc_url(admin_url('admin.php?page=pixel-manager')),
          esc_html__('Open Pixel Manager settings', 'pixel-manager-for-woocommerce')
        );
        $notices[$notice_id] = array(
          "is_active" => true,
          "created_at" => current_time('Y-m-d'),
          "html" => $this->pmw_add_admin_notice_html("notice-info", $html, $notice_id)
        );
      }
           
      /*** GrowInsights360 ***/
      $notice_id = "growinsights360";
      if(!is_array($notices)){
        $notices = array();
      }
      if(!isset($notices[$notice_id])){
        $html = '
          <div>
            <p style="margin:0; font-size:16px; font-weight:600; color:#1d2327;">
              🚀 GrowInsights360 – All-in-one GA4 Analytics Dashboard
            </p>
            <p style="margin:8px 0 12px; font-size:14px; color:#1d2327; line-height:1.5;">
              New feature in the Pixel Tag Manager plugin: Connect your Google Analytics 4 account securely with Google OAuth, monitor traffic, conversions, user behavior, and gain powerful insights reports to grow your business – all in one dashboard.
            </p>
            <a class="button button-primary" href="admin.php?page=pixel-manager-growinsights360" style="text-decoration:none;">
              👉 Open GrowInsights360 Dashboard
            </a>
          </div>';
        $notices[$notice_id] = array(
          "is_active" => true,
          "created_at" => "2025-08-20",
          "html" => $this->pmw_add_admin_notice_html("notice-info", $html, $notice_id)
        );
      }

      //****Offer 1 ****
      $notice_id = "offer_freevspro";
      if( !isset($notices[$notice_id]) && !pmw_is_pro_version ){        
        $html = '<img src="'.esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/pro.jpg").'" alt="Offer Image"><div><h3>Pixel Tag Manager - FREE VS PRO</h3><span class="text">The FREE Plan includes basic tracking. Upgrade to the PRO Plan to unlock comprehensive eCommerce event tracking.</span><span class="text">Get started with just $9.</span><a class="offer-btn" target="_blank" href="'.esc_url_raw($this->get_price_plan_link()).'&utm_source=Plugin+WordPress+Notice&utm_medium=Notice+Explore+Offers+Button&m_campaign=Upsell+at+PixelTagManager+Plugin">FREE VS PRO Version</a></div>';
        $notices[$notice_id] = array(
          "is_active" => true,
          "created_at" => "2023-09-03",
          "html" => $this->pmw_add_admin_notice_html("notice-info", $html, $notice_id)
        );
      }

      //****Review ****
      $notice_id = "wp_ptm_review";
      if( !isset($notices[$notice_id]) ){        
        $html = '<div><br><h3>Is the Pixel Tag Manager working as you desire?</h3><span class="text">We’d really appreciate your feedback on Pixel Tag Manager. Your insights can help us make it even better!</span><a class="offer-btn" target="_blank" href="'.esc_url_raw("https://wordpress.org/support/plugin/pixel-manager-for-woocommerce/reviews/").'">Share Your Experience</a></div>';
        $notices[$notice_id] = array(
          "is_active" => true,
          "created_at" => "2023-09-03",
          "html" => $this->pmw_add_admin_notice_html("notice-info", $html, $notice_id)
        );
      }
      /* GA4*/
      $notice_id = "welcome_ga";
      if( !isset($notices[$notice_id]) && !$this->pmw_is_enable_ga3_or_ga4($pixels_option) ){
        $html = 'Start eCommerce tracking with Google Analytics 4 (GA4), Google Ads Conversion, Enhanced Conversions, Dynamic Remarketing and more!! <a href="admin.php?page=pixel-manager"><b><u>Enable</u></b></a>';
        $notices[$notice_id] = array(
          "is_active" => true,
          "created_at" => "2023-02-11",
          "html" => $this->pmw_add_admin_notice_html("notice-info", $html, $notice_id)
        );
      }
           
      $this->save_pmw_admin_notices($notices);
    }

    public function pmw_add_admin_notice_html($type_class ,$html, $html_id){
      return '<div id="'.$html_id.'" class="notice pmw-admin-notice is-dismissible '.$type_class.' '.$html_id.'"><p>'.$html.'</p></div>';
    }

    public function get_plan_features_html(){
      ob_start();
      ?>
      <li><?php esc_attr_e('GrowInsights360 GA4 Dashboard.','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Google Analytics 4 Tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Google Ads Enhanced Conversion Tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Facebook Pixel Tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Pinterest Pixel Tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('TikTok Ads Pixel tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Microsoft Ads Pixel (Bing Ads Pixel)','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Snapchat Pixel Tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Twitter Ads Pixel tracking','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Conversion API for Facebook, TikTok, Pinterest','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Advanced Matching data is now sent to Facebook, TikTok, Twitter, Bing, and Pinterest','pixel-manager-for-woocommerce'); ?></li>
      <li><?php esc_attr_e('Premium Support','pixel-manager-for-woocommerce'); ?></li>
      <?php
      return ob_get_clean();
    }
    public function get_plan_features_limited_detailshtml(){
      ob_start();?>
      <td><span class="free plan-yes"></span>(<?php esc_attr_e('Limited','pixel-manager-for-woocommerce'); ?>)</td>
      <td><span class="paid1-plan-yes"></span></td>
      <td><span class="paid2-plan-yes"></span></td>
      <td><span class="paid3-plan-yes"></span></td>
      <?php
      return ob_get_clean();
    }
    public function get_plan_features_limited_allhtml(){
      ob_start();?>
      <td><span class="free plan-yes"></span>(<?php esc_attr_e('Limited','pixel-manager-for-woocommerce'); ?>)</td>
      <td><span class="free plan-yes"></span>(<?php esc_attr_e('Limited','pixel-manager-for-woocommerce'); ?>)</td>
      <td><span class="free plan-yes"></span>(<?php esc_attr_e('Limited','pixel-manager-for-woocommerce'); ?>)</td>
      <td><span class="free plan-yes"></span>(<?php esc_attr_e('Limited','pixel-manager-for-woocommerce'); ?>)</td>
      <?php
      return ob_get_clean();
    }
    public function get_sidebar_html($is_pro_version, $plan_name){
      ob_start();
      ?>
      <div class="pmw_right_sidebar <?php echo (!$is_pro_version)?"pmw_right_sidebar-bg":""; ?>" id="pmw_right_sidebar">
        <div>
          <div class="pmw-sec-1 pmw-pro-plan-sell-box mb-3">
            <ul>
              <li><?php esc_attr_e("Your plan ",'pixel-manager-for-woocommerce'); ?><a class="pmw-pro-bg" href="<?php echo esc_url_raw("admin.php?page=pixel-manager-account"); ?>"><?php echo esc_attr($plan_name); ?></a></li>
            </ul>
          </div>
          <div class="pmw-sec-1 pmw-pro-plan-sell-box">
            <ul>
              <li><?php esc_attr_e("You're using the FREE plan, no license is needed if use only free features. Enjoy! :)","pixel-manager-for-woocommerce"); ?></li>
              <li><strong><?php esc_attr_e('To unlock complet eCommerce pixels tracking access and enhanced features with using Google Tag Manager','pixel-manager-for-woocommerce'); ?></strong>
                <a class="pmw-plan_link-btn" href="<?php echo esc_url_raw($this->get_price_plan_link());?>&utm_source=Plugin+WordPress+Screen&utm_medium=Sidebar+Upgrade+to+Pro&m_campaign=Upsell+at+PixelTagManager+Plugin" target="_blank"><?php esc_attr_e('Upgrade to Pro','pixel-manager-for-woocommerce'); ?></a>
                <a class="pmw-trywithfree" href="<?php echo esc_url_raw($this->get_price_plan_link());?>&utm_source=Plugin+WordPress+Screen&utm_medium=Sidebar+Enjoy+up+to+45&m_campaign=Upsell+at+PixelTagManager+Plugin" target="_blank"><?php esc_attr_e('See are plans!','pixel-manager-for-woocommerce'); ?></a>
              </li>
            </ul>
          </div>
          
          <div class="sidebar-img">
            <div class="pmw-christmas-offer">
              <p><span class="pmw-christmas-offer__badge"><?php esc_attr_e('Christmas Special','pixel-manager-for-woocommerce'); ?></span></p>
              <h4>BUSINESS YEARLY</h4>
              <h3><del>$49</del><strong> $39/ year</strong></h3>
              <h4>ENTERPRISE YEARLY</h4>
              <h3><del>$149</del><strong> $99/ year</strong></h3>
              <p><?php esc_attr_e('Enjoy an extra 20% - 35% Christmas discount—automatically applied to the Business yearly plan.','pixel-manager-for-woocommerce'); ?></p>
              <a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/checkout/?product=pixel-tag-manager-for-woocommerce&plan=172&utm_source=Plugin+WordPress+Screen&utm_medium=FreeVsPro+BUSINESS+Yearly+Christmas&m_campaign=Christmas+Offer"); ?>" class="pmw_btn pmw-btn-christmas">
                <?php esc_attr_e('Unlock the savings','pixel-manager-for-woocommerce'); ?>
              </a>
            </div>
          </div>
          <div class="pmw-sec-2">
            <ul>
              <li><a target="_blank" href="<?php echo esc_url_raw("https://growcommerce.io/docs/pixel-manager-for-woocommerce.pdf"); ?>" class="pmw_link-list-link"><?php echo esc_attr__('Installation Manual', 'pixel-manager-for-woocommerce'); ?></a></li>
              <li><?php echo esc_attr__('( Need help? Email support@growcommerce.io )', 'pixel-manager-for-woocommerce'); ?></li>
            </ul>
          </div>          
        </div>
      </div>
      <?php
      return ob_get_clean();
    }

    public function pmw_pixels_license_key_check($license_key){
      $PMW_API = new PMW_AdminAPIHelper();
      $license_key = sanitize_text_field($license_key);
      $status = "0";
      if($license_key != ""){
        $fields = array(
          "license_key" => $license_key
        );
        $store_id = $this->get_store_id();
        if($store_id != ""){          
          $data = array(
            "store_id" => sanitize_text_field($store_id),
            "website" => esc_url_raw(get_site_url()),
            "license_key" => $fields["license_key"],
            "status" => $status
          );
          $args = array(
            'timeout' => 10000,
            'headers' => array(
              'Authorization' => "Bearer PMDZCXJL==",
              'Content-Type' => 'application/json'
            ),
          'body' => wp_json_encode($data)
          );
          $PMW_API->pmw_api_call("license/update", $args);
          $PMW_API->update_store_api_data();          
        }
      }
    }    
  }
endif;
new PMW_AdminHelper();