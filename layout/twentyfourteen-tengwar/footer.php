<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>
		</article>
	</main>

<?php
	get_sidebar( 'content' );
	if ( has_nav_menu( 'secondary' ) ) get_sidebar();
	wp_footer(); ?>
</body></html>
