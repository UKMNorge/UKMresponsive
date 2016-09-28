<?php
	
use UKMNorge\DesignBundle\Utils\Sitemap;

header('Content-Type: text/html; charset=utf-8');
session_start();
setlocale(LC_ALL, 'nb_NO', 'nb', 'no');

define('PATH_THEME', TEMPLATEPATH . '/');
define('PATH_DESIGNBUNDLE', PATH_THEME .'UKMNorge/DesignBundle/');

define( 'WP_ENV', (strpos( $_SERVER['HTTP_HOST'], 'ukm.dev' ) !== false || isset($_GET['debug'])) ? 'dev' : 'prod' );

// AUTOLOAD AND SYMFONY EXISTING FILES
require_once('vendor/autoload.php');
require_once('Environment/wp_twig.class.php');

// MANUALLY LOAD FILES SYMFONY WOULD LOAD BY NAMESPACE
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Page.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Pages.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap/Section.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/Sitemap.php');
require_once( PATH_DESIGNBUNDLE . 'Utils/MetaTags.php');


WP_TWIG::setTemplateDir( PATH_DESIGNBUNDLE .'Resources/views/' );
WP_TWIG::setDebug( WP_ENV == 'dev' );
$WP_TWIG_DATA = [];

Sitemap::loadFromYamlFile( PATH_DESIGNBUNDLE . 'Resources/config/sitemap.yml' );