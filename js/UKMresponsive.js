/* DOCUMENT READY FUNCTIONS */
	jQuery(document).ready(function(){
		jQuery('#show_kontaktpersoner').attr('data-action', 'show')
									   .attr('data-toggle','#monstring_kontaktpersoner')
									   .attr('data-toggletitle', 'Kontaktpersoner')
									   .attr('data-toggleclose', 'Skjul kontaktpersoner');

		jQuery('[data-toggle=offcanvas]').click(function () {
			jQuery('.row-offcanvas').toggleClass('active');
		});
		
		jQuery('div.post div.wp-caption, div.post img').attr('style', 'max-width: 100%;height:auto');
		jQuery('div.post iframe').css('max-width','100%');
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
			innslag.trigger('hideInnslag');
		} else {
			data.slideDown();
			innslag.trigger('showInnslag');
		}
		jQuery(function($) {$(".swipebox").swipebox();});
	});
	
	jQuery(document).on('click', '.UKMTV img', function(){
		container = jQuery(this).parents('div.UKMTV');
		embedcontainer = container.find('div.embedcontainer'); 
		embedcontainer.html('<iframe src="' 
							+ container.find('div.embedcontainer').attr('data-framesource') 
							+ '?autoplay=true" frameborder width="'+ jQuery(this).width() +'" height="'+ jQuery(this).height() +'" style="max-width: 100%; border:none;"></iframe>').slideDown();
		jQuery(this).slideUp();
	});
	
	jQuery(document).on('change', '#pameldte_filter', function(){
	  url = window.location.href.split("?")[0];
	  window.location.href = url + '?type=' + jQuery(this).val();
	});
	
	
	
	// LINK #filter_search and #filter_search_mobile + bind fastLiveFilter	
	jQuery(document).on('keyup', '#filter_search', function(){
		jQuery('#filter_search_mobile').val(jQuery(this).val());
	});
	jQuery(document).on('keyup', '#filter_search_mobile', function(){
		jQuery('#filter_search').val( jQuery(this).val() );
		jQuery('#filter_search').change();
	});
	jQuery(document).ready(function() {
		jQuery('#filter_search').fastLiveFilter('#filter_this', 
												{callback:
													function(numShown) {
														if(numShown == 0) 
															jQuery('#filter_none').slideDown();
														else
															jQuery('#filter_none').hide();
													}
												});

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