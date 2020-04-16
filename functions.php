<?php
#require get_stylesheet_directory() . '/inc/featured-content.php';
#

@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

// Disable automatic WordPress plugin updates:	
add_filter( 'auto_update_plugin', '__return_false' );

// Disable automatic WordPress theme updates:
add_filter( 'auto_update_theme', '__return_false' );

/* Disable XMLRPC */
add_filter( 'xmlrpc_enabled', '__return_false' );

/* Den HTTP-Header vom XMLRPC-Eintrag bereinigen */
add_filter( 'wp_headers', 'AH_remove_x_pingback' );
 function AH_remove_x_pingback( $headers )
 {
 unset( $headers['X-Pingback'] );
 return $headers;
 }

/* Remove XMLRPC, WLW, Generator and ShortLink tags from header */
remove_action('wp_head', 'rsd_link');


/* this function gets executed during login */
function login_actions() {
    $_SESSION["wetterturnier_city"] = get_user_option("wt_default_city");
}

add_action('wp_login', 'login_actions');

/* automatic session reset */
function logout_actions($userID) {
   $sessions = WP_Session_Tokens::get_instance( $userID );
   $sessions->destroy_all();//destroys all sessions
   //wp_clear_auth_cookie();//clears cookies regarding WP Auth
   delete_option( "wt_city_userid_" . (string)$userID );
}

// use anonymous function to pass current userID to logout_actions()
$userID = get_current_user_id();
add_action('wp_logout', function() { global $userID; logout_actions($userID); }, 10, 1);


// ------------------------------------------------------------------
/// Add Matomo (former piwik) tracking via retostauffer.org
// ------------------------------------------------------------------
function wetterturnier_matomo_tracking() { ?>

   <script type="text/javascript">
       var _paq = _paq || [];
       /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
       _paq.push(['trackPageView']);
       _paq.push(['enableLinkTracking']);
       (function() {
	var u="https://retostauffer.org/piwik/";
         _paq.push(['setTrackerUrl', u+'piwik.php']);
         _paq.push(['setSiteId', '4']);
         var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
         g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
       })();
   </script>

<?php }
// Add hook for front-end <head></head>
add_action('admin_head', 'wetterturnier_matomo_tracking');
add_action('wp_head', 'wetterturnier_matomo_tracking');


// ------------------------------------------------------------------
/// @details Custom version of the main theme (twentyfourteen)
/// post detail output function. Allows for an additional argument.
/// @param fmt. String, format for output date.
// ------------------------------------------------------------------
function twentyfourteen_posted_on( $format = "F n Y" ) {
   if ( is_sticky() && is_home() && ! is_paged() ) {
      echo '<span class="featured-post">' . __( 'Sticky', 'twentyfourteen' ) . '</span>';
   }

   // Set up and print post meta information.
   printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$
s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s"
 rel="author">%5$s</a></span></span>',
      esc_url( get_permalink() ),
      esc_attr( get_the_date( 'c' ) ),
      esc_html( get_the_date( $format ) ),
      esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
      get_the_author()
   );
}
 
// -------------------------------------------------------------------
/// @details Custom header for twentyfourteen-child theme. 
// -------------------------------------------------------------------
function wpse_custom_header_setup() {
    add_theme_support( 'custom-header', apply_filters( 'wpse_header_args', array(
        'width'                  => 1600,
        'height'                 => 140,
    ) ) );

   add_image_size( 'custom-news-thumbnail', 180, 130, true ); // 
   add_image_size( 'custom-news-small-thumbnail', 120, 50, true ); // 
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

/// @details Register custom child theme javascript file.
function register_custom_js_theme_file() {
    wp_enqueue_script(
        'custom-child-theme-script',
        get_stylesheet_directory_uri() . '/js/scroll.js',
        array( 'jquery' )
    );
}

add_action( 'wp_enqueue_scripts', 'register_custom_js_theme_file' );

// -------------------------------------------------------------------
/// @details Adding a secondary child theme language file.
// -------------------------------------------------------------------
function twentyfourteen_child_language_file() {
    load_child_theme_textdomain( 'tfchild', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'twentyfourteen_child_language_file' );

/*
//enable tab indentation in wordpress html editor
if( !function_exists('mr_tab_to_indent_in_textarea') ){
function mr_tab_to_indent_in_textarea() {
      $tabindent = '<script>
      jQuery(function($) {
         $("textarea#content, textarea#wp_mce_fullscreen").keydown(function(e){  
            if( e.keyCode != 9 ) return;
            e.preventDefault();
            var textarea = $(this)[0], start = textarea.selectionStart, before = textarea.value.substring(0, start), after = textarea.value.substring(start, textarea.value.length);
            textarea.value = before + "\t" + after; textarea.setSelectionRange(start+1,start+1);  
         });
      });</script>';
      echo $tabindent;
   }
 
   add_action('admin_footer-post-new.php', 'mr_tab_to_indent_in_textarea');
   add_action('admin_footer-post.php', 'mr_tab_to_indent_in_textarea');
}
 */

// ------------------------------------------------------------------
/// Redirect user after successful login.
///
/// @param string $redirect_to URL to redirect to.
/// @param string $request URL the user is coming from.
/// @param object $user Logged user's data.
/// @return string
// ------------------------------------------------------------------
/**
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
*/

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
/// This is the function producing the output.
/// @return No return, creates/prints html output.
// ------------------------------------------------------------------
function shapeSpace_show_posts() {

	// Helper function
	function get_timeinfo( $secs ) {
		if ( ceil($secs/3600) <= 1 ) {
			return sprintf(__("%d minutes ago","tfchild"),ceil($secs/60));
		} else if ( ceil($secs/3600) < 2 ) {
			return sprintf(__("an hour and %d minutes ago","tfchild"),ceil(($secs-3600)/60));
		} else {
			return sprintf(__("%d hours ago","tfchild"),floor($secs/3600));
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
      <h1 class="entry-title"><?php _e("Latest forum activity","tfchild"); ?></h1>
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
								$cssclass,__("New topic","tfchild"),get_the_permalink(),get_the_title());

					} else {

                  // Getting topic
                  $topic = get_post( $post->post_parent );
						// Oputput
						printf("<span class=\"%s new reply\">%s <a href=\"%s#post-%d\">%s</a></span><br>",
                     	$cssclass,__("New reply on","tfchild"),$topic->guid,
                     	$post->ID,$topic->post_title);

					} ?>

					<span class="<?php print $cssclass; ?> meta">
					<?php printf("<span class=\"%s user\">%s %s</span>",
							$cssclass,__("Created by","tfchild"),$author); ?>
					<?php printf("%s <b>%s</b> %s <b>%s (%s)</b>",
							__("on","tfchild"),get_the_time("Y-m-d"),
							__("at","tfchild"),get_the_time("H:i"),$timeinfo); ?>
					</span>

				</li>
	         	
	         	
	         <?php } // endwhile ?> 

	        <?php wp_reset_postdata(); ?>
	        <?php wp_reset_query(); ?>
      </ul>
		</article>
	<?php } ?>
		
<?php }
/* Disable image conversion introduced in Wordpress 5.3*/
add_filter( 'big_image_size_threshold', '__return_false' );
?>
