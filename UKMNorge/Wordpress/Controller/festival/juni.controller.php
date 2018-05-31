<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

$WP_TWIG_DATA['HEADER']->background->url = '//ukm.no/wp-content/themes/UKMresponsive/UKMNorge/DesignBundle/Resources/views/Festival/forsidebilde.jpg';
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