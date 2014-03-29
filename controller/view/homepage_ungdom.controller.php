<?php
$austagder = fylke(1168, 'Aust-Agder', 'http://ukm.no/aust-agder/');
$finnmark = fylke(1214, 'Finnmark', 'http://ukm.no/finnmark/');
$rogaland = fylke(1398, 'Rogaland', 'http://ukm.no/rogaland/');

$nordtrondelag = fylke(1367, 'Nord-Trøndelag', 'http://ukm.no/nord-trondelag/');
$moreogromsdal = fylke(1352, 'Møre og Romsdal', 'http://ukm.no/moreogromsdal/');


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
	
		$rogaland->cover->portrait		= 'http://farm8.staticflickr.com/7298/9136822921_67bb31bd35_c_d.jpg';
		$rogaland->cover->landscape		= 'http://farm6.staticflickr.com/5534/9147533122_93a51c4138_c_d.jpg';

		$moreogromsdal->cover->portrait	= 'http://ukm.no/moreogromsdal/files/2014/03/2014_3225_1566-836x1024.jpg';
		$moreogromsdal->cover->landscape= 'http://ukm.no/pl3476/files/2014/02/2014_3476_767-1024x682.jpg';

		$nordtrondelag->cover->portrait	= 'http://ukm.no/pl3439/files/2014/01/2014_3439_198-1024x682.jpg';
		$nordtrondelag->cover->landscape= 'http://ukm.no/pl3457/files/2014/02/2014_3457_624-1024x678.jpg';
	
		// LENKE TIL LIVESENDINGER
		$austagder->live->link			= 'https://new.livestream.com/accounts/183084/events/2842026';
		$finnmark->live->link			= 'https://new.livestream.com/accounts/183084/events/1946553';
		$rogaland->live->link			= 'https://new.livestream.com/accounts/183084/events/2857743';
		$moreogromsdal->live->link		= 'https://new.livestream.com/accounts/183084/events/2875672';
		$nordtrondelag->live->link		= 'http://mediestudent.no/ukm2014_3/videostream/';
	
		// LIVESENDINGER AKKURAT NÅ?
		$austagder->live->now			= false;
		$finnmark->live->now			= false;
		$rogaland->live->now			= false;
		$moreogromsdal->live->now		= true;
		$nordtrondelag->live->now		= true;
		
		// POSTS
		$austagder->posts[] = 462;
		$austagder->posts[] = 204;
		$austagder->posts[] = 258;
		
		$finnmark->posts[]	= 248;
		$finnmark->posts[]	= 288;
		$finnmark->posts[]	= 246;

		$rogaland->posts[]	= 24;

		$moreogromsdal->posts[]	= 91;
		$moreogromsdal->posts[]	= 164;
		$moreogromsdal->posts[]	= 151;
		
		$nordtrondelag->posts[]	= 14;
		$nordtrondelag->posts[]	= 31;
	
	
		// FYLKESMONSTRINGER (REKKEFØLGE)
		$fylkesmonstringer[] = $moreogromsdal;
		$fylkesmonstringer[] = $nordtrondelag;
		$fylkesmonstringer[] = $rogaland;
		$fylkesmonstringer[] = $finnmark;
		$fylkesmonstringer[] = $austagder;

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
