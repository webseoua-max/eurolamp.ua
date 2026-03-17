<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Wt_Product_Feed_Licence_Manager
{
	public $module_id='';
	public $module_base='licence_manager';
	public $api_url='https://licensing.webtoffee.com/';
        public $my_account_url = '';
        public $main_plugin_slug='';
	public $tab_icons=array(
		'active'=>'<span class="dashicons dashicons-yes" style="color:#03da01; font-size:25px;"></span>',   
	    'inactive'=>'<span class="dashicons dashicons-warning" style="color:#ff1515; font-size:25px;"></span>'
	);

	public $products=array();

	public function __construct()
	{
		$this->module_id 			=Webtoffee_Product_Feed_Sync_Pro::get_module_id($this->module_base);
		$this->my_account_url		=$this->api_url.'my-account';
		$this->main_plugin_slug		='webtoffee-product-feed-pro';

		require_once plugin_dir_path(__FILE__).'classes/class-edd.php';		

		$this->products=array(
			$this->main_plugin_slug=>array(
				'product_id'			=>	WT_PF_ACTIVATION_ID,
				'product_edd_id'		=>	WT_PF_EDD_ACTIVATION_ID,
				'plugin_settings_url'	=>	admin_url('admin.php?page='.WEBTOFFEE_PRODUCT_FEED_PRO_ID.'#wt-licence'),
				'product_version'		=>	WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION,
				'product_name'			=>	WT_PF_ACTIVATION_ID,
				'product_slug'			=>	$this->main_plugin_slug,
				'product_display_name'	=>	'WebToffee WooCommerce Product Feed & Sync Manager(Pro)', 
			)
		);

		add_action('plugins_loaded', array($this, 'init'), 1);

		/**
		*	Add tab to settings section
		*/
		add_filter('wt_pf_plugin_settings_tabhead', array($this, 'licence_tabhead'));
		add_action('wt_pf_plugin_out_settings_pro_form', array($this, 'licence_content'));

		/**
		*	 Main Ajax hook to handle all ajax requests 
		*/
		add_action('wp_ajax_wt_pf_licence_manager_ajax', array($this, 'ajax_main'),11);

		/**
		*	 Check for plugin updates
		*/
		add_filter( 'pre_set_site_transient_update_plugins',array($this, 'update_check'));

		/** 
		*	Check For Plugin Information to display on the update details page
		*/
		add_filter('plugins_api', array($this, 'update_details'), 10, 3);
	}

	public function init()
	{
		/**
		*	Add products to licence manager
		*/
		$this->products=apply_filters('wt_pf_add_licence_manager', $this->products);		

		$this->check_for_licence_status_message();
	}

	/**
	*	Fetch the details of the new update.
	*	This will show in the plugins page as a popup
	*/
	public function update_details($false, $action, $args)
	{		
		if(!isset($args->slug))
		{
			return $false;
		}

		/**
		*	Get licence info
		*/
		$licence_data=$this->get_licence_data($args->slug);
	
		if(!$licence_data) /* no licence exists */
		{
			return $false;
		}

		/**
		*	Check product exists
		*/
		if(!isset($this->products[$args->slug]))
		{
			return $false;
		}

		/**
		*	Get product info
		*/
		$product_data=$this->products[$args->slug];

		return $this->get_license_type_obj($licence_data)->update_details($this, $product_data, $licence_data, $false, $action, $args);
	}


	/**
	* 	Check for plugin updates 
	*/
	public function update_check($transient)
	{
		if(empty( $transient->checked ))
		{
			return $transient;
		}

		$home_url=urlencode(home_url());

		/**
		*	Get all licence info
		*/
		$licence_data=$this->get_licence_data();

		/**
		*	Main product data
		*/
		$product_data=$this->products[$this->main_plugin_slug];

		/* This is for WC type licenese */
		/*
		include_once "classes/class-wt-response-error-messages.php";
		$error_message_obj=new wt_pf_licence_manager_error_messages($product_data['plugin_settings_url'], $product_data['product_display_name'], $this->my_account_url);
		 * 
		 */

		
		if(!function_exists('get_plugin_data')) /* this function is required for fetching current plugin version */
		{
		    require_once ABSPATH.'wp-admin/includes/plugin.php';
		}

		$timestamp=time(); //current timestamp
		foreach ($licence_data as $product_slug => $value)
		{
			if(  isset( $value['status'] ) &&  $value['status']=='active' && isset($this->products[$product_slug]))
			{
				$product_data=$this->products[$product_slug];

				/**
				*	Taking the last update check time
				*/
				$last_check=get_option($product_slug.'-last-update-check');
				if($last_check==false) //first time so add a four hour back time.
				{ 
					$last_check=$timestamp-14402;
					update_option($product_slug.'-last-update-check', $last_check);
				}

				/**
				* 	Previous check is before 4 hours or Force check
				*/
				if(($timestamp-$last_check)>14400 || (isset($_GET['force-check']) && $_GET['force-check']==1)) 
				{

					
						$args = array(
							'edd_action'		=> 	'get_version',
							'url' 				=> 	$home_url,
							
							/* product details */
							'item_id' 			=> 	WT_PF_EDD_ACTIVATION_ID,
							'license' 			=> 	$value['key'],
						);
					


					/* fetch plugin response */
					$response = $this->fetch_plugin_info($args);
					
					if(isset($response) && is_object($response) && $response!== false )
					{
						$plugin_slug=$product_slug;
						
							$transient=$this->add_update_availability($transient, $plugin_slug, $response);
						
					}

					/**
					*	Update last check time with current time
					*/
					update_option($product_slug.'-last-update-check', $timestamp);
				}			
			}
		}
		return $transient;
	}

	public function check_for_licence_status_message()
	{
		global $pagenow;
		if($pagenow!='plugins.php')
		{
			return;
		}
		
		/**
		*	Get all licence info
		*/
		$licence_data=$this->get_licence_data();

		$require_js_block=false;	
		foreach ($this->products as $product_slug => $product)
		{
			$status=true;
			if(!isset($licence_data[$product_slug]))
			{
				$status=false;
			}else
			{
				if(isset($licence_data[$product_slug]['status']))
				{
					if($licence_data[$product_slug]['status']=='' || $licence_data[$product_slug]['status']=='inactive')
					{
						$status=false;
					}					
				}else
				{
					$status=false;
				}
			}
			if(!$status)
			{
				$require_js_block=true;
				add_action('after_plugin_row_'.("{$product_slug}/{$product_slug}.php"), array($this, 'add_licence_status_message'), 10, 3);
			}
		}
		if($require_js_block)
		{
			add_action('admin_footer', array($this, 'plugins_page_scripts'));
		}
	}

	public function plugins_page_scripts()
	{
		?>
		<style type="text/css">
			.wt-sc-plugin-notice-tr p:before{ content: "\f534"; }
		</style>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.wt-sc-plugin-notice-tr').each(function(){
					if(jQuery(this).prev().addClass('update').hasClass('active'))
					{
						jQuery(this).addClass('active');
					}
				});
			});
		</script>
		<?php
	}

	public function add_licence_status_message($plugin_file, $plugin_data, $status)
	{
		?>
		<tr class="plugin-update-tr installer-plugin-update-tr wt-sc-plugin-notice-tr">
            <td colspan="4" class="plugin-update colspanchange">
                <div class="update-message notice inline">
                    <p><?php echo sprintf( __( 'The plugin license is not activated. You will not receive compatibility and security updates if the plugin license is not activated. %sActivate now%s' ), '<a href="'.esc_attr(admin_url('admin.php?page='.WEBTOFFEE_PRODUCT_FEED_PRO_ID)).'#wt-licence" target="_blank">', '</a>');?></p>
                </div>
            </td>
        </tr>
        <?php
	}

	/**
	*	Add plugin update availability to transient 
	*/
	public function add_update_availability($transient, $plugin_slug, $response)
	{
		$plugin_base_path="$plugin_slug/$plugin_slug.php";
		if(is_plugin_active($plugin_base_path)) /* checks the plugin is active */
		{
			$current_plugin_data=get_plugin_data(WP_PLUGIN_DIR."/$plugin_base_path");
			$current_version=$current_plugin_data['Version'];
			$new_version=$response->new_version;
			if(version_compare($new_version, $current_version, '>')) /* new version available */
			{
				$obj 									= new stdClass();
				$obj->slug 								= $plugin_slug;
				$obj->plugin 							= $plugin_base_path;
				$obj->new_version 						= $new_version;
				$obj->url 								= (isset($response->url) ? $response->url : '');
				$obj->package 							= (isset($response->package) ? $response->package : '');
				$obj->icons 							= (isset($response->icons) ? maybe_unserialize($response->icons) : array());
				$transient->response[$plugin_base_path] = $obj;
			}
		}

		return $transient;
	}

	/**
	*	Fetch plugin info for update check and update info
	*/
	public function fetch_plugin_info($args)
	{
		$request=$this->remote_get($args);

		if(is_wp_error($request) || wp_remote_retrieve_response_code($request)!=200)
		{
			return false;
		}


		$response=json_decode(wp_remote_retrieve_body($request));
		
				
		if(is_object($response))
		{
			return $response;
		}else
		{
			return false;
		}
	}

	/**
	* Main Ajax hook to handle all ajax requests. 
	*/
	public function ajax_main()
	{
		$allowed_actions=array('activate', 'deactivate', 'delete', 'licence_list', 'check_status');
		$action=(isset($_POST['wt_pf_licence_manager_action']) ? sanitize_text_field($_POST['wt_pf_licence_manager_action']) : '');
		$out=array('status'=>true, 'msg'=>'');
		if(!Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_PRO_ID))
		{
			$out['status']=false;

		}else
		{
			if(in_array($action,$allowed_actions))
			{
				if(method_exists($this,$action))
				{
					$out=$this->{$action}($out);
				}
			}
		}
		echo json_encode($out);
		exit();	
	}

	/**
	*	Ajax sub function to check licence status
	*/
	public function check_status($out)
	{
		$licence_data_arr=$this->get_licence_data('webtoffee-product-feed-pro');

		foreach($licence_data_arr as $product_slug => $licence_data)
		{

			if(isset($this->products[$product_slug]) && isset($licence_data['key'])) /* product currently exists */
			{
				$product_data=$this->products[$product_slug];

				$response=$this->fetch_status($product_data, $licence_data);

				$response_arr=json_decode($response, true);
						
				
				$new_status=$this->get_license_type_obj($licence_data)->check_status($licence_data, $response_arr);

				/* check update needed */
				if($licence_data['status']!=$new_status)
				{
					$licence_data['status']=$new_status;
					$this->update_licence_data($product_slug, $licence_data);
				}
			}
		}

		$out['status']=true;
		return $out;		
	}

	/**
	*	Fetch licence status
	*/
	public function fetch_status($product_data, $licence_data)
	{		
                $args = array(
                        'edd_action' 	=> 'check_license',
                        'license'		=> $licence_data['key'], 
                        'item_id' 		=> WT_PF_EDD_ACTIVATION_ID,
                        'url' 			=> urlencode(home_url()),
                );
		
		$request=$this->remote_get($args);
		
		$response = wp_remote_retrieve_body($request);

		return $response;
	}

	/**
	*	Ajax sub function to delete licence
	*/
	public function delete($out)
	{
		$out['status']=false;
		$er=0;

		$licence_product=trim(isset($_POST['wt_pf_licence_product']) ? sanitize_text_field($_POST['wt_pf_licence_product']) : '');
		if($licence_product=="")
		{
			$er=1;
			$out['msg']=__('Error !!!', 'webtoffee-product-feed-pro');
		}else
		{
			if(!isset($this->products[$licence_product]))
			{
				$er=1;
				$out['msg']=__('Error !!!', 'webtoffee-product-feed-pro');
			}
		}
		if($er==0)
		{
			$this->remove_licence_data($licence_product);
            $out['status']=true;
			$out['msg']=__("Successfully deleted.", 'webtoffee-product-feed-pro');
		}

		return $out;
	}

	/**
	*	Ajax sub function to deactivate licence
	*/
	public function deactivate($out)
	{

		$out['status']=false;
		$er=0;

		$licence_product= 'webtoffee-product-feed-pro';

		if($licence_product=="")
		{
			$er=1;
			$out['msg']=__('Error !!!', 'webtoffee-product-feed-pro');
		}else
		{
			if(!isset($this->products[$licence_product]))
			{
				$er=1;
				$out['msg']=__('Error !!!', 'webtoffee-product-feed-pro');
			}
		}

		if($er==0)
		{
			$licence_data=$this->get_licence_data($licence_product);
			if(!$licence_data)
			{
				$er=1;
				$out['msg']=__('Error !!!', 'webtoffee-product-feed-pro');
			}
		}

		$product_data=$this->products[$licence_product];
		if($er==0)
		{
			$license_type=$this->get_license_type($licence_data);
				$args=array(
					'edd_action'	=> 'deactivate_license',
					'license'		=> $licence_data[$licence_product]['key'],
					//'item_name' 	=> $product_data['product_display_name'], //name in EDD
					'item_id' 		=> WT_PF_EDD_ACTIVATION_ID, //ID in EDD
					'url' 			=> urlencode(home_url()),
				);

			$response=$this->remote_get($args);
			
			if(is_wp_error($response) || wp_remote_retrieve_response_code($response)!=200)
			{
				$out['msg']=__("Request failed, Please try again", 'webtoffee-product-feed-pro');
			}else
	        {
	        	$response=json_decode(wp_remote_retrieve_body($response), true);
	        	$success=false;
				
		        	if(isset($response['success']) && $response['success']===true)
		        	{
		        		$success=true;
		        	}
		        

		        if($success)
		        {
		        	$this->remove_licence_data($licence_product);
		            $out['status']=true;
					$out['msg']=__("Successfully deactivated.", 'webtoffee-product-feed-pro'); 
		        }else
		        {
		        	$out['msg']=__('Error', 'webtoffee-product-feed-pro');
		        }

	        }
		}
		return $out;
	}

	public function remote_get($args)
	{
		global $wp_version;
		$target_url=esc_url_raw($this->create_api_url($args));

		$def_args = array(
		    'timeout'     => 5,
		    'redirection' => 5,
		    'httpversion' => '1.0',
		    'user-agent'  => 'WordPress/' . $wp_version . '; ' . home_url(),
		    'blocking'    => true,
		    'headers'     => array(),
		    'cookies'     => array(),
		    'body'        => null,
		    'compress'    => false,
		    'decompress'  => true,
		    'sslverify'   => false,
		    'stream'      => false,
		    'filename'    => null
		);
		return wp_remote_get($target_url, $def_args);
	}

	/**
	*	Ajax sub function to activate licence
	*/
	public function activate($out)
	{
		global $wp_version;

		$out['status']=false;
		$er=0;
		$licence_product = WT_PF_ACTIVATION_ID;
		$licence_key=trim(isset($_POST['wt_pf_licence_key']) ? sanitize_text_field($_POST['wt_pf_licence_key']) : '');		

		if($er==0 && $licence_key=="")
		{
			$er=1;
			$out['msg']=__('Please enter Licence key', 'webtoffee-product-feed-pro');
		}
		if($er==0 && $licence_key!="")
		{
			/* check the licence key already applied */
			$licence_data=$this->get_licence_data();
			foreach ($licence_data as $product_slug => $licence_info)
			{
				if($product_slug==$licence_product) /* already one licence exists */
				{
					if($licence_info['status']=='active')
					{
						$er=1;
						$out['msg']=__('The chosen plugin already has an active licence.', 'webtoffee-product-feed-pro');
						break;
					}
				}

				/* current licence key matches with another product */
				if( isset($licence_info['key']) && $licence_key==$licence_info['key'] && $product_slug!=$licence_product && $licence_info['status']=='active')
				{
					$er=1;
					$out['msg']=__('This licence key has already been activated for another product. Please provide another licence key.', 'webtoffee-product-feed-pro');
					break;
				}
			}
		}


		if($er==0)
		{
			$product_data=$this->products[$licence_product];

			$args = array(
				'edd_action'		=> 'activate_license',
				'license'			=> $licence_key,
				//'item_name' 		=> $product_data['product_display_name'], //name in EDD
				'item_id' 			=> WT_PF_EDD_ACTIVATION_ID, //ID in EDD
				'url' 				=> urlencode(home_url()),
			);
			
			$response=$this->remote_get($args);

			// Request failed
			if(is_wp_error($response))
			{
				$out['msg']=$response->get_error_message();
			}
			elseif( wp_remote_retrieve_response_code( $response ) != 200 )
			{
				$out['msg']=__("Request failed, Please try again", 'webtoffee-product-feed-pro');
			}
	        else
	        {	        	
	        	$response_arr=json_decode($response['body'], true);
	
		        	if(isset($response_arr['success']) && $response_arr['success']===true) /* success */
		        	{
	        			$licence_data=array(
							'key'			=> $licence_key,
							'email'			=> (isset($response_arr['customer_email']) ? sanitize_text_field($response_arr['customer_email']) : ''), //from EDD
							'status'		=> 'active',
							'products'		=> $product_data['product_display_name'], 
							'instance_id'	=> (isset($response_arr['checksum']) ? sanitize_text_field($response_arr['checksum']) : ''), //from EDD
						);						
						$out['status']=true;	        		
		        	}

		        	if(!$out['status']) /* error */
		        	{	
		        		$out['msg']=$this->process_error_keys( (isset($response_arr['error']) ? $response_arr['error'] : '') );
		        	}

		        

		        if($out['status']===true) /* success. Save license info */
		        {
		        	$this->add_new_licence_data($licence_product, $licence_data);
		        	$out['msg']=__("Successfully activated.", 'webtoffee-product-feed-pro');
		        }

	        }
		}
		return $out;
	}

	/**
	*	Ajax sub function to get license list
	*/
	public function licence_list($out)
	{
		$licence_data_arr=$this->get_licence_data(); //taking all license info
		ob_start();
		include plugin_dir_path(__FILE__).'views/_licence_list.php';
		$out['html']=ob_get_clean();
		$out['license_status'] = $this->is_licence_active();
		return $out;
	}

	/**
	*	Mask licence key
	*/
	public function mask_licence_key($key)
	{
		$total_length=strlen($key);
		$non_mask_length=6; //including both side
		$mask_length=$total_length-$non_mask_length;
		
		if($mask_length>=1) //atleast one character
		{
			$key=substr_replace($key, str_repeat("*", $mask_length), floor($non_mask_length/2), ($total_length-$non_mask_length));
		}else
		{
			$key=str_repeat("*", $total_length); //replace all character
		}
		return $key;		
	}

	/**
	*	Licence tab head
	*/
	public function licence_tabhead($arr)
	{	
		$status=true;
		$licence_data=$this->get_licence_data();
		if(!$licence_data)
		{
			$status=false; //no licence found
		}

		if($status && count($licence_data)!=count($this->products))
		{
			$status=false; //licence misisng for some products
		}

		if($status)
		{
			$licence_statuses=array_column($licence_data, 'status');
			if(count($licence_statuses)==0 || in_array('inactive', $licence_statuses) || in_array('', $licence_statuses)) //inactive licence
			{
				$status=false;
			}		
		}
		if($status)
	    {
	        $activate_icon=$this->tab_icons['active'];   
	    }else
	    {
	        $activate_icon=$this->tab_icons['inactive'];
	    }
		$arr['wt-licence']=array(__('Licence','webtoffee-product-feed-pro'),$activate_icon);
		return $arr;
	} 

	
	/**
	*	Licence is active
	*/
	public function is_licence_active()
	{	
		$status=true;
		$licence_data=$this->get_licence_data();

		if(!$licence_data)
		{
			$status=false; //no licence found
		}

		if($status && count($licence_data)!=count($this->products))
		{
			$status=false; //licence misisng for some products
		}

		if($status)
		{
			$licence_statuses=array_column($licence_data, 'status');
			if(count($licence_statuses)==0 || in_array('inactive', $licence_statuses) || in_array('', $licence_statuses)) //inactive licence
			{
				$status=false;
			}		
		}

		return $status;

	} 
	
	/**
	*	Licence tab content
	*/
	public function licence_content()
	{
		wp_enqueue_script($this->module_id, plugin_dir_url( __FILE__ ).'assets/js/main.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_PRO_SYNC_VERSION);

		$params=array(
	        'ajax_url' => admin_url('admin-ajax.php'),
	        'nonce' => wp_create_nonce(WEBTOFFEE_PRODUCT_FEED_PRO_ID),
	        'tab_icons'=>$this->tab_icons,
	        'msgs'=>array(
	        	'key_mandatory'=>__('Please enter Licence key', 'webtoffee-product-feed-pro'),
	        	'email_mandatory'=>__('Please enter Email', 'webtoffee-product-feed-pro'),
	        	'product_mandatory'=>__('Please select a product', 'webtoffee-product-feed-pro'),
	        	'please_wait'=>__('Please wait...', 'webtoffee-product-feed-pro'),
	        	'error'=>__('Error', 'webtoffee-product-feed-pro'),
	        	'success'=>__('Success', 'webtoffee-product-feed-pro'),
	        	'unable_to_fetch'=>__('Unable to fetch Licence details', 'webtoffee-product-feed-pro'),
	        	'no_licence_details'=>__('No Licence details found.', 'webtoffee-product-feed-pro'),
	        	'sure'=>__('Are you sure?', 'webtoffee-product-feed-pro'),
	        )
		);
		wp_localize_script($this->module_id, 'wt_pf_licence_params', $params);
		$license_act_status = $this->is_licence_active();
		$view_file=plugin_dir_path(__FILE__).'views/licence-settings.php';	
		$view_params=array(
			'products'=>$this->products,
			'license_status' => $license_act_status	
		);
		Webtoffee_Product_Feed_Sync_Pro_Admin::envelope_settings_tabcontent('wt-licence', $view_file, '', $view_params, 0);
	}

	public function get_status_label($status)
	{
		$color_arr=array(
			'active'=>'#5cb85c',
			'inactive'=>'#ccc',
		);
		$color_css=(isset($color_arr[$status]) ? 'background:'.$color_arr[$status].';' : '');
		return '<span class="wt_pf_badge" style="'.$color_css.'">'.ucfirst($status).'</span>';
	}

	public function get_display_name($product_slug)
	{
		if(isset($this->products[$product_slug]))
		{
			return $this->products[$product_slug]['product_display_name'];
		}
		return '';
	}

	private function create_api_url($args)
	{
		return urldecode(add_query_arg($args, $this->api_url));	
	}

	/**
	*	Add new licence info
	*/
	private function add_new_licence_data($product_slug, $licence_data)
	{
		update_option($product_slug.'_licence_data', $licence_data);
	}

	private function remove_licence_data($product_slug)
	{
		delete_option($product_slug.'_licence_data');
	}

	private function update_licence_data($product_slug, $licence_data)
	{
		update_option($product_slug.'_licence_data', $licence_data);
	}

	private function get_licence_data($product_slug="webtoffee-product-feed-pro")
	{
		$licence_data=array();
		if($product_slug!="")
		{
			$licence_data[$product_slug]=get_option($product_slug.'_licence_data', false);
		}else
		{

			$licence_data=array();
			foreach ($this->products as $product_slug => $product)
			{
				$licence_info=get_option($product_slug.'_licence_data', false);
				if($licence_info) //licence exists
				{
					$licence_data[$product_slug]=$licence_info;	
				}
			}
		}
		return $licence_data;
	}

	/**
	*	Check the licence type is EDD or WC
	*/
	private function get_license_type_obj($licence_data)
	{

		return Wt_Product_Feed_Licence_Manager_Edd::get_instance();
	}

	/**
	*	Check the licence type is EDD or WC
	*/
	private function get_license_type($licence_data)
	{
		return 'EDD';
	}

	private function process_error_keys($key)
	{
		$msg_arr=array(
			"missing" => __("License doesn't exist", 'webtoffee-product-feed-pro'),
			"missing_url" => __("URL not provided", 'webtoffee-product-feed-pro'),
			"license_not_activable" => __("Attempting to activate a bundle's parent license", 'webtoffee-product-feed-pro'),
			"disabled" => __("License key revoked", 'webtoffee-product-feed-pro'),
			"no_activations_left" => __("No activations left", 'webtoffee-product-feed-pro'),
			"expired" => __("License has expired", 'webtoffee-product-feed-pro'),
			"key_mismatch" => __("License is not valid for this product", 'webtoffee-product-feed-pro'),
			"invalid_item_id" => __("Invalid Product", 'webtoffee-product-feed-pro'),
			"item_name_mismatch" => __("License is not valid for this product", 'webtoffee-product-feed-pro'),
		);
		return (isset($msg_arr[$key]) ? $msg_arr[$key] : __("Error", 'webtoffee-product-feed-pro'));
	}
}
new Wt_Product_Feed_Licence_Manager();