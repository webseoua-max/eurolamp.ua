<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
$products=(isset($view_params['products']) ? $view_params['products'] : array());

if($license_status){
	$display_license_act = 'none';
	$display_license_list = 'block';
}else{
	$display_license_act = 'block';
	$display_license_list = 'none';
}
?>
<style type="text/css">
.wt_pf_licence_container{ padding-bottom:20px; }
.wt_pf_licence_form_table td{ padding-bottom:20px; width:345px; }
.wt_pf_licence_form_table input[type="text"], .wt_pf_licence_form_table select{ width:100%; display:block; border:solid 1px #ccd0d4;}
.wt_pf_licence_form_table label{ width:100%; display:block; font-weight:bold; }
.wt_pf_licence_table{ margin-bottom:20px; }
.wt_pf_licence_form_table{ width:auto; }
</style>
<div class="wt-pfd-tab-content" data-id="<?php echo $target_id;?>">
	<div id="wt-pf-license-act-window" style="display:<?php echo esc_attr($display_license_act); ?>"> 
	<h3><span><?php _e('Activate new licence', 'webtoffee-product-feed-pro');?></span></h3>
	<form method="post" id="wt_pf_licence_manager_form">
		<?php
        // Set nonce:
        if (function_exists('wp_nonce_field'))
        {
            wp_nonce_field(WEBTOFFEE_PRODUCT_FEED_PRO_ID);
        }
        ?>
        <input type="hidden" name="wt_pf_licence_manager_action" value="activate">
        <input type="hidden" name="action" value="wt_pf_licence_manager_ajax">
        <table class="wp-list-table widefat fixed striped wt_pf_licence_form_table">
        	<tr>

				<td>
					<label><?php _e('Licence key:', 'webtoffee-product-feed-pro');?></label>
					<input type="text" name="wt_pf_licence_key">
				</td>

				<td style="width:100px;">
					<label>&nbsp;</label>
					<button class="button button-primary wt_pf_licence_activate_btn"><?php _e('Activate', 'webtoffee-product-feed-pro');?></button>
				</td>
			</tr>
        </table>
	</form>
</div>
	<br/>
	<div id="wt-pf-license-list-window" style="display:<?php echo esc_attr($display_license_list); ?>">
	<h3><span><?php _e('Licence details', 'webtoffee-product-feed-pro');?></span></h3>
	<div class="wt_pf_licence_list_container">
		
	</div>
	</div>
</div>