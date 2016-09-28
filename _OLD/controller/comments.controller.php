<?php

// TEMP FIX: Tillat kommentarer på ukm.no, media.ukm.no og meta.ukm.no
// Når neste sesong opprettes bør kommentarer slås av for alle nye blogger.
// $blogs_with_comments kan da fjernes

$blogs_with_comments = array(1,2175,2174);

if( (UKM_HOSTNAME == 'ukm.no' && in_array($blog_id, $blogs_with_comments) && comments_open() ) || (UKM_HOSTNAME != 'ukm.no' && comments_open()) ) {
#if( comments_open() ) {
	require_once('WPOO/WPOO/Comment.php');
	if( $user_ID ) {
		$DATA['comments']['user']['name'] = $user_identity;
	} else {
		$DATA['comments']['user'] = false;
	}
	$DATA['comments']['list'] = $DATA['post']->comments();
} else {
	$DATA['comments'] = false;
}