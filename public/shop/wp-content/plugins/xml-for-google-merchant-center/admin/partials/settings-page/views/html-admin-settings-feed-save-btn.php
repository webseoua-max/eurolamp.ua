<?php
/**
 * Print the Save button
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/settings_page/
 * 
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;

if ( $view_arr['tab_name'] === 'no_submit_tab' ) {
	return;
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php wp_nonce_field( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ); ?>
<input id="button-primary" class="button-primary" name="xfgmc_submit_action" type="submit" value="<?php
if ( $view_arr['tab_name'] === 'main_tab' ) {
	printf( '%s & %s (ID: %s)',
		esc_html__( 'Save', 'xml-for-google-merchant-center' ),
		esc_html__( 'Create feed', 'xml-for-google-merchant-center' ),
		esc_attr( $view_arr['feed_id'] )
	);
} else {
	printf( '%s (ID: %s)',
		esc_html__( 'Save', 'xml-for-google-merchant-center' ),
		esc_attr( $view_arr['feed_id'] )
	);
}
?>" />