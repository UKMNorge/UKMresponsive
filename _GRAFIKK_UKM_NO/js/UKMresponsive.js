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