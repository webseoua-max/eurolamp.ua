<?php
/**
 * Display tabs.
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/settings_page/
 * 
 * @param $view_arr['tabs_arr']
 * @param $view_arr['tab_name']
 * @param $view_arr['feed_id']
 */
defined( 'ABSPATH' ) || exit; ?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="nav-tab-wrapper" style="border-bottom: none; margin: 0; padding: 0;">
	<?php
	foreach ( $view_arr['tabs_arr'] as $tab => $name ) {
		if ( $tab === $view_arr['tab_name'] ) {
			$class = ' nav-tab-active';
		} else {
			$class = '';
		}
		printf(
			'<a class="nav-tab%1$s" href="?page=%2$s&action=edit&feed_id=%3$s&current_display=settings_feed&tab=%4$s">%5$s</a>',
			esc_attr( $class ),
			'xml-for-google-merchant-center',
			esc_attr( sanitize_key( $view_arr['feed_id'] ) ),
			esc_attr( $tab ),
			esc_html( $name )
		);
	}
	?>
</div>