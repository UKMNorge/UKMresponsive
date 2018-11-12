$(document).ready( function(){	
	$('.fullpage').each( function(){
		var fullpage = $(this);
		var image = new Image();
		if( fullpage.attr('data-photo-background') == 'false' ) {
			fullpage.find('.fullpage-content').css('opacity', 1);
		} else {
			image.onload = function() {
				fullpage.css('background-color', fullpage.attr('data-photo-background-transition'));
				fullpage.find('.fullpage-content')
					.css('background-image', 'url(' + image.src + ')')
					.css('opacity', 1)
					.attr('test', image.src);
			}
			image.src = $(this).attr('data-photo-background');
		}
	});
	
	new fullpage('#fullpage', {
		anchors:['start', 'om', 'finn'],
		licenseKey: 'D123E63D-4F5C490D-92314334-48693F2A',
		navigation: true,
		scrollOverflow: true,
	});
});