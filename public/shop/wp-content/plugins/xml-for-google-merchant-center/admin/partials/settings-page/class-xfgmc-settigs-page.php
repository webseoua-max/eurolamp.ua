<?php

/**
 * The class return the Settings page of the plugin XML for Google Merchant Center.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.3 (17-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin
 */

/**
 * The class return the Settings page of the plugin XML for Google Merchant Center.
 *
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/settings-page
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Settings_Page {

	/**
	 * The name of the current tab.
	 * 
	 * @since 0.1.0
	 * @access private
	 * @var string
	 */
	private $current_display = 'list_feeds';

	/**
	 * Current feed ID.
	 * 
	 * @since 0.1.0
	 * @access private
	 * @var string
	 */
	private $feed_id = null;

	/**
	 * The name of the current tab.
	 * 
	 * @since 0.1.0
	 * @access private
	 * @var string
	 */
	private $current_tab = 'main_tab';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		if ( isset( $_GET['current_display'] ) ) {
			$this->current_display = sanitize_text_field( $_GET['current_display'] );
		}

		if ( isset( $_GET['feed_id'] ) ) {
			if ( preg_match( '/^[0-9]+$/', sanitize_key( $_GET['feed_id'] ) ) ) {
				$this->feed_id = sanitize_key( $_GET['feed_id'] );
			}
		}

		if ( isset( $_GET['tab'] ) ) {
			$this->current_tab = sanitize_text_field( $_GET['tab'] );
		}

	}

	/**
	 * Render the settings page.
	 * 
	 * @return void
	 */
	public function render() {

		$view_arr = [ 
			'feed_id' => $this->get_current_feed_id(),
			'current_display' => $this->get_current_display(),
			'tab_name' => $this->get_current_tab_name(),
			'tabs_arr' => $this->get_tabs_arr()
		];

		switch ( $this->get_current_display() ) {
			case 'settings_feed':
				// страница редактирования данных о фиде
				include_once __DIR__ . '/class-xfgmc-feeds-list-table.php';
				include_once __DIR__ . '/views/html-admin-settings-feed.php';
				break;
			default: // 'list_feeds'
				// страница со списком фидов
				include_once __DIR__ . '/class-xfgmc-feeds-list-table.php';
				include_once __DIR__ . '/views/html-admin-list-feeds.php';
		}

	}

	/**
	 * Get the current display of the `Settings page` of this plugin.
	 * 
	 * @return string
	 */
	private function get_current_display() {
		return $this->current_display;
	}

	/**
	 * Get the current `feed ID`.
	 * 
	 * @return string
	 */
	private function get_current_feed_id() {
		return $this->feed_id;
	}

	/**
	 * Get the current `tab`.
	 * 
	 * @return string
	 */
	private function get_current_tab_name() {
		return $this->current_tab;
	}

	/**
	 * Get tabs array.
	 * 
	 * @param string $current
	 * 
	 * @return array `['main_tab' => 'Main settings', TAB_NAME => TAB_LABEL...]`
	 */
	private function get_tabs_arr() {

		$tabs_arr = [ 
			'main_tab' => sprintf( '%s',
				__( 'Main settings', 'xml-for-google-merchant-center' )
			),
			'shop_data_tab' => sprintf( '<channel>...%s...<item>',
				__( 'elements between', 'xml-for-google-merchant-center' )
			),
			'offer_data_tab' => sprintf( '<item>...%s...</item>',
				__( 'elements between', 'xml-for-google-merchant-center' )
			),
			'filtration_tab' => sprintf( '%s',
				__( 'Filtration', 'xml-for-google-merchant-center' )
			)
		];
		$tabs_arr = apply_filters(
			'xfgmc_f_tabs_arr',
			$tabs_arr,
			[ 'feed_id' => $this->get_current_feed_id() ]
		);
		return $tabs_arr;

	}

	/**
	 * Get the current `blog ID`.
	 * 
	 * @return string
	 */
	private function get_current_blog_id() {

		if ( is_multisite() ) {
			$cur_blog_id = get_current_blog_id();
		} else {
			$cur_blog_id = '0';
		}
		return (string) $cur_blog_id;

	}

}