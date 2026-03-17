<?php
/**
 * Product loop sale flash
 *
 * @author           WooThemes
 * @package          WooCommerce/Templates
 * @version          1.6.4
 * @flatsome-version 3.16.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$badge_style = get_theme_mod( 'bubble_style', 'style1' );

// Fix deprecated.
if($badge_style == 'style1') $badge_style = 'circle';
if($badge_style == 'style2') $badge_style = 'square';
if($badge_style == 'style3') $badge_style = 'frame';

?>
<div class="badge-container is-larger absolute left top z-1">
<?php 
global $post;
$product_id = $post->ID;
$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));
$text        = $custom_text ? $custom_text : __( 'Sale!', 'woocommerce' );
if (in_array(254, $product_categories)) {
	echo '<div class="badge"><div class="sale-badge badge-inner callout-new-bg is-small">';
	echo $text = flatsome_presentage_bubble( $product, $text ); 
	echo '</div></div>';
}
if (in_array(2766, $product_categories)) {
	echo '<div class="badge"><div class="sale-badge badge-inner callout-new-bg is-small">';
	echo $text = flatsome_presentage_bubble( $product, $text ); 
	echo '</div></div>';
}
?>
<?php echo apply_filters( 'flatsome_product_labels', '', $post, $product, $badge_style); ?>
</div>