/**
 * MAIN MENU BUTTON TOGGLE
**/
jQuery( document ).on('click', '#main_menu_button', function(e){
	e.preventDefault();
	
	if( 'hidden' == jQuery(this).attr('data-status') ) {
		jQuery('#UKMDesign_content').fadeOut(
			{'duration': 250, 
			 'complete': function(){
			 	jQuery('#UKMDesign_sitemap').fadeIn();
			 }
			}
		);
		jQuery(this).attr('data-status', 'visible');
	} else {
		jQuery('#UKMDesign_sitemap').fadeOut(
			{'duration': 250, 
			 'complete': function(){
			 	jQuery('#UKMDesign_content').fadeIn();
			 }
			}
		);
		jQuery(this).attr('data-status', 'hidden');
	}
});

/**
 * UKMresponsive AJAX
 *
**/
function UKMresponsiveAJAX( action, trigger, data ) {
	if( null == data ) {
		data = {};
	}
	data.action = 'UKMresponsive';
	data.ajaxaction = action;
	data.trigger = trigger;

	jQuery.post(ajaxurl, data, function(response) {
		if( response.success == true ) {
			console.info('TRIGGER: UKMresponsiveAJAX:success:'+ response.trigger);
			$(document).trigger('UKMresponsiveAJAX:success:'+ response.trigger, response);
		} else {
			console.info('TRIGGER: UKMresponsiveAJAX:fail:'+ response.trigger);
			$(document).trigger('UKMresponsiveAJAX:fail:'+ response.trigger, response);
		}
	}, 'json');
};



/**
 * SCROLL TO ID
**/
function scrollToId( id ) {
//	console.log('SCROLL TO ID: ' + id);

	var animateToPosition = $('#' + id ).offset().top - 50;
	if( animateToPosition < 0 ) {
		animateToPosition = 0;
	}
	scrollToPosition( animateToPosition );
}
function scrollToPosition( targetPosition ) {
//	console.log('SCROLL TO POS: ' + targetPosition);
	$('html, body').animate({
		scrollTop: targetPosition
	}, 300);
}

/** 
 * AUTOSHRINK FOR ALLE .autoshrink
 * OBS: kan kreve mye av nettleseren, så spar bruken!
**/	
$(document).ready( function() {
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
$(document).on('click', '.UKMtoggleShow', function(e){
	e.preventDefault();
	
	var target = $(this).attr('data-target');
	$(document).trigger('pre_UKMtoggleShow#'+ target);
	$('#' + target + '.UKMtoggleContent').slideDown(function(){$(document).trigger('UKMtoggleShow#'+ target);});
	$('.' + target + '.UKMtoggleShow').hide();
	$('.' + target + '.UKMtoggleHide').fadeIn();
});
	
$(document).on('click', '.UKMtoggleHide', function(e){
	e.preventDefault();
	
	var target = $(this).attr('data-target');
	$(document).trigger('pre_UKMtoggleHide#'+ target);
	$('#' + target + '.UKMtoggleContent').slideUp(function(){$(document).trigger('UKMtoggleHide#'+ target);});
	$('.' + target + '.UKMtoggleHide').hide();
	$('.' + target + '.UKMtoggleShow').fadeIn();
});

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
	$( '#mitt_UKM' ).html( JSONresponse.html );
	$( '#UKMfavoritt').fadeIn(200);
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
$(document).on('pre_UKMtoggleHide#UKMtoggleLokal', function(){
	scrollToPosition( fylkePositionAtToggleLokalTriggered );
});


/**
 * KOMMUNESIDE
 *
 * Lagre som favoritt
**/
$(document).on('click','#saveAsMine', function(e){
	e.preventDefault();
	if( $(this).attr('data-saved') == 'false' ) {
		Cookies.set('UKMfavoritt', $(this).attr('data-plid'), { expires: 365 });
		$(this).attr('data-saved', 'true');
		$(this).find('span.icon').removeClass('icon-heart-outlined').addClass('icon-heart');
		$('#saveAsMineExplanation').slideDown();
		$(this).find('.text').text( $(this).find('.text').text().replace('lagre', 'lagret') );
	} else {
		$(this).attr('data-saved', 'false');
		Cookies.remove('UKMfavoritt');
		$(this).find('span.icon').removeClass('icon-heart').addClass('icon-heart-outlined');
		$('#saveAsMineExplanation').slideUp();
		$(this).find('.text').text( $(this).find('.text').text().replace('lagret', 'lagre') );	
	}
});