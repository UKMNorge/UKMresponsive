<?php
	
use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');
require_once('_fylke.class.php');

// Init helper class
fylkeController::init( get_option('pl_id') );

// NÅR STARTER PÅMELDINGEN
$now = new DateTime('now');
$omToUker = new DateTime('now + 3 weeks');

/**
 * SET STATE
 * Switcher mellom fylkesmønstringens forskjellige states.
 * Forbereder hjelpeklassen slik at det alltid kan kjøres
 * get-funksjoner for å laste informasjon over i WP_TWIG_DATA
 *
 *
 * 1:	Hvis siden er paginert, vis kun arkivsiden
 * 2:	Påmeldingen har ikke startet
 * 3:	Det er mindre enn 2 uker til fylkesmønstringen, eller
 *   	fylkesmønstringen er over
 * 4:	Vi er mellom ny sesong og fylkesmønstring (høst/vinter)
 * 4.1:	Påmeldingen er åpen (siste frist har ikke passert)
 * 4.2: Påmeldingen er over, men det er mer enn 2 uker til fylkesmønstring (se pkt 3)
 *		vis derfor info om lokalmønstringer 
**/
// 1: pagination is active
if( $WP_TWIG_DATA['posts']->getPaged() ) {
	fylkeController::setState('arkiv');
	SEO::setTitle( 'Nyheter fra '. fylkeController::getMonstring()->getNavn() );
	SEO::setDescription( 
		'Her finner du alle nyheter fra '. fylkeController::getMonstring()->getNavn()
	);
}
// 2: Påmeldingen har ikke åpnet
elseif( !fylkeController::erPameldingStartet() ) {
	fylkeController::setState('pre_pamelding');
	SEO::setTitle( fylkeController::getMonstring()->getNavn() );
	SEO::setDescription( 
		'Hold deg oppdatert på hva som skjer med '. fylkeController::getMonstring()->getNavn()
	);
}
// 3: Fylkesmønstringen starter i løpet av 2 uker
elseif( $omToUker > fylkeController::getMonstring()->getStart() && fylkeController::getMonstring()->erRegistrert() ) {
	fylkeController::setState('fylkesmonstring');
	SEO::setTitle( fylkeController::getMonstring()->getNavn() );
	SEO::setDescription( 
		'Straks klar for fylkesfestival! Vi starter '. fylkeController::getMonstring()->getStart()->format('j. M \k\l. H:i')
	);
}
// 4: Vi er i perioden mellom åpen påmelding og 2 uker før fylkesmønstring
else {
	$forste_monstring	= fylkeController::getPameldingStarter();
	$siste_monstring	= fylkeController::getPameldingStarter();
	$siste_pamelding 	= fylkeController::getPameldingStarter();
	// Loop alle lokalmønstringer i fylket
	foreach( fylkeController::getLokalmonstringer() as $lokalmonstring ) {
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
	// 4.1: Påmeldingen er åpen
	if( $now < $siste_pamelding ) {
		fylkeController::setState('pamelding');
		SEO::setTitle( 'Påmeldingen er åpen!' );
		SEO::setDescription( 'Meld deg på UKM i '. fylkeController::getMonstring()->getFylke()	);
	}
	// 4.2: Påmeldingen er lukket - fokus på lokalmønstringer (publikum)
	 else {
		fylkeController::setState('lokalmonstringer');
		SEO::setTitle( 'Lokalmønstringene er i gang!' );
		SEO::setDescription( 'Les mer om din lokalmønstring i '. fylkeController::getMonstring()->getFylke() .' her');
	}
	$WP_TWIG_DATA['lokalt_start'] = $forste_monstring;
	$WP_TWIG_DATA['lokalt_stopp'] = $siste_monstring;
	$WP_TWIG_DATA['lokalt_siste_pamelding'] = $siste_pamelding;
}

/**
 * DEBUG OVERRIDES
 *
 * Brukes til å overstyre selectoren over, og vil alltid laste all relevant data
 *
**/
	// DEV SETTINGS FOR ALLE STATES I RIKTIG REKKEFØLGE
	#fylkeController::setState('pre_pamelding'); // DEV
	#fylkeController::setState('pamelding'); // DEV
	#fylkeController::setState('lokalmonstringer'); // DEV
	#fylkeController::setState('fylkesmonstring'); // DEV



/**
 * OVERFØR DATA TIL $WP_TWIG_DATA
**/
$view_template 					= fylkeController::getTemplate();
$WP_TWIG_DATA['fylke']		 	= fylkeController::getMonstring();
$WP_TWIG_DATA['lokalt'] 		= fylkeController::getLokalmonstringer();

$WP_TWIG_DATA['pamelding_apen'] = fylkeController::getPameldingApen();
$WP_TWIG_DATA['infoPage'] 		= fylkeController::getInfoPage();

$WP_TWIG_DATA['harInfoPage'] 	= fylkeController::harInfoPage();
$WP_TWIG_DATA['harProgram'] 	= fylkeController::harProgram();

$WP_TWIG_DATA['direkte']		= fylkeController::getLive();

$WP_TWIG_DATA['page_next'] 		= $WP_TWIG_DATA['posts']->getPageNext();
$WP_TWIG_DATA['page_prev']		= $WP_TWIG_DATA['posts']->getPagePrev();

if( get_option('UKM_banner_image') ) {
	$WP_TWIG_DATA['HEADER']->background->url = get_option('UKM_banner_image');
	$image = new SEOImage( str_replace('http:','https:', get_option('UKM_banner_image') ) );
	SEO::setImage( $image );
}

$meny = wp_get_nav_menu_object( get_option('UKM_menu') );	
$WP_TWIG_DATA['meny'] = wp_get_nav_menu_items( $meny );