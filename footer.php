<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

         </div><!-- #main -->

	 <footer id="colophon" class="site-footer" role="contentinfo">

         <?php
         // Theme url (need to append -child)
         $theme_uri =  get_template_directory_uri().'-child';
         ?>
			<?php get_sidebar( 'sidebar-3' ); ?>
			<?php get_sidebar( 'footer' ); ?>
         <logo>
            <a href="http://www.geo.fu-berlin.de/met/ag/strat/index.html" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_fuberlin.svg" />
            </a>
            <h1>Server and Infrastructure</h1>
         </logo>
         <logo>
            <a href="http://acinn.uibk.ac.at/" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_uibk.svg" />
            </a>
            <h1>Software and Data</h1>
         </logo>
         <logo>
            <a href="http://www.dwd.de/" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_dwd.svg" />
            </a>
            <h1>Data and Weather Products</h1>
         </logo>
         <logo>
            <a href="http://www.mswr.de/" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_meteoservice.svg" />
            </a>
            <h1>Data and Support</h1>
         </logo>
         <logo>
            <a href="http://www.ubimet.com/" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_ubimet.png" />
            </a>
            <h1>Documents and Prices</h1>
         </logo>
         <logo>
            <a href="https://www.handelsregister.international/MetMaps+GmbH.html" target="_new">
            <img src="<?php print $theme_uri; ?>/logo_metmaps.png" />
            </a>
            <h1>Visualisiation of Weather Data</h1>
         </logo>
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
