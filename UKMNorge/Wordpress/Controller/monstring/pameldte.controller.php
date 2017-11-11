<?php
require_once('UKM/monstring.class.php');
require_once('UKM/innslag.class.php');

$id = $WP_TWIG_DATA['page']->getLastParameter();

## Skal hente ut ETT innslag, gitt i $id.
if( is_numeric( $id ) ) {
	// /pameldte/id/ - i.e. forespørsel om enkelt-innslag. Funker både med og uten slutt-/.
	if(isset($_POST['singleMode']) && "true" == $_POST['singleMode'] ) {
		$WP_TWIG_DATA['singleMode'] = true;
	}

	if( isset( $_GET['cid'] ) ) {
		require_once('UKM/forestilling.class.php');
		$WP_TWIG_DATA['hendelse'] = new forestilling_v2( (int) $_GET['cid'] );
	}

	$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;
	$WP_TWIG_DATA['innslag'] = new innslag_v2($id);	
	$view_template = 'Monstring/innslag';
}
## Skal hente ut alle påmeldte innslag til påmeldte-oversikten.
else {
	// /pameldte/ - vil altså laste inn oversikten.
	$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;
	$view_template = 'Monstring/pameldte';
}