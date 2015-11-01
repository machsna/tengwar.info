<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
			<header>
				<?php the_title( '<h1>', '</h1>' );	?>

				<div><?php
					/*
					 * Note: With qtranslate-x, (get_)?the_modified_date will not work
					 * Workaround: $post->post_modified, and then pass it on to the function
					 * In each instance where twentyfourteentengwar_posted_on is called ...
					 */
					$thispostmodified = $post->post_modified;
					$sanitizedthispostmodified = mysql2date( 'Y-m-d', $thispostmodified, false );
					//echo ' {updated: ' . $sanitizedthispostmodified . '}' ;
					twentyfourteentengwar_posted_on( $sanitizedthispostmodified, get_the_date() );
					?></div>
			</header>

			<?php
	the_content();
	wp_link_pages( array(
		'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
		'after'       => '</div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
	) );

	edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
?>

