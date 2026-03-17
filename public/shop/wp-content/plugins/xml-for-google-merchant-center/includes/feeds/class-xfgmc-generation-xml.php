<?php

/**
 * This class is responsible for creating the feed.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.8 (19-11-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * This class is responsible for creating the feed.
 * 
 * Depends on the classes: `XFGMC_Get_Unit`, `XFGMC_Get_Paired_Tag`, `WP_Query`, `ZipArchive`, `DOMDocument`.
 * Depends on the constants: `XFGMC_SITE_UPLOADS_DIR_PATH`, `XFGMC_SITE_UPLOADS_URL`
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class XFGMC_Generation_XML {

	/**
	 * Feed ID.
	 * @var string
	 */
	protected $feed_id;

	/**
	 * Status sborki.
	 * @var int
	 */
	protected $status_sborki;

	/**
	 * Step export.
	 * @var int
	 */
	protected $step_export;

	/**
	 * XML code.
	 * @var string
	 */
	protected $result_xml = '';

	/**
	 * Starts feed generation.
	 * 
	 * @param string|int $feed_id - Required
	 */
	public function __construct( $feed_id ) {

		$this->feed_id = (string) $feed_id;
		$this->status_sborki = (int) common_option_get(
			'xfgmc_status_sborki',
			-1,
			$this->get_feed_id(),
			'xfgmc'
		);
		$this->step_export = (int) common_option_get(
			'xfgmc_step_export',
			25,
			$this->get_feed_id(),
			'xfgmc'
		);

	}

	/**
	 * Run the quick generation of the feed.
	 * 
	 * @return void
	 */
	public function quick_generation() {

		$date_sborki_start = current_time( 'Y-m-d H:i' );
		common_option_upd(
			'xfgmc_date_sborki_start',
			$date_sborki_start,
			'no',
			$this->get_feed_id(),
			'xfgmc'
		);
		$result_xml = $this->get_feed_header();
		new XFGMC_Write_File( $result_xml, '-1.tmp', $this->get_feed_id() );
		$result_xml = $this->get_feed_footer( 'quick_generation(); line ' . __LINE__ );
		// обновляем временный файл фида
		if ( is_multisite() ) {
			$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-%2$s-tmp.xml',
				$this->get_prefix_feed(),
				get_current_blog_id()
			);
		} else {
			$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-0-tmp.xml',
				$this->get_prefix_feed()
			);
		}
		$r = new XFGMC_Write_File( $result_xml, $feed_tmp_full_file_name, $this->get_feed_id() );
		$result = $r->get_result();
		if ( false === $result ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s. (%3$s); %4$s: %5$s; %6$s: %7$s',
				$this->get_feed_id(),
				__( 'An error occurred when writing the temporary feed file', 'xml-for-google-merchant-center' ),
				$feed_tmp_full_file_name,
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
		} else {
			$res_rename = $this->rename_feed_file();
			if ( true === $res_rename ) {
				$this->archiving();
				common_option_upd(
					'xfgmc_date_sborki_end',
					current_time( 'Y-m-d H:i' ),
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
				common_option_upd(
					'xfgmc_date_successful_feed_update',
					current_time( 'timestamp', 1 ),
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
			}
		}

	}

	/**
	 * Run the creation of the feed.
	 * 
	 * @return void
	 */
	public function run() {

		$result_xml = '';

		switch ( $this->get_status_sborki() ) {
			case -1:

				// сборка завершена
				new XFGMC_Error_Log( sprintf( 'FEED #%1$s; `status_sborki` = -1. %2$s; %3$s: %4$s; %5$s: %6$s',
					$this->get_feed_id(),
					__( 'Just in case, we disable the CRON task', 'xml-for-google-merchant-center' ),
					__( 'File', 'xml-for-google-merchant-center' ),
					'class-xfgmc-generation-xml.php',
					__( 'Line', 'xml-for-google-merchant-center' ),
					__LINE__
				) );
				wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $this->get_feed_id() ] );

				break;
			case 1:

				/**
				 * сборка начата. 
				 * на этом шаге мы:
				 *    - обнуляем счётчик обработанных элементов в фиде
				 *    - создаём пустой временный файл фида `/xfgmc/feed{1}/{1}-feed-xml-0-tmp.xml`
				 *    - создаём временный файл, в котором будет заголовок фида `/xfgmc/feed{1}/-1.tmp`
				 *    - создаём временный файл с id-шниками товаров, попавших в фид `/xfgmc/feed{1}/ids-in-xml-feed-{1}.tmp`
				 */

				$date_sborki_start = current_time( 'Y-m-d H:i' );
				common_option_upd(
					'xfgmc_date_sborki_start',
					$date_sborki_start,
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
				common_option_upd(
					'xfgmc_date_sborki_end',
					sprintf( '%s (%s: %s)',
						__( 'Not completed', 'xml-for-google-merchant-center' ),
						__( 'The version used is from', 'xml-for-google-merchant-center' ),
						common_option_get(
							'xfgmc_date_sborki_end',
							$date_sborki_start,
							$this->get_feed_id(),
							'xfgmc'
						)
					),
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
				new XFGMC_Error_Log( sprintf( 'FEED #%1$s; INFO: `status_sborki` = 1. %2$s (%3$s); %4$s: %5$s; %6$s: %7$s',
					$this->get_feed_id(),
					__( 'We started creating a feed', 'xml-for-google-merchant-center' ),
					$date_sborki_start,
					__( 'File', 'xml-for-google-merchant-center' ),
					'class-xfgmc-generation-xml.php',
					__( 'Line', 'xml-for-google-merchant-center' ),
					__LINE__
				) );

				// обнуляем счётчик обработанных элементов фида
				univ_option_upd(
					'xfgmc_last_element_feed_' . $this->get_feed_id(),
					0,
					'no'
				);

				// создаём пустой временный файл фида
				if ( is_multisite() ) {
					$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-%2$s-tmp.xml',
						$this->get_prefix_feed(),
						get_current_blog_id()
					);
				} else {
					$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-0-tmp.xml',
						$this->get_prefix_feed()
					);
				}
				$r = new XFGMC_Write_File( $result_xml, $feed_tmp_full_file_name, $this->get_feed_id() );
				$result = $r->get_result();
				if ( false === $result ) {
					new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s `%3$s`. %4$s; %5$s: %6$s; %7$s: %8$s',
						$this->get_feed_id(),
						__( 'An error occurred while creating an empty temporary feed file', 'xml-for-google-merchant-center' ),
						$feed_tmp_full_file_name,
						__( 'The creation of the feed has been stopped', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$this->stop();
					return;
				}

				// создаём временный файл, в котором будет заголовок фида
				$result_xml = $this->get_feed_header();
				$r = new XFGMC_Write_File( $result_xml, '-1.tmp', $this->get_feed_id() );
				if ( false === $result ) {
					new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s `%3$s`. %4$s; %5$s: %6$s; %7$s: %8$s',
						$this->get_feed_id(),
						__( 'An error occurred while creating a temporary feed file', 'xml-for-google-merchant-center' ),
						'-1.tmp',
						__( 'The creation of the feed has been stopped', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$this->stop();
					return;
				}

				// создаём временный файл с id-шниками товаров, попавших в фид
				$r = new XFGMC_Write_File(
					'-1;;;' . PHP_EOL,
					sprintf( 'ids-in-xml-feed-%s.tmp', $this->get_feed_id() ),
					$this->get_feed_id(),
					'create',
					XFGMC_PLUGIN_UPLOADS_DIR_PATH,
					'no_trim' // ! сохраняем символ переноса на другую строку
				);
				if ( false === $result ) {
					new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s `%3$s`. %4$s; %5$s: %6$s; %7$s: %8$s',
						$this->get_feed_id(),
						__( 'An error occurred while creating an empty temporary feed file', 'xml-for-google-merchant-center' ),
						sprintf( 'ids-in-xml-feed-%s.tmp', $this->get_feed_id() ),
						__( 'The creation of the feed has been stopped', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$this->stop();
				}

				$planning_result = XFGMC_Admin::cron_sborki_task_planning( $this->get_feed_id() );
				if ( false === $planning_result ) {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; ERROR: %2$s `xfgmc_cron_sborki` %3$s 1; %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( 'Failed to schedule a CRON task', 'xml-for-google-merchant-center' ),
						__( 'on step', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
				} else {
					$this->set_status_sborki( 2 );
				}

				break;
			case 2:

				// создание временных файлов товаров, входящих в фид
				$last_element_feed = (int) univ_option_get(
					'xfgmc_last_element_feed_' . $this->get_feed_id(),
					0
				);
				new XFGMC_Error_Log(
					sprintf( 'FEED #%1$s; INFO: %2$s (status_sborki = %3$s, last_element_feed = %4$s); %5$s: %6$s; %7$s: %8$s',
						$this->get_feed_id(),
						__( 'We continue to create the feed', 'xml-for-google-merchant-center' ),
						$this->get_status_sborki(),
						$last_element_feed,
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					)
				);

				// сразу запланируем задачу через 32 секунды
				$planning_result = XFGMC_Admin::cron_sborki_task_planning( $this->get_feed_id(), 32 );
				if ( false === $planning_result ) {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; ERROR: %2$s `xfgmc_cron_sborki` %3$s 2; %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( 'Failed to schedule a CRON task', 'xml-for-google-merchant-center' ),
						__( 'on step', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
				}

				$time_start = time();
				$step_export = (int) common_option_get(
					'xfgmc_step_export',
					500,
					$this->get_feed_id(),
					'xfgmc'
				);
				$args = apply_filters(
					'xfgmc_f_query_args',
					[
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => $step_export,
						'offset' => $last_element_feed,
						'relation' => 'AND',
						'orderby' => 'ID',
						'fields' => 'ids'
					],
					$this->get_feed_id()
				);
				new XFGMC_Error_Log( sprintf(
					'FEED #%1$s; %2$s =>',
					$this->get_feed_id(),
					__( 'Sending a request to the database', 'xml-for-google-merchant-center' )
				) );
				new XFGMC_Error_Log( $args );
				new XFGMC_Error_Log( json_encode( $args ) );
				$products_query = new \WP_Query( $args );
				$query_time = (int) time() - $time_start;
				new XFGMC_Error_Log( sprintf(
					'FEED #%1$s; %2$s: %3$s; %4$s: %5$s; %6$s: %7$s',
					$this->get_feed_id(),
					__( 'The query time to the database was', 'xml-for-google-merchant-center' ),
					$query_time,
					__( 'File', 'xml-for-google-merchant-center' ),
					'class-xfgmc-generation-xml.php',
					__( 'Line', 'xml-for-google-merchant-center' ),
					__LINE__
				) );
				$script_execution_time = (int) common_option_get(
					'xfgmc_script_execution_time',
					'26',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( $query_time > $script_execution_time ) {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; WARNING: %2$s: %3$s > %4$s. %5$s "%6$s" %7$s %8$s %9$s; %10$s: %11$s; %12$s: %13$s',
						$this->get_feed_id(),
						__( 'The query time to the database was', 'xml-for-google-merchant-center' ),
						$query_time,
						$script_execution_time,
						__(
							'If you experience freezes when creating the feed, try increasing the',
							'xml-for-google-merchant-center'
						),
						__( 'The maximum script execution time', 'xml-for-google-merchant-center' ),
						__( 'parameter to', 'xml-for-google-merchant-center' ),
						$query_time + 5,
						__( 'points', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
				}
				if ( $products_query->have_posts() ) {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; %2$s: %3$s; %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( 'The number of records returned by the server', 'xml-for-google-merchant-center' ),
						count( $products_query->posts ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$date_successful_feed_update = common_option_get(
						'xfgmc_date_successful_feed_update',
						50,
						$this->get_feed_id(),
						'xfgmc'
					);
					$date_save_set = common_option_get(
						'xfgmc_date_save_set',
						50,
						$this->get_feed_id(),
						'xfgmc'
					);
					for ( $i = 0; $i < count( $products_query->posts ); $i++ ) {
						$product_id = $products_query->posts[ $i ];
						new XFGMC_Error_Log( sprintf(
							'FEED #%1$s; INFO: %2$s ID = %3$s; %4$s: %5$s; %6$s: %7$s',
							$this->get_feed_id(),
							__( 'Getting started with the product', 'xml-for-google-merchant-center' ),
							$product_id,
							__( 'File', 'xml-for-google-merchant-center' ),
							'class-xfgmc-generation-xml.php',
							__( 'Line', 'xml-for-google-merchant-center' ),
							__LINE__
						) );
						$result_get_unit_obj = new XFGMC_Get_Unit(
							$product_id,
							$this->get_feed_id(),
							$date_successful_feed_update, // ! позволяет нам в случае чего вернуть кэш-данные товара
							$date_save_set // ! позволяет нам в случае чего вернуть кэш-данные товара
						);
						$result_xml = $result_get_unit_obj->get_result();
						// Remove hex and control characters from PHP string
						$result_xml = xfgmc_remove_special_characters( $result_xml );
						new XFGMC_Write_File(
							$result_xml,
							sprintf( '%s.tmp', $product_id ),
							$this->get_feed_id()
						);
						$ids_in_xml = $result_get_unit_obj->get_ids_in_xml();
						$ids_in_xml = sprintf( $ids_in_xml, $product_id, PHP_EOL );
						new XFGMC_Write_File(
							$ids_in_xml,
							sprintf( 'ids-in-xml-feed-%s.tmp', $this->get_feed_id() ),
							$this->get_feed_id(),
							'append'
						);

						$time_end = time();
						$time = $time_end - $time_start;
						if ( $time > $script_execution_time ) {
							break;
						} else {
							$last_element_feed++;
						}
						//new XFGMC_Error_Log(
						//	'$product_id = ' . $product_id
						//);
						// usleep( 200000 ); // притормозим на 0,2 секунды
					}
					univ_option_upd(
						'xfgmc_last_element_feed_' . $this->get_feed_id(),
						$last_element_feed,
						'no'
					);
				} else {
					new XFGMC_Error_Log( sprintf(
						'FEED #%1$s; %2$s: 0; %3$s: %4$s; %5$s: %6$s',
						$this->get_feed_id(),
						__( 'The number of records returned by the server', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$this->set_status_sborki( 3 );
				}

				break;
			case 3:

				// сразу запланируем задачу через 32 секунды
				$planning_result = XFGMC_Admin::cron_sborki_task_planning( $this->get_feed_id(), 32 );

				// постов нет, пишем концовку файла
				$last_element_feed = (int) univ_option_get(
					'xfgmc_last_element_feed_' . $this->get_feed_id(),
					0
				);
				new XFGMC_Error_Log(
					sprintf( 'FEED #%1$s; INFO: %2$s (status_sborki = %3$s, last_element_feed = %4$s); %5$s: %6$s; %7$s: %8$s',
						$this->get_feed_id(),
						__( 'We continue to create the feed', 'xml-for-google-merchant-center' ),
						$this->get_status_sborki(),
						$last_element_feed,
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					)
				);
				$result_xml = $this->get_feed_footer( 'run(); case 3; line ' . __LINE__ );
				// обновляем временный файл фида
				if ( is_multisite() ) {
					$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-%2$s-tmp.xml',
						$this->get_prefix_feed(),
						get_current_blog_id()
					);
				} else {
					$feed_tmp_full_file_name = sprintf( '%1$s-feed-xml-0-tmp.xml',
						$this->get_prefix_feed()
					);
				}
				$r = new XFGMC_Write_File( $result_xml, $feed_tmp_full_file_name, $this->get_feed_id() );
				$result = $r->get_result();
				if ( false === $result ) {
					new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s. %3$s; %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( 'In this step, an error occurred while creating a temporary feed file', 'xml-for-google-merchant-center' ),
						__( 'The creation of the feed has been stopped', 'xml-for-google-merchant-center' ),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
					$this->stop();
					return;
				}
				$res_rename = $this->rename_feed_file();
				if ( true === $res_rename ) {
					$this->set_status_sborki( 4 );
				} else {
					$this->stop();
				}

				break;
			case 4:

				new XFGMC_Error_Log(
					sprintf( 'FEED #%1$s; INFO: %2$s (status_sborki = %3$s); %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( 'Checking whether the feed needs to be archived', 'xml-for-google-merchant-center' ),
						$this->get_status_sborki(),
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					)
				);
				$this->archiving();
				common_option_upd(
					'xfgmc_date_sborki_end',
					current_time( 'Y-m-d H:i' ),
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
				common_option_upd(
					'xfgmc_date_successful_feed_update',
					current_time( 'timestamp', 1 ),
					'no',
					$this->get_feed_id(),
					'xfgmc'
				);
				$this->stop();

				break;
			default:

				$this->stop();

		} // end switch ( $this->get_status_sborki() )

	} // end run()

	/**
	 * Get feed header.
	 * 
	 * @see https://support.google.com/merchants/answer/14987622
	 * 
	 * @return string
	 */
	protected function get_feed_header() {

		$xml_rules = common_option_get(
			'xfgmc_xml_rules',
			'merchant_center',
			$this->get_feed_id(),
			'xfgmc'
		);
		$result_xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$result_xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL;
		$result_xml .= new XFGMC_Get_Open_Tag( 'channel' );
		$shop_name = stripslashes(
			common_option_get( 'xfgmc_shop_name',
				get_bloginfo( 'name' ),
				$this->get_feed_id(),
				'xfgmc' )
		);
		$result_xml .= new XFGMC_Get_Paired_Tag( 'title', esc_html( $shop_name ) );
		$shop_description = stripslashes(
			common_option_get(
				'xfgmc_shop_description',
				'',
				$this->get_feed_id(),
				'xfgmc' )
		);
		if ( ! empty( $shop_description ) ) {
			$result_xml .= new XFGMC_Get_Paired_Tag( 'description', esc_html( $shop_description ) );
		}
		$res_home_url = apply_filters( 'xfgmc_home_url', home_url( '/' ), $this->get_feed_id() );
		$result_xml .= new XFGMC_Get_Paired_Tag( 'link', xfgmc_replace_domain( $res_home_url, $this->get_feed_id() ) );
		$result_xml = apply_filters(
			'xfgmc_f_before_offers',
			$result_xml,
			[
				'xml_rules' => $xml_rules
			],
			$this->get_feed_id()
		);
		return $result_xml;

	}

	/**
	 * Get body of XML feed. All tags between the `offers` tag.
	 * 
	 * @param string $result_xml
	 * 
	 * @return string
	 */
	protected function get_feed_body( $result_xml = '' ) {

		$ids_in_xml_path = sprintf(
			'%1$s/xfgmc/feed%2$s/ids-in-xml-feed-%2$s.tmp',
			XFGMC_SITE_UPLOADS_DIR_PATH,
			$this->get_feed_id()
		);
		$file_content = file_get_contents( $ids_in_xml_path );
		if ( false === $file_content || $file_content == '' ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s (path = %3$s); %4$s: %5$s; %6$s: %7$s',
				$this->get_feed_id(),
				__( 'The list of product IDs in the feed is empty or the temporary file has been deleted', 'xml-for-google-merchant-center' ),
				$ids_in_xml_path,
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			return $result_xml;
		}
		$ids_in_xml_arr = $this->get_ids_in_xml_arr( $file_content );

		$name_dir = XFGMC_SITE_UPLOADS_DIR_PATH . '/xfgmc/feed' . $this->get_feed_id();
		foreach ( $ids_in_xml_arr as $key => $value ) {
			$product_id = (int) $key;
			$filename = sprintf( '%s/%s.tmp', $name_dir, $product_id );
			$result_xml .= file_get_contents( $filename );
		}

		$offer_count = count( $ids_in_xml_arr ); // число товаров попавших в фид
		common_option_upd(
			'xfgmc_count_products_in_feed',
			$offer_count,
			'no',
			$this->get_feed_id(),
			'xfgmc'
		);

		return $result_xml;

	}

	/**
	 * Get feed footer.
	 * 
	 * @param string $tracing For debug.
	 * 
	 * @return string
	 */
	protected function get_feed_footer( $tracing = '' ) {

		$result_xml = '';
		$result_xml .= $this->get_feed_body( $result_xml );
		if ( empty( $result_xml ) ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s (%3$s); %4$s: %5$s; %6$s: %7$s',
				$this->get_feed_id(),
				__( 'Data loss when writing a feed file', 'xml-for-google-merchant-center' ),
				$tracing,
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			return $result_xml;
		}

		$result_xml .= new XFGMC_Get_Closed_Tag( 'channel' );
		$result_xml .= new XFGMC_Get_Closed_Tag( 'rss' );
		return $result_xml;

	}

	/**
	 * Stops the creation of the feed
	 * 
	 * @return void
	 */
	public function stop() {

		if ( 'once' === common_option_get( 'xfgmc_run_cron', 'disabled', $this->get_feed_id(), 'xfgmc' ) ) {
			// если была одноразовая сборка - переводим переключатель в `отключено`
			common_option_upd( 'xfgmc_run_cron', 'disabled', 'no', $this->get_feed_id(), 'xfgmc' );
		}
		$this->set_status_sborki( -1 );
		wp_clear_scheduled_hook( 'xfgmc_cron_sborki', [ $this->get_feed_id() ] );
		do_action( 'xfgmc_after_construct', $this->get_feed_id(), 'full' );

	}

	/**
	 * Getting product IDs in an XML feed.
	 * 
	 * @param string $file_content
	 * 
	 * @return array
	 */
	protected function get_ids_in_xml_arr( $file_content ) {

		/**
		 * $file_content - содержимое файла (Обязательный параметр)
		 * Возвращает массив в котором ключи - это id товаров в БД WordPress, попавшие в фид
		 */
		$res_arr = [];
		$file_content_string_arr = explode( PHP_EOL, $file_content );
		for ( $i = 0; $i < count( $file_content_string_arr ) - 1; $i++ ) {
			$r_arr = explode( ';', $file_content_string_arr[ $i ] );
			$res_arr[ $r_arr[0] ] = '';
		}
		return $res_arr;

	}

	/**
	 * Set and save status sborki.
	 * 
	 * @param int $status_sborki
	 * 
	 * @return void
	 */
	private function set_status_sborki( $status_sborki ) {

		common_option_upd(
			'xfgmc_status_sborki',
			(string) $status_sborki,
			'no',
			$this->get_feed_id(),
			'xfgmc'
		);
		$this->status_sborki = (int) $status_sborki;

	}

	/**
	 * Get feed ID.
	 * 
	 * @return string
	 */
	protected function get_feed_id() {
		return $this->feed_id;
	}

	/**
	 * Get prefix of feed.
	 * 
	 * @return string
	 */
	protected function get_prefix_feed() {
		if ( $this->get_feed_id() === '1' ) {
			return '';
		} else {
			return $this->get_feed_id();
		}
	}

	/**
	 * Get status sborki.
	 * 
	 * @return string
	 */
	protected function get_status_sborki() {
		return $this->status_sborki;
	}

	/**
	 * Get step export.
	 * 
	 * @return string
	 */
	protected function get_step_export() {
		return $this->step_export;
	}

	/**
	 * Перименовывает временный файл фида `/xfgmc/feed{1}/{1}-feed-xml-0-tmp.xml` в основной.
	 * 
	 * @return array|false
	 */
	private function rename_feed_file() {

		if ( empty( $this->get_prefix_feed() ) ) {
			$folder_index = '1';
		} else {
			$folder_index = $this->get_prefix_feed();
		}
		if ( is_multisite() ) {
			$feed_tmp_full_file_name = sprintf( '%1$s/feed%2$s/%3$s-feed-xml-%4$s-tmp.xml',
				XFGMC_PLUGIN_UPLOADS_DIR_PATH,
				$folder_index,
				$this->get_prefix_feed(),
				get_current_blog_id()
			);
		} else {
			$feed_tmp_full_file_name = sprintf( '%1$s/feed%2$s/%3$s-feed-xml-0-tmp.xml',
				XFGMC_PLUGIN_UPLOADS_DIR_PATH,
				$folder_index,
				$this->get_prefix_feed()
			);
		}

		$feed_file_meta_obj = new XFGMC_Feed_File_Meta( $this->get_feed_id() );

		// /home/site.ru/public_html/wp-content/uploads/feed-xml-0.xml
		$feed_new_path = sprintf( '%s/%s',
			XFGMC_SITE_UPLOADS_DIR_PATH,
			$feed_file_meta_obj->get_feed_full_filename( true )
		);

		// https://site.ru/wp-content/uploads/feed-xml-2.xml
		$feed_new_url = sprintf(
			'%1$s/%2$s',
			XFGMC_SITE_UPLOADS_URL,
			$feed_file_meta_obj->get_feed_full_filename()
		);

		// старый адрес фида /home/site.ru/public_html/wp-content/uploads/feed-xml-0.xml
		$feed_old_path = common_option_get(
			'xfgmc_feed_path',
			'',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( ! empty( $feed_old_path ) ) {
			// Удаляем старый файл фида $feed_old_path
			if ( file_exists( $feed_old_path ) ) {
				$res = unlink( $feed_old_path );
				if ( true !== $res ) {
					new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s `%3$s`; %4$s: %5$s; %6$s: %7$s',
						$this->get_feed_id(),
						__( "Couldn't delete the old feed file", "xml-for-google-merchant-center" ),
						$feed_old_path,
						__( 'File', 'xml-for-google-merchant-center' ),
						'class-xfgmc-generation-xml.php',
						__( 'Line', 'xml-for-google-merchant-center' ),
						__LINE__
					) );
				}
			}
		}

		if ( false === rename( $feed_tmp_full_file_name, $feed_new_path ) ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s %3$s %4$s %5$s; %6$s: %7$s; %8$s: %9$s',
				$this->get_feed_id(),
				__( "I can't rename the feed file from", "xml-for-google-merchant-center" ),
				$feed_tmp_full_file_name,
				__( "to", "xml-for-google-merchant-center" ),
				$feed_new_path,
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			return false;
		} else {
			common_option_upd(
				'xfgmc_feed_path',
				$feed_new_path,
				'no',
				$this->get_feed_id(),
				'xfgmc'
			);
			common_option_upd(
				'xfgmc_feed_url',
				$feed_new_url,
				'no',
				$this->get_feed_id(),
				'xfgmc'
			);
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; SUCCESS: %2$s (path = %3$s; url = %4$s); %5$s: %6$s; %7$s: %8$s',
				$this->get_feed_id(),
				__( "The temporary feed file has been successfully renamed to the main one", "xml-for-google-merchant-center" ),
				$feed_tmp_full_file_name,
				$feed_new_url,
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			return true;
		}

	}

	/**
	 * Archiving to ZIP.
	 * 
	 * @return void
	 */
	private function archiving() {

		$archive_to_zip = common_option_get(
			'xfgmc_archive_to_zip',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $archive_to_zip === 'enabled' ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; %2$s; %3$s: %4$s; %5$s: %6$s',
				$this->get_feed_id(),
				__( 'Starting archiving the feed', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );

			$feed_file_meta_obj = new XFGMC_Feed_File_Meta( $this->get_feed_id() );

			$zip = new ZipArchive();
			$zip->open(
				XFGMC_SITE_UPLOADS_DIR_PATH . '/' . $feed_file_meta_obj->get_feed_full_filename(),
				ZipArchive::CREATE | ZipArchive::OVERWRITE
			);
			$zip->addFile(
				sprintf( '%s/%s',
					XFGMC_SITE_UPLOADS_DIR_PATH,
					$feed_file_meta_obj->get_feed_full_filename( true )
				),
				$feed_file_meta_obj->get_feed_full_filename( true )
			);
			$zip->close();
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; SUCCESS: %2$s; %3$s: %4$s; %5$s: %6$s',
				$this->get_feed_id(),
				__( 'The archiving was successful', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'class-xfgmc-generation-xml.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
		}

	}

}