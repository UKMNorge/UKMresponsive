<?php
$DATA['posts'] = array();

// LOAD PAGE DATA
the_post();
$DATA['page'] = new WPOO_Post( $post );

$paged = (get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;

$posts = query_posts('posts_per_page=6&paged='.$paged);

while(have_posts()) {
   the_post();
   $DATA['posts'][] = new WPOO_Post($post); 
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


$my_wp_query = new WP_Query();
$all_wp_pages = $my_wp_query->query(array('post_type' => 'page'));

$page = get_page_by_title('Prosjekter');

if($page) {
    $projects = get_page_children($page->ID, $all_wp_pages);
    
    foreach($projects as $project) {
        $DATA['projects'][] = new WPOO_Post($project);
    }
}


require_once(THEME_PATH . 'controller/element/nav_page.controller.php');
