<?php

// adding pre-order buton when out of stock
function wc_add_pre_order_btn() {
  global $product;

  $out_of_stock = !($product->stock_quantity > 0);
  $is_available = get_field('product_pre_order');
  ?>

  <?php if ($out_of_stock && $is_available): ?>
    <a href="#template-pre-order-form-<?php echo $product->id; ?>" class="button pre-order-btn">
      <?php pll_e('Передзамовлення'); ?>
    </a>
    <div id="template-pre-order-form-<?php echo $product->id; ?>" class="lightbox-by-id lightbox-content lightbox-white mfp-hide" style="max-width:550px ;padding:30px">
      <p class="lightbox__title text-center"><?php pll_e('Передзамовлення'); ?></p>
      <?php echo do_shortcode('[contact-form-7 id="a02d548" title="Pre-order form"]'); ?>
    </div>
    <script>
      window.addEventListener('load', function() {
        console.log("Ready");
        $.loadMagnificPopup().then(function() {
          $('.pre-order-btn').magnificPopup({
            type: 'inline',
            preloader: false,
          });
        });
      });
    </script>
  <?php endif ?>
  
  <?php
}
add_action('woocommerce_single_product_summary', 'wc_add_pre_order_btn', 30);

// Set a minimum order amount for checkout
function wc_minimum_order_amount() {
  $minimum = get_option( 'wc_minimum_order_amount_value' );

  if ( WC()->cart->total < $minimum ) {

    if( is_cart() ) {

      wc_print_notice( 
          sprintf( 'Загальна сума вашого поточного замовлення становить %s — щоб оформити замовлення, ви повинні мати замовлення на суму не менше %s' , 
            wc_price( WC()->cart->total ), 
            wc_price( $minimum )
          ), 'error' 
      );

    } else {
      wp_redirect(WC()->cart->get_cart_url());
    }
  }
}
add_action( 'woocommerce_before_checkout_form_cart_notices', 'wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' );

// Set a maximum order amount for checkout
function wc_maximum_order_amount() {
  $maximum = get_option( 'wc_maximum_order_amount_value' );

  if ( $maximum && WC()->cart->total > $maximum ) {

    if( is_cart() ) {

      wc_print_notice( 
          sprintf( 'Загальна сума вашого поточного замовлення становить %s — щоб оформити замовлення, ви повинні мати замовлення на суму не більше %s' , 
            wc_price( WC()->cart->total ), 
            wc_price( $maximum )
          ), 'error' 
      );

    } else {
      wp_redirect(WC()->cart->get_cart_url());
    }
  }
}
add_action( 'woocommerce_before_checkout_form_cart_notices', 'wc_maximum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_maximum_order_amount' );

/******* AMDIN PANEL *******/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
  /* Settings */
  function custom_woo_minimum_order_settings( $settings ) {
  
    $settings[] = array(
      'title' => 'Налаштування мінімального замовлення',
      'type' => 'title',
      'desc' => 'Встановіть мінімальну суму замовлення та налаштуйте сповіщення. Якщо мінімальна сума замовлення не буде досягнута, покупець не зможе перейти до оформлення замовлення.',
      'id' => 'wc_minimum_order_settings',
    );

    // Minimum order amount
    $settings[] = array(
      'title'             => __( 'Minimum order amount', 'woocommerce' ),
      'desc'              => __( 'Leave this empty if all orders are accepted, otherwise set the minimum order amount', 'wc_minimum_order_amount' ),
      'id'                => 'wc_minimum_order_amount_value',
      'default'           => '0',
      'type'              => 'number',
      'desc_tip'          => true,
      'css'               => 'width:70px;',
    );

    // Maximum order amount
    $settings[] = array(
      'title'             => __( 'Maximum order amount', 'woocommerce' ),
      'desc'              => __( 'Leave this empty if all orders are accepted, otherwise set the maximum order amount', 'wc_minimum_order_amount' ),
      'id'                => 'wc_maximum_order_amount_value',
      'default'           => '0',
      'type'              => 'number',
      'desc_tip'          => true,
      'css'               => 'width:70px;',
    );

    $settings[] = array( 'type' => 'sectionend', 'id' => 'wc_minimum_order_settings' );

    return $settings;
  }
  add_filter( 'woocommerce_general_settings','custom_woo_minimum_order_settings', 10, 2 );

}

/**
*   Ovveride woocommerce_button_proceed_to_checkout in WooCommerce
**/
function woocommerce_button_proceed_to_checkout() {
  $minimum = get_option( 'wc_minimum_order_amount_value' );
  $new_checkout_url = WC()->cart->get_checkout_url();
  if (WC()->cart->total >= $minimum) {
  ?>
    <a href="<?php echo $new_checkout_url; ?>" class="checkout-button button alt wc-forward">
      <?php _e( 'Proceed to checkout', 'woocommerce' ); ?>
    </a>
  <?php
  }
}

?>