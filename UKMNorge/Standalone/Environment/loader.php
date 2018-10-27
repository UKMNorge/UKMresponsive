<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

header('Content-Type: text/html; charset=utf-8');
session_start();
setlocale(LC_ALL, 'nb_NO', 'nb', 'no');

$constants = [
	'URL' => 'URL til siden',	
	'AJAX' => 'URL for ajax-kall',
	'SECTION' => 'Seksjonsnavn siden faller inn under',
	'TITLE' => 'Navn på siden',
	'DESCRIPTION' => 'Beskrivelse av sidens innhold',
	'TWIG_PATH' => 'Mappe med twig-templates',
];

foreach( $constants as $constant => $constant_value ) {
	if( !defined( 'STANDALONE_'. $constant ) ) {
		throw new Exception('Standalone-mode mangler konstanten `STANDALONE_'. $constant .'` ('. $constant_value.')');
	}
}

if( !defined('UKM_HOSTNAME') ) {
	define('UKM_HOSTNAME', 'ukm.no'); // ukm.dev for dev-environment
}


define('PATH_DESIGNBUNDLE', dirname(dirname(dirname(__FILE__))).'/DesignBundle/');
define('PATH_WORDPRESSBUNDLE', str_replace('DesignBundle','',PATH_DESIGNBUNDLE) .'Wordpress/');

// AUTOLOAD AND SYMFONY EXISTING FILES
require_once( PATH_WORDPRESSBUNDLE . 'Environment/wp_twig.class.php');
require_once( PATH_WORDPRESSBUNDLE . 'Environment/wp_config.class.php');

// MANUALLY LOAD FILES SYMFONY WOULD LOAD BY NAMESPACE
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Page.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Pages.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Section.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/SEO.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/SEOImage.php');


// LOAD CONFIG
Sitemap::loadFromYamlFile( PATH_DESIGNBUNDLE . 'Resources/config/sitemap.yml' );
WP_CONFIG::setConfigPath( PATH_DESIGNBUNDLE . 'Resources/config/parameters.yml' );

// TWIG INIT
WP_TWIG::setTemplateDir( PATH_DESIGNBUNDLE .'Resources/views/' );
WP_TWIG::addTemplateDir( STANDALONE_TWIG_PATH );
WP_TWIG::setDebug( is_defined('WP_ENV') && WP_ENV == 'dev' );
$WP_TWIG_DATA = [];


$WP_TWIG_DATA['HEADER'] = new stdClass();
$WP_TWIG_DATA['HEADER']->background = new stdClass();
$WP_TWIG_DATA['HEADER']->button = new stdClass();
$WP_TWIG_DATA['HEADER']->logo = new stdClass();
$WP_TWIG_DATA['UKM_HOSTNAME'] = UKM_HOSTNAME;
$WP_TWIG_DATA['blog_url'] = STANDALONE_URL;
$WP_TWIG_DATA['ajax_url'] = STANDALONE_AJAX;

// SEO INIT
$SEOImage = new SEOImage( WP_CONFIG::get('SEOdefaults')['image']['url'], 
						  WP_CONFIG::get('SEOdefaults')['image']['width'],
						  WP_CONFIG::get('SEOdefaults')['image']['height'],
						  WP_CONFIG::get('SEOdefaults')['image']['type'] );

SEO::setCanonical( STANDALONE_URL );
SEO::setSiteName( WP_CONFIG::get('SEOdefaults')['site_name'] );
SEO::setSection( STANDALONE_SECTION );
SEO::setType('website');
SEO::setTitle( STANDALONE_TITLE );
SEO::setDescription( STANDALONE_DESCRIPTION );
SEO::setAuthor( WP_CONFIG::get('SEOdefaults')['author'] );

SEO::setFBAdmins( WP_CONFIG::get('facebook')['admins'] );
SEO::setFBAppId( WP_CONFIG::get('facebook')['app_id'] );

SEO::setGoogleSiteVerification( WP_CONFIG::get('google')['site_verification'] );
SEO::setImage( $SEOImage );

	
