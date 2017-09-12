<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');
require_once('UKMNorge/Wordpress/Utils/page.class.php');
require_once('UKMNorge/Wordpress/Utils/posts.class.php');

$WP_TWIG_DATA['page'] = new page();
$WP_TWIG_DATA['posts'] = new posts(6);
$WP_TWIG_DATA['page_next'] = get_permalink( get_option( 'page_for_posts' ) );


switch( get_option('site_type') ) {
	case 'fylke':
		require_once('UKMNorge/Wordpress/Controller/fylke/frontpage.controller.php');
		break;
	case 'kommune':
#		require_once('UKMNorge/Wordpress/Controller/fylke/frontpage.controller.php');
		break;
	case 'land':
		$view_template = 'Page/home_norge';
		break;
	case 'ego':
		$view_template = 'Ego/home';
		break;
	default:
		$view_template = 'Page/home_norge';
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