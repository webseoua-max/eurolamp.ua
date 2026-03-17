<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>
<?php do_action( 'flatsome_before_404' ); ?>
<?php
if ( get_theme_mod( '404_block' ) ) :
	echo do_shortcode( '[block id="' . get_theme_mod( '404_block' ) . '"]' );
else :
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main container pt" role="main">
			<section class="error-404 not-found mb">
				<div class="row">
					<div class="col">
						<?php  echo do_shortcode('[wbcr_snippet id="2668"]') ?>
					</div>
					<div class="col medium-12 text-center block__error align-center">
					  <p class="error__title">404</p>
						<h1 class="page-title"><?php  pll_e('Oops! That page can&rsquo;t be found.'); ?></h1>
						<div class="page-content">
							<p><?php  pll_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?'); ?></p>
							<a href="<?php echo get_home_url( ) ?>" class="button primary"><?php  pll_e('На головну') ?></a>
						</div>
						<img src="/shop/wp-content/uploads/error.png" alt="error">
					</div>
				</div>
			</section>
		</main>
	</div>
<?php endif; ?>
<?php do_action( 'flatsome_after_404' ); ?>
<?php get_footer(); ?>
