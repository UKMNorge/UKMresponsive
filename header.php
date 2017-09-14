<?php
	
use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

header('Content-Type: text/html; charset=utf-8');
session_start();
setlocale(LC_ALL, 'nb_NO', 'nb', 'no');

define('PATH_THEME', TEMPLATEPATH . '/');
define('PATH_DESIGNBUNDLE', PATH_THEME .'UKMNorge/DesignBundle/');
define('URL_THEME', get_stylesheet_directory_uri() );

define( 'WP_ENV', (strpos( $_SERVER['HTTP_HOST'], 'ukm.dev' ) !== false || isset($_GET['debug'])) ? 'dev' : 'prod' );

// CHECK CACHE (AND DIE IF FOUND CACHE)
require_once('cache.php');

// AUTOLOAD AND SYMFONY EXISTING FILES
require_once('vendor/autoload.php');
require_once('UKMNorge/Wordpress/Environment/wp_twig.class.php');
require_once('UKMNorge/Wordpress/Environment/wp_config.class.php');

// MANUALLY LOAD FILES SYMFONY WOULD LOAD BY NAMESPACE
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Page.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Pages.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Section.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/SEO.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/SEOImage.php');

// LOAD CONFIG
WP_CONFIG::setConfigPath( PATH_DESIGNBUNDLE . 'Resources/config/parameters.yml' );

// TWIG INIT
WP_TWIG::setTemplateDir( PATH_DESIGNBUNDLE .'Resources/views/' );
WP_TWIG::setDebug( WP_ENV == 'dev' );
$WP_TWIG_DATA = [];

$WP_TWIG_DATA['UKM_HOSTNAME'] = UKM_HOSTNAME;
$WP_TWIG_DATA['blog_url'] = get_bloginfo('url');

// SITEMAP / MENU
Sitemap::loadFromYamlFile( PATH_DESIGNBUNDLE . 'Resources/config/sitemap.yml' );
$WP_TWIG_DATA['nav'] = Sitemap::getSections();

// SEO INIT
$SEOImage = new SEOImage( WP_CONFIG::get('SEOdefaults')['image']['url'], 
						  WP_CONFIG::get('SEOdefaults')['image']['width'],
						  WP_CONFIG::get('SEOdefaults')['image']['height'],
						  WP_CONFIG::get('SEOdefaults')['image']['type'] );

$SEO = new SEO();
$SEO->setCanonical( get_permalink() );
$SEO->setSiteName( WP_CONFIG::get('SEOdefaults')['site_name'] );
$SEO->setSection( get_bloginfo('name') );
$SEO->setType('website');
$SEO->setTitle( WP_CONFIG::get('SEOdefaults')['title'] );
$SEO->setDescription( WP_CONFIG::get('slogan') );
$SEO->setAuthor( WP_CONFIG::get('SEOdefaults')['author'] );

$SEO->setFBAdmins( WP_CONFIG::get('facebook')['admins'] );
$SEO->setFBAppId( WP_CONFIG::get('facebook')['app_id'] );

$SEO->setGoogleSiteVerification( WP_CONFIG::get('google')['site_verification'] );
$SEO->setImage( $SEOImage );

$WP_TWIG_DATA['SEO'] = $SEO;


/**
 * TEMA-INNSTILLINGER
 *
 * 1: UKM.no hovedside
 * 2: Fylkesside eller lokalside
 * 3: EGO
**/
// 1: UKM.no hovedside
if( get_current_blog_id() == 1 ) {
	$WP_TWIG_DATA['THEME'] = 'cherry';
}
// 2: Fylkesside eller lokalside
elseif( get_option('site_type') == 'fylke' || get_option('site_type') == 'kommune' ) {
	$section = new stdClass();
	$section->title = get_bloginfo('name');
	$section->url = get_bloginfo('url');
	$WP_TWIG_DATA['section'] = $section;
}
// 3: EGO
elseif( get_option('site_type') == 'ego' ) {
	$header = new stdClass();
	$header->logo = 'EGO';
	$header->config = 'hvaerego';
	$WP_TWIG_DATA['header'] = $header;
	
	$WP_TWIG_DATA['THEME'] = 'ego';
}
// Alle andre sider
else {
	$WP_TWIG_DATA['THEME'] = '';
}