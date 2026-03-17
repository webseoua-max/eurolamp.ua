<?php
/**
 * Posts single post.
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

if ( have_posts() ) : ?>

<?php /* Start the Loop */ ?>

<?php while ( have_posts() ) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="article-inner <?php flatsome_blog_article_classes(); ?>">
		<?php get_template_part( 'template-parts/posts/content', 'single' ); ?>
	</div>
	<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '' );
			if ( $tags_list ) :
		?>
		<span class="tags-links single__meta">
			<?php printf($tags_list ); ?>
		</span>
		<?php endif; // End if $tags_list ?>
</article>

<?php endwhile; ?>

<?php else : ?>

	<?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>
