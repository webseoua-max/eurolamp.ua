<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see              https://docs.woocommerce.com/document/template-structure/
 * @package          WooCommerce/Templates
 * @version          3.6.0
 * @flatsome-version 3.16.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || false === wc_get_loop_product_visibility( $product->get_id() ) || ! $product->is_visible() ) {
  return;
}

// Check stock status.
$out_of_stock = !$product->is_in_stock();

// Extra post classes.
$classes   = array();
$classes[] = 'product-small';
$classes[] = 'col';
$classes[] = 'has-hover';

if ( $out_of_stock ) $classes[] = 'out-of-stock';

?>

<div <?php wc_product_class( $classes, $product ); ?>>
  <div class="col-inner" data-product="<?php echo $product->get_id(); ?>">
    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
    <div class="product-small box <?php echo flatsome_product_box_class(); ?>">
      <div class="box-image">
        <div class="<?php echo flatsome_product_box_image_class(); ?>">
          <a href="<?php echo get_the_permalink(); ?>" aria-label="<?php echo esc_attr( $product->get_title() ); ?>">
            <?php
              /**
               *
               * @hooked woocommerce_get_alt_product_thumbnail - 11
               * @hooked woocommerce_template_loop_product_thumbnail - 10
               */
              do_action( 'flatsome_woocommerce_shop_loop_images' );
            ?>
          </a>
        </div>
        <div class="image-tools is-small top right">
          <?php do_action( 'flatsome_product_box_tools_top' ); ?>
        </div>
        <div class="image-tools is-small hide-for-small bottom left show-on-hover">
          <?php do_action( 'flatsome_product_box_tools_bottom' ); ?>
        </div>
        <div class="image-tools <?php echo flatsome_product_box_actions_class(); ?>">
          <?php do_action( 'flatsome_product_box_actions' ); ?>
        </div>
        <?php if ( $out_of_stock ) { ?>
        	<div class="out-of-stock-label">
        		<?php 
					  	$is_pre_order = get_field('product_pre_order');
					  	if ($is_pre_order) {
					  		_e( 'Передзамовлення', 'woocommerce' );
					  	} else {
					  		_e( 'Out of stock', 'woocommerce' );
					  	}
				  	?>
      		</div>
        <?php } ?>
      </div>

      <div class="box-text <?php echo flatsome_product_box_text_class(); ?>">
        <?php
          do_action( 'woocommerce_before_shop_loop_item_title' );

          echo '<div class="title-wrapper">';
          do_action( 'woocommerce_shop_loop_item_title' );
          echo '</div>';


          echo '<div class="price-wrapper">';
          do_action( 'woocommerce_after_shop_loop_item_title' );
          echo '</div>';

          $wrap_class = $out_of_stock ? 'out-of-stock' : '';
        ?>
        <div class="added-wrapper <?php echo $wrap_class; ?>">

          <?php if ($out_of_stock): ?>
            <?php wc_add_pre_order_btn() ?>

          <?php else: ?>
            <div class="quantity buttons_added">
              <input type="button" value="-" class="minus button is-form">
              <label class="screen-reader-text" for="quantity_<?php echo $product->get_id() ?>"><?php echo esc_attr( $product->get_title() ); ?> кількість</label>
              <input
                type="number"
                id="quantity_<?php echo $product->get_id() ?>"
                class="input-text qty text"
                name="quantity"
                value="1"
                title="Qty"
                size="<?php echo strlen($product->stock_quantity); ?>"
                min="1"
                max="<?php echo $product->stock_quantity; ?>"
                step="1"
                placeholder=""
                inputmode="numeric"
                autocomplete="off"
              >
              <input type="button" value="+" class="plus button is-form">
            </div>
          <?php endif ?>

          <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>

        <?php //do_action( 'flatsome_product_box_after' ); ?>
      </div>
    </div>
    <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
    </div>
</div><?php /* empty PHP to avoid whitespace */ ?>
