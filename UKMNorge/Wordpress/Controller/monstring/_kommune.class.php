<?php
	
require_once('_monstring.class.php');
/** 
 *	STATIC HELPER CLASS FOR frontpage.controller.php
**/
class kommuneController extends monstringController {
	
	public static function setState( $state ) {
		// Vil ikke overstyre state hvis vi er i arkiv-visning (har hentet inn nyheter side 2)
		if( self::$state == 'arkiv' ) {
			return false;
		}
		switch( $state ) {
            case 'uten_arrangement':
                self::$template = 'Kommune/front_uten_arrangement';
                break;
			case 'arkiv':
				self::$template = 'Kommune/front_arkiv';
				break;
			case 'lokalmonstring':
			case 'pre_pamelding':
			case 'pamelding':
				self::$template = 'Kommune/front_sesong';
				break;
			case 'ferdig':
				self::$template = 'Kommune/front_utenfor_sesong';
				break;
		}
		self::$state = $state;
	}
	
	public static function getFavoritt() {
		if( isset( $_COOKIE['UKMfavoritt'] ) && $_COOKIE['UKMfavoritt'] == self::getMonstring()->getId() ) {
			return $_COOKIE['UKMfavoritt'];
		}
		return false;
	}
	
	public static function harFavoritt() {
		return self::getFavoritt() ? true : false;
	}
}