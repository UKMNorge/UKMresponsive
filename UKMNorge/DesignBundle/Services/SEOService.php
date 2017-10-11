<?php
	
namespace UKMNorge\DesignBundle\Services;

use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

class SEOService extends SEO {
	public function __construct( $SEOdefaults, $seoAppDefault, $facebook, $google ) {
		$SEOImage = new SEOImage(
			$SEOdefaults['image']['url'], 
			$SEOdefaults['image']['width'],
			$SEOdefaults['image']['height'],
			$SEOdefaults['image']['type']
		);
		parent::setImage( $SEOImage );

		// SET DEFAULTS (UKMNorge/DesignBundle/config/parameters.yml)
		foreach( $SEOdefaults as $key => $val ) {
			if( !is_array( $val ) ) {
				$function = 'set' . ucfirst( $key );
				if( method_exists('UKMNorge\DesignBundle\Utils\SEO', $function ) ) {
					parent::$function( $val );
				}
			}
		}

		// SET APP DEFAULTS (App/config/parameters.yml)
		foreach( $seoAppDefault as $key => $val ) {
			if( !is_array( $val ) ) {
				$function = 'set' . ucfirst( $key );
				if( method_exists('UKMNorge\DesignBundle\Utils\SEO', $function ) ) {
					parent::$function( $val );
				}
			}
		}
		
		// SET FB INFOS (UKMNorge/DesignBundle/config/parameters.yml :: facebook )
		if( is_array( $facebook ) ) {
			if( isset( $facebook['app_id'] ) ) {
				parent::setFBAppId( $facebook['app_id'] );
			}
			if( isset( $facebook['admins'] ) ) {
				parent::setFBAdmins( $facebook['admins'] );
			}
		}
		
		// SET GOOGLE INFOS (UKMNorge/DesignBundle/config/parameters.yml :: google )
		if( is_array( $google ) ) {
			if( isset( $google['site_verification'] ) ) {
				parent::setGoogleSiteVerification( $google['site_verification'] );
			}
		}
	}
}