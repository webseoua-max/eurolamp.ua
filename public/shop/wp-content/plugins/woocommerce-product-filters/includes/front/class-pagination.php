<?php

namespace WooCommerce_Product_Filter_Plugin\Front;

use WooCommerce_Product_Filter_Plugin\Structure\Component;
use WooCommerce_Product_Filter_Plugin\Structure\Hook_Manager;

/**
 * The Pagination component class.
 */
class Pagination extends Component {

	/**
	 * Attach component methods to hooks.
	 *
	 * @param Hook_Manager $hook_manager The Hook manager instance.
	 */
	public function attach_hooks( Hook_Manager $hook_manager ) {
		$hook_manager->add_action( 'woocommerce_before_template_part', 'before_pagination', 150, 4 );
		$hook_manager->add_action( 'woocommerce_after_template_part', 'after_pagination', -150, 4 );
	}

	/**
	 * Adds a wrap before display pagination template.
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @param string $located       Path of inclusion.
	 * @param array  $args          Arguments passed to the template.
	 */
	public function before_pagination( $template_name, $template_path, $located, $args ) {
		if ( 'loop/pagination.php' === $template_name ) {
			$total = $args['total'] ?? wc_get_loop_prop( 'total_pages' );
			if ( $total <= 1 ) {
				/**
				 * Filters the pagination wrap start HTML element.
				 */
				$pagination_wrap_start = apply_filters( 'wcpf_pagination_wrap_start', '<nav class="woocommerce-pagination">' );
				echo wp_kses_post( $pagination_wrap_start );
			}
		}
	}

	/**
	 * Closes a wrap after display pagination template.
	 *
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @param string $located       Path of inclusion.
	 * @param array  $args          Arguments passed to the template.
	 */
	public function after_pagination( $template_name, $template_path, $located, $args ) {
		if ( 'loop/pagination.php' === $template_name ) {
			$total = $args['total'] ?? wc_get_loop_prop( 'total_pages' );
			if ( $total <= 1 ) {
				/**
				 * Filters the pagination wrap end HTML element.
				 */
				$pagination_wrap_end = apply_filters( 'wcpf_pagination_wrap_end', '</nav>' );
				echo wp_kses_post( $pagination_wrap_end );
			}
		}
	}
}
