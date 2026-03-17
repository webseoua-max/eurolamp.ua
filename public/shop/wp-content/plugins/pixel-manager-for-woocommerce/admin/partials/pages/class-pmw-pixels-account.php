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
if(!class_exists('PMW_PixelsAccount')){
  class PMW_PixelsAccount extends PMW_AdminHelper{
    protected $is_pro_version;
    protected $license_key;
    protected $disp_license_key;
    protected $store_data;
    protected $api_store;
    protected $plan_name;
    public function __construct( ) {
      $this->api_store = (object)$this->get_pmw_api_store();
      $this->is_pro_version = $this->pmw_is_pro_version($this->api_store);
      $this->license_key = $this->get_license_key($this->api_store);
      $this->plan_name = $this->get_plan_name($this->api_store);
      if($this->license_key != ""){
        /* deactivet the plan to allow user to use free plugin */
        $this->pmw_pixels_license_key_check($this->license_key);
        $this->api_store = (object)$this->get_pmw_api_store();
        $this->is_pro_version = $this->pmw_is_pro_version($this->api_store);
        $this->license_key = $this->get_license_key($this->api_store);
        $this->plan_name = $this->get_plan_name($this->api_store);

        $str_length = strlen($this->license_key)-12;
        $str_length = ($str_length > 13)?$str_length:"5";
        $this->disp_license_key = substr($this->license_key ,0, 6) . str_repeat("X", $str_length) . substr($this->license_key , -6);
      }            
      $this->req_int();
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
      //echo $this->get_store_id();
      ?>
      <div class="pmw-left-wrapper">
        <div class="pmw-account" id="pmw-account">
          <div class="pmw_form-wrapper active pmw_form-row rm-b" id="sec-pmw-pixels">            
            <div class="pmw_form-group">
              <h3><?php esc_attr_e('Super savings!!','pixel-manager-for-woocommerce'); ?></h3> 
              <section class="hero-section-banner">                      
                <div class="hero-caption">
                  <h1><?php echo esc_attr__('Boost your tracking capabilities by upgrading to our Pro Plan, starting at just $9.', 'pixel-manager-for-woocommerce'); ?></h1>             
                </div>                
                <div class="pmw-top-pro-btn">
                  <a class="pmw_btn pmw_btn-light-default-pro" target="_blank" href="<?php echo esc_url_raw($this->get_pmw_website_link().'checkout/?product=pixel-tag-manager-for-woocommerce&plan=95&utm_source=Plugin+WordPress+Screen&utm_medium=Account+Page+Buy+Now&m_campaign=Upsell+at+PixelTagManager+Plugin');?>"><?php echo esc_attr__('Buy Now', 'pixel-manager-for-woocommerce'); ?></a>
                </div>                    
              </section> 
              <div class="frrtopro">
                <h3><?php esc_attr_e('Upgrade to Pro:','pixel-manager-for-woocommerce'); ?></h3>              
                <p><?php esc_attr_e("Unlock Enhanced eCommerce tracking and access advanced features by upgrading from the FREE version to the PRO plan, seamlessly integrated using Google Tag Manager.","pixel-manager-for-woocommerce"); ?></p>
                <a class="pmw-plan_link-btn" href="<?php echo esc_url_raw($this->get_price_plan_link());?>&utm_source=Plugin+WordPress+Screen&utm_medium=Account+Page+Upgrade+to+Pro&m_campaign=Upsell+at+PixelTagManager+Plugin" target="_blank"><?php esc_attr_e('Upgrade to Pro','pixel-manager-for-woocommerce'); ?></a>
              </div>
            </div>
            <div class="plan_details">
              <?php
              if(!$this->is_pro_version){ ?>
              <?php 
                $fields = [ 
                  "section_account" => [    
                    [
                      "type" => "section",
                      "label" => __("Activate PRO Account", "pixel-manager-for-woocommerce"),
                      "class" => "google_section_setting",
                    ]
                  ],       
                  "button" => [
                    [
                      "type" => "button",
                      "name" => "pmw_active_key_steps",
                      "id" => "pmw_active_key_steps_btn",
                      "label" => "How to Activate Key"
                    ]
                  ]
                ];
                $form = array("name" => "pmw-show-step-active-key", "id" => "pmw-show-step-active-key", "method" => "post");
                $this->add_form_fields($fields, $form);
              } ?>
              <div class="pmw_form-group pmw-active-key-steps pmw-hide">
                <div class="frrtopro">
                  <h3><?php esc_attr_e('To activate the License key:','pixel-manager-for-woocommerce'); ?></h3>
                  <p><?php esc_attr_e("Acquire our Pro Plan, download the pro plugin, and on the same page, you'll have the option to add the license key.","pixel-manager-for-woocommerce"); ?><p>
                </div>
              </div>
            </div>

            <div class="plan_details">
              <div class="pmw_form-row">
                <div class="pmw_form-group ">
                  <?php
                  $this->add_section([
                    "type" => "section",
                    "label" => __("Your plan details", "pixel-manager-for-woocommerce"),
                    "class" => "plan_details_section",
                  ]); ?>
                </div>
              </div>
              <div class="pmw_form-row">
                <ul class="pmw_order-info ml-2">
                  <li><label><?php esc_attr_e('Plan','pixel-manager-for-woocommerce'); ?></label><span><?php echo esc_attr($this->plan_name); ?></span></li>
                  <?php if($this->is_pro_version){?>
                    <li><label><?php esc_attr_e('License Key','pixel-manager-for-woocommerce'); ?></label><span><?php echo esc_attr($this->disp_license_key); ?></span></li>
                  <?php }else{?>
                    <li><label><?php esc_attr_e('Upgrade to Pro','pixel-manager-for-woocommerce'); ?></label><strong><a target="_blank" href="<?php echo esc_url_raw($this->get_pmw_website_link().'checkout/?product=pixel-tag-manager-for-woocommerce&plan=95&utm_source=Plugin+WordPress+Screen&utm_medium=Account+Page+Buy+Now&m_campaign=Upsell+at+PixelTagManager+Plugin');?>"><?php echo esc_attr__('(Just $9)', 'pixel-manager-for-woocommerce'); ?></a></strong></li>
                  <?php } ?>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <?php
        echo $this->get_sidebar_html($this->is_pro_version, $this->plan_name);
        ?>
      </div>
        <?php
    }
    /**
     * Page JS
     **/
    protected function page_js(){
      ?>
      <script type="text/javascript">
        jQuery("#pmw_active_key_steps_btn").on("click", function (event) {
          event.preventDefault();
          jQuery(".pmw-active-key-steps").toggleClass("pmw-hide");
        });
      </script>
      <?php
    }
  }
}