<?php
function container_arrowbox( $content_block ) {
	$block = new stdClass();
	$block->type = 'container';
	$block->container = 'arrowbox';
	$block->contained = $content_block;
	
	return $block;
}
function block_icons( $anchor, $icons, $title=false, $lead=false ) {
	$block = new stdClass();
	$block->anchor = $anchor;
	$block->type = 'icons';
	$block->icons = $icons;
	if( $title ) 
		$block->title = $title;
	if( $lead ) 
		$block->lead = $lead;
	
	return $block;
}

function block_lead_center( $anchor, $post_id ) {
	$block = new stdClass();
	$block->anchor = $anchor;
	$block->type = 'lead_center';
	setup_block_post( $block, $post_id );	
	return $block;
}


function block_lead( $anchor, $post_id ) {
	$block = new stdClass();
	$block->anchor = $anchor;
	$block->type = 'lead';
	setup_block_post( $block, $post_id );	
	return $block;
}

function setup_block_post( &$block, $post_id ) {
	$post = get_post( $post_id );		

	$block->post = new stdClass();
	$block->post->ID = $post_id;
	$block->post->data = new WPOO_Post( $post );
	$block->title = $block->post->data->title;
	if( isset( $block->post->data->meta->lead ) )
		$block->lead = $block->post->data->meta->lead;
	else		
		$block->lead = null;
	$block->content = $block->post->data->content;
}
function block_image_oob_left( $anchor, $post_id, $image_xs, $image_sm, $image_md, $image_lg ) {
	$block = block_jumbo_image($anchor, '', '', $image_xs, $image_sm, $image_md, $image_lg);
	setup_block_post( $block, $post_id );
	$block->type = 'oob_left';
	return $block;
}

function block_image_oob_right( $anchor, $post_id, $image_xs, $image_sm, $image_md, $image_lg ) {
	$block = block_jumbo_image($anchor, '', '', $image_xs, $image_sm, $image_md, $image_lg);
	setup_block_post( $block, $post_id );
	$block->type = 'oob_right';
	return $block;
}

function block_jumbo_image( $anchor, $title, $subtitle, $image_xs, $image_sm, $image_md, $image_lg ) {
	$block = new stdClass();
	$block->anchor = $anchor;
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