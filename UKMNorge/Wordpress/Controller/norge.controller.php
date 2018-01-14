<?php
$WP_TWIG_DATA['in_season'] = date('m') < 5;
if( $WP_TWIG_DATA['in_season'] ) {
	$WP_TWIG_DATA['pamelding_apen'] = date('m') < 4;

	require_once('UKM/statistikk.class.php');
	$stat = new statistikk();
	$stat->setLand();
	$total = $stat->getTotal(get_site_option('season'));
	$WP_TWIG_DATA['pameldte'] = $total['persons'];
}

switch_to_blog( UKM_HOSTNAME == 'ukm.dev' ? 765 : 3449 );
$post_query = '?post_status=publish&posts_per_page=1';
$posts = query_posts($post_query);
global $post;
foreach( $posts as $key => $post ) {
	the_post();
	$WP_TWIG_DATA['news'] = new WPOO_Post( $post );
}