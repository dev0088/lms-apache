jQuery(document).ready(function ($) {
    $('.at-customizer').on( 'click', function( evt ){
        evt.preventDefault();
        section = $(this).data('section');
        panel = $(this).data('panel');
        if ( section ) {
            wp.customize.section( section ).focus();
        }
        if ( panel ) {
            wp.customize.panel( panel ).focus();
        }
    });
});