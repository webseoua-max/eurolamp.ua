<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.2.0 (05-04-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 0.1.0
	 * @access protected
	 * @var XFGMC_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 0.1.0
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * Container for core service objects.
	 *
	 * @since    4.1.0
	 * @access   protected
	 * @var      array    $services    Holds instances of core functionality objects.
	 */
	protected $services = [];

	/**
	 * The current version of the plugin.
	 *
	 * @since 0.1.0
	 * @access protected
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		if ( defined( 'XFGMC_PLUGIN_VERSION' ) ) {
			$this->version = XFGMC_PLUGIN_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'xml-for-google-merchant-center';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		// ! $this->define_public_hooks(); - отключил
		$this->define_core_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - XFGMC_Data. Defines all the plugin data in database.
	 * - XFGMC_Loader. Orchestrates the hooks of the plugin.
	 * - XFGMC_i18n. Defines internationalization functionality.
	 * - XFGMC_Admin. Defines all hooks for the admin area.
	 * - XFGMC_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 0.1.0
	 * @access private
	 * 
	 * @return void
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xfgmc-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xfgmc-i18n.php';

		/** ----------------------------------- */

		/**
		 * These classes are responsible for generating the feed.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/traits/global/traits-xfgmc-global-variables.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/class-xfgmc-generation-xml.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/class-xfgmc-write-file.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/class-xfgmc-feed-file-meta.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/class-xfgmc-feed-updater.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/feeds/class-xfgmc-rules-list.php';

		/**
		 * Adding third-party libraries.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common-libs/functions-icpd-useful-2-0-2.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common-libs/functions-icpd-woocommerce-1-1-1.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common-libs/class-icpd-set-admin-notices.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common-libs/class-icpd-promo.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/common-libs/backward-compatibility.php';

		/**
		 * These classes are responsible for updating the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/updates/class-xfgmc-plugin-form-activate.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/updates/class-xfgmc-plugin-upd.php';

		/**
		 * The class responsible for the feedback form inside the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-xfgmc-feedback.php';

		/**
		 * The classes are responsible for core the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-xfgmc-error-log.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-xfgmc-get-closed-tag.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-xfgmc-get-open-tag.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-xfgmc-get-paired-tag.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/core/class-xfgmc-data.php';

		/**
		 * This class manages the CRON tasks of generating the YML feed.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/cron/class-xfgmc-cron-manager.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wordpress/class-xfgmc-mime-types.php';

		// Подключение CLI команды
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wp-cli/class-xfgmc-wp-cli-command.php';
		}

		/** ----------------------------------- */

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-xfgmc-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-xfgmc-public.php';

		$this->loader = new XFGMC_Loader();

		$this->services['cron_manager'] = new XFGMC_Cron_Manager();
		$this->services['feed_updater'] = new XFGMC_Feed_Updater();
		$this->services['mime_types'] = new XFGMC_Mime_Types();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the XFGMC_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 0.1.0
	 * @access private
	 * 
	 * @return void
	 */
	private function set_locale() {

		$plugin_i18n = new XFGMC_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since 0.1.0
	 * @access private
	 * 
	 * @return void
	 */
	private function define_admin_hooks() {

		$plugin_admin = new XFGMC_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin->init_hooks( $this->loader );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 0.1.0
	 * @access private
	 * 
	 * @return void
	 */
	private function define_public_hooks() {

		$plugin_public = new XFGMC_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public->init_hooks( $this->loader );

	}

	/**
	 * Register hooks that are related to core functionality, but not tied 
	 * to admin or public-facing logic.
	 * 
	 * @since    0.1.0
	 * @access   private
	 * 
	 * @return   void
	 */
	private function define_core_hooks() {

		$cron_manager = $this->services['cron_manager'];
		$cron_manager->init_hooks( $this->loader );

		$feed_updater = $this->services['feed_updater'];
		$mime_types = $this->services['mime_types'];

		// слушаем изменение количества товаров в заказе
		$this->loader->add_action( 'woocommerce_reduce_order_item_stock', $feed_updater, 'check_update_feed_stock_change', 50, 3 );

		// Разрешим загрузку xml и csv файлов
		$this->loader->add_action( 'upload_mimes', $mime_types, 'add_mime_types' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 0.1.0
	 * 
	 * @return string The name of the plugin. For example: `xml-for-google-merchant-center`.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 0.1.0
	 * 
	 * @return XFGMC_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 0.1.0
	 * 
	 * @return string The version number of the plugin. For example: `0.1.0`.
	 */
	public function get_version() {
		return $this->version;
	}

}
