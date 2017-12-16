<?php
/**
 * Template Name: Full Width Page
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

function add_css_styling() { ?>
   <style type='text/css'>
      /* .site:before { display: none; } */
      /* .hentry { max-width: 100%; }
      div.entry-content
      { margin: 0px; padding: 0px; }
      h1.site-title { padding-left: 0px; } */
      .type-forum.hentry {
         display: block;
         position: relative;
         width: 100%;
      } 
      article.forum {
         position: relative;
      }
      #content.site-content.forum-page {
         display: block;
         max-width: 100%;
         margin: 0;
         padding: 0;
         padding-left: 272px;
         box-sizing: border-box;
      }
      #bbpress-forums .bbp-topic-content p,
      #bbpress-forums .bbp-reply-content p {
          font-size: 1.5em;
      }
      .hentry {
         width: 100%; 
         max-width: 100%;
      }
   </style>
<?php }
add_action('wp_head','add_css_styling');

get_header(); ?>

<div id="main-content" class="main-content">

   <?php
	if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'featured-content' );
	}
   ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content forum-page" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();
