<?php
if (!defined('ABSPATH')) {
    exit;
}
$wf_admin_view_path=WT_PRODUCT_FEED_PRO_PLUGIN_PATH.'admin/views/';
$wf_img_path=WT_PRODUCT_FEED_PRO_PLUGIN_URL.'images/';
?>
<div class="wrap" id="<?php echo WEBTOFFEE_PRODUCT_FEED_PRO_ID;?>">
    <h2 class="wp-heading-inline">
    <?php _e('Settings', 'webtoffee-product-feed-pro');?>
    </h2>
    <div class="nav-tab-wrapper wp-clearfix wt-pfd-tab-head">
        <?php
        $tab_head_arr=array(
            'wt-advanced'=>__('General', 'webtoffee-product-feed-pro'),            
            'wt-custom-fields' => __('Custom Fields', 'webtoffee-product-feed-pro'),
            'wt-other-solutions' => __('Other Solutions', 'webtoffee-product-feed-pro')
        );
        if(isset($_GET['debug']))
        {
            $tab_head_arr['wt-debug']='Debug';
        }
        Webtoffee_Product_Feed_Sync_Pro::generate_settings_tabhead($tab_head_arr);
        ?>
    </div>
    <div class="wt-pfd-tab-container">
        <?php
        //inside the settings form
        $setting_views_a=array(
            'wt-advanced'=>'admin-settings-advanced.php',
        );

        //outside the settings form
        $setting_views_b=array(          
            'wt-custom-fields'=>'admin-settings-custom-fields.php',
        );
        $setting_views_b['wt-other-solutions'] = 'admin-settings-other-solutions.php';

        if(isset($_GET['debug']))
        {
            $setting_views_b['wt-debug']='admin-settings-debug.php';
        }
        ?>
        <form method="post" class="wt_pf_settings_form_basic">
            <?php
            // Set nonce:
            if (function_exists('wp_nonce_field'))
            {
                wp_nonce_field(WEBTOFFEE_PRODUCT_FEED_PRO_ID);
            }
            foreach ($setting_views_a as $target_id=>$value) 
            {
                $settings_view=$wf_admin_view_path.$value;
                if(file_exists($settings_view))
                {
                    include $settings_view;
                }
            }
            ?>
            <?php 
            //settings form fields for module
            do_action('wt_pf_plugin_settings_pro_form');?>           
        </form>
        <?php
        foreach ($setting_views_b as $target_id=>$value) 
        {
            $settings_view=$wf_admin_view_path.$value;
            if(file_exists($settings_view))
            {
                include $settings_view;
            }
        }
        ?>
        <?php do_action('wt_pf_plugin_out_settings_pro_form');?> 
    </div>
    <?php //include $wf_admin_view_path."market.php";  ?>
</div>