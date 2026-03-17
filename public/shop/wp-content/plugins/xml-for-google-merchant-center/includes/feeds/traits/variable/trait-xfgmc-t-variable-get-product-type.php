<?php

/**
 * Trait for variable products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.3 (17-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 */

/**
 * The trait adds `get_product_type` methods.
 * 
 * This method allows you to return the `product_type` tag.
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/variable
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 * @depends    classes:     XFGMC_Get_Paired_Tag
 *             methods:     get_product
 *                          get_offer
 *                          get_feed_id
 *             functions:   common_option_get
 */

trait XFGMC_T_Variable_Get_Product_Type {

	/**
	 * Get `product_type` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324406
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:product_type>Главная > Женская одежда > Платья > Длинные платья</g:product_type>`.
	 */
	public function get_product_type( $tag_name = 'g:product_type', $result_xml = '' ) {

		$product_type = common_option_get(
			'xfgmc_product_type',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $product_type === 'enabled' ) {
			$tag_value = $this->get_breadcrumbs( $this->get_feed_category_id() );
			$result_xml = $this->get_variable_tag( $tag_name, htmlspecialchars( $tag_value, ENT_NOQUOTES ) );
		}
		return $result_xml;

	}

	/**
	 * Get breadcrumbs string.
	 * 
	 * @see https://support.google.com/merchants/answer/6324406
	 * 
	 * @param string $category_id
	 * @param string $result
	 * @param int|false $parent_id
	 * 
	 * @return string Example: `Главная > Женская одежда > Платья > Длинные платья`.
	 */
	public function get_breadcrumbs( $category_id, $result = '', $parent_id = false ) {

		if ( $parent_id === false ) {
			$term = get_term( $category_id, 'product_cat', 'OBJECT' );
		} else {
			$term = get_term( $parent_id, 'product_cat', 'OBJECT' );
		}

		if ( is_wp_error( $term ) ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s $category_id = %3$s %4$s wp_error; %5$s: %6$s; %7$s: %8$s',
				$this->get_feed_id(),
				__( 'The function get_term() for', 'xml-for-google-merchant-center' ),
				$category_id,
				__( 'returned', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'trait-xfgmc-t-variable-get-product-type.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
			new XFGMC_Error_Log( sprintf( 'error_key: %1$s; error_message: %2$s; error_data: %3$s;',
				$term->get_error_code(),
				$term->get_error_message(),
				$term->get_error_data()
			) );
		} else if ( null === $term ) {
			new XFGMC_Error_Log( sprintf( 'FEED #%1$s; ERROR: %2$s $category_id = %3$s %4$s null; %5$s: %6$s; %7$s: %8$s',
				$this->get_feed_id(),
				__( 'The function get_term() for', 'xml-for-google-merchant-center' ),
				$category_id,
				__( 'returned', 'xml-for-google-merchant-center' ),
				__( 'File', 'xml-for-google-merchant-center' ),
				'trait-xfgmc-t-variable-get-product-type.php',
				__( 'Line', 'xml-for-google-merchant-center' ),
				__LINE__
			) );
		} else {
			if ( is_object( $term ) ) {
				if ( $term->parent == 0 ) {
					$product_type_home = common_option_get(
						'xfgmc_product_type_home',
						'',
						$this->get_feed_id(),
						'xfgmc'
					);
					if ( empty( $product_type_home ) ) {
						if ( empty( $result ) ) {
							$result = $term->name;
						} else {
							$result = sprintf( '%s > %s', $term->name, $result );
						}
					} else {
						if ( empty( $result ) ) {
							$result = sprintf( '%s > %s', $product_type_home, $term->name );
						} else {
							$result = sprintf( '%s > %s > %s', $product_type_home, $term->name, $result );
						}
					}
				} else {
					if ( empty( $result ) ) {
						$result = $term->name;
						$result = $this->get_breadcrumbs( $category_id, $result, $term->parent );
					} else {
						$result = sprintf( '%s > %s', $term->name, $result );
						$result = $this->get_breadcrumbs( $category_id, $result, $term->parent );
					}
				}
			}
		}
		return $result;
	}

}