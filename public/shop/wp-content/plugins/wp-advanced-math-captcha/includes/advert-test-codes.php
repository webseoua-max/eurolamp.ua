<?php

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'ADVREC_VERSION', '1.3.0' );
define( 'ADVREC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADVREC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ADVREC_OPTION_NAME', 'recommendation_000' );
define( 'ADVREC_TARGET_PLUGIN', 'image-optimizer-x' );

/**
 * Main plugin class
 */
class ADVREC_AdverPluginRecommendation {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Check if target plugin is already installed and active
        if ( $this->is_target_plugin_active() ) {
            return; // Exit early, don't initialize anything
        }

        add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_menu' ), 100 );
        add_action( 'admin_menu', array( $this, 'add_admin_page' ) );
        add_action( 'admin_post_advrec_hide_recommendation', array( $this, 'handle_hide_recommendation' ) );
        add_action( 'admin_post_advrec_show_recommendation', array( $this, 'handle_show_recommendation' ) );
        add_action( 'admin_post_advrec_install_plugin', array( $this, 'handle_install_plugin' ) );
        add_action( 'admin_head', array( $this, 'output_admin_styles' ) );
        add_action( 'admin_notices', array( $this, 'show_media_library_notice' ) );
    }

    /**
     * Check if target plugin is active
     */
    private function is_target_plugin_active() {
        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $plugin_file = ADVREC_TARGET_PLUGIN . '/' . ADVREC_TARGET_PLUGIN . '.php';
        return is_plugin_active( $plugin_file );
    }

    /**
     * Show notice on Media Library page
     */
    public function show_media_library_notice() {
        // Check if we're on the Media Library page
        $screen = get_current_screen();
        if ( ! $screen || 'upload' !== $screen->id ) {
            return;
        }

        // Check if user can manage options
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Don't show if already dismissed
        $timestamp = get_option( ADVREC_OPTION_NAME );
        if ( false !== $timestamp && current_time( 'timestamp' ) < intval( $timestamp ) ) {
            return;
        }

        ?>
        <div class="notice notice-warning advrec-media-notice is-dismissible" style="position: relative; border-left-color: #ff6b35; padding: 0; overflow: hidden;">
            <style>
                .advrec-media-notice {
                    display: flex;
                    align-items: center;
                    padding: 0 !important;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    border-left: 4px solid #ff6b35;
                    background: linear-gradient(135deg, #fff5f2 0%, #ffffff 100%);
                }
                
                .advrec-media-notice-icon {
                    flex-shrink: 0;
                    padding: 20px 25px;
                    background: #ff6b35;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .advrec-media-notice-icon .dashicons {
                    font-size: 32px;
                    width: 32px;
                    height: 32px;
                    color: #fff;
                }
                
                .advrec-media-notice-content {
                    flex: 1;
                    padding: 20px 25px;
                }
                
                .advrec-media-notice-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #1d2327;
                    margin: 0 0 8px 0;
                }
                
                .advrec-media-notice-text {
                    font-size: 14px;
                    color: #50575e;
                    margin: 0;
                    line-height: 1.5;
                }
                
                .advrec-media-notice-actions {
                    flex-shrink: 0;
                    padding: 20px 25px;
                    display: flex;
                    gap: 10px;
                    align-items: center;
                }
                
                .advrec-media-notice-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 10px 20px;
                    background: #ff6b35;
                    color: #fff;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: 600;
                    font-size: 14px;
                    transition: all 0.3s ease;
                    border: none;
                    cursor: pointer;
                }
                
                .advrec-media-notice-btn:hover {
                    background: #e55a28;
                    color: #fff;
                    transform: translateY(-1px);
                    box-shadow: 0 4px 8px rgba(255, 107, 53, 0.3);
                }
                
                .advrec-media-notice-btn .dashicons {
                    font-size: 18px;
                    width: 18px;
                    height: 18px;
                }
                
                @media screen and (max-width: 782px) {
                    .advrec-media-notice {
                        flex-direction: column;
                        align-items: stretch;
                    }
                    
                    .advrec-media-notice-icon {
                        padding: 15px;
                    }
                    
                    .advrec-media-notice-actions {
                        padding: 15px 25px;
                        justify-content: flex-start;
                    }
                }
            </style>
            
            <div class="advrec-media-notice-icon">
                <span class="dashicons dashicons-format-image"></span>
            </div>
            
            <div class="advrec-media-notice-content">
                <h3 class="advrec-media-notice-title">Your Images Are Not Optimized!</h3>
                <p class="advrec-media-notice-text">
                    Unoptimized images slow down your website and hurt your SEO rankings. 
                    Optimize your media library now and boost site speed by up to 80%!
                </p>
            </div>
            
            <div class="advrec-media-notice-actions">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=media-optimization' ) ); ?>" class="advrec-media-notice-btn">
                    <span class="dashicons dashicons-performance"></span>
                    Optimize Now
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Output admin styles
     */
    public function output_admin_styles() {
        $screen = get_current_screen();
        if ( ! $screen || 'toplevel_page_media-optimization' !== $screen->id ) {
            return;
        }
        ?>
        <style>
            /* Main container */
            .advrec-wrap {
                max-width: 900px;
                margin: 20px 0;
            }

            /* Content section */
            .advrec-content {
                background: #fff;
                border-radius: 8px;
                padding: 0;
                margin-top: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            /* Banner section */
            .advrec-banner {
                width: 100%;
                height: auto;
                display: block;
                border-bottom: 3px solid #2271b1;
            }

            /* Content padding */
            .advrec-inner {
                padding: 40px;
            }

            /* Headline */
            .advrec-headline {
                font-size: 28px;
                font-weight: 600;
                color: #1d2327;
                margin: 0 0 20px 0;
                line-height: 1.3;
            }

            /* Subheadline */
            .advrec-subheadline {
                font-size: 18px;
                color: #50575e;
                margin: 0 0 30px 0;
                line-height: 1.6;
            }

            /* Features list */
            .advrec-features {
                list-style: none;
                padding: 0;
                margin: 20px 0 30px 0;
            }

            .advrec-features li {
                padding: 12px 0 12px 35px;
                position: relative;
                font-size: 16px;
                line-height: 1.6;
                color: #50575e;
            }

            .advrec-features li:before {
                content: "✓";
                position: absolute;
                left: 0;
                color: #00a32a;
                font-weight: bold;
                font-size: 20px;
            }

            /* CTA section */
            .advrec-cta {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 35px;
                border-radius: 8px;
                text-align: center;
                margin: 30px 0;
            }

            .advrec-cta-title {
                color: #fff;
                font-size: 24px;
                font-weight: 600;
                margin: 0 0 15px 0;
            }

            .advrec-cta-text {
                color: rgba(255, 255, 255, 0.95);
                font-size: 16px;
                margin: 0 0 25px 0;
                line-height: 1.6;
            }

            /* Install button */
            .advrec-install-btn {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                background: #fff;
                color: #764ba2;
                padding: 16px 45px;
                font-size: 18px;
                font-weight: 600;
                border: none;
                border-radius: 50px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                text-decoration: none;
            }

            .advrec-install-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
                background: #f8f9fa;
                color: #764ba2;
            }

            .advrec-install-btn .dashicons {
                font-size: 24px;
                width: 24px;
                height: 24px;
            }

            /* Stats section */
            .advrec-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin: 30px 0;
            }

            .advrec-stat {
                text-align: center;
                padding: 25px;
                background: #f6f7f7;
                border-radius: 8px;
            }

            .advrec-stat-number {
                display: block;
                font-size: 36px;
                font-weight: 700;
                color: #2271b1;
                margin-bottom: 8px;
            }

            .advrec-stat-label {
                display: block;
                font-size: 14px;
                color: #50575e;
                font-weight: 500;
            }

            /* Notice */
            .advrec-notice {
                padding: 15px 20px;
                margin: 20px 0;
                border-radius: 4px;
            }

            .advrec-notice.success {
                background: #d7f7df;
                border-left: 4px solid #00a32a;
            }

            .advrec-notice.error {
                background: #fcf0f1;
                border-left: 4px solid #d63638;
            }

            .advrec-notice p {
                margin: 0;
                font-size: 14px;
            }

            .advrec-notice.success p {
                color: #00450a;
            }

            .advrec-notice.error p {
                color: #3c1010;
            }

            /* Alternative buttons section */
            .advrec-alt-buttons {
                display: flex;
                gap: 15px;
                justify-content: center;
                margin-top: 35px;
                padding-top: 35px;
                border-top: 1px solid #dcdcde;
            }

            .advrec-alt-buttons form {
                margin: 0;
            }

            .advrec-alt-buttons .button {
                padding: 10px 25px;
                height: auto;
                font-size: 14px;
            }

            /* Admin bar icon */
            #wpadminbar #wp-admin-bar-media-optimization .ab-icon:before {
                content: "\f104";
                top: 2px;
            }

            #wpadminbar #wp-admin-bar-media-optimization .ab-label {
                margin-left: 5px;
            }

            /* Responsive */
            @media screen and (max-width: 782px) {
                .advrec-inner {
                    padding: 25px;
                }
                
                .advrec-headline {
                    font-size: 22px;
                }
                
                .advrec-subheadline {
                    font-size: 16px;
                }
                
                .advrec-stats {
                    grid-template-columns: 1fr;
                }
                
                .advrec-alt-buttons {
                    flex-direction: column;
                }

                .advrec-cta {
                    padding: 25px;
                }

                .advrec-install-btn {
                    padding: 14px 35px;
                    font-size: 16px;
                }
            }
			
            /* Loader spinner */
            .advrec-loader-spinner {
                border: 4px solid rgba(255, 255, 255, 0.3);
                border-top: 4px solid #fff;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: advrec-spin 1s linear infinite;
                margin: 0 auto;
            }
            
            @keyframes advrec-spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
        <?php
    }

    /**
     * Check if menu should be displayed
     */
    private function should_show_menu() {
        $timestamp = get_option( ADVREC_OPTION_NAME );
        
        // Show if option doesn't exist
        if ( false === $timestamp ) {
            return true;
        }
        
        // Show if current time is greater than saved timestamp
        if ( current_time( 'timestamp' ) > intval( $timestamp ) ) {
            return true;
        }
        
        return false;
    }

    /**
     * Add menu to admin bar
     */
    public function add_admin_bar_menu( $wp_admin_bar ) {
        // Check if user can manage options
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Check if menu should be displayed
        if ( ! $this->should_show_menu() ) {
            return;
        }

        $args = array(
            'id'    => 'media-optimization',
            'title' => '<span class="ab-icon dashicons dashicons-admin-media"></span><span class="ab-label">Media Optimization</span>',
            'href'  => admin_url( 'admin.php?page=media-optimization' ),
            'meta'  => array(
                'class' => 'media-optimization-menu',
            ),
        );
        
        $wp_admin_bar->add_node( $args );
    }

    /**
     * Add admin page
     */
    public function add_admin_page() {
		
        // Check if menu should be displayed
        if ( ! $this->should_show_menu() ) {
            return;
        }
		
        add_menu_page(
            'Media Optimization',
            'Media Optimization',
            'manage_options',
            'media-optimization',
            array( $this, 'render_admin_page' ),
            'dashicons-admin-media',
            100
        );
    }

    /**
     * Check plugin status
     */
    private function get_plugin_status() {
        $plugin_file = ADVREC_TARGET_PLUGIN . '/' . ADVREC_TARGET_PLUGIN . '.php';
        
        // Check if installed
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        
        $all_plugins = get_plugins();
        
        if ( ! isset( $all_plugins[ $plugin_file ] ) ) {
            return 'not_installed';
        }
        
        // Check if active
        if ( is_plugin_active( $plugin_file ) ) {
            return 'active';
        }
        
        return 'inactive';
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to access this page.' );
        }

        $plugin_status = $this->get_plugin_status();
        ?>
        <div class="wrap advrec-wrap">
            <h1>Media Optimization</h1>
            
            <?php
            // Display success message if set
            if ( isset( $_GET['message'] ) ) {
                $message = sanitize_text_field( wp_unslash( $_GET['message'] ) );
                if ( 'hidden' === $message ) {
                    ?>
                    <div class="advrec-notice success">
                        <p>Recommendation hidden for 30 days.</p>
                    </div>
                    <?php
                } elseif ( 'shown' === $message ) {
                    ?>
                    <div class="advrec-notice success">
                        <p>Recommendation is now visible.</p>
                    </div>
                    <?php
                } elseif ( 'plugin_installed' === $message ) {
                    ?>
                    <div class="advrec-notice success">
                        <p>Plugin installed and activated successfully!</p>
                    </div>
                    <?php
                }
            }
            
            // Display error message if set
            if ( isset( $_GET['error'] ) ) {
                $error = sanitize_text_field( wp_unslash( $_GET['error'] ) );
                ?>
                <div class="advrec-notice error">
                    <p><?php echo esc_html( $error ); ?></p>
                </div>
                <?php
            }
            ?>

            <div class="advrec-content">
                <!-- Banner -->
                <img src="https://ps.w.org/image-optimizer-x/assets/banner-772x250.png" 
                     alt="Image Optimizer X" 
                     class="advrec-banner">

                <div class="advrec-inner">
                    <!-- Headline -->
                    <h2 class="advrec-headline">
                        Optimize Your Images & Boost Your Site Speed by Up to 80%
                    </h2>
                    
                    <p class="advrec-subheadline">
                        Image Optimizer X automatically compresses and optimizes your images without losing quality. 
                        Faster loading times mean better SEO rankings and happier visitors!
                    </p>

                    <!-- Features -->
                    <ul class="advrec-features">
                        <li><strong>Automatic Image Compression</strong> - Reduce image file sizes by up to 90% without quality loss</li>
                        <li><strong>Lazy Loading</strong> - Images load only when needed, dramatically improving page speed</li>
                        <li><strong>WebP Conversion</strong> - Convert images to next-gen WebP format for maximum performance</li>
                        <li><strong>Bulk Optimization</strong> - Optimize all existing images in your media library with one click</li>
                        <li><strong>CDN Integration</strong> - Serve your images faster from global servers</li>
                        <li><strong>SEO Friendly</strong> - Better Core Web Vitals scores = Higher Google rankings</li>
                    </ul>

                    <!-- Stats -->
                    <div class="advrec-stats">
                        <div class="advrec-stat">
                            <span class="advrec-stat-number">80%</span>
                            <span class="advrec-stat-label">Faster Load Times</span>
                        </div>
                        <div class="advrec-stat">
                            <span class="advrec-stat-number">90%</span>
                            <span class="advrec-stat-label">File Size Reduction</span>
                        </div>
                        <div class="advrec-stat">
                            <span class="advrec-stat-number">100K+</span>
                            <span class="advrec-stat-label">Happy Users</span>
                        </div>
                    </div>

                    <?php if ( 'not_installed' === $plugin_status || 'inactive' === $plugin_status ) : ?>
                        <!-- CTA Section -->
                        <div class="advrec-cta">
                            <h3 class="advrec-cta-title">
                                <?php if ( 'not_installed' === $plugin_status ) : ?>
                                    Ready to Supercharge Your Website?
                                <?php else : ?>
                                    Activate Image Optimizer X Now!
                                <?php endif; ?>
                            </h3>
                            <p class="advrec-cta-text">
                                <?php if ( 'not_installed' === $plugin_status ) : ?>
                                    Install Image Optimizer X in one click and start optimizing your images automatically. 
                                    No configuration needed - it works right out of the box!
                                <?php else : ?>
                                    The plugin is already installed. Just one click to activate and start optimizing!
                                <?php endif; ?>
                            </p>
                            
                            <form method="post" id="advrec-install-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                                <input type="hidden" name="action" value="advrec_install_plugin">
                                <input type="hidden" name="plugin_action" value="<?php echo 'inactive' === $plugin_status ? 'activate' : 'install'; ?>">
                                <?php wp_nonce_field( 'advrec_install_plugin', 'advrec_install_nonce' ); ?>
                                <button type="submit" id="advrec-install-btn" class="advrec-install-btn">
                                    <span class="dashicons dashicons-download"></span>
                                    <?php if ( 'not_installed' === $plugin_status ) : ?>
                                        Install Image Optimizer X Now - FREE!
                                    <?php else : ?>
                                        Activate Image Optimizer X
                                    <?php endif; ?>
                                </button>
                            </form>
							
							
                            <!-- Loading indicator (hidden by default) -->
                            <div id="advrec-install-loader" style="display: none; text-align: center; margin-top: 20px;">
                                <div class="advrec-loader-spinner"></div>
                                <p style="color: rgba(255, 255, 255, 0.9); margin-top: 15px; font-size: 14px;">
                                    Installing plugin, please wait...
                                </p>
                            </div>
							
                        </div>
						
                            <script>
                            jQuery(document).ready(function($) {
                                $('#advrec-install-form').on('submit', function(e) {
                                    
                                    var $form = $(this);
                                    var $button = $('#advrec-install-btn');
                                    var $loader = $('#advrec-install-loader');
                                    
                                    // Hide button, show loader
                                    $button.hide();
                                    $loader.show();
                            
                                });
                            });
                            </script>
							
							
                    <?php endif; ?>

                    <!-- Alternative Actions -->
                    <div class="advrec-alt-buttons">
                        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <input type="hidden" name="action" value="advrec_hide_recommendation">
                            <?php wp_nonce_field( 'advrec_hide_recommendation', 'advrec_hide_nonce' ); ?>
                            <button type="submit" class="button">
                                Hide Recommendation for 30 Days
                            </button>
                        </form>

                        <?php /* <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                            <input type="hidden" name="action" value="advrec_show_recommendation">
                            <?php wp_nonce_field( 'advrec_show_recommendation', 'advrec_show_nonce' ); ?>
                            <button type="submit" class="button">
                                Always Show Recommendation
                            </button>
                        </form>  */ ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Handle hide recommendation
     */
    public function handle_hide_recommendation() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }

        // Verify nonce
        if ( ! isset( $_POST['advrec_hide_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['advrec_hide_nonce'] ) ), 'advrec_hide_recommendation' ) ) {
            wp_die( 'Security check failed.' );
        }

        // Calculate timestamp (current time + 30 days)
        $timestamp = current_time( 'timestamp' ) + ( 30 * DAY_IN_SECONDS );
        
        // Save to database
        update_option( ADVREC_OPTION_NAME, $timestamp );

        // Redirect back with success message
        wp_safe_redirect( add_query_arg( 'message', 'hidden', admin_url( 'index.php' ) ) );
        exit;
    }

    /**
     * Handle show recommendation
     */
    public function handle_show_recommendation() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }

        // Verify nonce
        if ( ! isset( $_POST['advrec_show_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['advrec_show_nonce'] ) ), 'advrec_show_recommendation' ) ) {
            wp_die( 'Security check failed.' );
        }

        // Delete option
        delete_option( ADVREC_OPTION_NAME );

        // Redirect back with success message
        wp_safe_redirect( add_query_arg( 'message', 'shown', admin_url( 'admin.php?page=media-optimization' ) ) );
        exit;
    }

    /**
     * Handle plugin installation
     */
    public function handle_install_plugin() {
        // Check user capabilities
        if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
            wp_die( 'You do not have sufficient permissions to perform this action.' );
        }

        // Verify nonce
        if ( ! isset( $_POST['advrec_install_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['advrec_install_nonce'] ) ), 'advrec_install_plugin' ) ) {
            wp_die( 'Security check failed.' );
        }

        $plugin_slug = ADVREC_TARGET_PLUGIN;
        $plugin_file = $plugin_slug . '/' . $plugin_slug . '.php';
        $plugin_action = isset( $_POST['plugin_action'] ) ? sanitize_text_field( wp_unslash( $_POST['plugin_action'] ) ) : 'install';

        // If only activation is needed
        if ( 'activate' === $plugin_action ) {
            $result = activate_plugin( $plugin_file );
            
            if ( is_wp_error( $result ) ) {
                wp_safe_redirect( add_query_arg( 'error', urlencode( $result->get_error_message() ), admin_url( 'admin.php?page=media-optimization' ) ) );
                exit;
            }
            
            wp_safe_redirect( add_query_arg( 'message', 'plugin_installed', admin_url( 'admin.php?page=media-optimization' ) ) );
            exit;
        }

        // Include required files for plugin installation
        if ( ! function_exists( 'plugins_api' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }
        if ( ! class_exists( 'WP_Upgrader' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        // Get plugin information from WordPress.org
        $api = plugins_api( 'plugin_information', array(
            'slug'   => $plugin_slug,
            'fields' => array(
                'short_description' => false,
                'sections'          => false,
                'requires'          => false,
                'rating'            => false,
                'ratings'           => false,
                'downloaded'        => false,
                'last_updated'      => false,
                'added'             => false,
                'tags'              => false,
                'compatibility'     => false,
                'homepage'          => false,
                'donate_link'       => false,
            ),
        ) );

        if ( is_wp_error( $api ) ) {
            wp_safe_redirect( add_query_arg( 'error', urlencode( 'Plugin not found in WordPress.org repository: ' . $api->get_error_message() ), admin_url( 'admin.php?page=media-optimization' ) ) );
            exit;
        }

        // Create upgrader instance with skin
        $skin     = new WP_Ajax_Upgrader_Skin();
        $upgrader = new Plugin_Upgrader( $skin );

        // Install plugin
        $install_result = $upgrader->install( $api->download_link );

        if ( is_wp_error( $install_result ) ) {
            wp_safe_redirect( add_query_arg( 'error', urlencode( 'Installation failed: ' . $install_result->get_error_message() ), admin_url( 'admin.php?page=media-optimization' ) ) );
            exit;
        }

        if ( false === $install_result ) {
            wp_safe_redirect( add_query_arg( 'error', urlencode( 'Installation failed: Unknown error' ), admin_url( 'admin.php?page=media-optimization' ) ) );
            exit;
        }

        // Activate plugin after installation
        $activate_result = activate_plugin( $plugin_file );

        if ( is_wp_error( $activate_result ) ) {
            wp_safe_redirect( add_query_arg( 'error', urlencode( 'Plugin installed but activation failed: ' . $activate_result->get_error_message() ), admin_url( 'admin.php?page=media-optimization' ) ) );
            exit;
        }

        // Success - redirect with success message
        wp_safe_redirect( add_query_arg( 'message', 'plugin_installed', admin_url( 'admin.php?page=media-optimization' ) ) );
        exit;
    }
}

/**
 * Initialize plugin
 */
function advrec_init() {
    return ADVREC_AdverPluginRecommendation::get_instance();
}

// Start the plugin
add_action( 'plugins_loaded', 'advrec_init' );
