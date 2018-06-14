<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/person.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/kategori.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/foresatt.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/sms_meldinger.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/sms.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/status.class.php');
require_once(UKMSAMTYKKE_PLUGIN_PATH . 'models/samtykke/timestamp.class.php');


try {
	if( !isset( $_GET['id'] ) ) {
		$WP_TWIG_DATA['skjul_skjema'] = true;
	} else {
		$data = explode('-', $_GET['id']);
		$mobil = $data[0];
		$id = $data[1];
	
		if( !is_numeric( $mobil ) || !is_numeric( $id ) ) {
			throw new Exception('Mangler numerisk mobil-nummer og ID-felt');
		}
		
		$samtykke = samtykke_person::getById( $id );
		if( $samtykke->getMobil() != $mobil ) {
			throw new Exception('Feil i ID-felt.');
		}
		
		$KREV_FORESATT = in_array( $samtykke->getKategori()->getId(), ['u13','u15'] );
		$ER_FORESATT = isset( $_GET['foresatt'] );
		
		if( isset( $_GET['feedback'] ) ) {
			$feedback = $_GET['feedback'];
		} elseif( isset( $_POST['feedback'] ) ) {
			$feedback = $_POST['feedback'];
		} else {
			$feedback = false;
		}
		
		// VI HAR FÅTT INN ET SVAR
		if( $feedback ) {
			/**
			 * FIKK FORESATT-INFO SAMMEN MED FEEDBACK
			 *
			 * Dvs at deltakeren er under 15,
			 * og vi må informere foresatte
			**/
	
			if( isset( $_POST['foresatt_navn'] ) ) {
				$_POST['foresatt_mobil'] = preg_replace('/[^0-9]/', '', $_POST['foresatt_mobil']);
				$samtykke->setForesatt( utf8_encode($_POST['foresatt_navn']), $_POST['foresatt_mobil'] );
				$samtykke->persist();
			}
			/**
			 * VI HAR FÅTT EN GODKJENNING
			**/
			if( $feedback == 'go' ) {
				// Foresatt: it's a GO!
				if( $ER_FORESATT ) {
					$samtykke->setForesattStatus('godkjent', $_SERVER['HTTP_CF_CONNECTING_IP']);
				}
				// Deltaker: take pictures of me!
				else {
					$samtykke->setStatus('godkjent', $_SERVER['HTTP_CF_CONNECTING_IP']);
				}
				$view_template = 'Samtykke/go';
			}
			/**
			 * VI HAR FÅTT EN NO-GO :(
			**/
			else {
				// No-go fra foresatte
				if( $ER_FORESATT ) {
					$samtykke->setForesattStatus('ikke_godkjent', $_SERVER['HTTP_CF_CONNECTING_IP']);
				}
				// No-go fra deltakeren
				else {
					$samtykke->setStatus('ikke_godkjent', $_SERVER['HTTP_CF_CONNECTING_IP']);
				}
				$view_template = 'Samtykke/nogo';
			}
			$samtykke->persist();
			
			/**
			 * FIKK FORESATT-INFO SAMMEN MED FEEDBACK
			 *
			 * Faktisk informer den foresatte.
			 * Må gjøres etter lagret samtykke, slik at vi kan gi foresatte
			 * informasjon om hva deltakeren ønsker.
			**/
			if( isset( $_POST['foresatt_navn'] ) ) {
				// SEND SMS TIL FORESATT
				$samtykke = samtykke_person::getById( $id );
				samtykke_sms::send('samtykke_foresatt', $samtykke );
			}
		}
		/**
		 * Hvis deltakeren ikke har sett siden før, marker den som lest nå
		**/
		elseif( !$ER_FORESATT && $samtykke->getStatus()->getId() == 'ikke_sett' ) {
			$samtykke->setStatus('ikke_svart', $_SERVER['HTTP_CF_CONNECTING_IP']);
			$samtykke->persist();
		}
		/**
		 * Hvis den foresatte ikke har sett siden før, marker den som lest nå
		**/
		elseif( $ER_FORESATT && $samtykke->getForesatt()->getStatus()->getId() == 'ikke_sett' ) {
			$samtykke->setForesattStatus('ikke_svart', $_SERVER['HTTP_CF_CONNECTING_IP']);
			$samtykke->persist();
		}	
		
		
		$WP_TWIG_DATA['samtykke'] = $samtykke;
		$WP_TWIG_DATA['krev_foresatt'] = $KREV_FORESATT;
		$WP_TWIG_DATA['er_foresatt'] = $ER_FORESATT;
	}
} catch( Exception $e ) {
	$view_template = 'Samtykke/error';
	$WP_TWIG_DATA['melding'] = $e->getMessage();
}