<?php
$MAPNAME = 'fylkeskontaktene';
$mailfilter = 'ukm.no';

require_once('UKM/kontakt.class.php');
require_once('UKM/inc/twig-admin.inc.php');
require_once('UKM/inc/excel.inc.php');
require_once(WP_PLUGIN_DIR . '/UKMkart/config.php');
require_once(WP_PLUGIN_DIR . '/UKMkart/functions.inc.php');

$DATA['kontaktkart'] = visitor_map($MAPNAME, $mailfilter);


global $objPHPExcel;
$objPHPExcel = null;
exInit('UKM Fylkeskontakter');

$rad = 1;
excell('A'.$rad, 'Fylke','bold');
excell('B'.$rad, 'Navn','bold');
excell('C'.$rad, 'Mobil','bold');
excell('D'.$rad, 'E-post','bold');
excell('E'.$rad, 'Bilde','bold');


if( is_array( $DATA['kontaktkart']['kontakter'])) {
	foreach( $DATA['kontaktkart']['kontakter'] as $key => $kontakt ) {
		$kontakt->bilde_ikkesirkel = str_replace('ukm.local','ukm.no', $kontakt->bilde);
		$kontakt->bilde = str_replace('ukm.local','ukm.no', $kontakt->bilde_sirkel);
		$kontakt->navn = $kontakt->fornavn .' '. $kontakt->etternavn;
		$DATA['kontaktkart']['kontakter'][$key] = $kontakt;
		
		$rad++;
		excell('A'.$rad, $kontakt->fylke->navn);
		excell('B'.$rad, $kontakt->navn);
		excell('C'.$rad, $kontakt->mobil);
		excell('D'.$rad, $kontakt->epost);
		excell('E'.$rad, $kontakt->bilde_ikkesirkel);
	}
}
$DATA['kontaktkart']['kart_url'] = str_replace('ukm.local','ukm.no', $DATA['kontaktkart']['kart_url'] );
$DATA['kontaktkart']['excel'] = exWrite($objPHPExcel,'UKM_fylkeskontakter'.get_option('season'));