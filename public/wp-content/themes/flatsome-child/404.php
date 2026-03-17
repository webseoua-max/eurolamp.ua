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
	<div id="primary" class="content-area" style="background-color: #f3f3f3;">
		<main id="main" class="site-main container pt" role="main" style="background-color: #f3f3f3;">
			<section class="error-404 not-found mt mb">
				<div class="row">
					<div class="col medium-12 text-center">
						<span class="header-font">4</span>		
						<span class="header-img"><img src="/wp-content/uploads/error-img.png" alt="error"></span>	
						<span class="header-font z-10">4</span>	
				  </div>
					<div class="col medium-12 text-center">
						<header class="page-title">
							<h1 class="page-title"><?php pll_e('К сожалению, такой страницы не существует', 'Eurolamp') ?></h1>
						</header>
						<div class="page-content">
							<p class="error__text"><?php pll_e('Зато у нас есть другие интересные страницы. Предлагаем посетить <a href="">Каталог продукции</a> и <a href="">Наши проекты</a>', 'Eurolamp') ?></p>
							<a href="<?php echo get_home_url(); ?>" class="button primary lowercase"><span><?php pll_e('На главную', 'Eurolamp') ?></span></a>
						</div>
					</div>
				</div>
			</section>
		</main>
	</div>
<?php endif; ?>
<?php do_action( 'flatsome_after_404' ); ?>
<?php get_footer(); ?>
