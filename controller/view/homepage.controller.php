<?php
$DATA['posts'] = array();

// LOAD PAGE DATA
the_post();
$DATA['page'] = new WPOO_Post( $post );

if ( get_query_var('paged') ) {
    $paged = get_query_var('paged');
} elseif ( get_query_var('page') ) {
    $paged = get_query_var('page');
} else {
    $paged = 1;
}
$posts = query_posts('posts_per_page=7&paged='.$paged);

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

require_once(THEME_PATH . 'controller/element/nav_page.controller.php');
