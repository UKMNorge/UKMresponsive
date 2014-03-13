jQuery(document).ready(function(){
	
	jQuery('.image_album').each(function(){
		  image_album = jQuery(this);
		  stupid_load = image_album.find('div.stupid_load');
		  grid_load   = image_album.find('div.grid_load');
		  photos = new Array();
		
		  // Loop all images
		  stupid_load.find('img').each(function(){
		    thumb = jQuery(this);
		    //grid_load.append('<img src="' + thumb.attr('data-source') +'" width="300" />');
		
		    photos.push( {'id': thumb.attr('data-id'),
							  'source': thumb.attr('data-source'),
							  'width': thumb.attr('data-width'),
							  'height': thumb.attr('data-height'),
							  'link': thumb.attr('data-source')
							  });
		  });
		processPhotos( photos, '#'+image_album.attr('id') );
	});
	
});

function processPhotos(photos, containerSelector){
	// divs to contain the images
//	var d = $("div.picrow");
	parent_container = jQuery( containerSelector );
	parent_container.parents('div.removePadding').css('padding', '5px');
	var d = parent_container.find('.grid_load');
		
	// get row width - this is fixed.
	var w = d.eq(0).innerWidth();
	if( w == null)
		return false;
	
	// initial height - effectively the maximum height +/- 10%;
	var h = 250;
	// margin width
	var border = 5;
	
	// store relative widths of all images (scaled to match estimate height above)
	var ws = [];
	$.each(photos, function(key, val) {
		var wt = parseInt(val.width, 10);
		var ht = parseInt(val.height, 10);
		if( ht != h ) { wt = Math.floor(wt * (h / ht)); }
		ws.push(wt);
	
	});
	
	// total number of images appearing in all previous rows
	var baseLine = 0; 
	var rowNum = 0;
	var imageCount = 0;
	var photos_printed = 0;
	while(photos.length > photos_printed) {
		rowNum++;
		var d_row = jQuery('<div/>', {id: 'row_'+rowNum, 'class':'album_row'});
		d.append(d_row);
		d_row.empty();
		
		// number of images appearing in this row
		var c = 0; 
		// total width of images in this row - including margins
		var tw = 0;
		
		// calculate width of images and number of images to view in this row.
		while( tw * 1.1 < w) {
			if(ws[baseLine+c] == undefined)
				break;
			tw += ws[baseLine + c] + border * 2;
			c++;
		}
		// Ratio of actual width of row to total width of images to be used.
		var r = w / tw; 
		
		// image number being processed
		var i = 0;
		// reset total width to be total width of processed images
		tw = 0;
		// new height is not original height * ratio
		var ht = Math.floor(h * r);
		while( i < c ) {
			var photo = photos[baseLine + i];
			// Calculate new width based on ratio
			var wt = Math.floor(ws[baseLine + i] * r);
			// add to total width with margins
			tw += wt + border * 2;
			// Create image, set src, width, height and margin
			
			var img = $('<img/>', {'class': "album_image clickable", 
								   'src': photo.source,
/* 								   'width': wt, */
								   'height': ht,
								   'data-link': photo.link}).css("margin", border + "px");
			var link = $('<a/>', {'href': photo.link}).html(img);
			d_row.append(link);
			//d_row.append(img);
			
			i++;
			photos_printed++;
		}
		// if total width is slightly smaller than 
		// actual div width then add 1 to each 
		// photo width till they match
		
		i = 0;
		while( tw < w ) {
			var img1 = d_row.find("img:nth-child(" + (i + 1) + ")");
			img1.width(img1.width() + 1);
			i = (i + 1) % c;
			tw++;
		}
		// if total width is slightly bigger than 
		// actual div width then subtract 1 from each 
		// photo width till they match
		i = 0;
		while( tw > w ) {
			var img2 = d_row.find("img:nth-child(" + (i + 1) + ")");
			img2.width(img2.width() - 1);
			i = (i + 1) % c;
			tw--;
		}
		
		// set row height to actual height + margins
		d_row.height(ht + border * 2);
	
		baseLine += c;
	}
	parent_container.find('.stupid_load').hide();
	parent_container.parents('div.removePadding').css('padding', '0px');
}