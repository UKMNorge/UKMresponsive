<?php

## Skal hente ut ETT innslag, gitt i $id.
$monstring = new monstring_v2(get_option('pl_id'));
$innslagCollection = $monstring->getInnslag();

// Hvis dette innslaget ikke finnes i mønstringen
if( ! $innslagCollection->harInnslagMedId($id) ) {
	// TODO: Returner feil her - annen template kanskje?
	echo "Ingen innslag funnet i denne mønstringen med ID ".$id."! <br />";
	$WP_TWIG_DATA['errors'][] = "Innslaget du har forsøkt å åpne finnes ikke i denne mønstringen. Dette er en feil, kontakt gjerne UKM Support og si hvilken lenke du trykket på.";
	return;
}

$innslag = new innslag_v2($id);	
// TOOD: Remove this
#if (UKM_HOSTNAME == 'ukm.dev')
#	sleep(2);
$WP_TWIG_DATA['innslag'] = $innslag;
$WP_TWIG_DATA['monstring'] = $monstring;

$WP_TWIG_DATA['innslagType'] = $innslag->getType();

$WP_TWIG_DATA['program'] = $innslag->getProgram($monstring);

if( $WP_TWIG_DATA['innslagType']->harTitler() ){
	$titler = $innslag->getTitler($monstring);
	$WP_TWIG_DATA['titler'] = $titler->getAll();

	// Dersom minst èn av titlene til innslaget skal leses opp
	$WP_TWIG_DATA['skalLesesOpp'] = false;	
	foreach( $WP_TWIG_DATA['titler'] as $tittel ) {
		if ( $tittel->skalLesesOpp() )
			$WP_TWIG_DATA['skalLeseOpp'] = true;	
	}
}
