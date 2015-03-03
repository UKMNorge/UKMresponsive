<?php
$blocks = array();
$DATA['blog']['css_extra'][] = 'less/css/festival14.css';
	// JUMBO TOP IMAGE	
	$blocks[] = block_jumbo_image('UKM Media', 
								  ' - et mediehus av og for ungdom', 
								  'https://farm4.staticflickr.com/3839/14564592764_30baef7413_c.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_9f6c78dc01_k.jpg'
								   );
	// LEAD TEXT (FROM PAGE)
	$blocks[] = block_lead(3);
	// ICONS FOR MORE INFO
	$blocks[] = container_arrowbox( 
					block_icons( array(array('name'=>'icon1')) )
				);
	
// SEND TO TWIG	
	$DATA['blocks'] = $blocks;


function container_arrowbox( $content_block ) {
	$block = new stdClass();
	$block->type = 'container';
	$block->container = 'arrowbox';
	$block->contained = $content_block;
	
	return $block;
}
function block_icons( $icons ) {
	$block = new stdClass();
	$block->type = 'icons';
	$block->icons = $icons;
	
	return $block;
}

function block_lead( $post_id ) {
	$post = get_post( $post_id );		

	$block = new stdClass();
	$block->type = 'lead';
	$block->post = new stdClass();
	$block->post->ID = $post_id;
	$block->post->data = new WPOO_Post( $post );
	$block->title = $block->post->data->title;
	$block->lead = $block->post->data->meta->lead;
	$block->content = $block->post->data->content;
	
	return $block;
}

function block_jumbo_image( $title, $subtitle, $image_xs, $image_sm, $image_md, $image_lg ) {
	$block = new stdClass();
	$block->type = 'jumbo_image';
	$block->image = new stdClass();
	$block->image->xs = $image_xs;
	$block->image->sm = $image_sm;
	$block->image->md = $image_md;
	$block->image->lg = $image_lg;
	$block->title = $title;
	$block->subtitle = $subtitle;
	return $block;
}