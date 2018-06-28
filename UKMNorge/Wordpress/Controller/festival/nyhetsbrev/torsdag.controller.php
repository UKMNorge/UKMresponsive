<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

#$WP_TWIG_DATA['HEADER']->background->url = 'http://ukm.dev/festivalen/wp-content/uploads/sites/60/2018/06/Screen-Shot-2018-06-19-at-21.11.18.png';#'//grafikk.ukm.no/UKMresponsive/img/festival/forsidebilde.jpg';
$WP_TWIG_DATA['HEADER']->hideSection = true;
$WP_TWIG_DATA['HEADER']->hideLogo = true;

$image = new SEOImage( str_replace('http:','https:', $WP_TWIG_DATA['HEADER']->background->url ) );
SEO::setImage( $image );

require_once(get_template_directory() . '/UKMNorge/Wordpress/Utils/posts.class.php');

$posts = new posts( null, true );
$posts->setCategory( 1 );
$posts->loadPosts();

//	$WP_TWIG_DATA['kategori'] = get_category( 1 );
$WP_TWIG_DATA['posts'] = $posts;

require_once('UKM/tv.class.php');
// KORSLAGET-TEASER
$WP_TWIG_DATA['openingVideo'] = new TV(12984);
$WP_TWIG_DATA['landsbyVideo'] = new TV(12987);