<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_PixelHelper
 * 
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelHelper')):	
	class PMW_PixelHelper{
		protected $options;
		protected $user_data;
		public function __construct(){
			$this->req_int();
			$this->options = $this->get_option();
		}
		public function req_int(){
			if (!function_exists('is_plugin_active')) {
			  include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}
			if (!class_exists('PMW_PixelItemFunction')) {
			  require_once('class-pixel-item-function.php');
			}
		}

		public function get_option(){
			return maybe_unserialize( get_option("pmw_pixels_option") );
		}

		/**
		 * Save converstion API logs
		 **/
		public function save_pmw_conversion_api_logs($new_log){
			// Get existing logs
			$existing_logs = $this->get_mw_conversion_api_logs();

			// Add new log to the beginning of the array
			array_unshift($existing_logs, $new_log);

			// Keep only the last 10 logs
			$logs = array_slice($existing_logs, 0, 10);

			return update_option("pmw_conversion_api_logs", maybe_serialize( $logs ));
		}

		public function get_mw_conversion_api_logs(){
			return maybe_unserialize( get_option("pmw_conversion_api_logs", []));
		}

		/**
		 * ceck pixel active 
		 */
		public function is_google_ads_conversion_enable(){
			if(isset($this->options['google_ads_conversion']) && isset($this->options['google_ads_conversion']['id'])){
				$pixel = $this->options['google_ads_conversion'];
				if(isset($pixel['id']) && isset($pixel['label']) && isset($pixel['is_enable']) && $pixel['id'] && $pixel['label'] && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}
		public function is_google_ads_enhanced_conversion_enable(){
			if(isset($this->options['google_ads_enhanced_conversion']) ){
				$pixel = $this->options['google_ads_enhanced_conversion'];
				if( isset($pixel['is_enable']) && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}

		public function is_google_ads_dynamic_remarketing_enable(){
			if(isset($this->options['google_ads_dynamic_remarketing']) && isset($this->options['google_ads_conversion']) ){
				$pixel_google_ads_conversion = $this->options['google_ads_conversion'];
				$pixel = $this->options['google_ads_dynamic_remarketing'];
				if( isset($pixel['is_enable']) && $pixel['is_enable'] && isset($pixel_google_ads_conversion['id']) && $pixel_google_ads_conversion['id']){
					return true;
				}
			}
			return false;
		}

		public function is_pixel_enable($key){
			if(isset($this->options[$key]) && isset($this->options[$key]['pixel_id'])){
				$pixel = $this->options[$key];
				if(isset($pixel['pixel_id']) && isset($pixel['is_enable']) && $pixel['pixel_id'] && $pixel['is_enable']){
					return true;
				}
			}
			return false;
		}

		public function is_send_sku(){
			if(isset($this->options['integration']['send_product_sku']) && $this->options['integration']['send_product_sku'] ){
				return true;
			}
			return false;
		}

		/*check other plugin active */
		public function is_yith_wc_brands_active() {
      return is_plugin_active('yith-woocommerce-brands-add-on-premium/init.php');
    }
    public function is_woocommerce_brands_active() {
      return is_plugin_active('woocommerce-brands/woocommerce-brands.php');
    }
    public function is_wpml_woocommerce_multi_currency_active() {
      global $woocommerce_wpml;
      if (is_plugin_active('woocommerce-multilingual/wpml-woocommerce.php') && is_object($woocommerce_wpml->multi_currency)) {
        return true;
      } else {
        return false;
      }
    }
    public function is_woocommerce_active() {
      return is_plugin_active('woocommerce/woocommerce.php');
    }

    public function get_order_total($page, $order){    	
    	if($page == "order_received"){
    		$order_total = (float) $order->get_total();
	    	if ( (isset($this->options["integration"]["exclude_tax_ordertotal"]) && $this->options["integration"]["exclude_tax_ordertotal"] ==1) ) {
					$order_total = (float) ( $order_total - $order->get_total_tax() );
				}
				if ( (isset($this->options["integration"]["exclude_shipping_ordertotal"]) && $this->options["integration"]["exclude_shipping_ordertotal"] ==1) ) {
					$order_total = (float) ( $order_total - $order->get_shipping_total() );
				}
				if ( (isset($this->options["integration"]["exclude_fee_ordertotal"]) && $this->options["integration"]["exclude_fee_ordertotal"] ==1) ) {
					$fee_total = 0;
			    // Calculate the total fees
			    foreach ($order->get_items('fee') as $fee) {
			       $fee_total += $fee->get_total();
			    }
			    $order_total = (float) ( $order_total - $fee_total );
			  }
				return $order_total;
			}
    }
	
    /**
     * Get GA4 configuration parameters
     */
    public function get_ga4_configuration() {
      $is_stop_send_user_data_ptm = isset($this->options["integration"]["stop_send_user_data_ptm"])?$this->options["integration"]["stop_send_user_data_ptm"]:false;
      return [        
        // User properties
        'user_id' => ($is_stop_send_user_data_ptm)?"":$this->get_user_id(),
        'user_properties' => ($is_stop_send_user_data_ptm)?"":$this->get_user_properties(),        
        // Traffic source
        //'campaign' => $this->get_campaign_parameters(),        
        // Custom dimensions and metrics
        //'custom_dimensions' => $this->get_custom_dimensions(),
        //'custom_metrics' => $this->get_custom_metrics()
      ];
    }

    /**
     * Get current user ID if logged in
     */
    public function get_user_id() {
      return is_user_logged_in() ? (string)get_current_user_id() : null;
    }
    
    /**
     * Get user properties
     */
    public function get_user_properties() {
      $user_props = [
        'logged_in' => is_user_logged_in() ? 'yes' : 'no',
        'role' => $this->get_user_role()
      ];
        
      if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $user_props['roles'] = $user->roles;
        $user_props['registered_date'] = $user->user_registered;
      }
        
      return apply_filters('pmw_ga4_user_properties', $user_props);
    }
    
    /**
     * Get user role
     */
    public function get_user_role() {
      if (!is_user_logged_in()) {
        return 'guest';
      }
      $user = wp_get_current_user();
      return !empty($user->roles) ? $user->roles[0] : 'subscriber';
    }
    
    /**
     * Get custom dimensions
     */
    public function get_custom_dimensions() {
      $dimensions = [];
      
      // Example: Add custom dimensions
      if (is_user_logged_in()) {
          $user = wp_get_current_user();
          $dimensions['user_registration_date'] = $user->user_registered;
      }
      
      return apply_filters('pmw_ga4_custom_dimensions', $dimensions);
    }
    /**
     * Get custom metrics
     */
    public function get_custom_metrics() {
      $metrics = [];
      
      // Example: Add custom metrics
      if (is_shop()) {
          $metrics['total_products'] = (int)wc_get_loop_prop('total');
      }
      
      return apply_filters('pmw_ga4_custom_metrics', $metrics);
    }

    /**
     * get user IP
     **/
    public function get_user_ip() {
	    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP')){
	      $ipaddress = getenv('HTTP_CLIENT_IP');
	    }else if(getenv('HTTP_X_FORWARDED_FOR')){
	      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    }else if(getenv('HTTP_X_FORWARDED')){
	      $ipaddress = getenv('HTTP_X_FORWARDED');
	    }else if(getenv('HTTP_FORWARDED_FOR')){
	      $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    }else if(getenv('HTTP_FORWARDED')){
	      $ipaddress = getenv('HTTP_FORWARDED');
	    }else if(getenv('REMOTE_ADDR')){
	      $ipaddress = getenv('REMOTE_ADDR');
	    }
	    return $ipaddress;
		}
		/*public function get_fb_event_id() {
	    $data = openssl_random_pseudo_bytes(16);
	    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
	    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
	    return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
	  }*/
	}
endif;