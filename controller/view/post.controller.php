<?php
// LOAD PAGE DATA
add_shortcode('gallery', 'image_gallery');

the_post();
$DATA['post'] = new WPOO_Post( $post );

if ( function_exists( 'get_coauthors' ) ) {
    $coauthors = get_coauthors($post->id);
    $wpoo_authors = array();
    foreach($coauthors as $author) {
        $wpoo_authors[] = new WPOO_Author($author);
    }
    $DATA['coauthors'] = $wpoo_authors;
}


$DATA['post']->blog = new stdClass();
$DATA['post']->blog->link = get_bloginfo('url');
$DATA['post']->blog->name = get_bloginfo('name');

$metadata = get_post_custom($post->id);

if( is_array( $metadata ) ) {
	foreach( $metadata as $key => $val ) {
		$DATA['post']->meta[$key] = $val[0];
	}
}

if (get_option("site_type") != "kommune" && get_option("site_type") != "fylke") {

	require_once('UKM/statistikk.class.php');

	$stat = new statistikk();
	$stat->setLand();

	$total = $stat->getTotal(get_site_option('season'));
	$stat = new stdClass();
	$stat->tall 	= $total['persons'];
	$stat->til		= get_site_option('season');
	$DATA['stat_pameldte'] = $stat;
}

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