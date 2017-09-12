<?php
/** 
	STATIC HELPER CLASS FOR frontpage.controller.php
**/
class fylkeFrontpageController {
	static $pl_id = false;
	static $monstring;

	static $template = 'Fylke/front';
	static $state = 'pre_pamelding';

	static $pameldingApen = false;
	static $pameldingStarter = null;

	static $harFylkeInfo = null;
	static $fylkeInfo = null;
	
	static $harProgram = null;

	public static function init( $pl_id ) {
		self::$pl_id = $pl_id;
		self::_loadMonstring();
		self::setPameldingStarter();
	}
	
	public static function setState( $state ) {
		// Vil ikke overstyre state hvis vi er i arkiv-visning (har hentet inn nyheter side 2)
		if( self::$state == 'arkiv' ) {
			return false;
		}
		switch( $state ) {
			case 'arkiv':
				self::$template = 'Fylke/front_arkiv';
				break;
			case 'pre_pamelding':
				self::$template = 'Fylke/front_pre_pamelding';
				break;
			case 'pamelding':
				self::$pameldingApen = true;
			case 'lokalmonstringer':
				self::_loadFylkeInfo();
				self::$template = 'Fylke/front_lokalmonstringer';
				break;
			case 'fylkesmonstring':
				self::$template = 'Fylke/front_fylkesfestival';
				break;
		}
		self::$state = $state;
	}
	
	public static function setPameldingStarter() {
		$configDatePameldingStarter = str_replace('YYYY', self::getMonstring()->getSesong(), WP_CONFIG::get('pamelding')['starter'] );
		self::$pameldingStarter = DateTime::createFromFormat( 'd.m.Y H:i:s', $configDatePameldingStarter .' 00:00:00' );
	}
	public static function getPameldingStarter() {
		return self::$pameldingStarter;
	}
	
	public static function getTemplate() {
		return self::$template;
	}
	
	public static function getMonstring() {
		return self::$monstring;
	}
	
	public static function getPameldingApen() {
		return self::$pameldingApen;
	}
	
	public static function getFylkeInfo() {
		if( null === self::$harFylkeInfo ) {
			self::_loadFylkeInfo();
		}
		return self::$fylkeInfo;
	}
	public static function harFylkeInfo() {
		if( null === self::$harFylkeInfo ) {
			self::_loadFylkeInfo();
		}
		return self::$harFylkeInfo;
	}
	
	public static function harProgram() {
		self::_loadProgram();
		return self::$harProgram;
	}
	
	private static function _loadProgram() {
		if( null === self::$harProgram ) {
			self::$harProgram = self::getMonstring()->getProgram()->getAntall() > 0;
		}
		return self;
	}
	
	public static function _loadFylkeInfo() {
		$page = get_page_by_path('info');
		self::$fylkeInfo = new page( $page );
		self::$harFylkeInfo = ( is_object( $page ) && $page->post_status == 'publish' );
		return self;
	}
	
	private static function _loadMonstring() {
		self::$monstring = new monstring_v2( self::$pl_id );
	}

	public static function getLokalmonstringer() {
		$monstringer = new monstringer_v2( self::getMonstring()->getSesong() );
		return $monstringer->getAllByFylke( self::getMonstring()->getFylke()->getId() );
	}
}