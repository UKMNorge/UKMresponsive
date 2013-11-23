<?php
require_once('UKM/monstring.class.php');

$DATA['fylke'] = get_option('fylke');

// HENT ALLE POSTS
	$DATA['posts'] = array();
	
	// LOAD PAGE DATA
	the_post();
	$DATA['page'] = new WPOO_Post( $post );
	
	// LOAD POSTS
	$posts_array = get_posts( 'posts_per_page=6' );
	foreach( $posts_array as $post ) {
		$DATA['posts'][] = new WPOO_Post( $post );
	}
	
// INFO OM MØNSTRINGEN
	$pl = new monstring( get_option('pl_id') );
	$monstring = new StdClass();
	$monstring->starter = $pl->get('pl_start');
	$monstring->slutter = $pl->get('pl_stop');
	$monstring->sted = $pl->get('pl_place');
	$DATA['monstring'] = $monstring;

// INFO OM LOKALMØNSTRINGER
	$kommuner_i_fylket = $pl->get('kommuner_i_fylket');
	$forste = 0;
	$siste = 0;
	$siste_pamelding = 0;
	
	foreach( $kommuner_i_fylket as $kommune_id => $kommune_navn ) {
		$lokalmonstring = new kommune_monstring( $kommune_id, get_option('season') );
		$lokalmonstring = $lokalmonstring->monstring_get();
		
		if($forste == 0) {
			$forste = $lokalmonstring->get('pl_start');
			$siste = $lokalmonstring->get('pl_stop');
		}
		
		if( $lokalmonstring->get('pl_start') <  $forste ) {
			$forste = $lokalmonstring->get('pl_start');
		}
		
		if( $lokalmonstring->get('pl_stop') > $siste ) {
			$siste = $lokalmonstring->get('pl_stop');
		}
		
		if( $lokalmonstring->get('pl_deadline') > $siste_pamelding ) {
			$siste_pamelding = $lokalmonstring->get('pl_deadline');
		}
		
	    $lm = new StdClass();
	    $lm->starter = $lokalmonstring->get('pl_start');
	    $lm->slutter = $lokalmonstring->get('pl_stop');
	    $lm->url = $lokalmonstring->get('link');
	    $lm->navn = $kommune_navn;
	    
		$lokalmonstringer[] = $lm;
	}
	$DATA['lokalmonstringer'] = $lokalmonstringer;
	


// HVILKEN PERIODE ER FYLKESSIDEN I?
	$VIEW = 'fylke_pre_lokal';
	if( time() > $siste_pamelding && time() < $siste ) {
		$VIEW = 'fylke_lokal';
	} elseif( time() > $siste && time() < $pl->get('pl_start') ) {
		$VIEW = 'fylke_pre';
	} elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$VIEW = 'fylke';
	} elseif( time() > $pl->get('pl_stop') ) {
		$VIEW = 'fylke_post';
	}

// DEBUG
$VIEW = 'fylke';

// PAGE NAV
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Program',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se program for fylkesmønstringen'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Hvem deltar?',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se alle som deltar på fylkesmønstringen.'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'mobile',
										   'description'	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!'
										  );
    $DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Program',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se program for fylkesmønstringen'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Hvem deltar?',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se alle som deltar på fylkesmønstringen.'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'mobile',
										   'description'	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!'
										  );
	
