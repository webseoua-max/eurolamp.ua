<?php
define( 'WP_CACHE', true );




define('WP_REDIS_SCHEME', 'unix');
define('WP_REDIS_PATH', '/home/eurolamp/.system/redis.sock');
define('WP_CACHE_KEY_SALT', 'example');
define('DISABLE_WP_CRON', true);

//Begin Really Simple Security key
define('RSSSL_KEY', 'gLlVL65F5CYIQiO51Rx5ZOwZDYwrxmlsWKim4XAG13qLcisjNfOwuarKRiSQGZhG');
//END Really Simple Security key

//Begin Really Simple SSL session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple SSL cookie settings

 
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки базы данных
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
// ** Параметры базы данных: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', "eurolamp_test" );
/** Имя пользователя базы данных */
define( 'DB_USER', "eurolamp_test" );
/** Пароль к базе данных */
define( 'DB_PASSWORD', "D4d#3gmD4_" );
/** Имя сервера базы данных */
define( 'DB_HOST', "eurolamp.mysql.tools" );
/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );
/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );
/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '5,D mF%r7r}4>Pt&7djg<e1DUuMXwYeDvAtY|9VZfnI_zYcR0l<6q((d[=2$o1DO' );
define( 'SECURE_AUTH_KEY',  ':Z1iS3}bLc<sZgKhrcXjV~<~CktgH+hbiT,.%aN7z_uuTTl/rJbtk(gnRMch8~ZY' );
define( 'LOGGED_IN_KEY',    '3/ziq:@_lH<WGo$Zm2XO;CSl0B41`O(@(IV4ppFZwWO:h]z$GwiY,6)$N-UL@9=~' );
define( 'NONCE_KEY',        '/kjWaR t,>xLT~c`.pOtm7^pA8n4f!Ub{|+/hO`XG7_F}/9uR5<PP(4e3in#fUY:' );
define( 'AUTH_SALT',        'xH~`ZP`S<,8H2CSo;yc/!WH5y_<&4+C+]:.A=js^eEC@9zaF89;t1@FK4{&W^~#@' );
define( 'SECURE_AUTH_SALT', 'T:tZU6ID>AHx@=IQ2`c%c .fcflCGpW]3(Sl(,#u!<:>IM7N#Fi;lePvumwW5Q-U' );
define( 'LOGGED_IN_SALT',   '7F?5Bj?r%+QI_pF02R&A}7,OGbrkZ/mi(aEn 7ErPi<gTHK4y_jiV&~8q7J-ehDP' );
define( 'NONCE_SALT',       '6g1-O=f=TsnMn.u~Prg*IfptK&dj-r~VZ_Fx]l*!>zAUq]P^ioMR5B)Y^&@jA}bH' );
/**#@-*/
/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';
/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */
/* Это всё, дальше не редактируем. Успехов! */
/** Абсолютный путь к директории WordPress. */
define( 'WP_SITEURL', 'https://eurolamp.ua/shop/' );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}
header('X-Frame-Options: SAMEORIGIN');
/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';