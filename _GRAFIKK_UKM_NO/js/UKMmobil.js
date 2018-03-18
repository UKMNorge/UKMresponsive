var UKMMobil = function( $ ) {
	var self = {
		
		har: function(){
			//console.log('UKMMOBIL:har()');
			return self.get() != null;
		},
		
		get: function() {
			//console.log('UKMMOBIL:get()');

			var saved = Cookies.get('UKMMobil');
			if( null != saved && undefined != saved && false != saved ) {
				return saved;
			}
			return null;
		},
		
		set: function( mobil ) {
			if( typeof mobil != 'string' ) {
				return false;
			}
			
			//console.log('UKMMOBIL:set( '+ mobil +' )');
			mobil = mobil.replace(/\D/g,''); // Replace all but digits
			
			if( mobil.length == 8 ) {
				Cookies.set(
					'UKMMobil',
					mobil, 
					self.getCookieConfig()
				);
				self.showGUI();
				return mobil;
			}
			return false;
		},
		
		remove: function() {
			Cookies.remove(
				'UKMMobil',
				self.getCookieConfig()
			);
		},
		
		getCookieConfig: function(){
			if( UKM_HOSTNAME != undefined && UKM_HOSTNAME != null && UKM_HOSTNAME == 'ukm.dev' ) {
				return {
					expires: 365,
					domain: 'ukm.dev',
					secure: false
				}
			}
			return {
				expires: 365,
				domain: 'ukm.no',
				secure: true
			}
		},
		
		ready: function(){
			if( self.har() ) {
				self.showGUI();
			}
			$(document).on('click', '#UKMMobil', self.showEdit );
			$(document).on('click', '#UKMMobil-hide', self.hideEdit );
			$(document).on('click', '#UKMMobil-glem', self.glem );
			$(document).on('click', '#UKMMobil-edit-button', self.edit );
		},
		
		showGUI: function( e ){
			$('#UKMMobil').html(
				self.get()
				+ ' <span class="icon icon-users"></span>'
			);
			$('#UKMMobil').fadeIn( 250 );
		},
		hideGUI: function(){
			$('#UKMMobil').fadeOut( 250 );
		},
		
		showEdit: function( e ) {
			$('#UKMMobil-edit').slideDown(250);
		},
		
		hideEdit: function( e ) {
			$('#UKMMobil-edit').slideUp(250);
		},
		
		glem: function( e ) {
			self.remove();
			self.hideEdit();
			$('#UKMMobil').hide();
		},
		
		edit: function( e ) {
			mobil = prompt('Hva er riktig mobilnummer?');
			self.set( mobil );
			self.hideEdit();
		},
	};
	
	return self;
}( jQuery );

jQuery(document).ready(function(){
	UKMMobil.ready();
});