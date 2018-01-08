
<?php

?>
<li>
	<?php printf("<div class=\"entry-title\">%s</div>\n",get_the_title()); ?>

   <div class="entry-meta">
      <?php	if ( has_post_thumbnail() ) {
         // Image if existing
         print "<div class=\"image\">\n";
         the_post_thumbnail( "custom-news-small-thumbnail" );
         print "</div>\n";
      } ?>

      <?php
         if ( 'post' == get_post_type() )
            twentyfourteen_posted_on( "M n y" );
         if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
            <span class="comments-link">
		      	<?php
		      		comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ),
		      									_( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) );
		      	?>
		      </span>
            <?php
         endif;
         edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
      ?>
   </div><!-- .entry-meta -->
	<?php print wp_trim_words( get_the_excerpt(), 55 ); ?>
	<?php printf("<a href=\"%s\" target=\"_self\">%s<span class=\"meta-nav\">→</span></a>",get_the_permalink(),__("Continue reading","tfchild")); ?>
</li>
