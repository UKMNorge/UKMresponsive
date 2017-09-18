<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');

require_once('UKMNorge/Wordpress/Utils/page.class.php');
$WP_TWIG_DATA['page'] = new page();

// SET OPENGRAPH AND SEARCH OPTIMIZATION INFOS
$SEO->setTitle( $WP_TWIG_DATA['page']->getPage()->title );
$SEO->setDescription( addslashes( preg_replace( "/\r|\n/", "", strip_tags( $WP_TWIG_DATA['page']->getPage()->lead ) ) ) );
$SEO->setAuthor( $WP_TWIG_DATA['page']->getPage()->author->display_name );

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
		// Sjekk om det spørres etter enkeltside eller oversikt.
		$id = $WP_TWIG_DATA['page']->getLastParameter();
		if( is_numeric($id) ) {
			// /pameldte/id/ - i.e. forespørsel om enkelt-innslag. Funker både med og uten slutt-/.
			if(isset($_POST['singleMode']) && "true" == $_POST['singleMode'] ) {
				$WP_TWIG_DATA['singleMode'] = true;
			}
			$view_template = 'Monstring/innslag';
			require_once("UKMNorge/Wordpress/Controller/monstring/deltaker.controller.php");
		}
		else {
			// /pameldte/ - vil altså laste inn oversikten.
			$view_template = 'Monstring/pameldte';
			require_once('UKMNorge/Wordpress/Controller/monstring/deltakere.controller.php');	
		}
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
	# Standard wordpress-side
	default:
		require_once('UKMNorge/Wordpress/Controller/page.controller.php');
		$view_template = 'Page/fullpage';
		break;
		
	## ORGANISASJONEN
	case 'org/logoer':
		$view_template = 'GrafiskProfil/logoer';
		break;
}

// RENDER
// echo "Total compile-time: ". (microtime(true) - $time)*1000 ."ms <br />";
// $time = microtime(true);
$out = WP_TWIG::render( $view_template, $WP_TWIG_DATA );
/*echo "Total Twig-rendertime: " . (microtime(true) - $time)*1000 . "ms <br />";*/
echo $out;

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}