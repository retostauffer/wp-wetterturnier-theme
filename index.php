<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

$divclass = "";
if ( is_home() ) {
   #unregister_sidebar("sidebar-2");
   $divclass = "home-content";
}

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

get_header(); ?>

<div id="main-content" class="main-content <?php print $divclass; ?>">

<?php
    if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
        // Include the featured content template.
        get_template_part( 'featured-content' );
    }
?>

   <div id="primary" class="content-area">
      <div id="content" class="site-content index-content" role="main">

         <?php
         query_posts( array("posts_per_page" => 2) );
            global $wp_query;
         if ( have_posts() ) :
           // Start the Loop.
                ///$first_post = false; 
           ///while ( have_posts() ) : the_post();
                /// if ( $first_post ) {
            ///     get_template_part( 'custom-news', get_post_format() );
                ///     $first_post = false;
                ///     if ( $wp_query->found_posts > 1 ) { 
                ///         echo "<article class=\"hentry\">\n<ul id=\"custom-news-small\">\n";
                ///     }
                /// } else {
            ///     get_template_part( 'custom-news-small', get_post_format() );
                /// }
            ///endwhile;
            echo "<article class=\"hentry\">";
               printf("<h1 class=\"entry-title\">%s</h1>",__("Latest News","tfchild"));
               echo "<ul id=\"custom-news-small\">\n";
            while ( have_posts() ) : the_post();
               get_template_part( 'custom-news-small', get_post_format() );
            endwhile;
                // Link to older news
            $lang = (function_exists('pll_current_language') ? pll_current_language('slug') : 'en');
                printf("<li class=\"show-older-news\"><a href=\"/news%s/\" target=\"_self\">%s</a></li>",
                    $lang,__("Show older news","tfchild"));
            echo "</ul>\n</article>\n";

        else :
            // If no content, include the "No posts found" template.
            get_template_part( 'content', 'none' );
        endif;

         // Latest forum replies
         shapeSpace_show_posts();
         ?>

         <article class="hentry">
         <h1 class="entry-title"><?php _e("Leaderboard","wpwt"); ?></h1>
         <?php
            global $WTuser;

            // Check if there are any valid rankings at the moment
            $current = $WTuser->current_tournament;
            $scored  = $WTuser->scored_players_per_town( $current->tdate );

            // No results for the current one? Well, take the one before!
            if ( ! $scored ) {
               $current = $WTuser->older_tournament( $current->tdate );
            }
            // Looping over the different cities
            //$cities = $WTuser->get_city_data();
            //foreach ( $cities as $city ) {
            foreach ( $WTuser->get_all_cityObj() as $cityObj ) {
                print do_shortcode(sprintf("[wetterturnier_ranking type=\"weekend\" city=%d "
                                           ."limit=5 slim=false header=false tdate=%d]",
                                           $cityObj->get("ID"),$current->tdate));
            }

            // City-ranking
            print do_shortcode(sprintf("[wetterturnier_ranking type=\"cities\" city=\"1:2:3\" limit=3 slim=false header=false tdate=%d]",
                               $current->tdate));
            //////print do_shortcode(sprintf("[wetterturnier_ranking type=\"cities\" cities=\"1,2,3,4,5\" limit=3 slim=false header=false tdate=%d]",
            //////                   $current->tdate));
            ?>
         </article>
         </div>
         <div class="twocolumn-right">
      </div><!-- #content -->
   </div><!-- #primary -->
   <?php get_sidebar("content"); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
