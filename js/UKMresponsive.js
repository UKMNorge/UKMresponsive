jQuery(document).on('click','.toggle', function(){
    if(jQuery(this).attr('data-action') == 'show') {
        jQuery( jQuery(this).attr('data-toggle') ).slideDown();
        jQuery(this).text("Skjul lokalmønstringer").attr('data-action', 'hide');
    } else {
        jQuery( jQuery(this).attr('data-toggle') ).slideUp();
        jQuery(this).text("Vis lokalmønstringer").attr('data-action', 'show');
    }
});