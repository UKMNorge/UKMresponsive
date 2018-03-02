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

/* PUSH TO FRONT */
if( function_exists('UKMpush_to_front_load_all_fm_data') ) {	
	if( (int) date('m') > 2 && (int) date('m') < 6 ) {
		$week = (int) date('W');
		$WP_TWIG_DATA['PtF'] = [
			'current' => UKMpush_to_front_load_all_fm_data( date('Y'), $week ),
			'previous' => (int) date('N') > 4 ? false : UKMpush_to_front_load_all_fm_data( date('Y'), $week-1 ), // Forrige uke vises tom onsdag
		];
	}
}