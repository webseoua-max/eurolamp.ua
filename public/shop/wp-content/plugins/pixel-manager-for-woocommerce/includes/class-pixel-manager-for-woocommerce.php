<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://growcommerce.io/
 * @since      1.0.0
 *
 * @package    Pixel_Manager_For_Woocommerce
 * @subpackage Pixel_Manager_For_Woocommerce/includes
 * @author     GrowCommerce
 */
class Pixel_Manager_For_Woocommerce {
	protected $loader;
	protected $plugin_name;
	protected $version;
	public function __construct() {
		if(class_exists( 'PRO_Pixel_Manager_For_Woocommerce' )){
			return;
		}
		if ( defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pixel-manager-for-woocommerce';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

		add_filter( 'plugin_action_links_' .plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' ), array($this,'pmw_plugin_action_links'),10 );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pixel_Manager_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Pixel_Manager_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Pixel_Manager_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Pixel_Manager_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pixel-manager-for-woocommerce-loader.php';
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pixel-manager-for-woocommerce-i18n.php';
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pixel-manager-for-woocommerce-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/pixels/class-pixel.php';
		$this->loader = new Pixel_Manager_For_Woocommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pixel_Manager_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Pixel_Manager_For_Woocommerce_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Pixel_Manager_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pixel_Manager_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function pmw_plugin_action_links($links) {
		$deactivate_link = $links['deactivate'];
    unset($links['deactivate']);
    $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=pixel-manager') . '">'.__("Settings", "pixel-manager-for-woocommerce").'</a>';
    $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=pixel-manager-growinsights360') . '">'.__("GrowInsights360", "pixel-manager-for-woocommerce").'</a>';
    $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=pixel-manager-documentation') . '">'.__("Documentation", "pixel-manager-for-woocommerce").'</a>';
    $links['deactivate'] = $deactivate_link;
    return $links;
	}
}