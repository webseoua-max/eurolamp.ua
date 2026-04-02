<?php

/**
 * Autoloader classes for WordPress.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    5.0.19 (26-08-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 */

/**
 * Autoloader classes for WordPress.
 *
 * This class is a `autoload mu-plugin` that downloads all the php files I need by itself. 
 * Without using composer. It is assumed that the file names are constructed as follows:
 * 
 * - `class-[PREFIX]-[CLASS-NAME].php` - code in the file: `class Y4YM_My_Name {...}`;
 * - `trait-[PLUGIN_PREFIX]-t-[TRAIT-NAME].php` - code in the file: `trait Y4YM_T_My_Name {...}`;
 * - `interface-[PLUGIN_PREFIX]-i-[INTERFACE-NAME].php` - code in the file: `interface Y4YM_I_My_Name`.
 * 
 * Usage example: `new Y4YM_Autoload( Y4YM_PLUGIN_DIR_PATH, 'Y4YM' )`;
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @see        https://www.kobzarev.com/programming/autoload/
 */
class Y4YM_Autoloader {

	/**
	 * The path to the classmap file.
	 *  
	 * Example: `/home/p135/www/site.ru/wp-content/plugins/yml-for-yandex-market/classmap.php`.
	 * 
	 * @var      string
	 */
	private $map_file;

	/**
	 * Classmap.
	 * 
	 * @var
	 */
	private $map;

	/**
	 * The plugin dir path.
	 * 
	 * Example: `/home/p135/www/site.ru/wp-content/plugins/yml-for-yandex-market/`.
	 * 
	 * @var      string
	 */
	private $plugin_dir_path = Y4YM_PLUGIN_DIR_PATH;

	/**
	 * The plugin prefix. 
	 * 
	 * Example: `Y4YM`.
	 * 
	 * @var      string
	 */
	private $prefix = 'Y4YM';

	/**
	 * Whether the class map has been updated since the last time the page was loaded.
	 * 
	 * @var bool 
	 */
	private $has_been_update = false;

	/**
	 * Constructor.
	 * 
	 * @param    string    $plugin_dir_path
	 * @param    string    $prefix
	 */
	public function __construct( $plugin_dir_path = '', $prefix = '' ) {

		if ( ! empty( $plugin_dir_path ) ) {
			$this->plugin_dir_path = $plugin_dir_path;
		}
		if ( ! empty( $prefix ) ) {
			$this->prefix = $prefix;
		}
		$this->map_file = __DIR__ . '/classmap.php';
		if ( ! file_exists( __DIR__ . '/classmap.php' ) ) {
			file_put_contents( $this->map_file, '<?php return [];' );
		}
		$this->map = @include $this->map_file;
		$this->map = is_array( $this->map ) ? $this->map : [];
		spl_autoload_register( [ $this, 'autoload' ] );
		add_action( 'shutdown', [ $this, 'update_cache' ] );

	}

	/**
	 * Создаёт/обновляет файл `classmap.php `если он был изменен с последней загрузки.
	 *                                                        
	 * @return   void
	 */
	public function update_cache(): void {

		if ( ! $this->has_been_update ) {
			return;
		}
		$map = implode(
			"\n",
			array_map(
				function ($k, $v) {
					return "'$k' => '$v',";
				},
				array_keys( $this->map ),
				array_values( $this->map )
			)
		);

		file_put_contents( $this->map_file, '<?php return [' . $map . '];' );

	}

	/**
	 * Пытается найти класс или трейт и загрзуить его через `require_once`.
	 * 
	 * @param    string    $class
	 *                                                        
	 * @return   void
	 */
	private function autoload( string $class ): void {

		if ( 0 === strpos( $class, $this->get_prefix() ) ) {
			if ( isset( $this->map[ $class ] ) && file_exists( $this->map[ $class ] ) ) {
				// Подключаем файл, который мы нашли в classmap. 
				// Проверка file_exists нужна на случай, если мы захотим удалить, переместить или переименовать файл.
				require_once $this->map[ $class ];
			} else {
				$this->has_been_update = true; // classmap нужно обновить
				$plugin_parts = explode( '\\', $class );
				$name = array_pop( $plugin_parts );
				if ( 1 === preg_match( '/_T_/', $name ) ) {
					$file_name = 'trait-' . $name . '.php';
				} else if ( 1 === preg_match( '/_I_/', $name ) ) {
					$file_name = 'interface-' . $name . '.php';
				} else {
					$file_name = 'class-' . $name . '.php';
				}
				$file_name = strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $file_name ) );
				// $path = implode( '/', $plugin_parts ) . '/' . $file_name;
				// $path = strtolower( str_replace( [ '\\', '_' ], [ '/', '-' ], $path ) );
				$found_flag = false;
				$all_php_files_arr = $this->get_dir_files( $this->get_plugin_dir_path() );
				// ! var_dump( $this->get_plugin_dir_path() );
				for ( $i = 0; $i < count( $all_php_files_arr ); $i++ ) {
					// ! echo '<br/>поиск ' . $file_name . ' в строке' . $all_php_files_arr[ $i ];
					if ( strpos( $all_php_files_arr[ $i ], $file_name ) !== false ) {
						$path = $all_php_files_arr[ $i ];
						$found_flag = true;
						break;
					}
				}
				if ( true === $found_flag ) {
					$this->map[ $class ] = $path; // Обновляем classmap
					require_once $path;
				}
			}
		}

	}

	/**
	 * Получает пути всех файлов и папок в указанной папке.
	 *
	 * @param    string    $dir                Путь до папки (на конце со слэшем или без).
	 * @param    bool      $recursive          Включить вложенные папки или нет?
	 * @param    bool      $include_folders    Включить ли в список пути на папки?
	 * 
	 * @return   array     Вернет массив путей до файлов/папок.
	 */
	private function get_dir_files( $dir, $recursive = true, $include_folders = false ): array {

		if ( ! is_dir( $dir ) ) {
			return [];
		}
		$files_arr = [];
		$dir = rtrim( $dir, '/\\' ); // удалим слэш на конце

		// Проверка наличия константы
		if ( defined( 'GLOB_BRACE' ) ) {
			$pattern = "$dir/{,.}[!.,!..]*";
			$flags = GLOB_BRACE;
		} else {
			$pattern = "$dir/*";
			$flags = 0;
		}

		foreach ( glob( $pattern, $flags ) as $file ) {
			if ( is_dir( $file ) ) {
				if ( $include_folders ) {
					$files_arr[] = $file;
				}
				if ( $recursive ) {
					$files_arr = array_merge(
						$files_arr,
						$this->get_dir_files( $file, $recursive, $include_folders )
					);
				}
			} else {
				$ext = pathinfo( $file, PATHINFO_EXTENSION );
				if ( 'php' === $ext ) {
					// если это php файл, то вносим в список
					$files_arr[] = $file;
				}
			}
		}
		return $files_arr;

	}

	/**
	 * Get the plugin dir path.
	 * 
	 * @return   string    Example: `/home/p135/www/site.ru/wp-content/plugins/yml-for-yandex-market/`.
	 */
	private function get_plugin_dir_path() {
		return $this->plugin_dir_path;
	}

	/**
	 * Get the plugin prefix.
	 * 
	 * @return   string    Example: `Y4YM`.
	 */
	private function get_prefix() {
		return $this->prefix;
	}

}