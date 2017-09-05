<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');

require_once('class/page.class.php');
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
	case 'dinmonstring': # NORGESKARTET
		$view_template = 'Kart/fullpage';
		break;
	case 'pameldte':
		$view_template = 'Monstring/deltakere';
		require_once('controller/monstring/deltakere.controller.php');
		break;
	case 'kontaktpersoner':
		$view_template = 'Fylke/kontaktpersoner';
		require_once('controller/kontaktpersoner.controller.php');
		break;
	default:
		$view_template = 'Page/meny';
		break;
}

// RENDER
echo WP_TWIG::render( $view_template, $WP_TWIG_DATA );

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}