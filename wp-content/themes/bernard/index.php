<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Bernard
 */

get_header(); ?>

		<div class="content">
			<div class="badges">
				<div class="badge gallery_link">
					<?php $sydney = WebcomicTag::webcomic_collection_image( 'full', 'webcomic2' ); ?>
					<?php last_webcomic_link('%link', $sydney, false, false, '', 'webcomic2'); ?>
				</div>
				<div class="badge gallery_link">
					<?php $catchphrase = WebcomicTag::webcomic_collection_image( 'full', 'webcomic3' ); ?>
					<?php last_webcomic_link('%link', $catchphrase, false, false, '', 'webcomic3'); ?>
				</div>
				<div class="badge video">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Home Video Widget') ) : ?>
						<?php dynamic_sidebar( 'sidebar-6' ); ?>
					<?php endif; ?>
					<!-- <iframe width="310" height="200" src="http://www.youtube.com/embed/GYc_p_TieLM" frameborder="0" allowfullscreen></iframe> -->
				</div>
			</div>
			<div class="posts">
				<?php if ( have_posts() ) : ?>

					<?php twentyeleven_content_nav( 'nav-above' ); ?>

					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php get_template_part( 'content', get_post_format() ); ?>

					<?php endwhile; ?>

					<?php twentyeleven_content_nav( 'nav-below' ); ?>

				<?php else : ?>

					<article id="post-0" class="post no-results not-found">
						<header class="entry-header">
							<h1 class="entry-title"><?php _e( 'Nothing Found', 'twentyeleven' ); ?></h1>
						</header><!-- .entry-header -->

						<div class="entry-content">
							<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyeleven' ); ?></p>
							<?php get_search_form(); ?>
						</div><!-- .entry-content -->
					</article><!-- #post-0 -->

				<?php endif; ?>
			</div>
			<div class="right_bar">
				<?php get_sidebar(); ?>
			</div>
		</div>

<?php get_footer(); ?>