<?php
$blocks = array();
$DATA['blog']['css_extra'][] = 'less/css/festival14.css';

	// JUMBO TOP IMAGE	
	$blocks[] = block_jumbo_image('top',
								  'UKM Media', 
								  ' - et mediehus av og for ungdom', 
								  'https://farm4.staticflickr.com/3839/14564592764_30baef7413_c.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_9f6c78dc01_k.jpg'
								   );

if( !IN_PRODUCTION_ENVIRONMENT ) {
	$blocks[] = block_lead( 'lead', UKM_HOSTNAME=='ukm.no' ? 2 : 5);

	$blocks[] = block_image_oob_left('konsept',
									 UKM_HOSTNAME=='ukm.no' ? 13 : 18,
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_c.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_b.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_b.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_639215e84e_h.jpg'
									);

	
	$blocks[] = block_lead( 'flerkamera', UKM_HOSTNAME=='ukm.no' ? 25 : 5);

	$blocks[] = block_lead_center( 'hdbussene', UKM_HOSTNAME=='ukm.no' ? 27 : 13);

	$blocks[] = block_lead( 'videoreportasjer', UKM_HOSTNAME=='ukm.no' ? 29 : 5);

	$blocks[] = block_lead_center( 'studio', UKM_HOSTNAME=='ukm.no' ? 33 : 13);

	$blocks[] = block_image_oob_right('foto',
									 UKM_HOSTNAME=='ukm.no' ? 35 : 9,
									 'https://farm4.staticflickr.com/3840/14533175723_732541e923_c.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_732541e923_b.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_025ebd9485_h.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_0bf9a226c8_k.jpg'
									);

	$blocks[] = block_lead( 'tekst', UKM_HOSTNAME=='ukm.no' ? 37 : 5);

/*
	// ICONS FOR MORE INFO
	$icons = array();
	$icons[] = media_icon( '#journalist', 'Tekst', 'http://ico.ukm.no/media_illustrations/tekst.jpg' );
	$icons[] = media_icon( '#fotograf', 'Foto', 'http://ico.ukm.no/media_illustrations/tekst.jpg' );
	$icons[] = media_icon( '#video', 'Video', 'http://ico.ukm.no/media_illustrations/tekst.jpg' );
	$icons[] = media_icon( '#flerkamera', 'Flerkamera', 'http://ico.ukm.no/media_illustrations/tekst.jpg' );
	$icons[] = media_icon( '#pr', 'PR og markedsføring', 'http://ico.ukm.no/media_illustrations/tekst.jpg' );
	$blocks[] = container_arrowbox( 
					block_icons( 'icons', $icons, 'Muligheter i UKM Media', 'Redaksjonen er delt inn disse kategoriene - finn hva du kan delta med!' )
				);
*/

/*	$blocks[] = block_lead( 'lead', 3 );

 	
	$blocks[] = block_image_oob_left('flerkamera',
									 5,
									 'https://farm8.staticflickr.com/7344/9293764007_9171a80bd3_z.jpg',
									 'https://farm8.staticflickr.com/7344/9293764007_9171a80bd3_b.jpg',
									 'https://farm8.staticflickr.com/7344/9293764007_181b381064_h.jpg',
									 'https://farm8.staticflickr.com/7344/9293764007_181b381064_h.jpg'
									);
*/
} else {
	// LEAD TEXT (FROM PAGE)
	$blocks[] = block_lead( 'lead', 5);
}
// SEND TO TWIG	
	$DATA['blocks'] = $blocks;


function media_icon( $anchor, $title, $icon_url ) {
	$icon = new stdClass();
	$icon->anchor = $anchor;
	$icon->title = $title;
	$icon->icon = $icon_url;

	return $icon;
}

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