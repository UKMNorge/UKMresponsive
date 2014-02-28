jQuery(document).ready(function(){
	processPhotos( photos );
});
function processPhotos(photos){
	// divs to contain the images
//	var d = $("div.picrow");
	
	var d = jQuery('div.album_container');
	
	// get row width - this is fixed.
	var w = d.eq(0).innerWidth();
	
	// initial height - effectively the maximum height +/- 10%;
	var h = 320;
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
								   'width': wt,
								   'height': ht,
								   'data-link': photo.link}).css("margin", border + "px");
			d_row.append(img);
			
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
	jQuery(document).on('click', '.album_image', function(e){
		e.preventDefault();
		window.location.href = jQuery(this).attr('data-link');
	});
}