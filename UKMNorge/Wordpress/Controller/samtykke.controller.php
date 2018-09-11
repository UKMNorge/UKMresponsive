<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/prosjekt.class.php');
require_once('UKM/samtykke/request.class.php');
require_once('UKM/samtykke/approval.class.php');

$request = samtykke_request::loadFromHash( $_GET['prosjekt'], $_GET['samtykke'] );
$prosjekt = $request->getProsjekt();


if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	#var_dump( $_POST );
	switch( $_POST['save'] ) {
		
		# FØRSTE SKJEMA (SAMTYKKE GITT)
		case 'approval':
			# For ung til å oppgi samtykke, etterspør foresattes info
			if( ($_POST['alder'] == 'under10' || (int) $_POST['alder'] < 15) && $_POST['alder'] != 'over20' ) {
				write_samtykke::godta( $request, $_POST['alder'] );
				$view_template = 'Samtykke/foresatt';
			}
			# Alt OK. Si takk.
			else {
				write_samtykke::godta( $request, $_POST['alder'] );
				$view_template = 'Samtykke/takk';
				try {
					$melding = samtykke_request::createMeldingTakk( $request );
					// SEND SMS
					if( UKM_HOSTNAME == 'ukm.dev' ) {
						echo '<h3>SMS-debug</h3>'.
							'<b>TEXT: </b>'. $melding .' <br />'.
							'<b>TO: </b>'. $request->getMobil();
					} else {
						require_once('UKM/sms.class.php');
						$sms = new SMS('samtykke-takk', 0);
						$sms->text( $melding )
							->to( $request->getMobil() )
							->from('UKMNorge')
							->ok()
							;
					}
				} catch( Exception $e ) {
					if( $e->getCode() == 2 ) {
						$view_template = 'Samtykke/foresatt';
					} else {
						throw $e;
					}
				}
			}
		break;
		
		# ANDRE SKJEMA (KONTAKT FORESATT)
		case 'request-parent':
			write_samtykke::lagreForesatt( $request, $_POST['navn'], str_replace(' ','', $_POST['mobil']) );
			$melding = samtykke_request::createMeldingForeldre( $request, $_POST['navn'], str_replace(' ','',$_POST['mobil']) );
			// SEND SMS
			if( UKM_HOSTNAME == 'ukm.dev' ) {
				echo '<h3>SMS-debug</h3>'.
					'<b>TEXT: </b>'. $melding .' <br />'.
					'<b>TO: </b>'. str_replace(' ','',$_POST['mobil']);
			} else {
				require_once('UKM/sms.class.php');
				$sms = new SMS('samtykke-barn', 0);
				$sms->text( $melding )
					->to( str_replace(' ','',$_POST['mobil']) )
					->from('UKMNorge')
					->ok()
					;
			}
			
			$WP_TWIG_DATA['foresatt'] = $_POST['navn'];
			$WP_TWIG_DATA['mobil'] = str_replace(' ','',$_POST['mobil']);
			$view_template = 'Samtykke/takkBarn';
		break;
		
		# TREDJE SKJEMA (FORESATT HAR GODTATT)
		case 'approval-foresatt':
			write_samtykke::godtaForesatt( $request );
			$view_template = 'Samtykke/takk';

			$melding = samtykke_request::createMeldingTakk( $request );
			// SEND SMS
			if( UKM_HOSTNAME == 'ukm.dev' ) {
				echo '<h3>SMS-debug</h3>'.
					'<b>TEXT: </b>'. $melding .' <br />'.
					'<b>TO: </b>'. $request->getMobil();
			} else {
				require_once('UKM/sms.class.php');
				$sms = new SMS('samtykke-takk', 0);
				$sms->text( $melding )
					->to( $request->getMobil() )
					->from('UKMNorge')
					->ok()
					;
			}
		break;
	}
} else {
	$SEOimage = new SEOimage( $request->getLenker()[0]->url );
	SEO::setImage( $SEOimage );
	SEO::setCanonical( 'https://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
	SEO::setTitle( 'Samtykke til å '. $prosjekt->getSetning() );
	SEO::setDescription( addslashes( preg_replace( "/\r|\n/", "", strip_tags( $WP_TWIG_DATA['post']->lead ) ) ) );
	SEO::setAuthor( 'UKM Norge' );
	
	if( isset( $_GET['foresatt'] ) && is_numeric( $_GET['foresatt'] ) ) {
		$approval = new samtykke_approval( $request->getId() );
		$WP_TWIG_DATA['foresatt'] = true;
		$WP_TWIG_DATA['approval'] = $approval;
	} else {
		$WP_TWIG_DATA['foresatt'] = false;
	}
}


$WP_TWIG_DATA['request'] = $request;
$WP_TWIG_DATA['prosjekt'] = $prosjekt;