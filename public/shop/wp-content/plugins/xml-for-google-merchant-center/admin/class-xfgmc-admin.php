<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.9 (23-12-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Admin {

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
	 * @va string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 * @param string $plugin_name  The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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
		wp_enqueue_style( 'jquery-ui-core' );

		wp_enqueue_style(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'css/xfgmc-admin.css',
			[],
			$this->version,
			'all'
		);

		// Color Picker - place 1 from 4
		wp_enqueue_style( 'wp-color-picker' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
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

		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url( __FILE__ ) . 'js/xfgmc-admin.js',
			[ 'jquery-ui-sortable', 'jquery' ],
			$this->version,
			false
		);

		// Color Picker - place 2 from 4
		wp_enqueue_script( 'wp-color-picker' );

		// select2 - place 2 from 5
		wp_enqueue_style(
			'select2',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css'
		);
		wp_enqueue_script(
			'select2',
			'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
			[ 'jquery' ]
		);
		wp_enqueue_script(
			'wplspms_orders',
			plugin_dir_url( __FILE__ ) . 'js/select2.js',
			[ 'jquery', 'select2' ]
		);
		// end select2 - place 2 from 5

	}

	/**
	 * Register the classes for the admin area.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function enqueue_classes() {

		new XFGMC_Feedback( [
			'plugin_version' => XFGMC_PLUGIN_VERSION,
			'logs_url' => XFGMC_PLUGIN_UPLOADS_DIR_URL . '/xml-for-google-merchant-center.log',
			'logs_path' => XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/xml-for-google-merchant-center.log'
		] );
		new ICPD_Promo( 'xfgmc' );

	}

	/**
	 * Print scripts in the footer of the admin panel.
	 * 
	 * Function for `admin_footer` action-hook.
	 *
	 * @since 0.1.0
	 * 
	 * @param string $data The data to print.
	 * 
	 * @return void
	 */
	public function print_admin_footer_script( $data ) {

		// Color Picker - place 3 from 4
		// https://wp-kama.ru/id_4621/vyibora-tsveta-iris-color-picker-v-wordpress.html 
		// http://automattic.github.io/Iris/
		?>
		<script type="text/javascript">jQuery(document).ready(function ($) {
				var myOptions = {
					// устанавливает цвет по умолчанию, также цвет по умолчанию из атрибута value у input
					defaultColor: false,
					// функция обратного вызова, срабатывающая каждый раз при выборе цвета (когда водите мышкой по палитре)
					change: function (event, ui) { },
					// функция обратного вызова, срабатывающая при очистке (сбросе) цвета
					clear: function () { },
					// спрятать ли выбор цвета при загрузке палитра будет появляться при клике
					hide: true,
					// показывать ли группу стандартных цветов внизу палитры 
					// можно добавить свои цвета указав их в массиве: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']
					palettes: true
				}
				$('#xfgmc_color_picker').wpColorPicker(myOptions);
			});</script>
	<?php // HACK: по хорошему нужно в цикле парсить поля с ColorPicker и выводит ID в скрипте автоматически 

	}

	/**
	 * The callback function. Usage in `select2` fields.
	 * 
	 * Function for `wp_ajax_xfgmc_select2` action-hook.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function select2_get_posts_ajax_callback() {

		// we will pass post IDs and titles to this array
		$return = [];

		// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
		$search_results = new WP_Query( [
			's' => $_GET['q'], // the search query
			'post_status' => 'publish', // if you don't want drafts to be returned
			'post_type' => [ 'product', 'product_variation' ],
			'ignore_sticky_posts' => 1,
			'posts_per_page' => 50 // how much to show at once
		] );
		if ( $search_results->have_posts() ) {
			while ( $search_results->have_posts() ) {
				$search_results->the_post();
				// shorten the title a little
				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
				$return[] = [ $search_results->post->ID, $title . ' (' . $search_results->post->post_name . ')' ]; // array( Post ID, Post Title )
			}
		}
		echo json_encode( $return );
		die;

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu. 
	 * Add a settings page for this plugin to the Admin menu.
	 * Function for `admin_menu` action-hook.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			'XML for Google Merchant Center',
			__( 'XFGMC', 'xml-for-google-merchant-center' ),
			'manage_woocommerce',
			$this->plugin_name,
			[ $this, 'display_plugin_settings_page' ],
			plugin_dir_url( __FILE__ ) . 'icons/xml-18x18.svg',
			56
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Debug page', 'xml-for-google-merchant-center' ),
			__( 'Debug page', 'xml-for-google-merchant-center' ),
			'manage_woocommerce',
			$this->plugin_name . '-debug',
			[ $this, 'display_plugin_debug_page' ]
		);

		add_submenu_page(
			$this->plugin_name,
			__( 'Extensions', 'xml-for-google-merchant-center' ),
			sprintf(
				'<span style="font-weight: 700; text-transform: uppercase;">%s</span>',
				__( 'More features', 'xml-for-google-merchant-center' )
			),
			'manage_woocommerce',
			$this->plugin_name . '-extensions',
			[ $this, 'display_plugin_extensions_page' ]
		);

	}

	/**
	 * Render the Settings page for this plugin.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function display_plugin_settings_page() {

		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
		}
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings-page/class-xfgmc-settigs-page.php';
		$settings_page = new XFGMC_Settings_Page();
		$settings_page->render();

	}

	/**
	 * Render the Debug page for this plugin.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function display_plugin_debug_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/debug-page/class-xfgmc-debug-page.php';
		$debug_page = new XFGMC_Debug_Page();
		$debug_page->render();

	}

	/**
	 * Render the Extensions page for this plugin.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function display_plugin_extensions_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/extensions-page/class-xfgmc-extensions-page.php';
		$debug_page = new XFGMC_Extensions_Page();
		$debug_page->render();

	}

	/**
	 * Listen submits buttons. 
	 * 
	 * Function for `admin_init` action-hook.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function listen_submits() {

		// сохранение настроек фида
		if ( isset( $_REQUEST['xfgmc_submit_action'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ) ) {
				$this->save_plugin_option();
			}
		}

		// создание фида
		if ( isset( $_REQUEST['xfgmc_submit_action_add_new_feed'] ) ) {
			if ( ! empty( $_POST )
				&& check_admin_referer( 'xfgmc_nonce_action_add_new_feed', 'xfgmc_nonce_field_add_new_feed' ) ) {
				$this->add_new_feed();
			}
		}

		// массовое удаление фидов по чекбоксу checkbox_xml_file
		if ( isset( $_GET['xfgmc_form_id'] ) && ( $_GET['xfgmc_form_id'] === 'xfgmc_wp_list_table' ) ) {
			if ( is_array( $_GET['checkbox_xml_file'] ) && ! empty( $_GET['checkbox_xml_file'] ) ) {
				if ( check_admin_referer( 'xfgmc_nonce_action_f', 'xfgmc_nonce_field_f' ) ) {
					if ( $_GET['action'] === 'delete' || $_GET['action2'] === 'delete' ) {
						$this->delete_feed();
					}
				}
			}
		}

		// дублировать фид
		if ( isset( $_GET['feed_id'] )
			&& isset( $_GET['action'] )
			&& sanitize_text_field( $_GET['action'] ) === 'duplicate'
		) {
			$feed_id = (string) sanitize_text_field( $_GET['feed_id'] );
			if ( wp_verify_nonce( $_GET['_wpnonce'], 'nonce_duplicate' . $feed_id ) ) {
				$this->duplicate_feed( $feed_id );
			}
		}

		// сохранение опций на странице отладки
		if ( isset( $_REQUEST['xfgmc_submit_action_debug_options'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ) ) {
				$this->save_debug_options();
			}
		}

		// очистка файла логов
		if ( isset( $_REQUEST['xfgmc_submit_action_clear_logs'] ) ) {
			if ( ! empty( $_POST ) && check_admin_referer( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ) ) {
				$this->clear_logs();
			}
		}

	}

	/**
	 * Show notifications in the admin panel. 
	 * 
	 * Function for `admin_init` action-hook.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	public function notices() {

		if ( is_multisite() ) {
			$plugin_notifications = get_blog_option( get_current_blog_id(), 'xfgmc_plugin_notifications', [] );
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
		} else {
			$plugin_notifications = get_option( 'xfgmc_plugin_notifications', [] );
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
		}
		if ( $plugin_notifications === 'disabled' ) {
			return;
		}
		if ( ! empty( $settings_arr ) ) {
			$feed_ids_arr = array_keys( $settings_arr );
			if ( ! empty( $feed_ids_arr ) ) {

				for ( $i = 0; $i < count( $feed_ids_arr ); $i++ ) {
					$feed_id_str = (string) $feed_ids_arr[ $i ];

					if ( isset( $settings_arr[ $feed_id_str ]['xfgmc_status_sborki'] ) ) {
						$status_sboki = $settings_arr[ $feed_id_str ]['xfgmc_status_sborki'];

						switch ( $status_sboki ) {
							case '1':
								new ICPD_Set_Admin_Notices(
									sprintf( '<span class="xfgmc_bold">XFGMC:</span> Feed #%s. %s.',
										$feed_id_str,
										__( 'Creating feed headers', 'xml-for-google-merchant-center' )
									),
									'success'
								);
								break;
							case '2':
								$last_element_feed = (int) univ_option_get(
									'xfgmc_last_element_feed_' . $feed_id_str,
									0
								);
								new ICPD_Set_Admin_Notices(
									sprintf( '<span class="xfgmc_bold">XFGMC:</span> Feed #%s. %s. %s: %s',
										$feed_id_str,
										__( 'Creating temporary feed files', 'xml-for-google-merchant-center' ),
										__( 'The number of processed products', 'xml-for-google-merchant-center' ),
										$last_element_feed
									),
									'success'
								);
								break;
							case '3':
								new ICPD_Set_Admin_Notices(
									sprintf( '<span class="xfgmc_bold">XFGMC:</span> Feed #%s. %s.',
										$feed_id_str,
										__( 'Gluing the feed', 'xml-for-google-merchant-center' )
									),
									'success'
								);
								break;
							case '4':
								new ICPD_Set_Admin_Notices(
									sprintf( '<span class="xfgmc_bold">XFGMC:</span> Feed #%s. %s...',
										$feed_id_str,
										__( 'Completing the assembly', 'xml-for-google-merchant-center' )
									),
									'success'
								);
								break;
						}

					}
				}
			}
		}

	}

	/**
	 * Save the plugin option.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function save_plugin_option() {

		$feed_id = sanitize_text_field( $_POST['xfgmc_feed_id_for_save'] );
		common_option_upd(
			'xfgmc_date_save_set',
			current_time( 'timestamp', 1 ),
			'no',
			$feed_id,
			'xfgmc'
		);

		$plugin_date = new XFGMC_Data();
		$options_name_and_default_date_arr = $plugin_date->get_opts_name_and_def_date( 'public' );
		foreach ( $options_name_and_default_date_arr as $option_name => $value ) {
			$save_if_empty = 'no';
			$save_if_empty = apply_filters(
				'xfgmc_f_flag_save_if_empty',
				$save_if_empty,
				[ 'opt_name' => $option_name ]
			);
			$this->save_plugin_set( $option_name, $feed_id, $save_if_empty );
		}
		new ICPD_Set_Admin_Notices( __( 'Updated', 'xml-for-google-merchant-center' ), 'success' );

		$planning_result = self::cron_starting_feed_creation_task_planning( $feed_id );
		if ( true === $planning_result ) {
			new ICPD_Set_Admin_Notices(
				sprintf( '%s. %s: %s',
					__(
						'The task of creating the feed has been queued for completion',
						'xml-for-google-merchant-center'
					),
					__( 'Feed ID', 'xml-for-google-merchant-center' ),
					$feed_id
				),
				'success'
			);
		}

	}

	/**
	 * Save plugin settings.
	 * 
	 * @param string $option_name
	 * @param string $feed_id
	 * @param string $save_if_empty Maybe: `empty_str`, `empty_arr` or `no`.
	 * 
	 * @return void
	 */
	private function save_plugin_set( $option_name, $feed_id, $save_if_empty = 'no' ) {

		if ( isset( $_POST[ $option_name ] ) ) {
			if ( is_array( $_POST[ $option_name ] ) ) {
				// массивы храним отдельно от других параметров
				univ_option_upd( $option_name . $feed_id, maybe_serialize( $_POST[ $option_name ] ) );
			} else {
				$option_value = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $_POST[ $option_name ] );
				common_option_upd( $option_name, $option_value, 'no', $feed_id, 'xfgmc' );
			}
		} else {
			if ( 'empty_str' === $save_if_empty ) {
				common_option_upd(
					$option_name,
					'',
					'no',
					$feed_id,
					'xfgmc'
				);
			}
			if ( 'empty_arr' === $save_if_empty ) {
				// массивы храним отдельно от других параметров
				univ_option_upd( sprintf( '%s%s', $option_name, $feed_id ), maybe_serialize( [] ) );
			}
		}

	}

	/**
	 * Add new feed.
	 * - Creates feed ID folder;
	 * - Adds an element to the array stored in the `xfgmc_settings_arr` option;
	 * - Increase option `xfgmc_last_feed_id` it by one;
	 * - Print notice.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function add_new_feed() {

		$errors = null;
		if ( is_multisite() ) {
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
			$last_feed_id = (int) get_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id', '0' );
		} else {
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
			$last_feed_id = (int) get_option( 'xfgmc_last_feed_id', '0' );
		}
		$new_feed_id_str = (string) $last_feed_id + 1;

		if ( ! is_dir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
			if ( ! mkdir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
				$errors = sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-admin.php; %5$s: %6$s',
					__( 'Folder creation error', 'xml-for-google-merchant-center' ),
					XFGMC_PLUGIN_UPLOADS_DIR_PATH,
					__( 'during the creation of a new feed', 'xml-for-google-merchant-center' ),
					__( 'Line', 'xml-for-google-merchant-center' ),
					__( 'File', 'xml-for-google-merchant-center' ),
					__LINE__
				);
				error_log( $errors, 0 );
			}
		}

		$name_dir = XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $new_feed_id_str;
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				$errors = sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-admin.php; %5$s: %6$s',
					__( 'Folder creation error', 'xml-for-google-merchant-center' ),
					$name_dir,
					__( 'during the creation of a new feed', 'xml-for-google-merchant-center' ),
					__( 'Line', 'xml-for-google-merchant-center' ),
					__( 'File', 'xml-for-google-merchant-center' ),
					__LINE__
				);
				error_log( $errors, 0 );
			}
		}

		if ( null === $errors ) {
			$plugin_date = new XFGMC_Data();
			$settings_arr[ $new_feed_id_str ] = $plugin_date->get_opts_name_and_def_date( 'all' );
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', $settings_arr );
				update_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id', $new_feed_id_str );
			} else {
				update_option( 'xfgmc_settings_arr', $settings_arr );
				update_option( 'xfgmc_last_feed_id', $new_feed_id_str );
			}

			$url = sprintf(
				'%s?page=%s&action=%s&feed_id=%s&current_display=%s',
				admin_url(),
				esc_attr( $_REQUEST['page'] ),
				'edit',
				esc_attr( $new_feed_id_str ),
				'settings_feed',
			);
			wp_safe_redirect( $url );
		} else {
			new ICPD_Set_Admin_Notices(
				sprintf( '%s. ID = %s',
					__(
						'Feed creation error. Failed to create a folder for temporary files',
						'xml-for-google-merchant-center'
					),
					esc_html( $new_feed_id_str )
				),
				'error'
			);
		}

	}

	/**
	 * Delete feed.
	 * - Remove feed ID folder;
	 * - Remove feed file;
	 * - Remove an element to the array stored in the `xfgmc_settings_arr` option;
	 * - Clear CRON scheduled;
	 * - Print notice.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function delete_feed() {

		if ( is_multisite() ) {
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
		} else {
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
		}

		$checkbox_xml_file_arr = $_GET['checkbox_xml_file'];
		for ( $i = 0; $i < count( $checkbox_xml_file_arr ); $i++ ) {
			$feed_id_str = (string) $checkbox_xml_file_arr[ $i ];

			xfgmc_remove_directory( XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $feed_id_str );

			if ( isset( $settings_arr[ $feed_id_str ] ) ) {
				unset( $settings_arr[ $feed_id_str ] );
				if ( is_multisite() ) {
					delete_blog_option( get_current_blog_id(), 'xfgmc_last_element_feed_' . $feed_id_str );
				} else {
					delete_option( 'xfgmc_last_element_feed_' . $feed_id_str );
				}
			}

			wp_clear_scheduled_hook( 'xfgmc_cron_start_feed_creation', [ $feed_id_str ] );
			wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $feed_id_str ] );

			new ICPD_Set_Admin_Notices(
				sprintf( '%s ID = %s %s',
					__( 'Feed with', 'xml-for-google-merchant-center' ),
					esc_html( $feed_id_str ),
					__( 'has been successfully deleted', 'xml-for-google-merchant-center' )
				),
				'success'
			);
		}

		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', $settings_arr );
		} else {
			update_option( 'xfgmc_settings_arr', $settings_arr );
		}

	}

	/**
	 * Duplicate feed.
	 * - Creates feed ID folder;
	 * - Adds an element to the array stored in the `xfgmc_settings_arr` option;
	 * - Increase option `xfgmc_last_feed_id` it by one;
	 * - Print notice.
	 *
	 * @since 0.1.0
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	private function duplicate_feed( $feed_id ) {

		$errors = null;
		if ( is_multisite() ) {
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
			$last_feed_id = (int) get_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id', '0' );
		} else {
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
			$last_feed_id = (int) get_option( 'xfgmc_last_feed_id', '0' );
		}
		$new_feed_id_str = (string) $last_feed_id + 1;

		if ( ! is_dir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
			if ( ! mkdir( XFGMC_PLUGIN_UPLOADS_DIR_PATH ) ) {
				$errors = sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-admin.php; %5$s: %6$s',
					__( 'Folder creation error', 'xml-for-google-merchant-center' ),
					XFGMC_PLUGIN_UPLOADS_DIR_PATH,
					__( 'during the duplicate of a new feed', 'xml-for-google-merchant-center' ),
					__( 'Line', 'xml-for-google-merchant-center' ),
					__( 'File', 'xml-for-google-merchant-center' ),
					__LINE__
				);
				error_log( $errors, 0 );
			}
		}

		$name_dir = XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/feed' . $new_feed_id_str;
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				$errors = sprintf( 'ERROR: %1$s "%2$s" %3$s; %4$s: class-xfgmc-admin.php; %5$s: %6$s',
					__( 'Folder creation error', 'xml-for-google-merchant-center' ),
					$name_dir,
					__( 'during the duplicate of a new feed', 'xml-for-google-merchant-center' ),
					__( 'Line', 'xml-for-google-merchant-center' ),
					__( 'File', 'xml-for-google-merchant-center' ),
					__LINE__
				);
				error_log( $errors, 0 );
			}
		}

		if ( null === $errors ) {
			$new_data_arr = $settings_arr[ $feed_id ];
			// обнулим часть значений т.к фид-клон ещё не создавался
			$new_data_arr['xfgmc_feed_url'] = '';
			$new_data_arr['xfgmc_feed_path'] = '';
			$new_data_arr['xfgmc_date_sborki_start'] = '-'; // 'Y-m-d H:i
			$new_data_arr['xfgmc_date_sborki_end'] = '-'; // 'Y-m-d H:i
			$new_data_arr['xfgmc_date_save_set'] = 0000000001; // 0000000001 - timestamp format
			$new_data_arr['xfgmc_count_products_in_feed'] = '-1';

			$settings_arr[ $new_feed_id_str ] = $new_data_arr;
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', $settings_arr );
				update_blog_option( get_current_blog_id(), 'xfgmc_last_feed_id', $new_feed_id_str );
			} else {
				update_option( 'xfgmc_settings_arr', $settings_arr );
				update_option( 'xfgmc_last_feed_id', $new_feed_id_str );
			}

			$url = sprintf(
				'%s?page=%s&action=%s&feed_id=%s&current_display=%s',
				admin_url(),
				esc_attr( $_REQUEST['page'] ),
				'edit',
				esc_attr( $new_feed_id_str ),
				'settings_feed',
			);
			wp_safe_redirect( $url );
		} else {
			new ICPD_Set_Admin_Notices(
				sprintf( '%s. ID = %s',
					__(
						'Feed duplicate error. Failed to create a folder for temporary files',
						'xml-for-google-merchant-center'
					),
					esc_html( $new_feed_id_str )
				),
				'error'
			);
		}

	}

	/**
	 * Save the plugin debug options.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function save_debug_options() {

		if ( isset( $_POST['xfgmc_keeplogs'] ) ) {
			$keeplogs = sanitize_text_field( $_POST['xfgmc_keeplogs'] );
		} else {
			$keeplogs = 'disabled';
		}

		if ( isset( $_POST['xfgmc_plugin_notifications'] ) ) {
			$plugin_notifications = sanitize_text_field( $_POST['xfgmc_plugin_notifications'] );
		} else {
			$plugin_notifications = 'disabled';
		}

		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'xfgmc_keeplogs', $keeplogs );
			update_blog_option( get_current_blog_id(), 'xfgmc_plugin_notifications', $plugin_notifications );
		} else {
			update_option( 'xfgmc_keeplogs', $keeplogs );
			update_option( 'xfgmc_plugin_notifications', $plugin_notifications );
		}
		new ICPD_Set_Admin_Notices( __( 'Updated', 'xml-for-google-merchant-center' ), 'success' );

	}

	/**
	 * Clear plugin logs.
	 *
	 * @since 0.1.0
	 * 
	 * @return void
	 */
	private function clear_logs() {

		$logs_file_name = XFGMC_PLUGIN_UPLOADS_DIR_PATH . '/xml-for-google-merchant-center.log';
		if ( file_exists( $logs_file_name ) ) {
			$res = unlink( $logs_file_name );
		} else {
			$res = false;
		}
		if ( true === $res ) {
			$message = __( 'Logs were cleared', 'xml-for-google-merchant-center' );
			$class = 'success';
		} else {
			$message = __(
				'Error accessing log file. The log file may have been deleted previously',
				'xml-for-google-merchant-center'
			);
			$class = 'warning';
		}
		new ICPD_Set_Admin_Notices( $message, $class );

	}

	/**
	 * Флаг для того, чтобы работало сохранение настроек если мультиселект пуст.
	 * Function for `xfgmc_f_flag_save_if_empty` action-hook.
	 * 
	 * @param string $save_if_empty
	 * @param array $args_arr
	 * 
	 * @return string
	 */
	public function flag_save_if_empty( $save_if_empty, $args_arr ) {

		if ( ! empty( $_GET ) && isset( $_GET['tab'] ) && $_GET['tab'] === 'tags_settings_tab' ) {
			// ! if ( $args_arr['opt_name'] === 'xfgmc_params_arr' ) {
			// !	$save_if_empty = 'empty_arr';
			// ! }
		}
		if ( ! empty( $_GET ) && isset( $_GET['tab'] ) && $_GET['tab'] === 'filtration_tab' ) {
			if ( $args_arr['opt_name'] === 'xfgmc_no_group_id_arr' ) {
				$save_if_empty = 'empty_arr';
			}
		}
		return $save_if_empty;

	}

	/**
	 * Дополнительная информация для формы обратной связи.
	 * 
	 * Function for `xfgmc_f_feedback_additional_info` action-hook.
	 * 
	 * @param string $additional_info
	 * 
	 * @return string
	 */
	public function feedback_additional_info( $additional_info ) {

		if ( is_multisite() ) {
			$settings_arr = get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', [] );
		} else {
			$settings_arr = get_option( 'xfgmc_settings_arr', [] );
		}
		if ( ! empty( $settings_arr ) ) {
			$feed_ids_arr = array_keys( $settings_arr );
			if ( ! empty( $feed_ids_arr ) ) {
				for ( $i = 0; $i < count( $feed_ids_arr ); $i++ ) {
					$feed_id_str = (string) $feed_ids_arr[ $i ];
					$additional_info .= sprintf( '<h2>Feed # %s</h2>', $feed_id_str );
					// URL-фида
					if ( isset( $settings_arr[ $feed_id_str ]['xfgmc_feed_url'] ) ) {
						$feed_url = $settings_arr[ $feed_id_str ]['xfgmc_feed_url'];
						$feed_rules = $settings_arr[ $feed_id_str ]['xfgmc_xml_rules'];
						$additional_info .= sprintf( '<p>URL: %s</p>', urldecode( $feed_url ) );
						$additional_info .= sprintf( '<p>Придерживаться правил: %s</p>', $feed_rules );
					} else {
						$additional_info .= sprintf( '<p>URL: %s</p>', '-' );
					}
				}
			}
		}
		return $additional_info;

	}

	/**
	 * Разрешим загрузку xml и csv файлов. Function for `upload_mimes` action-hook.
	 * 
	 * @param array $mimes
	 * 
	 * @return array
	 */
	public function add_mime_types( $mimes ) {

		$mimes['csv'] = 'text/csv';
		$mimes['xml'] = 'text/xml';
		$mimes['xml'] = 'text/xml';
		return $mimes;

	}

	/**
	 * Add cron intervals to WordPress. Function for `cron_schedules` action-hook.
	 * 
	 * @param array $schedules
	 * 
	 * @return array
	 */
	public function add_cron_intervals( $schedules ) {

		$schedules['every_minute'] = [
			'interval' => 60,
			'display' => __( 'Every minute', 'xml-for-google-merchant-center' )
		];
		$schedules['three_hours'] = [
			'interval' => 10800,
			'display' => __( 'Every three hours', 'xml-for-google-merchant-center' )
		];
		$schedules['six_hours'] = [
			'interval' => 21600,
			'display' => __( 'Every six hours', 'xml-for-google-merchant-center' )
		];
		$schedules['every_two_days'] = [
			'interval' => 172800,
			'display' => __( 'Every two days', 'xml-for-google-merchant-center' )
		];
		return $schedules;

	}

	/**
	 * The function responsible for starting the creation of the feed.
	 * Function for `xfgmc_cron_start_feed_creation` action-hook.
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	public function do_start_feed_creation( $feed_id ) {

		new XFGMC_Error_Log( sprintf( 'FEED #%1$s; %2$s; %3$s: %4$s; %5$s: %6$s',
			$feed_id,
			__( 'The CRON task for creating a feed has started', 'xml-for-google-merchant-center' ),
			__( 'File', 'xml-for-google-merchant-center' ),
			'class-xfgmc-admin.php',
			__( 'Line', 'xml-for-google-merchant-center' ),
			__LINE__
		) );

		// счётчик завершенных товаров в положение 0.
		univ_option_upd(
			'xfgmc_last_element_feed_' . $feed_id,
			'0',
			'no'
		);

		// запланируем CRON сборки
		$planning_result = self::cron_sborki_task_planning( $feed_id );

		if ( false === $planning_result ) {
			new XFGMC_Error_Log( sprintf(
				'FEED #%1$s; ERROR: %2$s `xfgmc_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
				$feed_id,
				__( 'Failed to schedule a CRON task', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-admin.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
		} else {
			new XFGMC_Error_Log( sprintf(
				'FEED #%1$s; %2$s `xfgmc_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
				$feed_id,
				__( 'Successful CRON task planning', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-admin.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			// сборку начали
			common_option_upd(
				'xfgmc_status_sborki',
				'1',
				'no',
				$feed_id,
				'xfgmc'
			);
			// сразу планируем крон-задачу на начало сброки фида в следующий раз в нужный час
			$run_cron = common_option_get(
				'xfgmc_run_cron',
				'disabled',
				$feed_id,
				'xfgmc'
			);
			if ( in_array( $run_cron, [ 'hourly', 'three_hours', 'six_hours', 'twicedaily', 'daily', 'every_two_days', 'weekly' ] ) ) {
				$arr = wp_get_schedules();
				if ( isset( $arr[ $run_cron ]['interval'] ) ) {
					self::cron_starting_feed_creation_task_planning( $feed_id, $arr[ $run_cron ]['interval'] );
				}
			}
		}

	}

	/**
	 * The function is called every minute until the feed is created or creation is interrupted.
	 * Function for `xfgmc_cron_sborki` action-hook.
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	public function do_it_every_minute( $feed_id ) {

		new XFGMC_Error_Log( sprintf( 'FEED #%1$s; %2$s `xfgmc_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
			$feed_id,
			__( 'The CRON task started', 'xml-for-google-merchant-center' ),
			__( 'File', 'xml-for-google-merchant-center' ),
			'class-xfgmc-admin.php',
			__( 'Line', 'xml-for-google-merchant-center' ),
			__LINE__
		) );

		$generation = new XFGMC_Generation_XML( $feed_id );
		$generation->run();

	}

	/**
	 * Cron starting the feed creation task planning.
	 * 
	 * @param string $feed_id
	 * @param int $delay_second Scheduling task CRON in N seconds.
	 * 
	 * @return bool|WP_Error
	 */
	public static function cron_starting_feed_creation_task_planning( $feed_id, $delay_second = 0 ) {

		$planning_result = false;
		$run_cron = common_option_get(
			'xfgmc_run_cron',
			'disabled',
			$feed_id,
			'xfgmc'
		);

		if ( $run_cron === 'disabled' ) {
			// останавливаем сборку досрочно, если это выбрано в настройках плагина при сохранении
			wp_clear_scheduled_hook( 'xfgmc_cron_start_feed_creation', [ $feed_id ] );
			wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $feed_id ] );
			univ_option_upd(
				'xfgmc_last_element_feed_' . $feed_id,
				0
			);
			common_option_upd(
				'xfgmc_status_sborki',
				'-1',
				'no',
				$feed_id,
				'xfgmc'
			);
		} else {
			wp_clear_scheduled_hook( 'xfgmc_cron_start_feed_creation', [ $feed_id ] );
			if ( ! wp_next_scheduled( 'xfgmc_cron_start_feed_creation', [ $feed_id ] ) ) {
				$cron_start_time = common_option_get(
					'xfgmc_cron_start_time',
					'disabled',
					$feed_id,
					'xfgmc'
				);
				switch ( $cron_start_time ) {
					case 'disabled':
						return false;
					case 'now':
						$cron_interval = current_time( 'timestamp', 1 ) + 2; // добавим 2 сек
						break;
					default:
						$gmt_offset = (float) get_option( 'gmt_offset' );
						$offset_in_seconds = $gmt_offset * 3600;
						$cron_interval = strtotime( $cron_start_time ) - $offset_in_seconds;
						if ( $cron_interval < current_time( 'timestamp', 1 ) ) {
							// если нужный час уже прошел. запланируем на следующие сутки
							$cron_interval = $cron_interval + 86400;
						}
				}

				// планируем крон-задачу на начало сброки фида в нужный час
				$planning_result = wp_schedule_single_event(
					$cron_interval + $delay_second,
					'xfgmc_cron_start_feed_creation',
					[ $feed_id ]
				);
			}
		}

		return $planning_result;

	}

	/**
	 * Cron sborki task planning.
	 * 
	 * @param string $feed_id
	 * @param int $delay_second Scheduling task CRON in N seconds.
	 * 
	 * @return bool|WP_Error
	 */
	public static function cron_sborki_task_planning( $feed_id, $delay_second = 5 ) {

		wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $feed_id ] );
		if ( ! wp_next_scheduled( 'xfgmc_cron_sborki', [ $feed_id ] ) ) {
			$planning_result = wp_schedule_single_event(
				current_time( 'timestamp', 1 ) + $delay_second, // добавим 5 секунд
				'xfgmc_cron_sborki',
				[ $feed_id ]
			);
		} else {
			$planning_result = false;
		}

		return $planning_result;

	}

	/**
	 * Позволяет добавить дополнительные поля на страницу создания элементов таксономии (термина).
	 * Function for `(taxonomy)_add_form_fields` action-hook.
	 * 
	 * @param WP_Term $tag Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function add_meta_product_cat( $term ) {

		?>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Google product category', 'xml-for-google-merchant-center' ); ?>
			</label>
			<input id="xfgmc_google_product_category" type="text" name="xfgmc_cat_meta[xfgmc_google_product_category]"
				value="" />
			<p>
				<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?>.
				<strong>google_product_category</strong>.
				<a href="//support.google.com/merchants/answer/6324436" target="_blank">
					<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
				</a>.
			</p>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Tax category', 'xml-for-google-merchant-center' ); ?>
			</label>
			<input id="xfgmc_tax_category" type="text" name="xfgmc_cat_meta[xfgmc_tax_category]" value="" />
			<p>
				<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>tax_category</strong>.
				<a href="//support.google.com/merchants/answer/7569847" target="_blank">
					<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
				</a>.
			</p>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Size', 'xml-for-google-merchant-center' ); ?>
			</label>
			<select name="xfgmc_cat_meta[xfgmc_size]" id="xfgmc_size">
				<option value="default" selected>
					<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
				</option>
				<?php
				$woo_attributes_arr = get_woo_attributes();
				if ( ! empty( $woo_attributes_arr ) ) {
					for ( $i = 0; $i < count( $woo_attributes_arr ); $i++ ) {
						printf( '<option value="%1$s">%2$s</option>%3$s',
							esc_attr( $woo_attributes_arr[ $i ]['id'] ),
							esc_attr( $woo_attributes_arr[ $i ]['name'] ),
							PHP_EOL
						);
					}
				}
				?>
			</select>
			<p>
				<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size</strong>. <a
					href="//support.google.com/merchants/answer/6324492" target="_blank">
					<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
				</a>.
				<?php esc_html_e(
					'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page',
					'xml-for-google-merchant-center' );
				?>.
			</p>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label for="xfgmc_size_type">
				<?php esc_html_e( 'Size type', 'xml-for-google-merchant-center' ); ?>
			</label>

			<select name="xfgmc_cat_meta[xfgmc_size_type]" id="xfgmc_size_type">
				<option value="default">
					<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
				</option>
				<?php
				$woo_attributes_arr = get_woo_attributes();
				if ( ! empty( $woo_attributes_arr ) ) {
					for ( $i = 0; $i < count( $woo_attributes_arr ); $i++ ) {
						printf( '<option value="%1$s">%2$s</option>%3$s',
							esc_attr( $woo_attributes_arr[ $i ]['id'] ),
							esc_attr( $woo_attributes_arr[ $i ]['name'] ),
							PHP_EOL
						);
					}
				}
				?>
			</select><br />
			<?php esc_html_e( 'In the absence of a substitute', 'xml-for-google-merchant-center' ); ?>:<br />
			<select name="xfgmc_cat_meta[xfgmc_size_type_alt]">
				<option value="default">
					<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
				</option>
				<option value="regular">Regular</option>
				<option value="petite">Petite</option>
				<option value="plus">Plus</option>
				<option value="bigandtall">Big and tall</option>
				<option value="maternity">Maternity</option>
			</select><br />
			<span class="description">
				<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size_type</strong>.
				<?php esc_html_e( 'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page', 'xml-for-google-merchant-center' ); ?>.
			</span>
		</div>
		<div class="form-field term-cat_meta-wrap">
			<label>
				<?php esc_html_e( 'Facebook product category', 'xml-for-google-merchant-center' ); ?>
			</label>
			<input id="xfgmc_fb_product_category" type="text" name="xfgmc_cat_meta[xfgmc_fb_product_category]" value="" />
			<p>
				<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?>.
				<strong>fb_product_category</strong>.
				<a href="//www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944"
					target="_blank">
					<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
				</a>.
			</p>
		</div>
		<?php

	}

	/**
	 * Позволяет добавить дополнительные поля на страницу редактирования элементов таксономии (термина).
	 * Function for `(taxonomy)_edit_form_fields` action-hook.
	 * 
	 * @param WP_Term $tag Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 *
	 * @return void
	 */
	public function edit_meta_product_cat( $term ) {

		global $post; ?>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Google product category', 'xml-for-google-merchant-center' ); ?>
				</label>
			</th>
			<td>
				<input id="xfgmc_google_product_category" type="text" name="xfgmc_cat_meta[xfgmc_google_product_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_google_product_category', true ) ) ?>" />
				<p class="description">
					<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?>.
					<strong>google_product_category</strong>.
					<a href="//support.google.com/merchants/answer/6324436" target="_blank">
						<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>.
				</p>
			</td>
		</tr>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Tax category', 'xml-for-google-merchant-center' ); ?>
				</label>
			</th>
			<td>
				<input id="xfgmc_tax_category" type="text" name="xfgmc_cat_meta[xfgmc_tax_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_tax_category', true ) ) ?>" />
				<p>
					<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>tax_category</strong>.
					<a href="//support.google.com/merchants/answer/7569847" target="_blank">
						<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>.
				</p>
			</td>
		</tr>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Size', 'xml-for-google-merchant-center' ); ?>
				</label>
			</th>
			<td>
				<?php $xfgmc_size = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size', true ) ); ?>
				<select name="xfgmc_cat_meta[xfgmc_size]" id="xfgmc_size">
					<option value="default" <?php selected( $xfgmc_size, 'default' ); ?>>
						<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
					</option>
					<?php
					$woo_attributes_arr = get_woo_attributes();
					if ( ! empty( $woo_attributes_arr ) ) {
						for ( $i = 0; $i < count( $woo_attributes_arr ); $i++ ) {
							$selected = selected( $xfgmc_size, $woo_attributes_arr[ $i ]['id'], false );
							printf( '<option value="%1$s" %2$s>%3$s</option>%4$s',
								esc_attr( $woo_attributes_arr[ $i ]['id'] ),
								esc_attr( $selected ),
								esc_attr( $woo_attributes_arr[ $i ]['name'] ),
								PHP_EOL
							);
						}
					}
					?>
				</select>
				<p class="description">
					<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size</strong>. <a
						href="//support.google.com/merchants/answer/6324492" target="_blank">
						<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>.
					<?php esc_html_e(
						'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page',
						'xml-for-google-merchant-center' );
					?>.
				</p>
			</td>
		</tr>
		<tr class="form-field">
			<?php
			$size_type = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size_type', true ) );
			$size_type_alt = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size_type_alt', true ) );
			?>
			<th scope="row" valign="top"><label for="xfgmc_size_type">
					<?php esc_html_e( 'Size type', 'xml-for-google-merchant-center' ); ?>
				</label></th>
			<td class="overalldesc">
				<select name="xfgmc_cat_meta[xfgmc_size_type]" id="xfgmc_size_type">
					<option value="default" <?php selected( $size_type, 'default' ); ?>>
						<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
					</option>
					<?php
					$woo_attributes_arr = get_woo_attributes();
					if ( ! empty( $woo_attributes_arr ) ) {
						for ( $i = 0; $i < count( $woo_attributes_arr ); $i++ ) {
							$selected = selected( $size_type, $woo_attributes_arr[ $i ]['id'], false );
							printf( '<option value="%1$s" %2$s>%3$s</option>%4$s',
								esc_attr( $woo_attributes_arr[ $i ]['id'] ),
								esc_attr( $selected ),
								esc_attr( $woo_attributes_arr[ $i ]['name'] ),
								PHP_EOL
							);
						}
					}
					?>
				</select><br />
				<?php esc_html_e( 'In the absence of a substitute', 'xml-for-google-merchant-center' ); ?>:<br />
				<select name="xfgmc_cat_meta[xfgmc_size_type_alt]">
					<option value="default" <?php selected( $size_type_alt, 'default' ); ?>>
						<?php esc_html_e( 'Default', 'xml-for-google-merchant-center' ); ?>
					</option>
					<option value="regular" <?php selected( $size_type_alt, 'regular' ); ?>>Regular</option>
					<option value="petite" <?php selected( $size_type_alt, 'petite' ); ?>>Petite</option>
					<option value="plus" <?php selected( $size_type_alt, 'plus' ); ?>>Plus</option>
					<option value="bigandtall" <?php selected( $size_type_alt, 'bigandtall' ); ?>>Big and tall</option>
					<option value="maternity" <?php selected( $size_type_alt, 'maternity' ); ?>>Maternity</option>
				</select><br />
				<span class="description">
					<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size_type</strong>.
					<?php esc_html_e( 'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page', 'xml-for-google-merchant-center' ); ?>.
				</span>
			</td>
		</tr>
		<tr class="form-field term-parent-wrap">
			<th scope="row" valign="top">
				<label>
					<?php esc_html_e( 'Facebook product category', 'xml-for-google-merchant-center' ); ?>
				</label>
			</th>
			<td>
				<input id="xfgmc_fb_product_category" type="text" name="xfgmc_cat_meta[xfgmc_fb_product_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_fb_product_category', true ) ) ?>" />
				<p class="description">
					<?php esc_html_e( 'Optional element', 'xml-for-google-merchant-center' ); ?>.
					<strong>fb_product_category</strong>.
					<a href="//www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944"
						target="_blank">
						<?php esc_html_e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>.
				</p>
			</td>
		</tr>
		<?php

	}

	/**
	 * Сохранение данных в БД. Function for `create_(taxonomy)` and `edited_(taxonomy)` action-hooks.
	 * 
	 * @param int $term_id
	 * 
	 * @return void
	 */
	public function save_meta_product_cat( $term_id ) {

		if ( ! isset( $_POST['xfgmc_cat_meta'] ) ) {
			return;
		}
		$xfgmc_cat_meta = array_map( 'sanitize_text_field', $_POST['xfgmc_cat_meta'] );
		foreach ( $xfgmc_cat_meta as $key => $value ) {
			if ( empty( $value ) ) {
				delete_term_meta( $term_id, $key );
				continue;
			}
			update_term_meta( $term_id, $key, $value );
		}
		return;

	}

	/**
	 * Adds a tab to the product editing page WooCommerce. 
	 * 
	 * Function for `woocommerce_product_data_tabs` filter-hook.
	 * 
	 * @param array $tabs
	 *
	 * @return array
	 */
	public static function add_woocommerce_product_data_tab( $tabs ) {

		$tabs['xfgmc_individual_settings_tab'] = [
			'label' => __( 'XML for Google Merchant Center', 'xml-for-google-merchant-center' ), // название вкладки
			'target' => 'xfgmc_individual_settings_tab', // идентификатор вкладки
			'class' => [ 'hide_if_grouped' ], // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 70 // приоритет вывода
		];
		return $tabs;

	}

	/**
	 * Print styles in the footer of the admin panel. Adds an icon for the XML for Google Merchant Center tab.
	 * 
	 * Function for `admin_footer` action-hook.
	 * 
	 * @see https://rawgit.com/woothemes/woocommerce-icons/master/demo.html
	 * 
	 * @param string $data The data to print.
	 *
	 * @return void
	 */
	public function set_product_data_tab_icon( $data ) {

		printf(
			'<style>#woocommerce-product-data ul.wc-tabs li.%s_options a::before {content: url("%s");}</style>',
			'xfgmc_individual_settings_tab',
			plugin_dir_url( __FILE__ ) . 'icons/xml-13x13.svg'
		);

	}

	/**
	 * Function for `woocommerce_product_data_panels` filter-hook.
	 * 
	 * @return void
	 */
	public static function add_fields_to_product_data_tab() {
		global $post; ?>
		<div id="xfgmc_individual_settings_tab" class="panel woocommerce_options_panel">
			<div class="options_group">
				<h2>
					<strong class="xfgmc_uppercase"><?php esc_html_e(
						'Individual product settings for XML-feed',
						'xml-for-google-merchant-center' ); ?></strong>
				</h2>
			</div>
			<?php do_action( 'xfgmc_prepend_individual_settings_tab', $post ); ?>
			<div class="options_group">
				<h2>
					<strong><?php esc_html_e(
						'Individual product settings for Google Merchatnt Center',
						'xml-for-google-merchant-center' ); ?></strong>
				</h2>
				<div class="xfgmc_notice inline notice woocommerce-message">
					<p>
						<?php esc_html_e( 'Here you can set up individual settings for Google Merchatnt Center', 'xml-for-google-merchant-center' ); ?>.
						<a target="_blank" href="//support.google.com/merchants/answer/7052112">
							<?php esc_html_e( 'Read more on Google', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
				</div>
				<?php
				woocommerce_wp_text_input( [
					'id' => '_xfgmc_google_product_category',
					'label' => sprintf(
						'%s <i>[google_product_category]</i>',
						__( 'Google product category', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_tax_category',
					'label' => sprintf(
						'%s <i>[tax_category]</i>',
						__( 'Tax category', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_select( [
					'id' => '_xfgmc_identifier_exists',
					'label' => sprintf(
						'%s <i>[identifier_exists]</i>',
						__( 'Identifier exists', 'xml-for-google-merchant-center' )
					),
					'options' => [
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'disabled' => __( 'Disabled', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ) . ' (no)',
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' ) . ' (yes)'
					],
					'description' => sprintf(
						'%s <strong>identifier_exists</strong>',
						__( 'Optional element', 'xml-for-google-merchant-center' )
					),
					'desc_tip' => 'true'
				] );

				woocommerce_wp_select( [
					'id' => '_xfgmc_adult',
					'label' => sprintf(
						'%s <i>[adult]</i>',
						__( 'Adult', 'xml-for-google-merchant-center' )
					),
					'options' => [
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'disabled' => __( 'Disabled', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ) . ' (no)',
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' ) . ' (yes)'
					],
					'description' => sprintf(
						'%s <strong>adult</strong>',
						__( 'Optional element', 'xml-for-google-merchant-center' )
					),
					'desc_tip' => 'true'
				] );

				woocommerce_wp_select( [
					'id' => '_xfgmc_condition',
					'label' => sprintf(
						'%s <i>[condition]</i>',
						__( 'Сondition', 'xml-for-google-merchant-center' )
					),
					'options' => [
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'disabled' => __( 'Disabled', 'xml-for-google-merchant-center' ),
						'new' => __( 'New', 'xml-for-google-merchant-center' ),
						'refurbished' => __( 'Refurbished', 'xml-for-google-merchant-center' ),
						'used' => __( 'Used', 'xml-for-google-merchant-center' )
					],
					'description' => sprintf(
						'%s <strong>condition</strong>',
						__( 'Optional element', 'xml-for-google-merchant-center' )
					),
					'desc_tip' => 'true'
				] );

				woocommerce_wp_select( [
					'id' => '_xfgmc_is_bundle',
					'label' => sprintf(
						'%s <i>[is_bundle]</i>',
						__( 'Kit', 'xml-for-google-merchant-center' )
					),
					'options' => [
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'disabled' => __( 'Disabled', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ),
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' )
					],
					'description' => sprintf(
						'%s <strong>is_bundle</strong>',
						__( 'Optional element', 'xml-for-google-merchant-center' )
					),
					'desc_tip' => 'true'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_multipack',
					'label' => sprintf(
						'%s <i>[multipack]</i>',
						__( 'Multipack', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'number',
					'custom_attributes' => [
						'step' => '1',
						'min' => '0',
					],
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_unit_pricing_measure',
					'label' => sprintf(
						'%s <i>[unit_pricing_measure]</i>',
						__( 'Unit pricing measure', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_unit_pricing_base_measure',
					'label' => sprintf(
						'%s <i>[unit_pricing_base_measure]</i>',
						__( 'Unit pricing base measure', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );


				woocommerce_wp_text_input( [
					'id' => '_xfgmc_shipping_label',
					'label' => sprintf(
						'%s <i>[shipping_label]</i>',
						__( 'Definition', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_store_code',
					'label' => sprintf(
						'%s <i>[store_code]</i>',
						__( 'Definition', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_min_handling_time',
					'label' => sprintf(
						'%s <i>[min_handling_time]</i>',
						__( 'Definition', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );

				woocommerce_wp_text_input( [
					'id' => '_xfgmc_max_handling_time',
					'label' => sprintf(
						'%s <i>[max_handling_time]</i>',
						__( 'Definition', 'xml-for-google-merchant-center' )
					),
					'description' => '',
					'desc_tip' => 'true',
					'type' => 'text'
				] );
				?>
			</div>
			<div class="options_group">
				<h2>
					<strong><?php esc_html_e( 'Custom labels', 'xml-for-google-merchant-center' ); ?></strong>
				</h2>
				<div class="xfgmc_notice inline notice woocommerce-message">
					<p>
						<?php esc_html_e( 'Here you can set up individual "Custom Labels" for the product', 'xml-for-google-merchant-center' ); ?>.
						<a target="_blank" href="//support.google.com/merchants/answer/6324473">
							<?php esc_html_e( 'Read more on Google', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
				</div>
				<?php
				for ( $i = 0; $i < 5; $i++ ) {
					$post_meta_name = '_xfgmc_custom_label_' . (string) $i;
					woocommerce_wp_text_input( [
						'id' => $post_meta_name,
						'label' => sprintf(
							'%s <i>[custom_label_%s]</i>',
							__( 'Definition', 'xml-for-google-merchant-center' ),
							(string) $i
						),
						'description' => sprintf( '%s custom_label_%s.',
							__( 'Definition', 'xml-for-google-merchant-center' ),
							(string) $i
						),
						'desc_tip' => 'true',
						'type' => 'text'
					] );
				}
				?>
			</div>

			<div class="options_group">
				<h2>
					<strong><?php esc_html_e( 'Individual product settings for Facebook', 'xml-for-google-merchant-center' ); ?></strong>
				</h2>
				<div class="xfgmc_notice inline notice woocommerce-message">
					<p>
						<?php esc_html_e( 'Here you can set up individual settings for Facebook', 'xml-for-google-merchant-center' ); ?>.
						<a target="_blank"
							href="//www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944">
							<?php esc_html_e( 'Read more on Facebook', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</p>
				</div>
				<?php
				woocommerce_wp_text_input( [
					'id' => '_xfgmc_fb_product_category',
					'label' => __( 'Facebook product category', 'xml-for-google-merchant-center' ),
					'description' => sprintf( '%s fb_product_category',
						__( 'Optional element', 'xml-for-google-merchant-center' )
					),
					'desc_tip' => 'true',
					'type' => 'text'
				] );
				?>
			</div>
			<?php do_action( 'xfgmc_append_individual_settings_tab', $post ); ?>
		</div>
		<?php
	}

	/**
	 * Adding fields to the "Inventory" tab.
	 * 
	 * Function for `woocommerce_product_options_stock` action-hook.
	 * 
	 * @return void
	 */
	function add_fields_to_inventory_product_data_tab() {

		woocommerce_wp_text_input( [
			'id' => '_xfgmc_barcode',
			'label' => __( 'Barcode for XML', 'xml-for-google-merchant-center' ),
			'placeholder' => sprintf( '%s: 978020137962', __( 'For example', 'xml-for-google-merchant-center' ) ),
			'description' => sprintf( '%s "_xfgmc_barcode" %s. %s get_post_meta',
				__( 'The data of this field is stored in the', 'xml-for-google-merchant-center' ),
				__( 'meta field', 'xml-for-google-merchant-center' ),
				__( 'You can always display them in your website template using', 'xml-for-google-merchant-center' )
			),
			'type' => 'text',
			'desc_tip' => true
		] );

	}

	/**
	 * Save woocommerce product meta field. 
	 * 
	 * Function for `save_post` action-hook.
	 * 
	 * @param int $post_id
	 * @param WP_Post $post Post object.
	 * @param bool $update (`true` — это обновление записи; `false` — это добавление новой записи).
	 * 
	 * @return void
	 */
	public function save_product_post_meta( $post_id, $post, $update ) {

		if ( $post->post_type !== 'product' ) {
			return; // если это не товар вукомерц
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return; // если это ревизия
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return; // если это автосохранение ничего не делаем
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return; // если юзер не имеет прав
		}

		$post_meta_arr = [
			'_xfgmc_google_product_category',
			'_xfgmc_tax_category',
			'_xfgmc_identifier_exists',
			'_xfgmc_adult',
			'_xfgmc_condition',
			'_xfgmc_is_bundle',
			'_xfgmc_multipack',
			'_xfgmc_unit_pricing_measure',
			'_xfgmc_unit_pricing_base_measure',
			'_xfgmc_shipping_label',
			'_xfgmc_store_code',
			'_xfgmc_min_handling_time',
			'_xfgmc_max_handling_time',
			'_xfgmc_custom_label_0',
			'_xfgmc_custom_label_1',
			'_xfgmc_custom_label_2',
			'_xfgmc_custom_label_3',
			'_xfgmc_custom_label_4',
			'_xfgmc_fb_product_category'
		];
		$post_meta_arr = apply_filters(
			'xfgmc_f_post_meta_arr',
			$post_meta_arr
		);
		$this->save_post_meta( $post_meta_arr, $post_id );
		$this->run_feeds_upd( $post_id );

	}

	/**
	 * Save post meta.
	 * 
	 * @param array $post_meta_arr
	 * @param int $post_id
	 * 
	 * @return void
	 */
	private function save_post_meta( $post_meta_arr, $post_id ) {

		for ( $i = 0; $i < count( $post_meta_arr ); $i++ ) {
			$meta_name = $post_meta_arr[ $i ];
			if ( isset( $_POST[ $meta_name ] ) ) {
				if ( empty( $_POST[ $meta_name ] ) ) {
					delete_post_meta( $post_id, $meta_name );
				} else {
					update_post_meta(
						$post_id,
						$meta_name,
						sanitize_text_field( $_POST[ $meta_name ] )
					);
				}
			}
		}

	}

	/**
	 * Add fields to variable settings.
	 * 
	 * Function for `woocommerce_product_after_variable_attributes` action-hook.
	 * 
	 * @param int     $loop           Position in the loop.
	 * @param array   $variation_data Variation data.
	 * @param WP_Post $variation      Post data. 
	 * 
	 * @return void
	 */
	public function add_fields_to_variable_settings( $loop, $variation_data, $variation ) {

		echo '<div>';
		woocommerce_wp_text_input( [
			'id' => '_xfgmc_barcode[' . $variation->ID . ']',
			'label' => __( 'Barcode for XML', 'xml-for-google-merchant-center' ),
			'placeholder' => sprintf( '%s: 978020137962', __( 'For example', 'xml-for-google-merchant-center' ) ),
			'description' => sprintf( '%s "_xfgmc_barcode" %s. %s get_post_meta',
				__( 'The data of this field is stored in the', 'xml-for-google-merchant-center' ),
				__( 'meta field', 'xml-for-google-merchant-center' ),
				__( 'You can always display them in your website template using', 'xml-for-google-merchant-center' )
			),
			'type' => 'text',
			'desc_tip' => 'true',
			'wrapper_class' => 'variable_description0_field form-row form-row-full',
			'value' => get_post_meta( $variation->ID, '_xfgmc_barcode', true )
		] );
		echo '</div>';

	}

	/**
	 * Save pwoocommerce variation product meta field. 
	 * 
	 * Function for `woocommerce_save_product_variation` action-hook.
	 * 
	 * @param int $post_id
	 * 
	 * @return void
	 */
	public function save_variation_product_post_meta( $post_id ) {

		if ( wp_is_post_revision( $post_id ) ) {
			return; // если это ревизия
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return; // если это автосохранение ничего не делаем
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return; // проверяем права юзера
		}

		// обращаем внимание на двойное подчёркивание в $woocommerce__xfgmc_barcode
		$woocommerce__xfgmc_barcode = $_POST['_xfgmc_barcode'][ $post_id ];
		if ( isset( $woocommerce__xfgmc_barcode ) && ! empty( $woocommerce__xfgmc_barcode ) ) {
			update_post_meta( $post_id, '_xfgmc_barcode', esc_attr( $woocommerce__xfgmc_barcode ) );
		} else {
			update_post_meta( $post_id, '_xfgmc_barcode', '' );
		}

	}

	/**
	 * Проверяет, нужно ли запускать обновление фида при обновлении товара и при необходимости запускает процесс.
	 * 
	 * @param int $post_id
	 * 
	 * @return void
	 */
	public function run_feeds_upd( $post_id ) {

		$settings_arr = univ_option_get( 'xfgmc_settings_arr' );
		$settings_arr_keys_arr = array_keys( $settings_arr );
		for ( $i = 0; $i < count( $settings_arr_keys_arr ); $i++ ) {

			$feed_id = (string) $settings_arr_keys_arr[ $i ]; // ! для правильности работы важен тип string
			$run_cron = common_option_get(
				'xfgmc_run_cron',
				'disabled',
				$feed_id,
				'xfgmc'
			);
			$ufup = common_option_get(
				'xfgmc_ufup',
				'disabled',
				$feed_id,
				'xfgmc'
			);
			if ( $run_cron === 'disabled' || $ufup === 'disabled' ) {
				new XFGMC_Error_Log( sprintf(
					'FEED #%1$s; INFO: %2$s ($run_cron = %3$s; $ufup = %4$s); %5$s: %6$s; %7$s: %8$s',
					$feed_id,
					__(
						'Creating a cache file is not required for this type',
						'xml-for-google-merchant-center'
					),
					$run_cron,
					$ufup,
					__( 'File', 'xml-for-google-merchant-center' ),
					'class-xfgmc-admin.php',
					__( 'Line', 'xml-for-google-merchant-center' ),
					__LINE__
				) );
				continue;
			}

			$do_cash_file = common_option_get(
				'xfgmc_do_cash_file',
				'enabled',
				$feed_id, 'xfgmc'
			);
			if ( $do_cash_file === 'enabled' || $ufup === 'enabled' ) {
				// если в настройках включено создание кэш-файлов в момент сохранения товара
				// или нужно запускать обновление фида при перезаписи файла
				$result_get_unit_obj = new XFGMC_Get_Unit( $post_id, $feed_id );
				$result_xml = $result_get_unit_obj->get_result();
				// Remove hex and control characters from PHP string
				$result_xml = xfgmc_remove_special_characters( $result_xml );
				new XFGMC_Write_File(
					$result_xml,
					sprintf( '%s.tmp', $post_id ),
					$feed_id
				);
			}

			// нужно ли запускать обновление фида при перезаписи файла
			if ( $ufup === 'enabled' ) {
				$status_sborki = (int) common_option_get(
					'xfgmc_status_sborki',
					-1,
					$feed_id,
					'xfgmc'
				);
				if ( $status_sborki === -1 ) {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; INFO: %2$s ($i = %3$s; $ufup = %4$s); %5$s: %6$s; %7$s: %8$s',
						$feed_id,
						__(
							'Starting a quick feed build',
							'xml-for-google-merchant-center'
						),
						$i,
						$ufup,
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-admin.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					clearstatcache(); // очищаем кэш дат файлов
					$generation = new XFGMC_Generation_XML( $feed_id );
					$generation->quick_generation();
				}
			}

		} // end for

	}

}
