<?php
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

new Math_Captcha_Settings();

class Math_Captcha_Settings {

	public $mathematical_operations;
	public $groups;
	public $forms;

	public function __construct() {
		// actions
		add_action( 'init', array( $this, 'load_defaults' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu_options' ) );
	}

	/**
	 * Load defaults.
	 */
	public function load_defaults() {
		if ( ! is_admin() )
			return;

		$this->forms = array(
			'login_form'			 => __( 'login form', 'math-captcha' ),
			'registration_form'		 => __( 'registration form', 'math-captcha' ),
			'reset_password_form'	 => __( 'reset password form', 'math-captcha' ),
			'comment_form'			 => __( 'comment form', 'math-captcha' ),
			'bbpress'				 => __( 'bbPress', 'math-captcha' ),
			'contact_form_7'		 => __( 'Contact Form 7', 'math-captcha' ),
			'woocommerce_login'		 => __( 'WooCommerce login', 'math-captcha' ),
			'woocommerce_register'	 => __( 'WooCommerce register', 'math-captcha' ),
			'woocommerce_reset'	     => __( 'WooCommerce reset', 'math-captcha' ),
			'woocommerce_checkout'	 => __( 'WooCommerce checkout', 'math-captcha' ),
			'wpforms'	 			 => __( 'WPForms', 'math-captcha' ),
            'formidable_forms'       => __('Formidable Forms', 'math-captcha'),
		);

		$this->mathematical_operations = array(
			'addition'		 => __( 'addition (+)', 'math-captcha' ),
			'subtraction'	 => __( 'subtraction (-)', 'math-captcha' ),
			'multiplication' => __( 'multiplication (&#215;)', 'math-captcha' ),
			'division'		 => __( 'division (&#247;)', 'math-captcha' )
		);

		$this->groups = array(
			'numbers'	 => __( 'numbers', 'math-captcha' ),
			'words'		 => __( 'words', 'math-captcha' )
		);
	}

	/**
	 * Add options menu.
	 */
	public function admin_menu_options() {
		add_options_page(
			__( 'Math Captcha', 'math-captcha' ), __( 'Math Captcha', 'math-captcha' ), 'manage_options', 'math-captcha', array( $this, 'options_page' )
		);
	}

	/**
	 * Render options page.
	 * 
	 * @return mixed
	 */
	public function options_page() 
    {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        
        switch ($action)
        {
            case 'cancel-key':
                Math_Captcha_Core::isPRO(true);
                break;
                
            case 'restore-purchase':
                Math_Captcha_Core::RestorePurchase();
                break;
                
            case 'download-geo':
                Math_Captcha_Core::Update_GEO_database();
                break;
        }
        
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'math'; // Default to math
        
        if ($tab == 'math' && Math_Captcha()->options['general']['collect_logs'])
        {
            
            // Get last 30 days
            $logs = WP_CONTENT_DIR.'/uploads/logs/mathcaptcha';
            $html_chart = '';
            $data = array();
            $file_counter = 0;
            if (file_exists($logs))
            {
                for ($i = 30; $i >= 0; $i--)
                {
                    $k = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i,   date("Y")));
                    $data[$k] = 0;
                }
                
                $exp_date = date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")-1));  // Keep logs for 1 year
                
                $file_counter = 0;
    
                foreach (glob($logs."/*.log") as $filename) 
                {
                    $date = trim(substr(basename($filename), 0, -4));
                    
                    if (isset($data[$date]))
                    {
                        $data[$date] = filesize($filename);
                        $file_counter++;
                        
                        continue;
                    }
                    
                    if ($date < $exp_date) unlink($filename);
                }   
                
                $html_chart = '<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                <div id="container">
                <canvas id="mathcanvas"></canvas>
                </div>
                
                <script>
    		var barChartData = {
    			labels: ['."'".implode("','", array_keys($data))."'".'],
    			datasets: [{
    				label: \'Blocked by captcha\',
    				backgroundColor: \'rgba(202, 34, 71, 0.8)\',
    				borderColor: \'rgba(202, 34, 71, 0.8)\',
    				borderWidth: 1,
    				data: ['.implode(",", $data).']
    			}]
    
    		};
    
    		window.onload = function() {
    			var ctx = document.getElementById(\'mathcanvas\').getContext(\'2d\');
    			window.myBar = new Chart(ctx, {
    				type: \'bar\',
    				data: barChartData,
    				options: {
    					responsive: true,
    					legend: {
    						position: \'top\',
    					},
    					title: {
    						display: true,
    						text: \'Statistic of blocked sessions (last 30 days)\'
    					}
    				}
    			});
    
    		};
                </script>
                ';         
            }
            
            
            if ($file_counter == 0) $html_chart = '<div class="update-nag notice notice-warning inline">Statistic data is not available yet. Captcha plugin needs to collect more data.</div>';
    
        }
        
        
        if (!defined('MATH_PLGLIC')) define( 'MATH_PLGLIC', Math_Captcha_Core::isPRO());
        
        echo '
		<div class="wrap">
			<h2>' . __( 'Math Captcha', 'math-captcha' ) . '</h2>';
            
        /*if ($tab == 'math') 
        { 
			echo '<a href="https://pentryforms.com/en?gid=WPPLG" target="_blank"><img src="'.plugins_url('images/', dirname(__FILE__)).'banner-pentryforms.png'.'"/></a><br>
			'.$html_chart;
        }*/
        echo $html_chart;
        
        echo '
            <div class="math-captcha-settings">
				<form action="options.php" id="settingsform" method="post">';
                ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=math-captcha&tab=math" class="nav-tab <?php echo ($_GET['tab'] == 'math' || !isset($_GET['tab'])) ? 'nav-tab-active' : ''; ?>">
                Math Captcha
            </a>
            <a href="?page=math-captcha&tab=geo" class="nav-tab <?php echo ($_GET['tab'] == 'geo') ? 'nav-tab-active' : ''; ?>">
                IP & GEO
            </a>
            <a href="?page=math-captcha&tab=restrict" class="nav-tab <?php echo ($_GET['tab'] == 'restrict') ? 'nav-tab-active' : ''; ?>">
                Limits & Restrictions
            </a>
            <a href="?page=math-captcha&tab=logs" class="nav-tab <?php echo ($_GET['tab'] == 'logs') ? 'nav-tab-active' : ''; ?>">
                Statistics & Logs
            </a>
            <?php /*
            <a href="?page=math-captcha&tab=customize" class="nav-tab <?php echo ($_GET['tab'] == 'customize') ? 'nav-tab-active' : ''; ?>">
                Customize & Style
            </a>
            */ ?>
            <a href="?page=math-captcha&tab=support" class="nav-tab <?php echo ($_GET['tab'] == 'support') ? 'nav-tab-active' : ''; ?>">
                PRO & Support
            </a>
        </h2>
        
        <div class="tab-content">
                
                <?php
        
        // Show Warning       
        switch ($tab) {
            default:
                break;
                
            case 'geo':
                //self::mc_WarningMessage();
                break;
                
            case 'restrict':
                if (!MATH_PLGLIC) self::mc_WarningMessage();
                break;
                
            case 'logs':
                if (!MATH_PLGLIC) self::mc_WarningMessage();
                break;
                
            case 'customize':
                if (!MATH_PLGLIC) self::mc_WarningMessage();
                break;
                
            case 'support':
                if (!MATH_PLGLIC) self::mc_WarningMessage();
                break;
        }

		wp_nonce_field( 'update-options' );
		settings_fields( 'math_captcha_options' );
		do_settings_sections( 'math_captcha_options' );


        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'math'; // Default to math
        
        switch ($tab) {
            default:
            case 'math':
                // Hide geo tab
                self::mc_general_enable_ip_rules(true);
                self::mc_general_geo_captcha(true);
                // Hide restrict tab
                self::mc_general_block_enable_ip_rules(true);
                self::mc_general_enable_ip_auto_block(true);
                self::mc_general_max_number_attempts(true);
                self::mc_general_lockout_period(true);
                self::mc_general_block_geo_captcha(true);
                // Hide logs tab
                self::mc_general_collect_logs(true);
                // Hide customize tab
                // Hide support tab
                self::mc_general_show_powered_by(true);
                self::mc_general_deactivation_delete(true);
                self::mc_general_geo_db_autoupdate(true);
                break;
                
            case 'geo':
                // Hide math tab
                self::mc_general_enable_captcha_for(true);
                self::mc_general_hide_for_logged_users(true);
                self::mc_general_mathematical_operations(true);
                self::mc_general_groups(true);
                self::mc_general_title(true);
                self::mc_general_time(true);
                self::mc_general_block_direct_comments(true);
                // Hide restrict tab
                self::mc_general_block_enable_ip_rules(true);
                self::mc_general_enable_ip_auto_block(true);
                self::mc_general_max_number_attempts(true);
                self::mc_general_lockout_period(true);
                self::mc_general_block_geo_captcha(true);
                // Hide logs tab
                self::mc_general_collect_logs(true);
                // Hide customize tab
                // Hide support tab
                self::mc_general_show_powered_by(true);
                self::mc_general_deactivation_delete(true);
                self::mc_general_geo_db_autoupdate(true);
                break;
                
            case 'restrict':
                // Hide math tab
                self::mc_general_enable_captcha_for(true);
                self::mc_general_hide_for_logged_users(true);
                self::mc_general_mathematical_operations(true);
                self::mc_general_groups(true);
                self::mc_general_title(true);
                self::mc_general_time(true);
                self::mc_general_block_direct_comments(true);
                // Hide geo tab
                self::mc_general_enable_ip_rules(true);
                self::mc_general_geo_captcha(true);
                // Hide logs tab
                self::mc_general_collect_logs(true);
                // Hide customize tab
                // Hide support tab
                self::mc_general_show_powered_by(true);
                self::mc_general_deactivation_delete(true);
                self::mc_general_geo_db_autoupdate(true);
                break;
            case 'logs':
                self::mc_tab_logs();
                
                // Hide math tab
                self::mc_general_enable_captcha_for(true);
                self::mc_general_hide_for_logged_users(true);
                self::mc_general_mathematical_operations(true);
                self::mc_general_groups(true);
                self::mc_general_title(true);
                self::mc_general_time(true);
                self::mc_general_block_direct_comments(true);
                // Hide geo tab
                self::mc_general_enable_ip_rules(true);
                self::mc_general_geo_captcha(true);
                // Hide restrict tab
                self::mc_general_block_enable_ip_rules(true);
                self::mc_general_enable_ip_auto_block(true);
                self::mc_general_max_number_attempts(true);
                self::mc_general_lockout_period(true);
                self::mc_general_block_geo_captcha(true);
                // Hide customize tab
                // Hide support tab
                self::mc_general_show_powered_by(true);
                self::mc_general_deactivation_delete(true);
                self::mc_general_geo_db_autoupdate(true);
                break;
            case 'customize':
                // Hide math tab
                self::mc_general_enable_captcha_for(true);
                self::mc_general_hide_for_logged_users(true);
                self::mc_general_mathematical_operations(true);
                self::mc_general_groups(true);
                self::mc_general_title(true);
                self::mc_general_time(true);
                self::mc_general_block_direct_comments(true);
                // Hide geo tab
                self::mc_general_enable_ip_rules(true);
                self::mc_general_geo_captcha(true);
                // Hide restrict tab
                self::mc_general_block_enable_ip_rules(true);
                self::mc_general_enable_ip_auto_block(true);
                self::mc_general_max_number_attempts(true);
                self::mc_general_lockout_period(true);
                self::mc_general_block_geo_captcha(true);
                // Hide logs tab
                self::mc_general_collect_logs(true);
                // Hide support tab
                self::mc_general_show_powered_by(true);
                self::mc_general_deactivation_delete(true);
                self::mc_general_geo_db_autoupdate(true);
                break;
            case 'support':
                self::mc_tab_support();
                
                // Hide math tab
                self::mc_general_enable_captcha_for(true);
                self::mc_general_hide_for_logged_users(true);
                self::mc_general_mathematical_operations(true);
                self::mc_general_groups(true);
                self::mc_general_title(true);
                self::mc_general_time(true);
                self::mc_general_block_direct_comments(true);
                // Hide geo tab
                self::mc_general_enable_ip_rules(true);
                self::mc_general_geo_captcha(true);
                // Hide restrict tab
                self::mc_general_block_enable_ip_rules(true);
                self::mc_general_enable_ip_auto_block(true);
                self::mc_general_max_number_attempts(true);
                self::mc_general_lockout_period(true);
                self::mc_general_block_geo_captcha(true);
                // Hide customize tab
                // Hide logs tab
                self::mc_general_collect_logs(true);
                break;
        }
        


        ?>
        </div>
        <?php
        
		echo '
					<p class="submit">';

		submit_button( '', 'primary', 'save_mc_general', false );

		echo ' ';

		submit_button( __( 'Reset to defaults', 'math-captcha' ), 'secondary reset_mc_settings', 'reset_mc_general', false );

		echo '
					</p>
    		</form>
			</div>
			<div class="clear"></div>
		</div>';
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		// general settings
		register_setting( 'math_captcha_options', 'math_captcha_options', array( $this, 'validate_settings' ) );
        
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'math'; // Default to math
        
        
        if (!defined('MATH_PLGLIC')) define( 'MATH_PLGLIC', Math_Captcha_Core::isPRO());
        $PRO_label = MATH_PLGLIC ? '' : ' [*PRO version*]';
        
        switch ($tab) {
            default:
            case 'math':
        		add_settings_section( 'math_captcha_settings', __( 'Math Captcha settings', 'math-captcha' ), '', 'math_captcha_options' );
                add_settings_field( 'mc_general_enable_captcha_for', __( 'Enable Math Captcha for', 'math-captcha' ), array( $this, 'mc_general_enable_captcha_for' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_hide_for_logged_users', __( 'Hide for logged in users', 'math-captcha' ), array( $this, 'mc_general_hide_for_logged_users' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_mathematical_operations', __( 'Mathematical operations', 'math-captcha' ), array( $this, 'mc_general_mathematical_operations' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_groups', __( 'Display captcha as', 'math-captcha' ), array( $this, 'mc_general_groups' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_title', __( 'Captcha field title', 'math-captcha' ), array( $this, 'mc_general_title' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_time', __( 'Captcha time', 'math-captcha' ), array( $this, 'mc_general_time' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_block_direct_comments', __( 'Block Direct Comments', 'math-captcha' ), array( $this, 'mc_general_block_direct_comments' ), 'math_captcha_options', 'math_captcha_settings' );
                break;
                
            case 'geo':
                add_settings_section( 'math_captcha_settings', __( 'IP & GEO settings', 'math-captcha' ), '', 'math_captcha_options' );
        		add_settings_field( 'mc_general_enable_ip_rules', __( 'IP rules', 'math-captcha' ), array( $this, 'mc_general_enable_ip_rules' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_geo_database', __( 'GEO database', 'math-captcha' ), array( $this, 'mc_general_geo_database' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_geo_captcha', __( 'Enable GEO captcha rules', 'math-captcha' ), array( $this, 'mc_general_geo_captcha' ), 'math_captcha_options', 'math_captcha_settings' );
                break;
                
            case 'restrict':
                add_settings_section( 'math_captcha_settings', __( 'Limits & Restrictions settings', 'math-captcha' ), '', 'math_captcha_options' );
        		add_settings_field( 'mc_general_block_enable_ip_rules', __( 'Restrictions IP rules', 'math-captcha' ), array( $this, 'mc_general_block_enable_ip_rules' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_enable_ip_auto_block', __( 'Enable Auto IP Blocking'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_enable_ip_auto_block' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_max_number_attempts', __( 'Maximum Number of Attempts'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_max_number_attempts' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_lockout_period', __( 'Lockout Period'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_lockout_period' ), 'math_captcha_options', 'math_captcha_settings' );
        		  add_settings_field( 'mc_general_geo_database', __( 'GEO database', 'math-captcha' ), array( $this, 'mc_general_geo_database' ), 'math_captcha_options', 'math_captcha_settings' );
        		add_settings_field( 'mc_general_block_geo_captcha', __( 'Enable Restrictions by country'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_block_geo_captcha' ), 'math_captcha_options', 'math_captcha_settings' );

                break;
            case 'logs':
                add_settings_section( 'math_captcha_settings', __( 'Statistics & Logs', 'math-captcha' ), '', 'math_captcha_options' );
                add_settings_field( 'mc_general_collect_logs', __( 'Collect logs', 'math-captcha' ), array( $this, 'mc_general_collect_logs' ), 'math_captcha_options', 'math_captcha_settings' );
                break;
            case 'customize':
                add_settings_section( 'math_captcha_settings', __( 'Customize & Style', 'math-captcha' ), '', 'math_captcha_options' );
                break;
            case 'support':
                add_settings_section( 'math_captcha_settings', __( 'Support & Advanced settings', 'math-captcha' ), '', 'math_captcha_options' );
                add_settings_field( 'mc_general_show_powered_by', __( 'Show Powered by'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_show_powered_by' ), 'math_captcha_options', 'math_captcha_settings' );
                add_settings_field( 'mc_general_geo_db_autoupdate', __( 'GEO database update'.$PRO_label, 'math-captcha' ), array( $this, 'mc_general_geo_db_autoupdate' ), 'math_captcha_options', 'math_captcha_settings' );
                add_settings_field( 'mc_general_pro_support', __( 'PRO & Support', 'math-captcha' ), array( $this, 'mc_general_pro_support' ), 'math_captcha_options', 'math_captcha_settings' );
                add_settings_field( 'mc_general_deactivation_delete', __( 'Deactivation', 'math-captcha' ), array( $this, 'mc_general_deactivation_delete' ), 'math_captcha_options', 'math_captcha_settings' );
                break;
        }
        
	}


	public function mc_WarningMessage() 
    {
        ?>
        <div class="gpd-warning-label">Some functionalities are exclusively available in the PRO version.</div>
        <?php
    }
    
	public function mc_tab_logs() 
    {
        
        if (!Math_Captcha()->options['general']['collect_logs']) return;    // Collect logs is disabled

        ?>
        <div class="gpd-stats-buttons">
            <?php
            $periods = ['today', 'yesterday', 'last-week', 'last-month', '3-months', '6-months', '12-months'];
            $current_period = isset($_GET['period']) ? sanitize_text_field($_GET['period']) : 'last-week';
            if (!in_array($current_period, $periods)) $current_period = 'last-week';
                if (!MATH_PLGLIC) 
                {
                    switch ($current_period)
                    {
                        case 'last-month':
                        case '3-months':
                        case '6-months':
                        case '12-months':
                            $current_period = 'last-week';
                            break;
                    }
                }
            
            foreach ($periods as $period) 
            {
                $link = '?page=math-captcha&tab=logs&period=' . esc_attr($period);
                
                if (!MATH_PLGLIC) 
                {
                    switch ($period)
                    {
                        case 'last-month':
                        case '3-months':
                        case '6-months':
                        case '12-months':
                            $link = "javascript:alert('Available in PRO version only');";
                            break;
                    }
                }
                
                $is_active = ($period === $current_period) ? ' active' : '';
                echo '<a href="'.$link.'" class="gpd-stats-button' . $is_active . '">' . ucwords(str_replace('-', ' ', $period)) . '</a>';
            }
            ?>
        </div>
        <?php

            // Get last 30 days
            $logs = WP_CONTENT_DIR.'/uploads/logs/mathcaptcha';
            $html_chart = '';
            $data = array();
            $file_counter = 0;
            if (file_exists($logs))
            {
                $days = 0;
                $file_date = '';
                switch ($current_period)
                {
                    case 'today': 
                        $file_date = date("Y-m-d");
                        
                    case 'yesterday': 
                        if ($file_date == '') $file_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1,   date("Y")));
                        
                        $full_day_counts = [];
                        for ($hour = 0; $hour < 24; $hour++) 
                        {
                            $hour_key = $file_date . ' ' . sprintf('%02d', $hour) . ':00';
                            $full_day_counts[$hour_key] = 0;
                        }
                        
                        $log_file = $logs.'/sessions/'.$file_date.'.log';
                        
                        if (file_exists($log_file)) 
                        {
                            $log_lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            
                            if ($log_lines !== false) 
                            {
                                $date_str = basename($log_file, '.log'); // Извлекаем дату из имени файла
                                $base_date = DateTime::createFromFormat('Y-m-d', $date_str);
                                
                                foreach ($log_lines as $line) 
                                {
                                    $parts = explode('|', $line);
                                    
                                    if (count($parts) !== 3) {
                                        continue;
                                    }
                                
                                    $datetime_str = trim($parts[0]);
                                    
                                    $datetime = DateTime::createFromFormat('Y-m-d H:i:s', $datetime_str);
                                    
                                    if ($datetime === false) {
                                        continue;
                                    }
                                
                                    $hour_key = $datetime->format('Y-m-d H:00');
                                    
                                    $full_day_counts[$hour_key]++;
                                }
                            }
                        }
                        $data = $full_day_counts;
                        break;
                        
                    case 'last-week': 
                        $days = 7;
                        
                    case 'last-month': 
                        if ($days == 0) $days = 30;
                        
                    case '3-months': 
                        if ($days == 0) $days = 90;
                        
                    case '6-months': 
                        if ($days == 0) $days = 180;
                        
                    case '12-months': 
                        if ($days == 0) $days = 365;
                        
                        for ($i = $days; $i >= 0; $i--)
                        {
                            $k = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - $i,   date("Y")));
                            $data[$k] = 0;
                        }
                        
                        $file_counter = 0;
            
                        foreach (glob($logs."/*.log") as $filename) 
                        {
                            $date = trim(substr(basename($filename), 0, -4));
                            if (isset($data[$date]))
                            {
                                $data[$date] = filesize($filename);
                                $file_counter++;
                            }
                        } 
                }

                
  
                
                ?>
                
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                
                <h2><?php _e('Statistic of blocked sessions ('.ucwords(str_replace('-', ' ', $current_period)).')', 'wp-advanced-math-captcha'); ?></h2>
                <canvas id="mathcanvas"></canvas>
                
                
                <script>
    		var barChartData = {
    			labels: [<?php echo "'".implode("','", array_keys($data))."'"; ?>],
    			datasets: [{
    				label: 'Blocked by MathCaptcha',
    				backgroundColor: 'rgba(202, 34, 71, 0.8)',
    				borderColor: 'rgba(202, 34, 71, 0.8)',
    				borderWidth: 1,
    				data: [<?php echo implode(",", $data); ?>]
    			}]
    
    		};
    
    		window.onload = function() {
    			var ctx = document.getElementById('mathcanvas').getContext('2d');
    			window.myBar = new Chart(ctx, {
    				type: 'bar',
    				data: barChartData,
    				options: {
    					responsive: true,
    					legend: {
    						position: 'top',
    					},
    					title: {
    						display: false,
    						text: 'Statistic of blocked sessions (last 30 days)'
    					}
    				}
    			});
    
    		};
                </script>
                <?php
                
                
                // Prepare dates
                $logDates = [];
                $current_date = new DateTime(); // Current date
            
                switch ($current_period) 
                {
                    case 'today':
                        $logDates[] = $current_date->format('Y-m-d'); // Add only today’s date
                        break;
            
                    case 'yesterday':
                        $current_date->sub(new DateInterval('P1D')); // Subtract 1 day
                        $logDates[] = $current_date->format('Y-m-d'); // Add yesterday’s date
                        break;
            
                    case 'last-week':
                        for ($i = 0; $i < 7; $i++) { // 7 days for last week
                            $date = clone $current_date;
                            $date->sub(new DateInterval('P' . $i . 'D')); // Subtract i days
                            $logDates[] = $date->format('Y-m-d');
                        }
                        break;
            
                    case 'last-month':
                        for ($i = 0; $i < 30; $i++) { // Approximate 30 days for last month
                            $date = clone $current_date;
                            $date->sub(new DateInterval('P' . $i . 'D')); // Subtract i days
                            $logDates[] = $date->format('Y-m-d');
                        }
                        break;
            
                    case '3-months':
                        for ($i = 0; $i < 90; $i++) { // Approximate 90 days for 3 months
                            $date = clone $current_date;
                            $date->sub(new DateInterval('P' . $i . 'D')); // Subtract i days
                            $logDates[] = $date->format('Y-m-d');
                        }
                        break;
            
                    case '6-months':
                        for ($i = 0; $i < 180; $i++) { // Approximate 180 days for 6 months
                            $date = clone $current_date;
                            $date->sub(new DateInterval('P' . $i . 'D')); // Subtract i days
                            $logDates[] = $date->format('Y-m-d');
                        }
                        break;
            
                    case '12-months':
                        for ($i = 0; $i < 365; $i++) { // Approximate 365 days for 12 months
                            $date = clone $current_date;
                            $date->sub(new DateInterval('P' . $i . 'D')); // Subtract i days
                            $logDates[] = $date->format('Y-m-d');
                        }
                        break;
                }
                
                //print_r($logDates);



                
                
                // GEO countries
                
                $geo = new MathCaptcha_GEO();
                
                $country_counts = [];
                $IP_counts = [];
                
                foreach ($logDates as $logFile_date)
                {
                    // Path to the log file (specify the real path)
                    $log_file = $logs . '/sessions/'.$logFile_date.'.log'; // Example path, replace with actual
                    
                    // Check if file exists
                    if (!file_exists($log_file)) {
                        continue; // skip date
                    }
                    
                    // Read file into array of lines using file()
                    $log_lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
                    if ($log_lines !== false) 
                    {
                        // Process each line in the log
                        foreach ($log_lines as $line) 
                        {
                            // Split line by | delimiter
                            $parts = explode('|', $line);
                            
                            if (count($parts) !== 3) {
                                continue; // Skip invalid lines
                            }
                        
                            // Extract country from the third part
                            $country = trim($parts[2]);
                            $IPaddr = trim($parts[1]);
                            
                            // Increment country count
                            if (!isset($country_counts[$country])) $country_counts[$country] = 0;
                            $country_counts[$country]++;
                            
                            // Increment IP address count
                            if (!isset($IP_counts[$IPaddr])) $IP_counts[$IPaddr] = 0;
                            $IP_counts[$IPaddr]++;
                        }
                    }
                    
                    arsort($country_counts);
                    arsort($IP_counts);
                    
                    //print_r($country_counts);
                }
                
                ?>
                <?php
                // https://developers.google.com/chart/interactive/docs/gallery/geochart
                ?>
                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                <script type="text/javascript">
                  google.charts.load('current', {
                    'packages':['geochart'],
                  });
                  google.charts.setOnLoadCallback(drawRegionsMap);
            
                  function drawRegionsMap() {
                    var data = google.visualization.arrayToDataTable([
                    
                        ['Country', 'Blocked by MathCaptcha'],
                    
                        <?php
                        foreach ($country_counts as $country_code => $country_count)
                        {
                            echo '["'.$country_code.'", '.$country_count.'],'."\n";
                        }
                        // $geo->getNameByCountryCode($country_code)
                        ?>
                    ]);
            
                    var options = {};
            
                    var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
            
                    chart.draw(data, options);
                  }
                </script>
            
            <h2><?php _e('Country Blocking Map', 'math-captcha'); ?></h2>
            
            <div id="regions_div" style="width: 900px; height: 500px;"></div>

            <?php
            $TOP_countries_counter = 15;
            ?>
            <h2><?php _e('Country Blocking Statistics (TOP '.$TOP_countries_counter.')', 'math-captcha'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-country" style="width: 60%;"><?php _e('Country', 'math-captcha'); ?></th>
                        <th scope="col" class="manage-column column-count" style="width: 40%;"><?php _e('Block Count', 'math-captcha'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $top_countries = array_slice($country_counts, 0, $TOP_countries_counter, true);
                    
                    foreach ($top_countries as $country_code => $count) : ?>
                        <tr>
                            <td><?php echo esc_html($geo->getNameByCountryCode($country_code).' ('.$country_code.')'); ?></td>
                            <td><?php echo esc_html(number_format($count)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            
            <?php
            $TOP_IP_counter = 15;
            ?>
            <h2><?php _e('IP Address Blocking Statistics (TOP '.$TOP_IP_counter.')', 'math-captcha'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-country" style="width: 60%;"><?php _e('IP Address', 'math-captcha'); ?></th>
                        <th scope="col" class="manage-column column-count" style="width: 40%;"><?php _e('Block Count', 'math-captcha'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $top_IP_counts = array_slice($IP_counts, 0, $TOP_IP_counter, true);
                    
                    foreach ($top_IP_counts as $IPaddr => $count) : ?>
                        <tr>
                            <td><?php echo esc_html($IPaddr); ?></td>
                            <td><?php echo esc_html(number_format($count)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                
                
                <?php
       
            }
    }
    
    
	public function mc_tab_support() 
    {
        ?>
        
        
        
        <?php
    }
    
    
    
public function mc_general_enable_captcha_for($hidden = false)
    {

        if (!defined('MATH_PLGLIC')) define('MATH_PLGLIC', Math_Captcha_Core::isPRO());

        echo '
    <div id="mc_general_enable_captcha_for" class="' . ($hidden ? 'hidden' : '') . '">
      <fieldset>';

        foreach ($this->forms as $val => $trans)
        {

            $labels = array();
            $is_disabled = false;
            $is_available = true;
            
            switch ($val) {
                case 'contact_form_7':
                    $is_disabled = !class_exists('WPCF7_ContactForm');
                    break;
            
                case 'bbpress':
                    $is_disabled = !class_exists('bbpress');
                    break;
            
                case 'woocommerce_login':
                    if (!function_exists('is_plugin_active')) {
                        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    }
                    $is_disabled = !is_plugin_active('woocommerce/woocommerce.php');
                    break;
            
                case 'woocommerce_register':
                case 'woocommerce_reset':
                case 'woocommerce_checkout':
                    if (!function_exists('is_plugin_active')) {
                        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    }
                    $is_disabled = !is_plugin_active('woocommerce/woocommerce.php');
                    
                    if (!defined('MATH_PLGLIC') || !MATH_PLGLIC) $is_available = false;
                    break;
					
				case 'wpforms':
					if (!function_exists('is_plugin_active')) {
                        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    }
					
					$is_disabled = !is_plugin_active('wpforms-lite/wpforms.php');
					
					//if (!defined('MATH_PLGLIC') || !MATH_PLGLIC) $is_available = false;
				    break;

                case 'formidable_forms':
                    if (!function_exists('is_plugin_active')) {
                        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
                    }
                    $is_disabled = !is_plugin_active('formidable/formidable.php');
                    break;
            }
            
            
            if ($is_disabled) {
                $labels[] = esc_html('Not Installed or active');
            }
            
            if (!$is_available) {
                $labels[] = esc_html('*PRO version*');
                $is_disabled = true;
            }
            
            $label = !empty($labels) ? " <b>(" . implode(" / ", $labels) . ")</b>" : '';

            
            echo '
        <input id="mc-general-enable-captcha-for-' . $val . '" type="checkbox" name="math_captcha_options[enable_for][]" value="' . $val . '" ' . checked(true, Math_Captcha()->options['general']['enable_for'][$val], false) . ' ' . disabled($is_disabled, true, false) . '/><label for="mc-general-enable-captcha-for-' . $val . '">' . esc_html($trans) . $label . '</label>' . "<br>";
        }

        echo '
        <br/>
        <span class="description">' . __('Select where you\'d like to use Math Captcha.', 'math-captcha') . '</span>
      </fieldset>
    </div>';
    }

	public function mc_general_hide_for_logged_users($hidden = false) {
		echo '
		<div id="mc_general_hide_for_logged_users" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-hide-for-logged" type="checkbox" name="math_captcha_options[hide_for_logged_users]" ' . checked( true, Math_Captcha()->options['general']['hide_for_logged_users'], false ) . '/><label for="mc-general-hide-for-logged">' . __( 'Enable to hide captcha for logged in users.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Would you like to hide captcha for logged in users?', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_mathematical_operations($hidden = false) {
		echo '
		<div id="mc_general_mathematical_operations" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>';

		foreach ( $this->mathematical_operations as $val => $trans ) {
			echo '
				<input id="mc-general-mathematical-operations-' . $val . '" type="checkbox" name="math_captcha_options[mathematical_operations][]" value="' . $val . '" ' . checked( true, Math_Captcha()->options['general']['mathematical_operations'][$val], false ) . '/><label for="mc-general-mathematical-operations-' . $val . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<br/>
				<span class="description">' . __( 'Select which mathematical operations to use in your captcha.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_groups($hidden = false) {
		echo '
		<div id="mc_general_groups" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>';

		foreach ( $this->groups as $val => $trans ) {
			echo '
				<input id="mc-general-groups-' . $val . '" type="checkbox" name="math_captcha_options[groups][]" value="' . $val . '" ' . checked( true, Math_Captcha()->options['general']['groups'][$val], false ) . '/><label for="mc-general-groups-' . $val . '">' . esc_html( $trans ) . '</label>';
		}

		echo '
				<br/>
				<span class="description">' . __( 'Select how you\'d like to display you captcha.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_title($hidden = false) {
		echo '
		<div id="mc_general_title" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input type="text" name="math_captcha_options[title]" value="' . Math_Captcha()->options['general']['title'] . '"/>
				<br/>
				<span class="description">' . __( 'How to entitle field with captcha?', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_time($hidden = false) {
		echo '
		<div id="mc_general_time" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input type="text" name="math_captcha_options[time]" value="' . Math_Captcha()->options['general']['time'] . '"/>
				<br/>
				<span class="description">' . __( 'Enter the time (in seconds) a user has to enter captcha value.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
    
	public function mc_general_geo_database($hidden = false) 
    {
        $geo_database = plugin_dir_path(__FILE__).'geo.mmdb';
        $is_expired = false;
        
        if (file_exists($geo_database)) 
        {
            $geo_filectime = filectime($geo_database);
            $geo_date = date("Y-m-d", $geo_filectime);
            if ($geo_filectime < strtotime('-60 days', time())) $is_expired = true;
        }
        else {
            $geo_filectime = 0;
            $geo_date = 'Invalid';
            $is_expired = true;
        }
        
        if ($is_expired) $geo_date .= ' <span class="gpd-error-label">Outdated</span><br><a href="?page=math-captcha&tab=support" class="button button-primary">Update</a>';
        else $geo_date .= ' <span class="gpd-success-label">Valid</span>';
        
		echo '
		<div id="mc_general_time" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				Updated: '.$geo_date.'
				<br/>
				<span class="description">' . __( 'Keep your GEO database updated to get more accurate results.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_enable_ip_auto_block($hidden = false) {
		echo '
		<div id="mc_general_enable_ip_auto_block" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' id="mc-general-enable-ip-auto-block" type="checkbox" name="math_captcha_options[enable_ip_auto_block]" ' . checked( true, Math_Captcha()->options['general']['enable_ip_auto_block'], false ) . '/><label for="mc-general-enable-ip-auto-block">' . __( 'Enable if for Maximum Number of Attempts & Lockout Period rules', 'math-captcha' ) . '</label>
			</fieldset>
		</div>';
    }

	public function mc_general_max_number_attempts($hidden = false) {
		echo '
		<div id="mc_general_max_number_attempts" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' type="text" name="math_captcha_options[max_number_attempts]" value="' . Math_Captcha()->options['general']['max_number_attempts'] . '"/>
				<br/>
				<span class="description">' . __( 'Sets the maximum number of incorrect captcha attempts allowed from a single IP address before restrictions are applied. Prevents bots or malicious users from repeatedly guessing the captcha answer by limiting their tries (e.g., 5 attempts). Once the limit is reached, further attempts can be blocked or delayed.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
	public function mc_general_lockout_period($hidden = false) {
		echo '
		<div id="mc_general_lockout_period" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' type="text" name="math_captcha_options[lockout_period]" value="' . Math_Captcha()->options['general']['lockout_period'] . '"/>
				<br/>
				<span class="description">' . __( 'Defines the duration (in minutes) that an IP address is blocked or restricted after exceeding the maximum number of attempts. Temporarily locks out the user or bot after failed attempts, stopping spam or brute-force attacks for a set time (e.g., 10 minutes). After this period, attempts are allowed again.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
    

	public function mc_general_block_enable_ip_rules($hidden = false) {
		echo '
		<div id="mc_general_block_enable_ip_rules" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-block-ip-rules" type="checkbox" name="math_captcha_options[block_ip_rules]" ' . checked( true, Math_Captcha()->options['general']['block_ip_rules'], false ) . '/><label for="mc-general-block-ip-rules">' . __( 'Enable Restriction IP rules', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'IP rules to restrict to pass captcha for specific IP addresses (or range of IP addresses). These IP addresses will never pass the captcha verification (good against bots and active spam users)', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
        $ip_rules_list = '';
        if (isset(Math_Captcha()->options['general']['block_ip_rules_list']) && !empty(Math_Captcha()->options['general']['block_ip_rules_list'])) $block_ip_rules_list = implode("\n", Math_Captcha()->options['general']['block_ip_rules_list']);
        
		echo '
		<div id="mc_general_block_ip_rules_list" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
                <span class="description"><b>' . __( 'Add IP addresses and these IP addresses will never pass the captcha verification (one per row)', 'math-captcha' ) . '</b></span>
                    <textarea name="math_captcha_options[block_ip_rules_list]" rows="5" cols="50">'.$block_ip_rules_list.'</textarea>  
                <span class="description"><b>' . __( 'e.g. 1.1.1.1 or 1.1.1.* or 1.1.1.0/24', 'math-captcha' ) . '</b></span>
			</fieldset>
		</div>';
	}
    
    

	public function mc_general_block_geo_captcha($hidden = false) {
		echo '
		<div id="mc_general_block_geo_captcha" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' id="mc-general-block-geo-captcha-rules" type="checkbox" name="math_captcha_options[block_geo_captcha_rules]" ' . checked( true, Math_Captcha()->options['general']['block_geo_captcha_rules'], false ) . '/><label for="mc-general-block-geo-captcha-rules">' . __( 'Block countries to pass captcha verification.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Enable this if you to block all visitors from e.g. Russia.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
		echo '
		<div id="mc_general_block_for_countries" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
                <span class="description"><b>' . __( 'Select countries you want to block', 'math-captcha' ) . '</b></span>
                ';

        $geo = new MathCaptcha_GEO();
        $countries = $geo->getCountryMapList();
		foreach ( $countries as $country_code => $country_name ) 
        {
            if ($country_code == 'A1' || $country_code == 'A2' || $country_code == 'O1') continue;

            $is_checked = !empty( Math_Captcha()->options['general']['block_for_countries'][$country_code] );

			echo '
				<input '.(MATH_PLGLIC ? '' : 'disabled').' id="mc-general-block-for-countries-' . $country_code . '" type="checkbox" name="math_captcha_options[block_for_countries][]" value="' . $country_code . '" ' . checked( true, $is_checked, false ) . '/><label for="mc-general-block-for-countries-' . $country_code . '">' . esc_html( $country_name ) . '</label>'."<br>";
		}

		echo '
			</fieldset>
		</div>';
	}

    
    

	public function mc_general_enable_ip_rules($hidden = false) {
		echo '
		<div id="mc_general_enable_ip_rules" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-ip-rules" type="checkbox" name="math_captcha_options[ip_rules]" ' . checked( true, Math_Captcha()->options['general']['ip_rules'], false ) . '/><label for="mc-general-ip-rules">' . __( 'Enable IP rules', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'IP rules allows to hide captcha for specific IP addresses (or range of IP addresses)', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
        $ip_rules_list = '';
        if (isset(Math_Captcha()->options['general']['ip_rules_list']) && !empty(Math_Captcha()->options['general']['ip_rules_list'])) $ip_rules_list = implode("\n", Math_Captcha()->options['general']['ip_rules_list']);
        
		echo '
		<div id="mc_general_hide_for_countries" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
                <span class="description"><b>' . __( 'Add IP addresses where captcha will be disabled (one per row)', 'math-captcha' ) . '</b></span>
                    <textarea name="math_captcha_options[ip_rules_list]" rows="5" cols="50">'.$ip_rules_list.'</textarea>  
                <span class="description"><b>' . __( 'e.g. 1.1.1.1 or 1.1.1.* or 1.1.1.0/24', 'math-captcha' ) . '</b></span>
			</fieldset>
		</div>';
	}
    

	public function mc_general_geo_captcha($hidden = false) {
		echo '
		<div id="mc_general_geo_captcha" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-geo-captcha-rules" type="checkbox" name="math_captcha_options[geo_captcha_rules]" ' . checked( true, Math_Captcha()->options['general']['geo_captcha_rules'], false ) . '/><label for="mc-general-geo-captcha-rules">' . __( 'Hide captcha for specific countries', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Enable this if you need to show the captcha for all visitors, except e.g. USA, Canada visitors.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
        
        echo '<br>';
        
		echo '
		<div id="mc_general_hide_for_countries" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
                <span class="description"><b>' . __( 'Select countries where captcha will be disabled.', 'math-captcha' ) . '</b></span>
                ';

        $geo = new MathCaptcha_GEO();
        $countries = $geo->getCountryMapList();
		foreach ( $countries as $country_code => $country_name ) 
        {
            if ($country_code == 'A1' || $country_code == 'A2' || $country_code == 'O1') continue;
            
			echo '
				<input id="mc-general-hide-for-countries-' . $country_code . '" type="checkbox" name="math_captcha_options[hide_for_countries][]" value="' . $country_code . '" ' . checked( true, Math_Captcha()->options['general']['hide_for_countries'][$country_code], false ) . '/><label for="mc-general-hide-for-countries-' . $country_code . '">' . esc_html( $country_name ) . '</label>'."<br>";
		}

		echo '
			</fieldset>
		</div>';
	}
    

    
    
	public function mc_general_block_direct_comments($hidden = false) {
		echo '
		<div id="mc_general_block_direct_comments" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-block-direct-comments" type="checkbox" name="math_captcha_options[block_direct_comments]" ' . checked( true, Math_Captcha()->options['general']['block_direct_comments'], false ) . '/><label for="mc-general-block-direct-comments">' . __( 'Block direct access to wp-comments-post.php', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Enable this to prevent spambots from posting to Wordpress via a URL.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}

	public function mc_general_deactivation_delete($hidden = false) {
		echo '
		<div id="mc_general_deactivation_delete" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-deactivation-delete" type="checkbox" name="math_captcha_options[deactivation_delete]" ' . checked( true, Math_Captcha()->options['general']['deactivation_delete'], false ) . '/><label for="mc-general-deactivation-delete">' . __( 'Delete settings on plugin deactivation.', 'math-captcha' ) . '</label>
				<br/>
				<span class="description">' . __( 'Delete settings on plugin deactivation', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
	public function mc_general_show_powered_by($hidden = false) {
		echo '
		<div id="mc_general_show_powered_by" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' id="mc-general-show-powered-by" type="checkbox" name="math_captcha_options[show_powered_by]" ' . checked( true, Math_Captcha()->options['general']['show_powered_by'], false ) . '/><label for="mc-general-show-powered-by">' . __( 'Show Powered by under MathCaptcha block', 'math-captcha' ) . '</label>
			</fieldset>
		</div>';
	}
    
	public function mc_general_geo_db_autoupdate($hidden = false) {
		echo '
		<div id="mc_general_geo_db_autoupdate" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input '.(MATH_PLGLIC ? '' : 'disabled').' id="mc-general-geo-db-autoupdate" type="checkbox" name="math_captcha_options[geo_db_autoupdate]" ' . checked( true, Math_Captcha()->options['general']['geo_db_autoupdate'], false ) . '/><label for="mc-general-geo-db-autoupdate">' . __( 'Automatically update GEO database', 'math-captcha' ) . '</label>
                <br/>
				<span class="description">' . __( 'Keep your GEO database updated to get more accurate results.', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
	public function mc_general_collect_logs($hidden = false) {
		echo '
		<div id="mc_general_collect_logs" class="'.($hidden ? 'hidden' : '').'">
			<fieldset>
				<input id="mc-general-collect-logs" type="checkbox" name="math_captcha_options[collect_logs]" ' . checked( true, Math_Captcha()->options['general']['collect_logs'], false ) . '/><label for="mc-general-collect-logs">' . __( 'Save all logs related to failed actions', 'math-captcha' ) . '</label>
				<br/>
                <span class="description">' . __( 'Enable this option to see charts and statistic information', 'math-captcha' ) . '</span>
			</fieldset>
		</div>';
	}
    
    
	public function mc_general_pro_support($hidden = false) 
    {
        $domain = Math_Captcha_Core::PrepareDomain(get_site_url());
        $key = Math_Captcha_Core::generateUniqueKey($domain);
        
        // GEO database
        $geo_database = plugin_dir_path(__FILE__).'geo.mmdb';
        $is_expired = false;
        
        if (file_exists($geo_database)) 
        {
            $geo_filectime = filectime($geo_database);
            $geo_date = date("Y-m-d", $geo_filectime);
            if ($geo_filectime < strtotime('-60 days', time())) $is_expired = true;
        }
        else {
            $geo_filectime = 0;
            $geo_date = 'Invalid';
            $is_expired = true;
        }
        
        if ($is_expired) $geo_date .= ' <span class="gpd-error-label">Outdated</span><br><a onclick="jQuery(\'#math_geo_blk\').hide();jQuery(\'#math-loader\').show();" href="?page=math-captcha&tab=support&action=download-geo" class="button button-primary">Manual Update</a>';
        else $geo_date .= ' <span class="gpd-success-label">Valid</span>';
        
        
        // Version type
        
        if (MATH_PLGLIC) $plg_version = '<span class="gpd-success-label">PRO version</span>';
        else {
            $user = wp_get_current_user();
            $email = isset($user->user_email) ? $user->user_email : '';
            $plg_version = 'Free<br><a target="_blank" href="https://www.cmsplughub.com/order?id=wp-advanced-math-captcha&website_url='.Math_Captcha_Core::PrepareDomain(get_site_url()).'&email='.$email.'" class="button button-primary">Get PRO version</a>&nbsp;<a href="?page=math-captcha&tab=support&action=restore-purchase" class="button button-primary">Restore Purchase</a><br><span class="description">If you\'ve already acquired a paid license, please click the \'Restore Purchase\' button to activate it</span>';
        }
        
		echo '
		<div id="mc_general_pro_support" class="'.($hidden ? 'hidden' : '').'">';
        
        ?>
        <p>Plugin version: <?php echo $plg_version; ?></p><br>
        <p id="math_geo_blk">GEO database: <?php echo $geo_date; ?></p><br>
        
<div id="math-loader" style="display: none;">
    <svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="40" fill="none" stroke="#e0e0e0" stroke-width="5"/>
        <circle cx="50" cy="50" r="30" fill="none" stroke="#007bff" stroke-width="5" stroke-linecap="round">
            <animate attributeName="r" values="25;30;25" dur="1.5s" repeatCount="indefinite"/>
            <animate attributeName="opacity" values="1;0.5;1" dur="1.5s" repeatCount="indefinite"/>
        </circle>
        <circle cx="50" cy="50" r="20" fill="none" stroke="#28a745" stroke-width="5" stroke-linecap="round">
            <animate attributeName="r" values="15;20;15" dur="1.5s" begin="0.5s" repeatCount="indefinite"/>
            <animate attributeName="opacity" values="1;0.5;1" dur="1.5s" begin="0.5s" repeatCount="indefinite"/>
        </circle>
    </svg>
</div>
        
        <p>KEY: <b><?php echo $key; ?></b></p><br>
        
        <?php
        
        echo '
            <script>
            function OpenChat()
            {
                var session = "'.md5(time().'-'.rand(1, 10000).'-'.$_SERVER["REMOTE_ADDR"]).'";
                var screen_size = window.screen.availWidth + "x" + window.screen.availHeight;
                var mydate = new Date();
                var h = ("0" + mydate.getHours()).slice(-2);
                var m = ("0" + mydate.getMinutes()).slice(-2);
                
                var url = "https://livechat.cmsplughub.com/chat.php?session="+session+"&s="+screen_size;
                
                var chat_h = window.screen.height - 150;
                if (chat_h > 730) chat_h = 730;
                
                var chat_w = window.screen.width;
                if (chat_w > 575) chat_w = 575;
                
                window.open (url, "PlugHub Support","menubar=1,location=no,toolbar=no,scrollbars=0,resizable=1,left=50,top=50,width="+chat_w+",height="+chat_h);
            }
            </script>
            
             
              <p><a href="javascript:;" onclick="OpenChat();">
                <img width="50" src="'.plugins_url('images/', dirname(__FILE__)).'chat.svg'.'" />
              </a></p>
		</div>';
	}
    

	/**
	 * Validate settings.
	 * 
	 * @param array $input
	 * @return array
	 */
	public function validate_settings( $input ) {
		if ( isset( $_POST['save_mc_general'] ) ) {
			// enable captcha forms
			$enable_for = array();

			if ( empty( $input['enable_for'] ) ) {
				foreach ( Math_Captcha()->defaults['general']['enable_for'] as $enable => $bool ) {
					$input['enable_for'][$enable] = false;
				}
			} else {
				foreach ( $this->forms as $enable => $trans ) {
					$enable_for[$enable] = (in_array( $enable, $input['enable_for'] ) ? true : false);
				}

				$input['enable_for'] = $enable_for;
			}

			if ( ! class_exists( 'WPCF7_ContactForm' ) && Math_Captcha()->options['general']['enable_for']['contact_form_7'] )
				$input['enable_for']['contact_form_7'] = true;

			if ( ! class_exists( 'bbPress' ) && Math_Captcha()->options['general']['enable_for']['bbpress'] )
				$input['enable_for']['bbpress'] = true;

			// enable mathematical operations
			$mathematical_operations = array();

			if ( empty( $input['mathematical_operations'] ) ) {
				add_settings_error( 'empty-operations', 'settings_updated', __( 'You need to check at least one mathematical operation. Defaults settings of this option restored.', 'math-captcha' ), 'error' );

				$input['mathematical_operations'] = Math_Captcha()->defaults['general']['mathematical_operations'];
			} else {
				foreach ( $this->mathematical_operations as $operation => $trans ) {
					$mathematical_operations[$operation] = (in_array( $operation, $input['mathematical_operations'] ) ? true : false);
				}

				$input['mathematical_operations'] = $mathematical_operations;
			}

			// enable groups
			$groups = array();

			if ( empty( $input['groups'] ) ) {
				add_settings_error( 'empty-groups', 'settings_updated', __( 'You need to check at least one group. Defaults settings of this option restored.', 'math-captcha' ), 'error' );

				$input['groups'] = Math_Captcha()->defaults['general']['groups'];
			} else {
				foreach ( $this->groups as $group => $trans ) {
					$groups[$group] = (in_array( $group, $input['groups'] ) ? true : false);
				}

				$input['groups'] = $groups;
			}

			// hide for logged in users
			$input['hide_for_logged_users'] = isset( $input['hide_for_logged_users'] );

			// block direct comments access
			$input['block_direct_comments'] = isset( $input['block_direct_comments'] );
            
            
			// IP rules
			$input['ip_rules'] = isset( $input['ip_rules'] );
			$input['ip_rules_list'] = trim( $input['ip_rules_list'] );
            if ($input['ip_rules_list'] != '') $input['ip_rules_list'] = explode("\n", $input['ip_rules_list']); 
            
			// geo captcha rules
			$input['geo_captcha_rules'] = isset( $input['geo_captcha_rules'] );
            
            // math_captcha_options[hide_for_countries]
			$hide_for_countries = array();
			//$mathematical_operations = array();
            
			if ( empty( $input['hide_for_countries'] ) ) {
	
			} else {

				foreach ( $input['hide_for_countries'] as $country_code ) {
					$hide_for_countries[$country_code] = (in_array( $country_code, $input['hide_for_countries'] ) ? true : false);
				}

				$input['hide_for_countries'] = $hide_for_countries;
			}
            
            
            // limits
            $input['block_ip_rules'] = isset( $input['block_ip_rules'] );
			$input['block_ip_rules_list'] = trim( $input['block_ip_rules_list'] );
            if ($input['block_ip_rules_list'] != '') $input['block_ip_rules_list'] = explode("\n", $input['block_ip_rules_list']); 
            $input['enable_ip_auto_block'] = isset( $input['enable_ip_auto_block'] );
            $input['max_number_attempts'] = (int) $input['max_number_attempts'];
            if ($input['max_number_attempts'] == 0) $input['max_number_attempts'] = 3;
            $input['lockout_period'] = (int) $input['lockout_period'];
            if ($input['lockout_period'] == 0) $input['lockout_period'] = 10;
            $input['block_geo_captcha_rules'] = isset( $input['block_geo_captcha_rules'] );
			$block_for_countries = array();
            if ( empty( $input['block_for_countries'] ) ) {
	
			} else {

				foreach ( $input['block_for_countries'] as $country_code ) {
					$block_for_countries[$country_code] = (in_array( $country_code, $input['block_for_countries'] ) ? true : false);
				}

				$input['block_for_countries'] = $block_for_countries;
			}


			// collect logs
			$input['collect_logs'] = isset( $input['collect_logs'] );
            

			// deactivation delete
			$input['deactivation_delete'] = isset( $input['deactivation_delete'] );
			$input['show_powered_by'] = isset( $input['show_powered_by'] );
			$input['geo_db_autoupdate'] = isset( $input['geo_db_autoupdate'] );

			// captcha title
			$input['title'] = trim( $input['title'] );

			// captcha time
			$time = (int) $input['time'];
			$input['time'] = ($time < 0 ? Math_Captcha()->defaults['general']['time'] : $time);

			// flush rules
			$input['flush_rules'] = true;
		} elseif ( isset( $_POST['reset_mc_general'] ) ) {
			$input = Math_Captcha()->defaults['general'];

			add_settings_error( 'settings', 'settings_reset', __( 'Settings restored to defaults.', 'math-captcha' ), 'updated' );
		}
        
        if (!defined('MATH_PLGLIC')) define( 'MATH_PLGLIC', Math_Captcha_Core::isPRO());
        if (!MATH_PLGLIC)
        {
            $input['enable_ip_auto_block'] = false;
            $input['max_number_attempts'] = 3;
            $input['lockout_period'] = 10;
            $input['block_geo_captcha_rules'] = false;
            $input['block_for_countries'] = array();
            $input['show_powered_by'] = true;
            $input['geo_db_autoupdate'] = false;
        }
        
        // Register cron for GEO autoupdate
        if ($input['geo_db_autoupdate']) 
        {
            if (!wp_next_scheduled('math_GEO_cron_event')) {
                wp_schedule_event(time(), 'monthly', 'math_GEO_cron_event');
            }
        }
        else {
            $timestamp = wp_next_scheduled('math_GEO_cron_event');
            if ($timestamp !== false) {
                wp_unschedule_event($timestamp, 'math_GEO_cron_event');
            }
        }


		return $input;
	}

}