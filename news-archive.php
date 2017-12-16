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

// Pagination
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<div id="news-archive-wrapper">
   <?php
   query_posts(sprintf('paged=%d',$paged));
   //query_posts(sprintf('posts_per_page=2&paged=%d',$paged));
   if ( have_posts() ) :
      // Start the Loop.
      while ( have_posts() ) : the_post();
   
         /* Include the post format-specific template for the content. If you want to
          * use this in a child theme, then include a file called called content-___.php
          * (where ___ is the post format) and that will be used instead.
          */
         get_template_part( 'custom-news', get_post_format() );
   
      endwhile;
      // Previous/next post navigation.
      twentyfourteen_paging_nav();
   else :
   	// If no content, include the "No posts found" template.
   	get_template_part( 'content', 'none' );
   endif;
   
   wp_reset_query();
   ?>
</div>
