<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once('UKM/monstring.class.php');
require_once('UKM/innslag.class.php');
require_once('UKM/forestilling.class.php');

$monstring = new monstring_v2( get_option('pl_id') );
$WP_TWIG_DATA['monstring'] = $monstring;
$id = $WP_TWIG_DATA['page']->getLastParameter();

## Skal hente ut programmet for en forestilling
if( is_numeric( $id ) ) {
	// /program/c_id/
	$view_template = 'Monstring/program_hendelse';
	$hendelse = new forestilling_v2( $id );
	$WP_TWIG_DATA['hendelse'] = $hendelse;

	SEO::setTitle( $hendelse->getNavn() );
	SEO::setDescription( $hendelse->getStart()->format('j. M \k\l. H:i') .'. '.( $monstring->getType() == 'kommune' ? 'UKM ' : ''). $monstring->getNavn() );

}
## Skal vise rammeprogrammet
else {
	$view_template = 'Monstring/program_oversikt';
	SEO::setTitle( 'Program for'.( $monstring->getType() == 'kommune' ? ' UKM' : '').' '. $WP_TWIG_DATA['monstring']->getNavn() );
	SEO::setDescription( 'Vi starter '. $monstring->getStart()->format('j. M \k\l. H:i') );
}