<?php
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL ^ E_DEPRECATED);
if( $_SERVER['HTTP_HOST'] == 'ukm.no' )
	ini_set('display_errors',0);
else
	ini_set('display_errors',1);

setlocale(LC_ALL, 'nb_NO', 'nb', 'no');


define('THEME_PATH', get_theme_root().'/UKMresponsive/' );
define('THEME_DEFAULT_IMAGE', 'http://grafikk.ukm.no/placeholder/post_placeholder.png');
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
	$DATA['url']['blog']		= get_bloginfo('url').'/';
	$DATA['url']['current']		= get_permalink();

	$DATA['blog']['url']		= get_bloginfo('url').'/';
	$DATA['blog']['name']		= get_bloginfo('name');


	$DATA['console'] = array();
	
	$DATA['placeholder']['post'] = THEME_DEFAULT_IMAGE;
	require_once('class/seo.class.php');
	$SEO = new SEO( $DATA['url']['current'] );
/**********************************
* 
**********************************/
	require_once('controller/nav_top.controller.php');
	
	$DATA['top_page'] = get_option('ukm_top_page');
	$SEO->section( $DATA['top_page'] );
/**********************************
* SWITCH VIEW
**********************************/
	require_once(THEME_PATH .'controller/breadcrumbs.controller.php');

	if( is_archive() ) {
		if(is_author()) {
    		require_once('controller/view/author.controller.php');
    		$VIEW = 'author';
		}
		else {
    		$VIEW = 'archive';
        }
	    require_once('controller/view/archive.controller.php');
   		$SEO->title( $DATA['jumbo']->header );
   		$SEO->description( 'Alle innlegg fra '. $DATA['jumbo']->content );
	} elseif( is_single() ) {
		require_once('controller/view/post.controller.php');
		require_once('controller/element/comments.controller.php');
		$VIEW = 'post';
		$BC->add( $DATA['url']['current'], 'artikkel' );
		$SEO->post( $DATA['post'] );
		$SEO->article( $DATA['post'] );
			
	} elseif( is_front_page() ) {
		wp_reset_query();
		wp_reset_postdata();
		$SEO->jumbo( $post->ID );
		$JUMBO_POST_ID = $post->ID;
		require('controller/element/jumbo.controller.php');

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
			require_once('controller/element/kontakt.controller.php');
			require_once('controller/view/fylke.controller.php');
		} elseif( get_option('site_type') == 'kommune' ) {
            $VIEW = 'kommune';
			require_once('controller/element/kontakt.controller.php');
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
				$BC->home('derdubor');
				$SEO->jumbo( $post->ID );
				$SEO->setImage( 'http://ukm.no/wp-content/uploads/2011/08/kart1.jpg' );
				break;
/*
            case 'styrerommet':
                require_once('controller/view/styrerommet.controller.php');
                $VIEW = 'styrerommet';
                break;
*/
            case 'urg':
				require_once('controller/view/post.controller.php');
            	require_once('controller/view/urg.controller.php');
            	$VIEW = 'urg';
				$SEO->jumbo( $post->ID );
            	break;
            case 'fylkeskontaktene':
				require_once('controller/view/post.controller.php');
            	require_once('controller/view/fylkeskontaktene.controller.php');
            	$VIEW = 'fylkeskontaktene';
				$SEO->jumbo( $post->ID );
            	break;
            case 'kontakt_start':
				require_once('controller/view/post.controller.php');
            	$VIEW = 'kontakt_start';
				$SEO->jumbo( $post->ID );
            	break;
            case 'kontakt':
				require_once('controller/view/post.controller.php');
				require_once('controller/view/kontakt.controller.php');
            	$VIEW = 'kontakt';
				$SEO->jumbo( $post->ID );
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
				$BC->add( $DATA['url']['current'], $DATA['post']->title);
				$SEO->post( $post );
				break;
		}
		if(isset( $DATA['jumbo'] ) && $BC->addJumbo )
			$BC->add( $DATA['url']['current'], $DATA['jumbo']->header );
/*
		elseif( isset( $DATA['post'] ) && isset( $DATA['post']->title ))
			$BC->add( $DATA['url']['current'], $DATA['post']->title);
*/
	} else {
		require_once('controller/view/404.controller.php');
		$VIEW = '404';
		$DATA['jumbo'] = (object) array('header' => 'Siden ikke funnet!', 'content' => 'Såkalt 404 altså');
		$BC->add( $DATA['url']['current'], 'side ikke funnet');
	}
	
if( !isset( $DATA['jumbo'] ) ) {
	$JUMBO_POST_ID = $post->ID;
	require_once('controller/element/jumbo.controller.php');
}

$DATA['breadcrumbs'] = $BC->get();

// Opprinnelig er title page-spesific, uten breadcrumbs. Overskriver dette her
	$seoTitle = '';
	foreach( $DATA['breadcrumbs']->crumbs as $c ) {
		$title = $c->title == 'artikkel' ?  $DATA['post']->title : $c->title;
		
		if ($c === end($DATA['breadcrumbs']->crumbs))
			$seoTitle .= $title;
		else 
			$seoTitle .= $title .' &raquo; ';
	}
	$SEO->title( $seoTitle );

$DATA['SEO'] = $SEO;
echo TWIGrender('view/'.$VIEW, object_to_array($DATA),true);

echo TWIGrender('console', $DATA, true);
/*
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;}</style>';
}
*/
die();