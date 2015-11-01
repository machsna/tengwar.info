<?php
/**
 * Custom template tags for Twenty Fourteen
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( ! function_exists( 'twentyfourteen_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
//	if ( $GLOBALS['the_query']->max_num_pages < 2 ) { // modified so it does not count the excluded category, see category.php and http://wordpress.stackexchange.com/questions/156819/#156824
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = custom_paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
//		'total'    => $GLOBALS['the_query']->max_num_pages, // modified so it does not count the excluded category, see category.php and http://wordpress.stackexchange.com/questions/156819/#156824
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( 'Previous page' ),
		'next_text' => __( 'Next page' ),
		'before_page_number' => '<span class="screen-reader-text">' . _x( 'Page','add new on admin bar' ) . ' </span>',
	) );

	if ( $links ) :

	?>

				<nav class="paging-navigation" role="navigation">
					<dl>
						<dt class="screen-reader-text"><?php _e( 'Posts navigation', 'twentyfourteen' ); ?>:</dt>
						<dd><?php echo $links; ?></dd>
					</dl>
				</nav>
<?php
	endif;
}
endif;

if ( ! function_exists( 'twentyfourteen_paging_nav_only_from_second_page' ) ) :
/**
 * This is a copy of twentyfourteen_paging_nav().
 * The only difference is that it calls custom_paginate_links_only_from_second_page()
 * instead of custom_paginate_links().
 */
function twentyfourteen_paging_nav_only_from_second_page() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
//	if ( $GLOBALS['the_query']->max_num_pages < 2 ) { // modified so it does not count the excluded category, see category.php and http://wordpress.stackexchange.com/questions/156819/#156824
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = custom_paginate_links_only_from_second_page( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
//		'total'    => $GLOBALS['the_query']->max_num_pages, // modified so it does not count the excluded category, see category.php and http://wordpress.stackexchange.com/questions/156819/#156824
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Previous', 'twentyfourteen' ),
		'next_text' => __( 'Next &rarr;', 'twentyfourteen' ),
	) );

	if ( $links ) :

	?>

				<nav class="paging-navigation" role="navigation">
					<dl>
						<dt class="screen-reader-text"><?php _e( 'Posts navigation', 'twentyfourteen' ); ?>:</dt>
						<dd><?php echo $links; ?></dd>
					</dl>
				</nav>
<?php
	endif;
}
endif;

if ( ! function_exists( 'twentyfourteen_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'twentyfourteen' ); ?></h1>
		<div class="nav-links">
			<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'twentyfourteen' ) );
			else :
				previous_post_link( '%link', __( '<span class="meta-nav">Previous Post</span>%title', 'twentyfourteen' ) );
				next_post_link( '%link', __( '<span class="meta-nav">Next Post</span>%title', 'twentyfourteen' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'twentyfourteentengwar_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Fourteen 1.0
 */
/*
 * Note: With qtranslate-x, (get_)?the_modified_date will not work
 * Workaround: $post->post_modified, and then pass it on to the function
 * In each instance where twentyfourteentengwar_posted_on is called ...
 */
function twentyfourteentengwar_posted_on( $itemmodified, $itemcreated ) {

	echo '<time itemprop="datePublished" title="' . __( 'Published on:') . ' ' . $itemcreated . '">' . $itemcreated . '</time>' ;
	/*printf( '<time itemprop="datePublished" title="'.__( 'Published on:').'">%s</time>',
		esc_html( $itemcreated )
	);*/

	if ( $itemmodified != $itemcreated ) {
		echo ' (';
		_e ( 'Last updated' );
		echo ': <time itemprop="dateModified">';
		//echo the_modified_date() ;
		echo $itemmodified ;
		//printf( __( 'Last updated: %s' ),
		//	esc_attr( get_the_modified_date() )
		//);
		echo '</time>)';
	}

}
endif;

/**
 * Find out if blog has more than one category.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return boolean true if blog has more than 1 category
 */
function twentyfourteen_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'twentyfourteen_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'twentyfourteen_category_count', $all_the_cool_cats );
	}

	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so twentyfourteen_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so twentyfourteen_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in twentyfourteen_categorized_blog.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'twentyfourteen_category_count' );
}
add_action( 'edit_category', 'twentyfourteen_category_transient_flusher' );
add_action( 'save_post',     'twentyfourteen_category_transient_flusher' );

/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
	<?php
		if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
			the_post_thumbnail( 'twentyfourteen-full-width' );
		} else {
			the_post_thumbnail();
		}
	?>
	</div>

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>">
	<?php
		if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
			the_post_thumbnail( 'twentyfourteen-full-width' );
		} else {
			the_post_thumbnail();
		}
	?>
	</a>

	<?php endif; // End is_singular()
}
