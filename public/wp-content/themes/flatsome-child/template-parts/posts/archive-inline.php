<?php
/**
 * Posts archive inline.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if ( have_posts() ) : ?>
<div id="post-list">

<?php /* Start the Loop */ ?>
<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-inner align-middle <?php flatsome_blog_article_classes(); ?>">
		<?php if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
		<div class="entry-image-float news__thumbnail flex">
			<div class="news-column__info">
				<p class="news-column__name">
				<?php
				$categories = get_the_category();
				if (!empty($categories)) {
						echo esc_html($categories[0]->name);
				}
				?>
				</p>
				<p class="news-column__date"><?php echo get_the_time('d M Y', get_the_ID()); ?></p>
			</div>
	 		<?php get_template_part( 'template-parts/posts/partials/entry-image', 'default'); ?>
	 	</div>
 		<?php } ?>
			<?php echo '<h2 class="entry-title"><a href="' . get_the_permalink() . '" rel="bookmark" class="plain">' . get_the_title() . '</a></h2>'; ?>
			<div class="entry-content">
				<?php if ( flatsome_option('blog_show_excerpt') || is_search())  { ?>
				<div class="entry-summary news-column">
					<?php the_excerpt(); ?>
					<div class="text-left">
						<a class="more-link button primary btn_red" href="<?php echo get_the_permalink(); ?>"><?php pll_e('Подробнее'); ?></a>
					</div>
				</div>
				<?php } else { ?>
				<?php	wp_link_pages(); ?>
			<?php }; ?>
			</div>
		<div class="clearfix"></div>		
	</div>
</article>

<?php endwhile; ?>

<?php flatsome_posts_pagination(); ?>

</div>

<?php else : ?>

	<?php get_template_part( 'template-parts/posts/content','none'); ?>

<?php endif; ?>
