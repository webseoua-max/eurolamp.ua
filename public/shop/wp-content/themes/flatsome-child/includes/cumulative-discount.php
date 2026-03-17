<?php
  /* Settings */
  // if( function_exists('acf_add_options_page') ) {

  //   acf_add_options_page(array(
  //       'page_title'    => 'Налаштування Лояльності',
  //       'menu_title'    => 'Налаштування Лояльності',
  //       'menu_slug'     => 'theme-discount-settings',
  //       'capability'    => 'edit_posts',
  //       'redirect'      => false
  //   ));

  // }

  function get_cumulative_orders() {
    global $current_user; // The WP_User Object

    if(!($current_user && $current_user->ID)) return 0;

    $cumulative_orders = 0;
    $orders = wc_get_orders([
      'customer_id' => $current_user->ID,
      'limit' => -1,
      'status' => ['wc-completed'],
    ]);

    foreach ($orders as $key => $order) {
      $cumulative_orders = $cumulative_orders + $order->calculate_totals();
    }

    return $cumulative_orders;
  }

  function get_cumulative_fee() {
    $steps = get_field('discount_steps', 'option');
    $current_discount = 0;
    $cumulative_orders = get_cumulative_orders();

    if($steps) {
      foreach ($steps as $i => $step) {
        if($cumulative_orders >= $step['total']) {
          $current_discount = $step['discount'];
        }
      }
    }

    return $current_discount;
  }

  function view_woocommerce_account_content(  ) {

      $steps = get_field('discount_steps', 'option');
      $step_amount = count($steps);
      $cumulative_orders = get_cumulative_orders();
      $current_discount = 0;
      $check_total = 0;
      $check_step = -999;
      $current_left = 0;

      if(!$steps) return '';

      ?>
      <div class="discount-system">
        <div class="discount-wrapper">
          <div class="discount-item" style="left: 0%">
            <span class="discount-value">0%</span>
            <span class="discount-divider"></span>
            <span class="discount-label">0<?= get_woocommerce_currency_symbol(); ?></span>
          </div>
          <?php foreach ($steps as $i => $step): ?>
            <?php
              if($cumulative_orders >= $step['total']) {
                $current_discount = $step['discount'];
                $check_total = $step['total'];
                $check_step = $i;
              }
              if($check_step+1 == $i) {
                $part_step = ($step['total'] - $check_total) / $step['total'] * (1/$step_amount);
                $current_left = ((($check_step+1)/$step_amount) + $part_step) * 100;
              }
            ?>
            <div class="discount-item" style="left: <?php echo (($i+1)/$step_amount)*100; ?>%">
              <span class="discount-value"><?= $step['discount']; ?>%</span>
              <span class="discount-divider"></span>
              <span class="discount-label"><?= $step['total']; ?><?= get_woocommerce_currency_symbol(); ?></span>
            </div>
          <?php endforeach ?>
          <div class="discount-progress">
            <div class="discount-result" style="width: <?= $current_left; ?>%"></div>
          </div>
        </div>
        <p><b>Сума покупок :</b> <?php echo $cumulative_orders; ?> <?= get_woocommerce_currency_symbol(); ?></p>
        <p><b>Поточна знижка :</b> <?php echo $current_discount; ?> %</p>
      </div>
      <?php
  };
  add_action( 'woocommerce_account_content', 'view_woocommerce_account_content' );

  function custom_cumulative_fee( $cart ) {
    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    $percent = get_cumulative_fee(); // 15%
    $cart_total = $cart->cart_contents_total; 
    $fee = $cart_total * $percent / 100;

    if ( $fee != 0 ) {
      $cart->add_fee( pll__('Програма лояльності'), -$fee, false );
    }
  }
  // add_action( 'woocommerce_cart_calculate_fees', 'custom_cumulative_fee', 10, 1);
?>