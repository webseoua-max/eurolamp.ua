<?php
/**
 * Default checkout layout.
 *
 * @package          Flatsome/WooCommerce/Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	wc_get_template( 'checkout/header.php' );
	?>

	<div class="cart-container container page-wrapper page-checkout">
		<?php wc_print_notices(); ?>
		
		<?php the_content(); ?>
	</div>

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
