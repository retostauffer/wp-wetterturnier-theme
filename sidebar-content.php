<?php
/**
 * The Content Sidebar
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( ! is_active_sidebar( 'sidebar-2' ) ) {
	return;
} else {?>
<div id="content-sidebar" class="content-sidebar content-sidebar-2 widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
</div><!-- #content-sidebar -->
<?php }

if ( ! is_active_sidebar( 'sidebar-10' ) ) {
	return;
} else {?>
<div id="content-sidebar-10" class="content-sidebar content-sidebar-10 widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-10' ); ?>
</div><!-- #content-sidebar -->
<?php } ?>
