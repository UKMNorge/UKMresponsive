<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');

	
fylkeFrontpageController::init( get_option('pl_id') );
	
$view_template 			= fylkeFrontpageController::getTemplate();
$WP_TWIG_DATA['fylke'] 	= fylkeFrontpageController::getMonstring();
$WP_TWIG_DATA['lokalt'] = fylkeFrontpageController::getLokalmonstringer();


class fylkeFrontpageController {
	static $pl_id = false;
	static $template = 'Fylke/front';
	static $state = 'pre';
	static $monstring;
	
	
	public static function init( $pl_id ) {
		self::$pl_id = $pl_id;
		self::_loadMonstring();
	}
	
	public static function getTemplate() {
		return self::$template;
	}
	
	public static function getMonstring() {
		return self::$monstring;
	}
	private static function _loadMonstring() {
		self::$monstring = new monstring_v2( self::$pl_id );
	}
	
	public static function getLokalmonstringer() {
		$monstringer = new monstringer_v2( self::getMonstring()->getSesong() );
		return $monstringer->getAllByFylke( self::getMonstring()->getFylke()->getId() );
	}
}