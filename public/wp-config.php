<?php
define( 'WP_CACHE', true );

 
define('DISABLE_WP_CRON', true);
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
define( 'DB_NAME', "eurolamp_base" );
/** Имя пользователя базы данных */
define( 'DB_USER', "eurolamp_base" );
/** Пароль к базе данных */
define( 'DB_PASSWORD', "ddR3)69g*D" );
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
define( 'AUTH_KEY',         '/<!!e>N~po&nAhzM<>|7+>r<&Bi6xG voGL$uq5(e(Ev`VLc}Rtxuxpcq$8*1 $@' );
define( 'SECURE_AUTH_KEY',  '4?]~Ol}$>q$(L8AR>1:Cz&ixn(M[+2H.m9~qQ2TgLn[iBz>.OA8zHqTh]NL}=83c' );
define( 'LOGGED_IN_KEY',    '=R*hLJ}Wmj0v$n~4[[R6cFhbZMHLa3z6ytNmvbsL?*mkjC#rV0}:k%=0e6i6wR8O' );
define( 'NONCE_KEY',        'l+1d9Os{{gDiWpQN1tP]:#$FRB4H&xfNoK7_M{)3.^H8~f_ISkCowV,7ZQzDe(f-' );
define( 'AUTH_SALT',        'KvAP:1s!b@v?>6>Mk>34h^P/u9C[dv]#7<uxOTMe0)>tEK}!O?$ggEh6(Rov1{1]' );
define( 'SECURE_AUTH_SALT', '/W>suZ+J=ra!tCTbMZwc|m>vES=HM/3ZAXK4G(ihzKo9z,g: UtF-}Yty^qi[8Zu' );
define( 'LOGGED_IN_SALT',   'Yi+t4>]iJh@qGY[Mv_no?)szXCWV{il*l}`/e@S3xvFf6G+/]hezT}y!>VHg*<Zs' );
define( 'NONCE_SALT',       '~DNi-$.vYYimof~K6!dT+5Fm8j^VS9sJI>N5S,x6fe[VNXT(ODs&/f&z)4|<At~~' );
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
define( 'WP_DEBUG', false );
/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */
/* Это всё, дальше не редактируем. Успехов! */
/** Абсолютный путь к директории WordPress. */
define( 'WP_SITEURL', 'https://eurolamp.ua/' );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}
header('X-Frame-Options: SAMEORIGIN');
/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';