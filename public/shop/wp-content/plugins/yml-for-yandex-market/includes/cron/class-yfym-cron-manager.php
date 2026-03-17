<?php

/**
 * CRON task management for the YML for Yandex Market plugin.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.2.0 (03-02-2026)
 *
 * @package    Y4YM
 * @subpackage Y4YM/admin
 */

/**
 * Class for managing CRON tasks related to feed generation.
 *
 * Responsible for:
 * - Scheduling the start of feed building
 * - Step-by-step feed generation
 * - Registration of custom cron intervals
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/cron
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class Y4YM_Cron_Manager {

	/**
	 * Add cron intervals to WordPress. Function for `cron_schedules` action-hook.
	 * 
	 * @param array $schedules
	 * 
	 * @return array
	 */
	public function add_cron_intervals( $schedules ) {

		$schedules['every_minute'] = [
			'interval' => 60,
			'display' => __( 'Every minute', 'yml-for-yandex-market' )
		];
		$schedules['three_hours'] = [
			'interval' => 10800,
			'display' => __( 'Every three hours', 'yml-for-yandex-market' )
		];
		$schedules['six_hours'] = [
			'interval' => 21600,
			'display' => __( 'Every six hours', 'yml-for-yandex-market' )
		];
		$schedules['every_two_days'] = [
			'interval' => 172800,
			'display' => __( 'Every two days', 'yml-for-yandex-market' )
		];
		return $schedules;

	}

	/**
	 * The function responsible for starting the creation of the feed.
	 * Function for `y4ym_cron_start_feed_creation` action-hook.
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	public function do_start_feed_creation( $feed_id ) {

		Y4YM_Error_Log::record( sprintf( 'FEED #%1$s; %2$s; %3$s: %4$s; %5$s: %6$s',
			$feed_id,
			__( 'The CRON task for creating a feed has started', 'yml-for-yandex-market' ),
			__( 'File', 'yml-for-yandex-market' ),
			'class-yfym-cron-manager.php',
			__( 'Line', 'yml-for-yandex-market' ),
			__LINE__
		) );

		// счётчик завершенных товаров в положение 0.
		univ_option_upd(
			'y4ym_last_element_feed_' . $feed_id,
			'0',
			'no'
		);

		// запланируем CRON сборки
		$planning_result = self::cron_sborki_task_planning( $feed_id );

		if ( false === $planning_result ) {
			Y4YM_Error_Log::record( sprintf(
				'FEED #%1$s; ERROR: %2$s `y4ym_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
				$feed_id,
				__( 'Failed to schedule a CRON task', 'yml-for-yandex-market' ),
				__( 'File', 'yml-for-yandex-market' ),
				'class-yfym-cron-manager.php',
				__( 'Line', 'yml-for-yandex-market' ),
				__LINE__
			) );
		} else {
			Y4YM_Error_Log::record( sprintf(
				'FEED #%1$s; %2$s `y4ym_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
				$feed_id,
				__( 'Successful CRON task planning', 'yml-for-yandex-market' ),
				__( 'File', 'yml-for-yandex-market' ),
				'class-yfym-cron-manager.php',
				__( 'Line', 'yml-for-yandex-market' ),
				__LINE__
			) );
			// сборку начали
			common_option_upd(
				'y4ym_status_sborki',
				'1',
				'no',
				$feed_id,
				'y4ym'
			);
			// сразу планируем крон-задачу на начало сброки фида в следующий раз в нужный час
			$run_cron = common_option_get(
				'y4ym_run_cron',
				'disabled',
				$feed_id,
				'y4ym'
			);
			if ( in_array( $run_cron, [ 'hourly', 'three_hours', 'six_hours', 'twicedaily', 'daily', 'every_two_days', 'weekly' ] ) ) {
				$arr = wp_get_schedules();
				if ( isset( $arr[ $run_cron ]['interval'] ) ) {
					self::cron_starting_feed_creation_task_planning( $feed_id, $arr[ $run_cron ]['interval'] );
				}
			}
		}

	}

	/**
	 * The function is called every minute until the feed is created or creation is interrupted.
	 * Function for `y4ym_cron_sborki` action-hook.
	 * 
	 * @param string $feed_id
	 * 
	 * @return void
	 */
	public function do_it_every_minute( $feed_id ) {

		Y4YM_Error_Log::record( sprintf( 'FEED #%1$s; %2$s `y4ym_cron_sborki`; %3$s: %4$s; %5$s: %6$s',
			$feed_id,
			__( 'The CRON task started', 'yml-for-yandex-market' ),
			__( 'File', 'yml-for-yandex-market' ),
			'class-yfym-cron-manager.php',
			__( 'Line', 'yml-for-yandex-market' ),
			__LINE__
		) );

		$generation = new Y4YM_Generation_XML( $feed_id );
		$generation->run();

	}

	/**
	 * Cron starting the feed creation task planning.
	 * 
	 * @param string $feed_id
	 * @param int $delay_second Scheduling task CRON in N seconds.
	 * 
	 * @return bool|WP_Error
	 */
	public static function cron_starting_feed_creation_task_planning( $feed_id, $delay_second = 0 ) {

		$planning_result = false;
		$run_cron = common_option_get(
			'y4ym_run_cron',
			'disabled',
			$feed_id,
			'y4ym'
		);

		if ( $run_cron === 'disabled' ) {
			// останавливаем сборку досрочно, если это выбрано в настройках плагина при сохранении
			wp_clear_scheduled_hook( 'y4ym_cron_start_feed_creation', [ $feed_id ] );
			wp_clear_scheduled_hook( 'y4ym_cron_sborki', [ $feed_id ] );
			univ_option_upd(
				'y4ym_last_element_feed_' . $feed_id,
				0
			);
			common_option_upd(
				'y4ym_status_sborki',
				'-1',
				'no',
				$feed_id,
				'y4ym'
			);
		} else {
			wp_clear_scheduled_hook( 'y4ym_cron_start_feed_creation', [ $feed_id ] );
			if ( ! wp_next_scheduled( 'y4ym_cron_start_feed_creation', [ $feed_id ] ) ) {
				$cron_start_time = common_option_get(
					'y4ym_cron_start_time',
					'disabled',
					$feed_id,
					'y4ym'
				);
				switch ( $cron_start_time ) {
					case 'disabled':

						return false;

					case 'now':

						$cron_interval = current_time( 'timestamp', 1 ) + 2; // добавим 2 сек

						break;
					default:

						$gmt_offset = (float) get_option( 'gmt_offset' );
						$offset_in_seconds = $gmt_offset * 3600;
						$cron_interval = strtotime( $cron_start_time ) - $offset_in_seconds;
						if ( $cron_interval < current_time( 'timestamp', 1 ) ) {
							// если нужный час уже прошел. запланируем на следующие сутки
							$cron_interval = $cron_interval + 86400;
						}
				}

				// планируем крон-задачу на начало сброки фида в нужный час
				$planning_result = wp_schedule_single_event(
					$cron_interval + $delay_second,
					'y4ym_cron_start_feed_creation',
					[ $feed_id ]
				);
			}
		}

		return $planning_result;

	}

	/**
	 * Cron sborki task planning.
	 * 
	 * @param string $feed_id
	 * @param int $delay_second Scheduling task CRON in N seconds.
	 * 
	 * @return bool|WP_Error
	 */
	public static function cron_sborki_task_planning( $feed_id, $delay_second = 5 ) {

		wp_clear_scheduled_hook( 'y4ym_cron_sborki', [ $feed_id ] );
		if ( ! wp_next_scheduled( 'y4ym_cron_sborki', [ $feed_id ] ) ) {
			$planning_result = wp_schedule_single_event(
				current_time( 'timestamp', 1 ) + $delay_second, // добавим 5 секунд
				'y4ym_cron_sborki',
				[ $feed_id ]
			);
		} else {
			$planning_result = false;
		}

		return $planning_result;

	}

}