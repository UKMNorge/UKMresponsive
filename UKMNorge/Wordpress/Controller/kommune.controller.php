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

$kommune_id = get_option('pl_owner_id');
if( !$kommune_id ) {
    $kommune_id = explode(',', get_option('kommuner'));
    if( is_array( $kommune_id ) ) {
        $kommune_id = $kommune_id[0];
    }
}
if( !$kommune_id ) {
    throw new Exception('Klarte ikke å finne kommune-ID');
}
$kommune_id = (Int) $kommune_id;

$omrade = Omrade::getByKommune( $kommune_id );

if( $omrade->getArrangementer( geoController::getSesong() )->getAntall() == 1 ) {
    $arrangement = $omrade->getArrangementer( geoController::getSesong() )->getFirst();
    if( $arrangement->erFellesmonstring() ) {
        header("Location: ". $arrangement->getLink());
        echo '<script type="text/javascript">window.location.href = "'. $arrangement->getLink() .'";</script>';
        exit();
    }
}
// her har kommunen alltid 1 id, da flere ID'er kun er mulig i arrangement,
// som nå blir håndtert av arrangement.controller.php
kommuneController::setOmrade( $omrade );
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