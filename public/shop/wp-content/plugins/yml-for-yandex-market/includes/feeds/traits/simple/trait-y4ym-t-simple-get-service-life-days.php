<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      5.0.23
 * @version    5.0.23 (15-11-2025)
 *
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_service_life_days` method.
 * 
 * This method allows you to return the `service-life-days` tag.
 *
 * @since      0.1.0
 * @package    Y4YM
 * @subpackage Y4YM/includes/feeds/traits/simple
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     Y4YM_Get_Paired_Tag
 *             methods:     get_product
 *                          get_feed_id
 *                          get_simple_global_attribute_value
 *                          get_simple_tag
 *             functions:   common_option_get
 */
trait Y4YM_T_Simple_Get_Service_Life_Days {

	/**
	 * Get `service-life-days` tag.
	 * 
	 * @see https://yandex.ru/support/merchants/ru/offers
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<service-life-days>P1Y</service-life-days>`.
	 */
	public function get_service_life_days( $tag_name = 'service-life-days', $result_xml = '' ) {

		$tag_value = '';

		$y4ym_service_life_days = common_option_get(
			'y4ym_service_life_days',
			'disabled',
			$this->get_feed_id(),
			'y4ym'
		);
		if ( $y4ym_service_life_days === 'enabled' ) {

			$service_life_days_value = $this->get_simple_product_post_meta( 'service_life_days' );
			if ( empty( $service_life_days_value ) ) {
				$service_life_days_value = common_option_get(
					'y4ym_service_life_days_default_value',
					0,
					$this->get_feed_id(),
					'y4ym'
				);
			}

			$service_life_days_value = (int) $service_life_days_value;
			if ( $service_life_days_value > 0 ) {
				$y = floor( $service_life_days_value / 365 );
				$m = floor( ( $service_life_days_value - 365 * $y ) / 30 );
				$d = floor( $service_life_days_value - 365 * $y - 30 * $m );

				$tag_value = 'P';
				if ( $y > 0 ) {
					$tag_value = sprintf( '%s%dY', $tag_value, $y );
				}
				if ( $m > 0 ) {
					$tag_value = sprintf( '%s%dM', $tag_value, $m );
				}
				if ( $d > 0 ) {
					$tag_value = sprintf( '%s%dD', $tag_value, $d );
				}
			}

			$result_xml = $this->get_simple_tag( $tag_name, $tag_value );

		}

		return $result_xml;

	}

}