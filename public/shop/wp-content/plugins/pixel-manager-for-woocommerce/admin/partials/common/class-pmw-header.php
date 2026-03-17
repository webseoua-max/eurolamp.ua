<?php
/**
 * @since      1.0.0
 * Description: Header
 */
if ( ! class_exists( 'PMW_Header' ) ) {
	class PMW_Header extends PMW_AdminHelper{
		protected $is_pro_version;
		protected $api_store;
		protected $site_url;
		public function __construct( ){
			$this->api_store = (object)$this->get_pmw_api_store();
      $this->is_pro_version = $this->pmw_is_pro_version($this->api_store);
			$this->site_url = "admin.php?page=";		
			add_action('pmw_header',array($this, 'before_start_header'));
			//add_action('pmw_header',array($this, 'header_notices'));
			add_action('pmw_header',array($this, 'page_header'));
			//add_action('pmw_header',array($this, 'header_menu'));
		}	
		
		/**
     * before start header section
     *
     * @since    1.0.0
     */
		public function before_start_header(){
			?>
			<div class="pmw_page">
			<?php
		}
		/**
     * header notices section
     *
     * @since    1.0.0
     */
		public function header_notices(){
			?>
			<div class="top_bar">
        <div class="pmw_container">
        </div>
      </div>
			<?php
		}
		/**
     * header section
     *
     * @since    1.0.0
     */
		public function page_header(){
			?>
      <main>
      	<div class="pmw_container-header">
          <section class="hero-section">
            <section class="hero-section-logo">
              <a target="_blank" href="<?php echo esc_url_raw($this->get_pmw_website_link());?>product/pixel-tag-manager-for-woocommerce/?utm_source=Plugin+WordPress+Screen&utm_medium=Top+Logo+Img&m_campaign=Upsell+at+PixelTagManager+Plugin" class=""><img src="<?php echo esc_url_raw(PIXEL_MANAGER_FOR_WOOCOMMERCE_URL."/admin/images/pixel-icon.png"); ?>" alt="rate-us" /></a>
            </section>
            <section class="hero-section-haader">			  					    
              <?php $this->header_menu();?>
            </section>
          </section>
				</div>
				<section class="pmw_section-tabbing">
        	<div class="pmw_container">
            <div class="pmw_section-tab-box">
			<?php
		}

		/* add active tab class */
	  protected function is_active_menu($page=""){
      if($page!="" && isset($_GET['page']) && sanitize_text_field($_GET['page']) == $page){
        return "active";
      }
      return;
	  }
	  /**
     * header section
     *
     * @since    1.0.0
     */
	  public function menu_list(){
	  	//slug => arra();
	  	$menu_list = array(
	  		'pixel-manager' => array(
	  			'title'=>__('Pixels Settings', 'pixel-manager-for-woocommerce'),
	  			'icon'=>'',
	  			'css-icon'=>'pmw_icon-setting',
	  			'acitve_icon'=>''
	  		),'pixel-manager-growinsights360'=>array(
	  			'title'=>__('GrowInsights360', 'pixel-manager-for-woocommerce'),
	  			'css-icon'=>'pmw_icon-growinsights360',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'pixel-manager-account'=>array(
	  			'title'=>__('Account', 'pixel-manager-for-woocommerce'),
	  			'css-icon'=>'pmw_icon-account',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		),'pixel-manager-support'=>array(
	  			'title'=>__('Support', 'pixel-manager-for-woocommerce'),
	  			'icon'=>'im_icon im_icon-support',
	  			'css-icon'=>'pmw_icon-support',
	  			'acitve_icon'=>''
	  		),'pixel-manager-documentation'=>array(
	  			'title'=>__('Documentation', 'pixel-manager-for-woocommerce'),
	  			'css-icon'=>'pmw_icon-documentation',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		)
	  	);
	  	if(!$this->is_pro_version){
	  		$menu_list["pixel-manager-freevspro"] = array(
	  			'title'=>__('Free Vs PRO', 'pixel-manager-for-woocommerce'),
	  			'css-icon'=>'pmw_icon-freevspro',
	  			'icon'=>'',
	  			'acitve_icon'=>''
	  		);
	  	}
	  	return apply_filters('wc_order_menu_list', $menu_list, $menu_list);
	  }
		/**
     * header menu section
     *
     * @since    1.0.0
     */
		public function header_menu(){
			$menu_list = $this->menu_list();
			if(!empty($menu_list)){
				?>				
      	<div class="pmw_main-top-menu pmw_d-flex pmw_justify-content-beetween align-items-center">
      		<ul class="pmw_main-tab-list <?php echo ($this->is_pro_version)?'sm-pmw_main-tab-list':''; ?>">
					<?php
					foreach ($menu_list as $key => $value) {
						if(isset($value['title']) && $value['title']){
							$is_active = $this->is_active_menu($key);
							$icon = "";
							if(!isset($value['icon']) && !isset($value['acitve_icon'])){
								$icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL.'/admin/images/'.$key.'-menu.png';					
								if($is_active == 'active'){
									$icon = PIXEL_MANAGER_FOR_WOOCOMMERCE_URL.'/admin/images/'.$is_active.'-'.$key.'-menu.png';
								}
							}else{
								$icon = (isset($value['icon']))?$value['icon']:((isset($value['acitve_icon']))?$value['acitve_icon']:"");
								if($is_active == 'active' && isset($value['acitve_icon'])){
									$icon =$value['acitve_icon'];
								}
							}
							if($key == "pixel-manager-freevspro"){
								?>								
								<li class="pmw_main-tab-item pmw_new_features_menu_item">
		              <a href="<?php echo esc_url_raw($this->site_url.$key); ?>" class="pmw_main-tab-link <?php echo esc_attr($is_active); ?>">
		              	<?php if( isset($value['css-icon']) && $value['css-icon'] ){?>
		              		<i class="pmw_icon <?php echo esc_attr($value['css-icon']); ?>"></i>
		              	<?php }else if($icon!=""){?>
		                	<span class="navinfoicon"><img src="<?php echo esc_url_raw($icon); ?>" /></span>
		              	<?php } ?>
		                <span class="navinfonavtext"><?php echo esc_attr($value['title']); ?></span><span class="pmw_new_features"><?php echo __('50% OFF', 'pixel-manager-for-woocommerce'); ?></span>
		              </a>
			          </li>
								<?php	
							}else if($key == "pixel-manager-support"){
								?>								
								<li class="pmw_main-tab-item">
		              <a target="_blank" href="<?php echo esc_url_raw($this->get_support_page_link()); ?>" class="pmw_main-tab-link <?php echo esc_attr($is_active); ?>">
		              	<?php if( isset($value['css-icon']) && $value['css-icon'] ){?>
		              		<i class="pmw_icon <?php echo esc_attr($value['css-icon']); ?>"></i>
		              	<?php }else if($icon!=""){?>
		                	<span class="navinfoicon"><img src="<?php echo esc_url_raw($icon); ?>" /></span>
		              	<?php } ?>
		                <span class="navinfonavtext"><?php echo esc_attr($value['title']); ?></span>
		              </a>
			          </li>
								<?php	
							}else{
							?>								
							<li class="pmw_main-tab-item">
	              <a href="<?php echo esc_url_raw($this->site_url.$key); ?>" class="pmw_main-tab-link <?php echo esc_attr($is_active); ?>">
	              	<?php if( isset($value['css-icon']) && $value['css-icon'] ){?>
	              		<i class="pmw_icon <?php echo esc_attr($value['css-icon']); ?>"></i>
	              	<?php }else if($icon!=""){?>
	                	<span class="navinfoicon"><img src="<?php echo esc_url_raw($icon); ?>" /></span>
	              	<?php } ?>
	                <span class="navinfonavtext"><?php echo esc_attr($value['title']); ?></span>
	              </a>
		          </li>
							<?php	
							}
						}
					}?>
					</ul>
				</div>
				<?php
			}
		}
	}
}
new PMW_Header();