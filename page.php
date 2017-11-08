<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

require_once('header.php');

require_once('UKMNorge/Wordpress/Utils/page.class.php');
$WP_TWIG_DATA['page'] = new page();

// SET OPENGRAPH AND SEARCH OPTIMIZATION INFOS
SEO::setTitle( $WP_TWIG_DATA['page']->getPage()->title );
SEO::setDescription( addslashes( preg_replace( "/\r|\n/", "", strip_tags( $WP_TWIG_DATA['page']->getPage()->lead ) ) ) );
SEO::setAuthor( $WP_TWIG_DATA['page']->getPage()->author->display_name );

// CHECK TO FIND CUSTOM PAGE CONTROLLER AND VIEW ISSET
if( isset( $WP_TWIG_DATA['page']->getPage()->meta->UKMviseng ) ) {
	$page_template = $WP_TWIG_DATA['page']->getPage()->meta->UKMviseng;
} else {
	$page_template = false;
}

// SELECT CORRECT TEMPLATE, INCLUDE AND RUN CONTROLLER
switch( $page_template ) {
	## TILHØRENDE MØNSTRINGEN
	# Lokalmønstringer i fylket
	case 'lokalmonstringer':
		require_once('UKMNorge/Wordpress/Controller/fylke/lokalmonstringer.controller.php');
		$view_template = 'Fylke/lokalmonstringer_fullpage';
		break;
	# Påmeldte til mønstringen
	case 'pameldte':
		require_once("UKMNorge/Wordpress/Controller/monstring/pameldte.controller.php");
		break;
	# Mønstringens program
	case 'program':
		require_once('UKMNorge/Wordpress/Controller/program.controller.php');
		break;
	# Kontaktpersoner på mønstringen
	case 'kontaktpersoner':
		$view_template = 'Monstring/kontaktpersoner';
		require_once('UKMNorge/Wordpress/Controller/monstring/kontaktpersoner.controller.php');
		break;

	## HOVEDSIDER
	# Norgeskartet
	case 'dinmonstring':
		$view_template = 'Kart/fullpage';
		break;
	# Vis menyen som side
	case 'hovedmeny':
		$view_template = 'Page/meny';
		break;
	# Vis kontakt-side
	case 'kontakt':
		require_once('UKMNorge/Wordpress/Controller/kontakt.controller.php');
		$view_template = 'Kontaktpersoner/liste';
		break;
		
	## ORGANISASJONEN
	case 'org/logoer':
		$view_template = 'GrafiskProfil/logoer';
		break;
	case 'org/fylkeskontakter':
		require_once('UKMNorge/Wordpress/Controller/fylkeskontakter.controller.php');
		$view_template = 'Kontaktpersoner/fylkeskontakter';
		break;
	# Vis kontakt-side
	case 'org/styret':
		require_once('UKMNorge/Wordpress/Controller/kontakt.controller.php');
		$view_template = 'Page/styret';
		break;
		
	# Standard wordpress-side
	default:
		require_once('UKMNorge/Wordpress/Controller/page.controller.php');
		$view_template = 'Page/fullpage';
		break;
}

if( $page_template == 'meny' || $har_meny ) {
	require_once('UKMNorge/Wordpress/Controller/menu.controller.php');
	$view_template = 'Page/fullpage_with_menu';
}

// RENDER
// echo "Total compile-time: ". (microtime(true) - $time)*1000 ."ms <br />";
// $time = microtime(true);
$out = WP_TWIG::render( $view_template, $WP_TWIG_DATA );
/*echo "Total Twig-rendertime: " . (microtime(true) - $time)*1000 . "ms <br />";*/
echo $out;
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}