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

/* Provide a page based on the current category's slug:
 * The slug is the same, but with an additional "-page",
 * for instance, for the category "modes", it is "modes-page".
 */
$currentcat = get_query_var('cat');
$currentcatname = get_category ($cat);
$catpagename = $currentcatname->slug.'-page';
$catpage = get_page_by_path( $catpagename );

get_header();

if ( empty( $catpage ) ) : /*bla*/
	if ( have_posts() ) :
			?>
			<header>
				<h1><?php
					/*printf( __( 'Category Archives: %s', 'twentyfourteen' ), single_cat_title( '', false ) );*/
					printf( single_cat_title( '', false ) );
					if ( $paged >= 2 ) printf( ' (' . __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) . ')' );
				?></h1>
			</header>
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

		// Previous/next page navigation.
		twentyfourteen_paging_nav();

	else : /* ( have_posts() ) */

		// If no content, include the "No posts found" template.
		get_template_part( 'content', 'none' );

	endif; /* ( have_posts() ) */


else : /*( empty( $catpage ) ) */

/*	// Remove category with slug "current" from query
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
	$the_query = new WP_Query( $args );*/

$first_page_post_count = 2;
$subsequent_pages_post_count = 4;
$paged = $paged ? $paged : 1;

if($paged > 1){
	// not first page
	$posts_per_page = $subsequent_pages_post_count;

	if($paged == 2){
		// second page
		$offset = $first_page_post_count;
	} else {
		// subsequent pages
		$offset = $first_page_post_count + ($subsequent_pages_post_count * ($paged - 2));
	}

} else {
	// first page
	$offset = 0;
	$posts_per_page = $first_page_post_count;
}

/*
'posts_per_page' => $posts_per_page,
'offset' => $offset

$query->set( 'posts_per_page', "$posts_per_page" );
$query->set( 'offset', "$offset" );
*/


//query_posts( $query_string . "&posts_per_page=$posts_per_page&offset=$offset" );

//$args = array_merge( $wp_query->query_vars, array( 'posts_per_page' => $posts_per_page, 'offset' => $offset ) );
//query_posts( $args );


	if ( have_posts() ) : ?>
			<header>
				<h1><?php
					//printf( '%s', single_cat_title( '', false ) );
					$catpageid = $catpage->ID;
					$catpagetitle = get_the_title( $catpageid );
					$customtitle = get_post_meta( $catpageid, "custom_title", true );
		if ( $customtitle ) :
					$currentlanguage = qtranxf_getLanguage();
					$translatedcustomtitle = qtranxf_use( $currentlanguage, $customtitle, true );
					echo '<span title="' . $catpagetitle . '" class="tengwar">' . $translatedcustomtitle . '</span>';
		else : /* ( $customtitle ) */
					echo $catpagetitle;
		endif; /* ( $customtitle ) */
				?></h1>
<?php
		if ( $paged < 2 ) : ?>
				<div><?php
					$fake_modified_date = get_post_meta( $catpageid, 'fake_modified_date', true );
					$catpagemodified = $catpage->post_modified;
					$sanitizedpagemodified = mysql2date( 'Y-m-d', $catpagemodified, false );
					$catpagecreated = $catpage->post_date;
					$sanitizedpagecreated = mysql2date( 'Y-m-d', $catpagecreated, false );
			if ( $fake_modified_date ) :
					twentyfourteentengwar_posted_on( $fake_modified_date, $sanitizedpagecreated );
			else :
					twentyfourteentengwar_posted_on( $sanitizedpagemodified, $sanitizedpagecreated );
			endif; ?></div>
			</header>

			<?php
			/* Include a page based on the current category's slug:
			 * The slug is the same, but with an additional "-page",
			 * for instance, for the category "modes", it is "modes-page".
			 * Also, apply (m)qtranslate.
			 */
			$catpagecontent = $catpage->post_content;
			$lang = qtranxf_getLanguage();
			$translatedcontent = qtranxf_use( $lang, $catpagecontent, false );
			echo $translatedcontent; ?>

<?php
		else : /* ( $paged < 2 ) */ ?>
			</header>
<?php
		endif; /* ( $paged < 2 ) */ ?>

			<section id="archives">
				<h2 id="archive"><?php
					printf( __( 'Archives', 'twentyfourteen' ) );
					if ( $paged >= 2 ) printf( ' (' . __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) . ')' );
				?></h2>

				<ul>
<?php
		// Start the Loop.
/*
 * Regular Loop:
 */
		while ( have_posts() ) : the_post();

			// Include the post format-specific template for the content. If you want to
			// use this in a child theme, then include a file called called content-___.php
			// (where ___ is the post format) and that will be used instead.
			get_template_part( 'content', get_post_format() );

		endwhile; ?>
				</ul>
<?php
		// Previous/next page navigation.
		twentyfourteen_paging_nav();
/*
 * Regular Loop ends here.
 */

/*
 * Alternative Loop:
 * The following special loop should be used instead of the previous one
 * if the first page is set not to display an archive
 * by setting $first_page_ppp to 0
 * in the filters custom_category_query_1_of_2 and custom_category_query_2_of_2
 *
		if ( $paged > 1 ) :
			while ( have_posts() ) : the_post();

				// Include the post format-specific template for the content. If you want to
				// use this in a child theme, then include a file called called content-___.php
				// (where ___ is the post format) and that will be used instead.
				get_template_part( 'content', get_post_format() );

			endwhile;
		endif; ?>
				</ul>
<?php
			//This is the special pagination:
			twentyfourteen_paging_nav_only_from_second_page();
 *
 * Alternative Loop ends here.
 */

	else : /* ( have_posts() ) */
		// If no content, include the "No posts found" template.
		get_template_part( 'content', 'none' );
	endif; /* ( have_posts() ) */ ?>
			</section>
<?php
endif; /* ( empty( $catpage ) ) */

get_footer();
