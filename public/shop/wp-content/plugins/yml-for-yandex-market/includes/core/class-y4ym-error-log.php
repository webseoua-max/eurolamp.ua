<?php

/**
 * Writes plugin logs.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.2.0 (03-02-2026)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 */

/**
 * Writes plugin logs.
 *
 * The recommended way is to use the static method:
 *
 *    `Y4YM_Error_Log::record( sprintf( '%1$s line: %2$s', 'Text', __LINE__ ) );`
 *
 * The legacy approach using the constructor is still supported for backward compatibility:
 *
 *    `new Y4YM_Error_Log( sprintf( '%1$s line: %2$s', 'Text', __LINE__ ) );`
 *
 * However, using the constructor is deprecated and may be removed in a future version.
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
final class Y4YM_Error_Log {

	/**
	 * Cache for the logging status to avoid repeated option lookups.
	 * 
	 * @var bool|null null if status is not yet determined, true if logs are enabled, false otherwise.
	 */
	protected static $logs_enabled = null;

	/**
	 * The text to write to the log file.
	 * @var mixed
	 */
	protected $text_to_log;

	/**
	 * Writes plugin logs.
	 * 
	 * Example: `Y4YM_Error_Log::record( 'Текст' );`.
	 * 
	 * @param mixed $text_to_log The text to write to the log file.
	 * @param string $log_dir_name Example: `/home/site.ru/public_html/wp-content/uploads/y4ym/`.
	 * 
	 * @return void
	 */
	public function __construct( $text_to_log, $log_dir_name = Y4YM_PLUGIN_UPLOADS_DIR_PATH ) {

		_deprecated_function( 'Y4YM_Error_Log::__construct', '5.2.0', 'Y4YM_Error_Log::record()' );
		// вызов оставлен для обратной совместимости
		$this->text_to_log = $text_to_log;
		self::record( $text_to_log, $log_dir_name );

	}

	/**
	 * The magic method `__toString`.
	 * 
	 * @return mixed
	 */
	public function __toString() {

		try {
			return self::get_array_as_string( $this->get_text_to_log() );
		} catch (Exception $e) {
			return 'Error in __toString: ' . $e->getMessage();
		}

	}

	/**
	 * Writes data to a log file.
	 *
	 * Performs the following checks and actions:
	 * - Checks if logging is enabled in plugin settings
	 * - Ensures text encoding is UTF-8
	 * - Creates the log directory if it doesn't exist
	 * - Writes data to the log file with exclusive lock (LOCK_EX)
	 *
	 * @since 5.2.0
	 * @param mixed $text_to_log The text to write to the log file.
	 * @param string $log_dir_name Example: `/home/site.ru/public_html/wp-content/uploads/y4ym/`.
	 * 
	 * @return void
	 */
	public static function record( $text_to_log, $log_dir_name = Y4YM_PLUGIN_UPLOADS_DIR_PATH ) {

		if ( ! wp_mkdir_p( $log_dir_name ) ) {
			error_log( sprintf( 'Y4YM: Y4YM_Error_Log: %1$s: %2$s. %3$s.',
				'There is no folder',
				$log_dir_name,
				'Recording plugin logs is not possible' )
			);
			return;
		}
		// /home/site.ru/public_html/wp-content/uploads/y4ym/yml-for-yandex-market.log
		$log_file_path = trailingslashit( $log_dir_name ) . 'yml-for-yandex-market.log';

		if ( false === self::keeplogs_status() ) {
			return;
		}

		if ( is_array( $text_to_log ) || is_object( $text_to_log ) ) {
			$r = self::get_array_as_string( $text_to_log );
			$text_to_log_str = $r;
		} else if ( true === $text_to_log ) {
			$text_to_log_str = '(boolean)true';
		} else if ( false === $text_to_log ) {
			$text_to_log_str = '(boolean)false';
		} else if ( null === $text_to_log ) {
			$text_to_log_str = '(null)';
		} else {
			$text_to_log_str = $text_to_log;
		}
		unset( $text_to_log );

		// Проверяем и устанавливаем кодировку
		if ( 'UTF-8' !== mb_detect_encoding( $text_to_log_str, [ 'UTF-8' ], true ) ) {
			$text_to_log_str = mb_convert_encoding( $text_to_log_str, 'UTF-8', 'auto' );
		}

		// Готовим полную строку для записи
		$full_text = '[' . gmdate( 'Y-m-d H:i:s' ) . '] ' . $text_to_log_str . PHP_EOL;

		// Записываем данные с эксклюзивной блокировкой
		$result = file_put_contents(
			$log_file_path,
			$full_text,
			FILE_APPEND | LOCK_EX
		);
		if ( false === $result ) {
			error_log( "Y4YM: Y4YM_Error_Log: Failed to write to log file: $log_file_path" );
		}

	}

	/**
	 * Checks whether logging is enabled.
	 *
	 * Uses caching to avoid repeated database queries or option lookups.
	 * On multisite, uses blog-specific option; on single site, uses global option.
	 * 
	 * @return bool `true` - if logging is enabled (`enabled`), `false` - otherwise.
	 */
	protected static function keeplogs_status() {

		// Return cached result if already determined
		if ( self::$logs_enabled !== null ) {
			return self::$logs_enabled;
		}

		// Get the option value depending on network mode
		if ( is_multisite() ) {
			$v = get_blog_option( get_current_blog_id(), 'y4ym_keeplogs', 'disabled' );
		} else {
			$v = get_option( 'y4ym_keeplogs', 'disabled' );
		}

		// Cache the boolean result
		self::$logs_enabled = $v;

		if ( $v === 'enabled' ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Converts data to a string.
	 * 
	 * @param mixed $text
	 * @param string $new_line
	 * @param int $i
	 * @param string $res
	 * 
	 * @return string
	 */
	protected static function get_array_as_string( $text, $new_line = PHP_EOL, $i = 0, $res = '' ) {

		$tab = str_repeat( '---', $i );
		if ( is_object( $text ) ) {
			$text = (array) $text;
		}
		if ( is_array( $text ) ) {
			$i++;
			foreach ( $text as $key => $value ) {
				if ( is_array( $value ) ) { // массив
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$res .= $tab . self::get_array_as_string( $value, $new_line, $i );
				} else if ( is_object( $value ) ) { // объект
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")";
					$value = (array) $value;
					$res .= $tab . self::get_array_as_string( $value, $new_line, $i );
				} else if ( is_bool( $value ) ) { // boolean
					if ( true === $value ) {
						$res .= $new_line . $tab . "[$key] => (boolean)true";
					} else {
						$res .= $new_line . $tab . "[$key] => (boolean)false";
					}
				} else {
					$res .= $new_line . $tab . "[$key] => (" . gettype( $value ) . ")" . $value;
				}
			}
		} else {
			$res .= $new_line . $tab . $text;
		}
		return $res;

	}

	/**
	 * Get the text to write to the log file.
	 * 
	 * @return mixed
	 */
	protected function get_text_to_log() {
		return $this->text_to_log;
	}

}