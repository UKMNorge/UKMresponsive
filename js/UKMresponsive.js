/* DOCUMENT READY FUNCTIONS */
	jQuery(document).ready(function(){
		jQuery('#show_kontaktpersoner').attr('data-action', 'show')
									   .attr('data-toggle','#monstring_kontaktpersoner')
									   .attr('data-toggletitle', 'Kontaktpersoner')
									   .attr('data-toggleclose', 'Skjul kontaktpersoner');
	});

/* DOCUMENT ON CLICK */
	jQuery(document).on('click','#lokalmonstringer_toggle', function(){
		console.log('Lokalmønstringer');
		pageFocus( jQuery(this) );
	});
	
	jQuery(document).on('click','#show_kontaktpersoner', function(){
		pageFocus( jQuery(this) );
	});
	
	jQuery(document).on('click','li.innslag div.header', function() {
		jQuery('li.innslag div.row.data').slideUp();
		innslag = jQuery(this).parents('li.innslag');
		data = innslag.find('div.row.data');
		if(data.is(':visible')) {
			data.slideUp();
		} else {
			data.slideDown();
		}
	});
	
	jQuery(document).on('click', '.UKMTV img', function(){
		container = jQuery(this).parents('div.UKMTV');
		embedcontainer = container.find('div.embedcontainer'); 
		embedcontainer.html('<iframe src="' 
							+ container.find('div.embedcontainer').attr('data-framesource') 
							+ '?autoplay=true" frameborder width="'+ jQuery(this).width() +'" height="'+ jQuery(this).height() +'" style="max-width: 100%; border:none;"></iframe>').slideDown();
		jQuery(this).slideUp();
	});

/* ON CLICK OF TOGGLE-CLASS ELEMENTS */
jQuery(document).on('click','.toggle', function(){
    if(jQuery(this).attr('data-action') == 'show') {
        jQuery( jQuery(this).attr('data-toggle') ).slideDown();
        jQuery(this).attr('data-action', 'hide');
    } else {
        jQuery(this).attr('data-action', 'show');
        jQuery( jQuery(this).attr('data-toggle') ).slideUp();
    }
});


/* UKM PAGE FOCUS / DEFOCUS */
jQuery(document).on('click','#pageDeFocus',function(){
	jQuery( '#' + jQuery(this).attr('data-clicker') ).click();
});
function pageFocus( clicked ) {
    if(clicked.attr('data-action') == 'show') {
        clicked.attr('data-action', 'hide');

        jQuery('#ukm_page_content').hide();
        jQuery('#ukm_page_pre_content').html( jQuery( clicked.attr('data-toggle') ).html() ).slideDown();

		jQuery('#ukm_page_jumbo_content').hide();
		jQuery('#ukm_page_jumbo_temp').html( clicked.attr('data-toggletitle') ).show();
		
		jQuery('#ukm_page_post_content').show();
		jQuery('#pageDeFocus').attr('data-clicker', clicked.attr('id') ).html( clicked.attr('data-toggleclose') );
    } else {
        clicked.attr('data-action', 'show');

		jQuery('#ukm_page_jumbo_temp').html('').hide();
		jQuery('#ukm_page_jumbo_content').show();

		jQuery('#ukm_page_pre_content').slideUp( 400, function(){jQuery('#ukm_page_content').show()} );
		jQuery('#ukm_page_post_content').hide();
    }	
}