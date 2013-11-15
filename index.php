<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

define('THEME_PATH', get_theme_root().'/UKMresponsive/' );
define('TWIG_PATH', __DIR__ );


require_once('WP/wp_get_post.class.php');
require_once('functions_theme.php');
error_reporting(E_ALL);
ini_set('display_errors',1);


/**********************************
* INITIATE TEMPLATE
**********************************/
	$DATA = array();
	$DATA['url']['base']		= 'http://'. $_SERVER['HTTP_HOST'];
	$DATA['url']['theme_dir'] 	= get_stylesheet_directory_uri();
	$DATA['url']['blog']		= get_option('site_url');
	$DATA['url']['current']		= get_permalink();

/**********************************
* 
**********************************/
	require_once('controller/nav_top.controller.php');



/**********************************
* SWITCH VIEW
**********************************/
	if( is_archive() ) {
	} elseif( is_single() ) {
		require_once('controller/view/post.controller.php');
		$VIEW = 'post';
	} elseif( is_front_page() ) {
		require_once('controller/view/homepage.controller.php');
		$VIEW = 'homepage';
	} elseif( is_page() ) {
	} else {
		require_once('controller/view/404.controller.php');
		$VIEW = '404';
	}


echo TWIGrender('view/'.$VIEW, object_to_array($DATA),true);
die();