<?php
$DATA['posts'] = array();

// CHECK FOR MOBILE
require_once(THEME_PATH . 'class/mobiledetect.class.php');
$mobileDetect = new Mobile_Detect();
$DATA['isMobile'] = $mobileDetect->isMobile();
// LOAD PAGE DATA
#the_post();
#$DATA['page'] = new WPOO_Post( $post );
$DATA['page'] = [];
if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} elseif ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}

$post_query = 'posts_per_page=7&paged='.$paged;

if( false ) { #$blog_id == 1 ) { Midlertidig fjernet, da vi dobbelt-publiserer til begge kategorier
	$posts = query_posts($post_query.'&cat=-12'); # Fjern UKM-kjendiser fra forsiden av UKM.no
} else {
	$posts = query_posts($post_query);
}

while(have_posts()) {
    the_post();
    $wpoopost = new WPOO_Post($post);
    $metadata = get_post_custom($post->id);
    if( is_array( $metadata ) ) {
    	foreach( $metadata as $key => $val ) {
    		$wpoopost->meta[$key] = $val[0];
    	}
    }
    $DATA['posts'][] = $wpoopost; 
}

$npl = get_next_posts_link();
if($npl) {
    $npl = explode('"',$npl); 
    $DATA['nextpost']=$npl[1];
}

$ppl = get_previous_posts_link();
if($ppl) {
    $ppl = explode('"',$ppl); 
    $DATA['prevpost']=$ppl[1];
}
wp_reset_postdata(); 

$args = array( 
        'child_of' => 20, 
        'parent ' => 20,
        'hierarchical' => 0,
        'sort_column' => 'menu_order', 
        'sort_order' => 'asc'
);
$projects = get_pages( $args );

if($projects) {
    foreach($projects as $project) {
        $DATA['projects'][] = new WPOO_Post($project);
    }
}

require_once('UKM/statistikk.class.php');

$stat = new statistikk();
$stat->setLand();

$total = $stat->getTotal(get_site_option('season'));
$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= get_site_option('season');
$DATA['stat_pameldte'] = $stat;

require_once(THEME_PATH . 'controller/element/nav_page.controller.php');
