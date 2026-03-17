<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://growcommerce.io/
 * @since      1.1.0
 *   
 * @package    PMW_Helper
 * 
 */
if(!defined('ABSPATH')){
  exit; // Exit if accessed directly
}
if(!class_exists('PMW_AdminMigrateHelper')):  
  class PMW_AdminMigrateHelper extends PMW_AdminHelper{
    protected $version;
    public function __construct() {
      if ( defined( 'PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION' ) ) {
        $this->version = PIXEL_MANAGER_FOR_WOOCOMMERCE_VERSION;
      } else {
        $this->version = '1.0.0';
      }
      $this->includes();
      $this->check_migrate();
    }
    public function includes(){
      if(!class_exists('PMW_AdminAPIHelper')){
        require_once( PIXEL_MANAGER_FOR_WOOCOMMERCE_DIR . 'admin/helper/class-pmw-admin-api-helper.php');
      }
    }

    /**
     * check migrate
     * @since    1.1.0
     */
    public function check_migrate(){
      $migration = $this->get_migration();
      if(!is_array($migration)){
        $migration = array();
      }
      $pmw_version = isset($migration['pmw_version'])?$migration['pmw_version']:"";
      if( version_compare($pmw_version , $this->version, ">=")){
        return;
      }else{
        $PMW_API = new PMW_AdminAPIHelper();
        $api_rs = $PMW_API->save_product_store(array(), 1);
        if (!empty($api_rs) && isset($api_rs->error) && $api_rs->error == '' && isset($api_rs->data) ) {
          $this->save_pmw_api_store((array)$api_rs->data);
        } 
        $migration["pmw_version"] = $this->version;
        $this->set_migration($migration);
      }      
    }

    /*
     * set migrate data in DB
     */
    public function set_migration($pmw_migration){
      update_option("pmw_migration", serialize($pmw_migration));
    }
    /*
     * get migrate data from DB
     */
    public function get_migration(){
      return unserialize(get_option('pmw_migration'));     
    }    
    
  }
endif;
new PMW_AdminMigrateHelper();