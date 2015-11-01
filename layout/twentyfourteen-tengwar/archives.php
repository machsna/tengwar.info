<?php
/*
Template Name: Archives
 * This should display an overview of the archives.
 * http://codex.wordpress.org/Creating_an_Archive_Index
*/
get_header(); ?>

<div id="primary" class="content-area">

	<header class="page-header">
		<h1 class="page-title">
			<?php the_title(); ?>
		</h1>
	</header><!-- .page-header -->

	<div id="text">

		<!--<?php get_search_form(); ?>-->

		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>

	</div><!-- #text-->

</div><!-- #primary -->

<?php
get_footer();
