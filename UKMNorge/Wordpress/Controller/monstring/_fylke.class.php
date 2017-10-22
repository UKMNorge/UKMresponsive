<?php
	
require_once('_monstring.class.php');
/** 
	STATIC HELPER CLASS FOR frontpage.controller.php
**/
class fylkeController extends monstringController {
		
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
				self::_loadInfoPage();
				self::$template = 'Fylke/front_lokalmonstringer';
				break;
			case 'fylkesmonstring':
				self::$template = 'Fylke/front_fylkesfestival';
				break;
		}
		self::$state = $state;
	}

	public static function getLokalmonstringer() {
		$monstringer = new monstringer_v2( self::getMonstring()->getSesong() );
		return $monstringer->utenGjester( $monstringer->getAllByFylke( self::getMonstring()->getFylke()->getId() ) );
	}
}