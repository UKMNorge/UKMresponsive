// @codekit-prepend "jquery-3.2.1.min.js";
// @codekit-prepend "jquery-autoshrink.min.js";
// @codekit-prepend "prototype.js";
// @codekit-prepend "aos.js";
// @codekit-prepend "jquery.imagesGrid.js";
// @codekit-prepend "cookie.js";
// @codekit-prepend "UKMmobil.js";
// @codekit-prepend "UKMkonkurranse.js";
// @codekit-prepend "UKMpameldte.js";
// @codekit-append "UKMfylke.js";
// @codekit-append "UKMfadecollapsible.js";

var UKMresponsive = {};

/**
 * MAIN MENU BUTTON TOGGLE
 **/
jQuery(document).on('click', '#main_menu_button', function(e) {
    e.preventDefault();

    if ('hidden' == jQuery(this).attr('data-status')) {
        jQuery('#UKMDesign_content').fadeOut({
            'duration': 250,
            'complete': function() {
                jQuery('#UKMDesign_sitemap').fadeIn(function() {
                    $('#UKMDesign_sitemap .autoshrink').autoshrink();
                });
            }
        });
        jQuery(this).attr('data-status', 'visible');
    } else {
        jQuery('#UKMDesign_sitemap').fadeOut({
            'duration': 250,
            'complete': function() {
                jQuery('#UKMDesign_content').fadeIn();
            }
        });
        jQuery(this).attr('data-status', 'hidden');
    }
});

/**
 * UKMresponsive AJAX
 *
 **/
function UKMresponsiveAJAX(action, trigger, data) {
    if (null == data) {
        data = {};
    }
    data.action = 'UKMresponsive';
    data.ajaxaction = action;
    data.trigger = trigger;

    jQuery.post(ajaxurl, data, function(response) {
        if (response.success == true) {
            //console.info('TRIGGER: UKMresponsiveAJAX:success:'+ response.trigger);
            $(document).trigger('UKMresponsiveAJAX:success:' + response.trigger, response);
        } else {
            //console.info('TRIGGER: UKMresponsiveAJAX:fail:'+ response.trigger);
            $(document).trigger('UKMresponsiveAJAX:fail:' + response.trigger, response);
        }
    }, 'json');
};



/**
 * SCROLL TO ID
 **/
function scrollToId(id) {
    //	console.log('SCROLL TO ID: ' + id);

    var animateToPosition = $('#' + id).offset().top - 50;
    if (animateToPosition < 0) {
        animateToPosition = 0;
    }
    scrollToPosition(animateToPosition);
}

function scrollToPosition(targetPosition) {
    //	console.log('SCROLL TO POS: ' + targetPosition);
    $('html, body').animate({
        scrollTop: targetPosition
    }, 300);
}

/** 
 * AUTOSHRINK FOR ALLE .autoshrink
 * OBS: kan kreve mye av nettleseren, så spar bruken!
 **/
$(document).ready(function() {
    $('.autoshrink').autoshrink();
});


/** 
 * UKM-toggle
 *	KNAPPER FOR Å VISE KREVER:
 *	 - data-target="id-på-div-som-skal-toggles"
 *	 - class="UKMtoggleShow id-på-div-som-skal-toggles
 *	 
 *	KNAPPER FOR Å SKJULE KREVER:
 *	 - data-target="id-på-div-som-skal-toggles"
 *	 - class="UKMtoggleHide id-på-div-som-skal-toggles
 *	
 *	CONTAINER SOM SKAL TOGGLES KREVER:
 *	 - class="UKMtoggleContent"
 *	 - id="id-på-div-som-skal-toggles"
 **/
$(document).on('click', '.UKMtoggle', function(e) {
    e.preventDefault();

    var target = $(this).attr('data-target');
    if ($('#' + target + '.UKMtoggleContent').is(':visible')) {
        UKMtoggleHide(target);
    } else {
        UKMtoggleShow(target);
    }
});

$(document).on('click', '.UKMtoggleShow', function(e) {
    e.preventDefault();
    UKMtoggleShow($(this).attr('data-target'));
});

$(document).on('click', '.UKMtoggleHide', function(e) {
    e.preventDefault();
    UKMtoggleHide($(this).attr('data-target'));
});

function UKMtoggleHide(target) {
    $(document).trigger('pre_UKMtoggleHide#' + target);
    $('#' + target + '.UKMtoggleContent').slideUp(function() { $(document).trigger('UKMtoggleHide#' + target); });
    $('.' + target + '.UKMtoggleHide').hide();
    $('.' + target + '.UKMtoggleShow').fadeIn();
}

function UKMtoggleShow(target) {
    $(document).trigger('pre_UKMtoggleShow#' + target);
    $('#' + target + '.UKMtoggleContent').slideDown(function() {
        $(document).trigger('UKMtoggleShow#' + target);
        AOS.refresh();
    });
    $('.' + target + '.UKMtoggleShow').hide();
    $('.' + target + '.UKMtoggleHide').fadeIn();
}

/**
 * FORSIDE: UKMfavoritt
 * 
 * Henter ut info om lagret favoritt-side til UKM.no::forsiden
 **/
// FAILED
$(document).on('UKMresponsiveAJAX:fail:favoritt', function(e, JSONresponse) {
    // console.warn('Favoritt ikke lagret');
});
// SUCCESS
$(document).on('UKMresponsiveAJAX:success:favoritt', function(e, JSONresponse) {
    $('.mitt_UKM').html(JSONresponse.html);
    $('.mitt_UKM').html(JSONresponse.html);
    $('.UKMfavoritt').fadeIn(200);
});


/**
 * FYLKESSIDE
 *
 * Resize knappetekst for lokalmønstring når listen vises
 **/
var fylkePositionAtToggleLokalTriggered = 0;

// Når listen vises, resize knappetekst og lagre scroll-position
$(document).on('UKMtoggleShow#UKMtoggleLokal', function() {
    $(window).trigger('resize');
    fylkePositionAtToggleLokalTriggered = $(window).scrollTop();
});

// Når listen lukkes, returner til tidligere scroll-position
$(document).on('pre_UKMtoggleHide#UKMtoggleLokal', function() {
    scrollToPosition(fylkePositionAtToggleLokalTriggered);
});


/**
 * KOMMUNESIDE
 *
 * Lagre som favoritt
 **/
$(document).on('click', '#saveAsMine', function(e) {
    e.preventDefault();
    if ($(this).attr('data-saved') == 'false') {
        Cookies.set('UKMfavoritt', $(this).attr('data-plid'), { expires: 365 });
        $(this).attr('data-saved', 'true');
        $(this).find('span.icon').removeClass('icon-heart-outlined').addClass('icon-heart');
        $('#saveAsMineExplanation').slideDown();
        $(this).find('.text').text($(this).find('.text').attr('data-saved'));
    } else {
        $(this).attr('data-saved', 'false');
        Cookies.remove('UKMfavoritt');
        $(this).find('span.icon').removeClass('icon-heart').addClass('icon-heart-outlined');
        $('#saveAsMineExplanation').slideUp();
        $(this).find('.text').text($(this).find('.text').attr('data-save'));
    }
});

/**
 * PROGRAM-SIDEN
 *
 **/
jQuery(document).on('click', '.huskMeg', function() {
    var huskId = 'UKMhusk-' + $(this).attr('data-husk') + '-' + $(this).attr('data-husk-id');

    if (jQuery(this).attr('data-husk-status') == 'true') {
        jQuery(this).attr('data-husk-status', 'false');
        jQuery(this).addClass('icon-star-outlined').removeClass('icon-star-full');
        Cookies.remove(huskId);
    } else {
        Cookies.set(huskId, true, { expires: 365 });
        jQuery(this).attr('data-husk-status', 'true');
        jQuery(this).addClass('icon-star-full').removeClass('icon-star-outlined');
    }
});

/**
 * PROGRAM: VIS POST I KATEGORI
 **/
$(document).on('showPost', function(e, post) {
    post.attr('data-visning', 'synlig');

    var headerContainer = post.find('.header');
    var dataContainer = post.find('.data');
    var buttonContainer = post.find('.cancel');

    headerContainer.slideUp();
    $.post(
        post.attr('data-post-url'), {
            contentMode: true,
            hideTopImage: post.attr('data-post-hideTopImage'),
        },
        function(response) {
            dataContainer.html(response);
        }
    );
    dataContainer.slideDown();
    buttonContainer.fadeIn();
});

$(document).on('hidePost', function(e, post) {
    post.attr('data-visning', 'skjult');
    var headerContainer = post.find('.header');
    var dataContainer = post.find('.data');
    var buttonContainer = post.find('.cancel');
    buttonContainer.fadeOut();
    dataContainer.slideUp(400, function() {
        $(this).html('Vennligst vent, laster inn...');
    });
    headerContainer.slideDown();
});

$(document).on('click', '.showPost .header', function(e) {
    e.preventDefault();
    $(document).trigger('showPost', [$(this).parents('.post')]);
});
$(document).on('click', '.hidePost', function(e) {
    e.preventDefault();
    $(document).trigger('hidePost', [$(this).parents('.post')]);
});

/**
 * DELTAKERE-SIDEN
 **/
$(document).on('visInnslag', function(e, innslag) {
    innslag.attr('data-visning', 'synlig');
    var dataContainer = innslag.find('.innslagData');
    var buttonContainer = innslag.find('.innslagCancel');
    var header = innslag.find('.header');

    header.slideUp();
    $.post(
        blog_url + 'pameldte/' + innslag.attr('data-id') + '/', {
            singleMode: "true"
        },
        function(response) {
            dataContainer.html(response);
        }
    );
    dataContainer.slideDown();
    buttonContainer.fadeIn();
});

$(document).on('skjulInnslag', function(e, innslag) {
    innslag.attr('data-visning', 'skjult');
    var dataContainer = innslag.find('.innslagData');
    var buttonContainer = innslag.find('.innslagCancel');
    var header = innslag.find('.header');

    header.slideDown();
    dataContainer.slideUp(
        400,
        function() {
            dataContainer.html('<div class="center">Vennligst vent, laster inn...</div>');
        }
    );
    buttonContainer.fadeOut();
});

$(document).on('click', '.innslagCard .header', function(e) {
    e.preventDefault();
    var innslag = $(this).parents('.innslagCard');
    if (innslag.attr('data-visning') == 'synlig') {
        $(document).trigger('skjulInnslag', [innslag]);
    } else {
        $(document).trigger('visInnslag', [innslag]);
    }

    return false;
});

$(document).on('click', '.hideInnslag', function() {
    var innslag = $(this).parents('.innslagCard');
    $(document).trigger('skjulInnslag', [innslag]);
});
/**
 * AOS - Animate On Scroll
 *
 **/
$(document).ready(function() {
    AOS.init();
});

/**
 * BILDEGALLERI
 **/
$(document).ready(function() {
    $('.swipebox').swipebox({
        removeBarsOnMobile: false,
        hideBarsDelay: 0
    });
    $(".images-grid").imagesGrid({
        rowHeight: 65,
        margin: 5,
    });

    $('.lazyLoad').each(function() {
        if ($(this).attr('data-src')) {
            $(this).attr('src', $(this).attr('data-src'));
        }
    });
});


/**
 * UKM-liste
 * Standard-liste for visning av data
 * TODO: Bør implementeres av deltakere + program
 */
$(document).on('click', '.UKMliste.expandable > li > header', function(e) {
    e.preventDefault();

    var li = $(this).parents('li');

    /**
     * Klikk på header med synlig innhold = lukk
     * Finn og trykk på cancel-knapp i footer 
     **/
    if (li.find('section').is(':visible')) {
        li.find('footer .cancel').click();
        return false;
    }
    /**
     * <li> <header class="sticky"> lar headeren bli igjen,
     * MEN skjuler description-tag'en
     * 
     * <li> <header class="super-sticky"> lar hele headeren bli igjen
     **/
    if ($(this).hasClass('sticky')) {
        $(this).find('.description').slideUp();
    } else if (!$(this).hasClass('super-sticky')) {
        $(this).slideUp();
    }
    li.find('section, footer').slideDown();
    $(document).trigger('UKMliste:load:' + li.attr('data-trigger'), [li]);
});

$(document).on('click', '.UKMliste.expandable > li .cancel', function(e) {
    e.preventDefault();
    var li = $(this).parents('.UKMliste > li');
    li.find('section, footer').slideUp();
    li.find('> header, > header > .description').slideDown();
    $(document).trigger('UKMliste:unload:' + li.attr('data-trigger'), [li]);
});

/* DEMO FUNCTIONS UKM-liste trigger
$(document).on('UKMliste:load:sanger', function(e, li) {
	console.warn('Triggered load:sanger');
	console.log( li.attr('data-id') );
	
});

$(document).on('UKMliste:unload:sanger', function(e, li) {
	console.error('Triggered unload:sanger');
	li.find('section').html('Vennligst vent, laster inn');
	console.log( li.attr('data-id') );
});
*/