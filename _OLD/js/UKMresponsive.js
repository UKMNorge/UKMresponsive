function pageFocus(clicked) {
    if (clicked.attr('data-action') == 'show') {
        clicked.attr('data-action', 'hide');

		var title = clicked.attr('data-showJumboUKM');
		if (title == 'true') {
			jQuery('#ukm_page_jumbo_header').hide();
			jQuery('#ukm_page_jumbo_header_temp').show();
		}

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

		jQuery('#ukm_page_pre_content').slideUp( 400, function(){jQuery('#ukm_page_content').show();} );
		jQuery('#ukm_page_post_content').hide();
		
		jQuery('#ukm_page_jumbo_header_temp').hide();
		jQuery('#ukm_page_jumbo_header').show();
    }
}

/* DOCUMENT READY FUNCTIONS */
	jQuery(document).ready(function(){
		jQuery('#show_kontaktpersoner').attr('data-action', 'show')
										.attr('data-toggle','#monstring_kontaktpersoner')
										.attr('data-toggletitle', 'Kontaktpersoner')
										.attr('data-toggleclose', 'Skjul kontaktpersoner');

		jQuery('div.post div.wp-caption, div.post img').attr('style', 'max-width: 100%;height:auto');
		jQuery('div.post iframe').css('max-width','100%');
	});

/* DOCUMENT ON CLICK */
	jQuery(document).on('click','#lokalmonstringer_toggle', function(){
		pageFocus( jQuery(this) );
	});
	
	jQuery(document).on('click','#show_kontaktpersoner,#show_main_mobile_menu', function(){
		pageFocus( jQuery(this) );
	});
	
	jQuery(document).on('change','#select_lokalmonstring', function(){
		var selected = jQuery(this).val();
		if (selected !== undefined && selected !== null) {
			window.location.href = selected;
		}
	});
	
	jQuery(document).on('click','li.innslag div.header', function() {
		jQuery('li.innslag div.row.data').slideUp();
		var innslag = jQuery(this).parents('li.innslag');
		var data = innslag.find('div.row.data');
		if (data.is(':visible')) {
			data.slideUp(400,'swing',function(){
				jQuery('#'+innslag.attr('id')).trigger('hiddenInnslag');
			});
			innslag.trigger('hideInnslag');
		} else {
			data.slideDown(400,'swing',function(){
				jQuery('#'+innslag.attr('id')).trigger('visibleInnslag');
			});
			innslag.trigger('showInnslag');
		}
	});
	
	
	jQuery(document).on('showInnslag','li.innslag', function(){
		var innslag = jQuery(this);
		innslag.find('.image_album_innslag').each(function(){
			var image_album = jQuery(this);
			var stupid_load = image_album.find('div.stupid_load');
			//var grid_load   = image_album.find('div.grid_load');
			var photos = [];
			
			// Loop all images
			stupid_load.find('img').each(function(){
				var thumb = jQuery(this);
				photos.push( {	'id': thumb.attr('data-id'),
								'source': thumb.attr('data-source'),
								'width': thumb.attr('data-width'),
								'height': thumb.attr('data-height'),
								'link': thumb.attr('data-source')
				});
			});
			processPhotos( photos, '#'+ image_album.attr('id') );
		});
	});
	jQuery(document).on('hideInnslag','li.innslag', function(){
		var innslag = jQuery(this);
		innslag.find('div.grid_load').html('');
		innslag.find('div.stupid_load').show();
	});
	jQuery(document).on('visibleInnslag','li.innslag', function(){
		jQuery(this).find('.UKMTV img:visible').click();
	});
	
	
	jQuery(document).on('click', '.UKMTV img', function(){
		var container = jQuery(this).parents('li.UKMTV');
		var embedcontainer = container.find('div.embedcontainer');
		embedcontainer.html('<iframe src="' +
							container.find('div.embedcontainer').attr('data-framesource') +
							'" frameborder width="'+ jQuery(this).width() +
							'" height="'+ jQuery(this).height() +
							'" style="max-width: 100%; border:none;"></iframe>').slideDown();
		jQuery(this).slideUp();
	});
	
	jQuery(document).on('change', '#pameldte_filter', function(){
		var url = window.location.href.split("?")[0];
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
														if (numShown === 0) {
															jQuery('#filter_none').slideDown();
														} else {
															jQuery('#filter_none').hide();
														}
													}
												});
		jQuery('#filter_search').change();
	});

/* ON CLICK OF TOGGLE-CLASS ELEMENTS */
jQuery(document).on('click','.toggle', function(){
    if (jQuery(this).attr('data-action') == 'show') {
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
/* Gjør hele lokalmønstringsknappen klikkbar */
jQuery(document).on('click','li.lokalmonstring', function(e) {
	window.location.href = jQuery(this).find('a').attr('href');
});