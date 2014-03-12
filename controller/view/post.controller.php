<?php
// LOAD PAGE DATA
add_shortcode('gallery', 'image_gallery');

the_post();
$DATA['post'] = new WPOO_Post( $post );


$DATA['post']->blog = new stdClass();
$DATA['post']->blog->link = get_bloginfo('url');
$DATA['post']->blog->name = get_bloginfo('name');


$DATA['post']->author->company_name = 'UKM Norge';

function image_gallery( $attr ) {
	$linkto = $attr['link'];
	$ids	= explode(',', $attr['ids'] );

	$TWIG_DATA = array();	

	foreach( $ids as $photo_id ) {
		$thumb = wp_get_attachment_image_src( $photo_id, 'thumbnail' );
		$large = wp_get_attachment_image_src( $photo_id, 'large');
		if( !$large ) 
			$large = wp_get_attachment_image_src( $photo_id, 'full');
		
		$photo = new stdClass();
		$photo->url 	= new stdClass();

		
		$photo->url->thumb 	= $thumb[0];
		$photo->url->full	= $large[0];
		$photo->width 		= $large[1];
		$photo->height		= $large[2];
		$photo->id 			= $photo_id;
		$TWIG_DATA['photos'][] = $photo;
			
	}
	$TWIG_DATA['album_id'] = uniqid();

	return TWIGrender( 'pre-render/image_album', object_to_array($TWIG_DATA),true);

}

/* TODO: Definere:
    post.author.image
    post.author.company_name
    post.author.facebook_url
*/