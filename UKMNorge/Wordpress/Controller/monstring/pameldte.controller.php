<?php
$id = $WP_TWIG_DATA['page']->getLastParameter();

## Skal hente ut ETT innslag, gitt i $id.
if( is_numeric( $id ) ) {
	// /pameldte/id/ - i.e. forespørsel om enkelt-innslag. Funker både med og uten slutt-/.
	if(isset($_POST['singleMode']) && "true" == $_POST['singleMode'] ) {
		$WP_TWIG_DATA['singleMode'] = true;
	}

	$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;
	$WP_TWIG_DATA['innslag'] = new innslag_v2($id);	
	$view_template = 'Monstring/innslag';
}
## Skal hente ut alle påmeldte innslag til påmeldte-oversikten.
else {
	// /pameldte/ - vil altså laste inn oversikten.
	require_once('UKM/monstring.class.php');	
	$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;
	$view_template = 'Monstring/pameldte';
}