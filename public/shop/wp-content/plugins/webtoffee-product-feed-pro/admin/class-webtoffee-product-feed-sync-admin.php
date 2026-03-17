<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webtoffee.com
 * @since      1.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync_Pro
 * @subpackage Webtoffee_Product_Feed_Sync_Pro/admin
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Webtoffee_Product_Feed_Sync_Pro
 * @subpackage Webtoffee_Product_Feed_Sync_Pro/admin
 * @author     WebToffee <info@webtoffee.com>
 */
if (!class_exists('Webtoffee_Product_Feed_Sync_Pro_Admin')) {

    class Webtoffee_Product_Feed_Sync_Pro_Admin {

        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        private $version;
        public static $modules = array(
            'history',
            'export',
            'cron',
            'licence_manager'
        );
        public static $existing_modules = array();
        public static $addon_modules = array();
        public $module_id='';
        /** @var string|null the generated external merchant settings ID */
        private $external_business_id;

        /** @var string the product sync to FB batch limit */
        public $batch_limit = 10;

        /** @var array the page handles that with the plugin views */
        public $wt_pages = array(
            'woocommerce_page_webtoffee-product-feed-pro',
            'webtoffee-product-feed_page_webtoffee-product-feed-pro',
            'webtoffee-product-feed-pro_page_webtoffee-product-feed-pro'
            );

        public $sync_description_type;
        public $log;
        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $plugin_name       The name of this plugin.
         * @param      string    $version    The version of this plugin.
         */
        public function __construct($plugin_name, $version) {

            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }

        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_styles() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Webtoffee_Product_Feed_Sync_Pro_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Webtoffee_Product_Feed_Sync_Pro_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            $current_screen = get_current_screen();

            if (isset($current_screen->id) && in_array($current_screen->id, $this->wt_pages)) {

                wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/webtoffee-product-feed-admin.css', array(), $this->version, 'all');
            }
            if (Webtoffee_Product_Feed_Sync_Pro_Common_Helper::wt_is_screen_allowed()) {
                wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wt-product-feed-admin.css', array(), $this->version, 'all');
            }
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts() {

            /**
             * This function is provided for demonstration purposes only.
             *
             * An instance of this class should be passed to the run() function
             * defined in Webtoffee_Product_Feed_Sync_Pro_Loader as all of the hooks are defined
             * in that particular class.
             *
             * The Webtoffee_Product_Feed_Sync_Pro_Loader will then create the relationship
             * between the defined hooks and the functions defined in this
             * class.
             */
            $current_screen = get_current_screen();

            if (isset($current_screen->id) && in_array($current_screen->id, $this->wt_pages)) {

                wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/webtoffee-product-feed-admin.js', array('jquery'), $this->version, false);
                wp_enqueue_script($this->plugin_name . '-steps', plugin_dir_url(__FILE__) . 'js/jquery.steps.js', array('jquery'), $this->version, false);
                $params = array(
                    'nonces' => array(
                        'main' => wp_create_nonce(WEBTOFFEE_PRODUCT_FEED_PRO_ID),
                    ),
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'plugin_id' => WEBTOFFEE_PRODUCT_FEED_PRO_ID,
                    'msgs' => array(
                        'error' => __('Error.', 'webtoffee-product-feed-pro'),
                        'success' => __('Success.', 'webtoffee-product-feed-pro'),
                        'loading' => __('Loading...', 'webtoffee-product-feed-pro'),
                        'process' => __('Processing Sync...', 'webtoffee-product-feed-pro'),
                        'sync_now' => __('Sync now', 'webtoffee-product-feed-pro'),
                        'sync_schedule' => __('Schedule Sync', 'webtoffee-product-feed-pro'),
                        'back' => __('Back', 'webtoffee-product-feed-pro'),
                        'next' => __('Next', 'webtoffee-product-feed-pro'),
                        'sync_completed_success' => __('All the products have been synced successfully.', 'webtoffee-product-feed-pro'),
                        'sync_completed_wizard_success' => __('The sync has been completed. Please be informed that it will take a while for the products to appear on your Facebook/Instagram catalog.', 'webtoffee-product-feed-pro'),
                    )
                );
                wp_localize_script($this->plugin_name, 'wt_feed_params', $params);
            }

            if (Webtoffee_Product_Feed_Sync_Pro_Common_Helper::wt_is_screen_allowed()) {
                /* enqueue scripts */
                if (!function_exists('is_plugin_active')) {
                    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                }
                if (is_plugin_active('woocommerce/woocommerce.php')) {
                    if (!wp_script_is('jquery-tiptip')) {
                        wp_enqueue_script('jquery-tiptip');
                    }
                    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wt-product-feed-admin.js', array('jquery', 'jquery-tiptip'), $this->version, false);
                    // Enqueue cron.js script
                    wp_enqueue_script($this->module_id, plugin_dir_url(__FILE__) . 'modules/cron/assets/js/cron.js', array('jquery'), $this->version, false);
                    
                    $wt_time_zone = apply_filters( 'wt_pf_website_timezone', Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_advanced_settings('default_time_zone') );
				
                    $wt_pf_params=array(
                        'timestamp'=> ($wt_time_zone) ? date_i18n('Y M d h:i:s A') : date('Y M d h:i:s A'),
                    );
                    wp_localize_script($this->module_id, 'wt_productfeed_cron_params', $wt_pf_params); 
                } else {
                    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wt-product-feed-admin.js', array('jquery'), $this->version, false);
                    wp_enqueue_script(WEBTOFFEE_PRODUCT_FEED_PRO_ID . '-tiptip', WT_PRODUCT_FEED_PRO_PLUGIN_URL . 'admin/js/tiptip.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION, false);
                }

                $order_addon_active_status = false;
                $user_addon_active_status = false;
                if (is_plugin_active('order-import-export-for-woocommerce/order-import-export-for-woocommerce.php')) {
                    $order_addon_active_status = true;
                }
                if (is_plugin_active('users-customers-import-export-for-wp-woocommerce/users-customers-import-export-for-wp-woocommerce.php')) {
                    $user_addon_active_status = true;
                }


                $params = array(
                    'nonces' => array(
                        'main' => wp_create_nonce(WEBTOFFEE_PRODUCT_FEED_PRO_ID),
                    ),
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'plugin_id' => WEBTOFFEE_PRODUCT_FEED_PRO_ID,
                    'msgs' => array(
                        'settings_success' => __('Settings updated.', 'webtoffee-product-feed-pro'),
                        'all_fields_mandatory' => __('All fields are mandatory', 'webtoffee-product-feed-pro'),
                        'settings_error' => __('Unable to update Settingss.', 'webtoffee-product-feed-pro'),
                        'template_del_error' => __('Unable to delete template', 'webtoffee-product-feed-pro'),
                        'template_del_loader' => __('Deleting template...', 'webtoffee-product-feed-pro'),
                        'value_empty' => __('Value is empty.', 'webtoffee-product-feed-pro'),                        
                        'error'=> sprintf( __( 'Something went wrong. Please reload and check or %s contact our support %s for easy troubleshooting.', 'webtoffee-product-feed-pro' ), '<a href="https://www.webtoffee.com/contact/" target="_blank">', '</a>' ),
                        'success' => __('Success.', 'webtoffee-product-feed-pro'),
                        'loading' => __('Loading...', 'webtoffee-product-feed-pro'),
                        'sure' => __('Are you sure?', 'webtoffee-product-feed-pro'),
                        'use_expression' => __('Use expression as value.', 'webtoffee-product-feed-pro'),
                        'cancel' => __('Cancel', 'webtoffee-product-feed-pro'),
                        'export_canceled' => __('Feed creation cancelled', 'webtoffee-product-feed-pro'),
                        'send_req' => __('Send feature request', 'webtoffee-product-feed-pro'),
                        'sending_req' => __('Sending...', 'webtoffee-product-feed-pro'),
                        'copied_msg' => __('URL copied to clipboard', 'webtoffee-product-feed-pro')
                    )
                );
                wp_localize_script($this->plugin_name, 'wt_pf_basic_params', $params);
            }

        }

        /**
         * Adds the Facebook menu item.
         *
         * @since 1.0.0
         */
        public function add_menu_item() {

            //add_submenu_page( 'webtoffee_product_feed', __( 'Facebook Catalog Manager', 'webtoffee-product-feed-pro' ), __( 'Facebook Catalog', 'webtoffee-product-feed-pro' ), 'manage_woocommerce', WT_Fb_Catalog_Manager_Pro_Settings::PAGE_ID, [ $this, 'render' ] );
            //add_submenu_page( 'webtoffee_product_feed', __( 'Facebook Category Mapping', 'webtoffee-product-feed-pro' ), __( 'Facebook Category Mapping', 'webtoffee-product-feed-pro' ), 'manage_woocommerce', 'wt-fbfeed-category-mapping', [ $this, 'wt_fbfeed_category_mapping' ] );
            //add_submenu_page( 'woocommerce', __( 'Facebook Attribute Mapping', 'webtoffee-product-feed-pro' ), __( 'Facebook Attribute Mapping', 'webtoffee-product-feed-pro' ), 'manage_woocommerce', 'wt-fbfeed-attribute-mapping', [ $this, 'wt_fbfeed_attribute_mapping' ], 7 );
        }

        /**
         * Show action links on the plugin screen.
         *
         * @param mixed $links Plugin action links.
         *
         * @return array
         */
        public function add_productfeed_action_links($links) {

            $plugin_links = array(
                '<a href="' . esc_url(admin_url('admin.php?page=webtoffee_product_feed_main_pro_export')) . '">' . __('Settings', 'webtoffee-product-feed-pro') . '</a>',
                '<a href="' . esc_url(admin_url('admin.php?page=webtoffee-product-feed-pro')) . '">' . __('FB Sync', 'webtoffee-product-feed-pro') . '</a>',
                '<a target="_blank" href="https://www.webtoffee.com/contact/">' . __('Support', 'webtoffee-product-feed-pro') . '</a>',
                '<a target="_blank" href="https://www.webtoffee.com/woocommerce-product-feed-sync-manager-setup-guide/">' . __('Documentation', 'webtoffee-product-feed-pro') . '</a>'
            );
            if (array_key_exists('deactivate', $links)) {
                $links['deactivate'] = str_replace('<a', '<a class="productfeed-deactivate-link"', $links['deactivate']);
            }
            return array_merge($plugin_links, $links);
        }

        /**
         * Gets the available tabs.
         *
         * @since 1.0.0
         *
         * @return tabs[]
         */
        public function get_tabs() {

            $tabs = [
                'connection-manager' => __('Manage Connection', 'webtoffee-product-feed-pro'),
            ];
            $is_connected = $this->is_connected();
            if ($is_connected || !empty(($_GET['fb_access_token']))) { // phpcs:ignore csrf ok.
                $tabs['sync-products'] = __('Sync Products', 'webtoffee-product-feed-pro');
                $tabs['map-categories'] = __('FB Category map', 'webtoffee-product-feed-pro');
                $tabs['logs'] = __('Logs', 'webtoffee-product-feed-pro');
                $tabs['scheduled-sync'] = __('Scheduled Sync', 'webtoffee-product-feed-pro');
            }
            return $tabs;
        }

        public function update_fb_connected_time($value) {

            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_FB_CONNECTED_TIME, $value);
        }

        public function get_fb_connected_time() {

            return get_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_FB_CONNECTED_TIME);
        }

        public function update_fb_access_token($value) {

            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_ACCESS_TOKEN, $value);
        }

        public function update_fb_user_id($value) {

            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_USER_ID, $value);
        }

        public function get_fb_user_id() {

            return get_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_USER_ID);
        }

        public function update_fb_business_id($value) {

            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_FB_BUSINESS_ID, $value);
        }

        public function update_fb_catalog_id($value) {

            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_FB_CATALOG_ID, $value);
        }

        public function get_access_token() {

            $access_token = get_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_ACCESS_TOKEN, '');

            return $access_token;
        }

        public function get_fb_catalog_id() {

            return get_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_FB_CATALOG_ID, '');
        }

        public function is_connected() {

            return (bool) $this->get_access_token();
        }

        public function get_fb_catalog_details() {


            return $this->get_fb_catalog_id();
        }

        /**
         * Disconnects the integration using the Graph API.
         *
         * @internal
         *
         * @since 1.0.0
         */
        public function handle_disconnect() {

            check_admin_referer(WT_Fb_Catalog_Manager_Pro_Settings::DISCONNECT_ACTION);

            if (!current_user_can('manage_woocommerce')) {
                wp_die(__('You do not have permission to uninstall Facebook Business Extension.', 'webtoffee-product-feed-pro'));
            }

            $user_id = $this->get_fb_user_id();
            $access_token = $this->get_access_token();

            $permission_revoke_url = "https://graph.facebook.com/{$user_id}/permissions?access_token={$access_token}";

            $response = wp_remote_request($permission_revoke_url, array(
                'method' => 'DELETE'
                    )
            );

            $this->update_fb_access_token('');
            $this->update_fb_user_id('');
            $this->update_fb_business_id('');
            $this->update_fb_catalog_id('');
            $this->update_fb_connected_time('');

            wp_safe_redirect($this->get_settings_url());
            exit;
        }

        public function get_settings_url() {

            return admin_url('admin.php?page=webtoffee-product-feed-pro');
        }

        /**
         * Renders the settings page.
         *
         * @since 1.0.0
         */
        public function render() {

            if (!current_user_can('manage_woocommerce')) {
                wp_die(__('You do not have permission to view', 'webtoffee-product-feed-pro'));
            }

            $tabs = $this->get_tabs();

            $current_tab = !empty(($_GET['fbtab'])) ? sanitize_text_field($_GET['fbtab']) : '';

            if (!$current_tab) {
                $current_tab = current(array_keys($tabs));
            }


            if (!empty(($_GET['fb_access_token']))) { // phpcs:ignore csrf ok.
                $fb_access_tkn = isset($_GET['fb_access_token']) ? sanitize_text_field(trim($_GET['fb_access_token'])) : '';
                $this->update_fb_access_token($fb_access_tkn);
                $fb_access_uid = isset($_GET['fb_user_id']) ? sanitize_text_field(trim($_GET['fb_user_id'])) : '';
                $this->update_fb_user_id($fb_access_uid);
                $fb_access_buid = isset($_GET['fb_business_id']) ? sanitize_text_field(trim($_GET['fb_business_id'])) : '';
                $this->update_fb_business_id($fb_access_buid);

                if (!empty(($_GET['fb_catalog_id']))) { // phpcs:ignore csrf ok.
                    $catalogs_data = isset($_GET['fb_catalog_id']) ? (array) $_GET['fb_catalog_id'] : array();

                    $this->update_fb_catalog_id($catalogs_data);
                }
                $this->update_fb_connected_time(time());
            }

            $is_connected = $this->is_connected();
            ?>

            <div class="woocommerce ">
            <?php if ('scheduled-sync' === $current_tab): ?>
                <div class="wt_pf_fbinsta_catalog_cron_current_time"><b><?php esc_html_e('Current server time:', 'webtoffee-product-feed-pro'); ?></b> <span>--:--:-- --</span><br/>
                </div>  			
            <?php endif; ?>
                <nav class="nav-tab-wrapper woo-nav-tab-wrapper" style="margin:0px; border: none">

            <?php foreach ($tabs as $id => $label) : ?>
                        <a href="<?php echo esc_html(admin_url('admin.php?page=' . WT_Fb_Catalog_Manager_Pro_Settings::PAGE_ID . '&fbtab=' . esc_attr($id))); ?>" class="nav-tab wt-nav-tab <?php echo $current_tab === $id ? 'nav-tab-active wt-nav-tab-act' : ''; ?>"><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>

                </nav>
                <div class="wt-fbfeed-tab-container">			

            <?php if ('connection-manager' === $current_tab): ?>
                        <h2 <?php if (!$is_connected) { ?> style="text-align:center;" <?php } ?> ><?php _e('Grow your store with Facebook Shops & Dynamic Ads', 'webtoffee-product-feed-pro'); ?></h2>
                        <div class="actions">
                <?php
                if (!$is_connected):
                    ?>
                                <div class="wt-fbfeed-tab-content" style="text-align:center;">
                                    <p><?php esc_html_e('You must connect with your FB business account as a pre-requisite to start synchronizing your products with Facebook.', 'webtoffee-product-feed-pro'); ?></p>
                                    <div class="not-connected-doc">
                                        <p><?php esc_html_e('If you haven\'t already set up a Facebook shop in your business account visit', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://www.facebook.com/business/help/268860861184453?id=1077620002609475"> <b><?php esc_html_e('this link', 'webtoffee-product-feed-pro'); ?></b> </a> <?php esc_html_e('to set up one.', 'webtoffee-product-feed-pro'); ?> </p>									
                                        <p><?php esc_html_e('Use this', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://developers.facebook.com/docs/marketing-api/catalog-batch/reference#supported-fields-items-batch"><b><?php esc_html_e('reference', 'webtoffee-product-feed-pro'); ?></b></a> <?php esc_html_e('to view the product fields that will be synchronized via this plugin.', 'webtoffee-product-feed-pro'); ?></p>
                                    </div>


                    <?php
                    $actions = [
                        'get-started' => [
                            'label' => __('Connect Facebook', 'webtoffee-product-feed-pro'),
                            'type' => 'primary',
                            'url' => $this->get_connect_url(),
                        ],
                    ];
                    ?>

                                    <img src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL . '/assets/images/undraw_social.svg'); ?>" alt="alt"/><br/>
                            <?php foreach ($actions as $action_id => $action) : ?>

                                        <a
                                            href="<?php echo esc_url($action['url']); ?>"
                                            style="background:#1877f2 !important; margin-top:5px;" class="button button-<?php echo esc_attr($action['type']); ?>"
                                    <?php echo ( 'get-started' !== $action_id ) ? 'target="_blank"' : ''; ?>
                                            >
                        <?php echo esc_html($action['label']); ?>
                                        </a>
                                        <p></p>

                    <?php endforeach; ?>

                                </div>

                                <?php endif; ?>


                                <?php
                                if ($is_connected) :
                                    $catalog_details = $this->get_fb_catalog_details();
                                    ?>

                                <div class="catalog-connected-section" style="background: #ebf3f7;padding: 10px;">
                                        <!--<p><?php //esc_html_e( 'You are currently connected to your FB account.', 'webtoffee-product-feed-pro' );  ?></p>-->
                                    <?php if (isset($catalog_details)): ?>
                                        <div class="catalogs-list-section">
                                            <div class="dashicons-before dashicons-cart" style="float:left; width: 50%;"><?php esc_html_e(' Catalogs associated with connected FB account:', 'webtoffee-product-feed-pro'); ?></div>
                                            <div class="fb-catalog-list"  style="float:right; width: 50%;">	
                                            <?php
                                            if (!empty($catalog_details)) {
                                                $ic = 0;
                                                foreach ($catalog_details as $catalog_id => $catalog_name):
                                                    if ($ic !== 0)
                                                        echo '<br/>';
                                                    ?>

                                                        <b><a target="_blank" href="<?php echo "https://facebook.com/products/catalogs/" . $catalog_id; ?>"><?php echo esc_attr($catalog_name); ?></a></b>								
                                <?php
                                $ic++;
                            endforeach;
                        }
                        ?>
                                            </div>
                                        </div>
                                <?php else: ?>
                                        <p><?php esc_html_e('Something went wrong with the connection establishment to catalogs, please refresh or try disconnecting and connect again to FB', 'webtoffee-product-feed-pro'); ?></p>
                    <?php endif; ?>

                                    <br/>
                                    <div class="clearfix"></div>
                                    <div class="catalog-doc-section">
                                        <p style=""><span><?php esc_html_e('Use', 'webtoffee-product-feed-pro'); ?> <a target="_blank" href="https://developers.facebook.com/docs/marketing-api/catalog-batch/reference#supported-fields-items-batch"><b><?php esc_html_e('this reference',  'webtoffee-product-feed-pro'); ?></b></a> <?php esc_html_e('to see which product fields will be synchronised by this plugin.', 'webtoffee-product-feed-pro'); ?></span></p>
                                        <br/>
                                        <p></p>
                                        <p></p>
                                    </div>
                                </div>
                                <div class="catalog-diconnect-btn" style="padding:10px;float: right;">

                                            <?php
                                            $revoke = false;
                                            $fb_connected_time = $this->get_fb_connected_time();
                                            if ('' != $fb_connected_time && ($fb_connected_time + (86400 * 90)) <= time()) {
                                                $revoke = __('The connection with your Facebook business account has expired. Please reconnect to enable product syncing.', 'webtoffee-product-feed-pro');
                                            }
                                            if ($revoke) {
                                                ?>
                                        <p style="float:left;margin-right: 10px;padding:5px;" class="notice notice-error"><?php echo esc_html($revoke); ?></p>
                                    <?php }
                                    ?>

                                    <a href="<?php echo esc_url($this->get_disconnect_url()); ?>" class="uninstall button button-add-media" style="margin-top:5px; padding-top: 1px;padding-bottom: 1px;">
                    <?php
                    if ($revoke) {
                        esc_html_e('Reconnect FB', 'webtoffee-product-feed-pro');
                    } else {
                        esc_html_e('Disconnect FB', 'webtoffee-product-feed-pro');
                    }
                    ?>
                                    </a>
                                </div>

                <?php endif; ?>
                        </div>




                            <?php elseif ('sync-products' === $current_tab): ?>

                                <?php
                                if ($is_connected) :
                                    $wc_path = self::wt_get_wc_path();
                                    wp_enqueue_script('wc-enhanced-select');
                                    wp_enqueue_style('woocommerce_admin_styles', $wc_path . '/assets/css/admin.css');
                                    ?>









                            <p id="sync-loader" style="text-align:center">
                                <i><?php esc_html_e('Fetching the product categories, tags for sync with your FB Catalog ...', 'webtoffee-product-feed-pro'); ?></i>
                                <span class="spinner is-active" style="float:none; margin-top: -3px;"></span>
                            <p>
                            <div class="sync-product-tab" style="display: none;">                                                               

                                <div class="wt-connecting-line"></div>
                                <div id="example-basic">


                                    <h3><?php esc_html_e('General', 'webtoffee-product-feed-pro'); ?></h3>
                                    <section>
                                        <h2 style="width: 80%;text-align: center;margin-top: 0px;"><?php esc_html_e('General settings', 'webtoffee-product-feed-pro'); ?></h2>                                        
                                        <form action="" name="sync_products_gen" id="sync_products_gen" class="sync_products_gen" method="post" autocomplete="off">	
                                        <?php //wp_nonce_field( 'wt-sync-products' );  ?>
                                            <input type="hidden" name="wt_batch_hash_key" id="wt_batch_hash_key" value="<?php echo wp_generate_uuid4(); ?>"/>					
                                            <table class="form-table">
                                                <tr>
                                                    <th><?php esc_html_e('Select FB Catalog', 'webtoffee-product-feed-pro'); ?></th>
                                                    <td>
                                                        <select name="wt_sync_selected_catalog"  >
                    <?php
                    $product_catalogs = !empty($this->get_fb_catalog_details()) ? $this->get_fb_catalog_details() : array();
                    foreach ($product_catalogs as $catalog_id => $catalog_name) {
                        ?>
                                                                <option value="<?php echo $catalog_id; ?>" ><?php echo esc_attr($catalog_name); ?></option>								
                        <?php
                    }
                    ?>

                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Choose the Facebook catalog you wish to sync products, if you have multiple catalogs associated with your business account.', 'webtoffee-product-feed-pro'); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label><?php esc_html_e('Product description type', 'webtoffee-product-feed-pro'); ?></label>
                                                        <span class="dashicons dashicons-editor-help wt-pf-tips wt-pf-tips-sp" 
                                                              data-wt-pf-tip="
                                                              <span class='wt_pf_tooltip_span'><?php esc_html_e('Facebook requires either a short or long description to be sent.', 'webtoffee-product-feed-pro'); ?></span><br />
                                                              "></span>
                                                    </th>
                                                    <td>
                    <?php
                    $product_descriptions = apply_filters('wt_pf_catalog_product_description_type', array(
                        'short' => __('Short', 'webtoffee-product-feed-pro'),
                        'long' => __('Long', 'webtoffee-product-feed-pro'),
                    ));
                    ?>
                                                        <select name="wt_sync_product_desc_type" id="wt_sync_product_desc_type">
                                                            <?php
                                                            foreach ($product_descriptions as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                        <?php
                    }
                    ?>
                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Choose long or short description type from the drop-down.', 'webtoffee-product-feed-pro'); ?></span>														
                                                    </td>
                                                </tr>										
                                                <tr>
                                                    <th><?php esc_html_e('Products per batch', 'webtoffee-product-feed-pro'); ?></th>
                                                    <td>
                                                        <input type="text" name="wt_sync_batch_count" value="10" /><br/><br/>
                                                        <i><?php esc_html_e('The number of records that the server will process for every iteration within the available server timeout interval. If the process fails you can lower this number accordingly and try again. Defaulted to 10 records. Maximum number allowed as per the Facebook limits is 5000.', 'webtoffee-product-feed-pro'); ?></i>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </section>


                                    <h3><?php esc_html_e('Filter Products', 'webtoffee-product-feed-pro'); ?></h3>

                                    <section>
                                        <h2 style="width: 80%;text-align: center;margin-top: 0px;"><?php esc_html_e('Filter products', 'webtoffee-product-feed-pro'); ?></h2>
                                        
                                        <form action="" name="sync_products" id="sync_products" class="sync_products" method="post" autocomplete="off">	
                                            <?php wp_nonce_field('wt-sync-products'); ?>
                                            <input type="hidden" name="wt_batch_hash_key" id="wt_batch_hash_key" value="<?php echo wp_generate_uuid4(); ?>"/>					
                                            <table class="form-table">																																																

                                                <tr>
                                                    <th><?php esc_html_e('Exclude categories', 'webtoffee-product-feed-pro'); ?><label>
                                                            <span class="dashicons dashicons-editor-help wt-pf-tips wt-pf-tips-sp" 
                                                                  data-wt-pf-tip="
                                                                  <span class='wt_pf_tooltip_span'><?php esc_html_e('Products belonging to these categories will be excluded from the sync', 'webtoffee-product-feed-pro'); ?></span><br />
                                                                  ">			
                                                            </span></label>
                                                    </th>
                                                    <td>
                                                        <select name="wt_sync_exclude_category[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Search for a product category&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                    <?php
                    $product_categories = $this->get_product_categories();
                    foreach ($product_categories as $category_id => $category_name) {
                        ?>
                                                                <option value="<?php echo $category_id; ?>" ><?php echo esc_attr($category_name); ?></option>								
                        <?php
                    }
                    ?>

                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more categories to be excluded from the sync.', 'webtoffee-product-feed-pro'); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><?php esc_html_e('Only include specific categories', 'webtoffee-product-feed-pro'); ?></th>
                                                    <td>
                                                        <select name="wt_sync_include_category[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Search for a product category&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                    <?php
                    foreach ($product_categories as $category_id => $category_name) {
                        ?>
                                                                <option value="<?php echo $category_id; ?>" ><?php echo esc_attr($category_name); ?></option>								
                        <?php
                    }
                    ?>

                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more categories. If left empty, all the categories will be included in the sync.', 'webtoffee-product-feed-pro'); ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th><label><?php esc_html_e('Exclude Tags', 'webtoffee-product-feed-pro'); ?></label>
                                                        <span class="dashicons dashicons-editor-help wt-pf-tips wt-pf-tips-sp" 
                                                              data-wt-pf-tip="
                                                              <span class='wt_pf_tooltip_span'><?php esc_html_e('Products associated with the specified tags will be excluded from the sync', 'webtoffee-product-feed-pro'); ?></span><br />
                                                              "></span>
                                                    </th>
                                                    <td>
                                                        <select name="wt_sync_exclude_tags[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __('Search for a product tag&hellip;', 'webtoffee-product-feed-pro'); ?>" >
                    <?php
                    $product_tags = $this->get_product_tags();
                    foreach ($product_tags as $product_tag_id => $product_tag_name) {
                        ?>
                                                                <option value="<?php echo $product_tag_id; ?>" ><?php echo esc_attr($product_tag_name); ?></option>								
                                                                <?php
                                                            }
                                                            ?>

                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Search and add one or more tags to be excluded from sync.', 'webtoffee-product-feed-pro'); ?></span>														
                                                    </td>
                                                </tr>


                                                <tr>
                                                    <th><label><?php esc_html_e('Exclude out of stock products', 'webtoffee-product-feed-pro'); ?></label>
                                                        <span class="dashicons dashicons-editor-help wt-pf-tips wt-pf-tips-sp" 
                                                              data-wt-pf-tip="
                                                              <span class='wt_pf_tooltip_span'><?php esc_html_e('Enabling this option would exclude the out-of-stock products from the sync', 'webtoffee-product-feed-pro'); ?></span><br />
                                                              "></span>
                                                    </th>
                                                    <td>
                                                        <input type="checkbox" name="wt_sync_exclude_outofstock" id="wt_sync_exclude_outofstock" value="1" >
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Enable to exclude out-of-stock products', 'webtoffee-product-feed-pro'); ?></span>														
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <th><label><?php esc_html_e('Choose variation', 'webtoffee-product-feed-pro'); ?>
                                                            <span class="dashicons dashicons-editor-help wt-pf-tips" 
                                                                  data-wt-pf-tip="
                                                                  <span class='wt_pf_tooltip_span'><?php esc_html_e('Choose the product variation need to be included in the feed.', 'webtoffee-product-feed-pro'); ?></span><br />">			
                                                            </span>
                                                        </label></th>
                                                    <td>
                                                        <?php
                                                        $include_variations_type = apply_filters('wt_pf_sync_include_variations_type', array(
                                                            '' => __('All variations', 'webtoffee-product-feed-pro'),
                                                            'default' => __('Default variation', 'webtoffee-product-feed-pro'),
                                                            'lowest' => __('Lowest priced variation', 'webtoffee-product-feed-pro'),
                                                        ));
                                                        ?>
                                                        <select name="wt_sync_include_variations_type" id="wt_sync_include_variations_type">
                                                            <?php
                                                            foreach ($include_variations_type as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo esc_html($key); ?>" ><?php echo $value; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="wt-pf_form_help"><?php esc_html_e('Include selected product variations in the sync.', 'webtoffee-product-feed-pro'); ?></span>
                                                    </td>
                                                    <td></td>
                                                </tr>                                                

                                            </table>

                                        </form>

                                    </section>
                                    <h3><?php esc_html_e('Map Categories', 'webtoffee-product-feed-pro'); ?></h3>
                                    <section id="category-section">													
                    <?php
                    $ajax_render = true;
                    require plugin_dir_path(dirname(__FILE__)) . 'includes/fbcatalog/partials/wt-fbfeed-category-mapping.php';
                    ?>
                                    </section>
                                    <!--												<h3>Pager</h3>
                                                                                                                                    <section>
                                                                                                                                            <p>The next and previous buttons help you to navigate through your content.</p>
                                                                                                                                            <button name="syncproducts" id="syncproducts" type="submit" class="button button-large button-primary edd-export-form"><?php esc_html_e('Sync Products', 'webtoffee-product-feed-pro'); ?></button>
                                                                                                                                    </section>-->
                                </div>




                                <!-- schedule sync area start -->     

                                <div style="display:none; position: relative;" class="wt_pf_schedule_sync_block_main">
                                    <div style="position: absolute;
                                         top: -540px;
                                         left: 300px;
                                         border: 1px solid #ccc;
                                         padding: 10px;
                                         background-color: white;
                                         z-index: 99;
                                         width: 40%;
                                         " class="wt_pf_schedule_sync_block_schedule">
                                        <form action="" name="sync_products_schedule" id="sync_products_schedule" class="sync_products_schedule" method="post" autocomplete="off">	
                                            <p><img style="float:right;margin-top:-20px;cursor:pointer;" class="productfeed-schedule-modal-cancel" src="<?php echo esc_url(WT_PRODUCT_FEED_PRO_PLUGIN_URL . '/assets/images/feature-close.svg'); ?>" alt="Close schedule modal" /></p>        
                                            <div class="wt_pf_fbinsta_catalog_cron_current_time wt_pf_schedule_modal"><b><?php esc_html_e('Current server time:', 'webtoffee-product-feed-pro'); ?></b> <span>--:--:-- --</span><br/>
                                            </div>
                                            <div class="wt_pf_schedule_sync_block">                        
                                                <p>
                                                    <label style="margin-right:50px;"><?php _e('Schedule', 'webtoffee-product-feed-pro'); ?></label>
                                                    <select name="wt_sync_schedule_interval" class="wt_sync_schedule_interval">
                                                        <option value="daily" ><?php esc_html_e('Daily', 'webtoffee-product-feed-pro'); ?></option>								
                                                        <option value="weekly" ><?php esc_html_e('Weekly', 'webtoffee-product-feed-pro'); ?></option>								
                                                    </select>                            
                                                </p>
                                            </div>
                                            <div class="wt_pf_schedule_sync_block">

                                                <p>
                                                <div class="wt_pf_schedule_sync_weekdays wt_iew_schedule_day_block" style="display:none;">                                
                                                    <div class="wt_pf_schedule_sync_weekday_block">
                                                        <label style="margin-right:83px;"><?php _e('Day', 'webtoffee-product-feed-pro'); ?></label>
                                                        <select name="wt_sync_schedule_cron_day" >
                    <?php
                    $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                    foreach ($days as $day) {
                        $day_vl = strtolower($day);
                        ?>
                                                                <option value="<?php echo esc_attr($day_vl); ?>" ><?php esc_html_e($day, 'webtoffee-product-feed-pro'); ?></option>                                        
                        <?php
                    }
                    ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                </p>
                                            </div>
                                            <div class="wt_pf_schedule_sync_block">
                                                <p>                            
                                                <div class="wt_iew_schedule_now_interval_sub_block wt_iew_schedule_starttime_block">
                                                    <label style="margin-right:80px;float: left;"><?php _e('Time', 'webtoffee-product-feed-pro'); ?></label>
                                                    <div style="float:left;">
                                                        <input  type="number" step="1" min="1" max="12" id="wt_iew_cron_start_val" name="wt_iew_cron_start_val" value="1" style="width:75px;" />
                                                        <span class="wt-iew_form_help" style="display:block; margin-top: 1px"><?php esc_html_e('Hour', 'webtoffee-product-feed-pro'); ?></span>
                                                    </div>
                                                    <div style="float:left;">
                                                        <span class="wt_iew_cron_start_val_min">:</span><input type="number" step="1" min="0" max="59" id="wt_iew_cron_start_val_min" name="wt_iew_cron_start_val_min" value="01" onchange="if (parseInt(this.value, 10) < 10)
                                                                    this.value = '0' + this.value;" style="width:75px;" />
                                                        <span class="wt-iew_form_help" style="display:block;  margin-top: 1px"><?php esc_html_e('Minute', 'webtoffee-product-feed-pro'); ?></span>
                                                    </div>
                                                    <div style="float:left;padding-left:5px;">
                                                        <select name="wt_iew_cron_start_ampm_val" style="width:75px;">
                                                            <?php
                                                            $am_pm = array('AM', 'PM');
                                                            foreach ($am_pm as $apvl) {
                                                                ?>
                                                                <option value="<?php echo esc_html(strtolower($apvl)); ?>"><?php echo esc_html($apvl); ?></option>
                        <?php
                    }
                    ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                </p>
                                            </div>
                                        </form>
                                        <div class="clearfix"></div>
                                        <div class="wt_pf_schedule_sync_block">
                                            <p style="width: 80%;
                                               padding: 20px;
                                               text-align: center;">
                                                <input type="button" value="Cancel" id="schedule_cancel" class="button-secondary" style="padding:5px 40px;">
                                                <input type="button" value="Schedule" id="schedule_procceed" style="float: right;padding:5px 40px;" class="button-primary">
                                            </p>
                                        </div>
                                    </div>
                                </div>








                                <!-- schedule sync area end -->                                                





                                <style>
                                    .edd-progress div{
                                        width:0px;
                                        background:#1877f2;
                                        height:10px
                                    }
                                </style>
                            </div>

                <?php endif; ?>
            <?php
            elseif ('map-categories' === $current_tab):
		/* enqueue scripts for enhanced select */
		if(!function_exists('is_plugin_active'))
		{
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
		}
		if(is_plugin_active('woocommerce/woocommerce.php'))
		{ 
			wp_enqueue_script('wc-enhanced-select');
			wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url().'/assets/css/admin.css');
		}else
		{
			wp_enqueue_style(WEBTOFFEE_PRODUCT_FEED_PRO_ID.'-select2', WT_PRODUCT_FEED_PRO_PLUGIN_URL. 'admin/css/select2.css', array(), WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION, 'all' );
			wp_enqueue_script(WEBTOFFEE_PRODUCT_FEED_PRO_ID.'-select2', WT_PRODUCT_FEED_PRO_PLUGIN_URL.'admin/js/select2.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION, false );
		}
                $this->wt_fbfeed_category_mapping();
                ?>
            <?php
            elseif ('scheduled-sync' === $current_tab):

                $this->wt_fbfeed_scheduled_sync();
                ?>

            <?php else: ?>
                        <div class="wt-fbfeed-tab-content">			
                <?php

                    $this->list_batch_logs();
                
                ?>
                        </div>
            <?php endif; ?>
                </div>
            </div>

                    <?php
                }

                /**
                 * Get WC Plugin path without fail on any version
                 */
                public static function wt_get_wc_path() {


                    return ( function_exists('WC') ) ? WC()->plugin_url() : plugins_url() . '/woocommerce';
                }

                /**
                 * Gets the product categories.
                 * 
                 * @return array
                 */
                public function get_product_categories() {

                    $term_query = new \WP_Term_Query([
                        'taxonomy' => 'product_cat',
                        'hide_empty' => false,
                        'fields' => 'id=>name',
                            ]);

                    $product_categories = $term_query->get_terms();
                    return is_array($product_categories) ? $product_categories : [];
                }

                /**
                 * Gets the product tags.
                 *
                 * @return array
                 */
                public function get_product_tags() {


                    $term_query = new \WP_Term_Query([
                        'taxonomy' => 'product_tag',
                        'hide_empty' => false,
                        'hierarchical' => false,
                        'fields' => 'id=>name',
                            ]);

                    $product_tags = $term_query->get_terms();
                    return is_array($product_tags) ? $product_tags : [];
                }

                /**
                 * Gets the URL for connecting.
                 * 
                 * @return string
                 */
                public function get_connect_url() {

                    return add_query_arg(rawurlencode_deep($this->get_connect_parameters()), WT_Fb_Catalog_Manager_Pro_Settings::OAUTH_URL);
                }

                /**
                 * Gets the URL for disconnecting.
                 *
                 * @return string
                 */
                public function get_disconnect_url() {

                    return wp_nonce_url(add_query_arg('action', WT_Fb_Catalog_Manager_Pro_Settings::DISCONNECT_ACTION, admin_url('admin.php')), WT_Fb_Catalog_Manager_Pro_Settings::DISCONNECT_ACTION);
                }

                public function get_connect_parameters() {

                    /**
                     * Filters the connection parameters.
                     *
                     * @param array $parameters connection parameters
                     */
                    return apply_filters('wt_facebook_connection_parameters', [
                        'client_id' => $this->get_client_id(),
                        'redirect_uri' => "https://fbconnect.webtoffee.com/",
                        'state' => admin_url('admin.php?page=webtoffee-product-feed-pro'), //?nonce=' ).wp_create_nonce( WT_Fb_Catalog_Manager_Pro_Settings::CONNECT_ACTION ), //$this->get_redirect_url(),
                        'display' => 'page',
                        'response_type' => 'code',
                        'scope' => implode(',', $this->get_scopes()),
                        'extras' => json_encode($this->get_connect_parameters_extras()),
                            ]);
                }

                public function wt_fbfeed_attribute_mapping() {


                    if (count($_POST) && isset($_POST['map_to_attr'])) {

                        check_admin_referer('wt-attribute-mapping');

                        $mapping_option = 'wt_fbfeed_attribute_mapping';

                        $mapping_data = array_map('absint', ($_POST['map_to_attr']));
                        foreach ($mapping_data as $local_attribute_id => $fb_attribute_id) {
                            if ($fb_attribute_id)
                                update_term_meta($local_category_id, 'wt_fb_category', $fb_attribute_id);
                        }

                        // Delete product categories dropdown cache
                        wp_cache_delete('wt_fbfeed_dropdown_product_attributes');

                        if (update_option($mapping_option, $mapping_data, false)) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
                            update_option('wt_mapping_message', esc_html__('Mapping Added Successfully', 'webtoffee-product-feed-pro'), false);
                            wp_safe_redirect(admin_url('admin.php?page=wt-fbfeed-attribute-mapping&wt_mapping_message=success'));
                            die();
                        } else {
                            update_option('wt_mapping_message', esc_html__('Failed To Add Mapping', 'webtoffee-product-feed-pro'), false);
                            wp_safe_redirect(admin_url('admin.php?page=wt-fbfeed-attribute-mapping&wt_mapping_message=error'));
                            die();
                        }
                    }
                    require plugin_dir_path(dirname(__FILE__)) . 'includes/fbcatalog/partials/wt-fbfeed-attribute-mapping.php';
                }

                private function get_client_id() {

                    return WT_Fb_Catalog_Manager_Pro_Settings::CLIENT_ID;
                }

                public function get_batch_limit() {

                    return apply_filters("wt_fbfeed_upload_limit", $this->batch_limit, $this);
                }

                public function get_total_exported() {
                    return ( ( $this->get_page() - 1 ) * $this->get_limit() ) + $this->exported_row_count;
                }

                public function get_percent_complete($found_posts, $step, $limit) {
                    return $found_posts ? floor(( ($step * $limit) / $found_posts ) * 100) : 100;
                }

                /**
                 * Gets the scopes that will be requested during the connection flow.
                 *
                 * @since 1.0.0
                 *
                 * @link https://developers.facebook.com/docs/marketing-api/access/#access_token
                 *
                 * @return string[]
                 */
                public function get_scopes() {

                    $scopes = [
                        'catalog_management',
                        'business_management',
                            //'ads_management',
                            //'ads_read',
                    ];

                    return (array) apply_filters('wt_facebook_connection_scopes', $scopes, $this);
                }

                private function get_connect_parameters_extras() {

                    $parameters = [
                        'setup' => [
                            'external_business_id' => $this->get_external_business_id(),
                            'timezone' => $this->get_timezone_string(),
                            'currency' => get_woocommerce_currency(),
                            'business_vertical' => 'ECOMMERCE',
                        ],
                        'business_config' => [
                            'business' => [
                                'name' => $this->get_business_name(),
                            ],
                        ],
                        'repeat' => false,
                    ];

                    return $parameters;
                }

                public function get_external_business_id() {

                    if (!is_string($this->external_business_id)) {

                        $value = get_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_EXTERNAL_BUSINESS_ID);

                        if (!is_string($value)) {

                            $value = uniqid(sanitize_title($this->get_business_name()) . '-', false);

                            update_option(WT_Fb_Catalog_Manager_Pro_Settings::OPTION_EXTERNAL_BUSINESS_ID, $value);
                        }

                        $this->external_business_id = $value;
                    }


                    return (string) apply_filters('wt_facebook_external_business_id', $this->external_business_id, $this);
                }

                public function get_business_name() {

                    $business_name = get_bloginfo('name');

                    $business_name = trim((string) apply_filters('wt_facebook_connection_business_name', is_string($business_name) ? $business_name : ''));

                    if (empty($business_name)) {
                        $business_name = get_bloginfo('url');
                    }

                    return html_entity_decode($business_name, ENT_QUOTES, 'UTF-8');
                }

                private function get_timezone_string() {

                    $timezone = wc_timezone_string();

                    if (preg_match('/([+-])(\d{2}):\d{2}/', $timezone, $matches)) {

                        $hours = (int) $matches[2];
                        $timezone = "Etc/GMT{$matches[1]}{$hours}";
                    }

                    return $timezone;
                }

                public function wt_fbfeed_category_mapping() {


                    if (count($_POST) && isset($_POST['map_to'])) {

                        check_admin_referer('wt-category-mapping');

                        $mapping_option = 'wt_fbfeed_category_mapping';

                        $mapping_data = array_map('absint', ($_POST['map_to']));
                        foreach ($mapping_data as $local_category_id => $fb_category_id) {
                            if ($fb_category_id)
                                update_term_meta($local_category_id, 'wt_fb_category', $fb_category_id);
                        }

                        // Delete product categories dropdown cache
                        wp_cache_delete('wt_fbfeed_dropdown_product_categories');

                        if (update_option($mapping_option, $mapping_data, false)) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
                            update_option('wt_mapping_message', esc_html__('Mapping Added Successfully', 'webtoffee-product-feed-pro'), false);
                            wp_safe_redirect(admin_url('admin.php?page=webtoffee-product-feed-pro&fbtab=map-categories&wt_mapping_message=success'));
                            die();
                        } else {
                            update_option('wt_mapping_message', esc_html__('Failed To Add Mapping', 'webtoffee-product-feed-pro'), false);
                            wp_safe_redirect(admin_url('admin.php?page=webtoffee-product-feed-pro&fbtab=map-categories&wt_mapping_message=error'));
                            die();
                        }
                    }
                    require plugin_dir_path(dirname(__FILE__)) . 'includes/fbcatalog/partials/wt-fbfeed-category-mapping.php';
                }

                /*
                 * Scheduled fbsync cron list
                 */

                public function wt_fbfeed_scheduled_sync() {

                    global $wpdb;

                    /* delete action */
                    if (isset($_GET['wt_pf_delete_fbsync'])) {
                        if (Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_PRO_ID)) {
                            $wt_pf_fbsync_id = isset($_GET['wt_pf_fbsync_id']) ? explode(",", $_GET['wt_pf_fbsync_id']) : array();
                            $wt_pf_fbsync_id = Wt_Pf_Sh::sanitize_item($wt_pf_fbsync_id, 'absint_arr');

                            if (count($wt_pf_fbsync_id) > 0) {
                                self::delete_fbcron_by_id($wt_pf_fbsync_id);
                            }
                        }
                    }

                    $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync_Pro::$sync_table;
                    $list_sql = "SELECT * FROM $tb ";

                    $delete_url_params = [];
                    $delete_url_params['wt_pf_delete_fbsync'] = 1;
                    $delete_url_params['wt_pf_fbsync_id'] = '_fbsync_id_';
                    $delete_url = wp_nonce_url(admin_url('admin.php?page=webtoffee-product-feed-pro&fbtab=scheduled-sync&' . http_build_query($delete_url_params)), WEBTOFFEE_PRODUCT_FEED_PRO_ID);

                    $history_list = $wpdb->get_results($list_sql, ARRAY_A);
                    include plugin_dir_path(__FILE__) . "/views/_fbsync_list.php";
                }

                /**
                 *  Delete fbcron entry from DB
                 */
                public static function delete_fbcron_by_id($wt_pf_fbsync_id) {

                    global $wpdb;
                    $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync_Pro::$sync_table;
                    if (is_array($wt_pf_fbsync_id)) {
                        $where = " IN(" . implode(",", array_fill(0, count($wt_pf_fbsync_id), '%d')) . ")";
                        $where_data = $wt_pf_fbsync_id;
                    } else {
                        $where = "=%d";
                        $where_data = array($wt_pf_fbsync_id);
                    }

                    $wpdb->query(
                            $wpdb->prepare("DELETE FROM $tb WHERE id" . $where, $where_data)
                    );
                }

                /**
                 * Process set autosync via ajax
                 *
                 * @since 1.0.0
                 * @return void
                 */
                function wt_fbfeed_set_auto_upload() {


                    parse_str($_POST['form'], $form);

                    $_REQUEST = $form = (array) $form;

                    check_admin_referer('wt-sync-products');                    

                    $wt_batch_hash_key = sanitize_text_field($_REQUEST['wt_batch_hash_key']);

                    $wt_sync_selected_catalog = sanitize_text_field($_REQUEST['wt_sync_selected_catalog']);

                    $wt_sync_exclude_category = isset($_REQUEST['wt_sync_exclude_category']) ? array_map('absint', ($_REQUEST['wt_sync_exclude_category'])) : [];
                    $wt_sync_include_category = isset($_REQUEST['wt_sync_include_category']) ? array_map('absint', ($_REQUEST['wt_sync_include_category'])) : [];
                    $wt_sync_exclude_tags = isset($_REQUEST['wt_sync_exclude_tags']) ? array_map('absint', ($_REQUEST['wt_sync_exclude_tags'])) : [];
                    $wt_sync_batch_count = isset($_REQUEST['wt_sync_batch_count']) ? absint($_REQUEST['wt_sync_batch_count']) : $this->get_batch_limit();
                    $wt_sync_exclude_outofstock = isset($_REQUEST['wt_sync_exclude_outofstock']) ? absint($_REQUEST['wt_sync_exclude_outofstock']) : false;
                    $wt_sync_product_desc_type = isset($_REQUEST['wt_sync_product_desc_type']) ? sanitize_text_field($_REQUEST['wt_sync_product_desc_type']) : 'short';

                    $wt_sync_schedule_interval = isset($_REQUEST['wt_sync_schedule_interval']) ? sanitize_text_field($_REQUEST['wt_sync_schedule_interval']) : 'daily';
                    $wt_sync_schedule_cron_day = isset($_REQUEST['wt_sync_schedule_cron_day']) ? sanitize_text_field($_REQUEST['wt_sync_schedule_cron_day']) : strtolower(date('D'));

                    $wt_iew_cron_start_val_hour = isset($_REQUEST['wt_iew_cron_start_val']) ? sanitize_text_field($_REQUEST['wt_iew_cron_start_val']) : 1;
                    $wt_iew_cron_start_val_minute = isset($_REQUEST['wt_iew_cron_start_val_min']) ?  substr( sanitize_text_field($_REQUEST['wt_iew_cron_start_val_min']) , -2 ) : '01';
                    $wt_iew_cron_start_ampm_val = isset($_REQUEST['wt_iew_cron_start_ampm_val']) ? sanitize_text_field($_REQUEST['wt_iew_cron_start_ampm_val']) : 'AM';

                    // If auto sync is enabled, schedule the sync
                    $sync_args = apply_filters('wt_pf_facebook_catalog_sync_args', array(
                        'wt_sync_selected_catalog' => $wt_sync_selected_catalog,
                        'wt_sync_exclude_category' => $wt_sync_exclude_category,
                        'wt_sync_include_category' => $wt_sync_include_category,
                        'wt_sync_exclude_tags' => $wt_sync_exclude_tags,
                        'wt_sync_batch_count' => $wt_sync_batch_count,
                        'wt_sync_exclude_outofstock' => $wt_sync_exclude_outofstock,
                        'wt_sync_product_desc_type' => $wt_sync_product_desc_type,
                        'wt_sync_schedule_interval' => $wt_sync_schedule_interval,
                        'wt_sync_schedule_cron_day' => $wt_sync_schedule_cron_day,
                        'wt_iew_cron_start_val_hour' => $wt_iew_cron_start_val_hour,
                        'wt_iew_cron_start_val_minute' => $wt_iew_cron_start_val_minute,
                        'wt_iew_cron_start_ampm_val' => $wt_iew_cron_start_ampm_val,
                        'wt_batch_hash_key' => $wt_batch_hash_key
                            ));
                    $this->schedule_fb_sync($sync_args);

                    echo json_encode(array('status' => 'finished'));
                    exit;
                }

                /**
                 * Process batch exports via ajax
                 *
                 * @since 1.0.0
                 * @return void
                 */
                function wt_fbfeed_ajax_upload() {

                    // Verify user has proper capabilities
                    if (!Wt_Pf_Sh::check_role_access(WEBTOFFEE_PRODUCT_FEED_PRO_ID)) {
                        wp_send_json_error(array(
                            'status' => 0,
                            'msg' => __('You do not have sufficient permissions to access this feature.', 'webtoffee-product-feed-pro')
                        ));
                        exit();
                    }
                   
                    parse_str($_POST['form'], $form);

                    $_REQUEST = $form = (array) $form;


                    check_admin_referer('wt-sync-products');
                    $step = absint($_POST['step']);

                    $wt_batch_hash_key = sanitize_text_field($_REQUEST['wt_batch_hash_key']);

                    $wt_sync_selected_catalog = sanitize_text_field($_REQUEST['wt_sync_selected_catalog']);

                    $wt_sync_exclude_category = isset($_REQUEST['wt_sync_exclude_category']) ? array_map('absint', ($_REQUEST['wt_sync_exclude_category'])) : [];
                    $wt_sync_include_category = isset($_REQUEST['wt_sync_include_category']) ? array_map('absint', ($_REQUEST['wt_sync_include_category'])) : [];
                    $wt_sync_exclude_tags = isset($_REQUEST['wt_sync_exclude_tags']) ? array_map('absint', ($_REQUEST['wt_sync_exclude_tags'])) : [];
                    $wt_sync_batch_count = isset($_REQUEST['wt_sync_batch_count']) ? absint($_REQUEST['wt_sync_batch_count']) : $this->get_batch_limit();
                    $wt_sync_exclude_outofstock = isset($_REQUEST['wt_sync_exclude_outofstock']) ? absint($_REQUEST['wt_sync_exclude_outofstock']) : false;
                    $wt_sync_product_desc_type = isset($_REQUEST['wt_sync_product_desc_type']) ? sanitize_text_field($_REQUEST['wt_sync_product_desc_type']) : 'short';
                    $wt_sync_include_variations_type = isset($_REQUEST['wt_sync_include_variations_type']) ? sanitize_text_field($_REQUEST['wt_sync_include_variations_type']) : '';
                    
                    $wt_sync_enable_autosync = isset($_REQUEST['wt_sync_enable_autosync']) ? absint($_REQUEST['wt_sync_enable_autosync']) : false;
                    $wt_sync_schedule_interval = isset($_REQUEST['wt_sync_schedule_interval']) ? sanitize_text_field($_REQUEST['wt_sync_schedule_interval']) : 'daily';
                    $wt_iew_cron_start_val_hour = isset($_REQUEST['wt_iew_cron_start_val']) ? sanitize_text_field($_REQUEST['wt_iew_cron_start_val']) : 1;
                    $wt_iew_cron_start_val_minute = isset($_REQUEST['wt_iew_cron_start_val_min']) ? sanitize_text_field($_REQUEST['wt_iew_cron_start_val_min']) : '01';
                    $wt_iew_cron_start_ampm_val = isset($_REQUEST['wt_iew_cron_start_ampm_val']) ? sanitize_text_field($_REQUEST['wt_iew_cron_start_ampm_val']) : 'AM';

                    if ($wt_sync_batch_count == 0 || $wt_sync_batch_count > 5000) {
                        $wt_sync_batch_count = $this->get_batch_limit();
                    }


                    $product_data = [];
                    $wc_fbfeed = new WT_Facebook_Catalog_Product();
                    $wc_fbfeed->sync_description_type = $wt_sync_product_desc_type;

                    $args = array(                        
                        'post_status' => array('publish'),
                        'posts_per_page' => $wt_sync_batch_count,
                        'offset' => ($step - 1) * $wt_sync_batch_count,
                        'fields' => 'ids',
                            /*
                              'tax_query'		 => array(
                              array(
                              'taxonomy'	 => 'product_type',
                              'field'		 => 'slug',
                              'terms'		 => array('simple', 'variable', 'variation'),
                              'operator'	 => 'IN'
                              ) )
                             * 
                             */
                    );

                    if( '' == $wt_sync_include_variations_type && empty( $wt_sync_include_category ) ){
                        $args['post_type'] = array('product', 'product_variation');
                    }else{
                        $args['post_type'] = array('product');
                    }
                    
                    if (!empty($wt_sync_exclude_category)) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'product_cat',
                            'terms' => $wt_sync_exclude_category, // Term ids to be excluded
                            'operator' => 'NOT IN' // Excluding terms
                        );
                    }

                    if (!empty($wt_sync_include_category)) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'product_cat',
                            'terms' => $wt_sync_include_category, // Term ids to be included
                            'operator' => 'IN' // Including terms
                        );
                    }


                    if (!empty($wt_sync_exclude_tags)) {
                        $args['tax_query'][] = array(
                            'taxonomy' => 'product_tag',
                            'terms' => $wt_sync_exclude_tags, // Term ids to be excluded
                            'operator' => 'NOT IN' // Excluding terms
                        );
                    }
                    if (!empty($wt_sync_exclude_category) || !empty($wt_sync_exclude_tags)) {
                        $args['tax_query']['relation'] = 'AND';
                    }


                    if ($wt_sync_exclude_outofstock) {
                        $args['meta_query'] = array(array(
                                'key' => '_stock_status',
                                'value' => 'outofstock',
                                'compare' => '!='
                            ));
                    }

                    $args = apply_filters('wt_pf_facebook_catalog_sync_args', $args);


                    $loop = new WP_Query($args);
                    $process_products = apply_filters('wt_facebook_sync_products', $loop->posts);
                    
                    // If include category is selected and variable products are under those category, the variations will not be returned by the WC query
                    if ( !empty($wt_sync_include_category) && '' == $wt_sync_include_variations_type ) {
                        $temp_prod_ids = $process_products;
                        foreach ($temp_prod_ids as $key => $product_id) {
                            $product = wc_get_product($product_id);
                            if ($product->is_type('variable')) {
                                $variations = $product->get_available_variations();
                                $variations_ids = wp_list_pluck($variations, 'variation_id');
                                foreach ($variations_ids as $variations_id) {
                                    $process_products[] = $variations_id;
                                }
                            }
                        }
                    }
                    
                    foreach ($process_products as $product_id) {
                                                                        
                        if( '' !== $wt_sync_include_variations_type ){
                            $product = wc_get_product( $product_id );
                            if( $product->is_type( 'variable' ) ){                        
                                $product_id = $this->get_filtered_variant_id($product_id, $wt_sync_include_variations_type);
                            }
                        }
                        
                        $product_item_data = $wc_fbfeed->process_item_update($product_id);                           
                        if (!empty($product_item_data['data']['price'])) {
                            $product_data[] = $product_item_data;
                        }

                    }  
                    $catalog_access_token = $this->get_access_token();

                    $request_body = [
                        "headers" => [
                            "Authorization" => "Bearer {$catalog_access_token}",
                            "Content-type" => "application/json",
                            "accept" => "application/json"],
                        "body" => json_encode([
                            "allow_upsert" => true,
                            "item_type" => "PRODUCT_ITEM",
                            "requests" => json_encode($product_data)
                        ]),
                    ];
                    $catalog_id = $wt_sync_selected_catalog; //$this->get_fb_catalog_id();
                    // Each bacth process the batch_limit
                     

                    if (!empty($product_data)) {

                        $this->wt_log_data_change('wt-feed-upload', 'Requested Product Data:');
                        $this->wt_log_data_change('wt-feed-upload', print_r($product_data, 1));

                        //$catalog_id				 = $this->get_fb_catalog_id();
                        #$batch_url				 = "https://graph.facebook.com/v17.0/$catalog_id/batch";
                        $items_batch = "https://graph.facebook.com/v17.0/$catalog_id/items_batch";
                        #$single_product_url	 = "https://graph.facebook.com/v17.0/$catalog_id/products";
                        $batch_response = wp_remote_post($items_batch, $request_body);
                        $this->wt_log_data_change('wt-feed-upload', 'Batch Response:');
                        $this->wt_log_data_change('wt-feed-upload', print_r($batch_response, 1));
                        $batch_response_details = wp_remote_retrieve_body($batch_response);
                        $batch_response_details = json_decode($batch_response_details);

                        if ( isset( $batch_response_details->handles[ 0 ] ) ) {
                            global $wpdb;
                            $table_name = $wpdb->prefix.'wt_pf_fbsync_log';

                            // First batch insert log
                            $batch_pocess_log = array();
                            if($step <= 1){
                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $insert_data=array(
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        'start_time'=>date( 'Y-m-d H:i:s' ), 

                                );
                                $insert_data_type=array('%s', '%s', '%s');

                                $wpdb->insert($table_name, $insert_data, $insert_data_type);
                            }else{
                                // All other batch update last log row
                                $last_log = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' ORDER BY id DESC LIMIT 1');
                                $batch_pocess_log = maybe_unserialize($last_log->data);

                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $update_data=array(
                                        'id' => $last_log->id,
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        //'start_time'=>date( 'Y-m-d H:i:s' )

                                );
                                $wpdb->update($table_name, $update_data, array('id' => $last_log->id));
                            }    				
				
			}
                    }


                    $percentage_completed = $this->get_percent_complete($loop->found_posts, $step, $wt_sync_batch_count);

                    if ($percentage_completed !== 100) {

                        $step += 1;
                        echo json_encode(array('step' => $step, 'percentage' => $percentage_completed, 'products' => $loop->found_posts,));
                        exit;
                    } else {


                        echo json_encode(array('step' => 'done', 'percentage' => 100, 'url' => admin_url('admin.php?page=webtoffee-product-feed-pro'), 'catalog' => 'https://facebook.com/products/catalogs/' . $catalog_id . '/products', 'url' => admin_url('admin.php?page=webtoffee-product-feed-pro')));
                        exit;
                    }
                }

                
        public function get_filtered_variant_id($product_id, $wt_sync_include_variations_type) {


            $product = wc_get_product($product_id);
            if ('lowest' == $wt_sync_include_variations_type) {

                // Initialize variables
                $lowest_price = null;
                $lowest_price_variation_id = null;

                // Loop through the variations
                foreach ($product->get_available_variations() as $variation) {
                    // Get the variation price
                    $variation_price = floatval($variation['display_price']);

                    // Compare with the lowest price found so far
                    if ($lowest_price === null || $variation_price < $lowest_price) {
                        $lowest_price = $variation_price;
                        $lowest_price_variation_id = $variation['variation_id'];
                    }
                }
                
                return $lowest_price_variation_id;
            } else {
                $variation_id = false;
                foreach ($product->get_available_variations() as $variation_values) {
                    foreach ($variation_values['attributes'] as $key => $attribute_value) {
                        $attribute_name = str_replace('attribute_', '', $key);
                        $default_value = $product->get_variation_default_attribute($attribute_name);
                        if ($default_value == $attribute_value) {
                            $is_default_variation = true;
                            break;
                        } else {
                            $is_default_variation = false;
                            break; // Stop this loop to start next main lopp
                        }
                    }
                    if ($is_default_variation) {
                        $variation_id = $variation_values['variation_id'];
                        break; // Stop the main loop
                    }
                }
                return $variation_id;
            }
        }

        public function wt_log_data_change($content = 'wt-feed-upload', $data = '') {


                    if (version_compare(WC()->version, '2.7.0', '<')) {
                        $this->log = new WC_Logger();
                    } else {
                        $this->log = wc_get_logger();
                    }

                    if (version_compare(WC()->version, '2.7.0', '<')) {
                        $this->log->add($content, $data);
                    } else {
                        $context = array('source' => $content);
                        $this->log->log("debug", $data, $context);
                    }
                }

                public function get_batch_status($handle, $fb_catalog_id) {

                    $access_token = $this->get_access_token();

                    $batch_status_handle_check_url = "https://graph.facebook.com/v17.0/$fb_catalog_id/check_batch_request_status?handle=$handle&access_token=$access_token&load_ids_of_invalid_requests=1";
                    $batch_status_response = wp_remote_get($batch_status_handle_check_url);
                    $batch_status_response_details = wp_remote_retrieve_body($batch_status_response);
                    $batch_status_response_details = json_decode($batch_status_response_details);

                    $this->wt_log_data_change('wt-feed-upload', 'Batch Status Response:');
                    $this->wt_log_data_change('wt-feed-upload', print_r($batch_status_response, 1));

                    return $batch_status_response_details;
                }

                public function list_batch_logs() {

		?>

		<div class="wt_fbfeed_history_page">
			<h2 class="wp-heading-inline"><?php _e( 'Logs', 'webtoffee-product-feed-pro' ); ?></h2>
			<p>
		<?php _e( 'Lists log of failed product syncs, mostly required for debugging purposes.', 'webtoffee-product-feed-pro' ); ?>
				<span><a target="_blank" href="<?php echo admin_url( 'admin.php?page=wc-status&tab=logs' ); ?>"><?php _e( 'Uploaded data logs', 'webtoffee-product-feed-pro' ); ?></a> ( <i><?php _e( 'The log file name starts with wt-feed-upload', 'webtoffee-product-feed-pro' ); ?></i> )</span>
			</p>

		<?php
                // List all batch logs here
                global $wpdb;
                $table_name = $wpdb->prefix.'wt_pf_fbsync_log';
                $sync_log_exist = true;
                if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                    $sync_log_exist = false;
                }
                $log_list = ($sync_log_exist) ? $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY id DESC') : array();

                if ( is_array( $log_list ) && count( $log_list ) > 0 ) {
			?>
				<table class="wp-list-table widefat fixed striped history_list_tb log_list_tb">
					<thead>
						<tr>
							<th class="log_file_name_col"><?php _e( 'Batch Started at', 'webtoffee-product-feed-pro' ); ?></th>
							<th><?php _e( 'Action' , 'webtoffee-product-feed-pro' ); ?></th>
						</tr>
					</thead>
                                        <tbody>
			<?php
                         foreach ($log_list as $key => $log_list_handles) {

                             ?>
                            <tr><td></td><td></td></tr>
                            <?php 
                            $catalog_details = $this->get_fb_catalog_details();
                            $catalog_name = $log_list_handles->catalog_id; 
                            if(isset($catalog_details[$log_list_handles->catalog_id])){
                                $catalog_name = $catalog_details[$log_list_handles->catalog_id]; 
                            }
                            ?>
                            <tr><td><?php esc_html_e('Catalog: ', 'webtoffee-product-feed-pro'); echo esc_html( $catalog_name ); ?></td><td><?php esc_html_e('Started at:', 'webtoffee-product-feed-pro'); echo esc_html( $log_list_handles->start_time ); ?></td></tr>                               
                                            
                           <?php
                             $log_list_single_batch = maybe_unserialize($log_list_handles->data); 
                                foreach ( $log_list_single_batch as $h_key => $log_list_details ) :
                                foreach ( $log_list_details as $key => $single_batch_log ) :
                                ?>						
				<?php

					if ( isset( $single_batch_log[ 'batch_handle' ] ) ) {
						?>
									<tr>
										<td class="log_file_name_col"><span class="wt_fbfeed_view_log_name" data-log-file="<?php echo esc_attr( $single_batch_log[ 'batch_handle' ] ); ?>"><?php echo esc_attr( $single_batch_log[ 'batch_time' ] ); ?></span></td>
										<td>

											<a class="wt_fbfeed_view_log_btn" data-batch-handle="<?php echo esc_attr( $single_batch_log[ 'batch_handle' ] ); ?>" data-batch-handle-catalog="<?php echo esc_attr( $single_batch_log[ 'catalog_id' ] ); ?>"><?php _e( 'View Status', 'webtoffee-product-feed-pro' ); ?></a>

										</td>
									</tr>
						<?php
					}
				
				?>
						
			<?php   endforeach;
                            endforeach; ?>
                         <?php
                         }
                        ?>
                         </tbody>
				</table>
						<?php
					} else {
						?>
				<h4 class="wt_fbfeed_history_no_records"><?php _e( 'No logs found.', 'webtoffee-product-feed-pro' ); ?></h4>
					<?php
				}
				?>

			<div class="wt_fbfeed_view_log wt_fbfeed_popup">
				<div class="wt_fbfeed_popup_hd">
					<span style="line-height:40px;" class="dashicons dashicons-media-text"></span>
					<span class="wt_fbfeed_popup_hd_label"><?php esc_html_e( 'View log', 'webtoffee-product-feed-pro' ); ?></span>
					<div class="wt_fbfeed_popup_close">X</div>
				</div>
				<div class="wt_fbfeed_log_container">

				</div>
			</div>



		</div>

		<?php
	}

    public function wt_fbfeed_batch_status() {
        // Verify user has proper capabilities
		if (!Wt_Pf_Sh::check_role_access(WEBTOFFEE_PRODUCT_FEED_ID)) {
			wp_send_json_error(array(
				'status' => 0,
				'msg' => __('You do not have sufficient permissions to access this feature.', 'webtoffee-product-feed-pro')
			));
			exit();
		}
        if (!empty($_POST['batch_handle'])) {
            $nonce = (isset($_POST['_wpnonce']) ? sanitize_text_field($_POST['_wpnonce']) : '');
            if (!wp_verify_nonce($nonce, WEBTOFFEE_PRODUCT_FEED_PRO_ID)) {
                return false;
            }
            $handle = sanitize_text_field($_POST['batch_handle']);
            $catalog_id = sanitize_text_field($_POST['catalog_id']);
            $batch_status_data = $this->get_batch_status($handle, $catalog_id);

            if (isset($batch_status_data->data)) {
                wp_send_json(["errors" => $batch_status_data->data[0]->errors, "status" => $batch_status_data->data[0]->status, 'batch_response' => $batch_status_data->data[0], 'ids_of_invalid_requests' => $batch_status_data->data[0]->ids_of_invalid_requests]);
            }elseif (isset($batch_status_data->error)) {
                wp_send_json(["errors" => __( $batch_status_data->error->message, 'webtoffee-product-feed-pro'), "status" => 'failed', 'batch_response' => '']);
            }else{
                wp_send_json(["errors" => __('The request could not be handled', 'webtoffee-product-feed-pro'), "status" => 'cancelled', 'batch_response' => '']);
            }

            exit;
        }
    }

                    /**
                     * Process batch exports via ajax
                     *
                     * @since 1.0.0
                     * @return void
                     */
                    function wt_fbfeed_ajax_save_category() {


                        parse_str($_POST['form'], $form);

                        $_REQUEST = $form = (array) $form;
                        check_admin_referer('wt-category-mapping');

                        $mapping_option = 'wt_fbfeed_category_mapping';

                        $map_to_cats = !empty(($_REQUEST['map_to'])) ? ($_REQUEST['map_to']) : array();
                        $mapping_data = array_map('absint', $map_to_cats);

                        foreach ($mapping_data as $local_category_id => $fb_category_id) {
                            if ($fb_category_id)
                                update_term_meta($local_category_id, 'wt_fb_category', $fb_category_id);
                        }

                        // Delete product categories dropdown cache
                        wp_cache_delete('wt_fbfeed_dropdown_product_categories');

                        if (update_option($mapping_option, $mapping_data, false)) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
                            update_option('wt_mapping_message', esc_html__('Mapping Added Successfully', 'webtoffee-product-feed-pro'), false);
                            echo json_encode(array('step' => 'done', 'message' => 'success'));
                        } else {
                            update_option('wt_mapping_message', esc_html__('Failed To Add Mapping', 'webtoffee-product-feed-pro'), false);
                            echo json_encode(array('step' => 'done', 'message' => 'failure'));
                        }

                        exit();
                    }

                    /**
                     * Registers menu options
                     * Hooked into admin_menu
                     *
                     * @since    1.0.0
                     */
                    public function admin_menu() {
                        $menus = array(
                            'general-settings' => array(
                                'menu',
                                __('General Settings', 'webtoffee-product-feed-pro'),
                                __('General Settings', 'webtoffee-product-feed-pro'),
                                apply_filters('wt_import_export_allowed_capability', 'import'),
                                WEBTOFFEE_PRODUCT_FEED_PRO_ID,
                                array($this, 'admin_settings_page'),
                                'dashicons-controls-repeat',
                                56
                            )
                        );
                        $menus = apply_filters('wt_pf_admin_menu_pro', $menus);

                        $menu_order = array("export", "export-sub", "import", "history", "history_log", "cron");
                        $this->wt_menu_order_changer($menus, $menu_order);

                        $main_menu = reset($menus); //main menu must be first one

                        $parent_menu_key = $main_menu ? $main_menu[4] : WEBTOFFEE_PRODUCT_FEED_PRO_ID;

                        /* adding general settings menu */
                        $menus['general-settings-sub'] = array(
                            'submenu',
                            $parent_menu_key,
                            __('General Settings', 'webtoffee-product-feed-pro'),
                            __('General Settings', 'webtoffee-product-feed-pro'),
                            apply_filters('wt_import_export_allowed_capability', 'import'),
                            WEBTOFFEE_PRODUCT_FEED_PRO_ID,
                            array($this, 'admin_settings_page')
                        );

                        $menus['fb_catalog_manage'] = array(
                            'submenu',
                            $parent_menu_key,
                            __('Facebook/Instagram Catalog Sync', 'webtoffee-product-feed-pro'),
                            __('FB/Insta Catalog', 'webtoffee-product-feed-pro'),
                            apply_filters('wt_import_export_allowed_capability', 'import'),
                            WT_Fb_Catalog_Manager_Pro_Settings::PAGE_ID,
                            array($this, 'render')
                        );

                        if (count($menus) > 0) {
                            foreach ($menus as $menu) {
                                if ($menu[0] == 'submenu') {
                                    /* currently we are only allowing one parent menu */
                                    add_submenu_page($parent_menu_key, $menu[2], $menu[3], $menu[4], $menu[5], $menu[6]);
                                } else {
                                    add_menu_page($menu[1], $menu[2], $menu[3], $menu[4], $menu[5], $menu[6], $menu[7]);
                                }
                            }
                        }
                        if (function_exists('remove_submenu_page')) {
                            //remove_submenu_page(WT_PIEW_POST_TYPE, WT_PIEW_POST_TYPE);
                        }
                    }

                    public function wt_menu_order_changer(&$arr, $index_arr) {
                        $arr_t = array();
                        foreach ($index_arr as $i => $v) {
                            foreach ($arr as $k => $b) {
                                if ($k == $v)
                                    $arr_t[$k] = $b;
                            }
                        }
                        $arr = $arr_t;
                    }

                    public function admin_settings_page() {
                        include(plugin_dir_path(__FILE__) . 'partials/webtoffee-product-feed-admin-display.php');
                    }

                    /**
                     * 	Save admin settings and module settings ajax hook
                     */
                    public function save_settings() {
                        $out = array(
                            'status' => false,
                            'msg' => __('Error', 'webtoffee-product-feed-pro'),
                        );

                        if (Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_PRO_ID)) {
                            $advanced_settings = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_advanced_settings();
                            $advanced_fields = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::get_advanced_settings_fields();
                            $validation_rule = Webtoffee_Product_Feed_Sync_Pro_Common_Helper::extract_validation_rules($advanced_fields);
                            $new_advanced_settings = array();
                            foreach ($advanced_fields as $key => $value) {
                                $form_field_name = isset($value['field_name']) ? $value['field_name'] : '';
                                $field_name = (substr($form_field_name, 0, 8) !== 'wt_pf_' ? 'wt_pf_' : '') . $form_field_name;
                                $validation_key = str_replace('wt_pf_', '', $field_name);
                                if (isset($_POST[$field_name])) {
                                    $new_advanced_settings[$field_name] = Wt_Pf_Sh::sanitize_data($_POST[$field_name], $validation_key, $validation_rule);
                                }
                            }

                            $checkbox_items = array('wt_pf_enable_import_log', 'wt_pf_enable_history_auto_delete', 'wt_pf_include_bom', 'wt_pf_all_shipping_zone');
                            foreach ($checkbox_items as $checkbox_item) {
                                $new_advanced_settings[$checkbox_item] = isset($new_advanced_settings[$checkbox_item]) ? $new_advanced_settings[$checkbox_item] : 0;
                            }

                            Webtoffee_Product_Feed_Sync_Pro_Common_Helper::set_advanced_settings($new_advanced_settings);
                            $out['status'] = true;
                            $out['msg'] = __('Settings Updated', 'webtoffee-product-feed-pro');
                            do_action('wt_pf_after_advanced_setting_update_pro', $new_advanced_settings);
                        }
                        echo json_encode($out);
                        exit();
                    }

                    
                    /**
                     * 	Save admin product edit additional fields settings ajax hook
                     */
                    public function save_settings_custom_fields() {
                        $out = array(
                            'status' => false,
                            'msg' => __('Error', 'webtoffee-product-feed-pro'),
                        );

                        if ( Wt_Pf_Sh::check_write_access( WEBTOFFEE_PRODUCT_FEED_PRO_ID ) ) {
                            
                            $checkbox_items = array(
                                'discard',
                                'brand',
                                'gtin',
                                'mpn',
                                'han',
                                'ean',
                                'condition',
                                'agegroup',
                                'gender',
                                'size',
                                'color',
                                'material',
                                'pattern',
                                'unit_pricing_measure',
                                'unit_pricing_base_measure',
                                'energy_efficiency_class',
                                'min_energy_efficiency_class',
                                'max_energy_efficiency_class',
                                'glpi_pickup_method',
                                'glpi_pickup_sla',
                                'custom_label_0',
                                'custom_label_1',
                                'custom_label_2',
                                'custom_label_3',
                                'custom_label_4',
                                'availability_date',
                                '_wt_google_google_product_category',
                                '_wt_facebook_fb_product_category'
                            );
                            
                            $new_advanced_settings = array();
                            foreach ($checkbox_items as $checkbox_item) {
                                $new_advanced_settings[$checkbox_item] = isset($_POST['_wt_feed_'.$checkbox_item]) ? absint( $_POST['_wt_feed_'.$checkbox_item] ) : 0;
                            }

                            update_option('wt_pf_enabled_product_fields', $new_advanced_settings);
                            $out['status'] = true;
                            $out['msg'] = __('Settings Updated', 'webtoffee-product-feed-pro');
                            do_action('wt_pf_after_custom_fields_setting_update_pro', $new_advanced_settings);
                        }
                        echo json_encode($out);
                        exit();
                    }                    
                    
                    /**
                     * 	Delete pre-saved temaplates entry from DB - ajax hook
                     */
                    public function delete_template() {
                        $out = array(
                            'status' => false,
                            'msg' => __('Error', 'webtoffee-product-feed-pro'),
                        );

                        if (Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_PRO_ID)) {
                            if (isset($_POST['template_id'])) {

                                global $wpdb;
                                $template_id = absint($_POST['template_id']);
                                $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync_Pro::$template_tb;
                                $where = "=%d";
                                $where_data = array($template_id);
                                $wpdb->query($wpdb->prepare("DELETE FROM $tb WHERE id" . $where, $where_data));
                                $out['status'] = true;
                                $out['msg'] = __('Template deleted successfully', 'webtoffee-product-feed-pro');
                                $out['template_id'] = $template_id;
                            }
                        }
                        wp_send_json($out);
                    }

                    /**
                      Registers modules: admin
                     */
                    public function admin_modules() {
                        $wt_pf_admin_modules = get_option('wt_pf_admin_modules');
                        if ($wt_pf_admin_modules === false) {
                            $wt_pf_admin_modules = array();
                        }
                        foreach (self::$modules as $module) { //loop through module list and include its file
                            $is_active = 1;
                            if (isset($wt_pf_admin_modules[$module])) {
                                $is_active = $wt_pf_admin_modules[$module]; //checking module status
                            } else {
                                $wt_pf_admin_modules[$module] = 1; //default status is active
                            }
                            $module_file = plugin_dir_path(__FILE__) . "modules/$module/$module.php";
                            if (file_exists($module_file) && $is_active == 1) {
                                self::$existing_modules[] = $module; //this is for module_exits checking
                                require_once $module_file;
                            } else {
                                $wt_pf_admin_modules[$module] = 0;
                            }
                        }
                        $out = array();
                        foreach ($wt_pf_admin_modules as $k => $m) {
                            if (in_array($k, self::$modules)) {
                                $out[$k] = $m;
                            }
                        }

                        update_option('wt_pf_admin_modules', $out);

                        // Explode the plugin path into an array
                        $plugin_path_array = explode('/', WT_PRODUCT_FEED_PRO_BASE_NAME);

                        // Plugin folder is the first element
                        $plugin_folder_name = reset($plugin_path_array);

                        /**
                         * 	Add on modules 
                         */
                        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                        foreach (self::$addon_modules as $module) { //loop through module list and include its file
                            $plugin_file = "$plugin_folder_name-$module/$plugin_folder_name-$module.php";
                            if (is_plugin_active($plugin_file)) {
                                $module_file = WP_PLUGIN_DIR . "/$plugin_folder_name-$module/$module/$module.php";
                                if (file_exists($module_file)) {
                                    self::$existing_modules[] = $module;
                                    require_once $module_file;
                                }
                            }
                        }


                        $addon_modules_basic = array(
                            'custom' => $plugin_folder_name,
                            'google' => $plugin_folder_name,    
                            'google_local_product_inventory' => $plugin_folder_name,
                            'google_local_inventory_ads' => $plugin_folder_name,
                            'google_product_reviews' => $plugin_folder_name,                            
                            'google_promotions' => $plugin_folder_name,
                            'buyon_google' => $plugin_folder_name,   
                            'google_manufacturer' => $plugin_folder_name,                               
                            'facebook' => $plugin_folder_name,
                            'tiktok' => $plugin_folder_name,
                            //'tiktokshop' => $plugin_folder_name,
                            'pinterest' => $plugin_folder_name,
                            'pinterest_rss' => $plugin_folder_name,
                            'snapchat' => $plugin_folder_name, 
                            'bing' => $plugin_folder_name,
                            'idealo' => $plugin_folder_name,
                            'leguide' => $plugin_folder_name,
                            'pricespy' => $plugin_folder_name,
                            'pricerunner' => $plugin_folder_name,
                            'skroutz' => $plugin_folder_name,
                            'shopzilla' => $plugin_folder_name,
                            'fruugo' => $plugin_folder_name,
                            'heureka' => $plugin_folder_name,
                            'vivino' => $plugin_folder_name,
                            'yandex' => $plugin_folder_name,
                            'onbuy' => $plugin_folder_name,
                            'twitter' => $plugin_folder_name,
                            'rakuten' => $plugin_folder_name,
                            'shopmania' => $plugin_folder_name,
                            'criteo' => $plugin_folder_name,
                        );

                        foreach ($addon_modules_basic as $module_key => $module_path) {
                            if (is_plugin_active("{$module_path}/{$module_path}.php")) {
                                $module_file = WP_PLUGIN_DIR . "/{$module_path}/admin/modules/$module_key/$module_key.php";
                                if (file_exists($module_file)) {
                                    self::$existing_modules[] = $module_key;
                                    require_once $module_file;
                                }
                            }
                        }
                    }

                    public static function module_exists($module) {
                        return in_array($module, self::$existing_modules);
                    }

                    /**
                     * Envelope settings tab content with tab div.
                     * relative path is not acceptable in view file
                     */
                    public static function envelope_settings_tabcontent($target_id, $view_file = "", $html = "", $variables = array(), $need_submit_btn = 0) {
                        extract($variables);
                        ?>
            <div class="wt-pfd-tab-content" data-id="<?php echo $target_id; ?>">
            <?php
            if ($view_file != "" && file_exists($view_file)) {
                include_once $view_file;
            } else {
                echo $html;
            }
            ?>
            <?php
            if ($need_submit_btn == 1) {
                include WT_PRODUCT_FEED_PRO_PLUGIN_PATH . "admin/views/admin-settings-save-button.php";
            }
            ?>
            </div>
            <?php
        }

        /**
         * Schedule Facebook product sync based on the data submitted
         * 
         * @param array $sync_args Contains the schedule interval and recurrence and product search criteria.
         */
        public function schedule_fb_sync($sync_args) {


            global $wpdb;
            $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync_Pro::$sync_table;
            $cron_start_time = $sync_args['wt_iew_cron_start_val_hour'] . ':' . $sync_args['wt_iew_cron_start_val_minute'] . ' ' . $sync_args['wt_iew_cron_start_ampm_val'];
            $cron_data = array(
                'interval' => $sync_args['wt_sync_schedule_interval'],
                'day_vl' => $sync_args['wt_sync_schedule_cron_day'],
                'start_time' => $cron_start_time,
            );

            $start_time = Webtoffee_Product_Feed_Sync_Pro_Cron::prepare_start_time($cron_data);
            $insert_data = array(
                'cron_data' => maybe_serialize($sync_args),
                'start_time' => $start_time, //next cron start time
                'last_run' => 0, //first time, not started yet
                'status' => Webtoffee_Product_Feed_Sync_Pro_Cron::$status_arr['not_started'], //not started yet status
                'next_offset' => 0,
            );

            $insert_data_type = array('%s', '%d', '%d', '%d', '%d');

            $wpdb->insert($tb, $insert_data, $insert_data_type); //success
        }                

    }
}

