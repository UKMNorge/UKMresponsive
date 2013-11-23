<?php

$DATA['page']['title'] = single_cat_title('', false);

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		$DATA['posts'][] = new WPOO_Post( $post );
	}
}