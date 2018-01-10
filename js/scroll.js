
// Initialize demo table
jQuery(document).on('ready',function() {

   // Copy jQuery to $
   $ = jQuery
   /// Relocate element
   function sticky_relocate() {
       var window_top = $(window).scrollTop();
       var header_height = $('div.header-main').outerHeight();
       var div_top = $('#secondary').offset().top + $('img.theme-logo').height()
             $('#primary-sidebar .widget_polylang').height() + header_height; 
       console.log( window_top + "  header: " + header_height + "  div: " + div_top );
       if (window_top > div_top) {
           console.log('MAKE sticky');
           $('#primary-sidebar').addClass('stick')
                  .css("width",$("#secondary").width());
       } else {
           console.log('REMOVE sticky');
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
