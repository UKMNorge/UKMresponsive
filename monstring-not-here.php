<?php
use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

require_once('header.php');
require_once('UKM/monstringer.class.php');


$template = 'Monstring/'.get_option('status_monstring');

// Prøv å finn nye mønstringer for kommunen
$monstringer = [];


/**
 * Hent kommuner fra bloggen
 * Ved avlysning / flytting / endring lagres en liste
 * med kommuneid'er mønstringen har i endringstidspunktet
**/
$kommuner = explode(',', get_option('kommuner'));
if( is_array( $kommuner ) ) {
	// Loop alle kommuner
	foreach( $kommuner as $kommune_id ) {
		// Prøv å finne en mønstring for kommunen
		#echo '<br /> Finn mønstring for kommune '. $kommune_id .' i '. get_site_option('season') .'-sesongen:';
		try {
			$monstring = monstringer_v2::kommune( $kommune_id, get_site_option('season') );
			$monstringer[ $monstring->getId() ] = $monstring;
			#echo 'Fant '. $monstring->getId() .' - '. $monstring->getNavn();
		} catch( Exception $e ) {
			#echo $e->getMessage();
			// Ignorer mønstringer vi ikke finner
		}
	}
}

/**
 * Hvis vi har funnet én mønstring for denne siden
 * er det dit brukeren skal
 *
 * Burde kanskje ikke gjelde når mønstringen er splittet, da det kan
 * være at deltakeren søker kommunen som er tatt ut. Videresender likevel inntil videre
**/
if( sizeof( $monstringer ) == 1 ) {
	$monstring = array_shift( $monstringer );
	wp_redirect( $monstring->getLink() );
	exit;
}

switch( str_replace('Monstring/', '', $template) ) {
	case 'avlyst':
		try {
			$WP_TWIG_DATA['kommune'] = new kommune( $kommuner[0] );
		} catch( Exception $e ) {
			// Ignorer hvis vi ikke finner gitt kommune - view håndterer det
		}
		break;
	default:
		$WP_TWIG_DATA['monstringer'] = $monstringer;
		break;
}

echo WP_TWIG::render( $template, $WP_TWIG_DATA );

wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}