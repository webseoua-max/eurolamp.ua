<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       
 * @since      1.0.0
 *
 * @package    
 * @package    PMW_Pixel
 * PixelManagerDataLayer, PixelManagerOptions
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

if(!class_exists('PMW_PixelManager')):
  class PMW_PixelManager extends PMW_Pixel {
    protected $options;
    protected $PixelItemFunction;
    public $PixelManagerDataLayer = array();
    protected $click_ids = [];
    public $PTMUserData;
    protected $version;
    protected $purchase_event_trigger;
    protected $PMW_AdminHelper;
    protected $api_store;
    protected $is_send_sku;
    protected $is_stop_send_user_data_ptm;
    public function __construct( $options ){
      $this->version = PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION;
      $this->options = $options;
      $this->req_int();
      $this->collect_click_ids();
      $this->PMW_AdminHelper = new PMW_AdminHelper();
      $this->api_store = (object)$this->PMW_AdminHelper->get_pmw_api_store();
      $this->PixelItemFunction = new PMW_PixelItemFunction();
      $this->is_send_sku = $this->is_send_sku();
      $this->is_stop_send_user_data_ptm = isset($this->options["integration"]["stop_send_user_data_ptm"]) ? $this->options["integration"]["stop_send_user_data_ptm"] : false;
      if(!$this->is_stop_send_user_data_ptm) {
        $this->PTMUserData = $this->get_user_data();
      }

      $this->purchase_event_trigger = isset($this->options["tracking"]["purchase_event_trigger"]) ? $this->options["tracking"]["purchase_event_trigger"] : "url_based";
      add_action( 'wp_head', array( $this, 'init_in_wp_head') , 120);

      if($this->is_woocommerce_active()){
        add_action("wp_footer", array($this, "PMW_create_datalayer_object"));        
      }else{
        add_action("wp_footer", array($this, "PMW_JS_Call"));
      }

      if($this->purchase_event_trigger != "url_based"){
        add_action($this->purchase_event_trigger, array($this, "PMW_woocommerce_inject_data_layer_product_thankyou"));
      }

      add_action( 'wp_enqueue_scripts', array($this,'enqueue_scripts'));
      add_action('wp_ajax_pmw_process_conversion_events', array($this, 'process_conversion_events'));
      add_action('wp_ajax_nopriv_pmw_process_conversion_events', array($this, 'process_conversion_events'));
    }

    public function req_int(){
      if (!class_exists('PMW_PixelItemFunction')) {
        require_once('class-pixel-item-function.php');
      }
      if (!class_exists('PMW_AdminHelper')) {
        require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-helper.php');
      }
    }

    /**
     * Sanitize Facebook payload recursively before sending to Conversion API
     */
    private function sanitize_payload_data(array $payload) {
      foreach ($payload as $key => $value) {
        if (is_array($value)) {
          $payload[$key] = $this->sanitize_payload_data($value);
          continue;
        }

        if (is_string($value)) {
          if ('event_source_url' === $key) {
            $payload[$key] = esc_url_raw($value);
          } else {
            $payload[$key] = sanitize_text_field($value);
          }
        } elseif (is_float($value)) {
          $payload[$key] = floatval($value);
        } elseif (is_int($value)) {
          $payload[$key] = intval($value);
        } elseif (is_bool($value)) {
          $payload[$key] = (bool)$value;
        }
      }

      return $payload;
    }

    public function enqueue_scripts() {
      wp_enqueue_script("pmw-pixel-manager.js", esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL . '/admin/pixels/js/pixel-manager.js'), array('jquery'), $this->version, false);
    }

    public function init_in_wp_head(){
      $this->inject_option_data_layer();     
      $this->inject_gtm_data_layer();
      if($this->is_woocommerce_active() && $this->purchase_event_trigger == "url_based"){ 
        $this->PMW_woocommerce_inject_data_layer_product();
      }      
    }

    public function inject_gtm_data_layer(){
      $gtm_container_load_mode = isset($this->options["gtm_container"]["load_mode"]) ? $this->options["gtm_container"]["load_mode"] : "default_ptm";
      $gtm_container_custom_id = isset($this->options["gtm_container"]["custom_container_id"]) ? $this->options["gtm_container"]["custom_container_id"] : "";

      $gtm_container_id = "GTM-P3DXNCNZ";
      if($gtm_container_load_mode == "use_own" && $gtm_container_custom_id != ""){
        $gtm_container_id = $gtm_container_custom_id;
      }elseif($gtm_container_load_mode == "default_ptm"){
        if(isset($this->options["axeptio"]["project_id"]) && isset($this->options["axeptio"]["is_enable"]) && $this->options["axeptio"]["project_id"] != "" && $this->options["axeptio"]["is_enable"]){
          $gtm_container_id = "GTM-58V46ZS3";
        }
      }
      if($gtm_container_load_mode != "stop" && $gtm_container_id != ""){
      ?><!-- Google Tag Manager -->
<script>let ptm_gtm_container_id = '<?php echo esc_attr($gtm_container_id); ?>'; (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',ptm_gtm_container_id);
document.addEventListener('DOMContentLoaded', function () {
  var noscriptElement = document.createElement('noscript');
  // Create a new iframe element for the GTM noscript tag
  var iframeElement = document.createElement('iframe');
  iframeElement.src = 'https://www.googletagmanager.com/ns.html?id='+ptm_gtm_container_id;
  iframeElement.height = '0';
  iframeElement.width = '0';
  iframeElement.style.display = 'none';
  // Append the iframe to the noscript element
  noscriptElement.appendChild(iframeElement);
  // Append the noscript element to the body
  document.body.insertBefore(noscriptElement, document.body.firstChild);
});
</script>
<!-- End Google Tag Manager -->
      <?php
      }
    }
    /*setting DataLayer */
    public function inject_option_data_layer(){
      unset($this->options["privecy_policy"]);
      unset($this->options["user"]);
      $set_options = $this->options;
      $set_options["version_free"] = true;
      $set_options["version"] = $this->version;
      $api_tokens = array(
        'fb_conversion_api' => 'api_token',
        'tiktok_conversion_api' => 'api_token',
        'pinterest_conversion_api' => 'api_token',
        'twitter_conversion_api' => 'api_token',
        'snapchat_conversion_api' => 'api_token'
      );
      foreach($api_tokens as $integration => $token_key){
        if(isset($set_options[$integration][$token_key])){
          unset($set_options[$integration][$token_key]);
        }
      }
      $EventOptions = array(
        "time" => strtotime("now")
      );
      ?>
    <script type="text/javascript" data-pagespeed-no-defer data-cfasync="false">
      var pmw_f_ajax_url = '<?php echo esc_url_raw(admin_url( 'admin-ajax.php' )); ?>';
      window.PixelManagerOptions = window.PixelManagerOptions || [];
      window.PixelManagerOptions = <?php echo json_encode($set_options); ?>;
      window.PixelManagerEventOptions = <?php echo json_encode($EventOptions); ?>;
    </script>
      <?php
    }

    public function PMW_JS_Call(){
      ?>
      <script type="text/javascript" data-pagespeed-no-defer data-cfasync="false">
        window.addEventListener('load', call_view_wordpress_js,true);
        function call_view_wordpress_js(){              
          var PMW_JS = new PMW_PixelManagerJS("", false, false);
        }        
      </script>
      <?php
    }

    /**
     * Collect click IDs from various platforms
     */
    protected function collect_click_ids() {
        $click_sources = [
            // Google Click ID - Used for Google Ads (Search, Display, YouTube, etc.)
            'gclid' => [
                'param' => 'gclid',
                'type' => 'google',
                'description' => 'Google Ads click identifier for web traffic'
            ],
            // Facebook Click ID - Used for tracking Facebook ad clicks
            'fbclid' => [
                'param' => 'fbclid',
                'type' => 'facebook',
                'description' => 'Facebook click identifier for ad tracking'
            ],
            // Microsoft Click ID - Used for Microsoft Advertising (Bing Ads)
            'msclkid' => [
                'param' => 'msclkid',
                'type' => 'microsoft',
                'description' => 'Microsoft Advertising click identifier'
            ],
            // TikTok Click ID - Used for TikTok ad tracking
            'ttclid' => [
                'param' => 'ttclid',
                'type' => 'tiktok',
                'description' => 'TikTok click identifier for ad attribution'
            ],
            // Display & Video 360 Click ID - Google's Display & Video 360 platform
            'dclid' => [
                'param' => 'dclid',
                'type' => 'display',
                'description' => 'Google Display & Video 360 click identifier'
            ],
            // Google Click ID for web-to-app - Used for app install campaigns
            'gbraid' => [
                'param' => 'gbraid',
                'type' => 'google',
                'description' => 'Google Ads click identifier for web-to-app traffic'
            ],
            // Google Click ID for web-to-web - Enhanced measurement for web traffic
            'wbraid' => [
                'param' => 'wbraid',
                'type' => 'google',
                'description' => 'Google Ads click identifier for enhanced web measurement'
            ],
            // Pinterest Click ID - Used for Pinterest ad tracking
            'epik' => [
                'param' => 'epik',
                'type' => 'pinterest',
                'description' => 'Pinterest click identifier for ad tracking and attribution'
            ]
        ];

        foreach ($click_sources as $key => $source) {
            if (!empty($_GET[$source['param']])) {
                $this->click_ids[$key] = [
                    'id' => sanitize_text_field($_GET[$source['param']]),
                    'type' => $source['type'],
                    'timestamp' => time()
                ];
            }
        }

        // Check for UTM parameters
        $utm_sources = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
        foreach ($utm_sources as $utm) {
            if (!empty($_GET[$utm])) {
                $this->click_ids[$utm] = sanitize_text_field($_GET[$utm]);
            }
        }
    }

    /**
     * Get all collected click IDs
     */
    public function get_click_ids() {
        return $this->click_ids;
    }

    /**
     * print PixelManagerDataLayer call in footer
     **/
    public function PMW_create_datalayer_object(){
      // Add click IDs to data layer
      if (!empty($this->click_ids)) {
        $this->PixelManagerDataLayer['click_ids'] = $this->click_ids;
      }
      $PTMGA4ConfigurationParams = $this->get_ga4_configuration();

      $this->PixelManagerDataLayer['currency'] = get_woocommerce_currency();
      ?>
      <script type="text/javascript" data-pagespeed-no-defer data-cfasync="false">
        window.PixelManagerDataLayer = window.PixelManagerDataLayer || [];
        window.PixelManagerDataLayer.push({data:<?php echo json_encode($this->PixelManagerDataLayer); ?>});
        window.PTMUserData = <?php echo (!$this->is_stop_send_user_data_ptm) ? json_encode($this->PTMUserData) : 'null'; ?>;
        
        window.PTMGA4ConfigurationParams = <?php echo json_encode($PTMGA4ConfigurationParams); ?>;
        /**
         * start GTM for Google Analytics with GTM
         **/
        window.addEventListener('load', call_ga4_data_layer,true);
        function call_ga4_data_layer(){ 
          var PMW_JS = new PMW_PixelManagerJS();
        }
      </script>
      <?php      
    }

    /**
     * inject data layer in product thank you page
     **/
    public function PMW_woocommerce_inject_data_layer_product_thankyou($order_id){
      $order = "";
      if(empty($order_id)){
        $order = $this->PixelItemFunction->get_order_from_order_received_page();
        return;
      }else{
        $order = wc_get_order($order_id);
      }
      if(!$order){
        return;
      }

      if(!isset($this->PixelManagerDataLayer['checkout']) || !is_array($this->PixelManagerDataLayer['checkout'])){
        $this->PixelManagerDataLayer['checkout'] = [];
      }

      $this->PixelManagerDataLayer['checkout']['cart_product_list'] = [];

      $order_items = $order->get_items();
      if(!empty($order_items)){
        foreach((array)$order_items as $order_item){
          $product_id = $this->PixelItemFunction->get_variation_id_or_product_id($order_item->get_data(), true);
          $product = wc_get_product( $product_id );
          if(!$product){
            continue;
          }
          $data = $this->PixelItemFunction->get_product_details_for_datalayer($product, "", $this->is_send_sku);
          $data['quantity'] = (int)$order_item['quantity'];
          $this->PixelManagerDataLayer['checkout']['cart_product_list'][$product_id] = $data;
        }
      }

      $coupon_codes = $order->get_coupon_codes();
      $coupon = (is_array($coupon_codes) && !empty($coupon_codes)) ? $coupon_codes[0] : "";
      $display_order_id = $order->get_order_number();
      $order_data = array(
        "id"              => $display_order_id,
        "total"           => number_format((float)$this->get_order_total("order_received", $order),2,'.',''),
        "discount"        => number_format((float)$order->get_total_discount(),2,'.',''),
        "tax"             => number_format((float)$order->get_total_tax(),2,'.',''),
        "shipping"        => number_format((float)$order->get_total_shipping(),2,'.',''),
        "coupon"          => $coupon,
        "currency"        => $order->get_currency(),
        "payment_method"  => $order->get_payment_method()
      );

      $this->PixelManagerDataLayer['checkout'] = array_merge( $this->PixelManagerDataLayer['checkout'], $order_data );
      if(!$this->is_stop_send_user_data_ptm){
        $this->PTMUserData = $this->get_user_data($display_order_id);
      }
      ?>
      <script type="text/javascript" data-pagespeed-no-defer data-cfasync="false">
        window.addEventListener('load', call_purchase,true);
        function call_purchase(){
          const ptm_last_orderid = localStorage.getItem("ptm_last_orderid");
          const ptm_current_orderid = PixelManagerDataLayer[0]["data"]["checkout"]["id"];
          if (ptm_last_orderid !== ptm_current_orderid) {
            var PMW_JS = new PMW_PixelManagerJS("", false);
            if( Object.keys(PixelManagerDataLayer[0]["data"]["checkout"]).length >0 ){
              PMW_JS.Purchase();
            }
            PMW_JS.TrackPurchase();
            localStorage.setItem("ptm_last_orderid", ptm_current_orderid);
          }
        }
      </script>
      <?php
    }
    /**
     * inject data layer for product
     **/
    public function PMW_woocommerce_inject_data_layer_product(){
      if ( is_order_received_page() ) {
        if( $this->PixelItemFunction->get_order_from_order_received_page() ) {
          $order = $this->PixelItemFunction->get_order_from_order_received_page();        
          $order_items = $order->get_items();
          /*if( is_user_logged_in() ) {
            $user = get_current_user_id();
          }else{
            $user = $order->get_billing_email();
          }*/
          if(!empty($order_items)){
            foreach((array)$order_items as $order_item){
              $product_id = $this->PixelItemFunction->get_variation_id_or_product_id($order_item->get_data(), true);
              $product = wc_get_product( $product_id );
              $data = $this->PixelItemFunction->get_product_details_for_datalayer($product, "", $this->is_send_sku);
              
              $data['quantity'] = (int)$order_item['quantity'];
              $this->PixelManagerDataLayer['checkout']['cart_product_list'][$product_id] = $data;
            }
          }
          $coupon = $order->get_coupon_codes();
          $coupon = (is_array($coupon) && !empty($coupon))?$coupon[0]:"";
          $order_id = $order->get_order_number();
          $order_data = array(
            "id"              => $order_id,
            //"total"         => $order->get_total(),
            "total"           => number_format((float)$this->get_order_total("order_received", $order),2,'.',''),
            "discount"        => number_format((float)$order->get_total_discount(),2,'.',''),
            "tax"             => number_format((float)$order->get_total_tax(),2,'.',''),
            "shipping"        => number_format((float)$order->get_total_shipping(),2,'.',''),
            "coupon"          => $coupon,
            "currency"        => $order->get_currency(),
            "payment_method"  => $order->get_payment_method()
          );
          $this->PixelManagerDataLayer['checkout'] = array_merge( $this->PixelManagerDataLayer['checkout'], $order_data );
          if(!$this->is_stop_send_user_data_ptm){
            $this->PTMUserData = $this->get_user_data($order_id);
          }
          ?>
          <script type="text/javascript" data-pagespeed-no-defer data-cfasync="false">
            window.addEventListener('load', call_purchase,true);
            function call_purchase(){
              const ptm_last_orderid = localStorage.getItem("ptm_last_orderid");
              const ptm_current_orderid = PixelManagerDataLayer[0]["data"]["checkout"]["id"];
              if (ptm_last_orderid !== ptm_current_orderid) {
                var PMW_JS = new PMW_PixelManagerJS("", false);
                if( Object.keys(PixelManagerDataLayer[0]["data"]["checkout"]).length >0 ){
                  PMW_JS.Purchase();
                }
                PMW_JS.TrackPurchase();
                localStorage.setItem("ptm_last_orderid", ptm_current_orderid);
              }
            }        
          </script>
          <?php
        }
      }
    }

    /**
     * Process conversion events for all enabled platforms
     */
    public function process_conversion_events() {
      
      $response = [
        'success' => true,
        'log_time' => date('Y-m-d H:i:s'),
        'order_id' => isset($_POST['order_id']) ? intval($_POST['order_id']) : '',
        'processed' => [],
        'errors' => []
      ];

      try {
        // Get and sanitize input
        $event_id = isset($_POST['event_id']) ? sanitize_text_field($_POST['event_id']) : '';
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $total = isset($_POST['total']) ? floatval($_POST['total']) : 0;
        $currency = isset($_POST['currency']) ? sanitize_text_field($_POST['currency']) : '';
        
        // Handle items JSON
        $items = [];
        if (!empty($_POST['items'])) {
          $items_json = wp_unslash($_POST['items']);
          $items = json_decode($items_json, true);
          if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid items data');
          }
        }
        
        // Handle platforms JSON
        $platforms = [];
        if (!empty($_POST['platforms'])) {
          $platforms_json = wp_unslash($_POST['platforms']);
          $platforms = json_decode($platforms_json, true);
          if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid platforms data');
          }
        }
        if (empty($platforms) || !is_array($platforms)) {
          throw new Exception('No platforms specified');
        }

        // Process each enabled platform
        foreach ($platforms as $platform => $is_enabled) {
          if (!$is_enabled) {
            continue;
          }

          try {
            $method_name = "process_{$platform}_event";
            if (method_exists($this, $method_name)) {
              $result = $this->$method_name([
                'event_id' => $event_id,
                'order_id' => $order_id,
                'total' => $total,
                'currency' => $currency,
                'items' => $items
              ]);

              if($result['success']){
                $response['processed'][$platform] = ['success' => true, 'platform' => $platform, 'data' => $result];
              }else{
                $response['processed'][$platform] = ['success' => false, 'platform' => $platform, 'data' => $result];
              }
            } else {
              throw new Exception("Handler method not found for platform: {$platform}");
            }
          } catch (Exception $e) {
            $response['processed'][$platform] = [
              'success' => false,
              'error' => $e->getMessage()
            ];
            $response['errors'][] = "{$platform}: " . $e->getMessage();
          }
        }
        
        if($this->options['integration']['conversion_api_logs']){
          $this->save_pmw_conversion_api_logs($response);
        }
        wp_send_json_success($response);
      } catch (Exception $e) {
        wp_send_json_error([
            'message' => $e->getMessage(),
            'errors' => isset($response['errors']) ? $response['errors'] : []
        ]);
      }
    }

    /**
     * Process Facebook conversion event
     */
    private function process_facebook_event($data) {
      $pixel_id = isset($this->options['facebook_pixel']['pixel_id']) ? sanitize_text_field($this->options['facebook_pixel']['pixel_id']) : '';
      $access_token = isset($this->options['fb_conversion_api']['api_token']) ? sanitize_text_field($this->options['fb_conversion_api']['api_token']) : '';
      $access_tokenis_enable = isset($this->options['fb_conversion_api']['is_enable']) ? sanitize_text_field($this->options['fb_conversion_api']['is_enable']) : '';
      $test_event_code = isset($this->options['fb_conversion_api']['test_event_code']) ? sanitize_text_field($this->options['fb_conversion_api']['test_event_code']) : '';
      $conversion_api_logs_payload = isset($this->options['integration']['conversion_api_logs_payload']) ? sanitize_text_field($this->options['integration']['conversion_api_logs_payload']) : '';

      if (empty($pixel_id) || empty($access_token) || empty($access_tokenis_enable)) {
        throw new Exception('Facebook Pixel ID or Access Token not configured');
      }
      $order_id = isset($data['order_id']) ? intval($data['order_id']) : '';
      $event_id = isset($data['event_id']) ? sanitize_text_field($data['event_id']) : '';
      $currency = isset($data['currency']) ? sanitize_text_field($data['currency']) : '';
      $total = isset($data['total']) ? floatval($data['total']) : 0.0;
      $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
      $event_source_url = '';
      if ($order_id && function_exists('wc_get_order')) {
        $order = wc_get_order($order_id);
        if ($order) {
          $event_source_url = esc_url_raw($order->get_checkout_order_received_url());
        }
      }

      $user_data = $this->get_user_data($order_id);
      unset($user_data['st1']);
      $payload = [
        'partner_agent' => 'GrowCommerce',
        'data' => [
          [
            'event_name' => 'Purchase',
            'event_time' => time(),
            'event_id' => $event_id,
            'event_source_url' => $event_source_url,
            'action_source' => "website",
            'user_data' => $user_data,
            'custom_data' => [
              'currency' => $currency,
              'value' => $total,
              'content_type' => 'product',
              'contents' => array_map(function($item) {
               return [
                  'id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
                  'quantity' => isset($item['quantity']) ? intval($item['quantity']) : 1,
                  'item_price' => isset($item['price']) ? floatval($item['price']) : 0.0
                ];
              }, $items)
            ]
          ]
        ]
      ];
      $payload = $this->sanitize_payload_data($payload);

      if(!empty($test_event_code)){
        $payload['test_event_code'] = $test_event_code;
      }
      $log_data = [
        'success' => false,     // Will be updated after API call
        'error' => null,        // Will store any error messages
        'response' => null      // Will store the API response
      ];
      if($conversion_api_logs_payload){
        $log_data['payload'] = $payload;
      }
      try {
        $response = wp_remote_post("https://graph.facebook.com/v24.0/{$pixel_id}/events", [
          'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer {$access_token}"
          ],
          'body' => json_encode($payload),
          'timeout' => 15,
          'retry' => 2
        ]);
        if (is_wp_error($response)) {
          $log_data['error'] = $response->get_error_message();
        }else{
          $body = json_decode(wp_remote_retrieve_body($response), true);
          $log_data['response'] = $body;
          if (isset($body['error'])) {
            $log_data['error'] = (isset($body['error'])) ? $body['error'] : 'Unknown error';
          }else{
            $log_data['success'] = true;
          }
        }
        return $log_data;
      } catch (Exception $e) {
        $log_data['error'] = $e->getMessage();
        return $log_data;
      }
    }

    /**
     * Process TikTok conversion event
     */
    private function process_tiktok_event($data) {
      $pixel_id = isset($this->options['tiktok_pixel']['pixel_id']) ? sanitize_text_field($this->options['tiktok_pixel']['pixel_id']) : "";
      $access_token = isset($this->options['tiktok_conversion_api']['api_token']) ? sanitize_text_field($this->options['tiktok_conversion_api']['api_token']) : "";
      $access_tokenis_enable = isset($this->options['tiktok_conversion_api']['is_enable']) ? sanitize_text_field($this->options['tiktok_conversion_api']['is_enable']) : "";
      $conversion_api_logs_payload = isset($this->options['integration']['conversion_api_logs_payload']) ? sanitize_text_field($this->options['integration']['conversion_api_logs_payload']) : '';

      if (empty($pixel_id) || empty($access_token) || empty($access_tokenis_enable)) {
        throw new Exception('TikTok Pixel ID or Access Token not configured');
      }
      $order_id = isset($data['order_id']) ? intval($data['order_id']) : '';
      $currency = isset($data['currency']) ? sanitize_text_field($data['currency']) : '';
      $total = isset($data['total']) ? floatval($data['total']) : 0.0;
      $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];

      $user_data = $this->get_user_data($order_id);
      $processed_data = [];
      
      // Map and process user data fields to TikTok's standard parameter names
      if (!empty($user_data['em'])) {
        $processed_data['email'] = [hash('sha256', $user_data['em'])];
      }
      if (!empty($user_data['ph'])) {
        $processed_data['phone'] = [hash('user.phone', $user_data['ph'])];
      }
      if (!empty($user_data['external_id'])) {
        $processed_data['external_id'] = [hash('sha256', $order_id)];
      }
      // Add user agent if available
      if (!empty($user_data['client_user_agent'])) {
          $processed_data['client_user_agent'] = $user_data['client_user_agent'];
      }
      if(isset($_COOKIE['_ttp']) && !empty($_COOKIE['_ttp'])) {
        $processed_data['ttp'] = sanitize_text_field($_COOKIE['_ttp']);
      }
      $payload = [
        'event_source' => 'web',
        'event_source_id' => $pixel_id,
        'data' => [
          [ 
            'event' => 'Purchase',
            'event_id' => (string)$order_id,
            'event_time' => time(),
            'user' => $processed_data,
            'properties' => [
              'currency' => $currency,
              'value' => $total,
              'content_type' => 'product',
              'contents' => array_map(function($item) {
                  return [
                    'price' => isset($item['price']) ? floatval($item['price']) : 0.0,
                    'quantity' => isset($item['quantity']) ? intval($item['quantity']) : 1,
                    'content_id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
                    'content_category' => isset($item['category']) ? sanitize_text_field($item['category']) : '',
                    'content_name' => isset($item['name']) ? sanitize_text_field($item['name']) : '',
                    'brand' => isset($item['brand']) ? sanitize_text_field($item['brand']) : ''                  
                  ];
              }, $items)
            ],
            'page' => [
              //'url' => get_permalink(),
              'ref' => 'Thank You Page'
            ]
          ]
        ]
      ];
      $payload = $this->sanitize_payload_data($payload);

      $log_data = [
        'success' => false,     // Will be updated after API call
        'error' => null,        // Will store any error messages
        'response' => null      // Will store the API response
      ];
      if($conversion_api_logs_payload){
        $log_data['payload'] = $payload;
      }
      try {
        $response = wp_remote_post('https://business-api.tiktok.com/open_api/v1.3/event/track/', [
          'headers' => [
            'Content-Type' => 'application/json',
            'Access-Token' => $access_token
          ],
          'body' => json_encode($payload),
          'timeout' => 15,
          'retry' => 2
        ]);
       
        if (is_wp_error($response)) {
          $log_data['error'] = $response->get_error_message();
        }else{
          $body = json_decode(wp_remote_retrieve_body($response), true);
          $log_data['response'] = $body;
          if (isset($body['error'])) {
            $log_data['error'] = (isset($body['error'])) ? $body['error'] : 'Unknown error';
          }else{
            $log_data['success'] = true;
          }
        }
        return $log_data;
      } catch (Exception $e) {
        $log_data['error'] = $e->getMessage();
        return $log_data;
      }
    }

    /**
     * Process Pinterest conversion event
     */
    private function process_pinterest_event($data) {
      $ad_account_id = isset($this->options['pinterest_conversion_api']['ad_account_id']) ? sanitize_text_field($this->options['pinterest_conversion_api']['ad_account_id']) : "";
      $access_token = isset($this->options['pinterest_conversion_api']['api_token']) ? sanitize_text_field($this->options['pinterest_conversion_api']['api_token']) : "";
      $access_tokenis_enable = isset($this->options['pinterest_conversion_api']['is_enable']) ? sanitize_text_field($this->options['pinterest_conversion_api']['is_enable']) : "";
      $conversion_api_logs_payload = isset($this->options['integration']['conversion_api_logs_payload']) ? sanitize_text_field($this->options['integration']['conversion_api_logs_payload']) : '';

      if (empty($ad_account_id) || empty($access_token) || empty($access_tokenis_enable)) {
        throw new Exception('Pinterest Ad Account ID or Access Token not configured');
      }
      $currency = isset($data['currency']) ? sanitize_text_field($data['currency']) : '';
      $order_id = isset($data['order_id']) ? intval($data['order_id']) : '';
      $user_data = $this->get_user_data($order_id);
      foreach ($user_data as $key => $value) {
        if (empty($value) || !in_array($key, ['em', 'ph', 'fn', 'ln', 'country', 'ct', 'st', 'zp'])) {
          unset($user_data[$key]);
        }else if(in_array($key, ['em', 'ph', 'fn', 'ln', 'country', 'ct', 'st', 'zp'])){
          $user_data[$key] = [hash('sha256', $value)];
        }
      }
      if(!empty($this->click_ids['epik']['id'])){
        $user_data['click_id'] = [hash('sha256', $this->click_ids['epik']['id'])];
      }
      if(!empty($data['event_id'])){
        $user_data['external_id'] = [hash('sha256', (string)$order_id)];
      }else{
        unset($user_data['external_id']);
      }

      $event_source_url = '';
      if ($order_id && function_exists('wc_get_order')) {
        $order = wc_get_order($order_id);
        if ($order) {
          $event_source_url = esc_url_raw($order->get_checkout_order_received_url());
        }
      }

      $total = isset($data['total']) ? floatval($data['total']) : 0;
      $items = isset($data['items']) && is_array($data['items']) ? $data['items'] : [];
      $num_items = 0;
      foreach ($items as $item) {
        $num_items += isset($item['quantity']) ? intval($item['quantity']) : 1;
      }

      $payload = [
        'event_name' => 'checkout',
        'event_time' => time(),
        'action_source' => 'web',
        'event_id' => (string)$order_id,
        'event_source_url' => $event_source_url,
        'user_data' => $user_data,
        'content_name' => "Order received",
        'partner_name' => 'GrowCommerce',
        'custom_data' => [
          'currency' => $currency,
          'value' => $total,
          'order_id' => (string)$order_id,
          'contents' => array_map(function($item) {
            $quantity = isset($item['quantity']) ? intval($item['quantity']) : 1;
            return [
              'id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
              'item_name' => isset($item['name']) ? sanitize_text_field($item['name']) : '',
              'item_category' => isset($item['category']) ? sanitize_text_field($item['category']) : '',
              'item_brand' => isset($item['brand']) ? sanitize_text_field($item['brand']) : '',
              'quantity' => $quantity,
              'item_price' => isset($item['price']) ? floatval($item['price']) : 0.0
            ];
          }, $items),
          'num_items' => $num_items
        ]
      ];
      $payload = $this->sanitize_payload_data($payload);
      $log_data = [
        'success' => false,     // Will be updated after API call
        'error' => null,        // Will store any error messages
        'response' => null      // Will store the API response
      ];
      if($conversion_api_logs_payload){
        $log_data['payload'] = $payload;
      }
      try {
        $response = wp_remote_post("https://api.pinterest.com/v5/ad_accounts/{$ad_account_id}/events", [
          'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token
          ],
          'body' => json_encode(['data' => [$payload]]),
          'timeout' => 15,
          'retry' => 2
        ]);
        if (is_wp_error($response)) {
          $log_data['error'] = $response->get_error_message();
        }else{
          $body = json_decode(wp_remote_retrieve_body($response), true);
          $log_data['response'] = $body;
          if (isset($body['error'])) {
            $log_data['error'] = (isset($body['error'])) ? $body['error'] : 'Unknown error';
          }else{
            $log_data['success'] = true;
          }
        }
        return $log_data;
      } catch (Exception $e) {
        $log_data['error'] = $e->getMessage();
        return $log_data;
      }
    }

    /**
     * Process Twitter conversion event
     */
    /*private function process_twitter_event($data) {
      $pixel_id = isset($this->options['twitter_pixel']['pixel_id']) ? $this->options['twitter_pixel']['pixel_id'] : "";
      $access_token = isset($this->options['twitter_conversion_api']['api_token']) ? $this->options['twitter_conversion_api']['api_token'] : "";
      $access_tokenis_enable = isset($this->options['twitter_conversion_api']['is_enable']) ? $this->options['twitter_conversion_api']['is_enable'] : "";
      
      if (empty($pixel_id) || empty($access_token) || empty($access_tokenis_enable)) {
        throw new Exception('Twitter Pixel ID or Access Token not configured');
      }

      // Get user data for identifiers
      $user_data = $this->get_user_data($data['order_id']);
      
      // Build identifiers array for X Ads API
      $identifiers = [];
      
      // Add hashed email if available
      if (!empty($user_data['em'])) {
        $identifiers[] = ['hashed_email' => $user_data['em']];
      }
      
      // Add hashed phone if available
      if (!empty($user_data['ph'])) {
        $identifiers[] = ['hashed_phone_number' => $user_data['ph']];
      }
      
      // Add IP address and user agent
      if (!empty($user_data['client_ip_address'])) {
        $identifiers[] = ['ip_address' => $user_data['client_ip_address']];
      }
      
      if (!empty($user_data['client_user_agent'])) {
        // Add user agent to the last IP identifier or create a new one
        if (!empty($identifiers) && isset($identifiers[count($identifiers) - 1]['ip_address'])) {
          $identifiers[count($identifiers) - 1]['user_agent'] = $user_data['client_user_agent'];
        } else {
          $identifiers[] = ['user_agent' => $user_data['client_user_agent']];
        }
      }
      
      // Add Twitter click ID if available
      if (!empty($this->click_ids['twclid']['id'])) {
        $identifiers[] = ['twclid' => $this->click_ids['twclid']['id']];
      }
      
      // Build contents array
      $contents = [];
      foreach ($data['items'] as $item) {
        $contents[] = [
          'content_id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
          'content_name' => isset($item['name']) ? sanitize_text_field($item['name']) : '',
          'content_type' => 'product',
          'content_price' => isset($item['price']) ? intval(floatval($item['price']) * 1000000) : 0, // Convert to micro units
          'num_items' => isset($item['quantity']) ? intval($item['quantity']) : 1
        ];
      }
      
      // Build conversion payload for X Ads API
      $payload = [
        'conversions' => [
          [
            'conversion_time' => gmdate('Y-m-d\TH:i:s.v\Z', time()), // ISO-8601 with milliseconds
            'event_id' => $data['event_id'],
            'conversion_type' => 'PURCHASE',
            'conversion_value_micro' => intval(floatval($data['total']) * 1000000), // Convert to micro units
            'conversion_currency' => $data['currency'],
            'identifiers' => $identifiers,
            'number_items' => array_sum(array_column($data['items'], 'quantity')),
            'contents' => $contents,
            'price_currency' => $data['currency'],
            'value' => $data['total'],
            'conversion_id' => $data['event_id'],
            'description' => 'Purchase',
          ]
        ]
      ];

      $response = wp_remote_post("https://ads-api.x.com/12/measurement/conversions/" . $pixel_id, [
        'headers' => [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $access_token
        ],
        'body' => json_encode($payload),
        'timeout' => 15,
        'retry' => 2
      ]);

      if (is_wp_error($response)) {
        throw new Exception($response->get_error_message());
      }

      $body = json_decode(wp_remote_retrieve_body($response), true);
      
      if (isset($body['error'])) {
        throw new Exception((isset($body['error']['message'])) ? $body['error']['message'] : 'Unknown error');
      }

      return $body;
    }*/

    /**
     * Process Snapchat conversion event
     */
    /*private function process_snapchat_event($data) {
      $pixel_id = isset($this->options['snapchat_pixel']['pixel_id']) ? $this->options['snapchat_pixel']['pixel_id'] : "";
      $access_token = isset($this->options['snapchat_conversion_api']['api_token']) ? $this->options['snapchat_conversion_api']['api_token'] : "";
      $access_tokenis_enable = isset($this->options['snapchat_conversion_api']['is_enable']) ? $this->options['snapchat_conversion_api']['is_enable'] : "";
      
      if (empty($pixel_id) || empty($access_token) || empty($access_tokenis_enable)) {
        throw new Exception('Snapchat Pixel ID or Access Token not configured');
      }

      $payload = [
        'event' => 'PURCHASE',
        'action_source' => 'website',
        'external_id' => $data['event_id'],
        'content_type' => 'product',
        'event_time' => time() * 1000, // Snapchat expects milliseconds
        'user_data'=> $user_data,
        'custom_data' => [
          'value' => $data['total'],
          'currency' => $data['currency'],
          'order_id' => $data['order_id'],
          'content_category' => 'product',
          'number_items' => array_sum(array_column($data['items'], 'quantity')),
          'brand' => $data['brand'],
          'contents' => array_map(function($item) {
            return [
              'id' => isset($item['id']) ? sanitize_text_field($item['id']) : '',
              'item_price' => isset($item['price']) ? intval(floatval($item['price']) * 1000000) : 0, // Convert to micro units
              'quantity' => isset($item['quantity']) ? intval($item['quantity']) : 1
            ];
          }, $data['items'])
        ]
      ];

      $response = wp_remote_post('https://tr.snapchat.com/v3/$pixel_id/events', [
        'headers' => [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . $access_token
        ],
        'body' => json_encode([$payload]),
        'timeout' => 15,
        'retry' => 2
      ]);

      if (is_wp_error($response)) {
        throw new Exception($response->get_error_message());
      }

      $body = json_decode(wp_remote_retrieve_body($response), true);
      
      if (isset($body['error'])) {
        throw new Exception((isset($body['error']['message'])) ? $body['error']['message'] : 'Unknown error');
      }

      return $body;
    }*/

    /**
     * Get user data for tracking, with fallback to order data
     *
     * @param int|null $order_id Optional. The order ID to get additional data from
     * @return array Processed user data with hashed PII
     */
    private function get_user_data($order_id = null) {
      $user_data = [
        'client_ip_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
        'client_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        'fbc' => isset($_COOKIE['_fbc']) ? $_COOKIE['_fbc'] : '',
        'fbp' => isset($_COOKIE['_fbp']) ? $_COOKIE['_fbp'] : ''
      ];

      // 1. First get logged-in user data
      if (is_user_logged_in()) {
        $user = wp_get_current_user();
        
        // Basic user info
        if (!empty($user->user_email)) {
          $user_data['em'] = hash('sha256', strtolower(trim($user->user_email)));
        }
        
        if (!empty($user->billing_phone)) {
          $user_data['ph'] = hash('sha256', preg_replace('/[^0-9]/', '', $user->billing_phone));
        }
        
        // Name components
        if (!empty($user->first_name)) {
          $user_data['fn'] = hash('sha256', strtolower(trim($user->first_name)));
        }
        
        if (!empty($user->last_name)) {
          $user_data['ln'] = hash('sha256', strtolower(trim($user->last_name)));
        }
        
        // Get additional user meta if available
        $user_meta = get_user_meta($user->ID);
        if (!empty($user_meta['billing_state'][0])) {
          $user_data['st'] = hash('sha256', strtolower(trim($user_meta['billing_state'][0])));
        }

        if (!empty($user_meta['billing_country'][0])) {
          $user_data['country'] = hash('sha256', strtolower(trim($user_meta['billing_country'][0])));
        }

        // Add more address fields if available
        if (!empty($user_meta['billing_address_1'][0])) {
          $user_data['st1'] = hash('sha256', strtolower(trim($user_meta['billing_address_1'][0])));
        }

        if (!empty($user_meta['billing_city'][0])) {
          $user_data['ct'] = hash('sha256', strtolower(trim($user_meta['billing_city'][0])));
        }
        
        if (!empty($user_meta['billing_postcode'][0])) {
          $user_data['zp'] = hash('sha256', preg_replace('/[^0-9]/', '', $user_meta['billing_postcode'][0]));
        }
        
      }

      // 2. Then enhance with order data if available
      if ($order_id && function_exists('wc_get_order')) {
        $order = wc_get_order($order_id);
        if ($order) {
          // Only add order data if we don't already have it from user data
          if (empty($user_data['em'])) {
            $billing_email = $order->get_billing_email();
            if (!empty($billing_email) && is_email($billing_email)) {
              $user_data['em'] = hash('sha256', strtolower(trim($billing_email)));
            }
          }

          if (empty($user_data['ph'])) {
            $billing_phone = $order->get_billing_phone();
            if (!empty($billing_phone)) {
              $user_data['ph'] = hash('sha256', preg_replace('/[^0-9]/', '', $billing_phone));
            }
          }
          
          if (empty($user_data['fn'])) {
            $first_name = $order->get_billing_first_name();
            if (!empty($first_name)) {
              $user_data['fn'] = hash('sha256', strtolower(trim($first_name)));
            }
          }

          if (empty($user_data['ln'])) {
            $last_name = $order->get_billing_last_name();
            if (!empty($last_name)) {
              $user_data['ln'] = hash('sha256', strtolower(trim($last_name)));
            }
          }

          // Address components if missing
          $address_fields = [
            'ct' => 'billing_city',
            'st' => 'billing_state',
            'country' => 'billing_country',
            'st1' => 'billing_address_1',
            'zp' => 'billing_postcode'
          ];

          foreach ($address_fields as $key => $field) {
            if (empty($user_data[$key])) {
              $value = $order->{"get_$field"}();
              if (!empty($value)) {
                $user_data[$key] = hash('sha256', strtolower(trim($value)));
                // Special handling for postcode (remove non-numeric characters)
                if ($key === 'zp') {
                  $user_data[$key] = hash('sha256', preg_replace('/[^0-9]/', '', $value));
                }
              }
            }
          }

          // Add/override with order-specific data
          $user_data['external_id'] = (string) $order_id;
          //$user_data['client_transaction_id'] = (string) $order_id;
        }
      }

      // Add device information
      if (empty($user_data['client_ip_address'])) {
        $user_data['client_ip_address'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
      }
      
      if (empty($user_data['client_user_agent'])) {
        $user_data['client_user_agent'] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
      }

      // Remove any empty values
      return array_filter($user_data);
    }
  }
endif;