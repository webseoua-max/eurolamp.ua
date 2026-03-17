<?php

/**
 * Trait for simple products.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (10-05-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes/feeds/traits/simple
 */

/**
 * The trait adds `get_brand` method.
 * 
 * This method allows you to return the `brand` tag.
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
trait XFGMC_T_Simple_Get_Brand {

	/**
	 * Get `brand` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324351
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:brand>Google</g:brand>`.
	 */
	public function get_brand( $tag_name = 'g:brand', $result_xml = '' ) {

		$brand_name = '';
		$brand = common_option_get(
			'xfgmc_brand',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( $brand === 'woocommerce_brands' ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( ( is_plugin_active( 'perfect-woocommerce-brands/perfect-woocommerce-brands.php' )
			|| is_plugin_active( 'perfect-woocommerce-brands/main.php' )
			|| class_exists( 'Perfect_Woocommerce_Brands' ) ) && $brand === 'sfpwb' ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'pwb-brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( ( is_plugin_active( 'saphali-custom-brands-pro/saphali-custom-brands-pro.php' )
			|| class_exists( 'saphali_brands_pro' ) ) && $brand === 'saphali_brands' ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'brands' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( ( is_plugin_active( 'premmerce-woocommerce-brands/premmerce-brands.php' ) )
			&& ( $brand === 'premmercebrandsplugin' ) ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( ( is_plugin_active( 'woocommerce-brands/woocommerce-brands.php' ) )
			&& ( $brand === 'woocommerce_brands' ) ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( class_exists( 'woo_brands' ) && $brand === 'woo_brands' ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'product_brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( ( is_plugin_active( 'yith-woocommerce-brands-add-on/init.php' ) )
			&& ( $brand === 'yith_woocommerce_brands_add_on' ) ) {
			$barnd_terms = get_the_terms( $this->get_product()->get_id(), 'yith_product_brand' );
			if ( $barnd_terms !== false ) {
				foreach ( $barnd_terms as $barnd_term ) {
					$brand_name = $barnd_term->name;
					break;
				}
			}
		} else if ( $brand == 'post_meta' ) {
			$brand_post_meta_id = common_option_get(
				'xfgmc_brand_post_meta',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( get_post_meta( $this->get_product()->get_id(), $brand_post_meta_id, true ) !== '' ) {
				$brand_xml = get_post_meta( $this->get_product()->get_id(), $brand_post_meta_id, true );
				$brand_name = $brand_xml;
			}
		} else if ( $brand == 'default_value' ) {
			$brand_xml = common_option_get(
				'xfgmc_brand_post_meta',
				'',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( $brand_xml !== '' ) {
				$brand_name = $brand_xml;
			}
		} else {
			if ( $brand !== 'disabled' ) {
				$brand_name = xfgmc_replace_decode( $this->get_simple_global_attribute_value( $brand ) );
			}
		}

		$skip_brand_reason = false;
		$skip_brand_reason = apply_filters(
			'xfgmc_f_simple_skip_brand_reason',
			$skip_brand_reason,
			[ 
				'product' => $this->get_product(),
				'brand_name' => $brand_name
			],
			$this->get_feed_id()
		);
		if ( false === $skip_brand_reason ) {
			// ! обернул $tag_value в htmlspecialchars т.к у нас могут быть амперсанды
			$tag_value = htmlspecialchars( $brand_name );
		} else {
			$this->add_skip_reason( [ 
				'reason' => $skip_brand_reason,
				'post_id' => $this->get_product()->get_id(),
				'file' => 'trait-xfgmc-t-simple-get-brand.php',
				'line' => __LINE__
			] );
			return '';
		}

		$result_xml = $this->get_simple_tag( $tag_name, $tag_value );
		return $result_xml;

	}

}