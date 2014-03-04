<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors',1);

setlocale(LC_ALL, 'nb_NO', 'nb', 'no');


define('THEME_PATH', get_theme_root().'/UKMresponsive/' );
define('TWIG_PATH', __DIR__ );

require_once('vendor/autoload.php');
require_once('WPOO/WPOO/Post.php');
require_once('WPOO/WPOO/Author.php');
require_once('UKM/inc/twig-admin.inc.php');
require_once('functions_theme.php');


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
	
	$DATA['top_page'] = get_option('ukm_top_page');
/**********************************
* SWITCH VIEW
**********************************/
	if( is_archive() ) {
		if(is_author()) {
    		require_once('controller/view/author.controller.php');
    		$VIEW = 'author';
		}
		else {
    		$VIEW = 'archive';
        }
	    require_once('controller/view/archive.controller.php');

	} elseif( is_single() ) {
		require_once('controller/view/post.controller.php');
		require_once('controller/element/comments.controller.php');
		$VIEW = 'post';
	} elseif( is_front_page() ) {
		if( get_option('ukm_top_page') == 'ambassadorer' ) {
			require_once('controller/view/homepage.controller.php');
			$VIEW = 'homepage_ambassador';      
		} elseif( get_option('ukm_top_page') == 'arrangorer' ) {
			require_once('controller/view/arrangorlogon.controller.php');
			$VIEW = 'homepage_arrangorer';
		} elseif( get_option('ukm_top_page') == 'internasjonalt' ) {
			require_once('controller/view/homepage.controller.php');
			$VIEW = 'homepage_internasjonalt';      
		} elseif( get_option('site_type') == 'fylke' ) {
            $VIEW = 'fylke';
			require_once('controller/view/fylke.controller.php');
		} elseif( get_option('site_type') == 'kommune' ) {
            $VIEW = 'kommune';
			require_once('controller/view/kommune.controller.php');
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
            case 'styrerommet':
                require_once('controller/view/styrerommet.controller.php');
                $VIEW = 'styrerommet';
                break;
            case 'urg':
				require_once('controller/view/post.controller.php');
            	require_once('controller/view/urg.controller.php');
            	$VIEW = 'urg';
            	break;
            case 'fylkeskontaktene':
				require_once('controller/view/post.controller.php');
            	require_once('controller/view/fylkeskontaktene.controller.php');
            	$VIEW = 'fylkeskontaktene';
            	break;
            case 'kontakt_start':
				require_once('controller/view/post.controller.php');
            	$VIEW = 'kontakt_start';
            	break;
            case 'kontakt':
				require_once('controller/view/post.controller.php');
				require_once('controller/view/kontakt.controller.php');
            	$VIEW = 'kontakt';
            	break;
            case 'program':
				require_once('controller/element/innslag.controller.php');
            	require_once('controller/view/program.controller.php');
            	break;
            case 'pameldte':
				require_once('controller/element/innslag.controller.php');
            	require_once('controller/view/pameldte.controller.php');
            	$VIEW = 'pameldte';
            	break;

			default:
				require_once('controller/view/post.controller.php');
				$VIEW = 'page';
				break;
		}
	} else {
		require_once('controller/view/404.controller.php');
		$VIEW = '404';
		$DATA['jumbo'] = (object) array('header' => 'Siden ikke funnet!', 'content' => 'Såkalt 404 altså');

	}
	
if( !isset( $DATA['jumbo'] ) ) {
	$JUMBO_POST_ID = $post->ID;
	require_once('controller/element/jumbo.controller.php');
}

echo TWIGrender('view/'.$VIEW, object_to_array($DATA),true);
/*
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;}</style>';
}
*/
die();