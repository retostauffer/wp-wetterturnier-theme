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


?>
