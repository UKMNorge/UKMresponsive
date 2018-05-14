var UKMfilter = function( group, id ){
	var name;
	var selector = '#'+ group + '-' + id;
	var listSelector = '.filter-'+ group +'-'+ id;
	
	var self = {
		init: function() {
			name = $( selector ).html();
			$( listSelector ).attr('filter-'+ group +'-show', self.isVisible());
		},
		
		getGroup: function(){
			return group;
		},
		getId: function(){
			return id;
		},
		getSelector: function(){
			return selector;
		},
		
		getListSelector: function(){
			return listSelector;
		},
		
		getName: function(){
			return name;
		},
		
		isVisible: function() {
			return $( selector ).attr('data-show') == 'true';
		},
		
		show: function(){
			$( selector ).attr('data-show', true);
			$( selector ).addClass('btn-primary').removeClass('btn-outline-primary');
		},
		hide: function(){
			$( selector ).attr('data-show', false);
			$( selector ).addClass('btn-outline-primary').removeClass('btn-primary');
			$( listSelector ).addClass('d-none');
		},
		
		click: function(){
			//console.warn('Don\'t you dare do that again!');	
			if( self.isVisible() ) {
				self.hide();
				return 'hide';
			} else {
				self.show();
				return 'show';
			}
		},
	};
	
	return self;
}

var UKMpameldte = function( filterList ){
	var groups = new Map( null );

	var self = {
		registergroup: function( group ) {
			groups.set( group, new Map( null ) );
			var keys = Array.from( groups.keys() );
			var groupList = keys.join('|');
			$('.filter').each( function(){
				$(this).attr('data-filters', groupList);
			});
		},
		
		registerFilter: function( group, filterId) {
			if( self.hasGroup( group ) ) {
				var filter = new UKMfilter( group, filterId );
				filter.init();
				groups.get( group ).set( filterId, filter );
			}
		},
		
		click: function( group, filterId ) {
			if( self.hasFilter( group, filterId ) ) {
				var filter = groups.get( group ).get( filterId );
				var state = filter.click();
				
				if( state == 'hide' ) {
					// HVIS INGEN FILTER ER VALGT, SKAL ALLE ELEMENTER VISES
					var noneSelected = true;
					groups.get( group ).forEach( function( filter ){
						if( filter.isVisible() ) {
							noneSelected = false;
							return;
						}
					});
					
					if( noneSelected ) {
						groups.get( group ).forEach( function( filter ){
							$( filter.getListSelector() ).each( function(){
								$(this).attr('filter-'+ filter.getGroup() +'-show', true);
							});
						});
						filter = null;
					}
				} else {
					// HVIS FØRSTE FILTER ER VALGT MÅ VI KONTROLLERE STATUS PÅ NYTT
					// PÅ GRUNN AV "INGEN FILTER VALGT"-FUNKSJONEN
					var numSelected = 0;
					groups.get( group ).forEach( function( filter ){
						if( filter.isVisible() ) {
							numSelected++;
							if( numSelected > 2 ) {
								return;
							}
						}
					});

					if( numSelected == 1 ) {
						groups.get( group ).forEach( function( _filter ){
							$( _filter.getListSelector() ).each( function(){
								$(this).attr('filter-'+ _filter.getGroup() +'-show', false);
								$(this).addClass('d-none');
							});
						});
						filter.show();
					}
					
				}
				self.updateList( filter );
			} else {
				
			}
		},
		
		hasGroup: function( group ) {
			if( groups.has( group ) ) {
				return true;
			}
			//console.warn('Sorry, group #'+ group +' does not exist');
			return false;
		},
		
		hasFilter: function( group, filterId ) {
			if( self.hasGroup( group ) ) {
				if( groups.get( group ).has( filterId ) ) {
					return true;
				}
				//console.warn('Sorry, group #'+ group +' does not have the filter #'+ filterId );
			}
		},
		
		updateList: function( filter ){
			if( filter == null ) {
				var filterAffectedElements = filterList +' .filter';
			} else {
				var filterAffectedElements = filterList +' '+ filter.getListSelector();
			}
			$( filterAffectedElements ).each( function(){
				var listElement = $(this);
				var filters = listElement.attr('data-filters').split('|');
				var foundHideFilter = false;

				if( filter != null ) {
					$( this ).attr('filter-'+ filter.getGroup() +'-show', filter.isVisible());
				}
				
				filters.forEach( function( filter ) {
					var filterStatus = 'filter-'+ filter +'-show';
					if( listElement.attr( filterStatus ) == 'false' ) {
						foundHideFilter = true;
					}
				});
				if( !foundHideFilter ) {
					listElement.removeClass('d-none');
				}
			});
		},
		
		
	};
	
	return self;
}('#searchList');


/**
 * REGISTER GUI HOOKS
**/
$(document).on('click', '.filterKategori', function(){
	UKMpameldte.click( 'kategori', $(this).attr('data-id') );
});
$(document).on('click', '.filterFylke', function(){
	UKMpameldte.click( 'fylke', $(this).attr('data-id') );
});



/**
 * LOAD FILTERS AND FILTER GROUPS
**/
$(document).ready( function(){
	UKMpameldte.registergroup('kategori');

	$('.filterKategori').each( function(){
		UKMpameldte.registerFilter('kategori', $(this).attr('data-id'));
	});

	UKMpameldte.registergroup('fylke');
	$('.filterFylke').each( function(){
		UKMpameldte.registerFilter('fylke', $(this).attr('data-id'));
	});

});