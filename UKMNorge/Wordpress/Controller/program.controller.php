<?php
require_once('UKM/monstring.class.php');
require_once('UKM/forestilling.class.php');

$WP_TWIG_DATA['monstring'] = new monstring_v2( get_option('pl_id') );

if( isset( $_GET['id'] ) ) {
	$view_template = 'Monstring/program_hendelse';
	$WP_TWIG_DATA['hendelse'] = new forestilling_v2( $_GET['id'] );	
} else {
	$view_template = 'Monstring/program_oversikt';
}