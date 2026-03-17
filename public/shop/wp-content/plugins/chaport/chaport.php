<?php

/**
 * Plugin Name: Chaport — Live Chat & Chatbots
 * Description: Modern live chat plugin for WordPress. Powerful features: multi-channel, chatbots, customization, etc. Free plan. Unlimited chats & websites.
 * Version: 1.1.9
 * Author: Chaport
 * Author URI: https://www.chaport.com/
 * Text Domain: chaport
 * Domain Path: /languages
 * License: MIT
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

require_once(dirname(__FILE__) . '/includes/models/chaport_app_id.php');
require_once(dirname(__FILE__) . '/includes/models/chaport_installation_code.php');
require_once(dirname(__FILE__) . '/includes/renderers/chaport_installation_code_renderer.php');
require_once(dirname(__FILE__) . '/includes/renderers/chaport_app_id_renderer.php');

return ChaportPlugin::bootstrap();

final class ChaportPlugin {
	// Minimum required version of Wordpress for this plugin to work
	const WP_MAJOR = 2;
	const WP_MINOR = 8;

	private $is_disallow_unfiltered_html;
	private static $instance; // singleton
	public static function bootstrap() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() { // constructable via ChaportPlugin::bootstrap()
		$this->is_disallow_unfiltered_html = true;

		add_action('plugins_loaded', array($this, 'load_textdomain'));
		add_action('admin_enqueue_scripts', array($this, 'handle_admin_enqueue_scripts') );
		add_action('admin_menu', array($this, 'handle_admin_menu'));
		add_action('admin_init', array($this, 'handle_admin_init'));
		add_action('wp_footer', array($this, 'render_chaport_code'));

		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'handle_plugin_actions'));
	}

	public function wp_version_is_compatible() {
		global $wp_version;
		$version = array_map('intval', explode('.', $wp_version));
		return $version[0] > self::WP_MAJOR || ($version[0] === self::WP_MAJOR && $version[1] >= self::WP_MINOR);
	}

	public function load_textdomain() {
		load_plugin_textdomain('chaport', false, basename(dirname(__FILE__)) . '/languages');
	}

	public function handle_admin_enqueue_scripts($hook) {
		// Include styles _only_ on Chaport Settings page
		if ($hook === 'settings_page_chaport') {
			wp_enqueue_style('chaport', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0.0');
			wp_enqueue_script('chaport', plugin_dir_url(__FILE__) . 'assets/js/toggle.js', array(), '1.0.0', array('in_footer' => true ));
		}
	}

	public function handle_admin_menu() {
		add_options_page(
			__('Chaport Settings', 'chaport'), // $page_title
			__('Chaport', 'chaport'), // $menu_title
			'manage_options', // $capability
			'chaport', // $menu_slug
			array($this, 'render_settings_page') // $function (callback)
		);
	}

	public function handle_admin_init() {
		$this->is_disallow_unfiltered_html = (
			defined('DISALLOW_UNFILTERED_HTML') && DISALLOW_UNFILTERED_HTML
		);

		// register_setting('chaport_options', 'chaport_options');
		register_setting('chaport_options', 'chaport_options', array($this, 'sanitize_options'));

		add_settings_section(
			'chaport_general_settings', // $id
			__('Chaport Settings', 'chaport'), // $title
			array($this, 'render_chaport_general_settings'), // $callback
			'chaport' // $page
		);

		add_settings_field(
			'chaport_installation_type_field', // $id
			__('Installation type', 'chaport'), // $title
			array($this, 'render_installation_type_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);

		add_settings_field(
			'chaport_app_id_field', // $id
			__('App ID', 'chaport'), // $title
			array($this, 'render_app_id_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);

		add_settings_field(
			'chaport_app_installation_code_field', // $id
			__('Installation code', 'chaport'), // $title
			array($this, 'render_installation_code_field'), // $callback
			'chaport', // $page
			'chaport_general_settings' //$section
		);
	}

	public function handle_plugin_actions($links) {
		// Build and escape the URL.
		$url = add_query_arg(
			'page',
			'chaport',
			get_admin_url() . 'admin.php'
		);
		// Create the link.
		$settings_link = sprintf(
			"<a href='%s'>%s</a>",
			esc_url( $url ),
			esc_html__( 'Settings', 'chaport' )
		);
		// Adds the link to the end of the array.
		array_push(
			$links,
			$settings_link
		);
		return $links;
	}

	public function sanitize_options($input) {
		// Preserve existing saved values to avoid erasing other fields
		$options = get_option('chaport_options', array());

		$output = array();
		$output['installation_type'] = (isset($input['installation_type']) && $input['installation_type'] === 'installationCode') ? 'installationCode' : 'appId';

		if ($this->is_disallow_unfiltered_html) {
			// Revert previous settings
			$output['installation_code'] = isset($options['installation_code']) ? $options['installation_code'] : '';
		} else {
			$output['installation_code'] = isset($input['installation_code']) ? trim($input['installation_code']) : (isset($options['installation_code']) ? $options['installation_code'] : '');
		}

		// Disallow saving custom code if DISALLOW_UNFILTERED_HTML is set
		if (
				$output['installation_type'] === 'installationCode'
				&& $this->is_disallow_unfiltered_html
		) {
			// Block saving, show error, revert to previous value
			add_settings_error(
					'chaport_options', // Setting slug
					'chaport_installation_code_error', // Error code
					__('You are not allowed to save custom installation code. Please use the App ID method instead, or contact your host administrator to add it for you.', 'chaport'), // Message
					'error'
			);
			
			// Revert previous settings
			$output['installation_type'] = isset($options['installation_type']) ? $options['installation_type'] : 'appId';
			$output['app_id'] = isset($options['app_id']) ? $options['app_id'] : '';
		} else {
			$output['app_id'] = isset($input['app_id']) ? trim($input['app_id']) : (isset($options['app_id']) ? $options['app_id'] : '');
		}

		return $output;
	}

	public function get_options() {
		$options = get_option('chaport_options', array());
		$sanitized = array();
		$sanitized['app_id'] = isset($options['app_id']) ? trim($options['app_id']) : '';
		$sanitized['installation_code'] = isset($options['installation_code']) ? trim($options['installation_code']) : '';
		$sanitized['installation_type'] = isset($options['installation_type']) && $options['installation_type'] === 'installationCode' ? 'installationCode' : 'appId';
		return $sanitized;
	}

	public function render_chaport_general_settings() {
		$status_message = __('Not configured.', 'chaport'); // Default status message
		$status_class = 'chaport-status-warning'; // Default status class

		$options = $this->get_options();
		if (!isset($options['app_id'])) {
			$options['app_id'] = '';
		}
		if (!isset($options['installation_code'])) {
			$options['installation_code'] = '';
		}
		if (!isset($options['installation_type'])) {
			$options['installation_type'] = 'appId';
		}

		if (!empty($options['app_id']) && $options['installation_type'] === 'appId') {
			if (ChaportAppId::isValid($options['app_id'])) {
				$status_message = __('Configured.', 'chaport');
				$status_class = 'chaport-status-ok';
			} else {
				$status_message = __('Error. Invalid App ID.', 'chaport');
				$status_class = 'chaport-status-error';
			}
		} elseif (!empty($options['installation_code']) && $options['installation_type'] === 'installationCode') {
			if (ChaportInstallationCode::isValid($options['installation_code'])) {
				$status_message = __('Configured.', 'chaport');
				$status_class = 'chaport-status-ok';
			} else {
				$status_message = __('Error. Invalid Installation Code.', 'chaport');
				$status_class = 'chaport-status-error';
			}
		}

		require(dirname(__FILE__) . '/includes/snippets/chaport_status_snippet.php');

		if ($this->is_disallow_unfiltered_html) {
			echo '<div class="chaport-status-box chaport-status-warning">';
			echo esc_html__('Custom installation code is disabled for site admins. Only host administrators can manage custom JavaScript code due to the current Wordpress security settings.', 'chaport');
			echo '</div>';
		}
	}

	public function render_app_id_field() {
		$options = $this->get_options();

		printf(
			"<input id='chaport_app_id_field' name='chaport_options[app_id]' size='40' type='text' value='%s' />",
			esc_attr($options['app_id'])
		);
	}

	public function render_installation_code_field() {
		if (!$this->is_disallow_unfiltered_html) {
			$options = $this->get_options();

			echo "<textarea id='chaport_app_installation_code_field' name='chaport_options[installation_code]' rows='10' cols='60'>";
			echo esc_textarea($options['installation_code']);
			echo "</textarea>";
		} else {
			echo "<div id='chaport_app_installation_code_field'>" . esc_html__('Unavailable due to security settings', 'chaport') . "</div>";
		}
	}

	public function render_installation_type_field() {
		$options = $this->get_options();
		$input_array = array(
			'appId' => array(
				'class' => 'chaport-default chaport-left',
				'id' => 'chaport_default_app_id',
				'value' => 'appId',
				'onclick' => 'ChooseAppId()',
				'label' => __('Default', 'chaport')
			),
			'installationCode' => array(
				'class' => 'chaport-default chaport-right',
				'id' => 'chaport_default_installation_code',
				'value' => 'installationCode',
				'onclick' => $this->is_disallow_unfiltered_html ? 'javascript: void(0)' : 'ChooseInstallationCode()',
				'label' => __('Installation code', 'chaport')
			)
		);

		if ($options['installation_type'] !== 'installationCode') {
			$options['installation_type'] = 'appId';
		}

		echo "<div class='switch-chaport' id='chaport_installation_type_field'>\n";
		foreach ($input_array as $value) {
			printf(
				"<input type='radio' name='chaport_options[installation_type]' class='%s' id='%s' value='%s' onclick='%s'%s%s>\n",
				esc_attr( $value['class'] ),
				esc_attr( $value['id'] ),
				esc_attr( $value['value'] ),
				esc_attr( $value['onclick'] ),
				$options['installation_type'] === $value['value'] ? ' checked' : '',
				( $this->is_disallow_unfiltered_html && $value['value'] === 'installationCode' ) ? ' disabled' : ''
			);

			printf(
				"<label for='%s' class='btn'>%s</label>\n",
				esc_attr( $value['id'] ),
				esc_html( $value['label'] )
			);
		};
		echo "</div>";
	}

	public function render_settings_page() {
		if (!current_user_can('manage_options')) {
			wp_die(esc_html__("You don't have access to this page", 'chaport'));
		}

		require(dirname(__FILE__) . '/includes/snippets/chaport_settings_snippet.php');
	}

	public function render_chaport_code() {
		// ignore requests to widgets.php for legacy widgets
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if (isset($_GET['legacy-widget-preview'])) {
			return;
		}
		if (!$this->wp_version_is_compatible() || is_feed() || is_robots() || is_trackback() || is_embed()) {
			return;
		}

		$options = $this->get_options();
		$app_id = $options['app_id'];
		$installation_code = $options['installation_code'];
		$options['installation_type'] = $options['installation_type'];
		$user_settings = wp_get_current_user();

		if (!empty($app_id) && ChaportAppId::isValid($app_id) && ($options['installation_type'] === 'appId')) {
			$renderer = new ChaportAppIdRenderer(ChaportAppId::fromString($app_id));
		} elseif(!empty($installation_code) && ChaportInstallationCode::isValid($installation_code) && ($options['installation_type'] === 'installationCode')){
			$renderer = new ChaportInstallationCodeRenderer(ChaportInstallationCode::fromString($installation_code));
		} else {
			return;
		}

		if (!empty($user_settings->user_email)) {
			$renderer->setUserEmail($user_settings->user_email);
		}
		if (!empty($user_settings->display_name)) {
			$renderer->setUserName($user_settings->display_name);
		}

		$renderer->render();
	}
}
