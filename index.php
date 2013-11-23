<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors',1);

define('THEME_PATH', get_theme_root().'/UKMresponsive/' );
define('TWIG_PATH', __DIR__ );

require_once('vendor/autoload.php');
require_once('WPOO/WPOO/Post.php');
require_once('WPOO/WPOO/Author.php');
require_once('UKM/inc/twig-admin.inc.php');
require_once('functions_theme.php');
error_reporting(E_ALL);
ini_set('display_errors',1);


/**********************************
* INITIATE TEMPLATE
**********************************/
	$DATA = array();
	$DATA['url']['base']		= 'http://'. $_SERVER['HTTP_HOST'];
	$DATA['url']['theme_dir'] 	= get_stylesheet_directory_uri().'/';
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
	    require_once('controller/view/archive.controller.php');
		if(is_author()) {
    		require_once('controller/view/author.controller.php');
    		$VIEW = 'author';
		}
		else {
    		$VIEW = 'archive';
        }
	} elseif( is_single() ) {
		require_once('controller/view/post.controller.php');
		require_once('controller/element/comments.controller.php');
		$VIEW = 'post';
	} elseif( is_front_page() ) {
		if( get_option('ukm_top_page') == 'internasjonalt' ) {
			require_once('controller/view/homepage.controller.php');
			$VIEW = 'homepage_internasjonalt';	
		} elseif( get_option('site_type') == 'fylke' ) {
            $VIEW = 'fylke';
			require_once('controller/view/fylke.controller.php');
		} else {
			require_once('controller/view/homepage.controller.php');
			$VIEW = 'homepage';
		}
	} elseif( is_page() ) {
		
		$viseng = get_post_meta($post->ID, 'UKMviseng', true);
		switch ( $viseng ) {
			case 'dinmonstring':
				require_once('controller/view/dinmonstring.controller.php');
				$VIEW = 'dinmonstring';
				break;
			default:
				require_once('controller/view/post.controller.php');
				$VIEW = 'page';
				break;
		}
	} else {
		require_once('controller/view/404.controller.php');
		$VIEW = '404';
	}

echo TWIGrender('view/'.$VIEW, object_to_array($DATA),true);
die();