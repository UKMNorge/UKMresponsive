<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

require_once('UKM/monstring.class.php');
require_once('UKM/innslag.class.php');

$id = $WP_TWIG_DATA['page']->getLastParameter();
$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;

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

	$innslag = new innslag_v2($id);	
	$view_template = 'Monstring/innslag';
	
	SEO::setCanonical( SEO::getCanonical(). $id .'/'); // Already set to correct page, but is missing id
	SEO::setTitle( $innslag->getNavn() .' @ UKM');
	SEO::setDescription( 'Les mer om '. $innslag->getNavn() .' som deltar.' );
	if( $innslag->getBilder()->har() ) {
		$image = $innslag->getBilder()->first()->getSize('large');
		$SEOimage = new SEOimage( $image->getUrl(), $image->getWidth(), $image->getHeight(), $image->getMimeType() );
		SEO::setImage( $SEOimage );
	}

	$WP_TWIG_DATA['innslag'] = $innslag;
}
## Skal hente ut alle påmeldte innslag til påmeldte-oversikten.
else {
	// /pameldte/ - vil altså laste inn oversikten.
	$WP_TWIG_DATA['monstring'] = new monstring_v2(get_option('pl_id'));;
	$view_template = 'Monstring/pameldte';

	SEO::setTitle( 'Påmeldte til '. $WP_TWIG_DATA['monstring']->getNavn() );
	SEO::setDescription( 'Les mer om de som er med' );
}