<?php
require_once('UKM/mail.class.php');
require_once('UKM/sql.class.php');
require_once('UKM/fylker.class.php');
require_once('UKM/monstring.class.php');

$view_template = 'Passord/glemt';

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$WP_TWIG_DATA['email'] = $_POST['email'];
	/**
	 * CASE: VI SKAL SENDE E-POST TIL FYLKESKONTAKTEN
	**/
	if( isset( $_POST['fylkesrequest'] ) ) {
		$fylke = fylker::getById( $_POST['fylke'] );
		UKMresponsive_sendPassFylke( $_POST['email'], $_POST['navn'], $fylke, $_POST['kommune'] );
		
		$view_template = 'Passord/sendt';
		$WP_TWIG_DATA['mottaker'] = 'UKM '. $fylke->getNavn();
	}
	/**
	 * CASE: BRUKEREN HAR VALGT ÉN AV FLERE KOMMUNER Å ETTERSPØRRE PASSORD FOR
	**/
	elseif( isset( $_POST['kommune'] ) ) {
		$kommune = new kommune( $_POST['kommune'] );

		UKMresponsive_sendPass( $_POST['email'], $kommune->getFylke(), $kommune );
		$view_template = 'Passord/funnet';
	}
	/**
	 * CASE: VI HAR FÅTT OPPGITT EN E-POSTADRESSE
	**/
	elseif( isset( $_POST['email'] ) ) {
		if (!preg_match("/^([-0-9a-zA-Z.+_-]+)@([0-9a-zA-Z.+_-]+)\.([a-zA-Z]){2,4}$/", $_POST['email'])){
			
			$WP_TWIG_DATA['emailcontent'] = htmlentities( 
				'?subject=Glemt arrangørpassord'
				.'&body=Systemet kjente ikke igjen e-postadressen min. Fint om dere kan sende meg arrangørpassord. '
				.'Min e-post er '. $_POST['email'] .'.'
			);
			$view_template = 'Passord/invalid';
		} else {
			
			// EDGE-CASE: e-postadressen har flere bruker-relasjoner, og vil kun ta den første.
			global $wpdb;
			$row = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT 
						`b_id`,
						`b_kommune`,
						`b_fylke`
					FROM `ukm_brukere`
					WHERE `b_email` = '%s'
					",
					[
						$_POST['email']
					]
				), 
				ARRAY_A
			);
			
			/**
			 * RESULT: FANT BRUKER-RELASJON MED GITT E-POST
			**/
			if( null != $row ) {
				if( $row['b_kommune'] == 0 ) {
					$fylke = fylker::getById( $row['b_fylke'] );
					$navn = $fylke->getNavn();
				} else {
					$kommune = new kommune( $row['b_kommune'] );
					$navn = $kommune->getNavn();
					$fylke = $kommune->getFylke();
				}
				UKMresponsive_sendPassUser( $row['b_id'], $_POST['email'], $navn, $fylke );
				$view_template = 'Passord/funnet';
			} else {
				// Søk opp blant kontaktpersoner
				$SQL = new SQL(
					monstring_v2::getLoadQry() . "
					JOIN `smartukm_rel_pl_ab` AS `rel` 
						ON (`rel`.`pl_id` = `place`.`pl_id`) 
					JOIN `smartukm_contacts` AS `contact` 
						ON(`contact`.`id` = `rel`.`ab_id`) 
					WHERE `contact`.`email` = '#email'
					GROUP BY `place`.`pl_id`",
					[
						'email' => $_POST['email']
					]
				);
				$res = $SQL->run();
				
				$monstringer = [];
				if( $res ) {
					while( $row = mysql_fetch_assoc( $res ) ) {
						$monstring = new monstring_v2( $row['pl_id'] );
						#echo $monstring->getSesong().'<br />';
						if( $monstring->getSesong() == get_site_option('season') ) {
							$monstringer[] = $monstring;
						}
					}
				}
				
				/**
				 * RESULT: FANT IKKE NOEN REFERANSE I SYSTEMET
				 * Prompt user send to fylkeskontakt
				**/
				if( sizeof( $monstringer ) == 0 ) {
					$WP_TWIG_DATA['fylker'] = fylker::getAll();
					$view_template = 'Passord/ingen';
				} 
				/**
				 * RESULT: FANT EN SINGEL-MØNSTRING I SYSTEMET
				**/
				elseif( sizeof( $monstringer ) == 1 && !$monstringer[0]->erFellesmonstring() ) {
					$fylke = $monstringer[0]->getFylke();
					$kommune = $monstringer[0]->getKommune();
					
					UKMresponsive_sendPass( $_POST['email'], $fylke, $kommune );
					$view_template = 'Passord/funnet';
				} 
				/**
				 * RESULT: FANT FLERE MØNSTRINGER I SYSTEMET
				 * RESULT: FANT EN FELLESMØNSTRING I SYSTEMET
				**/
				else {
					$WP_TWIG_DATA['valg'] = [];
					foreach( $monstringer as $monstring ) {
						foreach( $monstring->getKommuner() as $kommune ) {
							$WP_TWIG_DATA['valg'][ $kommune->getId() ] = $kommune->getNavn();
						}
					}
	
					$view_template = 'Passord/flere';
				}
			}
		}
	}
}


function UKMresponsive_sendPass( $epost, $fylke, $kommune ) {
	$message = 
		'Hei!'.
		"\r\n\r\n".
		'En bruker har glemt passordet sitt.'.
		'<a href="https://ukm.no/'. $fylke->getLink() .'/wp-admin/admin.php?page=UKMpassord">1. Hent passordet</a>'.
		"\r\n".
		'2. Videresend brukernavn og passord til '. $epost .
		"\r\n\r\n".
		'Brukernavn: '. $kommune->getURLsafe() .
		"\r\n".
		'Passord: '.
		"\r\n\r\n".
		'Mvh,'.
		"\r\n".
		'UKM Norges arrangørsystem'.
		"\r\n".
		'support@ukm.no'
	;
	
#	echo $message;
	$mail = new UKMmail();
	$mail
		->subject( 'Ønsker passord til arrangørsystemet' )
		->message( $message )
		->to( 'support@ukm.no' )
		->setReplyTo( $_POST['email'], $_POST['navn'] )
		->ok()
	;
}

function UKMresponsive_sendPassUser( $id, $epost, $navn, $fylke ) {	
	$message = 
		'Hei!'.
		"\r\n\r\n".
		'<a href="https://ukm.no/'. $fylke->getLink() .'/wp-admin/admin.php?page=UKMpassord&send='. $id .'">'. 
		'Klikk her for å sende nytt passord til '. $navn .'</a>'.
		' som har glemt passordet sitt.'.
		"\r\n\r\n".
		'Mvh,'.
		"\r\n".
		'UKM Norges arrangørsystem'.
		"\r\n".
		'support@ukm.no'
	;
	
#	echo $message;
	$mail = new UKMmail();
	$mail
		->subject( 'Ønsker passord til arrangørsystemet' )
		->message( $message )
		->to( 'support@ukm.no' )
		->setReplyTo( $_POST['email'], $_POST['navn'] )
		->ok()
	;
}

function UKMresponsive_sendPassFylke( $epost, $navn, $fylke, $kommune) {
	$message = 
		'Hei UKM '. $fylke->getNavn().
		"\r\n\r\n".
		$navn .' har vært i kontakt med support, og ønsker tilgang til arrangørsystemet for kommune: '. $kommune .'.'.
		"\r\n\r\n".
		'Vi har ikke funnet e-postadressen ('. $epost .') registrert noe sted i systemet, '.
		'og trenger derfor at dere godkjenner tilgangen i arrangørsystemet. '.
		"\r\n\r\n".
		'Logg inn til arrangørsystemet som vanlig, og velg menyen "Passordliste".'.
		"\r\n\r\n".
		'Herfra velger du om: '.
		"\r\n".
		' 1) '. $navn .' skal oppføres som ny hovedkontakt.'.
		"\r\n".
		' 2) du sender brukernavn og passord direkte til '. $navn .' som et svar på denne e-posten.'.
		"\r\n".
		' 3) eventuelt kan du sende '. $navn .' lenken til glemt passord for deltakere, hvis du tror det er en deltaker.'.
		"\r\n".
		'Lenke for glemt passord: https://delta.'. UKM_HOSTNAME .'/resetting/request'.
		"\r\n\r\n".
		'Mvh,'.
		"\r\n".
		'UKM Norges arrangørsystem'.
		"\r\n".
		'support@ukm.no'
	;
	
	$mail = new UKMmail();
	$mail
		->subject( 'Mangler passord til arrangørsystemet' )
		->message( $message )
		->to( implode(',', [$_POST['email'], $fylke->getLink().'@ukm.no', 'support@ukm.no']) )
		->setReplyTo( $_POST['email'], $_POST['navn'] )
		->ok()
	;
}