<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');

$WP_TWIG_DATA['page_next'] = $WP_TWIG_DATA['posts']->getPageNext();
$WP_TWIG_DATA['page_prev'] = $WP_TWIG_DATA['posts']->getPagePrev();


fylkeFrontpageController::init( get_option('pl_id') );
$FYLKE = fylkeFrontpageController::getMonstring();
$LOKALT = fylkeFrontpageController::getLokalmonstringer();

// NÅR STARTER PÅMELDINGEN
fylkeFrontpageController::setPameldingStarter( $FYLKE );
$now = new DateTime('now');
$omToUker = new DateTime('now + 2 weeks');

// SET STATE
// pagination is active
if( $WP_TWIG_DATA['posts']->getPaged() ) {
	fylkeFrontpageController::setState('arkiv');
}
// Påmeldingen har ikke åpnet
elseif( $now < fylkeFrontpageController::getPameldingStarter() ) {
	fylkeFrontpageController::setState('pre_pamelding');
}
// Fylkesmønstringen starter i løpet av 2 uker
elseif( $omToUker > $FYLKE->getStart() ) {
	fylkeFrontpageController::setState('fylkesmonstring');
}
// Vi er i perioden mellom åpen påmelding og 2 uker før fylkesmønstring
else {
	$forste_monstring	= fylkeFrontpageController::getPameldingStarter();
	$siste_monstring	= fylkeFrontpageController::getPameldingStarter();
	$siste_pamelding 	= fylkeFrontpageController::getPameldingStarter();
	// Loop alle lokalmønstringer i fylket
	foreach( $LOKALT as $lokalmonstring ) {
		if( $lokalmonstring->getStart() < $forste_monstring ) {
			$forste_monstring = $lokalmonstring->getStart();
		}
		if( $lokalmonstring->getStop() > $siste_monstring ) {
			$siste_monstring = $lokalmonstring->getStop();
		}
		if( $lokalmonstring->getFrist1() > $siste_pamelding ) {
			$siste_pamelding = $lokalmonstring->getFrist1();
		}
		if( $lokalmonstring->getFrist2() > $siste_pamelding ) {
			$siste_pamelding = $lokalmonstring->getFrist2();
		}
	}
	// Påmeldingen er åpen
	if( true || $now < $siste_pamelding ) {
		fylkeFrontpageController::setState('pamelding');
	}
	// Påmeldingen er lukket - fokus på lokalmønstringer (publikum)
	 else {
		fylkeFrontpageController::setState('lokalmonstringer');
	}
	$WP_TWIG_DATA['lokalt_start'] = $forste_monstring;
	$WP_TWIG_DATA['lokalt_stopp'] = $siste_monstring;
	$WP_TWIG_DATA['lokalt_siste_pamelding'] = $siste_pamelding;
}

// DEV SETTINGS FOR ALLE STATES I RIKTIG REKKEFØLGE
#fylkeFrontpageController::setState('pre_pamelding'); // DEV
#fylkeFrontpageController::setState('pamelding'); // DEV
fylkeFrontpageController::setState('lokalmonstringer'); // DEV
#fylkeFrontpageController::setState('fylkesmonstring'); // DEV



$view_template 			= fylkeFrontpageController::getTemplate();
$WP_TWIG_DATA['fylke'] 	= $FYLKE;
$WP_TWIG_DATA['lokalt'] = $LOKALT;

$WP_TWIG_DATA['pamelding_apen'] = fylkeFrontpageController::getPameldingApen();
$WP_TWIG_DATA['fylkeInfo'] = fylkeFrontpageController::getFylkeInfo();

$WP_TWIG_DATA['harFylkeInfo'] = fylkeFrontpageController::harFylkeInfo();
$WP_TWIG_DATA['harProgram'] = fylkeFrontpageController::harProgram();

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
	
	public static function setPameldingStarter( $fylke ) {
		$configDatePameldingStarter = str_replace('YYYY', $fylke->getSesong(), WP_CONFIG::get('pamelding')['starter'] );
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