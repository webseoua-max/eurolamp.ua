<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.10 (12-01-2026)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_availability_date` method.
 * 
 * This method allows you to return the `availability_date` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *             functions:   common_option_get
 */
trait XFGMC_T_Simple_Get_Availability_Date {

	/**
	 * Get `availability_date` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324470
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:availability_date>2016-11-25T13:00-0800</g:availability_date>.
	 */
	public function get_availability_date( $tag_name = 'g:availability_date', $result_xml = '' ) {

		$tag_value = '';
		$use_availability_date = common_option_get(
			'xfgmc_use_availability_date',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $use_availability_date === 'disabled' ) {
			return $result_xml;
		}

		if ( $use_availability_date === 'enabled_default_value' ) {
			$availability_date = common_option_get(
				'xfgmc_availability_date',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ! empty( $availability_date ) ) {
				$tag_value = $availability_date;
			}
		}

		if ( $use_availability_date === 'enabled' ) {
			$add_to_availability = (int) common_option_get(
				'xfgmc_add_to_availability',
				'0',
				$this->get_feed_id(),
				'xfgmc'
			);
			// Получаем текущую дату + $add_to_availability дня в объекте DateTime, с учётом часового пояса WordPress
			$date = new DateTime( 'now', wp_timezone() );
			$date->modify( sprintf( '+%s days', $add_to_availability ) );
			// Формируем строку в нужном формате: Y-m-d\TH:iP (без секунд, смещение без разделителя)
			$tag_value = $date->format( 'Y-m-d\TH:iP' ); // ISO 8601 $date->format('c')
		}

		$tag_value = apply_filters(
			'x4gmc_f_simple_tag_value_availability_date',
			$tag_value,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_name = apply_filters(
				'x4gmc_f_simple_tag_name_availability_date',
				$tag_name,
				[ 'product' => $this->get_product() ],
				$this->get_feed_id()
			);
			$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'x4gmc_f_simple_tag_availability_date',
			$result_xml,
			[ 'product' => $this->get_product() ],
			$this->get_feed_id()
		);
		return $result_xml;

	}

}