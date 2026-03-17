<?php
/**
 * Display a list of feeds and the "Add new feed" button.
 * 
 * @version    4.0.3 (17-06-2025)
 * @package    XFGMC
 * @subpackage XFGMC/admin/partials/settings_page/
 */
defined( 'ABSPATH' ) || exit;

$feeds_list_table = new XFGMC_Feeds_List_Table();
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="xfgmc_wrap" class="wrap">

	<div id="icon-options-general" class="icon32"></div>
	<h1>XML for Google Merchant Center - <?php esc_html_e( 'Feeds list', 'xml-for-google-merchant-center' ); ?></h1>
	<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'xfgmc_nonce_action_add_new_feed', 'xfgmc_nonce_field_add_new_feed' ); ?>
		<input value="+ <?php esc_html_e( 'Add New Feed', 'xml-for-google-merchant-center' ); ?>" class="button-primary"
			type="submit" name="xfgmc_submit_action_add_new_feed" />
	</form>

	<?php $feeds_list_table->display_html_form(); ?>

	<?php printf( '<p style="text-align: right;"><strong>%s:</strong> %s, <strong>%s:</strong> %s</p>',
		esc_html__( 'Current site time', 'xml-for-google-merchant-center' ),
		current_time( 'mysql', 0 ),
		esc_html__( 'Current server time', 'xml-for-google-merchant-center' ),
		current_time( 'mysql', 1 )
	); ?>

	<?php do_action( 'xfgmc_print_view_html_icpd_my_plugins_list' ); ?>

</div> <!-- .wrap -->
<?php
