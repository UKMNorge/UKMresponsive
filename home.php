<?php

use UKMNorge\DesignBundle\Utils\Sitemap;

require_once('header.php');

$page = new stdClass();


$WP_TWIG_DATA['page'] = $page;


#echo Sitemap::getSections();
echo WP_TWIG::render( 'Page/home_norge', $WP_TWIG_DATA );