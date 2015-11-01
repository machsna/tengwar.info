<?php
/**
 * Twenty Fourteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see twentyfourteen_content_width()
 *
 * @since Twenty Fourteen 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 474;
}

/**
 * Twenty Fourteen only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentyfourteen_setup' ) ) :
/**
 * Twenty Fourteen setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_setup() {

	/*
	 * Make Twenty Fourteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentyfourteen', get_template_directory() . '/languages' );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', twentyfourteen_font_url() ) );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 672, 372, true );
	add_image_size( 'twentyfourteen-full-width', 1038, 576, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'twentyfourteen' ),
		'secondary' => __( 'Secondary menu in left sidebar', 'twentyfourteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'twentyfourteen_custom_background_args', array(
		'default-color' => 'f5f5f5',
	) ) );

	// Add support for featured content.
	add_theme_support( 'featured-content', array(
		'featured_content_filter' => 'twentyfourteen_get_featured_posts',
		'max_posts' => 6,
	) );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
endif; // twentyfourteen_setup
add_action( 'after_setup_theme', 'twentyfourteen_setup' );

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'twentyfourteen_content_width' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return array An array of WP_Post objects.
 */
function twentyfourteen_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Twenty Fourteen.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'twentyfourteen_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return bool Whether there are featured posts.
 */
function twentyfourteen_has_featured_posts() {
	return ! is_paged() && (bool) twentyfourteen_get_featured_posts();
}

/**
 * Register three Twenty Fourteen widget areas.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_widgets_init() {
	require get_template_directory() . '/inc/widgets.php';
	register_widget( 'Twenty_Fourteen_Ephemera_Widget' );

	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Main sidebar that appears on the left.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Content Sidebar', 'twentyfourteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Additional sidebar that appears on the right.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'twentyfourteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'twentyfourteen_widgets_init' );

/**
 * Register Lato Google font for Twenty Fourteen.
 *
 * @since Twenty Fourteen 1.0
 *
 * @return string
 */
function twentyfourteen_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'twentyfourteen' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Lato:300,400,700,900,300italic,400italic,700italic' ), "//fonts.googleapis.com/css" );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'twentyfourteen-style', get_stylesheet_uri(), array( 'genericons' ) );
	wp_enqueue_style( 'twentyfourteen-style', get_stylesheet_uri(), array() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentyfourteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentyfourteen-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'twentyfourteen-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentyfourteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		wp_enqueue_script( 'twentyfourteen-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
		wp_localize_script( 'twentyfourteen-slider', 'featuredSliderDefaults', array(
			'prevText' => __( 'Previous', 'twentyfourteen' ),
			'nextText' => __( 'Next', 'twentyfourteen' )
		) );
	}

	wp_enqueue_script( 'twentyfourteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20140319', true );
}
add_action( 'wp_enqueue_scripts', 'twentyfourteen_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_admin_fonts() {
	wp_enqueue_style( 'twentyfourteen-lato', twentyfourteen_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'twentyfourteen_admin_fonts' );

if ( ! function_exists( 'twentyfourteen_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_the_attached_image() {
	$post                = get_post();
	/**
	 * Filter the default Twenty Fourteen attachment size.
	 *
	 * @since Twenty Fourteen 1.0
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
	$attachment_size     = apply_filters( 'twentyfourteen_attachment_size', array( 810, 810 ) );
	$next_attachment_url = wp_get_attachment_url();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID',
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id ) {
			$next_attachment_url = get_attachment_link( $next_id );
		}

		// or get the URL of the first image attachment.
		else {
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
		}
	}

	printf( '<a href="%1$s" rel="attachment">%2$s</a>',
		esc_url( $next_attachment_url ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

if ( ! function_exists( 'twentyfourteen_list_authors' ) ) :
/**
 * Print a list of all site contributors who published at least one post.
 *
 * @since Twenty Fourteen 1.0
 */
function twentyfourteen_list_authors() {
	$contributor_ids = get_users( array(
		'fields'  => 'ID',
		'orderby' => 'post_count',
		'order'   => 'DESC',
		'who'     => 'authors',
	) );

	foreach ( $contributor_ids as $contributor_id ) :
		$post_count = count_user_posts( $contributor_id );

		// Move on if user has not published a post (yet).
		if ( ! $post_count ) {
			continue;
		}
	?>

	<div class="contributor">
		<div class="contributor-info">
			<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
			<div class="contributor-summary">
				<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
				<p class="contributor-bio">
					<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
				</p>
				<a class="button contributor-posts-link" href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
					<?php printf( _n( '%d Article', '%d Articles', $post_count, 'twentyfourteen' ), $post_count ); ?>
				</a>
			</div><!-- .contributor-summary -->
		</div><!-- .contributor-info -->
	</div><!-- .contributor -->

	<?php
	endforeach;
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
/*function twentyfourteen_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( ( ! is_active_sidebar( 'sidebar-2' ) )
		|| is_page_template( 'page-templates/full-width.php' )
		|| is_page_template( 'page-templates/contributors.php' )
		|| is_attachment() ) {
		$classes[] = 'full-width';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'twentyfourteen_body_classes' );*/

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function twentyfourteen_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'twentyfourteen_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function twentyfourteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) ) . " $sep ";
	}

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	return $title;
}
add_filter( 'wp_title', 'twentyfourteen_wp_title', 10, 2 );

// Implement Custom Header features.
require get_template_directory() . '/inc/custom-header.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';

/*
 * modified: remove qtranslate non-HTML5 header
 * http://wordpress.org/support/topic/how-to-remove-a-meta-tag
 * This really also removes rel="alternate" links, but I can do without those
 */
remove_action('wp_head', 'qtranxf_header');

/*
 * modified: remove wpautop which inserts some trailing </p> tags:
 * http://codex.wordpress.org/Function_Reference/wpautop
 * I have identified the problem being at wpautop thanks to the following page:
 * http://wordpress.org/support/topic/how-to-remove-the-ltpgts-from-the_content
 */
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );


/*
 * modified: add class "current-menu-item" to menu with ID 126 (link to /archives/ page)
 * http://wordpress.org/support/topic/how-do-i-set-up-conditional-current-menu-item-id-attributes#post-2344804
 */
/*add_filter( 'nav_menu_css_class', 'add_custom_class', 10, 2 );

function add_custom_class( $classes = array(), $menu_item = false ) {
	if ( is_single() && in_category( 'tengwar-blog' ) && 126 == $menu_item->ID && ! in_array( 'current-menu-item', $classes ) ) {
        $classes[] = 'parent-menu-item';
    }
    return $classes;
}*/


}

/*
 * Cleanup header
 * http://stackoverflow.com/questions/3080811/wp-head-remove-action-not-working-in-wp3-0#3081449
 */
function clean_head () {
	remove_action( 'wp_head',             'feed_links',                    2     );
	remove_action( 'wp_head',             'feed_links_extra',              3     );
	remove_action( 'wp_head',             'rsd_link'                             );
	remove_action( 'wp_head',             'wlwmanifest_link'                     );
	remove_action( 'wp_head',             'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head',             'wp_print_styles',               8     );
	remove_action( 'wp_head',             'wp_print_head_scripts',         9     );
	remove_action( 'wp_head',             'wp_generator'                         );
	remove_action( 'wp_head',             'rel_canonical'                        );
	remove_action( 'wp_head',             'wp_shortlink_wp_head',          10, 0 );
	remove_action( 'wp_head',             'wp_enqueue_scripts',            1     );
// The following are probably not used anyway:
	remove_action( 'wp_head',             'index_rel_link'                       );
	remove_action( 'wp_head',             'parent_post_rel_link',          10, 0 );
	remove_action( 'wp_head',             'start_post_rel_link',           10, 0 );
	remove_action( 'wp_head',             'locale_stylesheet'                    );
//	remove_action( 'publish_future_post', 'check_and_publish_future_post', 10, 1 );
	remove_action( 'wp_head',             'noindex',                       1     );
	remove_action( 'wp_footer',           'wp_print_footer_scripts'              );
//	remove_action( 'template_redirect',   'wp_shortlink_header',           11, 0 );
// I am not sure what this is good for:	
//	add_action('widgets_init', 'my_remove_recent_comments_style');
/*	function my_remove_recent_comments_style() {
		global $wp_widget_factory;
		remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	}
*/}
add_action('init', 'clean_head');


/**
 * Display the links to the general feeds.
 *
 * copied from /wp-includes/general-template.php
 *
 * @param array $args Optional arguments.
 */
function my_custom_feed_links( $args = array() ) {
	if ( !current_theme_supports('automatic-feed-links') )
		return;

	$defaults = array(
		/* translators: Separator between blog name and feed type in feed links */
		'separator'	=> _x('&raquo;', 'feed link'),
		/* translators: 1: blog title, 2: separator (raquo) */
		'feedtitle'	=> __('%1$s %2$s Feed'),
		/* translators: 1: blog title, 2: separator (raquo) */
		'comstitle'	=> __('%1$s %2$s Comments Feed'),
	);

	$args = wp_parse_args( $args, $defaults );

	echo "\t" . '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( sprintf( $args['feedtitle'], get_bloginfo('name'), $args['separator'] ) ) . '" href="' . esc_url( get_feed_link() ) . "\" />\n";
	echo "\t" . '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( sprintf( $args['comstitle'], get_bloginfo('name'), $args['separator'] ) ) . '" href="' . esc_url( get_feed_link( 'comments_' . get_default_feed() ) ) . "\" />\n";
}

/**
 * Display the links to the extra feeds such as category feeds.
 *
 * copied from /wp-includes/general-template.php
 *
 * @param array $args Optional arguments.
 */
function my_custom_feed_links_extra( $args = array() ) {
	$defaults = array(
		/* translators: Separator between blog name and feed type in feed links */
		'separator'   => _x('&raquo;', 'feed link'),
		/* translators: 1: blog name, 2: separator(raquo), 3: post title */
		'singletitle' => __('%1$s %2$s %3$s Comments Feed'),
		/* translators: 1: blog name, 2: separator(raquo), 3: category name */
		'cattitle'    => __('%1$s %2$s %3$s Category Feed'),
		/* translators: 1: blog name, 2: separator(raquo), 3: tag name */
		'tagtitle'    => __('%1$s %2$s %3$s Tag Feed'),
		/* translators: 1: blog name, 2: separator(raquo), 3: author name  */
		'authortitle' => __('%1$s %2$s Posts by %3$s Feed'),
		/* translators: 1: blog name, 2: separator(raquo), 3: search phrase */
		'searchtitle' => __('%1$s %2$s Search Results for &#8220;%3$s&#8221; Feed'),
		/* translators: 1: blog name, 2: separator(raquo), 3: post type name */
		'posttypetitle' => __('%1$s %2$s %3$s Feed'),
	);

	$args = wp_parse_args( $args, $defaults );

	if ( is_singular() ) {
		$id = 0;
		$post = get_post( $id );

		if ( comments_open() || pings_open() || $post->comment_count > 0 ) {
			$title = sprintf( $args['singletitle'], get_bloginfo('name'), $args['separator'], the_title_attribute( array( 'echo' => false ) ) );
			$href = get_post_comments_feed_link( $post->ID );
		}
	} elseif ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		if ( is_array( $post_type ) )
			$post_type = reset( $post_type );

		$post_type_obj = get_post_type_object( $post_type );
		$title = sprintf( $args['posttypetitle'], get_bloginfo( 'name' ), $args['separator'], $post_type_obj->labels->name );
		$href = get_post_type_archive_feed_link( $post_type_obj->name );
	} elseif ( is_category() ) {
		$term = get_queried_object();

		if ( $term ) {
			$title = sprintf( $args['cattitle'], get_bloginfo('name'), $args['separator'], $term->name );
			$href = get_category_feed_link( $term->term_id );
		}
	} elseif ( is_tag() ) {
		$term = get_queried_object();

		if ( $term ) {
			$title = sprintf( $args['tagtitle'], get_bloginfo('name'), $args['separator'], $term->name );
			$href = get_tag_feed_link( $term->term_id );
		}
	} elseif ( is_author() ) {
		$author_id = intval( get_query_var('author') );

		$title = sprintf( $args['authortitle'], get_bloginfo('name'), $args['separator'], get_the_author_meta( 'display_name', $author_id ) );
		$href = get_author_feed_link( $author_id );
	} elseif ( is_search() ) {
		$title = sprintf( $args['searchtitle'], get_bloginfo('name'), $args['separator'], get_search_query( false ) );
		$href = get_search_feed_link();
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( $args['posttypetitle'], get_bloginfo('name'), $args['separator'], post_type_archive_title( '', false ) );
		$post_type_obj = get_queried_object();
		if ( $post_type_obj )
			$href = get_post_type_archive_feed_link( $post_type_obj->name );
	}

	if ( isset($title) && isset($href) )
		echo "\t" . '<link rel="alternate" type="' . feed_content_type() . '" title="' . esc_attr( $title ) . '" href="' . esc_url( $href ) . '" />' . "\n";
}




/*
 * http://mattvarone.com/wordpress/cleaner-output-for-wp_nav_menu/
 */

class MV_Cleaner_Walker_Nav_Menu extends Walker {
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
	function start_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}
	function end_lvl(&$output, $depth) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	function start_el(&$output, $item, $depth, $args) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
// <modified>
// keep not only the class 'current-menu-item', but also 'current-menu-parent'
// hacked together based on http://wordpress.stackexchange.com/questions/15850/remove-classes-from-body-class
//		$classes = in_array( 'current-menu-parent', $classes ) ? array( 'current-menu-parent' ) : array();
		$tengwar_whitelist = array( 'tengwar' );
		$tengwar_class = array_intersect( $classes, $tengwar_whitelist );
		$whitelist = array( 'current-menu-parent', 'current-menu-item' );
		$classes = array_intersect( $classes, $whitelist );
// </modified>
		$tengwar_current_class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $tengwar_class ), $item, $args ) );
		$tengwar_class_name = strlen( trim( $tengwar_current_class_names ) ) > 0 ? ' class="' . esc_attr( $tengwar_current_class_names ) . '"' : '';
		$current_class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = strlen( trim( $current_class_names ) ) > 0 ? ' class="' . esc_attr( $current_class_names ) . '"' : '';
		$id = apply_filters( 'nav_menu_item_id', '', $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
// <modified>
// indent the code
//		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
// </modified>
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
// <modified>
		if ( esc_attr( $current_class_names ) == 'current-menu-item' ) {
			$item_output = $args->before;
			$item_output .= '<span'. $tengwar_class_name . $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</span>';
		} else {
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			$item_output = $args->before;
			$item_output .= '<a'. $attributes;
			if ( esc_attr( $tengwar_class_name ) ) {
				$item_output .= $tengwar_class_name;
			}
			$item_output .= '>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
//			if ( esc_attr( $tengwar_class_name ) ) {
//				$item_output .= '</span>';
//			}
			$item_output .= '</a>';
		}
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	function end_el(&$output, $item, $depth) {
		$output .= "</li>";
	}
}


/*
 * custom mqtranslate integration
 * code copied from /wp-content/plugins/mqtranslate/mqtranslate_widget.php
 */

// Language Select Code for non-Widget users
function custom_qtranxf_generateLanguageSelectCode($style='', $id='') {
	global $q_config;
	if($style=='') $style='text';
	if(is_bool($style)&&$style) $style='image';
	if(is_404()) $url = get_option('home'); else $url = '';
	if($id=='') $id = 'qtranslate';
	$id .= '-chooser';
	$flag_location=qtranxf_flag_location();
	switch($style) {
		case 'image':
		case 'text':
		case 'dropdown':
			echo "\t\t".'<ul class="language-chooser">';
			foreach(qtranxf_getSortedLanguages() as $language) {
				if($language == $q_config['language']) {
					$classes[] = 'active';
					echo "\n\t\t\t".'<li class="'. implode(' ', $classes) .'"';
				} else {
					echo "\n\t\t\t".'<li><a href="'.qtranxf_convertURL($url, $language, false, true).'"';
					echo ' hreflang="'.$language.'"';
				}
				// set hreflang
				if($style=='image')
					echo ' class="qtranxf_flag qtranxf_flag_'.$language.'"';
				echo '><span';
				if($style=='image')
					echo ' style="display:none"';
				echo '>'.$q_config['language_name'][$language].'</span>';
				if($language != $q_config['language'])
					echo '</a>';
				echo '</li>';
			}
			echo "\n\t\t</ul>";
			if($style=='dropdown') {
				echo '<script type="text/javascript">'.PHP_EOL.'// <![CDATA['.PHP_EOL;
				echo "var lc = document.getElementById('".$id."');".PHP_EOL;
				echo "var s = document.createElement('select');".PHP_EOL;
				echo "s.id = 'qtranxs_select_".$id."';".PHP_EOL;
				echo "lc.parentNode.insertBefore(s,lc);".PHP_EOL;
				// create dropdown fields for each language
				foreach(qtranxf_getSortedLanguages() as $language) {
					echo qtranxf_insertDropDownElement($language, qtranxf_convertURL($url, $language, false, true), $id);
				}
				// hide html language chooser text
				echo "s.onchange = function() { document.location.href = this.value;}".PHP_EOL;
				echo "lc.style.display='none';".PHP_EOL;
				echo '// ]]>'.PHP_EOL.'</script>'.PHP_EOL;
			}
			break;
		case 'both':
			echo PHP_EOL.'<ul class="qtranxs_language_chooser" id="'.$id.'">'.PHP_EOL;
			foreach(qtranxf_getSortedLanguages() as $language) {
				echo '<li';
				if($language == $q_config['language'])
					echo ' class="active"';
				echo '><a href="'.qtranxf_convertURL($url, $language, false, true).'"';
				echo ' class="qtranxs_flag_'.$language.' qtranxs_flag_and_text" title="'.$q_config['language_name'][$language].'">';
				//echo '<img src="'.$flag_location.$q_config['flag'][$language].'"></img>';
				echo '<span>'.$q_config['language_name'][$language].'</span></a></li>'.PHP_EOL;
			}
			echo '</ul><div class="qtranxs_widget_end"></div>'.PHP_EOL;
			break;
	}
}

function custom_qtranxf_header(){
	global $q_config;
	foreach($q_config['enabled_languages'] as $language) {
		if($language != qtranxf_getLanguage())
			echo "\t".'<link rel="alternate" hreflang="'.$language.'" href="'.qtranxf_convertURL('',$language).'" />'."\n";
	}	
}




/** COMMENTS WALKER */
class zipGun_walker_comment extends Walker_Comment {
    
    // init classwide variables
    var $tree_type = 'comment';
    var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );

    /** CONSTRUCTOR
     * You'll have to use this if you plan to get to the top of the comments list, as
     * start_lvl() only goes as high as 1 deep nested comments */
    function __construct() { ?>
        
        <h3 id="comments-title">Comments</h3>
        <ul id="comment-list">
        
    <?php }
    
    /** START_LVL 
     * Starts the list before the CHILD elements are added. */
    function start_lvl( &$output, $depth = 0, $args = array() ) {       
        $GLOBALS['comment_depth'] = $depth + 1; ?>

                <ul class="children">
    <?php }

    /** END_LVL 
     * Ends the children list of after the elements are added. */
    function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 1; ?>

        </ul><!-- /.children -->
        
    <?php }
    
    /** START_EL */
    function start_el( &$output, $comment, $depth, $args, $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment; 
        $parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' ); ?>
        
        <li <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
            <div id="comment-body-<?php comment_ID() ?>" class="comment-body">
            
                <div class="comment-author vcard author">
                    <?php echo ( $args['avatar_size'] != 0 ? get_avatar( $comment, $args['avatar_size'] ) :'' ); ?>
                    <cite class="fn n author-name"><?php echo get_comment_author_link(); ?></cite>
                </div><!-- /.comment-author -->

                <div id="comment-content-<?php comment_ID(); ?>" class="comment-content">
                    <?php if( !$comment->comment_approved ) : ?>
                    <em class="comment-awaiting-moderation">Your comment is awaiting moderation.</em>
                    
                    <?php else: comment_text(); ?>
                    <?php endif; ?>
                </div><!-- /.comment-content -->

                <div class="comment-meta comment-meta-data">
                    <a href="<?php echo htmlspecialchars( get_comment_link( get_comment_ID() ) ) ?>"><?php comment_date(); ?> at <?php comment_time(); ?></a> <?php edit_comment_link( '(Edit)' ); ?>
                </div><!-- /.comment-meta -->

                <div class="reply">
                    <?php $reply_args = array(
                        'add_below' => $add_below, 
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'] );
    
                    comment_reply_link( array_merge( $args, $reply_args ) );  ?>
                </div><!-- /.reply -->
            </div><!-- /.comment-body -->

    <?php }

    function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
        
        </li><!-- /#comment-' . get_comment_ID() . ' -->
        
    <?php }
    
    /** DESTRUCTOR
     * I'm just using this since we needed to use the constructor to reach the top 
     * of the comments list, just seems to balance out nicely:) */
    function __destruct() { ?>
    
    </ul><!-- /#comment-list -->

    <?php }
}



/**
 * HTML comment list class.
 *
 * @uses Walker
 * @since 2.7.0
 */
class custom_Walker_Comment extends Walker {
	/**
	 * What the class handles.
	 *
	 * @see Walker::$tree_type
	 *
	 * @since 2.7.0
	 * @var string
	 */
	public $tree_type = 'comment';

	/**
	 * DB fields to use.
	 *
	 * @see Walker::$db_fields
	 *
	 * @since 2.7.0
	 * @var array
	 */
	public $db_fields = array ('parent' => 'comment_parent', 'id' => 'comment_ID');

	/**
	 * Start the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of comment.
	 * @param array $args Uses 'style' argument for type of HTML list.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				break;
			case 'ul':
			default:
				break;
		}
	}

	/**
	 * End the list of items after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 2.7.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of comment.
	 * @param array  $args   Will only append content if style argument value is 'ol' or 'ul'.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				break;
			case 'ol':
				break;
			case 'ul':
			default:
				break;
		}
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * This function is designed to enhance Walker::display_element() to
	 * display children of higher nesting levels than selected inline on
	 * the highest depth level displayed. This prevents them being orphaned
	 * at the end of the comment list.
	 *
	 * Example: max_depth = 2, with 5 levels of nested content.
	 * 1
	 *  1.1
	 *    1.1.1
	 *    1.1.1.1
	 *    1.1.1.1.1
	 *    1.1.2
	 *    1.1.2.1
	 * 2
	 *  2.2
	 *
	 * @see Walker::display_element()
	 * @see wp_list_comments()
	 *
	 * @since 2.7.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing.
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

		if ( !$element )
			return;

		$id_field = $this->db_fields['id'];
		$id = $element->$id_field;

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

		// If we're at the max depth, and the current element still has children, loop over those and display them at this level
		// This is to prevent them being orphaned to the end of the list.
		if ( $max_depth <= $depth + 1 && isset( $children_elements[$id]) ) {
			foreach ( $children_elements[ $id ] as $child )
				$this->display_element( $child, $children_elements, $max_depth, $depth, $args, $output );

			unset( $children_elements[ $id ] );
		}

	}

	/**
	 * Start the element output.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::start_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment Comment data object.
	 * @param int    $depth   Depth of comment in reference to parents.
	 * @param array  $args    An array of arguments.
	 */
	public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;

		if ( !empty( $args['callback'] ) ) {
			ob_start();
			call_user_func( $args['callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}

		if ( ( 'pingback' == $comment->comment_type || 'trackback' == $comment->comment_type ) && $args['short_ping'] ) {
			ob_start();
			$this->ping( $comment, $depth, $args );
			$output .= ob_get_clean();
		} elseif ( 'html5' === $args['format'] ) {
			ob_start();
			$this->html5_comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		} else {
			ob_start();
			$this->comment( $comment, $depth, $args );
			$output .= ob_get_clean();
		}
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @since 2.7.0
	 *
	 * @see Walker::end_el()
	 * @see wp_list_comments()
	 *
	 * @param string $output  Passed by reference. Used to append additional content.
	 * @param object $comment The comment object. Default current comment.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	public function end_el( &$output, $comment, $depth = 0, $args = array() ) {
		if ( !empty( $args['end-callback'] ) ) {
			ob_start();
			call_user_func( $args['end-callback'], $comment, $args, $depth );
			$output .= ob_get_clean();
			return;
		}
		/*if ( 'div' == $args['style'] )
			$output .= "</div><!-- #comment-## -->\n";
		else
			$output .= "\t\t\t\t\t</li><!-- #comment-## -->\n";*/
	}

	/**
	 * Output a pingback comment.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment The comment object.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function ping( $comment, $depth, $args ) {
		$tag = ( 'div' == $args['style'] ) ? 'div' : 'li';
?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-body">
				<?php _e( 'Pingback:' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
<?php
	}

	/**
	 * Output a single comment.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment Comment to display.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function comment( $comment, $depth, $args ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-author vcard">
			<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
		</div>
		<?php if ( '0' == $comment->comment_approved ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
		<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
			?>
		</div>

		<?php comment_text( get_comment_id(), array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
<?php
	}

	/**
	 * Output a comment in the HTML5 format.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment Comment to display.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
?>
					<li>
						<article id="comment-<?php comment_ID(); ?>" itemprop="comment">
							<header>
								<h3><?php
									printf( '%s', get_comment_author_link() );
									?></h3>
								<div><a href="<?php
									echo esc_url( get_comment_link( $comment->comment_ID, $args ) );
									?>"><time datetime="<?php
									//comment_time( 'c' ); // messed up ...
									printf( '%1$sT%2$s', get_comment_date(), get_comment_time() );
									?>"><?php
									printf( _x( '%1$s at %2$s', '1: date, 2: time' ), get_comment_date(), get_comment_time() );
									?></time></a></div>
							</header>
<?php
							edit_comment_link( __( 'Edit' ), '<span class="edit-link">', '</span>' );

							if ( '0' == $comment->comment_approved ) : ?>
							<p class="comment-awaiting-moderation"><?php
								_e( 'Your comment is awaiting moderation.' );
								?></p><?php
							endif; ?>

							<!-- Here follows manual input of comment-<?php comment_ID(); ?> -->

<?php comment_text(); ?>

							<!-- Here ends manual input of comment-<?php comment_ID(); ?> -->
<?php /* if ( $max_depth > 0 ) : ?>
							<div class="reply"><?php
								comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
								?></div><!-- .reply -->
<?php endif; */ ?>
						</article>
					</li>
<?php
	}
}

/**
 * Output a complete commenting form for use within a template.
 *
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0.0
 *
 * @param array       $args {
 *     Optional. Default arguments and form fields to override.
 *
 *     @type array $fields {
 *         Default comment fields, filterable by default via the 'comment_form_default_fields' hook.
 *
 *         @type string $author Comment author field HTML.
 *         @type string $email  Comment author email field HTML.
 *         @type string $url    Comment author URL field HTML.
 *     }
 *     @type string $comment_field        The comment textarea field HTML.
 *     @type string $must_log_in          HTML element for a 'must be logged in to comment' message.
 *     @type string $logged_in_as         HTML element for a 'logged in as <user>' message.
 *     @type string $comment_notes_before HTML element for a message displayed before the comment form.
 *                                        Default 'Your email address will not be published.'.
 *     @type string $comment_notes_after  HTML element for a message displayed after the comment form.
 *                                        Default 'You may use these HTML tags and attributes ...'.
 *     @type string $id_form              The comment form element id attribute. Default 'commentform'.
 *     @type string $id_submit            The comment submit element id attribute. Default 'submit'.
 *     @type string $name_submit          The comment submit element name attribute. Default 'submit'.
 *     @type string $title_reply          The translatable 'reply' button label. Default 'Leave a Reply'.
 *     @type string $title_reply_to       The translatable 'reply-to' button label. Default 'Leave a Reply to %s',
 *                                        where %s is the author of the comment being replied to.
 *     @type string $cancel_reply_link    The translatable 'cancel reply' button label. Default 'Cancel reply'.
 *     @type string $label_submit         The translatable 'submit' button label. Default 'Post a comment'.
 *     @type string $format               The comment form format. Default 'xhtml'. Accepts 'xhtml', 'html5'.
 * }
 * @param int|WP_Post $post_id Post ID or WP_Post object to generate the form for. Default current post.
 */
function custom_comment_form( $args = array(), $post_id = null ) {
	if ( null === $post_id )
		$post_id = get_the_ID();

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$args = wp_parse_args( $args );
	if ( ! isset( $args['format'] ) )
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$html5    = 'html5' === $args['format'];
	$fields   =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => "\t\t\t\t\t\t" . '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => "\t\t\t\t\t\t" . '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
		            '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );

	/**
	 * Filter the default comment form fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $fields The default comment fields.
	 */
	$fields = apply_filters( 'comment_form_default_fields', $fields );
	$defaults = array(
		'fields'               => $fields,
		'comment_field'        => "\t\t\t\t\t\t" . '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		/** This filter is documented in wp-includes/link-template.php */
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		/** This filter is documented in wp-includes/link-template.php */
		'logged_in_as'         => "\t\t\t\t\t\t" . '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => "\t\t\t\t\t\t" . '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
		'comment_notes_after'  => "\n\t\t\t\t\t\t" . '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'name_submit'          => 'submit',
		'title_reply'          => __( 'Leave a Reply' ),
		'title_reply_to'       => __( 'Leave a Reply to %s' ),
		'cancel_reply_link'    => __( 'Cancel reply' ),
		'label_submit'         => __( 'Post Comment' ),
		'format'               => 'xhtml',
	);

	/**
	 * Filter the comment form default arguments.
	 *
	 * Use 'comment_form_default_fields' to filter the comment fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $defaults The default comment form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

		if ( comments_open( $post_id ) ) :

			/**
			 * Fires before the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_before' );
			?>
				<div id="respond">
					<h3><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?></h3>
<?php
				if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : 
					echo $args['must_log_in'];

					/**
					 * Fires after the HTML-formatted 'must log in after' message in the comment form.
					 *
					 * @since 3.0.0
					 */
					do_action( 'comment_form_must_log_in_after' );

				else : ?>

					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="comment-form"<?php echo $html5 ? ' novalidate' : ''; ?>>
<?php
						/**
						 * Fires at the top of the comment form, inside the <form> tag.
						 *
						 * @since 3.0.0
						 */
						do_action( 'comment_form_top' );

						if ( is_user_logged_in() ) :

							/**
							 * Filter the 'logged in' message for the comment form for display.
							 *
							 * @since 3.0.0
							 *
							 * @param string $args_logged_in The logged-in-as HTML-formatted message.
							 * @param array  $commenter      An array containing the comment author's
							 *                               username, email, and URL.
							 * @param string $user_identity  If the commenter is a registered user,
							 *                               the display name, blank otherwise.
							 */
							echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );

							/**
							 * Fires after the is_user_logged_in() check in the comment form.
							 *
							 * @since 3.0.0
							 *
							 * @param array  $commenter     An array containing the comment author's
							 *                              username, email, and URL.
							 * @param string $user_identity If the commenter is a registered user,
							 *                              the display name, blank otherwise.
							 */
							do_action( 'comment_form_logged_in_after', $commenter, $user_identity );

						else :
							echo $args['comment_notes_before'];

							/**
							 * Fires before the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_before_fields' );
							foreach ( (array) $args['fields'] as $name => $field ) {
								/**
								 * Filter a comment form field for display.
								 *
								 * The dynamic portion of the filter hook, $name, refers to the name
								 * of the comment form field. Such as 'author', 'email', or 'url'.
								 *
								 * @since 3.0.0
								 *
								 * @param string $field The HTML-formatted output of the comment form field.
								 */
								echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
							}
							/**
							 * Fires after the comment fields in the comment form.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_after_fields' );

						endif;

						/**
						 * Filter the content of the comment textarea field for display.
						 *
						 * @since 3.0.0
						 *
						 * @param string $args_comment_field The content of the comment textarea field.
						 */
						echo apply_filters( 'comment_form_field_comment', $args['comment_field'] );

						echo $args['comment_notes_after']; ?>

						<p class="form-submit"><input name="<?php
							echo esc_attr( $args['name_submit'] );
							?>" type="submit" id="<?php
							echo esc_attr( $args['id_submit'] );
							?>" value="<?php
							echo esc_attr( $args['label_submit'] );
							?>" /><?php
							custom_comment_id_fields( $post_id );
							?></p>
						<?php
						/**
						 * Fires at the bottom of the comment form, inside the closing </form> tag.
						 *
						 * @since 1.5.0
						 *
						 * @param int $post_id The post ID.
						 */
						do_action( 'comment_form', $post_id );
						?>

					</form>
<?php endif; ?>
				</div>
<?php
			/**
			 * Fires after the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_after' );
		else :
			/**
			 * Fires after the comment form if comments are closed.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_comments_closed' );
		endif;
}


/**
 * Retrieve hidden input HTML for replying to comments.
 *
 * @since 3.0.0
 *
 * @param int $id Optional. Post ID. Default current post ID.
 * @return string Hidden input HTML for replying to comments
 */
function custom_get_comment_id_fields( $id = 0 ) {
	if ( empty( $id ) )
		$id = get_the_ID();

	$replytoid = isset($_GET['replytocom']) ? (int) $_GET['replytocom'] : 0;
	$result  = "<input type='hidden' name='comment_post_ID' value='$id' id='comment_post_ID' />";
	$result .= "<input type='hidden' name='comment_parent' id='comment_parent' value='$replytoid' />";

	/**
	 * Filter the returned comment id fields.
	 *
	 * @since 3.0.0
	 *
	 * @param string $result    The HTML-formatted hidden id field comment elements.
	 * @param int    $id        The post ID.
	 * @param int    $replytoid The id of the comment being replied to.
	 */
	return apply_filters( 'comment_id_fields', $result, $id, $replytoid );
}

/**
 * Output hidden input HTML for replying to comments.
 *
 * @since 2.7.0
 *
 * @param int $id Optional. Post ID. Default current post ID.
 */
function custom_comment_id_fields( $id = 0 ) {
	echo custom_get_comment_id_fields( $id );
}



/**
 * Retrieve paginated link for archive post pages.
 *
 * Technically, the function can be used to create paginated link list for any
 * area. The 'base' argument is used to reference the url, which will be used to
 * create the paginated links. The 'format' argument is then used for replacing
 * the page number. It is however, most likely and by default, to be used on the
 * archive post pages.
 *
 * The 'type' argument controls format of the returned value. The default is
 * 'plain', which is just a string with the links separated by a newline
 * character. The other possible values are either 'array' or 'list'. The
 * 'array' value will return an array of the paginated link list to offer full
 * control of display. The 'list' value will place all of the paginated links in
 * an unordered HTML list.
 *
 * The 'total' argument is the total amount of pages and is an integer. The
 * 'current' argument is the current page number and is also an integer.
 *
 * An example of the 'base' argument is "http://example.com/all_posts.php%_%"
 * and the '%_%' is required. The '%_%' will be replaced by the contents of in
 * the 'format' argument. An example for the 'format' argument is "?page=%#%"
 * and the '%#%' is also required. The '%#%' will be replaced with the page
 * number.
 *
 * You can include the previous and next links in the list by setting the
 * 'prev_next' argument to true, which it is by default. You can set the
 * previous text, by using the 'prev_text' argument. You can set the next text
 * by setting the 'next_text' argument.
 *
 * If the 'show_all' argument is set to true, then it will show all of the pages
 * instead of a short list of the pages near the current page. By default, the
 * 'show_all' is set to false and controlled by the 'end_size' and 'mid_size'
 * arguments. The 'end_size' argument is how many numbers on either the start
 * and the end list edges, by default is 1. The 'mid_size' argument is how many
 * numbers to either side of current page, but not including current page.
 *
 * It is possible to add query vars to the link by using the 'add_args' argument
 * and see {@link add_query_arg()} for more information.
 *
 * The 'before_page_number' and 'after_page_number' arguments allow users to
 * augment the links themselves. Typically this might be to add context to the
 * numbered links so that screen reader users understand what the links are for.
 * The text strings are added before and after the page number - within the
 * anchor tag.
 *
 * @since 2.1.0
 *
 * @param string|array $args Optional. Override defaults.
 * @return array|string String of page links or array of page links.
 */
function custom_paginate_links( $args = '' ) {
	global $wp_query, $wp_rewrite;

	$total        = ( isset( $wp_query->max_num_pages ) ) ? $wp_query->max_num_pages : 1;
	$current      = ( get_query_var( 'paged' ) ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$defaults = array(
		'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
		'format' => $format, // ?page=%#% : %#% is replaced by the page number
		'total' => $total,
		'current' => $current,
		'show_all' => false,
		'prev_next' => true,
		'prev_text' => __('&laquo; Previous'),
		'next_text' => __('Next &raquo;'),
		'end_size' => 1,
		'mid_size' => 2,
		'type' => 'list',
		'add_args' => false, // array of query args to add
		'add_fragment' => '',
		'before_page_number' => '',
		'after_page_number' => ''
	);

	$args = wp_parse_args( $args, $defaults );

	// Who knows what else people pass in $args
	$total = (int) $args['total'];
	if ( $total < 2 ) {
		return;
	}
	$current  = (int) $args['current'];
	$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
	if ( $end_size < 1 ) {
		$end_size = 1;
	}
	$mid_size = (int) $args['mid_size'];
	if ( $mid_size < 0 ) {
		$mid_size = 2;
	}
	$add_args = is_array( $args['add_args'] ) ? $args['add_args'] : false;
	$r = '';
	$page_links = array();
	$dots = false;

	if ( $args['prev_next'] && $current && 1 < $current ) :
		$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current - 1, $link );
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $args['add_fragment'];

		/**
		 * Filter the paginated links for the given archive pages.
		 *
		 * @since 3.0.0
		 *
		 * @param string $link The paginated link URL.
		 */
		$page_links[] = '<a class="prev" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
	endif;
	for ( $n = 1; $n <= $total; $n++ ) :
		if ( $n == $current ) :
			$page_links[] = '<span class="current">' . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span>";
			$dots = true;
		else :
			if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
				$link = str_replace( '%#%', $n, $link );
				if ( $add_args )
					$link = add_query_arg( $add_args, $link );
				$link .= $args['add_fragment'];

				/** This filter is documented in wp-includes/general-template.php */
				$page_links[] = '<a href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
				$dots = true;
			elseif ( $dots && ! $args['show_all'] ) :
				$page_links[] = '<span class="dots">' . __( '&hellip;' ) . '</span>';
				$dots = false;
			endif;
		endif;
	endfor;
	if ( $args['prev_next'] && $current && ( $current < $total || -1 == $total ) ) :
		$link = str_replace( '%_%', $args['format'], $args['base'] );
		$link = str_replace( '%#%', $current + 1, $link );
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $args['add_fragment'];

		/** This filter is documented in wp-includes/general-template.php */
		$page_links[] = '<a class="next" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
	endif;
	switch ( $args['type'] ) {
		case 'array' :
			return $page_links;

		case 'list' :
			$r .= "\n\t\t\t\t\t\t\t<ul><li>";
			$r .= join("</li><li>", $page_links);
			$r .= "</li></ul>\n\t\t\t\t\t\t";
			break;

		default :
			$r = join(" ", $page_links);
			break;
	}
	return $r;
}

/**
 * This is a copy of custom_paginate_links()
 * The difference is that $current needs to be decreased by 1 and
 * that the counters are increased by 1:
 *
 * 1. in the code for generating the prev link,
 *
 * 2. in the code for generating the numbered links, and
 *
 * 3. in the code for generating the next link.
 */
function custom_paginate_links_only_from_second_page( $args = '' ) {
	global $wp_query, $wp_rewrite;

	$total        = ( isset( $wp_query->max_num_pages ) ) ? $wp_query->max_num_pages : 1;
	$current      = ( get_query_var( 'paged' ) ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

	$defaults = array(
		'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
		'format' => $format, // ?page=%#% : %#% is replaced by the page number
		'total' => $total,
		'current' => $current,
		'show_all' => false,
		'prev_next' => true,
		'prev_text' => __('&laquo; Previous'),
		'next_text' => __('Next &raquo;'),
		'end_size' => 1,
		'mid_size' => 2,
		'type' => 'plain',
		'add_args' => false, // array of query args to add
		'add_fragment' => '',
		'before_page_number' => '',
		'after_page_number' => ''
	);

	$args = wp_parse_args( $args, $defaults );

	// Who knows what else people pass in $args
	$total = (int) $args['total'];
	if ( $total < 2 ) {
		return;
	}
	/*
	 * This is the $current that needs to be decreased.
	 */
	$current  = (int) $args['current'] - 1;
	$end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
	if ( $end_size < 1 ) {
		$end_size = 1;
	}
	$mid_size = (int) $args['mid_size'];
	if ( $mid_size < 0 ) {
		$mid_size = 2;
	}
	$add_args = is_array( $args['add_args'] ) ? $args['add_args'] : false;
	$r = '';
	$page_links = array();
	$dots = false;

	/*
	 * This is the code for generating the prev link.
	 * Counters are increased by 1:
	 * 
	 * - $current is replaced by ( $current + 1 )
	 *
	 * - In the if condition, 1 is replaced by ( 1 + 1 )
	 *
	 * The brackets are not really required; they add legibility.
	 */
	if ( $args['prev_next'] && $current && ( 1 + 1 ) < ( $current + 1 ) ) :
		$link = str_replace( '%_%', 2 == ( $current + 1 ) ? '' : $args['format'], $args['base'] );
		$link = str_replace( '%#%', ( $current + 1 ) - 1, $link );
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $args['add_fragment'];

		/**
		 * Filter the paginated links for the given archive pages.
		 *
		 * @since 3.0.0
		 *
		 * @param string $link The paginated link URL.
		 */
		$page_links[] = '<a class="prev page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
	endif;
	/*
	 * This is the code for generating the numbered links.
	 * Counters are increased by 1:
	 * 
	 * - $n is replaced by ( $n + 1 )
	 *
	 * The brackets are not really required; they add legibility.
	 */
	for ( $n = 1; $n <= $total; $n++ ) :
		if ( $n == $current ) :
			$page_links[] = "<span class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span>";
			$dots = true;
		else :
			if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
				$link = str_replace( '%_%', 1 == ( $n + 1 ) ? '' : $args['format'], $args['base'] );
				$link = str_replace( '%#%', ( $n + 1 ), $link );
				if ( $add_args )
					$link = add_query_arg( $add_args, $link );
				$link .= $args['add_fragment'];

				/** This filter is documented in wp-includes/general-template.php */
				$page_links[] = "<a class='page-numbers' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
				$dots = true;
			elseif ( $dots && ! $args['show_all'] ) :
				$page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
				$dots = false;
			endif;
		endif;
	endfor;
	/*
	 * This is the code for generating the next link.
	 * Counters are increased by 1:
	 * 
	 * - $current is replaced by ( $current + 1 )
	 *
	 * - In the if condition, $total is replaced by ( $total + 1 )
	 *
	 * The brackets are not really required; they add legibility.
	 */
	if ( $args['prev_next'] && ( $current + 1 ) && ( ( $current + 1 ) < ( $total + 1 ) || -1 == $total ) ) :
		$link = str_replace( '%_%', $args['format'], $args['base'] );
		$link = str_replace( '%#%', ( $current + 1 ) + 1, $link );
		if ( $add_args )
			$link = add_query_arg( $add_args, $link );
		$link .= $args['add_fragment'];

		/** This filter is documented in wp-includes/general-template.php */
		$page_links[] = '<a class="next page-numbers" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
	endif;
	switch ( $args['type'] ) {
		case 'array' :
			return $page_links;

		case 'list' :
			$r .= "<ul class='page-numbers'>\n\t<li>";
			$r .= join("</li>\n\t<li>", $page_links);
			$r .= "</li>\n</ul>\n";
			break;

		default :
			$r = join(" ", $page_links);
			break;
	}
	return $r;
}

/*
 * Make the output of wp_title more similar to the respective h1:
 * 
 * 1. For date-based archives, produce e.g.: 2014-08-03 (Page 2) $sep tengwar.info
 *    instead of: 03 $sep August $sep 2014 $sep Page 2 $sep tengwar.info
 * 
 * 2. For paged category archvies, produce e.g.: Tengwar Modes (Page 2) $sep tengwar.info
 *    instead of: Tengwar Modes $sep Page 2 $sep tengwar.info
 *
 * In other cases, wp_title is not changed.
 */
function twentyfourteentengwar_wp_title( $title, $sep ) {
	global $page, $paged;

	$pagenumber = '' ;
	$blogname = get_bloginfo( 'name' ) ;

	if ( $paged >= 2 || $page >= 2 ) {
		$pagenumber = '(' . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) ) . ')' ;
	}

	if ( is_day() ) {
		$daydate = get_the_date();
		$title = "$daydate $pagenumber $sep $blogname" ;
	} elseif ( is_month() ) {
		$monthdate = mysql2date( 'Y-m', get_the_date(), false );
		$title = "$monthdate $pagenumber $sep $blogname" ;
	} elseif ( is_year() ) {
		$yeardate = mysql2date( 'Y', get_the_date(), false );
		$title = "$yeardate $pagenumber $sep $blogname" ;
/*	if ( is_day() || is_month() || is_year() ) {
		$title = twentyfourteentengwar_archive_title() . " $sep $blogname" ;*/
	} elseif ( is_category() && ( $paged >= 2 || $page >= 2 ) ) {
		$categorytitle = single_cat_title( '', false ) ;
		$title = "$categorytitle $pagenumber $sep $blogname" ;
	}

	return $title;
}
add_filter( 'wp_title', 'twentyfourteentengwar_wp_title', 10, 2 );

function twentyfourteentengwar_archive_title() {
	global $paged;
	if ( is_day() ) :
		printf( __( 'Daily Archives: %s', 'twentyfourteen' ), get_the_date() );
	elseif ( is_month() ) :
		$monthdate = mysql2date( 'Y-m', get_the_date(), false );
		printf( __( 'Monthly Archives: %s', 'twentyfourteen' ), $monthdate );
	elseif ( is_year() ) :
		$yeardate = mysql2date( 'Y', get_the_date(), false );
		printf( __( 'Yearly Archives: %s', 'twentyfourteen' ), $yeardate );
	else :
		_e( 'Archives', 'twentyfourteen' );
	endif;
	if ( $paged >= 2 ) :
		echo ' (';
//		printf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
		printf( __( 'Page %s', 'twentyfourteen' ), $paged );
		echo ')';
	endif;
}

/*
 * Remove category with slug "current" from category archive query
 * http://codex.wordpress.org/Plugin_API/Action_Reference/pre_get_posts
 * [This replaces a complicated $the_query = new WP_Query( $args ); on category.php]
 *
 * Also, show a different number of posts on the first page of a category archive.
 * http://wordpress.stackexchange.com/questions/124303/different-posts-per-page-setting-for-first-and-rest-of-the-paginated-pages/
 * http://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
 *
 * In order for the latter to work, the following found_post filter
 * must also be present, and of course, it must use the same $first_page_ppp number.
 *
 * The latter is controlled by $first_page_ppp,
 * which must be identical in both filters.
 *
 * If $first_page_ppp is set to 0, then
 * pagination must be done with twentyfourteen_paging_nav_only_from_second_page();
 * instead of twentyfourteen_paging_nav();
 */
function custom_category_query_1_of_2( $query ) {
    if ( $query->is_category() && $query->is_main_query() ) {
//    if ( ( $query->is_category( 'modes' ) || $query->is_category( 'remarks' ) ) && $query->is_main_query() ) {
		$remove_cat = get_category_by_slug('current');
		$remove_cat_id = $remove_cat->cat_ID;
        $query->set( 'cat', "-$remove_cat_id" );

		/*
		 * The remainder is only for showing a different number of posts on the first page.
		 */
	    $ppp = get_option( 'posts_per_page' );
	    $first_page_ppp = 3;
	    $paged = $query->query_vars[ 'paged' ];

/*
 * Solid working code:
 *
		if( !is_paged() ) {
			$query->set( 'posts_per_page', $first_page_ppp );
		} else {
			$paged_offset = $first_page_ppp + ( ($paged - 2) * $ppp );
			$query->set( 'offset', $paged_offset );
		}
/*
 * Solid working ends here.
 */

/*
 * Buggy code:
 * This allows $first_page_ppp to be set to 0
 * However, when doing so, the first page of the 'blog' category disappears ...
 */
		if( $first_page_ppp != 0 ) {
			if( !is_paged() ) {
				$query->set( 'posts_per_page', $first_page_ppp );
			} else {
				$paged_offset = $first_page_ppp + ( ($paged - 2) * $ppp );
				$query->set( 'offset', $paged_offset );
			}
		} else {
			$paged = $paged - 1;
			$paged_offset = .1 + ( ($paged - 1) * $ppp );
			$query->set( 'offset', $paged_offset );
		}
/*
 * Buggy code ends here.
 */
	}
}
add_action( 'pre_get_posts', 'custom_category_query_1_of_2' );

/*
 * In order for the previous pre_get_posts filter to work,
 * the following found_post filter must also be present.
 *
 * It must use the same $first_page_ppp number and, of course,
 * apply under the same IF condition.
 */
function custom_category_query_2_of_2( $found_posts, $query ) {
	$ppp = get_option( 'posts_per_page' );
	$first_page_ppp = 3;

	if ( $query->is_category() && $query->is_main_query() && $first_page_ppp != 0 ) {
//    if ( ( $query->is_category( 'modes' ) || $query->is_category( 'remarks' ) ) && $query->is_main_query() ) {
		if( !is_paged() ) {
			return( $first_page_ppp + ( $found_posts - $first_page_ppp ) * $first_page_ppp / $ppp );
		} else {
			return( $found_posts - ($first_page_ppp - $ppp) );
		}
	}
	return $found_posts; // fallback for non-category queries (such as per date)
}
add_filter( 'found_posts', 'custom_category_query_2_of_2', 10, 2 );
