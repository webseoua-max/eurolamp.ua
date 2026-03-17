<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @package    Pixel_Manager_For_Woocommerce/admin/partials
 * Pixel Tag Manager
 */

if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_PixelsUpgradePro')){
  class PMW_PixelsUpgradePro extends PMW_AdminHelper{
    public function __construct( ) {
      $this->page_html();
    }
    /**
     * Page HTML
     **/
    protected function page_html(){
      ?>
      <script type="text/javascript">          
       var a = document.createElement("a");
        a.href = "<?php echo esc_url_raw($this->get_price_plan_link()); ?>";
        document.body.appendChild(a);
        a.click();
        a.remove();        
      </script>
      <?php
      exit;
    }
  }
}