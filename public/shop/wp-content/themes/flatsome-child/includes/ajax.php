<?php

function update_free_delivery() {
  $price_shipping = 2999 - WC()->cart->cart_contents_total;
	?>
	<?php if ($price_shipping > 0): ?>
		<div class="discount-shipping-block">
			<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/shipping.png" alt="shipping icon" class="discount-shipping-block__icon">
			<p class="discount-shipping-block__label"><?php pll_e('Безкоштовна доставка від 2999 грн'); ?></p>
			<hr class="discount-shipping-block__devider">
			<p class="discount-shipping-block__text"><?php echo strtr(pll__('Вам бракує лише $price_shipping грн. щоб її отримати'), ['$price_shipping' => $price_shipping]); ?></p>
		</div>
	<?php endif ?>

	<?php
  wp_die();
}
add_action( 'wp_ajax_update_free_delivery', 'update_free_delivery' );
add_action( 'wp_ajax_nopriv_update_free_delivery', 'update_free_delivery' );


function download_feed_file() {
	header("Access-Control-Allow-Origin: *");

  if (isset($_GET['file'])) {
		$uploads = wp_upload_dir();
    $file = basename(urldecode($_GET['file']));
    $filepath = $uploads['path'] ."/webtoffee_product_feed/". $file;

		echo file_get_contents($filepath);

  //   if (file_exists($filepath)) {
  //       if (ob_get_level()) {
  //           ob_end_clean();
  //       }

  //       // header('Content-Description: File Transfer');
  //       // header('Content-Type: application/octet-stream');
  //       // header('Content-Disposition: attachment; filename="' . $file . '"');
  //       // header('Expires: 0');
  //       // header('Cache-Control: must-revalidate');
  //       // header('Pragma: public');
  //       // header('Content-Length: ' . filesize($filepath));

  //       // readfile($filepath);

  // 			wp_die();
  //   } else {
  //       http_response_code(404); // File not found
  //       wp_die("Error: File not found.");
  //   }
	// } else {
	//     wp_die("Error: No file specified for download.");
	}
  wp_die();
}
add_action( 'wp_ajax_download_feed_file', 'download_feed_file' );
add_action( 'wp_ajax_nopriv_download_feed_file', 'download_feed_file' );

?>