<?php
$MAPNAME = 'fylkeskontaktene';
$mailfilter = 'ukm.no';

require_once('UKM/kontakt.class.php');
require_once('UKM/inc/twig-admin.inc.php');
require_once(WP_PLUGIN_DIR . '/UKMkart/config.php');
require_once(WP_PLUGIN_DIR . '/UKMkart/functions.inc.php');

$DATA['kontaktkart'] = visitor_map($MAPNAME, $mailfilter);


foreach( $DATA['kontaktkart']['kontakter'] as $key => $kontakt ) {
	$kontakt->bilde_ikkesirkel = str_replace('ukm.local','ukm.no', $kontakt->bilde);
	$kontakt->bilde = str_replace('ukm.local','ukm.no', $kontakt->bilde_sirkel);
	$kontakt->navn = $kontakt->fornavn .' '. $kontakt->etternavn;
	$DATA['kontaktkart']['kontakter'][$key] = $kontakt;
}
$DATA['kontaktkart']['kart_url'] = str_replace('ukm.local','ukm.no', $DATA['kontaktkart']['kart_url'] );