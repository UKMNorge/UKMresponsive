<?php
	
require_once('_monstring.class.php');
/** 
	STATIC HELPER CLASS FOR frontpage.controller.php
**/
class fylkeController extends monstringController {
	static $harFylkeInfo = null;
	static $fylkeInfo = null;
	
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

	public static function _loadFylkeInfo() {
		$page = get_page_by_path('info');
		self::$fylkeInfo = new page( $page );
		self::$harFylkeInfo = ( is_object( $page ) && $page->post_status == 'publish' );
		return self;
	}

	public static function getLokalmonstringer() {
		$monstringer = new monstringer_v2( self::getMonstring()->getSesong() );
		return $monstringer->getAllByFylke( self::getMonstring()->getFylke()->getId() );
	}
}