<?php
/**
 * Display tabs.
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/debug_page/
 * 
 * @param $view_arr['tabs_arr']
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit; ?>
<div class="nav-tab-wrapper" style="border-bottom: none; margin: 0; padding: 0;">
	<?php
	foreach ( $view_arr['tabs_arr'] as $tab => $name ) {
		if ( $tab === $view_arr['tab_name'] ) {
			$class = ' nav-tab-active';
		} else {
			$class = '';
		}
		printf(
			'<a class="nav-tab%1$s" href="?page=%2$s&action=edit&current_display=debug_page&tab=%3$s">%4$s</a>',
			esc_attr( $class ),
			'xml-for-google-merchant-center-debug',
			esc_attr( $tab ),
			esc_html( $name )
		);
	}
	?>
</div>