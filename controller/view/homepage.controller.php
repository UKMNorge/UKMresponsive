<?php
$DATA['posts'] = array();

// LOAD PAGE DATA
the_post();
$DATA['page'] = new WPOO_Post( $post );


$posts = query_posts('posts_per_page=6');

while(have_posts()) {
   the_post();
   $DATA['posts'][] = new WPOO_Post($post); 
} 


require_once(THEME_PATH . 'controller/element/nav_page.controller.php');