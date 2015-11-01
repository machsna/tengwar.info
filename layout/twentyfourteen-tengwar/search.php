<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header();

global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();

foreach($query_args as $key => $string) {
	$query_split = explode("=", $string);
	$search_query[$query_split[0]] = urldecode($query_split[1]);
} // foreach

$search = new WP_Query($search_query);

	if ( have_posts() ) : ?>
			<header>
				<h1><?php bloginfo( 'name' ); ?></h1>
			</header>

			<section id="search-results">
				<h2><?php
					printf( __( 'Search Results for: %s', 'twentyfourteen' ), get_search_query() );
					if ( $paged >= 2 ) :
						echo ' (';
						printf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
						echo ')';
					endif;
				?></h2>
<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;
				// Previous/next post navigation.
				twentyfourteen_paging_nav(); ?>
			</section>
<?php
	else : // if ( have_posts() )
		get_template_part( 'content', 'none' );
	endif; // if ( have_posts() )

get_footer();
