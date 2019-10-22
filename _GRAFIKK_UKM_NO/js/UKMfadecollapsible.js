/**
 * Fade-collapsible
 */
jQuery(document).on('click', '.fade-collapsible .actions', function() {
    var collapsible = $(this).parents('.fade-collapsible');
    var content = collapsible.find('.content');

    content.toggleClass('-expanded');

    if (content.hasClass('-expanded')) {
        collapsible.find('.actions .icon')
            .removeClass('dashicons-arrow-down-alt2')
            .addClass('dashicons-arrow-up-alt2');
    } else {
        collapsible.find('.actions .icon')
            .removeClass('dashicons-arrow-up-alt2')
            .addClass('dashicons-arrow-down-alt2');
    }
});