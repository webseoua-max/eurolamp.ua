<?php
if (!defined('ABSPATH')) {
    exit;
}
if(isset($cron_list) && is_array($cron_list) && count($cron_list)>0)
{
	?>
<div class="cron_list_wrapper">
	<table class="wp-list-table widefat fixed striped cron_list_tb" style="margin-bottom:55px;">
	<thead>
		<tr>
			<th width="50"><?php esc_html_e("No.", 'webtoffee-product-feed-pro'); ?></th>
			<th width="100"><?php esc_html_e("Feed name", 'webtoffee-product-feed-pro'); ?></th>
			<th width="100"><?php esc_html_e("Channel", 'webtoffee-product-feed-pro'); ?></th>
			<th width="100"><?php esc_html_e("Cron type", 'webtoffee-product-feed-pro'); ?></th>
			<th width="100">
				<?php esc_html_e("Status", 'webtoffee-product-feed-pro'); ?>
				<span class="dashicons wtdashicons-editor-help wt-pfd-tips" 
					data-wt-pfd-tip="
					<span class='wt_productfeed_tooltip_span'><?php echo sprintf(__('%sFinished%s - Process completed', 'webtoffee-product-feed-pro'), '<b>', '</b>');?></span><br />
					<span class='wt_productfeed_tooltip_span'><?php echo sprintf(__('%sDisabled%s - The process has been disabled temporarily', 'webtoffee-product-feed-pro'), '<b>', '</b>');?> </span><br />
					<span class='wt_productfeed_tooltip_span'><?php echo sprintf(__('%sRunning%s - Process currently active and running', 'webtoffee-product-feed-pro'), '<b>', '</b>');?> </span><br />
					<span class='wt_productfeed_tooltip_span'><?php echo sprintf(__('%sUploading%s - Processed records are being uploaded to the specified location, finalizing export.', 'webtoffee-product-feed-pro'), '<b>', '</b>');?> </span><br />
					<span class='wt_productfeed_tooltip_span'><?php echo sprintf(__('%sDownloading%s - Input records are being downloaded from the specified location prior to import process.', 'webtoffee-product-feed-pro'), '<b>', '</b>');?> </span>">			
				</span>
			</th>
			<th><?php esc_html_e("Time", 'webtoffee-product-feed-pro'); ?></th>
			<th width="200"><?php esc_html_e("Actions", 'webtoffee-product-feed-pro'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i=0;
	foreach($cron_list as $key =>$cron_item)
	{
            
            $feed_data = maybe_unserialize($cron_item['data']);
            $filename = isset( $feed_data['post_type_form_data']['wt_pf_export_catalog_name'] ) ? $feed_data['post_type_form_data']['wt_pf_export_catalog_name'] : '' ;
            if( ''=== $filename ){
                $filename = isset( $feed_data['post_type_form_data']['item_filename'] ) ? $feed_data['post_type_form_data']['item_filename'] : '' ;
            }            
            $filetype = $feed_data['advanced_form_data']['wt_pf_file_as'];
            $i++;
                $item_type = ucfirst($cron_item['item_type']);
		?>
		<tr>
			<td><?php echo absint($i);?></td>
			<td><?php echo esc_html($filename.'.'.$filetype); ?></td>
			<td><?php echo esc_html($item_type); ?></td>
			<td><?php echo ($cron_item['schedule_type']=='server_cron' ? esc_html__('Server cron', 'webtoffee-product-feed-pro') : esc_html__('WordPress cron', 'webtoffee-product-feed-pro')); ?></td>
			<td>
				<span class="wt_productfeed_badge" style="padding:5px;color:white;<?php echo (isset(self::$status_color_arr[$cron_item['status']]) ? 'background:'.self::$status_color_arr[$cron_item['status']] : ''); ?>">
					<?php
					echo (isset(self::$status_label_arr[$cron_item['status']]) ? self::$status_label_arr[$cron_item['status']] : esc_html__('Unknown', 'webtoffee-product-feed-pro'));
					?>
				</span>
				<?php
				/**
				* 	Show completed percentage if status is running
				*/
				if($cron_item['status']==self::$status_arr['running'] && $cron_item['history_id']>0)
				{
					$history_module_obj=Webtoffee_Product_Feed_Sync_Pro::load_modules('history');
					if(!is_null($history_module_obj))
					{
						$history_entry=$history_module_obj->get_history_entry_by_id($cron_item['history_id']);
						if($history_entry)
						{
							echo '<br />'.number_format((($history_entry['offset']/$history_entry['total'])*100), 2).'% '.esc_html__(' Done', 'webtoffee-product-feed-pro');
						}
					}
				}
				?>
			</td>
			<td>
				<?php
					if($cron_item['status']==self::$status_arr['finished'] || $cron_item['status']==self::$status_arr['disabled'])
					{
						if($cron_item['last_run']>0)
						{
							echo esc_html__('Last run: ', 'webtoffee-product-feed-pro').date_i18n('Y-m-d h:i:s A', $cron_item['last_run']).'<br />';
						}

						/**
						*	Finished, so waiting for next run
						*/
						if($cron_item['status']==self::$status_arr['finished'] && $cron_item['start_time']>0 && $cron_item['start_time']!=$cron_item['last_run'])
						{
							echo esc_html__('Next run: ', 'webtoffee-product-feed-pro').date_i18n('Y-m-d h:i:s A', $cron_item['start_time']).'<br />';
						}
					}

					if($cron_item['status']==self::$status_arr['running'] || $cron_item['status']==self::$status_arr['uploading'] || $cron_item['status']==self::$status_arr['downloading'])
					{
						if($cron_item['last_run']>0 && $cron_item['start_time']!=$cron_item['last_run'])
						{
							echo esc_html__('Last run: ', 'webtoffee-product-feed-pro').date_i18n('Y-m-d h:i:s A', $cron_item['last_run']).'<br />';
						}else
						{
							echo esc_html__('Started at: ', 'webtoffee-product-feed-pro').date_i18n('Y-m-d h:i:s A', $cron_item['start_time']).'<br />';
						}
					}

					if($cron_item['status']==self::$status_arr['not_started'] && $cron_item['start_time']>0)
					{
						echo esc_html__('Will start at: ', 'webtoffee-product-feed-pro').date_i18n('Y-m-d h:i:s A', $cron_item['start_time']).'<br />';
					}
				?>
			</td>
			<td>
				<?php
				$page_id=(isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '');

				/* status change section */
				$action_label=esc_html__('Disable', 'webtoffee-product-feed-pro');
				$action='disable';
				if($cron_item['status'] == self::$status_arr['disabled'])
				{
					$action='enable';
					$action_label=esc_html__('Enable', 'webtoffee-product-feed-pro');
				}
				$action_url=wp_nonce_url(admin_url('admin.php?page='.$page_id.'&wt_productfeed_change_schedule_status='.$action.'&wt_productfeed_cron_id='.$cron_item['id']), WEBTOFFEE_PRODUCT_FEED_PRO_ID);
				
				/* delete section */
				$delete_url=wp_nonce_url(admin_url('admin.php?page='.$page_id.'&wt_productfeed_delete_schedule=1&wt_productfeed_cron_id='.$cron_item['id']), WEBTOFFEE_PRODUCT_FEED_PRO_ID);
				
                                /* edit action */
                                if($cron_item['action_type'] == 'import'){
                                    $edit_url = admin_url('admin.php?page=wt_import_export_for_woo_import&wt_productfeed_cron_edit_id='.$cron_item['id']);
                                }else{
                                    $edit_url = admin_url('admin.php?page=wt_import_export_for_woo_export&wt_productfeed_cron_edit_id='.$cron_item['id']);
                                }
                                
                                if(!class_exists("Webtoffee_Product_Feed_Sync_Pro_$item_type")){
                                    $edit_url = '#';
                                }
                                
				?>
                        <a href="<?php echo esc_url($action_url);?>"><?php echo esc_html($action_label);?></a> | <a title="<?php esc_html_e('Delete cron entry', 'webtoffee-product-feed-pro'); ?>" class="wt_productfeed_delete_cron" data-href="<?php echo esc_url($delete_url);?>"><?php esc_html_e('Delete', 'webtoffee-product-feed-pro'); ?></a>
				<?php
				if($cron_item['schedule_type']=='server_cron')
				{
					$cron_url=$this->generate_cron_url($cron_item['id'], $cron_item['action_type'], $cron_item['item_type']);
				?>
					| <a class="wt_productfeed_cron_url" data-href="<?php echo esc_url($cron_url);?>" title="<?php esc_html_e('Generate new cron URL.', 'webtoffee-product-feed-pro');?>"><?php esc_html_e('Cron URL', 'webtoffee-product-feed-pro');?></a>
				<?php	
				}
				?>
			</td>
		</tr>
		<?php	
	}
	?>
	</tbody>
	</table>
</div>

	<?php //include plugin_dir_path(__FILE__).'/_schedule_update.php'; ?>
	<?php
}else
{
	?>
	<h4 style="margin-bottom:55px; text-align:center; background:#fff; padding:15px 0px;"><?php esc_html_e("No scheduled actions found."); ?></h4>
	<?php
}
?>