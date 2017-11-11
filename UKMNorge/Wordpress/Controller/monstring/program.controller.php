<?php
require_once('UKM/monstring.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/forestilling.class.php');

$WP_TWIG_DATA['monstring'] = new monstring_v2( get_option('pl_id') );
$id = $WP_TWIG_DATA['page']->getLastParameter();

## Skal hente ut programmet for en forestilling
if( is_numeric( $id ) ) {
	// /program/c_id/
	$view_template = 'Monstring/program_hendelse';
	$WP_TWIG_DATA['hendelse'] = new forestilling_v2( $id );	
}
## Skal vise rammeprogrammet
else {
	$view_template = 'Monstring/program_oversikt';
}