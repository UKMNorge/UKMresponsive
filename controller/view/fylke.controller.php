<?php
require_once('UKM/monstring.class.php');

$DATA['fylke'] = get_option('fylke');

// HENT ALLE POSTS
	$DATA['posts'] = array();
	
	// LOAD PAGE DATA
	the_post();
	$DATA['page'] = new WPOO_Post( $post );
	
	// LOAD POSTS
    $paged = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    
    $posts = query_posts('posts_per_page=6&paged='.$paged);
    while(have_posts()) {
       the_post();
       $DATA['posts'][] = new WPOO_Post($post); 
    }
    
    $npl = get_next_posts_link();
    if($npl) {
        $npl = explode('"',get_next_posts_link()); 
        $DATA['nextpost']=$npl[1];
    }
    $ppl = get_previous_posts_link();
    if($ppl) {
        $ppl = explode('"',$ppl); 
        $DATA['prevpost']=$ppl[1];
    }
	
// INFO OM MØNSTRINGEN
	$pl = new monstring( get_option('pl_id') );
	$monstring = new StdClass();
	$monstring->starter = $pl->get('pl_start');
	$monstring->slutter = $pl->get('pl_stop');
	$monstring->sted = $pl->get('pl_place'); // info['pl_place']; //
    $monstring->navn = $pl->get('pl_name'); // info['pl_name'];  //
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
	$half_lokalmonstringer = ceil( sizeof( $lokalmonstringer ) / 2);
	
	$DATA['lokalmonstringer']['alle'] = $lokalmonstringer;
	$DATA['lokalmonstringer']['first_half'] = array_slice( $lokalmonstringer, 0, $half_lokalmonstringer);
	$DATA['lokalmonstringer']['second_half'] = array_slice( $lokalmonstringer, $half_lokalmonstringer);
	

// HVILKEN PERIODE ER FYLKESSIDEN I?
	$VIEW = 'fylke_pre_lokal';
	if( time() > $siste_pamelding && time() < $siste ) {
		$VIEW = 'fylke_lokal';
	} elseif( time() > $siste && time() < $pl->get('pl_start') ) {
		$VIEW = 'fylke_pre';
	} elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$VIEW = 'fylke_aktiv';
	} elseif( time() > $pl->get('pl_stop') ) {
		$VIEW = 'fylke_post';
	}

// DEBUG
$VIEW = 'fylke_pre_lokal';

// PAGE NAV

    $DATA['page_nav'][] = (object) array( 'url' => 'bilder/',
                                          'title' => 'Bilder',
                                          'icon'  => 'kamera',
                                          'description' => 'Bilder fra '. $pl->get('pl_name').''
                                      );
    $DATA['page_nav'][] = (object) array( 'url' => '//tv.ukm.no',
                                          'title' => 'Film',
                                          'icon'  => 'ukmtv_black',
                                          'description' => 'Film fra fra '. $pl->get('pl_name').' i UKM-TV'
                                      );

	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Program',
										   'icon'			=> 'table',
										   'description'	=> 'Se program for fylkesmønstringen'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Hvem deltar?',
										   'icon'			=> 'hvem',
										   'description'	=> 'Se alle som deltar på fylkesmønstringen.'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'info',
										   'description'	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!'
										  );