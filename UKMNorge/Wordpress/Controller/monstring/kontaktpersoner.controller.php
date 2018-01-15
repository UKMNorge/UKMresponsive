<?php
require_once('UKM/monstring.class.php');
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

$monstring = new monstring_v2( get_option('pl_id') );
$WP_TWIG_DATA['monstring'] = $monstring;

SEO::setTitle( 'Kontaktpersoner for'.( $monstring->getType() == 'kommune' ? ' UKM' : '').' '. $WP_TWIG_DATA['monstring']->getNavn() );
SEO::setDescription( 'Vi gleder oss til du tar kontakt!' );
