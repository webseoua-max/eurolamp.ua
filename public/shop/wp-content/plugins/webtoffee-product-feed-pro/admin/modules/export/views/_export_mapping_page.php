<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wt_pf_export_main">
    <p><?php echo $step_info['description']; ?></p>
    <p> &#x261E; <?php _e(' To learn about adding <b>static values</b> refer to', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://www.webtoffee.com/using-static-values-for-product-feed/"><?php _e(' this ', 'webtoffee-product-feed-pro') ;?></a><?php _e('article.', 'webtoffee-product-feed-pro');?> </p>
    <p> &#x261E; <?php _e(' To learn about adding <b>computed values</b> refer to', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://www.webtoffee.com/simplifying-computations-with-product-feed/"><?php _e(' this ', 'webtoffee-product-feed-pro') ;?></a><?php _e('article.', 'webtoffee-product-feed-pro');?>                  
                <span class="dashicons dashicons-editor-help wt-pf-tips" 
                          data-wt-pf-tip="
                          <span class='wt_pf_tooltip_span'><?php echo __(' (+)10% - Increase value by 10 percent<br/>  (+)10 - Increase value by 10 <br/> Allowed operations are *,-,+', 'webtoffee-product-feed-pro'); ?></span><br />
                          ">			
                </span> </p>
	<div class="wtpf_meta_mapping_box">
		<div class="wtpf_meta_mapping_box_hd wt_pf_noselect">
			<span class="dashicons dashicons-arrow-down"></span>
			<?php _e('Default fields', 'webtoffee-product-feed-pro');?>
			<span class="wtpf_meta_mapping_box_selected_count_box"><span class="wtpf_meta_mapping_box_selected_count_box_num">0</span> <?php _e(' columns(s) selected', 'webtoffee-product-feed-pro'); ?></span>
		</div>
		<div style="clear:both;"></div>
		<div class="wtpf_meta_mapping_box_con" data-sortable="0" data-loaded="1" data-field-validated="0" data-key="" style="display:inline-block;">
			<table class="wt-pfd-mapping-tb wt-pfd-exporter-default-mapping-tb">
				<thead>
					<tr>
			    		<th>
			    			<input type="checkbox" name="" class="wt_pf_mapping_checkbox_main">
			    		</th>
						<th width="35%"><span id="wt_pf_channel_selected"><?php _e('Catalog', 'webtoffee-product-feed-pro');?></span> <?php _e('Attributes', 'webtoffee-product-feed-pro');?></th>
			    		<th><?php _e('WooCommerce Product Fields', 'webtoffee-product-feed-pro');?></th>
						<th></th>
			    	</tr>
				</thead>
				<tbody>
				<?php
				$draggable_tooltip=__("Drag to rearrange the columns", 'webtoffee-product-feed-pro');
				$tr_count=0;

                                $custom_attr_count = 0;
                                $custom_attr = false;
                                foreach($form_data_mapping_fields as $key=>$val)
				{
					if(isset($mapping_fields[$key]))
					{
						$label=$mapping_fields[$key];
						$wc_prod_attributes = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::attribute_dropdown( $this->to_export, $val[0] );
						include "_export_mapping_tr_html.php";
					  	unset($mapping_fields[$key]); //remove the field from default list
					  	$tr_count++;
					}else{
                                            if('custom' === $this->to_export){
                                                $custom_attr = true;
                                                $label= $key;
                                                $wc_prod_attributes = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::attribute_dropdown( $this->to_export, $val[0] );
                                                include "_export_mapping_tr_html.php";
                                                $tr_count++;  
                                                $custom_attr_count++;
                                            }
                                        }	
				}
				if(count($mapping_fields)>0)
				{
					foreach($mapping_fields as $key=>$label)
					{		
						$wc_prod_attributes = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::attribute_dropdown( $this->to_export, $key );
						$row_matched = 0;
						if( false !== strpos( $wc_prod_attributes, ' selected>' ) ){
							$row_matched = 1;
						}
						$val=array($key, $row_matched); //enable the field							
						include "_export_mapping_tr_html.php";
						$tr_count++;
					}
				}
				if($tr_count==0)
				{
					?>
					<tr>
						<td colspan="3" style="text-align:center;">
							<?php _e('No fields found.', 'webtoffee-product-feed-pro'); ?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
                                <?php if('custom' === $this->to_export):?>
                                <?php //Add dynamic created fields here ?>        
                                <button class="button button-secondary wt_pf_add_attr_btn" type="button"> <?php esc_html_e('+ Add New Attribute', 'webtoffee-product-feed-pro'); ?></button>
                                <?php endif; ?>
		</div>
	</div>
	<div style="clear:both;"></div>
	<?php
	if($this->mapping_enabled_fields)
	{
		foreach($this->mapping_enabled_fields as $mapping_enabled_field_key=>$mapping_enabled_field)
		{
			$mapping_enabled_field=(!is_array($mapping_enabled_field) ? array($mapping_enabled_field, 0) : $mapping_enabled_field);
			
			if(count($form_data_mapping_enabled_fields)>0)
			{
				if(in_array($mapping_enabled_field_key, $form_data_mapping_enabled_fields))
				{
					$mapping_enabled_field[1]=1;
				}else
				{
					$mapping_enabled_field[1]=0;
				}
			}
			?>
			<div class="wtpf_meta_mapping_box">
				<div class="wtpf_meta_mapping_box_hd wt_pf_noselect">
					<span class="dashicons dashicons-arrow-right"></span>
					<?php echo $mapping_enabled_field[0];?>
					<span class="wtpf_meta_mapping_box_selected_count_box"><span class="wtpf_meta_mapping_box_selected_count_box_num">0</span> <?php _e(' columns(s) selected', 'webtoffee-product-feed-pro'); ?></span>
				</div>
				<div style="clear:both;"></div>
				<div class="wtpf_meta_mapping_box_con" data-sortable="0" data-loaded="0" data-field-validated="0" data-key="<?php echo esc_attr( $mapping_enabled_field_key );?>"></div>
			</div>
			<div style="clear:both;"></div>
			<?php
		}
	}
	?>
</div>
