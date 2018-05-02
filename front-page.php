<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

require_once('header.php');
require_once('UKMNorge/Wordpress/Utils/page.class.php');
require_once('UKMNorge/Wordpress/Utils/posts.class.php');

SEO::setCanonical( $WP_TWIG_DATA['blog_url'] );

$WP_TWIG_DATA['page'] = new page();
$WP_TWIG_DATA['posts'] = new posts(6);
$WP_TWIG_DATA['page_next'] = get_permalink( get_option( 'page_for_posts' ) );


switch( get_option('site_type') ) {
	case 'fylke':
		require_once('UKMNorge/Wordpress/Controller/monstring/fylke.controller.php');
		break;
	case 'kommune':
		require_once('UKMNorge/Wordpress/Controller/monstring/kommune.controller.php');
		break;
	case 'land':
		$view_template = 'Page/fullpage';
		break;
	case 'ego':
		$view_template = 'Ego/home';
		$section = new stdClass();
		$section->title = 'Redaksjonelt';
		$section->link = Sitemap::getPage('egoego', 'forsiden');
		$WP_TWIG_DATA['section'] = $section;//null; // Fjern section-header på forsiden
#		$WP_TWIG_DATA['HEADER']->logo->url = '//grafikk.ukm.no/profil/ego/EGO_logo.png';
#		$WP_TWIG_DATA['HEADER']->logo->link = Sitemap::getPage('egoego', 'forsiden');
		break;
	case 'organisasjonen':
		$view_template = 'Page/home_organisasjonen';
		$WP_TWIG_DATA['section'] = null; // Fjern section-header på forsiden
		$WP_TWIG_DATA['HEADER']->background->url = '//grafikk.ukm.no/UKMresponsive/img/banner-test-cherry.jpg';
		$WP_TWIG_DATA['HEADER']->background->position = 'top';
		$WP_TWIG_DATA['HEADER']->slogan = WP_CONFIG::get('organisasjonen')['slogan'];
		$WP_TWIG_DATA['HEADER']->button->background = 'rgba(242, 109, 21, 0.44)';
		break;
	# Samtykke-skjema
	case 'samtykke':
		$view_template = 'Samtykke/view';
		require_once('UKMNorge/Wordpress/Controller/samtykke.controller.php');
		break;
	case 'media':
		$view_template = 'Media/home';
		require_once('UKMNorge/Wordpress/Controller/menu.controller.php');
		$WP_TWIG_DATA['HEADER']->background->url = '//ukm.no/media/files/2018/05/2016-06-27-14.57.29-1800x1350.jpg';
		$WP_TWIG_DATA['HEADER']->background->position = 'top';
		$WP_TWIG_DATA['HEADER']->slogan = 'UKM sin medieavdeling - av og for ungdom';
		$WP_TWIG_DATA['HEADER']->button->background = 'rgba(242, 109, 21, 0.44)';
		break;
	case 'gdpr':
	case 'site':
		$view_template = 'Page/fullpage';
		break;
	default:
		$view_template = 'Page/home_norge';
		require_once('UKMNorge/Wordpress/Controller/norge.controller.php');
		
		$WP_TWIG_DATA['HEADER']->background->url = '//grafikk.ukm.no/UKMresponsive/img/banner-test-gul-ish.jpg';
		$WP_TWIG_DATA['HEADER']->background->position = 'top';
		$WP_TWIG_DATA['HEADER']->slogan = WP_CONFIG::get('hvaerukm')['slogan'];
		$WP_TWIG_DATA['HEADER']->button->background = 'rgba(242, 109, 21, 0.44)';
		break;
}
echo WP_TWIG::render( $view_template, $WP_TWIG_DATA );

wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}