<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');
require_once('_kommune.class.php');

// Init helper class
kommuneController::init( get_option('pl_id') );

$MONSTRING = kommuneController::getMonstring();

/**
 * SET STATE
 * Switcher mellom fylkesmønstringens forskjellige states.
 * Forbereder hjelpeklassen slik at det alltid kan kjøres
 * get-funksjoner for å laste informasjon over i WP_TWIG_DATA
 *
 *
 * 1:	Hvis siden er paginert, vis kun arkivsiden
 * 2:	Påmeldingen har ikke startet, eller er åpen (mønstringen er / er ikke registrert)
 * 3:	Lokalmønstring
 * 5:	Mønstringen er over (helårig / tilbakeblikk)
**/
// 1: pagination is active
if( $WP_TWIG_DATA['posts']->getPaged() ) {
	kommuneController::setState('arkiv');
}
// 2: Påmeldingen er ikke stengt (pre_pamelding, pre_registrering, pamelding)
elseif( !$MONSTRING->erPameldingApen() ) {
	kommuneController::setState('pamelding');
}

/**
 * DEBUG OVERRIDES
 *
 * Brukes til å overstyre selectoren over, og vil alltid laste all relevant data
 *
**/
	// DEV SETTINGS FOR ALLE STATES I RIKTIG REKKEFØLGE
	kommuneController::setState('pre_pamelding'); // DEV



/**
 * OVERFØR DATA TIL $WP_TWIG_DATA
**/
$view_template 					= kommuneController::getTemplate();
$WP_TWIG_DATA['monstring'] 		= $MONSTRING;
$WP_TWIG_DATA['pamelding_apen'] = true;//kommuneController::getPameldingApen();
$WP_TWIG_DATA['harProgram'] 	= kommuneController::harProgram();

$WP_TWIG_DATA['infoPage'] 		= kommuneController::getInfoPage();
$WP_TWIG_DATA['harInfoPage'] 	= kommuneController::harInfoPage();

$WP_TWIG_DATA['page_next'] 		= $WP_TWIG_DATA['posts']->getPageNext();
$WP_TWIG_DATA['page_prev']		= $WP_TWIG_DATA['posts']->getPagePrev();