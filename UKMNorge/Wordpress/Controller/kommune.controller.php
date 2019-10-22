<?php

use UKMNorge\Nettverk\Omrade;
use UKMNorge\Samtykke\Kommunikasjon;

require_once('class/geoController.class.php');

class kommuneController extends geoController {
    public static function setView( $state ) {
        switch( $state ) {
            case 'arkiv':
                static::$view = '';
                break;
            case 'uten_arrangement':
                static::$view = 'Kommune/FrontUtenArrangement';
                break;
            case 'med_arrangementer':
                // Med flere arrangement. Med ett arrangement
                // håndteres av arrangement.controller.php
                static::$view = 'Kommune/FrontMedArrangement';
                break;
        }
    }
}

// INIT
// her har kommunen alltid 1 id, da flere ID'er kun er mulig i arrangement,
// som nå blir håndtert av arrangement.controller.php
kommuneController::setOmrade( Omrade::getByKommune( get_option('kommuner') ) );
kommuneController::isActiveArkiv( $WP_TWIG_DATA );

// Manuelt set init-state
kommuneController::setState('uten_arrangement');
// Har kommunen flere arrangementer, er det egen state
if( kommuneController::getOmrade()->getArrangementer( kommuneController::getSesong() )->har() ) {
    kommuneController::setState('med_arrangementer');
}
#kommuneController::setState('pamelding');

$view_template = kommuneController::getView();
$WP_TWIG_DATA['omrade'] = geoController::getOmrade();
$WP_TWIG_DATA['sesong'] = geoController::getSesong();
$WP_TWIG_DATA['pamelding'] = geoController::getPamelding();