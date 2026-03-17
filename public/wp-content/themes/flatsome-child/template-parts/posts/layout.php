<?php
/**
 * Posts layout.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */
?>
<section class="section">
	<div class="section-content relative">
		<div class="text-left container">
		<?php
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
		}
		?>
		</div>
		<div class="page-title-inner container align-center text-center flex-row-col medium-flex-wrap">
			<div class="title-wrapper flex-col">
			<?php if(!is_single()) { ?>
				<h1 class="entry-title mb-0">
				<?php
					if ( is_category() ) :
						printf( '<span>' . single_cat_title( '', false ) . '</span>' );

					elseif ( is_tag() ) :
						printf( '<span>' . pll_e('Тэг: ') . single_tag_title( '', false ) . '</span>' );

					elseif ( is_search() ) :
						printf( __( 'Search Results for: %s', 'flatsome' ), '<span>' . get_search_query() . '</span>' );

					elseif ( is_author() ) :
						/* Queue the first post, that way we know
						* what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'Author Archives: %s', 'flatsome' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
						/* Since we called the_post() above, we need to
						* rewind the loop back to the beginning that way
						* we can run the loop properly, in full.
						*/
						rewind_posts();

					elseif ( is_day() ) :
						printf( __( 'Daily Archives: %s', 'flatsome' ), '<span>' . get_the_date() . '</span>' );

					elseif ( is_month() ) :
						printf( __( 'Monthly Archives: %s', 'flatsome' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

					elseif ( is_year() ) :
						printf( __( 'Yearly Archives: %s', 'flatsome' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

					elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
						_e( 'Asides', 'flatsome' );

					elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
						_e( 'Images', 'flatsome');

					elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
						_e( 'Videos', 'flatsome' );

					elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
						_e( 'Quotes', 'flatsome' );

					elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
						_e( 'Links', 'flatsome' );

					else :
						pll_e('Новости');

					endif;
					?>
				</h1>	
				<?php } else { 
					echo '<h1 class="entry-title">' . get_the_title() . '</h1>'; ?>
					<div class="text-center fw-600">
					<?php echo '<p class="date__single">'. pll_e('Дата публикации') . get_the_time('d M Y', get_the_ID()) .'<p>'; ?>
					</div>
				<?php the_post_thumbnail('large');  }  ?>
			</div>	
		</div>	
	</div>	
</section>
<?php
do_action('flatsome_before_blog');
?>
<?php if(!is_single() && get_theme_mod('blog_featured', '') == 'top'){ get_template_part('template-parts/posts/featured-posts'); } ?>
<?php if(!is_single()) { echo do_shortcode('[block id="title-blog"]');}  ?>
<div class="<?php if(!is_single()) { 
	echo ('blog__wrap');
  } else {
	echo ('blog__single');
  } ?>">
	<div class="row align-center">
		<div class="large-<?php if(!is_single()) { echo ('12');} else { echo ('10'); } ?> col">
		<?php if(!is_single() && get_theme_mod('blog_featured', '') == 'content'){ get_template_part('template-parts/posts/featured-posts'); } ?>
		<?php
			if(is_single()){
				get_template_part( 'template-parts/posts/single-post');
				comments_template();
			} elseif(get_theme_mod('blog_style_archive', '') && (is_archive() || is_search())){
				get_template_part( 'template-parts/posts/archive', get_theme_mod('blog_style_archive', '') );
			} else{
				get_template_part( 'template-parts/posts/archive', get_theme_mod('blog_style', 'normal') );
			}
		?>
		</div>
	</div>
</div>
<?php if(is_single()) { echo do_shortcode('[block id="blog-footer-ru"]');}  ?>

<?php do_action('flatsome_after_blog');
