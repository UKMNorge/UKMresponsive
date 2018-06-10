var UKMKonkurranse = function( $ ) {
	
	/**
	 * EVENT FACTORY ? 
	**/
	var events = function( _navn ) {
		var _events = [];
		
		var navn = (_navn !== undefined && _navn !== null) ? _navn.toUpperCase() : 'UKJENT';
		
		var self = {
			/* EVENT HANDLERS */
			emit: function( event, data ) {
				//console.info( navn + '::emit('+event+')', data);
				if( _events[event] != null ) {
					_events[event].forEach( function( _event ) {
						_event.call(null, data );
					});
				}
				return self;
			},
			
			on: function( event, callback ) {
				if( _events[event] == null ) {
					_events[ event ] = [callback];
					return;
				}
				_events[ event ].push( callback );
				return self;
			}
		};
		
		return self;
	}('UKMkonkurranse');

	/**
	 * KONKURRANSE
	**/
	var self = {
		
		har: function( _sporsmal ) {
			self.ajax( 'har', _sporsmal );
		},
		
		get: function( _sporsmal ) {
			self.ajax( 'get', _sporsmal );
		},
		
		svar: function( _sporsmal, _svar ) {
			self.ajax(
				'svar',
				_sporsmal,
				{
					svar: _svar,
				}
			);
		},

		ajax: function( _action, _sporsmal, _param_data ) {
			// HENT MOBILNUMMER
			var mobil = UKMMobil.get();
			if( !mobil ) {
				mobil = prompt('Hva er mobilnummeret ditt?');
				mobil = UKMMobil.set( mobil );
			}
			
			// SETT DATA
			var data = {
				action: 'UKMkonkurranse',
				konkurranse: _action,
				sporsmal: _sporsmal,
				mobil: mobil
			};
			
			// LEGG TIL PARAMETER DATA
			if( null !== _param_data && undefined !== _param_data ) {
				for (var attrname in _param_data) {
					data[ attrname ] = _param_data[ attrname ];
				}
			}

			// KJØR POST
			jQuery.post(
				ajaxurl,
				data,
				self.handleResponse,
				'json'
			);
		},
		
		handleResponse: function( response ) {
			if( response.success == true ) {
				self.emit( response.sporsmal +':'+ response.action, response );
			} else {
				//alert('Beklager, klarte ikke å hente konkurranse-status');
			}
		},
		
		/****************************************/
		/** EVENT HANDLERS						*/
		/****************************************/
		on: function( event, callback ) {
			return events.on( event, callback );
		},
		emit: function( event, data ) {
			return events.emit( event, data );
		},
	};
	
	return self;
}( jQuery );