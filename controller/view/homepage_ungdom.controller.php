<?php
$austagder = fylke(1168, 'Aust-Agder', 'http://ukm.no/aust-agder/');
$finnmark = fylke(1214, 'Finnmark', 'http://ukm.no/finnmark/');
$rogaland = fylke(1398, 'Rogaland', 'http://ukm.no/rogaland/');

$nordtrondelag = fylke(1367, 'Nord-Trøndelag', 'http://ukm.no/nord-trondelag/');
$moreogromsdal = fylke(1352, 'Møre og Romsdal', 'http://ukm.no/moreogromsdal/');

$akershus 			= fylke(1151, 'Akershus', 'http://ukm.no/akershus/');
$nordland			= fylke(1370, 'Nordland', 'http://ukm.no/nordland/');
$hordaland			= fylke(1279, 'Hordaland', 'http://ukm.no/hordaland/');
$telemark			= fylke(1470, 'Telemark', 'http://ukm.no/telemark');
$oslo				= fylke(1385, 'Oslo', 'http://ukm.no/oslo/');
$troms				= fylke(1477, 'Troms', 'http://ukm.no/troms/');
$hedmark			= fylke(1266, 'Hedmark', 'http://ukm.no/hedmark/');
$sognogfjordane		= fylke(1433, 'Sogn og Fjordane', 'http://ukm.no/sognogfjordane/');

////////////////////////////////
// JOHANNES START

	//////////////////////////////////////////////////////
	// 3. HELG
	//////////////////////////////////////////////////////

	//// AKERSHUS
		// COVER PHOTOS
		$akershus->cover->portrait		= 'http://ukm.no/akershus/files/2014/03/Zapp-On-2.jpg';
		$akershus->cover->landscape		= 'http://ukm.no/akershus/files/2014/03/Zapp-On-2.jpg';
		// LENKE TIL LIVESENDINGER
		$akershus->live->link			= 'https://new.livestream.com/accounts/183084/events/2891485';
		// LIVESENDINGER AKKURAT NÅ?
		$akershus->live->now			= true;		
		// POSTS
		$akershus->posts[] = 65;
		
	//// NORDLAND
		// COVER PHOTOS
		$nordland->cover->portrait		= 'http://ukm.no/pl3428/files/2014/02/2014_3428_690-1024x682.jpg';
		$nordland->cover->landscape		= 'http://ukm.no/pl3428/files/2014/02/2014_3428_690-1024x682.jpg';
		// LENKE TIL LIVESENDINGER
		$nordland->live->link			= 'https://new.livestream.com/accounts/183084/events/2891505';
		// LIVESENDINGER AKKURAT NÅ?
		$nordland->live->now			= true;		
		// POSTS
		$nordland->posts[] = 46;
		
	//// HORDALAND
		// COVER PHOTOS
		$hordaland->cover->portrait		= 'http://ukm.no/pl3493/files/2014/01/2014_3493_133-822x1024.jpg';
		$hordaland->cover->landscape		= 'http://ukm.no/pl3493/files/2014/01/2014_3493_133-822x1024.jpg';
		// LENKE TIL LIVESENDINGER
		$hordaland->live->link			= 'https://new.livestream.com/accounts/183084/events/2891511';
		// LIVESENDINGER AKKURAT NÅ?
		$hordaland->live->now			= true;		
		// POSTS
		$hordaland->posts[] = 33;

	//// TELEMARK
		// COVER PHOTOS
		$telemark->cover->portrait		= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/5.EBAS_22321-1024x682.jpg';
		$telemark->cover->landscape		= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/6.eirillTigerguttJensen20130624_0365-1024x682.jpg';
		// LENKE TIL LIVESENDINGER
		$telemark->live->link			= 'https://new.livestream.com/accounts/183084/events/2891521';
		// LIVESENDINGER AKKURAT NÅ?
		$telemark->live->now			= true;		
		// POSTS
		$telemark->posts[] = 1;

	//// OSLO
		// COVER PHOTOS
		$oslo->cover->portrait			= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/10.Sara_bf_2833_1-682x1024.jpg';
		$oslo->cover->landscape			= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/14.Magnus_og_Ayla_2891-1024x682.jpg';
		// POSTS
#		$oslo->posts[] = null;

	//// TROMS
		// COVER PHOTOS
		$troms->cover->portrait			= 'http://ukm.no/troms/files/2014/03/2014_3217_2595-1024x661.jpg';
		$troms->cover->landscape		= 'http://ukm.no/troms/files/2014/04/2014_3217_2633-1024x683.jpg';
		// LENKE TIL LIVESENDINGER
		$troms->live->link				= 'https://new.livestream.com/accounts/183084/ukmtroms2014';
		// LIVESENDINGER AKKURAT NÅ?
		$troms->live->now				= true;		
		// POSTS
		$troms->posts[] = 107;
		$troms->posts[] = 188;
		$troms->posts[] = 198;

	//// HEDMARK
		// COVER PHOTOS
		$hedmark->cover->portrait		= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/11.Mathias_ditlefsen_18401-1024x682.jpg';
		$hedmark->cover->landscape		= 'http://2013.ukm.no/ukm.no/festivalen/files/2013/06/11.Mathias_ditlefsen_18401-1024x682.jpg';
		// LENKE TIL LIVESENDINGER
		$hedmark->live->link			= 'https://new.livestream.com/accounts/183084/events/2891532';
		// LIVESENDINGER AKKURAT NÅ?
		$hedmark->live->now				= true;		
		// POSTS
		$hedmark->posts[] = 342;

	//// SOGN OG FJORDANE
		// COVER PHOTOS
		$sognogfjordane->cover->portrait	= 'http://ukm.no/pl3384/files/2014/03/2014_3384_2097-1024x679.jpg';
		$sognogfjordane->cover->landscape	= 'http://ukm.no/pl3384/files/2014/03/2014_3384_2097-1024x679.jpg';
		// LENKE TIL LIVESENDINGER (FINN NRK-lenke)
		$sognogfjordane->live->link			= 'http://www.nrk.no/sognogfjordane/folg-ukm-direkte-i-nett-tv-1.11649073';
		// LIVESENDINGER AKKURAT NÅ?
		$sognogfjordane->live->now			= false;		
		// POSTS
#		$sognogfjordane->posts[] = null;







	// FYLKESMONSTRINGER (REKKEFØLGE)
		$fylkesmonstringer[] = $akershus;
		$fylkesmonstringer[] = $troms;
		$fylkesmonstringer[] = $nordland;
		$fylkesmonstringer[] = $hordaland;
		$fylkesmonstringer[] = $telemark;
#		$fylkesmonstringer[] = $oslo;
		$fylkesmonstringer[] = $hedmark;
#		$fylkesmonstringer[] = $sognogfjordane;



	//////////////////////////////////////////////////////
	// 2 FØRSTE HELGER
	//////////////////////////////////////////////////////
		// COVER PHOTOS
		$austagder->cover->portrait		= 'http://ukm.no/aust-agder/files/2014/03/DSC_0012.jpg';
		$austagder->cover->landscape	= 'http://ukm.no/aust-agder/files/2014/02/Sequence-01.Still0011.jpg';
	
		$finnmark->cover->portrait		= 'http://ukm.no/pl3123/files/2014/02/2014_3123_674-1024x682.jpg';
		$finnmark->cover->landscape		= 'http://ukm.no/finnmark/files/2014/03/Uten-navn.png';
	
		$rogaland->cover->portrait		= 'http://farm8.staticflickr.com/7298/9136822921_67bb31bd35_c_d.jpg';
		$rogaland->cover->landscape		= 'http://farm6.staticflickr.com/5534/9147533122_93a51c4138_c_d.jpg';

		$moreogromsdal->cover->portrait	= 'http://ukm.no/moreogromsdal/files/2014/03/IMG_3309-1024x682.jpg';
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
		$moreogromsdal->live->now		= false;
		$nordtrondelag->live->now		= false;
		
		// POSTS
		$austagder->posts[] = 462;
		$austagder->posts[] = 204;
		$austagder->posts[] = 258;
		
		$finnmark->posts[]	= 248;
		$finnmark->posts[]	= 288;
		$finnmark->posts[]	= 246;

		$rogaland->posts[]	= 24;

		$moreogromsdal->posts[]	= 189;
		$moreogromsdal->posts[]	= 164;
		$moreogromsdal->posts[]	= 227;
		
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
