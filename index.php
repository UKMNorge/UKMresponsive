<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');

echo is_front_page();

echo WP_TWIG::render( 'Page/meny', $WP_TWIG_DATA );
if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}