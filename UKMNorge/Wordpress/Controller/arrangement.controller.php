<?php

require_once('class/geoController.class.php');

class arrangementController extends geoController {
    public static function setView( $state ) {
        switch( $state ) {
            case 'arkiv':
                static::$view = 'Arkiv';
                break;
            case 'arrangement':
                static::$view = 'Arrangement/FrontArrangement';
                break;
            default:
                static::$view = 'Arrangement/FrontPamelding';
                break;
        }
    }

    public static function tillaterTittellose() {
        foreach( static::getArrangement()->getInnslagTyper() as $tillatt_type ) {
            if( $tillatt_type->harTitler() ) {
                continue;
            }
            return true;
        }
        return false;
    }
}

$arrangement = arrangementController::getArrangement();
// Init
arrangementController::setState( 'front' );
arrangementController::isActiveArkiv( $WP_TWIG_DATA );

if( new DateTime('now') > arrangementController::getPamelding()->starter && !$arrangement->erPameldingApen() ) {
    arrangementController::setState('arrangement');
}

$view_template = arrangementController::getView();
$WP_TWIG_DATA['sesong'] = arrangementController::getSesong();
$WP_TWIG_DATA['arrangement'] = arrangementController::getArrangement();
$WP_TWIG_DATA['arrangement_tillater_tittellose'] = arrangementController::tillaterTittellose();
$WP_TWIG_DATA['fylke'] = arrangementController::getArrangement()->getFylke();
$WP_TWIG_DATA['pamelding'] = arrangementController::getPamelding();