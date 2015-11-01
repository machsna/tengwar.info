<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>><head>

	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php
		wp_title( '•', true, 'right' );
		?></title>
<?php
	custom_qtranxf_header();
	my_custom_feed_links();
	my_custom_feed_links_extra();
?>
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet"  href="<?php echo bloginfo('stylesheet_directory'); ?>/style.css" type="text/css" />
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]--> 
<?php
	/*
	 * the following are generated with
	 * http://realfavicongenerator.net
	 * and with
	 * /wp-content/themes/twentyfourteen-tengwar/tengwar-files/favicon310.xcf
	 */
?>
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png"><link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png"><link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png"><link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png"><link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png"><link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png"><link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png"><link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png"><link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png"><link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32"><link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192"><link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96"><link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16"><link rel="manifest" href="/manifest.json"><meta name="msapplication-TileColor" content="#00a300"><meta name="msapplication-TileImage" content="/mstile-144x144.png"><meta name="theme-color" content="#9ae269">
<?php /*<link rel="profile" href="http://gmpg.org/xfn/11" />*/?>
<?php /*<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />*/?>
<?php /*wp_head();*/ ?>

</head><body itemscope itemtype="http://schema.org/WebPage"<?php if ( is_front_page() )	echo ' class="home"'; ?>>

	<nav id="primary-navigation" role="navigation">
		<a class="screen-reader-text skip-link" href="#primary"><?php _e( 'Skip to content', 'twentyfourteen' ); ?></a>
<?php
			if ( function_exists( 'qtranxf_generateLanguageSelectCode' ) ) {
				custom_qtranxf_generateLanguageSelectCode();
			}
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container' => false,
				'menu_id' => '',
				'items_wrap' => "\n\t\t".'<ul id="%1$s">'."\n\t\t\t".'%3$s'."\n\t\t".'</ul>',
				'walker' => new MV_Cleaner_Walker_Nav_Menu ) );
		?>

	</nav>

	<main>
		<article id="primary">
