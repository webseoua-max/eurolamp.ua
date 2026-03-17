<?php

/**
 * Handles custom MIME type registrations for the plugin.
 *
 * @link       https://icopydoc.ru
 * @since      5.2.0
 * @version    5.2.0 (03-02-2026)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 */

/**
 * Handles custom MIME type registrations for the plugin.
 *
 * This class is responsible for adding support for additional file types
 * (such as .xml, .yml, and .csv) to WordPress upload functionality.
 * It ensures that these file types can be safely uploaded through the media library
 * or other upload interfaces within WordPress.
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */
class Y4YM_Mime_Types {

	public function init() {
		add_filter( 'upload_mimes', [ $this, 'add_mime_types' ] );
	}

	/**
	 * Разрешим загрузку xml и csv файлов. Function for `upload_mimes` action-hook.
	 * 
	 * @param array $mimes
	 * 
	 * @return array
	 */
	public function add_mime_types( $mimes ) {

		$mimes['csv'] = 'text/csv';
		$mimes['xml'] = 'text/xml';
		$mimes['yml'] = 'text/xml';
		return $mimes;

	}

}