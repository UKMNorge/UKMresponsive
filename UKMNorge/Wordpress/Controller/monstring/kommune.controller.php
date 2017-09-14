<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');
require_once('_kommune.class.php');

// Init helper class
kommuneController::init( get_option('pl_id') );

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
}
// 2: Påmeldingen har ikke åpnet enda (dato for systemåpning)
elseif( !kommuneController::getPameldingApen() ) {
	kommuneController::setState('pre_pamelding');
}
// 3: Påmeldingen er ikke stengt (registrert dato by default eller user), (pre_registrering, pamelding)
elseif( kommuneController::getMonstring()->erPameldingApen() ) {
	kommuneController::setState('pamelding');
}
// 4: Mønstringen er ikke over, ergo er påmeldingen stengt (by default eller user)
elseif( !kommuneController::getMonstring()->erFerdig() ) {
	kommuneController::setState('lokalmonstring');
}
// 5: Mønstringen er over
else {
	kommuneController::setState('ferdig');
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