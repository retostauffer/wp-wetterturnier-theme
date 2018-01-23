
// Initialize demo table
jQuery(document).on('ready',function() {

   // Copy jQuery to $
   $ = jQuery
   /// Relocate element
   function sticky_relocate() {
       // Dont make navigation sticky if vertical window height is too
       // small. Users might then not see the lower part of the navigation at all.
       var dont = $(window).height() - $("#secondary #primary-sidebar").outerHeight();
       if ( dont < 0 ) { return; }
       // Checking current scroll position
       var window_top    = $(window).scrollTop();
       var header_height = $('div.header-main').outerHeight();
       var div_top = $('#secondary').offset().top + $('img.theme-logo').height();
       var left = parseInt($("#secondary").css("padding-left")) - $(window).scrollLeft();
       if (window_top > div_top) {
           $('#primary-sidebar').addClass('stick')
              .css("left", parseInt($("#secondary").css("padding-left")) - $(window).scrollLeft() )
              .css("width",$("#secondary").width());
       } else {
           $('#primary-sidebar').removeClass('stick')
                  .css("width","100%");
       }
   }
   
   $(function() {
       $(window).scroll(sticky_relocate);
       sticky_relocate();
   });
   
   var dir = 1;
   var MIN_TOP = 200;
   var MAX_TOP = 350;
   
   function autoscroll() {
       var window_top = $(window).scrollTop() + dir;
       if (window_top >= MAX_TOP) {
           window_top = MAX_TOP;
           dir = -1;
       } else if (window_top <= MIN_TOP) {
           window_top = MIN_TOP;
           dir = 1;
       }
       $(window).scrollTop(window_top);
       window.setTimeout(autoscroll, 100);
   }

});
