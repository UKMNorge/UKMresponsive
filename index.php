<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');

echo is_front_page();

echo WP_TWIG::render( 'Page/meny', $WP_TWIG_DATA );
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}