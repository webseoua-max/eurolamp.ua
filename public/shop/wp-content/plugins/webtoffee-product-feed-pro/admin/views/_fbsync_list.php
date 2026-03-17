<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<div class="wt_pf_history_page">
	<?php
	if(isset($history_list) && is_array($history_list) && count($history_list)>0)
	{
		?>
		<table class="wp-list-table widefat fixed striped history_list_tb">
		<thead>
			<tr>
				<th width="50">#</th>				
				<th width=""><?php _e("Status",  'webtoffee-product-feed-pro'); ?></th>
				<th width=""><?php _e("Interval",  'webtoffee-product-feed-pro'); ?></th>
				<th width=""><?php _e("Last run",  'webtoffee-product-feed-pro'); ?></th>
				<th width=""><?php _e("Next run",  'webtoffee-product-feed-pro'); ?></th>
				<th width="">
					<?php _e("Actions"); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i=0;
		foreach($history_list as $key =>$history_item)
		{			
			$i++;
			$fbsync_crondata = maybe_unserialize($history_item['cron_data']);
			?>
			<tr>
				<th style="vertical-align:top;">
					<?php echo $i;?></td>
				<td><?php echo (isset(Webtoffee_Product_Feed_Sync_Pro_Cron::$status_label_arr[$history_item['status']]) ? Webtoffee_Product_Feed_Sync_Pro_Cron::$status_label_arr[$history_item['status']] : __('Unknown', 'webtoffee-product-feed-pro')); ?></td>
				<td><?php echo ucfirst($fbsync_crondata['wt_sync_schedule_interval']) ?></td>
				<td><?php echo ($history_item['last_run']) ? date_i18n('Y-m-d h:i:s A', $history_item['last_run']) : '----'; ?></td>
                                <td><?php if($history_item['start_time'] > 0 ) { echo date_i18n('Y-m-d h:i:s A', $history_item['start_time']); } ?></td>
				<td>					
					<a class="wt_pf_delete_fbsync" style="cursor:pointer;" data-href="<?php echo str_replace('_fbsync_id_', $history_item['id'], $delete_url);?>"><?php _e('Delete', 'webtoffee-product-feed-pro'); ?></a>
				</td>
			</tr>
			<?php	
		}
		?>
		</tbody>
		</table>
		<?php
		
	}else
	{
		?>
		<h4 class="wt_pf_history_no_records"><?php _e('No records found.','webtoffee-product-feed-pro'); ?></h4>
		<?php
	}
	?>
</div>