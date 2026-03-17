<?php
/**
 * Print the Save and Clear logs button for Debug page.
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/debug_page/
 * 
 * @param $view_arr['tab_name']
 */
defined( 'ABSPATH' ) || exit;

?>
<?php wp_nonce_field( 'xfgmc_nonce_action', 'xfgmc_nonce_field' ); ?>
<input id="button-primary" class="button-primary" type="submit"
	name="xfgmc_submit_action_<?php echo esc_attr( $view_arr['tab_name'] ); ?>" value="<?php
		 if ( $view_arr['tab_name'] === 'simulation' ) {
			 printf( '%s', esc_html__( 'Simulation', 'xml-for-google-merchant-center' ) );
		 } else {
			 printf( '%s', esc_html__( 'Save', 'xml-for-google-merchant-center' ) );
		 }
		 ?>" />

<?php if ( $view_arr['tab_name'] === 'debug_options' ) : ?>
	<input class="button" type="submit" id="xfgmc_submit_action_clear_logs" name="xfgmc_submit_action_clear_logs"
		value="<?php esc_html_e( 'Clear logs', 'xml-for-google-merchant-center' ); ?>" />
<?php endif; ?>