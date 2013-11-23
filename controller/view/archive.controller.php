<?php
$DATA['posts'] = array();

// LOAD PAGE DATA
the_post();
$DATA['page'] = new WPOO_Post( $post );

// LOAD POSTS
$posts_array = get_posts( 'posts_per_page=12' );
foreach( $posts_array as $post ) {
	$DATA['posts'][] = new WPOO_Post( $post );
}