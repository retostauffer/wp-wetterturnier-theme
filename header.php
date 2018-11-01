<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <!-- Used to hide login button if logged in -->
    <?php if ( is_user_logged_in() ) { ?>
        <style type="text/css">
        aside.widget.bbp_widget_login h1.widget-title { display: none !important; }
        </style>
    <?php } ?>

    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
    <![endif]-->
    <?php wp_head(); ?>
    <script type='text/javascript'>
    jQuery(document).on('ready', function() {

        // Show extra button before <a> href in collapsed responsive menu
        jQuery( "#secondary-topbar ul li.menu-item-has-children > a" )
            .before( "<span class=\"menu-show-children collapsed\"></span>");

        jQuery( "#secondary-topbar ul li span.menu-show-children" ).on( "click", function(e) {
            e.stopImmediatePropagation();
            if ( ! $(this).hasClass("expanded") ) {
                $( this ).addClass("expanded");
                $( this ).closest("li").children("ul.sub-menu").show();
            } else {
                $( this ).removeClass("expanded");
                $( this ).closest("li").children("ul.sub-menu").hide();
            }
        }); 

        // Navigation for browser (not collapsed version)
        jQuery('#primary-sidebar li.menu-item-has-children').on('mouseover',function() {
            jQuery( this ).children('ul.sub-menu' )
                .show();
        }).on('mouseleave',function() {
            jQuery( this ).children('ul.sub-menu' )
                .hide();
        });

        // Show wordpress/phpbb login form 
        jQuery('.bbp_widget_login > h1').on('click',function() {
            obj = jQuery( this ).parent('aside')
            bbpdisp = jQuery( obj ).children('form.bbp-login-form' ).css('display');
            //console.log( bbpdisp )
            // Show or hide, depends on current css state
            if ( bbpdisp == 'none' ) {
                jQuery( obj ).children('form.bbp-login-form' ).show();
            } else {
                jQuery( obj ).children('form.bbp-login-form' ).hide();
            }
        });

        // Header image link 
        jQuery( '#site-header').on('click',function() {
            window.location.replace("<?php print esc_url( home_url( '/' ) ); ?>");
        });

        // Functionality for the secondary-topbar navigation menu
        jQuery("#secondary-topbar").on('click',function() {
           var stat = jQuery(this).children("ul").css("display");
           console.log(stat)
           if ( stat == "block" ) {
              jQuery(this).children("ul").hide(); //.css('display','inline');
           } else {
              jQuery(this).children("ul").show();
              // At the same time: hide open login-topbar if visible
              jQuery('#login-topbar > form').hide();
           }
        });

        // Functionality for the login-topbar login form
        // and the login-cities-menu login form.
        jQuery("#login-topbar, #login-cities-menu").on("click",function() {
           var stat = jQuery(this).children("div").css("display");
console.log("show hide")
           if ( stat === "none" ) {
              $(this).children("div").fadeIn()
           } else {
              $(this).children("div").fadeOut()
              // At the same time: hide topbar navigation if visible
              jQuery('#secondary-topbar > ul').hide()
           }
        });
        jQuery("form.bbp-login-form *, div.cities-menu-topbar-form, div.login-topbar-form").on('click',function(event) {
           event.stopPropagation();
        });
         

        // Disable login form, force the user to enable the form by accepting
        // the GPDR privacy statement.
        <?php
        $gdpr = sprintf("%s <a href='/%s' target='_self'></a>",
            __("Accept GDPR privacy policy", "tfchild"), "gdpr");
        ?>
        $.each($("form.bbp-login-form, #login-cities-menu > div, #login-topbar > div"), function() {
            var gdpr = "<div class=\"wt-gdpr\"><span><?php print $gdpr; ?></span></div>";
            $(this).prepend(gdpr);
        });
        $(".wt-gdpr a").on("click", function(event) { event.stopPropagation(); });

        // Add interactivity to the .wt-gdpr buttons
        $(".wt-gdpr").on("click", function(event) {
            console.log($(this))
            console.log($(this).hasClass("agreed"))
            if ( $(this).hasClass("agreed") ) {
                $(this).parent("form, div").find("fieldset, form").fadeOut();
                $(this).removeClass("agreed");
            } else {
                $(this).parent("form, div").find("fieldset, form").fadeIn();
                $(this).addClass("agreed");
            }
        });

        // for smaller devices there is a selection where the user can
        // switch between "show news" or "show overview"
        // It is actually "show div.twocolumn-left" or "show.div-twocolumn-right".
        // as defined in index.php. Depends what you put in there.
        jQuery("div#content.index-content .twocolumn-select input").on("click",function() {
           if ( $(this).attr("show") == "left" ) {
              jQuery("div#content div.twocolumn-right").hide();
              jQuery("div#content div.twocolumn-left").show();
           } else {
              jQuery("div#content div.twocolumn-left").hide();
              jQuery("div#content div.twocolumn-right").show();
           }
        });
    });
    </script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
    <?php if ( get_header_image() ) : ?>
   <!-- display: none. Hide header. Has to stay go guarantee that the navigation has correct background -->
    <div id="site-header" style="display: none; height: <?php echo get_custom_header()->height ?>px;background-image: url(<?php echo header_image() ?>); background-repeat: no-repeat;">
    <!-- <div id="site-header" style="height: <?php echo get_custom_header()->height ?>px;width: <?php echo get_custom_header()->width ?>px;background-image: url(<?php echo header_image() ?>);"> -->
    </div>
    <?php endif; ?>

    <header id="masthead" class="site-header" role="banner">
        <div class="header-main">
           <div id="secondary-topbar">
           <?php wp_nav_menu( array('slug'=>'secondary','container'=>'false') ); ?>
           </div>
           <?php if ( ! is_user_logged_in() ) { ?>
               <div id="login-topbar">
                  <div class="login-topbar-form">
                  <?php // Login form for the collapsed navigation
                  wp_login_form(array(
                      "remember"    => true,
                      "form_id"     => "topbar-login",
                      "id_username" => "topbar-user_login",
                      "id_password" => "topbar-user_pass",
                      "id_remember" => "topbar-rememberme",
                      "id_submit"   => "topbar-wp-submit"
                  )); ?>
                  </div>
               </div>
           <?php } else { ?>
               <div id="logout-topbar">
                  <a class="logout-topbar" href="<?php echo wp_logout_url(); ?>"></a>
               </div>
           <?php } ?>

           <div class="search-toggle">
               <a href="#search-container" class="screen-reader-text"><?php _e( 'Search', 'twentyfourteen' ); ?></a>
           </div>

           <nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
               <button class="menu-toggle"><?php _e( 'Primary Menu', 'twentyfourteen' ); ?></button>
               <a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'twentyfourteen' ); ?></a>
               <?php //wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
               <div class="wetterturnier-cities-menu">
               </div>
               <?php if ( ! is_user_logged_in() ) { ?>
                  <div id="login-cities-menu" class="wt-login" style="float: left;">
                      <div class="cities-menu-topbar-form">
                      <?php // Login form for the collapsed navigation
                      wp_login_form(array(
                          "remember"    => true,
                          "form_id"     => "cities-menu-login",
                          "id_username" => "cities-menu-user_login",
                          "id_password" => "cities-menu-user_pass",
                          "id_remember" => "cities-menu-rememberme",
                          "id_submit"   => "cities-menu-wp-submit"
                      )); ?>
                      </div>
                  </div>
               <?php } ?>
           </nav>
        </div>

        <div id="search-container" class="search-box-wrapper hide">
            <div class="search-box">
                <?php get_search_form(); ?>
            </div>
        </div>
    </header>

    <div id="main" class="site-main">

