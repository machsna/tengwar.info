<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

$customtitle = get_post_meta( get_the_ID(), "custom_title", true );
$currentlanguage = qtranxf_getLanguage();

	if ( is_single() ) : ?>
			<header>
				<h1><?php
					if ( $customtitle ) :
						$translatedcustomtitle = qtranxf_use( $currentlanguage, $customtitle, true );
						echo '<span title="' . the_title( '', '', false ) . '" class="tengwar">' . $translatedcustomtitle . '</span>';
					else :
						the_title();
					endif; ?></h1>
				<div><?php
						if ( 'post' == get_post_type() )
							/*
							 * Note: With qtranslate-x, (get_)?the_modified_date will not work
							 * Workaround: $post->post_modified, and then pass it on to the function
							 * In each instance where twentyfourteentengwar_posted_on is called ...
							 */
							$fake_modified_date = get_post_meta( get_the_ID(), 'fake_modified_date', true );
							$thispostmodified = $post->post_modified;
							$sanitizedthispostmodified = mysql2date( 'Y-m-d', $thispostmodified, false );
							//echo ' {updated: ' . $sanitizedthispostmodified . '}' ;
							if ( $fake_modified_date ){
								twentyfourteentengwar_posted_on( $fake_modified_date, get_the_date() );
							} else {
								twentyfourteentengwar_posted_on( $sanitizedthispostmodified, get_the_date() );
							}
					?></div>
			</header>

			<!-- Here follows manual input of post-<?php the_ID(); ?> -->

<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyfourteen' ) ); ?>


			<!-- Here ends manual input of post-<?php the_ID(); ?> -->
<?php
	else : // ( is_single() ) ?>
					<li>
						<article id="post-<?php the_ID(); ?>">
							<header>
								<h3><a href="<?php
									echo esc_url( get_permalink() )
									?>" rel="bookmark"><?php
									if ( $customtitle ) :
										$translatedcustomtitle = qtranxf_use( $currentlanguage, $customtitle, true );
										echo '<span title="' . the_title( '', '', false ) . '" class="tengwar">' . $translatedcustomtitle . '</span>';
									else :
										the_title();
									endif;
									?></a></h3>
								<div><?php
									if ( 'post' == get_post_type() )
										/*
										 * Note: With qtranslate-x, (get_)?the_modified_date will not work
										 * Workaround: $post->post_modified, and then pass it on to the function
										 * In each instance where twentyfourteentengwar_posted_on is called ...
										 */
										$fake_modified_date = get_post_meta( get_the_ID(), 'fake_modified_date', true );
										$thispostmodified = $post->post_modified;
										$sanitizedthispostmodified = mysql2date( 'Y-m-d', $thispostmodified, false );
										//echo ' {updated: ' . $sanitizedthispostmodified . '}' ;
										if ( $fake_modified_date ){
											twentyfourteentengwar_posted_on( $fake_modified_date, get_the_date() );
										} else {
											twentyfourteentengwar_posted_on( $sanitizedthispostmodified, get_the_date() );
										}
									?></div>
							</header>

							<!-- Here begins excerpt of post-<?php the_ID(); ?> -->

<?php the_excerpt(); ?>


							<!-- Here ends excerpt of post-<?php the_ID(); ?> -->
						</article>
					</li>
<?php
	endif; // ( is_single() )

the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
