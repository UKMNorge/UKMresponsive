<?php
$DATA['posts'] = array();

// LOAD PAGE DATA
the_post();
$DATA['page'] = new wp_get_post( $post );

// LOAD POSTS
$posts_array = get_posts( 'posts_per_page=6' );
foreach( $posts_array as $post ) {
	$DATA['posts'][] = new wp_get_post( $post );
}

require_once(THEME_PATH . 'controller/element/nav_page.controller.php');