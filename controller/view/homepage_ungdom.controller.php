<?php
$austagder = fylke(1168, 'Aust-Agder', 'http://ukm.no/austagder/');
$finnmark = fylke(1214, 'Finnmark', 'http://ukm.no/finnmark/');


////////////////////////////////
// JOHANNES START


	//////////////////////////////////////////////////////
	// FØRSTE HELG
	//////////////////////////////////////////////////////
		// COVER PHOTOS
		$austagder->cover->portrait		= 'http://ukm.no/aust-agder/files/2014/03/DSC_0012.jpg';
		$austagder->cover->landscape	= 'http://ukm.no/aust-agder/files/2014/02/Sequence-01.Still0011.jpg';
	
		$finnmark->cover->portrait		= 'http://ukm.no/pl3123/files/2014/02/2014_3123_674-1024x682.jpg';
		$finnmark->cover->landscape		= 'http://ukm.no/finnmark/files/2014/03/Uten-navn.png';
	
		// LENKE TIL LIVESENDINGER
		$austagder->live->link			= 'https://new.livestream.com/accounts/183084/events/2842026';
		$finnmark->live->link			= 'https://new.livestream.com/accounts/183084/events/1946553';
	
		// LIVESENDINGER AKKURAT NÅ?
		$austagder->live->now			= true;
		$finnmark->live->now			= true;
		
		// POSTS
		$austagder->posts[] = 462;
		$austagder->posts[] = 204;
		$austagder->posts[] = 258;
		
		$finnmark->posts[]	= 248;
		$finnmark->posts[]	= 91;
		$finnmark->posts[]	= 54;
	
	
		// FYLKESMONSTRINGER (REKKEFØLGE)
		$fylkesmonstringer[] = $austagder;
		$fylkesmonstringer[] = $finnmark;
////////////////////////////////
// JOHANNES SLUTT

if( $paged == 1 ) {
	global $post_id;
	$PAGE_POST 	= $post;
	$POST_ID	= $post_id;
	
	foreach( $fylkesmonstringer as $fm ) {
		switch_to_blog( $fm->ID );
		foreach( $fm->posts as $post_id ) {
			$post	= get_post( $post_id );
			@$WPOO_post	= new WPOO_Post( $post );
	
			$fm->postdata[] = $WPOO_post;
		}
		restore_current_blog();
	}
	
	$post 		= $PAGE_POST;
	$post_id	= $POST_ID;
	
	
	$DATA['fylkesmonstringer'] = $fylkesmonstringer;
}


require_once('UKM/statistikk.class.php');

$stat = new statistikk();
$stat->setLand();
$total = $stat->getTotal(get_option('season'));

$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= get_option('season');

$DATA['stat_pameldte'] = $stat; 



function fylke( $blog_id, $fylkenavn, $link ) {
	$fylke = new stdClass();
	$fylke->ID		= $blog_id;
	$fylke->link	= $link;
	$fylke->title	= 'Fylkesmønstringen i '. $fylkenavn;
	$fylke->posts	= array();
	$fylke->cover 	= new stdClass();
	$fylke->live	= new stdClass();
	return $fylke;
}
