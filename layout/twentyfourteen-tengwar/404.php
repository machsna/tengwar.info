<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>
			<header>
				<h1><?php _e( 'Page not found' ); ?></h1>
			</header>

			<p><?php _e( 'Sorry, no such page.' ); ?></p>
<?php get_footer();
