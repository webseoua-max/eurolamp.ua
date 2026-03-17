<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/admin
 * @author     GrowCommerce
 */
if ( ! class_exists( 'Pixel_Manager_For_Woocommerce_Admin' ) ) {	
	class Pixel_Manager_For_Woocommerce_Admin extends PMW_AdminHelper{
		private $plugin_name;
		private $version;
		protected $screen_id;
		protected $is_pro_version;
		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string    $plugin_name       The name of this plugin.
		 * @param      string    $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->is_pro_version = $this->pmw_is_pro_version();
			$this->includes();
			$this->plugin_name = $plugin_name;
			$this->version = $version;
			$this->screen_id = isset($_GET['page'])?sanitize_text_field($_GET['page']):"";
			add_action( 'admin_menu', array($this,'admin_menu'));
			add_action( 'admin_enqueue_scripts', array( $this, 'pmw_page_scripts' ) );
			if(strpos($this->screen_id, 'pixel-manager') !== true){
				$this->pmw_add_admin_notices();
				add_action( 'admin_notices', array( $this, 'pmw_display_admin_notices') );
			}
		}

		/**
		 * includes required fils
		 *
		 * @since    1.0.0
		 */
		public function includes() {
			if (!class_exists('PMW_AjaxHelper')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/ajax/class-pmw-ajax-helper.php');
	    }
			if (!class_exists('PMW_Header')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/common/class-pmw-header.php');
	    }
	    if (!class_exists('PMW_Footer')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/common/class-pmw-footer.php');
	    }
	    if (!class_exists('PMW_AdminMigrateHelper')) {
	      require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-migrate-helper.php');
	    }
		}

		public function pmw_page_scripts(){
			?>
			<script>
	      var pmw_ajax_url = '<?php echo esc_url_raw(admin_url( 'admin-ajax.php' )); ?>';
	    </script>
			<?php
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			if(strpos($this->screen_id, 'pixel-manager') !== false){
				wp_enqueue_style( $this->plugin_name, esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL . '/admin/css/pixel-manager-for-woocommerce-admin.css'), array(), $this->version, 'all' );
			}
			if( in_array($this->screen_id, array("pixel-manager-growinsights360", "pixel-manager-documentation")) ){
				wp_enqueue_style( $this->plugin_name.'-custom', esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL . '/admin/css/pixel-manager-for-woocommerce-admin-custom.css'), array(), $this->version, 'all' );
			}
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			if(strpos($this->screen_id, 'pixel-manager') !== false){
				wp_enqueue_script( $this->plugin_name, esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL  . '/admin/js/pixel-manager-for-woocommerce-admin.js'), array( 'jquery' ), $this->version, false );
			}
		}

		/**
		 * Add Menu for the admin area.
		 * @since    1.0.0
		 */
		public function admin_menu(){
			$menu_icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/pixel-tag-manager.png";
			$discount_icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/discount.png";
			add_menu_page(
	      __('Pixel Tag Manager','pixel-manager-for-woocommerce'), __('Pixel Tag Manager','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager', array($this, 'show_page'),esc_url_raw($menu_icon), 56 );
			add_submenu_page('pixel-manager', __('Pixel Settings','pixel-manager-for-woocommerce'), __('Pixel Settings','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager' );
			add_submenu_page('pixel-manager', __('GrowInsights360','pixel-manager-for-woocommerce'), __('GrowInsights360','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-growinsights360', array($this, 'show_page') );
			add_submenu_page('pixel-manager', __('Account','pixel-manager-for-woocommerce'), __('Account','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-account', array($this, 'show_page'));
			add_submenu_page('pixel-manager', __('Documentation','pixel-manager-for-woocommerce'), __('Documentation','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-documentation', array($this, 'show_page'));
			add_submenu_page('pixel-manager', __('Support','pixel-manager-for-woocommerce'), __('Support','pixel-manager-for-woocommerce'), 'manage_options', 'pixel-manager-support', array($this, 'show_page'));
			if(!$this->is_pro_version){
				add_submenu_page('pixel-manager', __('Upgrade to Pro','pixel-manager-for-woocommerce'), '<span style="background: #2271b1;padding: 1px 10px 3px 10px;color:#fff;">'.__('Upgrade to Pro','pixel-manager-for-woocommerce').'</span>', 'manage_options', 'pixel-manager-upgrade-pro', array($this, 'show_page'));
				add_submenu_page('pixel-manager', __('Free Vs Pro','pixel-manager-for-woocommerce'), __('Free Vs Pro','pixel-manager-for-woocommerce').'<img style="position: absolute; height: 28px;bottom: 5px; right: 10px;" src="'.$discount_icon.'">', 'manage_options', 'pixel-manager-freevspro', array($this, 'show_page'));				
			}
			
		}

		/**
		 * Load page for the admin area.
		 * @since    1.0.0
		 */
		public function show_page() {
			$get_action = "";
	   	if(isset($_GET['page'])) {
	      $get_action = str_replace("-", "_", sanitize_text_field($_GET['page']) );
	    }
	    do_action('pmw_header');
	    if(method_exists($this, $get_action)){
	      $this->$get_action();
	    }
	    if( !in_array($get_action, array("pixel_manager_growinsights360", "pixel_manager_documentation")) ){
	      do_action('pmw_footer');
	    }
	  }

	  public function pixel_manager(){
	  	require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels.php');
	  	new PMW_Pixels();
	  }
	  public function pixel_manager_support(){
	  	require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-support.php');
	  	new PMW_PixelsSupport();
	  }

	  public function pixel_manager_upgrade_pro(){
	    require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-upgrade-pro.php');
	    new PMW_PixelsUpgradePro();
	  }
	  public function pixel_manager_freevspro(){
	    require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-freevspro.php');
	    new PMW_PixelsFreeVsPro();
	  }
	  public function pixel_manager_account(){
	    require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-account.php');
	    new PMW_PixelsAccount();
	  }
	  public function pixel_manager_documentation(){
	    require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-documentation.php');
	    new PMW_PixelsDocumentation();
	  }
	  public function pixel_manager_growinsights360(){
	    require_once(PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/partials/pages/class-pmw-pixels-growinsights360.php');
	    new PMW_PixelsGrowInsights360();
	  }
	}
}