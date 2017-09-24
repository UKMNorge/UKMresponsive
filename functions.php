<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

add_theme_support( 'post-thumbnails' );
add_theme_support( 'menus' );

add_action( 'wp_ajax_nopriv_UKMresponsive', 'UKMresponsive_ajax' );

define('PATH_THEME', TEMPLATEPATH . '/');
define('PATH_DESIGNBUNDLE', PATH_THEME .'UKMNorge/DesignBundle/');
define('PATH_WORDPRESSBUNDLE', PATH_THEME .'UKMNorge/Wordpress/');
define('URL_THEME', get_stylesheet_directory_uri() );
define( 'WP_ENV', (strpos( $_SERVER['HTTP_HOST'], 'ukm.dev' ) !== false || isset($_GET['debug'])) ? 'dev' : 'prod' );

// AUTOLOAD AND SYMFONY EXISTING FILES
require_once('vendor/autoload.php');
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
WP_TWIG::setDebug( WP_ENV == 'dev' );
$WP_TWIG_DATA = [];


function UKMresponsive_ajax() {
	global $WP_TWIG_DATA;
	// LOAD CONFIG
	WP_CONFIG::setConfigPath( PATH_DESIGNBUNDLE . 'Resources/config/parameters.yml' );
	
	// TWIG INIT
	WP_TWIG::setTemplateDir( PATH_DESIGNBUNDLE .'Resources/views/' );
	WP_TWIG::setDebug( WP_ENV == 'dev' );
	$WP_TWIG_DATA = [];

	$response = [
		'action' => $_POST['ajaxaction'],
		'trigger' => $_POST['trigger'],
		'success' => false,
	];

	$ajaxFile = PATH_WORDPRESSBUNDLE . 'Ajax/'. basename($response['action']) .'.ajax.php';
	if( file_exists( $ajaxFile ) ) {
		require_once( PATH_WORDPRESSBUNDLE . 'Ajax/'. basename($response['action']) .'.ajax.php');
	}
	
	echo json_encode( $response );
	wp_die();
}