<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
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

				/* Provide a page based on the current category's slug:
				 * The slug is the same, but with an additional "-page",
				 * for instance, for the category "modes", it is "modes-page".
				 */
				$cat = get_query_var('cat');
				$currentcat = get_category ($cat);
				$pagename = $currentcat->slug.'-page';
				$page = get_page_by_path( $pagename );
			?>

			<?php if ( $the_query->have_posts() ) : ?>

			<header class="archive-header">
				<h1 class="archive-title"><?php printf( '%s', single_cat_title( '', false ) ); ?></h1>

				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>

		<div class="entry-meta">

				<?php if ( $paged < 2 ) :
					$pagemodified = $page->post_modified;
					$sanitizedpagemodified = mysql2date( 'Y-m-d', $pagemodified, false );
					echo ' (';
					_e ( 'Last updated' );
					echo ': <time itemprop="dateModified">';
					echo $sanitizedpagemodified;
					echo '</time>)';
				endif; ?>

<?php if( empty( $page ) ) : ?>
	<span>It does not exist.</span>
<?php else : ?>
	<span>It exists.</span>
<?php endif; ?>

		</div><!-- .entry-meta -->



			</header><!-- .archive-header -->

				<?php if ( $paged < 2 ) :
					/* Include a page based on the current category's slug:
					 * The slug is the same, but with an additional "-page",
					 * for instance, for the category "modes", it is "modes-page".
					 * Also, apply (m)qtranslate.
					 */
					$pagecontent = $page->post_content;
					$lang = qtrans_getLanguage();
					$translatedcontent = qtrans_use( $lang, $pagecontent, false );
					echo $translatedcontent;
				endif; ?>



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
