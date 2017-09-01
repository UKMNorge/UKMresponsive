<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');

$WP_TWIG_DATA['page_next'] = $WP_TWIG_DATA['posts']->getPageNext();
$WP_TWIG_DATA['page_prev'] = $WP_TWIG_DATA['posts']->getPagePrev();

	
fylkeFrontpageController::init( get_option('pl_id') );

$FYLKE = fylkeFrontpageController::getMonstring();
$LOKALT = fylkeFrontpageController::getLokalmonstringer();

// NÅR STARTER PÅMELDINGEN
$configDatePameldingStarter = str_replace('YYYY', $FYLKE->getSesong(), WP_CONFIG::get('pamelding')['starter'] );
$pameldingStarter = DateTime::createFromFormat( 'd.m.Y H:i:s', $configDatePameldingStarter .' 00:00:00' );
$now = new DateTime('now');
$omToUker = new DateTime('now + 2 weeks');

// SET STATE
// pagination is active
if( $WP_TWIG_DATA['posts']->getPaged() ) {
	fylkeFrontpageController::setState('arkiv');
}
// Fylkesmønstringen er ikke registrert
elseif( !$FYLKE->erRegistrert() ) {
	fylkeFrontpageController::setState('ikke_registrert');
}
// Påmeldingen har ikke åpnet
elseif( $pameldingStarter > $now ) {
	fylkeFrontpageController::setState('pre_pamelding');
}
// Fylkesmønstringen starter i løpet av 2 uker
elseif( $omToUker < $FYLKE->getStart() ) {	// DEV KROKODILLE FEIL VEI
	fylkeFrontpageController::setState('fylkesmonstring');
}
// Vi er i perioden mellom åpen påmelding og 2 uker før fylkesmønstring
else {
	$forste_monstring	= $pameldingStarter;
	$siste_monstring	= $pameldingStarter;
	$siste_pamelding 	= $pameldingStarter;
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
#fylkeFrontpageController::setState('ikke_registrert'); // DEV
#fylkeFrontpageController::setState('pre_pamelding'); // DEV
#fylkeFrontpageController::setState('pamelding'); // DEV
fylkeFrontpageController::setState('lokalmonstringer'); // DEV
#fylkeFrontpageController::setState('fylkesmonstring'); // DEV		TODO: NEXT STEP FIX FYLKESMØNSTRINGSSIDEN I ALLE TILSTANDER



$view_template 			= fylkeFrontpageController::getTemplate();
$WP_TWIG_DATA['fylke'] 	= $FYLKE;
$WP_TWIG_DATA['lokalt'] = $LOKALT;
$WP_TWIG_DATA['harFylkeInfo'] = fylkeFrontpageController::getHarFylkeInfo();


class fylkeFrontpageController {
	static $harFylkeInfo = false;
	static $pl_id = false;
	static $template = 'Fylke/front';
	static $state = 'ikke_registrert';
	static $monstring;
	
	
	public static function init( $pl_id ) {
		self::$pl_id = $pl_id;
		self::_loadMonstring();
	}
	
	public static function setState( $state ) {
		switch( $state ) {
			case 'arkiv':
				self::$template = 'Fylke/front_arkiv';
				break;
			case 'ikke_registrert':
				self::$template = 'Fylke/front_ikke_registrert';
				break;
			case 'pre_pamelding':
				self::$template = 'Fylke/front_pre_pamelding';
				break;
			case 'pamelding':
				self::$template = 'Fylke/front_pamelding';
				break;
			case 'lokalmonstringer':
				self::$template = 'Fylke/front_lokalmonstringer';
				self::_loadHarFylkeInfo();
				break;
		}
		self::$state = $state;
	}
	
	public static function getTemplate() {
		return self::$template;
	}
	
	public static function getMonstring() {
		return self::$monstring;
	}
	
	public static function getHarFylkeInfo() {
		return self::$harFylkeInfo;
	}
	
	public static function _loadHarFylkeInfo() {
		self::$harFylkeInfo = rand(0,1);		// TODO: KANSKJE IKKE RANDOM?
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