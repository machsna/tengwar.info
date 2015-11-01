<?php
/*
Template Name: Page with archive for category specified in Custom Field
*/

/* This example is for a child theme of Twenty Thirteen: 
*  You'll need to adapt it the HTML structure of your own theme.
*/

get_header(); ?>

	<section id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php 
				// Remove category with slug "current" from query
				// Do something for proper pagination ( http://stackoverflow.com/questions/24840931/ )
				// Also, inc/template-tags.php has been modified so the function twentyfourteen_paging_nav knows the proper max_num_pages, see http://wordpress.stackexchange.com/questions/156819/#156824
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				// Get current category's slug:
				$current_cat = get_query_var('cat');
				$current_cat_slug = get_category ($current_cat);
				// Get category "current" ID:
				$remove_cat = get_category_by_slug('current');
				//echo 'it is '. $remove_cat->cat_ID;
				// Array that includes current category, excludes category "current", fixes pagination
				$args = array (
					'category_name' => $current_cat_slug->slug,
					'cat'      => "-$remove_cat->cat_ID",
					'paged'    => $paged, // this will be set to what WordPress thinks is the proper page to start
					);
				$the_query = new WP_Query( $args );
			?>

			<?php if ( $the_query->have_posts() ) : ?>

			<!--<header class="archive-header">-->
				<?php if ( $paged < 2 ) :
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					//if ( comments_open() || get_comments_number() ) {
					//	comments_template();
					//}
				endwhile;
				else : ?>
					<h1><?php single_cat_title( '', true ); ?></h1>
				<?php endif; ?>




			<!--</header>--><!-- .archive-header -->

			<div id="archives">
				<?php if ( $paged < 2 ) : ?>
					<h2 id="archive" class="archive-title"><?php printf( __( 'Archives', 'twentyfourteen' ) ); ?></h2>
				<?php endif; ?>

				<?php
						// Start the Loop.
						while ( $the_query->have_posts() ) : $the_query->the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

						endwhile;
						// Previous/next page navigation.
						twentyfourteen_paging_nav();

					else :
						// If no content, include the "No posts found" template.
						get_template_part( 'content', 'none' );

					endif;
				?>
			</div><!-- #archives -->
		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
