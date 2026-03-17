<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Страница общих настроек для этого плагина.
 *
 * Может быть использована только, если этот плагин используется как отдельный плагин, а не как аддон
 * дя плагина Clearfy. Если плагин загружен, как аддон для Clearfy, эта страница не будет подключена.
 *
 * Поддерживает режим работы с мультисаймами. Вы можете увидеть эту страницу в панели настройки сети.
 *
 * @author        Alexander Kovalev <alex.kovalevv@gmail.com>, Github: https://github.com/alexkovalevv
 * @copyright (c) 2018 Webraftic Ltd
 * @version       1.0
 */
class WCTR_CyrliteraPage extends WBCR\Factory_Templates_134\Pages\PageBase {

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	public $id = "transliteration";

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	public $page_parent_page = "seo";

	/**
	 * {@inheritDoc}
	 *
	 * @var string
	 */
	public $page_menu_dashicon = 'dashicons-testimonial';

	/**
	 * {@inheritDoc}
	 *
	 * @var bool
	 */
	public $available_for_multisite = true;

	/**
	 * {@inheritDoc}
	 *
	 * @since  1.1.0
	 * @var bool
	 */
	public $show_right_sidebar_in_options = true;

	/**
	 * WCTR_CyrliteraPage constructor.
	 *
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 *
	 * @param \Wbcr_Factory480_Plugin $plugin
	 */
	public function __construct( Wbcr_Factory480_Plugin $plugin ) {
		$this->menu_title = __( 'Cyrlitera', 'cyrlitera' );

		if ( ! defined( 'LOADING_CYRLITERA_AS_ADDON' ) ) {
			$this->internal                   = false;
			$this->menu_target                = 'options-general.php';
			$this->add_link_to_plugin_actions = true;
			$this->page_parent_page           = null;
			$this->show_search_options_form = false;
		}

		parent::__construct( $plugin );

		$this->plugin = $plugin;
	}


	public function getPageTitle() {
		return defined( 'LOADING_CYRLITERA_AS_ADDON' ) ? __( 'Transliteration', 'cyrlitera' ) : __( 'General', 'cyrlitera' );
	}

	/**
	 * Этот метод преобразовываем слаги для уже существующих страниц, терминов. Если это преобразование уже было выполнено,
	 * то мы больше незапускаем массовую конвертацию
	 */
	public function convertExistingSlugs() {
		$use_transliterations         = $this->plugin->getPopulateOption( 'use_transliteration' );
		$transliterate_existing_slugs = $this->plugin->getPopulateOption( 'transliterate_existing_slugs' );

		if ( ! $use_transliterations || $transliterate_existing_slugs ) {
			return;
		}

		WCTR_Helper::convertExistingSlugs();

		$this->plugin->updatePopulateOption( 'transliterate_existing_slugs', 1 );
	}

	/**
	 * Метод выполняется после сохранения формы настроек. Когда пользователь включает транслитерацию,
	 * метод запускает массовую конвертацию слагов для уже существующих страниц,
	 * терминов. Если это преобразование уже было выполнено, то мы больше незапускаем массовую конвертацию
	 */
	protected function afterFormSave() {
		$this->convertExistingSlugs();
	}

	/**
	 * Permalinks options.
	 *
	 * @since 1.0.0
	 * @return mixed[]
	 */
	public function getPageOptions() {
		$options[] = [
			'type' => 'html',
			'html' => '<div class="wbcr-factory-page-group-header">' . '<strong>' . __( 'Cyrillic to Latin transliteration.', 'cyrlitera' ) . '</strong>' . '<p>' . __( 'Converts Cyrillic permalinks for posts, pages, taxonomies, and media files to the Latin alphabet. Supports Russian, Ukrainian, Georgian, and Bulgarian. Example: http://site.dev/последние-новости -> http://site.dev/poslednie-novosti', 'cyrlitera' ) . '</p>' . '</div>'
		];

		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'use_transliteration',
			'title'   => __( 'Apply transliteration to new content', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => __( 'Automatically converts URLs for newly created pages, posts, tags, and categories to Latin characters.', 'cyrlitera' ),
			'default' => false
		];
		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'use_transliteration_filename',
			'title'   => __( 'Transliterate file names on upload', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => __( 'Automatically converts Cyrillic characters to Latin in file names when new media files are uploaded.', 'cyrlitera' ),
			'default' => false
		];
		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'filename_to_lowercase',
			'title'   => __( 'Convert file names to lowercase on upload', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => __( 'Automatically converts file names of newly uploaded media to lowercase. Example: File_Name.jpg -> file_name.jpg', 'cyrlitera' ),
			'default' => false
		];

		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'redirect_from_old_urls',
			'title'   => __( 'Redirect old URLs to transliterated URLs', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => __( 'Automatically redirects visitors from existing non-Latin URLs to their transliterated Latin versions.', 'cyrlitera' ),
			'default' => false
		];

		$options[] = [
			'type'  => 'textarea',
			'way'   => 'buttons',
			'name'  => 'custom_symbols_pack',
			'title' => __( 'Custom character mappings', 'cyrlitera' ),
			'hint'  => __( 'You can add custom transliteration character pairs. Example:', 'cyrlitera' ) . ' <b>Ё=Jo,ё=jo,Ж=Zh,ж=zh</b>'
		];
		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'dont_use_transliteration_on_frontend',
			'title'   => __( 'Disable transliteration on the frontend', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => __( 'Temporarily turns off transliteration on the frontend if you experience display or compatibility issues.', 'cyrlitera' ),
			'default' => false
		];
		$options[] = [
			'type'    => 'checkbox',
			'way'     => 'buttons',
			'name'    => 'use_force_transliteration',
			'title'   => __( 'Force transliteration (override other plugins)', 'cyrlitera' ),
			'layout'  => [ 'hint-type' => 'icon', 'hint-icon-color' => 'green' ],
			'hint'    => sprintf( __( 'If another plugin affects transliteration of links and file names, enable this option to make %s override those changes.', 'cyrlitera' ), WCTR_Plugin::app()->getPluginTitle() ),
			'default' => false
		];

		// Произвольный html код
		$options[] = [
			'type' => 'html', // тип элемента формы
			'html' => [ $this, 'rollbackButton' ]
		];

		$formOptions = [];

		$formOptions[] = [
			'type'  => 'form-group',
			'items' => $options,
			//'cssClass' => 'postbox'
		];

		return apply_filters( 'wbcr_cyrlitera_general_form_options', $formOptions, $this );
	}

	/**
	 * @param $html_builder Wbcr_FactoryForms480_Html
	 */
	public function rollbackButton( $html_builder ) {
		$form_name = $html_builder->getFormName();

		$rollback    = false;
		$convert_now = false;

		if ( isset( $_POST['wbcr_cyrlitera_rollback_action'] ) ) {
			check_admin_referer( $form_name, 'wbcr_cyrlitera_rollback_nonce' );

			if ( WCTR_Plugin::app()->isNetworkActive() ) {
				foreach ( WCTR_Plugin::app()->getActiveSites() as $site ) {
					switch_to_blog( $site->blog_id );
					WCTR_Helper::rollbackUrlChanges();
					restore_current_blog();
				}
			} else {
				WCTR_Helper::rollbackUrlChanges();
			}

			$rollback = true;
		}

		if ( isset( $_POST['wbcr_cyrlitera_convert_now_action'] ) ) {
			check_admin_referer( $form_name, 'wbcr_cyrlitera_convert_now_nonce' );

			if ( WCTR_Plugin::app()->isNetworkActive() ) {
				foreach ( WCTR_Plugin::app()->getActiveSites() as $site ) {
					switch_to_blog( $site->blog_id );
					WCTR_Helper::convertExistingSlugs();
					restore_current_blog();
				}
			} else {
				WCTR_Helper::convertExistingSlugs();
			}

			$convert_now = true;
		}

		?>
        <div class="form-group form-group-checkbox factory-control-convert_now_button">
            <label for="wbcr_clearfy_convert_now_button" class="col-sm-4 control-label">
				<span class="factory-hint-icon factory-hint-icon-green" data-toggle="factory-tooltip" data-placement="right" title="" data-original-title="<?php esc_attr_e( 'Converts URLs for posts, pages, tags, and categories created before the plugin was installed to Latin characters. Previously uploaded media files are not affected.', 'cyrlitera' ) ?>">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAQAAABKmM6bAAAAUUlEQVQIHU3BsQ1AQABA0X/komIrnQHYwyhqQ1hBo9KZRKL9CBfeAwy2ri42JA4mPQ9rJ6OVt0BisFM3Po7qbEliru7m/FkY+TN64ZVxEzh4ndrMN7+Z+jXCAAAAAElFTkSuQmCC" alt="">
				</span>
            </label>
            <div class="control-group col-sm-8">
                <div class="factory-checkbox factory-from-control-checkbox factory-buttons-way btn-group">
                    <form method="post">
						<?php wp_nonce_field( $form_name, 'wbcr_cyrlitera_convert_now_nonce' ); ?>
                        <input type="submit" name="wbcr_cyrlitera_convert_now_action" value="<?php _e( 'Convert Existing Posts and Categories', 'cyrlitera' ) ?>" class="button button-default"/>
						<?php if ( $convert_now ): ?>
                            <div style="color:green;margin-top:5px;"><?php _e( 'URL of old posts, pages, terms, tags successfully converted into Latin!', 'cyrlitera' ) ?></div>
						<?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="form-group form-group-checkbox factory-control-rollback_button">
            <label for="wbcr_clearfy_rollback_button" class="col-sm-4 control-label">
				<span class="factory-hint-icon factory-hint-icon-green" data-toggle="factory-tooltip" data-placement="right" title="" data-original-title="<?php esc_attr_e( 'Restores URLs to their original state before conversion.', 'cyrlitera' ) ?>">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAkAAAAJCAQAAABKmM6bAAAAUUlEQVQIHU3BsQ1AQABA0X/komIrnQHYwyhqQ1hBo9KZRKL9CBfeAwy2ri42JA4mPQ9rJ6OVt0BisFM3Po7qbEliru7m/FkY+TN64ZVxEzh4ndrMN7+Z+jXCAAAAAElFTkSuQmCC" alt="">
				</span>
            </label>
            <div class="control-group col-sm-8">
                <div class="factory-checkbox factory-from-control-checkbox factory-buttons-way btn-group">
                    <form method="post">
						<?php wp_nonce_field( $form_name, 'wbcr_cyrlitera_rollback_nonce' ); ?>
                        <input type="submit" name="wbcr_cyrlitera_rollback_action" value="<?php _e( 'Rollback URL Conversions', 'cyrlitera' ) ?>" class="button button-default"/>
						<?php if ( $rollback ): ?>
                            <div style="color:green;margin-top:5px;"><?php _e( 'The rollback of new changes was successful!', 'cyrlitera' ) ?></div>
						<?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
		<?php
	}
}
