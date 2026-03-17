<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_AdminAPIHelper')):
  class PMW_AdminAPIHelper{
    protected $PMW_AdminHelper;
    public function __construct() {
      if(class_exists( 'PMW_AdminHelper' )){
        $this->PMW_AdminHelper = new PMW_AdminHelper();
      }
      //$this->includes();
      //add_action('admin_init',array($this, 'init'));
    }
    public function includes(){
    }
    public function init(){      
    }
    /**
     * API call function
     **/
    public function pmw_api_call( string $end_point, array $args ){
      try {
        if( !empty($args) && $end_point ){ 
          $url = PMW_API_URL.$end_point;
          $args['timeout']= "1000";
          $request = wp_remote_post(esc_url_raw($url), $args);
          return json_decode(wp_remote_retrieve_body($request));
        }
      } catch (Exception $e) {
        return $e->getMessage();
      }
    }

    protected function sanitize_pixels_option(array $pixels_option){
      $sensitive_keys = array(
        'fb_conversion_api' => array('api_token','test_event_code'),
        'tiktok_conversion_api' => array('api_token'),
        'pinterest_conversion_api' => array('api_token'),
        'twitter_conversion_api' => array('api_token'),
        'snapchat_conversion_api' => array('api_token'),
        'google_tag' => array('id'),
        'google_analytics_4_pixel' => array('pixel_id'),
        'facebook_pixel' => array('pixel_id'),
        'pinterest_pixel' => array('pixel_id'),
        'snapchat_pixel' => array('pixel_id'),
        'bing_pixel' => array('pixel_id'),
        'twitter_pixel' => array('pixel_id'),
        'tiktok_pixel' => array('pixel_id'),
        'google_ads_conversion' => array('id','label'),
        'google_ads_form_conversion' => array('id','label','selector'),
       
      );
      foreach($sensitive_keys as $key => $fields){
        if($key === 'generate_lead_from'){
          if(isset($pixels_option[$key])){
            unset($pixels_option[$key]);
          }
          continue;
        }
        if(isset($pixels_option[$key])){
          if($fields === true){
            unset($pixels_option[$key]);
            continue;
          }
          foreach($fields as $field){
            if(isset($pixels_option[$key][$field])){
              unset($pixels_option[$key][$field]);
            }
          }
        }
      }
      return $pixels_option;
    }

    public function get_product_data(array $pixels_option= array(), $product_status = "1"){
      $product_data = array();
      if(empty($pixels_option) && class_exists( 'PMW_AdminHelper' ) ){
        $pixels_option = $this->PMW_AdminHelper->get_pmw_pixels_option();
      }else if(!class_exists( 'PMW_AdminHelper' )){
        $pixels_option =  unserialize( get_option("pmw_pixels_option"));
      }
      $pixels_option = $this->sanitize_pixels_option(is_array($pixels_option)?$pixels_option:array());
      return array(
        "settings" => $pixels_option,
        "status" => $product_status,
        "version" => PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION,
        "domain" => esc_url_raw(get_site_url()),
        "update_date" => date("Y-m-d")
      );
    }

    public function save_product_store( $pixels_option = array(), $product_status = "1"){
      if(empty($pixels_option) && class_exists( 'PMW_AdminHelper' ) ){
        $pixels_option = $this->PMW_AdminHelper->get_pmw_pixels_option();
      }else if(!class_exists( 'PMW_AdminHelper' )){
        $pixels_option =  unserialize( get_option("pmw_pixels_option"));
      }
      if(empty($pixels_option)){
        return;
      }

      //$current_user = wp_get_current_user();
      $country_data = get_option('woocommerce_default_country');
      $country_data_array = array();
      if($country_data){
        $country_data_array = explode(":", $country_data);
      }
      $store_data = array(
        'store_info' => array(
          'country_code' => (isset($country_data_array[0]))?$country_data_array[0]:$country_data,
          'state_code' => (isset($country_data_array[1]))?$country_data_array[1]:"",
          'is_multisite' => is_multisite(),
          'currency_code' => get_option('woocommerce_currency'),
          'language_code' => get_locale()
        )
      );

      $data = array(
        "email" => sanitize_email($pixels_option['user']['email_id']),
        //"first_name" => "",
        //"last_name" => "",
        "website" => esc_url_raw(get_site_url()),            
        "product_id" => ( defined( 'PMW_PRODUCT_ID' ) )?PMW_PRODUCT_ID:2,
        "store_data" => $store_data,
        "product_data" => $this->get_product_data($pixels_option, $product_status)
      );

      $args = array(
        'timeout' => 10000,
        'headers' => array(
          'Authorization' => "Bearer PMDZCXJL==",
          'Content-Type' => 'application/json'
        ),
      'body' => wp_json_encode($data)
      );
      return $this->pmw_api_call("store/save", $args);
    }
    public function update_store_api_data(){
      $store_id = $this->PMW_AdminHelper->get_store_id();
      if($store_id != ""){
        $data = array(
          "store_id" => sanitize_text_field($store_id),
          "website" => esc_url_raw(get_site_url())
        );
        $args = array(
          'timeout' => 10000,
          'headers' => array(
            'Authorization' => "Bearer PMDZCXJL==",
            'Content-Type' => 'application/json'
          ),
        'body' => wp_json_encode($data)
        );
        $api_rs = $this->pmw_api_call("store/get", $args);
        if (isset($api_rs->error) && $api_rs->error == '' ) {
          $this->PMW_AdminHelper->save_pmw_api_store((array)$api_rs->data);
        }
      }
    }
  }
endif;