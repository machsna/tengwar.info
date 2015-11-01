<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
			<header>
				<h1><?php bloginfo( 'name' ); ?></h1>
			</header>
<?php
	if ( have_posts() ) : ?>

			<section id="archives">
				<h2><?php twentyfourteentengwar_archive_title(); ?></h2>

				<ul>
<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', get_post_format() );

		endwhile; ?>
				</ul>
<?php
		// Previous/next page navigation.
		twentyfourteen_paging_nav();

	else : /* ( have_posts() ) */
		get_template_part( 'content', 'none' );

	endif; /* ( have_posts() ) */ ?>
			</section>
<?php get_footer();
