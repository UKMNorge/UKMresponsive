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
			//console.info('TRIGGER: UKMresponsiveAJAX:success:'+ response.trigger);
			$(document).trigger('UKMresponsiveAJAX:success:'+ response.trigger, response);
		} else {
			//console.info('TRIGGER: UKMresponsiveAJAX:fail:'+ response.trigger);
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
	$('#' + target + '.UKMtoggleContent').slideDown(function(){
		$(document).trigger('UKMtoggleShow#'+ target);
		AOS.refresh();	
	});
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
	$( '.mitt_UKM' ).html( JSONresponse.html );
	$( '.mitt_UKM' ).html( JSONresponse.html );
	$( '.UKMfavoritt').fadeIn(200);
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
		$(this).find('.text').text( $(this).find('.text').attr('data-saved') );
	} else {
		$(this).attr('data-saved', 'false'); 
		Cookies.remove('UKMfavoritt');
		$(this).find('span.icon').removeClass('icon-heart').addClass('icon-heart-outlined');
		$('#saveAsMineExplanation').slideUp();
		$(this).find('.text').text( $(this).find('.text').attr('data-save') );	
	}
});

/**
 * PROGRAM-SIDEN
 *
**/
jQuery( document ).on('click', '.huskMeg', function(){
	var huskId = 'UKMhusk-'+ $(this).attr('data-husk') +'-'+ $(this).attr('data-husk-id');

	if( jQuery( this ).attr('data-husk-status') == 'true' ) {
		jQuery( this ).attr('data-husk-status', 'false');
		jQuery( this ).addClass('icon-star-outlined').removeClass('icon-star-full');
		Cookies.remove( huskId );
	} else {
		Cookies.set(huskId, true, {expires: 365} );
		jQuery( this ).attr('data-husk-status', 'true');
		jQuery( this ).addClass('icon-star-full').removeClass('icon-star-outlined');
	}
});

/**
 * PROGRAM: VIS POST I KATEGORI
**/
$( document ).on('showPost', function( e, post ) {
	post.attr('data-visning', 'synlig');
	
	var headerContainer = post.find('.header');
	var dataContainer = post.find('.data');
	var buttonContainer = post.find('.cancel');
	
	headerContainer.slideUp();
	$.post(
			post.attr('data-post-url'),
			{
				contentMode: true
			},
			function( response ) {
				dataContainer.html( response );
			}
		);
	dataContainer.slideDown();
	buttonContainer.fadeIn();
});

$( document ).on('hidePost', function( e, post ) {
	post.attr('data-visning', 'skjult');
	var headerContainer = post.find('.header');
	var dataContainer = post.find('.data');
	var buttonContainer = post.find('.cancel');
	buttonContainer.fadeOut();
	dataContainer.slideUp( 400, function(){
		$(this).html('Vennligst vent, laster inn...');
	});
	headerContainer.slideDown();
});

$( document ).on('click', '.showPost .header', function( e ){
	e.preventDefault();
	$( document ).trigger('showPost', [ $(this).parents('.post') ] );
});
$( document ).on('click', '.hidePost', function( e ) {
	e.preventDefault();
	$( document ).trigger('hidePost', [ $( this ).parents('.post') ]);
});

/**
 * DELTAKERE-SIDEN
**/
$( document ).on('visInnslag', function( e, innslag ){
	innslag.attr('data-visning', 'synlig');
	var dataContainer = innslag.find('.innslagData');
	var buttonContainer = innslag.find('.innslagCancel');
	var header = innslag.find('.header');
	
	header.slideUp();
	$.post(
			blog_url + 'pameldte/'+ innslag.attr('data-id') +'/',
			{
				singleMode: "true"
			},
			function( response ) {
				dataContainer.html( response );
			}
		);
	dataContainer.slideDown();
	buttonContainer.fadeIn();
});

$( document ).on('skjulInnslag', function( e, innslag ){
	innslag.attr('data-visning', 'skjult');
	var dataContainer = innslag.find('.innslagData');
	var buttonContainer = innslag.find('.innslagCancel');
	var header = innslag.find('.header');
	
	header.slideDown();
	dataContainer.slideUp(
		400,
		function(){
			dataContainer.html('<div class="center">Vennligst vent, laster inn...</div>');
		}
	);
	buttonContainer.fadeOut();
});

$( document ).on('click', '.innslagCard .header', function( e ){
	e.preventDefault();
	var innslag = $( this ).parents('.innslagCard');
	if( innslag.attr('data-visning') == 'synlig' ) {
		$( document ).trigger('skjulInnslag', [ innslag ] );
	} else {
		$( document ).trigger('visInnslag', [ innslag ] );
	}
	
	return false;
});

$( document ).on('click', '.hideInnslag', function(){
	var innslag = $(this).parents('.innslagCard');
	$( document ).trigger('skjulInnslag', [ innslag ] );
});
/**
 * AOS - Animate On Scroll
 *
**/
$(document).ready(function(){
	AOS.init();
});