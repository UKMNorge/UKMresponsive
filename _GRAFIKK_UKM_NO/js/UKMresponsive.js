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

/* UKM-toggle
KNAPPER FOR Å VISE KREVER:
 - data-attr="id-på-div-som-skal-toggles"
 - class="UKMtoggleShow id-på-div-som-skal-toggles
 
KNAPPER FOR Å SKJULE KREVER:
 - data-attr="id-på-div-som-skal-toggles"
 - class="UKMtoggleShow id-på-div-som-skal-toggles

CONTAINER SOM SKAL TOGGLES KREVER:
 - class="UKMtoggleContent"
 - id="id-på-div-som-skal-toggles"
*/
$(document).on('click', '.UKMtoggleShow', function(e){
	e.preventDefault();
	
	var target = $(this).attr('data-target');
	$('#' + target + '.UKMtoggleContent').slideDown();
	$('.' + target + '.UKMtoggleShow').hide();
	$('.' + target + '.UKMtoggleHide').fadeIn();
});
	
$(document).on('click', '.UKMtoggleHide', function(e){
	e.preventDefault();
	
	var target = $(this).attr('data-target');
	$('#' + target + '.UKMtoggleContent').slideUp();
	$('.' + target + '.UKMtoggleHide').hide();
	$('.' + target + '.UKMtoggleShow').fadeIn();
});