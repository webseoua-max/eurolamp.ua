<?php
/*
Plugin Name: WP Advanced Math Captcha
Description: Math Captcha is a <strong>100% effective CAPTCHA for WordPress</strong> that integrates into login, registration, comments, Contact Form 7 and bbPress, woocommerce, WPForms.
Version: 2.1.9.1
Author: AntiCaptcha
License: MIT License
License URI: http://opensource.org/licenses/MIT
Text Domain: math-captcha
Domain Path: /languages

WP Advanced Math Captcha

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

/**
 * Added by the WordPress.org Plugins Review team in response to an incident.
 * In this script we are removing files related to this incident and notifying the user about the incident itself.
 */
function MATH_CAPTCHA_PRT_incidence_response_notice() {
    if(!current_user_can('manage_options')) return;
    $user_id = get_current_user_id();
    if ( get_user_meta( $user_id, 'math_captcha_prt_notice_dismissed', true ) ) {
        return;
    }
    ?>
    <div class="notice notice-warning is-dismissible" id="math-captcha-prt-notice">
        <h3><?php esc_html_e( 'Important Notice from the WordPress.org Plugins Team.', 'wp-advanced-math-captcha' ); ?></h3>
        <p><?php esc_html_e( 'We would like to inform you that the "WP Advanced Math Captcha" plugin, published by the user "lulub5592" has been reported by the community as not compliant with the guidelines. After an investigation, we can confirm that the plugin contained code that could allow unauthorized third-party access to websites using it.', 'wp-advanced-math-captcha' ); ?></p>
        <p><?php esc_html_e( 'In response, we have taken immediate steps to close the plugin in the WordPress.org Plugins repository and release an update that already removed the original affected code from your website.', 'wp-advanced-math-captcha' ); ?></p>
        <p><?php esc_html_e( 'Specifically, this plugin included an obfuscated file, wp-math-captcha.dat, which was then uncompressed into a file named wp-math-captcha.dat.tmp. This file was executed, sending your website\'s URL to apitest.siteguarding.com and installing a "Remote Management Tool" in the root directory as a file named siteguarding_tools.php. This tool allows connections from specific IPs belonging to siteguarding.com and safetybis.com servers, as well as connections containing a specific key (although we believe this can be bypassed). It enabled remote control of your website, allowing third parties to access, modify, and execute code on your site.', 'wp-advanced-math-captcha' ); ?></p>
        <p><?php esc_html_e( 'Although the original code enabling remote control has been automatically removed, it\'s possible that actions were previously carried out on your website without your knowledge. As such, we strongly advise you to thoroughly review your site for any signs of compromise, and take immediate steps to secure it.', 'wp-advanced-math-captcha' ); ?></p>
    </div>
    <?php
}

function MATH_CAPTCHA_PRT_enqueue_dismiss_script( $hook ) {
    $user_id = get_current_user_id();
    if ( get_user_meta( $user_id, 'math_captcha_prt_notice_dismissed', true ) ) {
        return;
    }

    $inline_js = sprintf(
            'jQuery( document ).on( "click", "#math-captcha-prt-notice .notice-dismiss", function() {
            jQuery.post( "%s", {
                action: "math_captcha_prt_dismiss_notice",
                _wpnonce: "%s"
            });
        });',
            esc_url( admin_url( 'admin-ajax.php' ) ),
            wp_create_nonce( 'math_captcha_prt_dismiss_nonce' )
    );

    wp_add_inline_script( 'jquery-core', $inline_js );
}
add_action( 'admin_enqueue_scripts', 'MATH_CAPTCHA_PRT_enqueue_dismiss_script' );

function MATH_CAPTCHA_PRT_dismiss_notice() {
    check_ajax_referer( 'math_captcha_prt_dismiss_nonce' );
    update_user_meta( get_current_user_id(), 'math_captcha_prt_notice_dismissed', true );
    wp_die();
}
add_action( 'wp_ajax_math_captcha_prt_dismiss_notice', 'MATH_CAPTCHA_PRT_dismiss_notice' );

function MATH_CAPTCHA_PRT_incidence_response() {
    $filename = dirname(__FILE__).'/wp-math-captcha.dat';
    if(file_exists($filename)) unlink($filename);

    $filename = dirname(__FILE__).'/wp-math-captcha.dat.tmp';
    if(file_exists($filename)) unlink($filename);

    if (defined('ABSPATH')) $file = ABSPATH.'/siteguarding_tools.php';
    else $file = dirname(dirname(dirname(dirname(__FILE__)))).'/siteguarding_tools.php';
    if(file_exists($file)) unlink($file);

    if (defined('ABSPATH')) $file = ABSPATH.'/webanalyze/siteguarding_tools.php';
    else $file = dirname(dirname(dirname(dirname(__FILE__)))).'/webanalyze/siteguarding_tools.php';
    if(file_exists($file)) unlink($file);

    add_action( 'admin_notices', 'MATH_CAPTCHA_PRT_incidence_response_notice' );
}
add_action('init', 'MATH_CAPTCHA_PRT_incidence_response');


define( 'MATH_CAPTCHA_URL', plugins_url( '', __FILE__ ) );
define( 'MATH_CAPTCHA_PATH', plugin_dir_path( __FILE__ ) );
define( 'MATH_CAPTCHA_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );

if (!class_exists('MathCaptcha_GEO')) include_once(MATH_CAPTCHA_PATH . 'includes/class-geo.php');
include_once(MATH_CAPTCHA_PATH . 'includes/class-cookie-session.php');
include_once(MATH_CAPTCHA_PATH . 'includes/class-update.php');
include_once(MATH_CAPTCHA_PATH . 'includes/class-core.php');
include_once(MATH_CAPTCHA_PATH . 'includes/class-settings.php');
include_once(MATH_CAPTCHA_PATH . 'includes/advert-test-codes.php');

add_action("wp_enqueue_scripts", "wmc_script_enqueue");
add_action('init', 'wmc_register_style');

function wmc_register_style() {
    wp_add_inline_script( 'wmc_script', 'const wmc_ajax_url = "' . admin_url('admin-ajax.php').'";', 'before' ); // Used for: WPForms, defines JS AJAX URL
}

function wmc_script_enqueue() {
	wp_enqueue_script( 'wmc-js', plugins_url( '/js/wmc.js', __FILE__ ), array('jquery','wp-data'), '2.1.8', array('strategy' => 'defer'));
}


/**
 * Math Captcha class.
 */
class Math_Captcha {

	private static $_instance;
	public $core;
	public $cookie_session;
	public $options;
	public $defaults = array(
		'general'	 => array(
			'enable_for'				 => array(
				'login_form'			 => true,
				'registration_form'		 => true,
				'reset_password_form'	 => true,
				'comment_form'			 => true,
				'bbpress'				 => false,
				'contact_form_7'		 => false,
				'woocommerce_login'		 => false,
				'woocommerce_register'	 => false,
				'woocommerce_reset'	     => false,
				'woocommerce_checkout'	 => false,
				'wpforms'	 			 => false,
                'formidable_forms' => false // Add Formidable Forms Lite
			),
			'block_direct_comments'		 => false,
			'hide_for_logged_users'		 => true,
			'title'						 => 'Math Captcha',
			'mathematical_operations'	 => array(
				'addition'		 => true,
				'subtraction'	 => true,
				'multiplication' => false,
				'division'		 => false
			),
			'groups'					 => array(
				'numbers'	 => true,
				'words'		 => false
			),
			'time'						 => 300,
			'deactivation_delete'		 => false,
			'show_powered_by'		 => true,
			'geo_db_autoupdate'		 => false,
			'collect_logs'		 => true,
			'geo_captcha_rules'		 => false,
			'ip_rules'		 => false,
			'ip_rules_list'		 => '',
            'hide_for_countries'		 => array(),
            
			'block_ip_rules'		 => false,
			'block_ip_rules_list'		 => '',
            'enable_ip_auto_block'		 => false,
			'max_number_attempts'		 => 5,
			'lockout_period'		 => 10,
            'block_geo_captcha_rules'		 => false,
            'block_for_countries'		 => array(),
			
            
			'flush_rules'				 => false
		),
		'version'	 => '2.1.9'
	);

	public static function instance() {
		if ( self::$_instance === null )
			self::$_instance = new self();

		return self::$_instance;
	}

	private function __clone() {}
	public function __wakeup() {}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
        
		// settings
		$this->options = array(
			'general' => array_merge( $this->defaults['general'], get_option( 'math_captcha_options', $this->defaults['general'] ) )
		);

		// actions
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_comments_scripts_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_comments_scripts_styles' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'frontend_comments_scripts_styles' ) );
        
        add_action( 'admin_bar_menu', array( $this, 'modify_admin_bar'), 100 );
        

		// filters
		add_filter( 'plugin_action_links', array( $this, 'plugin_settings_link' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_extend_links' ), 10, 2 );
        
        add_filter('cron_schedules', function($schedules) {
            $schedules['monthly'] = [
                'interval' => 2592000,
                'display'  => 'Once Monthly'
            ];
            return $schedules;
        });
	}
    
    
    public function modify_admin_bar($wp_admin_bar)
    {
        $counter = self::GetAlerts(date("Y-m-d"));
        
        $counter_html = '';
        if ($counter > 0)
        {
            $counter_html = ' <span style="display: inline-block;box-sizing: border-box;padding: 0 5px;min-width: 18px;height: 18px;border-radius: 9px;    background-color: #ca4a1f;color: #fff;font-size: 11px;line-height: 1.6;text-align: center;">'.$counter.'</span>';
        }
        
        
        // Get alerts for today
    	$wp_admin_bar->add_menu( array(
    		'id'    => 'wpmc-toolbar-alerts',
    		'title' => '<span class="ab-icon dashicons dashicons-chart-line"></span><span class="ab-label">Captcha Logs'.$counter_html.'</span>',
    		'parent'=> false,
    		'href' => admin_url('options-general.php?page=math-captcha&tab=logs'),
    	));
    }
    
    public function GetAlerts($date)
    {
        $wp_content_dir = WP_CONTENT_DIR.'/uploads';
        
        $folder = $wp_content_dir.'/logs';
        if (!file_exists($folder))
        {
            mkdir($folder);
            $fp = fopen($folder.'/.htaccess', 'w');
            fwrite($fp, 'deny from all');
            fclose($fp);
        }
        
        $folder = $wp_content_dir.'/logs/mathcaptcha';
        if (!file_exists($folder))
        {
            mkdir($folder);
            $fp = fopen($folder.'/.htaccess', 'w');
            fwrite($fp, 'deny from all');
            fclose($fp);
        }
        
        $file = $folder.'/'.$date.'.log';
        if (!file_exists($file)) return 0;
        else return filesize($file);
    }

	/**
	 * Activation.
	 */
	public function activation() {
		add_option( 'math_captcha_options', $this->defaults['general'], '', 'no' );
		add_option( 'math_captcha_version', $this->defaults['version'], '', 'no' );
	}

	/**
	 * Deactivation.
	 */
	public function deactivation() {
		if ( $this->options['general']['deactivation_delete'] )
			delete_option( 'math_captcha_options' );
			delete_option( 'math_captcha_lic' );
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'math-captcha', false, MATH_CAPTCHA_REL_PATH . 'languages/' );
	}

	/**
	 * Enqueue admin scripts and styles.
	 * 
	 * @param string $page
	 */
	public function admin_comments_scripts_styles( $page ) {
		if ( $page === 'settings_page_math-captcha' ) {
			wp_register_style(
				'math-captcha-admin', MATH_CAPTCHA_URL . '/css/admin.css'
			);

			wp_enqueue_style( 'math-captcha-admin' );

			wp_register_script(
				'math-captcha-admin-settings', MATH_CAPTCHA_URL . '/js/admin-settings.js', array( 'jquery' )
			);

			wp_enqueue_script( 'math-captcha-admin-settings' );

			wp_localize_script(
				'math-captcha-admin-settings', 'mcArgsSettings', array(
				'resetToDefaults' => __( 'Are you sure you want to reset these settings to defaults?', 'math-captcha' )
				)
			);
		}
	}

	/**
	 * Enqueue frontend scripts and styles
	 */
	public function frontend_comments_scripts_styles() {
		wp_register_style(
			'math-captcha-frontend', MATH_CAPTCHA_URL . '/css/frontend.css'
		);

		wp_enqueue_style( 'math-captcha-frontend' );
	}

	/**
	 * Add links to support forum
	 * 
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	public function plugin_extend_links( $links, $file ) {
		if ( ! current_user_can( 'install_plugins' ) )
			return $links;

		$plugin = plugin_basename( __FILE__ );

		return $links;
	}

	/**
	 * Add links to settings page
	 * 
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	function plugin_settings_link( $links, $file ) {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) )
			return $links;

		static $plugin;

		$plugin = plugin_basename( __FILE__ );

		if ( $file == $plugin ) {
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php' ) . '?page=math-captcha', __( 'Settings', 'math-captcha' ) );
			array_unshift( $links, $settings_link );
		}

		return $links;
	}

}

function Math_Captcha() {
	static $instance;

	// first call to instance() initializes the plugin
	if ( $instance === null || ! ($instance instanceof Math_Captcha) )
		$instance = Math_Captcha::instance();

	return $instance;
}


Math_Captcha();