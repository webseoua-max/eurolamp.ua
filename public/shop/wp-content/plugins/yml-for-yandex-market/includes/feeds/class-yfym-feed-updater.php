<?php

/**
 * Handles feed updates triggered by product changes.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.2.0 (03-02-2026)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 */

/**
 * Class for managing feed updates when products are modified.
 *
 * Responsible for:
 * - Running feed update on product save
 * - Running feed update on stock change
 *
 * @package Y4YM
 * @subpackage Y4YM/includes
 */
class Y4YM_Feed_Updater {

	/**
	 * Checks whether the feed should be updated when a product is updated,
	 * and starts the feed generation process if needed.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public static function run_feeds_upd( $post_id ) {

		$settings_arr = univ_option_get( 'y4ym_settings_arr' );
		$settings_arr_keys_arr = array_keys( $settings_arr );
		for ( $i = 0; $i < count( $settings_arr_keys_arr ); $i++ ) {

			$feed_id = (string) $settings_arr_keys_arr[ $i ]; // ! для правильности работы важен тип string
			$run_cron = common_option_get(
				'y4ym_run_cron',
				'disabled',
				$feed_id,
				'y4ym'
			);
			$ufup = common_option_get(
				'y4ym_ufup',
				'disabled',
				$feed_id,
				'y4ym'
			);
			if ( $run_cron === 'disabled' || $ufup === 'disabled' ) {
				Y4YM_Error_Log::record( sprintf(
					'FEED #%1$s; INFO: %2$s ($run_cron = %3$s; $ufup = %4$s); %5$s: %6$s; %7$s: %8$s',
					$feed_id,
					__(
						'Creating a cache file is not required for this type',
						'yml-for-yandex-market'
					),
					$run_cron,
					$ufup,
					__( 'File', 'yml-for-yandex-market' ),
					'class-yfym-feed-updater.php',
					__( 'Line', 'yml-for-yandex-market' ),
					__LINE__
				) );
				continue;
			}

			$do_cash_file = common_option_get(
				'y4ym_do_cash_file',
				'enabled',
				$feed_id, 'y4ym'
			);
			if ( $do_cash_file === 'enabled' || $ufup === 'enabled' ) {
				// если в настройках включено создание кэш-файлов в момент сохранения товара
				// или нужно запускать обновление фида при перезаписи файла
				$result_get_unit_obj = new Y4YM_Get_Unit( $post_id, $feed_id );
				$result_xml = $result_get_unit_obj->get_result();
				// Remove hex and control characters from PHP string
				$result_xml = y4ym_remove_special_characters( $result_xml );
				new Y4YM_Write_File(
					$result_xml,
					sprintf( '%s.tmp', $post_id ),
					$feed_id
				);
			}

			// нужно ли запускать обновление фида при перезаписи файла
			if ( $ufup === 'enabled' ) {
				$status_sborki = (int) common_option_get(
					'y4ym_status_sborki',
					-1,
					$feed_id,
					'y4ym'
				);
				if ( $status_sborki === -1 ) {
					Y4YM_Error_Log::record( sprintf(
						'FEED #%1$s; INFO: %2$s ($i = %3$s; $ufup = %4$s); %5$s: %6$s; %7$s: %8$s',
						$feed_id,
						__(
							'Starting a quick feed build',
							'yml-for-yandex-market'
						),
						$i,
						$ufup,
						__( 'File', 'yml-for-yandex-market' ),
						'class-yfym-feed-updater.php',
						__( 'Line', 'yml-for-yandex-market' ),
						__LINE__
					) );
					clearstatcache(); // очищаем кэш дат файлов
					$generation = new Y4YM_Generation_XML( $feed_id );
					$generation->quick_generation();
				}
			}

		} // end for

	}

	/**
	 * Fires when stock reduced to a specific line item.
	 * 
	 * Function for `woocommerce_reduce_order_item_stock` action-hook.
	 * 
	 * @param WC_Order_Item_Product $item Order item data.
	 * @param array $change  Change Details.
	 * @param WC_Order $order  Order data.
	 *
	 * @return void
	 */
	public function check_update_feed_stock_change( $item, $change, $order ) {

		$settings_arr = univ_option_get( 'y4ym_settings_arr' );
		$settings_arr_keys_arr = array_keys( $settings_arr );
		for ( $i = 0; $i < count( $settings_arr_keys_arr ); $i++ ) {

			$feed_id = (string) $settings_arr_keys_arr[ $i ]; // ! для правильности работы важен тип string
			$run_cron = common_option_get(
				'y4ym_run_cron',
				'disabled',
				$feed_id,
				'y4ym'
			);
			$upd_feed_after_stock_change = common_option_get(
				'y4ym_upd_feed_after_stock_change',
				'disabled',
				$feed_id,
				'y4ym'
			);
			if ( $run_cron === 'disabled' || $upd_feed_after_stock_change === 'disabled' ) {
				continue;
			}
			if ( $upd_feed_after_stock_change === 'enabled' ) {
				$status_sborki = (int) common_option_get(
					'y4ym_status_sborki',
					-1,
					$feed_id,
					'y4ym'
				);
				if ( $status_sborki === -1 ) {
					$planning_result = Y4YM_Cron_Manager::cron_starting_feed_creation_task_planning( $feed_id );
					if ( true === $planning_result ) {
						Y4YM_Error_Log::record( sprintf( 'FEED #%1$s; %2$s; %3$s: %4$s; %5$s: %6$s',
							$feed_id,
							__(
								'After changing the stock product the task of creating the feed has been queued for completion',
								'yml-for-yandex-market'
							),
							__( 'File', 'yml-for-yandex-market' ),
							'class-yfym-feed-updater.php',
							__( 'Line', 'yml-for-yandex-market' ),
							__LINE__
						) );
					}
				}
			}

		}

	}

}