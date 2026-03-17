<?php
/**
 * Template name: Page - Left Sidebar
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

get_header(); ?>

<?php do_action( 'flatsome_before_page' ); ?>

<div  class="page-wrapper page-left-sidebar">
	<div class="row">
		<div id="content" class="content__block right col" role="main">
			<div class="page-inner">
			<?php if ( !is_front_page() ) { echo do_shortcode( '[wbcr_snippet id="2668"]' ); }  ?>
				<?php if(get_theme_mod('default_title', 0)){ ?>
					<header class="entry-header">
						<h1 class="entry-title mb"><?php the_title(); ?></h1>
					</header>
				<?php } ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php the_content(); ?>
					<?php if ( comments_open() || '0' != get_comments_number() ){
								comments_template(); } ?>
				<?php endwhile; // end of the loop. ?>
			</div>
		</div>

		<div class="sidebar__block col col-first ">
		<?php if ( !is_front_page() ) { 
      echo do_shortcode( '[ux_sidebar id="inner_sidebar"]' );
		}
		else {
		 get_sidebar(); 	
		}		
		?>	
		
		</div>
	</div>
</div>


<?php do_action( 'flatsome_after_page' ); ?>

<?php get_footer(); ?>
