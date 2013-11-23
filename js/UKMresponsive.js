// Lokalmonstring slider

jQuery(document).on('click','.toggle', function(){
    if(jQuery(this).attr('data-action') == 'show') {
        jQuery( jQuery(this).attr('data-toggle') ).removeClass('visible-lg').removeClass('visible-md');
        jQuery( jQuery(this).attr('data-toggle') ).slideDown();
        jQuery(this).text("Skjul lokalmønstringer").attr('data-action', 'hide');
    } else {
        jQuery(this).text("Vis lokalmønstringer").attr('data-action', 'show');
        jQuery( jQuery(this).attr('data-toggle') ).slideUp({
            complete: function() { jQuery(this).addClass('visible-lg').addClass('visible-md');}
        });
    }
});