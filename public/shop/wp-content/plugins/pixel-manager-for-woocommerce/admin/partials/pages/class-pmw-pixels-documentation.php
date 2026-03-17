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
if(!class_exists('PMW_PixelsDocumentation')){
  class PMW_PixelsDocumentation extends PMW_AdminHelper{
    public function __construct( ) {
      $this->load_html();
    }
    protected function load_html(){
      $this->page_html();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      //echo $this->get_store_id();
      $iframe_url = "https://growcommerce.io/doc/pixel-manager/";
      ?>
      <div class="pmw_page">
        <div class="grow-doc-iframe grow-growinsights360-iframe"> 
          <iframe src="<?php echo esc_url_raw($iframe_url); ?>"></iframe>
        </div>
      </div>
      <?php
    }
  }
}