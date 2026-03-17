<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<table class="form-table wt-pf-adv-filter-options" style="display: none;">
    <thead class="wt-pf-adv-filter-head">
        <tr>
            <th style="width:180px;"><label style="margin-left:10px;"><?php esc_html_e('If', 'webtoffee-product-feed-pro'); ?>
                </label>
            </th>
            <th style="width:180px;"><label style="margin-left:10px;"><?php esc_html_e('Condition', 'webtoffee-product-feed-pro'); ?>
                </label>
            </th>
            <th style="width:180px;"><label style="margin-left:10px;"><?php esc_html_e('Value', 'webtoffee-product-feed-pro'); ?>
                </label>
            </th>
            <th style="width:150px;"><label style="margin-left:10px;"><?php esc_html_e('Then', 'webtoffee-product-feed-pro'); ?>
                </label>
            </th>
            <th style="width:150px;"><label style="margin-left:10px;"><?php esc_html_e('Actions', 'webtoffee-product-feed-pro'); ?>
                </label>
            </th>              
        </tr>
    </thead>
    <tbody>
        <?php 

        $row_count = 1;
        if(!empty($advanced_filter_options['fields'])){
            $row_count = count($advanced_filter_options['fields']);
        }
        
        ?>
        <?php 

        for( $i = 0; $i < $row_count; $i++ ) {
                   $filter_if = isset($advanced_filter_options['fields'][$i]) ?  $advanced_filter_options['fields'][$i] : '';
                   $filter_condition = isset( $advanced_filter_options['condition'][$i] ) ? $advanced_filter_options['condition'][$i] : '';
                   $filter_val = isset( $advanced_filter_options['val'][$i] ) ? $advanced_filter_options['val'][$i] : '';
                   $filter_then = isset( $advanced_filter_options['then'][$i] ) ? $advanced_filter_options['then'][$i] : '';
                   ?>
        <tr class="filter_row_data">
            <td>
                <select name="wt_pf_adv_filter_if[]" id="wt_pf_adv_filter_if" style="width:180px;">
                    <?php
                    $wc_product_attributes = '<option value="">Select</option>';
                    $wc_prod_attributes = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::attribute_dropdown( $this->to_export, $filter_if);
                    $search_vals = array(
                        '<option></option>',
                        '<optgroup label="Constant"><option style="font-weight: bold;" value="wt-static-map-vl">Static value</option></optgroup>',
                        '<optgroup label="Compute"><option style="font-weight: bold;" value="wt-compute-map-vl">Computed value</option></optgroup>',
                        '<option value="type">Product Type</option>',
                        '<option value="tags">Tags</option>',
                        '<option value="long_title">Product Title[long_title]</option>',
                        '<option value="promotion_id">Product Id[promotion_id]</option>',
                        '<option value="url">Product URL</option>',
                        );
                    $replace_vals = array(
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        );
                                        
                    $wc_prod_attributes = str_replace($search_vals, $replace_vals, $wc_prod_attributes);
                    $wc_product_attributes .= $wc_prod_attributes;
                    echo $wc_product_attributes;
                    ?>

                </select>                
            </td>
            <td>
                <select name="wt_pf_adv_filter_condition[]" id="wt_pf_adv_filter_condition" style="width:180px;">
                    <?php
                    $wt_feed_adv_filter_condition = array(
                        '' => 'Select',
                        'contains' => 'Contains',
                        'doesnot_contains' => 'Does not contains',
                        'is_equal' => 'Is equal to',
                        'is_not_equal' => 'Is not equal to',
                        'is_greater' => 'Is greater than',
                        'is_greater_or_equal' => 'Is greater or equal to',
                        'is_lesser' => 'Is less than',
                        'is_lesser_or_equal' => 'Is less or equal to',
                        'is_empty' => 'Is empty',
                        'is_not_empty' => 'Is not empty',
                    );
                    foreach ($wt_feed_adv_filter_condition as $key => $adv_filter_condition) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo ($filter_condition == $key ? 'selected' : ''); ?>><?php echo esc_attr($adv_filter_condition); ?></option>								
                        <?php
                    }
                    ?>

                </select>                
            </td>
            <td>
                <input required type="text" name="wt_pf_adv_filter_val[]" value="<?php echo $filter_val; ?>" id="wt_pf_adv_filter_val" style="width:180px;"/>
            </td>
            <td>
                <select name="wt_pf_adv_filter_then[]" id="wt_pf_adv_filter_then" style="width:150px;">
                    <?php
                    $wt_feed_adv_filter_then = array(
                        '' => 'Select',
                        'include' => 'Include',
                        'exclude' => 'Exclude',
                    );
                    foreach ($wt_feed_adv_filter_then as $key => $adv_filter_then) {
                        ?>
                        <option value="<?php echo $key; ?>" <?php echo ($filter_then == $key ? 'selected' : ''); ?>><?php echo esc_attr($adv_filter_then); ?></option>								
                        <?php
                    }
                    ?>
                </select>                
            </td>
            <td>
                <img style="margin-left: 10px;" class="wt-feed-filter-row-remove" src="<?php echo WT_PRODUCT_FEED_PRO_PLUGIN_URL.'/assets/images/wt_fi_trash.svg';?>" alt="<?php _e('Delete', 'webtoffee-product-feed-pro'); ?>" title="<?php _e('Delete', 'webtoffee-product-feed-pro'); ?>"/>
                </td>            
        </tr>
                <?php } ?>
        <tr><td><button class="button button-secondary wt_pf_add_filter_btn" type="button"> + <?php _e('Add Filter', 'webtoffee-product-feed-pro'); ?></button></td></tr>
    </tbody>
</table>