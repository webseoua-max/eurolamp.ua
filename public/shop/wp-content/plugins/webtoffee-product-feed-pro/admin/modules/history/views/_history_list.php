<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="wt_pf_history_page">
	<h2 class="wt_pf_page_hd"><?php _e('Product Feed', 'webtoffee-product-feed-pro'); ?>
	<span class="wt-webtoffee-icon" style="float: <?php echo (!is_rtl()) ? 'right' : 'left'; ?>;">
		<a href="javascript:void(0);" class="productfeed-feature-link  button button-add-media" style="font-size:13px; margin-right: 10px;text-decoration: none;"><?php esc_html_e('Request a feature',  'webtoffee-product-feed-pro'); ?></a>
		<span style="font-size:14px;"><?php esc_html_e('Developed by',  'webtoffee-product-feed-pro'); ?></span>
    <a target="_blank" href="https://www.webtoffee.com">
        <img src="<?php echo WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/webtoffee-logo_small.png';?>" style="max-width:100px;">
    </a>
</span>
	</h2>
		
	<hr>
	<h2 class="wp-heading-inline"><?php _e('Manage feeds',  'webtoffee-product-feed-pro');?></h2>
	<div class="wt_pf_bulk_action_box">
		<select class="wt_pf_bulk_action wt_pf_select">
			<option value=""><?php _e('Bulk Actions', 'webtoffee-product-feed-pro'); ?></option>
			<option value="delete"><?php _e('Delete', 'webtoffee-product-feed-pro'); ?></option>
		</select>
		<button class="button button-primary wt_pf_bulk_action_btn" type="button" style="float:left;"><?php _e('Apply', 'webtoffee-product-feed-pro'); ?></button>
		&nbsp;&nbsp;<a class="button page-title-action" href="<?php echo esc_url(admin_url('admin.php?page=webtoffee_product_feed_main_pro_export')); ?>"><?php esc_html_e('Add new feed', 'webtoffee-product-feed-pro'); ?></a>
	</div>
	<?php
	echo self::gen_pagination_html($total_records, $this->max_records, $offset, 'admin.php', $pagination_url_params);
	?>
	<?php
	if(isset($history_list) && is_array($history_list) && count($history_list)>0)
	{
		?>
		<table class="wp-list-table widefat fixed striped history_list_tb">
		<thead>
			<tr>
				<th width="50">
					<input type="checkbox" name="" class="wt_pf_history_checkbox_main">
					<?php esc_html_e('No', 'webtoffee-product-feed-pro'); ?>
				</th>				
				<th width="100"><?php esc_html_e("Name",  'webtoffee-product-feed-pro'); ?></th>
				<th width="90"><?php esc_html_e("Catalog type",  'webtoffee-product-feed-pro'); ?></th>
				<th width="55"><?php esc_html_e("File type",  'webtoffee-product-feed-pro'); ?></th>
				<th width=""><?php esc_html_e("URL",  'webtoffee-product-feed-pro'); ?></th>				
				<th width="65"><?php esc_html_e("Products",  'webtoffee-product-feed-pro'); ?></th>				
				<th width="110"><?php esc_html_e("Refresh interval",  'webtoffee-product-feed-pro'); ?></th>					
				<th width="165"><?php esc_html_e("Last updated",  'webtoffee-product-feed-pro'); ?></th>
				<th width="156">
					<?php esc_html_e("Actions",  'webtoffee-product-feed-pro'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=$offset;

		foreach($history_list as $key =>$history_item)
		{
			
			$i++;
			?>
			<tr>
				<th style="vertical-align:top;"><input type="checkbox" value="<?php echo $history_item['id'];?>" name="history_id[]" class="wt_pf_history_checkbox_sub">
					<?php echo $i;?></td>
				<?php $form_data=maybe_unserialize($history_item['data']); ?>
				<td><?php echo ucfirst(pathinfo($history_item['file_name'], PATHINFO_FILENAME)); ?></td>
                                <?php                                      
                                $catalog_type = isset( $form_data['post_type_form_data']['wt_pf_export_post_type'] ) ? esc_html( $form_data['post_type_form_data']['wt_pf_export_post_type'] ) : '';                                
                                if('' === $catalog_type ){
                                    $catalog_type = isset( $form_data['post_type_form_data']['item_type'] ) ? $form_data['post_type_form_data']['item_type'] : '';
                                }
                                ?>
				<td><?php echo isset( $catalog_type ) ? esc_html( ucfirst( $catalog_type ) ) : ''; ?></td>
				<td><?php echo strtoupper(pathinfo($history_item['file_name'], PATHINFO_EXTENSION)); ?></td>
				<td><?php echo content_url().'/uploads/webtoffee_product_feed/'.($history_item['file_name']); ?><br/><button data-uri = "<?php echo content_url().'/uploads/webtoffee_product_feed/'.($history_item['file_name']); ?>" class="button button-primary wt_pf_copy"><?php esc_html_e( 'Copy URL', 'webtoffee-product-feed-pro' ) ; ?></button></td>				
				<td><?php echo ucfirst($history_item['total']); ?></td>
				<td>                                    
                                    <?php 
                                    $generate_inreval = isset($form_data['post_type_form_data']['item_gen_interval']) ? $form_data['post_type_form_data']['item_gen_interval'] : '';
                                    if('' === $generate_inreval){
                                        $generate_inreval = isset($form_data['post_type_form_data']['wt_pf_export_catalog_interval']) ? $form_data['post_type_form_data']['wt_pf_export_catalog_interval'] : '';
                                    }                                    
                                    echo ucfirst($generate_inreval); 
                                    ?>                                
                                </td>
				<td><?php echo date_i18n('Y-m-d h:i:s A', $history_item['updated_at']); ?></td>
				<td>					
					<a class="wt_pf_delete_history wt_manage_feed_icons" data-href="<?php echo str_replace('_history_id_', $history_item['id'], $delete_url);?>"><img src="<?php echo WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_trash.svg';?>" alt="<?php _e('Delete', 'webtoffee-product-feed-pro'); ?>" title="<?php _e('Delete', 'webtoffee-product-feed-pro'); ?>"/></a>
					<?php
					$action_type=$history_item['template_type'];
					if($form_data && is_array($form_data))
					{
						$to_process=(isset($form_data['post_type_form_data']) && isset($form_data['post_type_form_data']['item_type']) ? $form_data['post_type_form_data']['item_type'] : '');
                                                if('' == $to_process){
                                                        $to_process=(isset($form_data['post_type_form_data']) && isset($form_data['post_type_form_data']['wt_pf_export_post_type']) ? $form_data['post_type_form_data']['wt_pf_export_post_type'] : '');
                                                }
						if($to_process!="")
						{
							if(Webtoffee_Product_Feed_Sync_Pro_Admin::module_exists($action_type))
							{
								$action_module_id=Webtoffee_Product_Feed_Sync_Pro::get_module_id($action_type);
								$url=admin_url('admin.php?page='.$action_module_id.'&wt_pf_rerun='.$history_item['id']);
								?>
								   <a class="wt_pf_export_edit_btn wt_manage_feed_icons" href="<?php echo $url;?>" target="_blank"><img src="<?php echo WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_edit.svg';?>" alt="<?php _e('Edit', 'webtoffee-product-feed-pro'); ?>" title="<?php _e('Edit', 'webtoffee-product-feed-pro'); ?>"/></a>
								<?php
							}
						}
					}

                    if($action_type=='export' && Webtoffee_Product_Feed_Sync_Pro_Admin::module_exists($action_type)){
                        $export_download_url=wp_nonce_url(admin_url('admin.php?wt_pf_export_download=true&file='.$history_item['file_name']), WEBTOFFEE_PRODUCT_FEED_PRO_ID);
						?>
                            <a class="wt_pf_export_download_btn wt_manage_feed_icons" target="_blank" href="<?php echo esc_url($export_download_url); ?>"><img src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_download.svg'); ?>" alt="<?php esc_html_e('Download', 'webtoffee-product-feed-pro'); ?>" title="<?php esc_html_e('Download', 'webtoffee-product-feed-pro'); ?>"/></a>
						<?php
					}                                        
					?>
					<?php if(  'manual' !== $generate_inreval ) { ?>
					<a class="wt_pf_export_refresh_btn wt_manage_feed_icons" href="javascript:void(0);" data-cron_id="<?php echo esc_attr($history_item['id']); ?>"><img src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_refresh.svg');?>" alt="<?php esc_html_e('Refresh', 'webtoffee-product-feed-pro'); ?>" title="<?php esc_html_e('Refresh', 'webtoffee-product-feed-pro'); ?>"/></a>
					<?php } ?>    
				<a class="wt_pf_export_duplicate_btn wt_manage_feed_icons" href="javascript:void(0);" data-cron_id="<?php echo esc_attr($history_item['id']); ?>"><img src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_duplicate.svg');?>" alt="<?php esc_html_e('Duplicate', 'webtoffee-product-feed-pro'); ?>" title="<?php esc_html_e('Duplicate', 'webtoffee-product-feed-pro'); ?>"/></a>
				</td>
			</tr>
			<?php	
		}
		?>
		</tbody>
		</table>
		<?php
		echo self::gen_pagination_html($total_records, $this->max_records, $offset, 'admin.php', $pagination_url_params);
	}else
	{
		?>
		<h4 class="wt_pf_history_no_records"><?php _e("No records found."); ?></h4>
		<?php
	}
	?>
</div>