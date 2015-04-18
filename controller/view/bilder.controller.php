<?php
global $blog_id;
$monstringen = new monstring(get_option('pl_id'));
$hendelser = $monstringen->forestillinger();

$data = array();

foreach( $hendelser as $hendelsen ) {
	$hendelse = new forestilling( $hendelsen['c_id'] );
	$alle_innslag = $hendelse->innslag();
	
	$data_hendelse = new stdClass();
	$data_hendelse->ID = $hendelse->g('c_id');
	$data_hendelse->navn = $hendelse->g('c_name');
	$data_hendelse->innslag = array();
	
	foreach( $alle_innslag as $innslaget ) {
		$innslag = new innslag( $innslaget['b_id'] );
		$media = $innslag->related_items();
		$images = $media['image'];

		$data_innslag = new stdClass();
		$data_innslag->ID = $innslag->g('b_id');
		$data_innslag->navn = $innslag->g('b_name');
		$data_innslag->bilder = array();

		foreach( $images as $image ) {
			if(!is_array($image['post_meta']['sizes']['large']))
				continue;
			if($blog_id !== $image['blog_id'])
				continue;

			$data_bilde = new stdClass();
			$data_bilde->url 	= $image['blog_url'].'/files/'.$image['post_meta']['sizes']['large']['file'];
			$data_bilde->thumb	= $image['blog_url'].'/files/'.$image['post_meta']['sizes']['thumbnail']['file'];
			$data_bilde->foto	= $image['post_meta']['author'];
			
			$data_innslag->bilder[] = $data_bilde;
		}
		$data_hendelse->innslag[] = $data_innslag;
	}
	$data[] = $data_hendelse;
}

$DATA['hendelser'] = $data;