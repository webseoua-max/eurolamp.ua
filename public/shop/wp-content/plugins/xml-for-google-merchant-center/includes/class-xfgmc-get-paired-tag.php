<?php

/**
 * Creates a paired tag.
 *
 * @link       https://icopydoc.ru
 * @since      0.1.0
 * @version    4.0.0 (02-06-2025)
 *
 * @package    XFGMC
 * @subpackage XFGMC/includes
 */

/**
 * Creates a paired tag.
 * 
 * Usage example: `new XFGMC_Get_Paired_Tag( 'price', 1500, [ 'from' => 'true' ] );`
 *
 * @since      0.1.0
 * @package    XFGMC
 * @subpackage XFGMC/includes
 * @author     Maxim Glazunov <icopydoc@gmail.com>
 */

class XFGMC_Get_Paired_Tag extends XFGMC_Get_Closed_Tag {

	/**
	 * The tag value.
	 *
	 * @access protected
	 * @var array $tag_value.
	 */
	protected $tag_value;

	/**
	 * Array of tag attributes.
	 *
	 * @access protected
	 * @var array $tag_attributes_arr.
	 */
	protected $tag_attributes_arr;

	/**
	 * Constructor.
	 * 
	 * @param string $tag_name
	 * @param mixed $tag_value
	 * @param array $tag_attributes_arr
	 */
	public function __construct( $tag_name, $tag_value = '', array $tag_attributes_arr = [] ) {
		parent::__construct( $tag_name );

		if ( ! empty( $tag_value ) ) {
			$this->tag_value = $tag_value;
		} else if ( $tag_value === (float) 0 || $tag_value === (int) 0 ) {
			// если нужно передать нулевое значение в качестве value
			$this->tag_value = $tag_value;
		}

		if ( ! empty( $tag_attributes_arr ) ) {
			$this->tag_attributes_arr = $tag_attributes_arr;
		}
	}

	/**
	 * Get the a paired tag.
	 * 
	 * @return string
	 */
	public function __toString() {

		if ( empty( $this->get_tag_name() ) ) {
			return '';
		} else {
			return sprintf( "<%1\$s%3\$s>%2\$s</%1\$s>",
				$this->get_tag_name(),
				$this->get_tag_value(),
				$this->get_attr_tag()
			) . PHP_EOL;
		}

	}

	/**
	 * Get the tag value.
	 * 
	 * @return mixed
	 */
	public function get_tag_value() {

		return $this->tag_value;

	}

	/**
	 * Get the tag attributes.
	 * 
	 * @return string
	 */
	public function get_attr_tag() {

		$res_string = '';
		if ( ! empty( $this->tag_attributes_arr ) ) {
			foreach ( $this->tag_attributes_arr as $key => $value ) {
				$res_string .= sprintf( ' %s="%s"', $key, $value );
			}
		}
		return $res_string;

	}

}