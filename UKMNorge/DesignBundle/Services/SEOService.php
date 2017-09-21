<?php
	
namespace UKMNorge\DesignBundle\Services;

use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

class SEOService extends SEO {
	public function __construct( $SEOdefaults ) {
		$SEOImage = new SEOImage(
			$SEOdefaults['image']['url'], 
			$SEOdefaults['image']['width'],
			$SEOdefaults['image']['height'],
			$SEOdefaults['image']['type']
		);
		parent::setImage( $SEOImage );
		parent::setTitle( 'UKM Delta' );
	}
}