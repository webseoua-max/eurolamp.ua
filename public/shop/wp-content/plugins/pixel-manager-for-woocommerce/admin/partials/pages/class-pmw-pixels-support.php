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
if(!class_exists('PMW_PixelsSupport')){
  class PMW_PixelsSupport extends PMW_AdminHelper{
    public function __construct( ) {
      $this->page_html();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      //wp_redirect($this->get_support_page_link());
      ?>
      <script type="text/javascript">          
       var a = document.createElement("a");
        a.href = "<?php echo esc_url_raw($this->get_support_page_link()); ?>";
        document.body.appendChild(a);
        a.click();
        a.remove();        
      </script>
      <?php
      exit;
    }
  }
}