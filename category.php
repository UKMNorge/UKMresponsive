<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

require_once('header.php');
require_once('UKMNorge/Wordpress/Utils/posts.class.php');

// FETCH CATEGORY INFOS
$category = get_queried_object();
$WP_TWIG_DATA['category'] = $category;

$posts = new posts();

$WP_TWIG_DATA['posts'] = $posts->getAll();
$WP_TWIG_DATA['page_next'] = $posts->getPageNext();
$WP_TWIG_DATA['page_prev'] = $posts->getPagePrev();

echo WP_TWIG::render( 'Category/list', $WP_TWIG_DATA );
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}