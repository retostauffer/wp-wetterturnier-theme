<?php
#require get_stylesheet_directory() . '/inc/featured-content.php';

# -------------------------------------------------------------------
# -------------------------------------------------------------------
function wpse_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'wpse_header_args', array(
        'width'                  => 1600,
        'height'                 => 140,
    ) ) );

   add_image_size( 'custom-news-thumbnail', 180, 130, true ); // 
   # Custom wordpress sidebar. Disabled right now as you can see.
   //register_sidebar( array(
   //   'name'          => __( 'WT Content Sidebar', 'twentyfourteen' ),
   //   'id'            => 'sidebar-10',
   //   'description'   => __( 'Special Wetterturnier sidebar on the right.', 'twentyfourteen' ),
   //   'before_widget' => '<aside id="%1$s" class="widget %2$s">',
   //   'after_widget'  => '</aside>',
   //   'before_title'  => '<h1 class="widget-title">',
   //   'after_title'   => '</h1>',
   //) );
}
add_action( 'after_setup_theme', 'wpse_custom_header_setup' );

// bbpress extension css
function register_custom_css_theme_file() {
   $url = sprintf("%s/custom-bbpress.css",dirname( get_stylesheet_uri() ));
   $check = wp_enqueue_style(  'child_theme_bbpress', $url, array('bbp-default'), '1.0', 'all' ); 
   ////global $wp_styles;
   ////print_r($wp_styles);
}
add_action('wp_enqueue_scripts','register_custom_css_theme_file');


// ------------------------------------------------------------------
/// Redirect user after successful login.
///
/// @param string $redirect_to URL to redirect to.
/// @param string $request URL the user is coming from.
/// @param object $user Logged user's data.
/// @return string
// ------------------------------------------------------------------
function my_login_redirect( $redirect_to, $request, $user ) {
   //is there a user to check?
   if ( isset( $user->roles ) && is_array( $user->roles ) ) {
      //check for admins
      if ( in_array( 'administrator', $user->roles ) ) {
         // redirect them to the default place
         return $redirect_to;
      } else {
         return home_url();
      }
   } else {
      return $redirect_to;
   }
}
add_filter( 'login_redirect', function( $url, $query, $user ) { return home_url(); }, 10, 3 );

// ------------------------------------------------------------------
/// @details Function called when shortcode [display_all_news] is used
///   in one of the pages. Includes allnews.php.
// ------------------------------------------------------------------
function display_news_archive() {
   global $content;
   ob_start();
   include ( sprintf("%s/news-archive.php",get_stylesheet_directory()) );
   $output = ob_get_clean();
   return $output;
}
add_shortcode( 'display-news-archive', 'display_news_archive' );


////add_action( 'after_setup_theme', 'childtheme_custom_image_size' );
////###add_action( 'admin_init', 'childtheme_custom_image_size' );
////function childtheme_custom_image_size() {
////   //add_theme_support( 'post-thumbnails' );
////}

# -------------------------------------------------------------------
# Custom function for the bbpress child theme
# -------------------------------------------------------------------
////////////////scheissdreck der include_once("bbpress/functions.php");

// -------------------------------------------------------------------
/// @details Custom wordpress child theme thumbnail function.
// -------------------------------------------------------------------
function custom_post_thumbnail() {
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
   <a class="post-thumbnail custom-news-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
   <?php
      if ( ( ! is_active_sidebar( 'sidebar-2' ) || is_page_template( 'page-templates/full-width.php' ) ) ) {
         the_post_thumbnail( 'custom-news-thumbnail' );
      } else {
         the_post_thumbnail( 'custom-news-thumbnail', array( 'alt' => get_the_title()) );
      }
   ?>
   </a>
   <?php endif; // End is_singular()
}

// ------------------------------------------------------------------
/// @details Custom functions to be able to print the latest bbpress
/// posts (if plugin is active) on the front page.
/// Based on the idea of https://www.inmotionhosting.com/support/website/wordpress/get-post-wordpress-function.
// ------------------------------------------------------------------
function shapeSpace_topic_cpt() {
	$labels = array(
		'name'               => __('Topics', 'shapeSpace'),
		'singular_name'      => __('Topic', 'shapeSpace'),
		'menu_name'          => __('Topics', 'shapeSpace'),
		'name_admin_bar'     => __('Topics', 'shapeSpace'),
		'add_new'            => __('Add New', 'shapeSpace'),
		'add_new_item'       => __('Add New Topic', 'shapeSpace'),
		'new_item'           => __('New Topic', 'shapeSpace'),
		'edit_item'          => __('Edit Topic', 'shapeSpace'),
		'view_item'          => __('View Topic', 'shapeSpace'),
		'all_items'          => __('All Topics', 'shapeSpace'),
		'search_items'       => __('Search Topics', 'shapeSpace'),
		'parent_item_colon'  => __('Parent Topics:', 'shapeSpace'),
		'not_found'          => __('No topics found.', 'shapeSpace'),
		'not_found_in_trash' => __('No topics found in Trash.', 'shapeSpace'),
	);
	$args = array(
		'labels'              => $labels,
		'taxonomies'          => array(),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		//'rewrite'             => array('slug' => 'topic'),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => null,
		'supports'            => array('title', 'editor', 'author', 'excerpt', 'custom-fields'),
		'exclude_from_search' => false, 
	);
	register_post_type('topic', $args);
}
add_action('init', 'shapeSpace_topic_cpt');



// ------------------------------------------------------------------
/// @details Custom functions to be able to print the latest bbpress
/// posts (if plugin is active) on the front page.
/// Based on the idea of https://www.inmotionhosting.com/support/website/wordpress/get-post-wordpress-function.
// ------------------------------------------------------------------
function shapeSpace_reply_cpt() {
	$labels = array(
		'name'               => __('Replies', 'shapeSpace'),
		'singular_name'      => __('Reply', 'shapeSpace'),
		'menu_name'          => __('Replies', 'shapeSpace'),
		'name_admin_bar'     => __('Replies', 'shapeSpace'),
		'add_new'            => __('Add New', 'shapeSpace'),
		'add_new_item'       => __('Add New Reply', 'shapeSpace'),
		'new_item'           => __('New Reply', 'shapeSpace'),
		'edit_item'          => __('Edit Reply', 'shapeSpace'),
		'view_item'          => __('View Reply', 'shapeSpace'),
		'all_items'          => __('All Replies', 'shapeSpace'),
		'search_items'       => __('Search Replies', 'shapeSpace'),
		'parent_item_colon'  => __('Parent Replies:', 'shapeSpace'),
		'not_found'          => __('No replies found.', 'shapeSpace'),
		'not_found_in_trash' => __('No replies found in Trash.', 'shapeSpace'),
	);
	$args = array(
		'labels'              => $labels,
		'taxonomies'          => array(),
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array('slug' => 'reply'),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => null,
		'supports'            => array('title', 'editor', 'author', 'excerpt', 'custom-fields'),
		'exclude_from_search' => true, 
	);
	register_post_type('reply', $args);
}
add_action('init', 'shapeSpace_reply_cpt');

// ------------------------------------------------------------------
/// @details Custom functions to be able to print the latest bbpress
/// posts (if plugin is active) on the front page.
/// Based on the idea of https://www.inmotionhosting.com/support/website/wordpress/get-post-wordpress-function.
/// This is the function producing the output.
/// @return No return, creates/prints html output.
// ------------------------------------------------------------------
function shapeSpace_show_posts() {

	// Helper function
	function get_timeinfo( $secs ) {
		if ( ceil($secs/3600) <= 1 ) {
			return sprintf(__("%d minutes ago","wpwt"),ceil($secs/60));
		} else if ( ceil($secs/3600) < 2 ) {
			return sprintf(__("an hour and %d minutes ago","wpwt"),ceil(($secs-3600)/60));
		} else {
			return sprintf(__("%d hours ago","wpwt"),floor($secs/3600));
		}
	}
		
	//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
   $paged = 1;
	
   // Loading data
   $args = array('post_type' => array('reply','topic'),
                 'posts_per_page' => 5, 'paged' => $paged,
                 'order' => 'DESC');
	$wp_query = new WP_Query();
	$wp_query->query($args);

	// Styling class
	$cssclass = "wt-forumactivity";
	$now = (int)time();

   // If there are forum posts
   if ( $wp_query->have_posts() ) { ?>

		<style type="text/css">
		ul#<?php print $cssclass; ?> {
			list-style: none;
			margin: 0;
		}		
		ul#<?php print $cssclass; ?> li {
			padding: 5px 10px;
			border-left: 3px solid #CECECE;
			margin-bottom: 5px;
			line-height: .8em;
			background-color: #eef0f2;
		}
		ul#<?php print $cssclass; ?> li.topic {
			border-color: #FF6600;
		}
		ul#<?php print $cssclass; ?> li span.meta {
			margin: 0; padding: 0;
		}
		ul#<?php print $cssclass; ?> li span.meta {
			margin: 0; padding: 0;
			font-size: 0.8em;
			line-height: 0.5em;
			color: gray;
		}
		</style>

		<article class="hentry">
      <h1 class="entry-title"><?php _e("Latest forum activity","wpwt"); ?></h1>
      <ul id="<?php print $cssclass; ?>">
      <?php while ($wp_query->have_posts() ) {
         $wp_query->the_post();
         $post = get_post();

         // Check post type (typoic or reply)
         $istopic  = strcmp(get_post_type(),"topic") === 0;
 			$author   = get_the_author();

			// Posted
			$timeinfo = get_timeinfo($now-(int)get_the_time("U"));
         ?>
	
            <li class="<?php printf("%s %s",$cssclass,($istopic) ? "topic" : "reply"); ?>">
               <?php

					// Output for topic
					if ( $istopic ) {

						// Oputput
						printf("<span class=\"%s new topic\">%s: <a href=\"%s\">%s</a></span><br>",
								$cssclass,__("New topic","wpwt"),get_the_permalink(),get_the_title());

					} else {
						
						// Loading title of the parent topic
						$parent = get_the_title( (int)$post->post_parent );
						// Oputput
						printf("<span class=\"%s new reply\">%s <a href=\"%s\">%s</a></span><br>",
								$cssclass,__("New reply on","wpwt"),get_the_permalink(),$parent);

					} ?>

					<span class="<?php print $cssclass; ?> meta">
					<?php printf("<span class=\"%s user\">%s %s</span>",
							$cssclass,__("Created by","wpwt"),$author); ?>
					<?php printf("%s <b>%s</b> %s <b>%s (%s)</b>",
							__("on","wpwt"),get_the_time("Y-m-d"),
							__("at","wpwt"),get_the_time("H:i"),$timeinfo); ?>
					</span>

				</li>
	         	
	         	
	         <?php } // endwhile ?> 

	        <?php wp_reset_postdata(); ?>
	        <?php wp_reset_query(); ?>
      </ul>
		</article>
	<?php } ?>
		
<?php }

?>
