<?php
	
use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');
require_once('_kommune.class.php');

// Init helper class
kommuneController::init( get_option('pl_id') );

/**
 * Test at lokalmønstringen har kommuner
 * Hvis ikke er det en feil, og mønstringen bør være avlyst.
 * Oppdater denne settingen, og videresend brukeren til monstring-not-found.php
 * som vil hjelpe deltakeren tilbake på rett spor.
 */
try {
	$monstring = kommuneController::getMonstring();
	$ant_kommuner = $monstring->getKommuner()->getAntall();
} catch( Exception $e ) {}

if( 0 == $ant_kommuner ) {
	$test = get_option('status_monstring');
	if( !$test ) {
		update_option('status_monstring', 'avlyst');
		update_option('debug_status_monstring', 'setByKommuneController');
	}
	header('Location: '.$_SERVER['REQUEST_URI']);
}

/**
 * SET STATE
 * Switcher mellom lokalmønstringens forskjellige states.
 * Forbereder hjelpeklassen slik at det alltid kan kjøres
 * get-funksjoner for å laste informasjon over i WP_TWIG_DATA
 *
 *
 * 1:	Hvis siden er paginert, vis kun arkivsiden
 * 2:	Påmeldingen har ikke åpnet enda (1.okt eller hva det er)
 * 3:	Påmeldingen er åpen helt eller delvis (frist1/frist2/begge)
 * 4:	Fokus på mønstringen. Påmeldingen er stengt
 * 5:	Mønstringen er over (helårig / tilbakeblikk, nyheter i fokus)
**/
// 1: pagination is active
if( $WP_TWIG_DATA['posts']->getPaged() ) {
	kommuneController::setState('arkiv');
	SEO::setTitle( 'Nyheter fra UKM '. kommuneController::getMonstring()->getNavn() );
	SEO::setDescription( 
		'Her finner du alle nyheter fra '. kommuneController::getMonstring()->getNavn()
	);
}
// 2: Påmeldingen har ikke åpnet enda (dato for systemåpning)
elseif( !kommuneController::erPameldingStartet() ) {
	kommuneController::setState('pre_pamelding');
	SEO::setTitle( 'UKM '. kommuneController::getMonstring()->getNavn() );
	SEO::setDescription( 'Hold deg oppdatert på hva som skjer med '. kommuneController::getMonstring()->getNavn() );
}
// 3: Påmeldingen er ikke stengt (registrert dato by default eller user), (pre_registrering, pamelding)
elseif( kommuneController::getMonstring()->erPameldingApen() ) {
	kommuneController::setState('pamelding');
	SEO::setTitle( 'Påmeldingen er åpen!' );
	SEO::setDescription( 'Meld deg på UKM '. kommuneController::getMonstring()->getNavn() );
}
// 4: Mønstringen er ikke over, ergo er påmeldingen stengt (by default eller user)
elseif( !kommuneController::getMonstring()->erFerdig() ) {
	kommuneController::setState('lokalmonstring');
	SEO::setTitle( 'Vi er i gang!' );
	SEO::setDescription( 'Alt om UKM '. kommuneController::getMonstring()->getNavn() );
}
// 5: Mønstringen er over
else {
	kommuneController::setState('ferdig');
	SEO::setTitle( 'Alt om UKM '. kommuneController::getMonstring()->getNavn() );
	SEO::setDescription( 'Les mer om UKM '. kommuneController::getMonstring()->getNavn() );
}

/**
 * DEBUG OVERRIDES
 *
 * Brukes til å overstyre selectoren over, og vil alltid laste all relevant data
 *
**/
	// DEV SETTINGS FOR ALLE STATES I RIKTIG REKKEFØLGE
#	kommuneController::setState('pre_pamelding'); // DEV
#	kommuneController::setState('pamelding'); // DEV
#	kommuneController::setState('lokalmonstring'); // DEV
#	kommuneController::setState('ferdig'); // DEV


/**
 * OVERFØR DATA TIL $WP_TWIG_DATA
**/
$view_template 						= kommuneController::getTemplate();
$WP_TWIG_DATA['state']				= kommuneController::getState();
$WP_TWIG_DATA['monstring'] 			= kommuneController::getMonstring();
$WP_TWIG_DATA['harProgram']	 		= kommuneController::harProgram();

$WP_TWIG_DATA['infoPage']	 		= kommuneController::getInfoPage();
$WP_TWIG_DATA['harInfoPage']	 	= kommuneController::harInfoPage();

$WP_TWIG_DATA['page_next'] 			= $WP_TWIG_DATA['posts']->getPageNext();
$WP_TWIG_DATA['page_prev']			= $WP_TWIG_DATA['posts']->getPagePrev();

$WP_TWIG_DATA['favoritt']			= kommuneController::getFavoritt();
$WP_TWIG_DATA['harFavoritt']		= kommuneController::harFavoritt();

$WP_TWIG_DATA['direkte']			= kommuneController::getLive();
$WP_TWIG_DATA['ukmtv']				= kommuneController::getUKMTV();

require_once(PATH_WORDPRESSBUNDLE. 'Controller/banner.controller.php');
require_once(PATH_WORDPRESSBUNDLE. 'Controller/monstring/meny.controller.php');