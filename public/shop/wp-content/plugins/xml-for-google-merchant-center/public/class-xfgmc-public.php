<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.2.0 (05-04-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to enqueue the public-facing 
 * stylesheet and JavaScript.
 *
 * @package    XFGMC
 * @subpackage XFGMC/public
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since 0.1.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 0.1.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Registers all frontend-related hooks through the given loader instance.
	 *
	 * This method attaches the necessary actions to enqueue styles and scripts 
	 * on the frontend of the website. It uses the provided loader to properly 
	 * bind callbacks, ensuring correct execution within the WordPress hook system.
	 *
	 * @since   0.1.0
	 * @access  public
	 *
	 * @param XFGMC_Loader $loader The loader object responsible for managing WordPress hooks.
	 *
	 * @return void
	 */
	public function init_hooks( XFGMC_Loader $loader ) {

		$loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_styles' );
		$loader->add_action( 'wp_enqueue_scripts', $this, 'enqueue_scripts' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in XFGMC_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The XFGMC_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/xfgmc-public.css',
			[],
			$this->version,
			'all'
		);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in XFGMC_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The XFGMC_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/xfgmc-public.js',
			[ 'jquery' ],
			$this->version,
			false
		);

	}

}
