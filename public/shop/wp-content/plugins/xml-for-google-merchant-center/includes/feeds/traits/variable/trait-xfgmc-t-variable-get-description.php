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
 * The trait adds `get_description` and `replace_tags` methods.
 * 
 * This method allows you to return the `description` tag.
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
trait XFGMC_T_Variable_Get_Description {

	/**
	 * Get `description` tag.
	 * 
	 * @see https://support.google.com/merchants/answer/6324468
	 * 
	 * @param string $tag_name
	 * @param string $result_xml
	 * 
	 * @return string Example: `<g:description><![CDATA[<p>текст</p>]]></g:description>`.
	 */
	public function get_description( $tag_name = 'g:description', $result_xml = '' ) {

		$tag_value = '';
		$xfgmc_desc = common_option_get(
			'xfgmc_desc',
			'fullexcerpt',
			$this->get_feed_id(),
			'xfgmc'
		);
		$xfgmc_the_content = common_option_get(
			'xfgmc_the_content',
			'enabled',
			$this->get_feed_id(),
			'xfgmc'
		);
		$xfgmc_enable_tags_behavior = common_option_get(
			'xfgmc_enable_tags_behavior',
			'default',
			$this->get_feed_id(),
			'xfgmc'
		);
		$var_desc_priority = common_option_get(
			'xfgmc_var_desc_priority',
			'disabled',
			$this->get_feed_id(),
			'xfgmc'
		);

		if ( $var_desc_priority === 'enabled' || $var_desc_priority === 'on' ) {
			// если описание вариации в приоритете
			$tag_value = $this->get_offer()->get_description();
		}

		switch ( $xfgmc_desc ) {
			case "full":

				// сейчас и далее проверка на случай, если описание вариации главнее
				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_description();
				}

				break;
			case "excerpt":

				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_short_description();
				}

				break;
			case "fullexcerpt":

				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_description();
					if ( empty( $tag_value ) ) {
						$tag_value = $this->get_product()->get_short_description();
					}
				}

				break;
			case "excerptfull":

				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_short_description();
					if ( empty( $tag_value ) ) {
						$tag_value = $this->get_product()->get_description();
					}
				}

				break;
			case "fullplusexcerpt":

				if ( $var_desc_priority === 'enabled' || $var_desc_priority === 'on' ) {
					$tag_value = sprintf( '%1$s<br/>%2$s',
						$this->get_offer()->get_description(),
						$this->get_product()->get_short_description()
					);
				} else {
					$tag_value = sprintf( '%1$s<br/>%2$s',
						$this->get_product()->get_description(),
						$this->get_product()->get_short_description()
					);
				}

				break;
			case "excerptplusfull":

				if ( $var_desc_priority === 'enabled' || $var_desc_priority === 'on' ) {
					$tag_value = sprintf( '%1$s<br/>%2$s',
						$this->get_product()->get_short_description(),
						$this->get_offer()->get_description()
					);
				} else {
					$tag_value = sprintf( '%1$s<br/>%2$s',
						$this->get_product()->get_short_description(),
						$this->get_product()->get_description()
					);
				}

				break;
			case 'post_meta':

				$post_meta = common_option_get(
					'xfgmc_source_description_post_meta',
					'',
					$this->get_feed_id(),
					'xfgmc'
				);
				if ( get_post_meta( $this->get_product()->get_id(), $post_meta, true ) !== '' ) {
					$tag_value = get_post_meta( $this->get_product()->get_id(), $post_meta, true );
				} else {
					$tag_value = '';
				}

				break;
			default:

				if ( empty( $tag_value ) ) {
					$tag_value = $this->get_product()->get_description();
					$tag_value = apply_filters( 'xfgmc_f_variable_switchcase_default_description',
						$tag_value,
						[ 
							'xfgmc_desc' => $xfgmc_desc,
							'product' => $this->get_product(),
							'offer' => $this->get_offer()
						],
						$this->get_feed_id()
					);
				}

		}

		if ( empty( $tag_value ) ) {
			// схожее со строкой 43, на случай, если описание вариации имеет низкий приоритет, а другие описания пусты
			$tag_value = $this->get_offer()->get_description();
		}

		if ( ! empty( $tag_value ) ) {
			if ( $xfgmc_the_content === 'enabled' ) {
				$tag_value = html_entity_decode( apply_filters( 'the_content', $tag_value ) );
			}
			$tag_value = apply_filters(
				'xfgmc_description_filter',
				$tag_value,
				$this->get_product()->get_id(),
				$this->get_product(),
				$this->get_feed_id()
			);
			$tag_value = trim( $tag_value );
		}

		$tag_value = apply_filters(
			'xfgmc_f_variable_tag_value_description',
			$tag_value,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		if ( ! empty( $tag_value ) ) {
			$tag_value = $this->replace_tags( $tag_value, $xfgmc_enable_tags_behavior );
			$tag_value = '<![CDATA[' . $tag_value . ']]>';
			$tag_name = apply_filters(
				'xfgmc_f_variable_tag_name_description',
				$tag_name,
				[ 
					'product' => $this->get_product(),
					'offer' => $this->get_offer()
				],
				$this->get_feed_id()
			);
			$result_xml = new XFGMC_Get_Paired_Tag( $tag_name, $tag_value );
		}

		$result_xml = apply_filters(
			'xfgmc_f_variable_tag_description',
			$result_xml,
			[ 
				'product' => $this->get_product(),
				'offer' => $this->get_offer()
			],
			$this->get_feed_id()
		);
		if ( empty( $result_xml ) ) {
			// пропускаем вариации без описания
			$skip_products_without_desc = common_option_get(
				'xfgmc_skip_products_without_desc',
				'disabled',
				$this->get_feed_id(),
				'xfgmc'
			);
			if ( ( $skip_products_without_desc === 'enabled' ) && ( $tag_value == '' ) ) {
				$this->add_skip_reason( [ 
					'offer_id' => $this->get_offer()->get_id(),
					'reason' => __( 'Variation product has no description', 'xml-for-google-merchant-center' ),
					'post_id' => $this->get_offer()->get_id(),
					'file' => 'trait-xfgmc-t-variable-get-description.php',
					'line' => __LINE__
				] );
				return '';
			}
		}
		return $result_xml;

	}

	/**
	 * Summary of replace_tags.
	 * 
	 * @param string $description_xml
	 * @param string $xfgmc_enable_tags_behavior
	 * 
	 * @return string
	 */
	private function replace_tags( $tag_value, $xfgmc_enable_tags_behavior ) {

		if ( $xfgmc_enable_tags_behavior == 'default' ) {
			$tag_value = str_replace( '<ul>', '', $tag_value );
			$tag_value = str_replace( '<li>', '', $tag_value );
			$tag_value = str_replace( '</li>', '<br/>', $tag_value );
		}

		$xfgmc_enable_tags_custom = common_option_get(
			'xfgmc_enable_tags_custom',
			'',
			$this->get_feed_id(),
			'xfgmc'
		);
		if ( $xfgmc_enable_tags_behavior == 'default' ) {
			$enable_tags = '<p>,<br/>,<br>';
			$enable_tags = apply_filters( 'xfgmc_enable_tags_filter', $enable_tags, $this->get_feed_id() );
		} else {
			$enable_tags = trim( $xfgmc_enable_tags_custom );
			if ( $enable_tags !== '' ) {
				$enable_tags = '<' . str_replace( ',', '>,<', $enable_tags ) . '>';
			}
		}
		$tag_value = xfgmc_strip_tags( $tag_value, $enable_tags );
		$tag_value = str_replace( '<br>', '<br/>', $tag_value );
		$tag_value = strip_shortcodes( $tag_value );
		return $tag_value;

	}

}