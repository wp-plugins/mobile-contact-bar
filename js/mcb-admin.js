/**
 * Admin related JavaScript functions
 * 
 * @since 0.0.1
 * 
 * @package Mobile_Contact_Bar
 * @author Anna Bansaghi <anna.bansaghi@mamikon.net>
 * @license GPL-3.0
 * @link https://github.com/bansaghi/mobile-contact-bar/
 * @copyright Anna Bansaghi
 */


;(function( $, window, document, undefined ) {
  'use strict';

  $(document).ready(function() {

    $( ".mcb-slider" ).slider({
      min: 0,
      max: 1,
      step: 0.01,
      orientation: "horizontal",
      
      create: function( event, ui ) {
        $(this).slider('value', $(event.target).next('input').val());
      },

      slide: function( event, ui ) {
        $(this).next('input').val( ui.value );
      }
    });


    $("#bar_is_toggle").click(function() {
        $("#bar_toggle_color").closest('tr').toggle(this.checked);
        $("#bar_toggle_collapse").closest('tr').toggle(this.checked);
        $("#bar_toggle_expand").closest('tr').toggle(this.checked);
    }).triggerHandler('click');

    $("#bar_toggle_color")
      .closest('td').css('padding-top', 5 ).css('padding-left', 15 )
      .prev('th').css('padding-top', 5 )
      .children('em').css('padding-left', 15 );



    $("#icon_is_border").click(function() {
        $("#icon_border_color").closest('tr').toggle(this.checked);
        $("#icon_border_width").closest('tr').toggle(this.checked);
    }).triggerHandler('click');

    $("#icon_border_color")
      .closest('td').css('padding-top', 5 ).css('padding-bottom', 0 ).css('padding-left', 15 )
      .prev('th').css('padding-top', 5 ).css('padding-bottom', 0 )
      .children('em').css('padding-left', 15 );

    $("#icon_border_width")
      .closest('td').css('padding-top', 5 ).css('padding-left', 15 )
      .prev('th').css('padding-top', 5 )
      .children('em').css('padding-left', 15 );

  });
})( jQuery, window, document );
