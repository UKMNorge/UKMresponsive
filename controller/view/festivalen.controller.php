<?php
require_once('UKM/monstring.class.php');

$DATA['fylke'] = get_option('fylke');

// HENT ALLE POSTS
	$DATA['posts'] = array();
	
	// LOAD PAGE DATA
	the_post();
	$DATA['page'] = new WPOO_Post( $post );
	
	// LOAD POSTS
	if ( get_query_var('paged') ) {
	    $paged = get_query_var('paged');
	} elseif ( get_query_var('page') ) {
	    $paged = get_query_var('page');
	} else {
	    $paged = 1;
	}
    $posts = query_posts('posts_per_page=7&paged='.$paged);
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
	$monstring->starter_tekst = $pl->starter();
	$monstring->slutter = $pl->get('pl_stop');
	$monstring->sted = $pl->get('pl_place'); // info['pl_place']; //
    $monstring->navn = $pl->get('pl_name'); // info['pl_name'];  //
	$DATA['monstring'] = $monstring;
	
	$kontaktpersoner = $pl->kontakter();
	foreach( $kontaktpersoner as $kontakt ) {
		$k = new stdClass();
		$k->navn 	= $kontakt->get('name');
		$k->tittel	= $kontakt->get('title');
		$k->bilde	= $kontakt->get('image');
		$k->mobil	= $kontakt->get('tlf');
		$k->epost	= $kontakt->get('email');
		$k->facebook= $kontakt->get('facebook');
		
		$kontakter[] = $k;
	}
	$DATA['kontaktpersoner'] = $kontakter;


// HVILKEN PERIODE ER FYLKESSIDEN I?
	$DATA['state'] = 'pre';
	if( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$DATA['state'] = 'active';
	} elseif( time() > $pl->get('pl_stop') ) {
		$DATA['state'] = 'post';
	}



// HAR UKM-TV-SIDE? (opplastede videoer?)
	$kategori = 'Fylkesmønstringen i '. $pl->g('pl_name').' '.$pl->g('season');
	$sql = new SQL("SELECT `tv_id` FROM `ukm_tv_files`
					WHERE `tv_category` LIKE '#kategori%'",
					array('kategori' => $kategori) );
	$res = $sql->run();
	if( !$res )
		$UKMTV = false;
	else
		$UKMTV = mysql_num_rows( $res ) > 0 ? $kategori : false;

// HAR LIVESTREAM
	$DATA['livelink'] = get_option('ukm_live_link');



	// HAR UKM-TV SIDE
	if( $UKMTV ) {
	    $DATA['page_nav'][] = (object) array( 'url' => '//tv.ukm.no/samling/'. urlencode($UKMTV),
                                          'title' => 'Film',
                                          'icon'  => 'ukmtv_black',
                                          'description' => 'Film fra fra '. $pl->get('pl_name').' i UKM-TV'
                                      );
    }
    // HAR PROGRAM
	if( in_array( $VIEW, array('fylke_pre','fylke_aktiv','fylke_post')) || $pl->har_program() ) {
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'program/',
											   'title'		 	=> 'Program',
											   'icon'			=> 'calendar',
											   'description'	=> 'Se program for festivalen'
											  );
	}
	// HAR INNSLAG
	$innslag = $pl->innslag();
	if( sizeof( $innslag ) > 0 && $VIEW == 'kommune_post' ) {
	    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
	                                          'title'		=> 'Hvem deltok?',
	                                          'icon'		=> 'people',
	                                          'description' => 'Se alle som deltok på festivalen.'
	                                      );
	} elseif( sizeof( $innslag ) > 0 ) {
	    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
	                                          'title'		=> 'Hvem deltar?',
	                                          'icon'		=> 'people',
	                                          'description' => 'Se alle som deltar på festivalen.'
	                                      );
	}
	
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#kontaktpersoner',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'i',
										   'description'	=> 'Har du spørsmål om UKM-festivalen? Disse kan hjelpe!',
										   'id'				=> 'show_kontaktpersoner'
										  );