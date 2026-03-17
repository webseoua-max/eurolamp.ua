<?php

/**
 * Set and Get the Plugin Data.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.10 (12-01-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * Set and Get the Plugin Data.
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Data {

	/**
	 * Plugin options array.
	 *
	 * @access private
	 * @var array
	 */
	private $data_arr = [];

	/**
	 * Set and Get the Plugin Data.
	 * 
	 * @param array $data_arr
	 */
	public function __construct( $data_arr = [] ) {

		if ( empty( $data_arr ) ) {
			$this->data_arr = [
				[
					'opt_name' => 'xfgmc_status_sborki',
					'def_val' => '-1',
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // дата начала сборки
					'opt_name' => 'xfgmc_date_sborki_start',
					'def_val' => '-', // 'Y-m-d H:i
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // дата завершения сборки
					'opt_name' => 'xfgmc_date_sborki_end',
					'def_val' => '-', // 'Y-m-d H:i
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[  // дата последнего успешного обновления фида
					'opt_name' => 'xfgmc_date_successful_feed_update',
					'def_val' => 0000000001, // 0000000001 - timestamp format
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[  // дата сохранения настроек плагина
					'opt_name' => 'xfgmc_date_save_set',
					'def_val' => 0000000001, // 0000000001 - timestamp format
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[  // число товаров, попавших в выгрузку
					'opt_name' => 'xfgmc_count_products_in_feed',
					'def_val' => '-1',
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[
					'opt_name' => 'xfgmc_feed_url', // https://site.ru/wp-content/uploads/feed-xml-0.xml
					'def_val' => '',
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[
					'opt_name' => 'xfgmc_feed_path', // /home/site.ru/public_html/wp-content/uploads/feed-xml-0.xml
					'def_val' => '',
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				[ // сюда будем записывать критически ошибки при сборке фида
					'opt_name' => 'xfgmc_critical_errors', // ? возможно удалить в перспективе
					'def_val' => '',
					'mark' => 'private',
					'type' => 'auto',
					'tab' => 'none'
				],
				// ------------------- ОСНОВНЫЕ НАСТРОЙКИ -------------------
				[
					'opt_name' => 'xfgmc_run_cron',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Creating this feed', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s. %s "%s" %s "%s"',
							__( 'The refresh interval on your feed', 'xml-for-google-merchant-center' ),
							__( 'If you select the option', 'xml-for-google-merchant-center' ),
							__( 'Create a feed once and DO NOT update', 'xml-for-google-merchant-center' ),
							__(
								'after generating the feed, the parameter value will change to',
								'xml-for-google-merchant-center'
							),
							__( 'Disable the creation and updating of this feed', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[
								'value' => 'disabled',
								'text' => __( 'Disable the creation and updating of this feed', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'once',
								'text' => sprintf( '%s',
									__( "Create a feed once and DO NOT update", "xml-for-google-merchant-center" )
								)
							],
							[
								'value' => 'hourly',
								'text' => __( 'Create a feed once an hour', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'three_hours', 'text' => __( 'Create a feed every three hours', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'six_hours', 'text' => __( 'Create a feed every six hours', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'twicedaily', 'text' => __( 'Create a feed twice a day', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'daily', 'text' => __( 'Create a feed once a day', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'every_two_days', 'text' => __( 'Create a feed every two days', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'weekly', 'text' => __( 'Create a feed once a week', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_cron_start_time',
					'def_val' => 'now',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'td-td',
						'label' => __( 'Starting at the specified time', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s',
							__( 'The time at which the feed generation should start', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'now', 'text' => __( 'Now', 'xml-for-google-merchant-center' ) ],
							[ 'value' => '1am', 'text' => '01-00' ],
							[ 'value' => '2am', 'text' => '02-00' ],
							[ 'value' => '3am', 'text' => '03-00' ],
							[ 'value' => '4am', 'text' => '04-00' ],
							[ 'value' => '5am', 'text' => '05-00' ],
							[ 'value' => '6am', 'text' => '06-00' ],
							[ 'value' => '7am', 'text' => '07-00' ],
							[ 'value' => '8am', 'text' => '08-00' ],
							[ 'value' => '9am', 'text' => '09-00' ],
							[ 'value' => '10am', 'text' => '10-00' ],
							[ 'value' => '11am', 'text' => '11-00' ],
							[ 'value' => '12pm', 'text' => '12-00' ],
							[ 'value' => '1pm', 'text' => '13-00' ],
							[ 'value' => '2pm', 'text' => '14-00' ],
							[ 'value' => '3pm', 'text' => '15-00' ],
							[ 'value' => '4pm', 'text' => '16-00' ],
							[ 'value' => '5pm', 'text' => '17-00' ],
							[ 'value' => '6pm', 'text' => '18-00' ],
							[ 'value' => '7pm', 'text' => '19-00' ],
							[ 'value' => '8pm', 'text' => '20-00' ],
							[ 'value' => '9pm', 'text' => '21-00' ],
							[ 'value' => '10pm', 'text' => '22-00' ],
							[ 'value' => '11pm', 'text' => '23-00' ],
							[ 'value' => '12am', 'text' => '00-00' ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_ufup',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Update feed when updating products', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s "%s" %s',
							__( 'This option does not work if selected', 'xml-for-google-merchant-center' ),
							__( 'Disable the creation and updating of this feed', 'xml-for-google-merchant-center' ),
							__( 'in the field above', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_xml_rules',
					'def_val' => 'merchant_center',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'To follow the rules', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s <i>(%s)</i>. %s. %s. <a target="_blank" href="%s/?utm_source=xml-for-google-merchant-center&utm_medium=documentation&utm_campaign=basic-version&utm_content=settings-page&utm_term=about-rules">%s</a>',
							__( 'Exclude products that do not meet the requirements', 'xml-for-google-merchant-center' ),
							__( 'missing required elements/data', 'xml-for-google-merchant-center' ),
							__(
								'The plugin will try to automatically remove products from the XML-feed for which the required fields for the feed are not filled',
								'xml-for-google-merchant-center'
							),
							__( 'Also, this item affects the structure of the file', 'xml-for-google-merchant-center' ),
							'//icopydoc.ru/na-chto-vliyaet-priderzhivatsya-pravil-v-plagine-xfgmc',
							__( 'Learn more about how it works', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[
								'value' => 'merchant_center',
								'text' => sprintf( '%s',
									__( 'Google Merchant Center', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'facebook',
								'text' => sprintf( '%s',
									__( 'Facebook', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'all_elements',
								'text' => sprintf( '%s (%s)',
									__( 'No rules', 'xml-for-google-merchant-center' ),
									__( 'For experienced users', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_feed_assignment',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'Feed assignment', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Not used in feed. Inner note for your convenience', 'xml-for-google-merchant-center' ),
						'placeholder' => __( 'For Google Merchant Center', 'xml-for-google-merchant-center' ),
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_feed_name',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Name of the feed file', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s. <strong>%s:</strong> %s',
							__(
								'If you leave the field empty, the default value will be used',
								'xml-for-google-merchant-center'
							),
							__( 'Important', 'xml-for-google-merchant-center' ),
							__(
								'Spaces cannot be used',
								'xml-for-google-merchant-center'
							)
						),
						'placeholder' => 'feed-xml-0',
						'tr_class' => ''
					]
				],
				[
					'opt_name' => 'xfgmc_file_extension',
					'def_val' => 'xml',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Feed file extension', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s: XML',
							__( 'Default', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'xml', 'text' => 'XML (' . __( 'recommend', 'xml-for-google-merchant-center' ) . ')' ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_archive_to_zip',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'Archive to ZIP', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'xml-for-google-merchant-center' ),
							__( 'Disabled', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_step_export',
					'def_val' => '500',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'Step export', 'xml-for-google-merchant-center' ),
						'desc' =>
							sprintf( '%s. %s. %s',
								__( 'The value affects the speed of file creation', 'xml-for-google-merchant-center' ),
								__(
									'If you have any problems with the generation of the file - try to reduce the value in this field',
									'xml-for-google-merchant-center'
								),
								__( 'More than 500 can only be installed on powerful servers', 'xml-for-google-merchant-center' )
							),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => '80', 'text' => '80' ],
							[ 'value' => '100', 'text' => '100' ],
							[ 'value' => '200', 'text' => '200' ],
							[ 'value' => '300', 'text' => '300' ],
							[ 'value' => '400', 'text' => '400' ],
							[
								'value' => '500',
								'text' => sprintf(
									'500 (%s)', __( 'Default value', 'xml-for-google-merchant-center' )
								)
							],
							[ 'value' => '600', 'text' => '600' ],
							[ 'value' => '700', 'text' => '700' ],
							[ 'value' => '800', 'text' => '800' ],
							[ 'value' => '900', 'text' => '900' ],
							[ 'value' => '1000', 'text' => '1000' ],
							[ 'value' => '1100', 'text' => '1100' ],
							[ 'value' => '1200', 'text' => '1200' ],
							[ 'value' => '1300', 'text' => '1300' ],
							[ 'value' => '1400', 'text' => '1400' ],
							[ 'value' => '1500', 'text' => '1500' ]
						],
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_script_execution_time',
					'def_val' => '26',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'The maximum script execution time', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s. <strong>%s:</strong> 26. %s 10-30 %s',
							__(
								'The maximum script execution time in seconds',
								'xml-for-google-merchant-center'
							),
							__( 'Default value', 'xml-for-google-merchant-center' ),
							__(
								'If you experience freezes when creating the feed, try increasing this parameter by',
								'xml-for-google-merchant-center'
							),
							__( 'points', 'xml-for-google-merchant-center' ),
						),
						'placeholder' => '26',
						'tr_class' => ''
					]
				],
				[
					'opt_name' => 'xfgmc_ignore_cache',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'label' => __( 'Ignore plugin cache', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s: <a 
						href="https://icopydoc.ru/pochemu-ne-obnovilis-tseny-v-fide-para-slov-o-tihih-pravkah/%s">%s</a>',
							__(
								"Changing this option can be useful if your feed prices don't change after syncing",
								'xml-for-google-merchant-center'
							),
							'?utm_source=xml-for-google-merchant-center&utm_medium=documentation&utm_campaign=basic-version&utm_content=settings-page&utm_term=about-cache',
							__( 'Learn More', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_do_cash_file',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'main_tab',
					'data' => [
						'label' => __(
							'Сreate cache files when saving products',
							'xml-for-google-merchant-center'
						),
						'desc' => sprintf( '%s. %s',
							__(
								'This option allows you to reduce the load on the site at the time of saving the product card',
								'xml-for-google-merchant-center'
							),
							__(
								'However, disabling this option leads to a significant increase in the feed creation time',
								'xml-for-google-merchant-center'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tr_class' => ''
					]
				],
				// ------------------- ДАННЫЕ МАГАЗИНА -------------------
				// https://support.google.com/merchants/answer/14987622
				[
					'opt_name' => 'xfgmc_shop_name',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'shop_data_tab',
					'data' => [
						'label' => __( 'Shop name', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'The short name of the store should not exceed 20 characters',
							'xml-for-google-merchant-center'
						),
						'default_value' => false,
						'placeholder' => 'Super Shop',
						'tag_name' => 'always',
						'tag_name_for_desc' => 'title'
					]
				],
				[
					'opt_name' => 'xfgmc_shop_description',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'shop_data_tab',
					'data' => [
						'label' => __( 'Shop description', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Shop description', 'xml-for-google-merchant-center' ),
						'default_value' => false,
						'placeholder' => '',
						'tag_name' => 'always',
						'tag_name_for_desc' => 'description'
					]
				],
				// ------------------- НАСТРОЙКИ АТРИБУТОВ -------------------
				[
					'opt_name' => 'xfgmc_source_id',
					'def_val' => 'default',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Source ID of the product', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'default', 'text' => __( 'Product ID / Variation ID', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' ) ],
							[
								'value' => 'germanized',
								'text' => __( 'Substitute from', 'xml-for-google-merchant-center' ) . 'WooCommerce Germanized'
							]
						],
						'tag_name' => 'id',
						'tag_name_for_desc' => 'g:id'
					]
				],
				[
					'opt_name' => 'xfgmc_source_id_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'tag_name' => 'id',
						'tag_name_for_desc' => 'g:id'
					]
				],
				[
					'opt_name' => 'xfgmc_product_title',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Product name', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'title',
						'tag_name_for_desc' => 'g:title'
					]
				],
				[
					'opt_name' => 'xfgmc_desc',
					'def_val' => 'fullexcerpt',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Description of the product', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s',
							__( 'The source of the description', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[
								'value' => 'excerpt',
								'text' => __( 'Only Excerpt description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'full',
								'text' => __( 'Only Full description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'excerptfull',
								'text' => __( 'Excerpt or Full description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'fullexcerpt',
								'text' => __( 'Full or Excerpt description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'excerptplusfull',
								'text' => __( 'Excerpt plus Full description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'fullplusexcerpt',
								'text' => __( 'Full plus Excerpt description', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'post_meta',
								'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' )
							]
						],
						'tr_class' => 'xfgmc_tr',
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_source_description_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'td-td',
						'label' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_enable_tags_behavior',
					'def_val' => 'default',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'td-td',
						'label' => __( 'List of allowed tags', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'default', 'text' => __( 'Default', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'custom', 'text' => __( 'From the field below', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_enable_tags_custom',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'default_value' => false,
						'label' => __( 'Allowed tags', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s <code>p,br,h3</code>',
							__( 'For example', 'xml-for-google-merchant-center' )
						),
						'placeholder' => 'p,br,h3',
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_var_desc_priority',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'th-td',
						'label' => __(
							'The varition description takes precedence over others',
							'xml-for-google-merchant-center'
						),
						'desc' => sprintf( '%s: %s',
							__( 'Default', 'xml-for-google-merchant-center' ),
							__( 'Enabled', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_the_content',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'th-td',
						'label' => __( 'Use the filter', 'xml-for-google-merchant-center' ) . ' the_content',
						'desc' => sprintf( '%s: %s. <a href="https://developer.wordpress.org/reference/hooks/the_content/">%s</a>',
							__( 'Default', 'xml-for-google-merchant-center' ),
							__( 'Enabled', 'xml-for-google-merchant-center' ),
							__( 'Learn More', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'description',
						'tag_name_for_desc' => 'g:description'
					]
				],
				[
					'opt_name' => 'xfgmc_link',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => sprintf( '%s (URL)',
							__( 'Product link', 'xml-for-google-merchant-center' )
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'link',
						'tag_name_for_desc' => 'g:link'
					]
				],
				[
					'opt_name' => 'xfgmc_mobile_link',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => sprintf( '%s (URL)',
							__( 'Product mobile link', 'xml-for-google-merchant-center' )
						),
						'desc' => __(
							'The mobile link attribute lets you include a URL to a mobile-optimized version of your landing page',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'mobile_link',
						'tag_name_for_desc' => 'g:mobile_link'
					]
				],
				[
					'opt_name' => 'xfgmc_image_link',
					'def_val' => 'full',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Picture', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s',
							__( 'Specify the size of the image to be used in the feed', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => $this->get_registered_image_sizes(),
						'tag_name' => 'image_link',
						'tag_name_for_desc' => 'g:image_link'
					]
				],
				[
					'opt_name' => 'xfgmc_availability',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Quantity of products', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'To make it work you must enable "Manage stock" and indicate "Stock quantity"',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'availability',
						'tag_name_for_desc' => 'g:availability'
					]
				],
				[
					'opt_name' => 'xfgmc_behavior_onbackorder',
					'def_val' => 'true',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __(
							'For pre-order products, establish availability equal to',
							'xml-for-google-merchant-center'
						),
						'desc' => sprintf( '%s in_stock/out_of_stock/preorder',
							__(
								'For pre-order products, establish availability equal to',
								'xml-for-google-merchant-center'
							)
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'in_stock', 'text' => 'in_stock' ],
							[ 'value' => 'out_of_stock', 'text' => 'out_of_stock' ],
							[ 'value' => 'preorder', 'text' => 'preorder' ]
						],
						'tag_name' => 'availability',
						'tag_name_for_desc' => 'g:availability'
					]
				],
				[
					'opt_name' => 'xfgmc_use_availability_date',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'label' => __( 'Availability date', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s "preorder"',
							__(
								'This parameter applies only to products whose status is equal to',
								'xml-for-google-merchant-center'
							)
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[
								'value' => 'enabled',
								'text' => sprintf( '%s. %s',
									__( 'Enabled', 'xml-for-google-merchant-center' ),
									__( 'Use the current date', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'enabled_default_value',
								'text' => sprintf( '%s. %s',
									__( 'Enabled', 'xml-for-google-merchant-center' ),
									__( 'Use the default value', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tag_name' => 'availability_date',
						'tag_name_for_desc' => 'g:availability_date'
					]
				],
				[
					'opt_name' => 'xfgmc_add_to_availability',
					'def_val' => '0',
					'mark' => 'public',
					'type' => 'number',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'td-td',
						'label' => __( 'Add days to the current date', 'xml-for-google-merchant-center' ),
						'desc' => __(
							"This option will add the number of days you specified to today's date",
							'xml-for-google-merchant-center'
						),
						'placeholder' => '0',
						'tag_name' => 'availability_date',
						'tag_name_for_desc' => 'g:availability_date'
					]
				],
				[
					'opt_name' => 'xfgmc_availability_date', // default value
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'Date, time, and timezone, ISO 8601 compliant',
							'xml-for-google-merchant-center'
						),
						'placeholder' => '2025-05-29T13:00-0800',
						'tag_name' => 'availability_date',
						'tag_name_for_desc' => 'g:availability_date'
					]
				],
				[
					'opt_name' => 'xfgmc_price',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Product price', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'price',
						'tag_name_for_desc' => 'g:price'
					]
				],
				[
					'opt_name' => 'xfgmc_default_currency',
					'def_val' => 'RUB',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shop currency', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s! %s: <strong>USD</strong>. <a href="//support.google.com/merchants/answer/160637" target="_blank">%s</a>',
							__( 'Uppercase letter', 'xml-for-google-merchant-center' ),
							__( 'For example', 'xml-for-google-merchant-center' ),
							__( 'Read more', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'placeholder' => 'USD',
						'tag_name' => 'price',
						'tag_name_for_desc' => __( 'Shop currency', 'xml-for-google-merchant-center' )
					]
				],
				[
					'opt_name' => 'xfgmc_sale_price',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Sale price', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'In sale price indicates the old price of the goods, which must necessarily be higher than the new price (price)',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'sale_price',
						'tag_name_for_desc' => 'g:sale_price'
					]
				],
				[
					'opt_name' => 'xfgmc_unit_pricing_measure',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shop SKU', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Shop SKU', 'xml-for-google-merchant-center' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'unit_pricing_measure',
						'tag_name_for_desc' => 'g:unit_pricing_measure'
					]
				],
				[
					'opt_name' => 'xfgmc_unit_pricing_base_measure',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shop SKU', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Shop SKU', 'xml-for-google-merchant-center' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'unit_pricing_base_measure',
						'tag_name_for_desc' => 'g:unit_pricing_base_measure'
					]
				],
				[
					'opt_name' => 'xfgmc_google_product_category',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Google product category', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'google_product_category',
						'tag_name_for_desc' => 'g:google_product_category'
					]
				],
				[
					'opt_name' => 'xfgmc_product_type',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Google product type', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Google product type', 'xml-for-google-merchant-center' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'product_type',
						'tag_name_for_desc' => 'g:product_type'
					]
				],
				[
					'opt_name' => 'xfgmc_product_type_home',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Add root element', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Main', 'xml-for-google-merchant-center' ),
						'tag_name' => 'product_type',
						'tag_name_for_desc' => 'g:product_type'
					]
				],
				[
					'opt_name' => 'xfgmc_brand',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Brand', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Brand', 'xml-for-google-merchant-center' ),
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [
							[
								'value' => 'disabled',
								'text' => __( 'Disabled', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'woocommerce_brands',
								'text' => __( 'WooCommerce brands', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'post_meta',
								'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'default_value',
								'text' => sprintf( '%s "%s"',
									__( 'Default value from field', 'xml-for-google-merchant-center' ),
									__( 'Default value', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tag_name' => 'brand',
						'tag_name_for_desc' => 'g:brand'
					]
				],
				[
					'opt_name' => 'xfgmc_brand_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => sprintf( '%s / %s',
							__( 'Default value', 'xml-for-google-merchant-center' ),
							__( 'Name post_meta', 'xml-for-google-merchant-center' )
						),
						'desc' => '',
						'placeholder' => sprintf( '%s / %s',
							__( 'Default value', 'xml-for-google-merchant-center' ),
							__( 'Name post_meta', 'xml-for-google-merchant-center' )
						),
						'tag_name' => 'brand',
						'tag_name_for_desc' => 'g:brand'
					]
				],
				[
					'opt_name' => 'xfgmc_gtin',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'GTIN', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'no', 'text' => __( 'No', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' ) ],
							[
								'value' => 'upc-ean-generator',
								'text' => sprintf( '%s UPC/EAN/GTIN Code Generator',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'ean-for-woocommerce',
								'text' => sprintf( '%s EAN for WooCommerce',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'germanized',
								'text' => sprintf( '%s WooCommerce Germanized',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tag_name' => 'gtin',
						'tag_name_for_desc' => 'g:gtin'
					]
				],
				[
					'opt_name' => 'xfgmc_gtin_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'tag_name' => 'gtin',
						'tag_name_for_desc' => 'g:gtin'
					]
				],
				[
					'opt_name' => 'xfgmc_mpn',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'MPN', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'no', 'text' => __( 'No', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'sku', 'text' => __( 'Substitute from SKU', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'post_meta', 'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' ) ],
							[
								'value' => 'upc-ean-generator',
								'text' => sprintf( '%s UPC/EAN/GTIN Code Generator',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'ean-for-woocommerce',
								'text' => sprintf( '%s EAN for WooCommerce',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							],
							[
								'value' => 'germanized',
								'text' => sprintf( '%s WooCommerce Germanized',
									__( 'Substitute from the plugin', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tag_name' => 'mpn',
						'tag_name_for_desc' => 'g:mpn'
					]
				],
				[
					'opt_name' => 'xfgmc_mpn_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Name post_meta', 'xml-for-google-merchant-center' ),
						'tag_name' => 'mpn',
						'tag_name_for_desc' => 'g:mpn'
					]
				],
				[
					'opt_name' => 'xfgmc_identifier_exists',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Identifier exists', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Identifier exists', 'xml-for-google-merchant-center' ),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'identifier_exists',
						'tag_name_for_desc' => 'g:identifier_exists'
					]
				],
				[
					'opt_name' => 'xfgmc_condition',
					'def_val' => 'new',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Condition', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'The default value, unless otherwise specified in the product settings',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'condition',
						'tag_name_for_desc' => 'g:condition'
					]
				],
				[
					'opt_name' => 'xfgmc_condition_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'new', 'text' => __( 'New', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'refurbished', 'text' => __( 'Refurbished', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'used', 'text' => __( 'Used', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'condition',
						'tag_name_for_desc' => 'g:condition'
					]
				],
				[
					'opt_name' => 'xfgmc_adult',
					'def_val' => 'false',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Adult', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'adult',
						'tag_name_for_desc' => 'g:adult'
					]
				],
				[
					'opt_name' => 'xfgmc_adult_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'alltrue', 'text' => __( 'Add to all', 'xml-for-google-merchant-center' ) . ' true' ],
							[ 'value' => 'allfalse', 'text' => __( 'Add to all', 'xml-for-google-merchant-center' ) . ' false' ]
						],
						'tag_name' => 'adult',
						'tag_name_for_desc' => 'g:adult'
					]
				],
				[
					'opt_name' => 'xfgmc_multipack',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Multipack', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'multipack',
						'tag_name_for_desc' => 'g:multipack'
					]
				],
				[
					'opt_name' => 'xfgmc_is_bundle',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Is bundle', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'is_bundle',
						'tag_name_for_desc' => 'g:is_bundle'
					]
				],
				[
					'opt_name' => 'xfgmc_is_bundle_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'alltrue', 'text' => __( 'Add to all', 'xml-for-google-merchant-center' ) . ' true' ],
							[ 'value' => 'allfalse', 'text' => __( 'Add to all', 'xml-for-google-merchant-center' ) . ' false' ]
						],
						'tag_name' => 'is_bundle',
						'tag_name_for_desc' => 'g:is_bundle'
					]
				],
				[
					'opt_name' => 'xfgmc_age_group',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Age group', 'xml-for-google-merchant-center' ),
						'desc' => __( 'Age group', 'xml-for-google-merchant-center' ),
						'woo_attr' => true,
						'default_value' => true,
						'key_value_arr' => [
							[
								'value' => 'disabled',
								'text' => __( 'Disabled', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'post_meta',
								'text' => __( 'Substitute from post meta', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'default_value',
								'text' => sprintf( '%s "%s"',
									__( 'Default value from field', 'xml-for-google-merchant-center' ),
									__( 'Default value', 'xml-for-google-merchant-center' )
								)
							]
						],
						'tag_name' => 'age_group',
						'tag_name_for_desc' => 'g:age_group'
					]
				],
				[
					'opt_name' => 'xfgmc_age_group_post_meta',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => sprintf( '%s / %s',
							__( 'Default value', 'xml-for-google-merchant-center' ),
							__( 'Name post_meta', 'xml-for-google-merchant-center' )
						),
						'desc' => sprintf( '%s: newborn, infant, toddler, kids, adult',
							__( 'Acceptable values', 'xml-for-google-merchant-center' )
						),
						'placeholder' => sprintf( '%s / %s',
							__( 'Default value', 'xml-for-google-merchant-center' ),
							__( 'Name post_meta', 'xml-for-google-merchant-center' )
						),
						'tag_name' => 'age_group',
						'tag_name_for_desc' => 'g:age_group'
					]
				],
				[
					'opt_name' => 'xfgmc_color',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Color', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'color',
						'tag_name_for_desc' => 'g:color'
					]
				],
				[
					'opt_name' => 'xfgmc_gender',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Gender', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'gender',
						'tag_name_for_desc' => 'g:gender'
					]
				],
				[
					'opt_name' => 'xfgmc_gender_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'male', 'text' => 'Male' ],
							[ 'value' => 'female', 'text' => 'Female' ],
							[ 'value' => 'unisex', 'text' => 'Unisex' ]
						],
						'tag_name' => 'gender',
						'tag_name_for_desc' => 'g:gender'
					]
				],
				[
					'opt_name' => 'xfgmc_material',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Material', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'material',
						'tag_name_for_desc' => 'g:material'
					]
				],
				[
					'opt_name' => 'xfgmc_pattern',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Pattern', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'pattern',
						'tag_name_for_desc' => 'g:pattern'
					]
				],
				[
					'opt_name' => 'xfgmc_size',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Size', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'size',
						'tag_name_for_desc' => 'g:size'
					]
				],
				[
					'opt_name' => 'xfgmc_size_type',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Size type', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'size_type',
						'tag_name_for_desc' => 'g:size_type'
					]
				],
				[
					'opt_name' => 'xfgmc_size_type_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'regular', 'text' => 'Regular' ],
							[ 'value' => 'petite', 'text' => 'petite' ],
							[ 'value' => 'plus', 'text' => 'plus' ],
							[ 'value' => 'bigandtall', 'text' => 'Big and tall' ],
							[ 'value' => 'maternity', 'text' => 'Maternity' ]
						],
						'tag_name' => 'size_type',
						'tag_name_for_desc' => 'g:size_type'
					]
				],
				[
					'opt_name' => 'xfgmc_size_system',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => true,
						'table_location' => 'th-td',
						'label' => __( 'Size system', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'size_system',
						'tag_name_for_desc' => 'g:size_system'
					]
				],
				[
					'opt_name' => 'xfgmc_size_system_default_value',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'table_location' => 'td-td',
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => true,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'AU', 'text' => 'AU' ],
							[ 'value' => 'BR', 'text' => 'BR' ],
							[ 'value' => 'CN', 'text' => 'CN' ],
							[ 'value' => 'DE', 'text' => 'DE' ],
							[ 'value' => 'EU', 'text' => 'EU' ],
							[ 'value' => 'FR', 'text' => 'FR' ],
							[ 'value' => 'IT', 'text' => 'IT' ],
							[ 'value' => 'JP', 'text' => 'JP' ],
							[ 'value' => 'MEX', 'text' => 'MEX' ],
							[ 'value' => 'UK', 'text' => 'UK' ],
							[ 'value' => 'US', 'text' => 'US' ]
						],
						'tag_name' => 'size_system',
						'tag_name_for_desc' => 'g:size_system'
					]
				],
				[
					'opt_name' => 'xfgmc_item_group_id',
					'def_val' => 'enabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Item group ID', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'Use the item group ID attribute to group product variants in your product data',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'item_group_id',
						'tag_name_for_desc' => 'g:item_group_id'
					]
				],
				[
					'opt_name' => 'xfgmc_length',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Length', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Length', 'xml-for-google-merchant-center' ),
							'&lt;g:product_length>X sm</g:product_length&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'dimensions',
						'tag_name_for_desc' => esc_attr( 'g:product_[lenght, width, height, weight]' ),
						'div_header' => __( 'Dimensions', 'xml-for-google-merchant-center' )
					]
				],
				[
					'opt_name' => 'xfgmc_width',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Width', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Width', 'xml-for-google-merchant-center' ),
							'&lt;g:product_width>X sm</g:product_width&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'dimensions',
						'tag_name_for_desc' => esc_attr( 'g:product_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_height',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Height', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Height', 'xml-for-google-merchant-center' ),
							'&lt;g:product_height>X sm</g:product_height&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'dimensions',
						'tag_name_for_desc' => esc_attr( 'g:product_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_product_weight',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Weight', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Weight', 'xml-for-google-merchant-center' ),
							'&lt;g:product_weight>X kg</g:product_weight&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'dimensions',
						'tag_name_for_desc' => esc_attr( 'g:product_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_custom_labels',
					'def_val' => 'true',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Custom elements', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s. %s <a target="_blank" href="%s">%s</a>',
							__( 'To set a value, edit your products', 'xml-for-google-merchant-center' ),
							__( 'About this tag', 'xml-for-google-merchant-center' ),
							'//yandex.ru/support/direct/feeds/requirements-xml.html',
							__( 'see the Yandex help', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'custom_label',
						'tag_name_for_desc' => 'g:custom_label_[0 - 4]'
					]
				],
				[
					'opt_name' => 'xfgmc_shipping',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%1$s. <a href="%2$s" target="_blank">%3$s</a>.<br/>%4$s "country" %5$s "%6$s". <a href="%7$s" target="_blank">%3$s</a>',
							__(
								'Google recommend that you set up shipping costs through Merchant Center settings instead of submitting the shipping attribute in the feed',
								'xml-for-google-merchant-center'
							),
							'//support.google.com/merchants/answer/6069284',
							__( 'Read more', 'xml-for-google-merchant-center' ),
							__(
								'To add this element to your feed make sure the fields are filled',
								'xml-for-google-merchant-center'
							),
							__( 'and', 'xml-for-google-merchant-center' ),
							__( 'Delivery area', 'xml-for-google-merchant-center' ),
							'//support.google.com/merchants/answer/6324484',
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_country',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping country', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => '',
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_delivery_area_type',
					'def_val' => 'region',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Delivery area', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'text' => 'region', 'value' => 'region' ],
							[ 'text' => 'postal_code', 'value' => 'postal_code' ],
							[ 'text' => 'location_id', 'value' => 'location_id' ],
							[ 'text' => 'location_group_name', 'value' => 'location_group_name' ]
						],
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_delivery_area_value',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Delivery area', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => '',
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_price',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping price', 'xml-for-google-merchant-center' ),
						'desc' => '[g:price]',
						'placeholder' => '',
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_service',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping service', 'xml-for-google-merchant-center' ),
						'desc' => '[g:service]',
						'placeholder' => '',
						'tag_name' => 'shipping',
						'tag_name_for_desc' => 'g:shipping'
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_length',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping length', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Length', 'xml-for-google-merchant-center' ),
							'&lt;g:shipping_length>X sm</g:shipping_length&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'shipping_dimensions',
						'tag_name_for_desc' => esc_attr( 'g:shipping_[lenght, width, height, weight]' ),
						'div_header' => __( 'Shipping dimensions', 'xml-for-google-merchant-center' )
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_width',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping width', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Width', 'xml-for-google-merchant-center' ),
							'&lt;g:shipping_width>X sm</g:shipping_width&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'shipping_dimensions',
						'tag_name_for_desc' => esc_attr( 'g:shipping_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_height',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping height', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Height', 'xml-for-google-merchant-center' ),
							'&lt;g:shipping_height>X sm</g:shipping_height&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'shipping_dimensions',
						'tag_name_for_desc' => esc_attr( 'g:shipping_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_shipping_weight',
					'def_val' => 'woo_shippings',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping weight', 'xml-for-google-merchant-center' ),
						'desc' => sprintf(
							'%s: %s',
							__( 'Weight', 'xml-for-google-merchant-center' ),
							'&lt;g:shipping_weight>X kg</g:shipping_weight&gt;'
						),
						'woo_attr' => true,
						'default_value' => false,
						'key_value_arr' => [
							[
								'value' => 'woo_shippings',
								'text' => __( 'Substitute from WooCommerce Shippings tab', 'xml-for-google-merchant-center' )
							]
						],
						'tag_name' => 'shipping_dimensions',
						'tag_name_for_desc' => esc_attr( 'g:shipping_[lenght, width, height, weight]' )
					]
				],
				[
					'opt_name' => 'xfgmc_tax',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Tax', 'xml-for-google-merchant-center' ),
						'desc' => __(
							"Required for the United States when you need to override the account tax settings that you created in Merchant Center. This attribute exclusively covers US sales tax. Don't use it for other taxes, such as value-added tax (VAT) or import tax",
							"xml-for-google-merchant-center"
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'tax',
						'tag_name_for_desc' => 'g:tax'
					]
				],
				[
					'opt_name' => 'xfgmc_tax_region',
					'def_val' => 'Washington',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Region', 'xml-for-google-merchant-center' ),
						'desc' => '[g:region]',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'text' => __( 'Idaho', 'xml-for-google-merchant-center' ), 'value' => 'ID' ],
							[ 'text' => __( 'Iowa', 'xml-for-google-merchant-center' ), 'value' => 'IA' ],
							[ 'text' => __( 'Alabama', 'xml-for-google-merchant-center' ), 'value' => 'AL' ],
							[ 'text' => __( 'Alaska', 'xml-for-google-merchant-center' ), 'value' => 'AK' ],
							[ 'text' => __( 'Arizona', 'xml-for-google-merchant-center' ), 'value' => 'AZ' ],
							[ 'text' => __( 'Arkansas', 'xml-for-google-merchant-center' ), 'value' => 'AR' ],
							[ 'text' => __( 'Wyoming', 'xml-for-google-merchant-center' ), 'value' => 'WY' ],
							[ 'text' => __( 'Washington', 'xml-for-google-merchant-center' ), 'value' => 'WA' ],
							[ 'text' => __( 'Vermont', 'xml-for-google-merchant-center' ), 'value' => 'VT' ],
							[ 'text' => __( 'Virginia', 'xml-for-google-merchant-center' ), 'value' => 'VA' ],
							[ 'text' => __( 'Wisconsin', 'xml-for-google-merchant-center' ), 'value' => 'WI' ],
							[ 'text' => __( 'Hawai', 'xml-for-google-merchant-center' ), 'value' => 'HI' ],
							[ 'text' => __( 'Delaware', 'xml-for-google-merchant-center' ), 'value' => 'DE' ],
							[ 'text' => __( 'Georgia', 'xml-for-google-merchant-center' ), 'value' => 'GA' ],
							[ 'text' => __( 'West Virginia', 'xml-for-google-merchant-center' ), 'value' => 'WV' ],
							[ 'text' => __( 'Illinois', 'xml-for-google-merchant-center' ), 'value' => 'IL' ],
							[ 'text' => __( 'Indiana', 'xml-for-google-merchant-center' ), 'value' => 'IN' ],
							[ 'text' => __( 'California', 'xml-for-google-merchant-center' ), 'value' => 'CA' ],
							[ 'text' => __( 'Kansas', 'xml-for-google-merchant-center' ), 'value' => 'KS' ],
							[ 'text' => __( 'Kentucky', 'xml-for-google-merchant-center' ), 'value' => 'KY' ],
							[ 'text' => __( 'Colorado', 'xml-for-google-merchant-center' ), 'value' => 'CO' ],
							[ 'text' => __( 'Connecticut', 'xml-for-google-merchant-center' ), 'value' => 'CT' ],
							[ 'text' => __( 'Louisiana', 'xml-for-google-merchant-center' ), 'value' => 'LA' ],
							[ 'text' => __( 'Massachusetts', 'xml-for-google-merchant-center' ), 'value' => 'MA' ],
							[ 'text' => __( 'Minnesota', 'xml-for-google-merchant-center' ), 'value' => 'MN' ],
							[ 'text' => __( 'Mississippi', 'xml-for-google-merchant-center' ), 'value' => 'MS' ],
							[ 'text' => __( 'Missouri', 'xml-for-google-merchant-center' ), 'value' => 'MO' ],
							[ 'text' => __( 'Michigan', 'xml-for-google-merchant-center' ), 'value' => 'MI' ],
							[ 'text' => __( 'Montana', 'xml-for-google-merchant-center' ), 'value' => 'MT' ],
							[ 'text' => __( 'Maine', 'xml-for-google-merchant-center' ), 'value' => 'ME' ],
							[ 'text' => __( 'Maryland', 'xml-for-google-merchant-center' ), 'value' => 'MD' ],
							[ 'text' => __( 'Nebraska', 'xml-for-google-merchant-center' ), 'value' => 'NE' ],
							[ 'text' => __( 'Nevada', 'xml-for-google-merchant-center' ), 'value' => 'NV' ],
							[ 'text' => __( 'New Hampshire', 'xml-for-google-merchant-center' ), 'value' => 'NH' ],
							[ 'text' => __( 'New Jersey', 'xml-for-google-merchant-center' ), 'value' => 'NJ' ],
							[ 'text' => __( 'New York', 'xml-for-google-merchant-center' ), 'value' => 'NY' ],
							[ 'text' => __( 'New Mexico', 'xml-for-google-merchant-center' ), 'value' => 'NM' ],
							[ 'text' => __( 'Ohio', 'xml-for-google-merchant-center' ), 'value' => 'OH' ],
							[ 'text' => __( 'Oklahoma', 'xml-for-google-merchant-center' ), 'value' => 'OK' ],
							[ 'text' => __( 'Oregon', 'xml-for-google-merchant-center' ), 'value' => 'OR' ],
							[ 'text' => __( 'Pennsylvania', 'xml-for-google-merchant-center' ), 'value' => 'PA' ],
							[ 'text' => __( 'Rhode Island', 'xml-for-google-merchant-center' ), 'value' => 'RI' ],
							[ 'text' => __( 'North Dakota', 'xml-for-google-merchant-center' ), 'value' => 'ND' ],
							[ 'text' => __( 'North Carolina', 'xml-for-google-merchant-center' ), 'value' => 'NC' ],
							[ 'text' => __( 'Tennessee', 'xml-for-google-merchant-center' ), 'value' => 'TN' ],
							[ 'text' => __( 'Texas', 'xml-for-google-merchant-center' ), 'value' => 'TX' ],
							[ 'text' => __( 'Florida', 'xml-for-google-merchant-center' ), 'value' => 'FL' ],
							[ 'text' => __( 'South Dakota', 'xml-for-google-merchant-center' ), 'value' => 'SD' ],
							[ 'text' => __( 'South Carolina', 'xml-for-google-merchant-center' ), 'value' => 'SC' ],
							[ 'text' => __( 'Utah', 'xml-for-google-merchant-center' ), 'value' => 'UT' ]
						],
						'tag_name' => 'tax',
						'tag_name_for_desc' => 'g:tax'
					]
				],
				[
					'opt_name' => 'xfgmc_tax_rate',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Tax rate', 'xml-for-google-merchant-center' ),
						'desc' => '[g:rate]',
						'placeholder' => '',
						'tag_name' => 'tax',
						'tag_name_for_desc' => 'g:tax'
					]
				],
				[
					'opt_name' => 'xfgmc_sipping_tax',
					'def_val' => 'no',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Shipping tax', 'xml-for-google-merchant-center' ),
						'desc' => '[g:tax_ship]',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'no', 'text' => __( 'No', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'yes', 'text' => __( 'Yes', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'tax',
						'tag_name_for_desc' => 'g:tax'
					]
				],
				[
					'opt_name' => 'xfgmc_tax_category',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Tax category', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'tax_category',
						'tag_name_for_desc' => 'g:tax_category'
					]
				],
				[
					'opt_name' => 'xfgmc_quantity',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Quantity of products', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'To make it work you must enable "Manage stock" and indicate "Stock quantity"',
							'xml-for-google-merchant-center'
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tag_name' => 'quantity',
						'tag_name_for_desc' => 'g:quantity'
					]
				],
				[
					'opt_name' => 'xfgmc_store_code',
					'def_val' => 'true',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'offer_data_tab',
					'data' => [
						'label' => __( 'Store code', 'xml-for-google-merchant-center' ),
						'desc' => sprintf( '%s. <a href="%s" target="_blank">%s</a>',
							__(
								'Required for products in local inventory ads, free local listings, and vehicle ads',
								'xml-for-google-merchant-center'
							),
							'//support.google.com/merchants/answer/13869896',
							__( 'Read more', 'xml-for-google-merchant-center' )
						),
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ],
						],
						'tag_name' => 'store_code',
						'tag_name_for_desc' => 'g:store_code'
					]
				],
				[
					'opt_name' => 'xfgmc_store_code_default_value',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'offer_data_tab',
					'data' => [
						'has_next' => false,
						'label' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'placeholder' => __( 'Default value', 'xml-for-google-merchant-center' ),
						'tag_name' => 'store_code',
						'tag_name_for_desc' => 'g:store_code'
					]
				],
				// ------------------- ФИЛЬТРАЦИЯ -------------------
				[
					'opt_name' => 'xfgmc_whot_export',
					'def_val' => 'all',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __( 'Whot export', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'key_value_arr' => [
							[
								'value' => 'all',
								'text' => __( 'Simple & Variable products', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'simple',
								'text' => __( 'Only simple products', 'xml-for-google-merchant-center' )
							],
							[
								'value' => 'variable',
								'text' => __( 'Only variable products', 'xml-for-google-merchant-center' )
							]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_replace_domain',
					'def_val' => '',
					'mark' => 'public',
					'type' => 'text',
					'tab' => 'filtration_tab',
					'data' => [
						'default_value' => false,
						'label' => __( 'Change the domain to', 'xml-for-google-merchant-center' ),
						'desc' => __(
							'The option allows you to change the domain of your site in the feed to any other',
							'xml-for-google-merchant-center'
						),
						'placeholder' => 'https://site.ru',
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_clear_get',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __(
							'Clear URL from GET-paramrs',
							'xml-for-google-merchant-center'
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_no_default_png_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __( 'Remove default.png from XML', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_del_identical_ids',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __(
							'Take steps to remove products with the same ID from the feed',
							'xml-for-google-merchant-center'
						),
						'desc' => sprintf( '%s. %s',
							__(
								'This is an experimental feature',
								'xml-for-google-merchant-center'
							),
							__(
								'It should only be used if you have an error related to the presence of products with the same identifier in the product feed',
								'xml-for-google-merchant-center'
							)
						),
						'woo_attr' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tr_class' => ''
					]
				],
				[
					'opt_name' => 'xfgmc_skip_products_without_pic',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __( 'Skip products without pictures', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						],
						'tr_class' => 'xfgmc_tr'
					]
				],
				[
					'opt_name' => 'xfgmc_skip_products_without_desc',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __( 'Skip products without description', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_skip_missing_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => sprintf( '%s (%s)',
							__( 'Skip missing products', 'xml-for-google-merchant-center' ),
							__( 'except for products for which a pre-order is permitted', 'xml-for-google-merchant-center' )
						),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				],
				[
					'opt_name' => 'xfgmc_skip_backorders_products',
					'def_val' => 'disabled',
					'mark' => 'public',
					'type' => 'select',
					'tab' => 'filtration_tab',
					'data' => [
						'label' => __( 'Skip backorders products', 'xml-for-google-merchant-center' ),
						'desc' => '',
						'woo_attr' => false,
						'default_value' => false,
						'key_value_arr' => [
							[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
							[ 'value' => 'enabled', 'text' => __( 'Enabled', 'xml-for-google-merchant-center' ) ]
						]
					]
				]
			];
		} else {
			$this->data_arr = $data_arr;
		}

		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;
			$currencies_arr = $WOOCS->get_currencies();

			if ( is_array( $currencies_arr ) ) {
				$array_keys = array_keys( $currencies_arr );
				for ( $i = 0; $i < count( $array_keys ); $i++ ) {
					$key_value_arr[] = [
						'value' => $array_keys[ $i ],
						'text' => $array_keys[ $i ]
					];
				}
			}
			$this->data_arr[] = [
				'opt_name' => 'xfgmc_wooc_currencies',
				'def_val' => '',
				'mark' => 'public',
				'type' => 'select',
				'tab' => 'shop_data_tab',
				'data' => [
					'label' => __( 'Feed currency', 'xml-for-google-merchant-center' ),
					'desc' => sprintf( '%s %s. %s.<br/><strong>%s:</strong> %s %s %s',
						__( 'You have plugin installed', 'xml-for-google-merchant-center' ),
						'WooCommerce Currency Switcher by PluginUs.NET. Woo Multi Currency and Woo Multi Pay',
						__( 'Indicate in what currency the prices should be', 'xml-for-google-merchant-center' ),
						__( 'Please note', 'xml-for-google-merchant-center' ),
						__( 'Google Merchant Center only supports the following currencies', 'xml-for-google-merchant-center' ),
						'RUR, RUB, UAH, BYN, KZT, UZS, USD, EUR',
						__( 'Choosing a different currency can lead to errors', 'xml-for-google-merchant-center' )
					),
					'woo_attr' => false,
					'default_value' => false,
					'key_value_arr' => $key_value_arr
				]
			];
		}

		$this->data_arr = apply_filters( 'xfgmc_f_set_default_feed_settings_result_arr', $this->get_data_arr() );

	}

	/**
	 * Get the plugin data array.
	 * 
	 * @return array
	 */
	public function get_data_arr() {
		return $this->data_arr;
	}

	/**
	 * Get options by name.
	 * 
	 * @param array $options_name_arr
	 * 
	 * @return array Example: `array([0] => opt_key1, [1] => opt_key2, ...)`.
	 */
	public function get_options( $options_name_arr = [] ) {

		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) && ! empty( $options_name_arr ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				if ( in_array( $this->get_data_arr()[ $i ]['opt_name'], $options_name_arr ) ) {
					$arr = $this->get_data_arr()[ $i ];
					$res_arr[] = $arr;
				}
			}
		}
		return $res_arr;

	}

	/**
	 * Get data for tabs.
	 * 
	 * @param string $tab_name Maybe: `main_tab`, `offer_data_tab`, `filtration_tab`, `offer_data_tab`,
	 * `shop_data_tab` and so on.
	 * 
	 * @return array Example: `array([0] => opt_key1, [1] => opt_key2, ...)`.
	 */
	public function get_data_for_tabs( $tab_name = '' ) {

		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $tab_name ) {
					case "main_tab":
					case "shop_data_tab":
					case "offer_data_tab":
					case "filtration_tab":

						if ( $this->get_data_arr()[ $i ]['tab'] === $tab_name ) {
							$arr = $this->get_data_arr()[ $i ];
							$res_arr[] = $arr;
						}

						break;
					default:

						$res_arr = apply_filters(
							'xfgmc_f_data_for_tabs_before',
							$res_arr,
							$tab_name, $this->get_data_arr()[ $i ]
						);

						if ( $this->get_data_arr()[ $i ]['tab'] === $tab_name ) {
							$arr = $this->get_data_arr()[ $i ];
							$res_arr[] = $arr;
						}

						$res_arr = apply_filters(
							'xfgmc_f_data_for_tabs_after',
							$res_arr,
							$tab_name, $this->get_data_arr()[ $i ]
						);

				}
			}
		}
		return $res_arr;

	}

	/**
	 * Get plugin options name.
	 * 
	 * @param string $whot Maybe: `all`, `public` or `private`.
	 * 
	 * @return array Example: `array([0] => opt_key1, [1] => opt_key2, ...)`.
	 */
	public function get_opts_name( $whot = '' ) {

		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
						}
						break;
					default:
						$res_arr[] = $this->get_data_arr()[ $i ]['opt_name'];
				}
			}
		}
		return $res_arr;

	}

	/**
	 * Get plugin options name and default date (array).
	 * 
	 * @param string $whot Maybe: `all`, `public` or `private`.
	 * 
	 * @return array Example: `array(opt_name1 => opt_val1, opt_name2 => opt_val2, ...)`.
	 */
	public function get_opts_name_and_def_date( $whot = 'all' ) {

		$res_arr = [];
		if ( ! empty( $this->get_data_arr() ) ) {
			for ( $i = 0; $i < count( $this->get_data_arr() ); $i++ ) {
				switch ( $whot ) {
					case "public":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'public' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					case "private":
						if ( $this->get_data_arr()[ $i ]['mark'] === 'private' ) {
							$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
						}
						break;
					default:
						$res_arr[ $this->get_data_arr()[ $i ]['opt_name'] ] = $this->get_data_arr()[ $i ]['def_val'];
				}
			}
		}
		return $res_arr;

	}

	/**
	 * Get plugin options name and default date (stdClass object).
	 * 
	 * @param string $whot
	 * 
	 * @return array<stdClass>
	 */
	public function get_opts_name_and_def_date_obj( $whot = 'all' ) {

		$source_arr = $this->get_opts_name_and_def_date( $whot );

		$res_arr = [];
		foreach ( $source_arr as $key => $value ) {
			$obj = new stdClass();
			$obj->name = $key;
			$obj->opt_def_value = $value;
			$res_arr[] = $obj; // unit obj
			unset( $obj );
		}
		return $res_arr;

	}

	/**
	 * Get array for the `xfgmc_picture` plugin option.
	 * 
	 * @return array
	 */
	private function get_registered_image_sizes() {

		$res_arr = [
			[ 'value' => 'disabled', 'text' => __( 'Disabled', 'xml-for-google-merchant-center' ) ],
			[ 'value' => 'full', 'text' => __( 'Full size (default)', 'xml-for-google-merchant-center' ) ]
		];
		$sizes = wp_get_registered_image_subsizes();
		foreach ( $sizes as $key => $val ) {
			if ( is_array( $val['crop'] ) ) {
				$crop = '';
			} else {
				$crop = sprintf( ' - %s',
					__( 'сrop thumbnail to exact dimensions', 'xml-for-google-merchant-center' )
				);
			}
			$cur_size_arr = [
				'value' => $key,
				'text' => sprintf( '%sx%s%s (%s)', $val['width'], $val['height'], $crop, $key )
			];
			array_push( $res_arr, $cur_size_arr );
			unset( $cur_size_arr );
		}
		return $res_arr;

	}

}