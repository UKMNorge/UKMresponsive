<?php
require_once('UKMconfig.inc.php');
require_once('UKM/monstring.class.php');

$DATA['fylke'] = get_option('fylke');

// HENT ALLE POSTS
	$DATA['posts'] = array();
	
	// LOAD PAGE DATA
	#the_post();
	#$DATA['page'] = new WPOO_Post( $post );
	$DATA['page'] = [];
	
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

// INFO OM LOKALMØNSTRINGER
	$kommuner_i_fylket = $pl->get('kommuner_i_fylket');
	$forste = 0;
	$siste = 0;
	$siste_pamelding = 0;
	$fellesmonstringer = array();
	
	foreach( $kommuner_i_fylket as $kommune_id => $kommune_navn ) {
		$lokalmonstring = new kommune_monstring( $kommune_id, get_option('season') );
		$lokalmonstring = $lokalmonstring->monstring_get();
		
		// Hopp over gjeste-kommuner for fylket (kun for test og arrangørsystem-funksjonalitet)
		if( $lokalmonstring->get('pl_name') == 'Gjester' ) {
    		continue;
		}
		
		// Hopp over kommuner som ikke er tilknyttet noen mønstring
		if( $lokalmonstring->get('pl_id') == 0 ) {
			continue;
		}
			
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
		
		if( $pl->get('fylke_id') == 3 && $lokalmonstring->fellesmonstring() ) {
			$fellesmonstring = new stdClass();
			$fellesmonstring->navn = $lokalmonstring->get('pl_name');
			$fellesmonstring->url = $lokalmonstring->get('link');
			$fellesmonstring->kommuner = $lokalmonstring->kommuneArray();
			$DATA['lokalmonstringer']['felles'][ $fellesmonstring->navn ] = $fellesmonstring;
			$fellesmonstringer[] = $fellesmonstring;
		} else {
		    $lm = new StdClass();
		    $lm->starter = $lokalmonstring->get('pl_start');
		    $lm->slutter = $lokalmonstring->get('pl_stop');
		    $lm->url = $lokalmonstring->get('link');
		    $lm->navn = $kommune_navn;
			$lokalmonstringer[] = $lm;
		}
	    
	}
	$half_lokalmonstringer = ceil( sizeof( $lokalmonstringer ) / 2);
	
	$DATA['lokalmonstringer']['alle'] = array_merge($lokalmonstringer, $fellesmonstringer);
	$DATA['lokalmonstringer']['first_half'] = array_slice( $lokalmonstringer, 0, $half_lokalmonstringer);
	$DATA['lokalmonstringer']['second_half'] = array_slice( $lokalmonstringer, $half_lokalmonstringer);

// HAR UKM-TV-SIDE? (opplastede videoer?)
	$UKMTV = $pl->har_ukmtv() ? $pl->get('url') .'/'. $pl->get('season') .'/' : false;


// HVILKEN PERIODE ER FYLKESSIDEN I?
	$VIEW = 'fylke/fylke_pre_lokal';
	if ( date('m') > 8 && date('m') < 12 ) {
		$VIEW = 'fylke/fylke';
	} elseif (!$pl->registered()) {
        	$VIEW = 'fylke/fylke_ikke_klar';
	} elseif( time() > $siste_pamelding && time() < $siste ) {
		$VIEW = 'fylke/fylke_lokal';
	} elseif( time() > $siste && time() < $pl->get('pl_start') ) {
		$VIEW = 'fylke/fylke_pre';
	} elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$VIEW = 'fylke/fylke_aktiv';
	} elseif( time() > $pl->get('pl_stop') ) {
		$VIEW = 'fylke/fylke_post';
	}
	
$DATA['console']['fm_view'][] = $VIEW;

$SEO->title('UKM '. $monstring->navn);
$description = 'Nyheter og informasjon om UKM '. $monstring->navn;
if( $pl->registered() ) {
	$description .= ' - '. $monstring->starter_tekst.', '. $monstring->sted;
}
$SEO->description( $description );

$DATA['livelink'] = get_option('ukm_live_link');
if( $VIEW == 'fylke/fylke_aktiv' ) {
	$perioder = get_option('ukm_hendelser_perioder');
	$embedcode = get_option('ukm_live_embedcode');
	
	$show_embed = false;
	
	if( $embedcode ) {
		foreach( $perioder as $p ) {
			if( $p->start < time() && $p->stop > time() ) {
				$show_embed = true;
				break;
			}
		}
	}
	
	if( $show_embed ) {
		$DATA['embedcode'] = str_replace('\"','"', $embedcode);
	}
}



	$SQL = new SQL("SELECT `id`
					FROM `ukm_bilder`
					WHERE `pl_id` = '#plid'
					LIMIT 1",
					array('plid' => $pl->g('pl_id'))
				);
	$res = $SQL->run();
	$har_bilder = mysql_num_rows( $res ) > 0;

	if( $har_bilder ) {
	    $DATA['page_nav'][] = (object) array( 'url' => 'bilder/',
	                                          'title' => 'Bilder',
	                                          'icon'  => 'kamera',
	                                          'description' => 'Bilder fra '. $pl->get('pl_name').''
	                                      );
	}
	// HAR UKM-TV SIDE
	if( $UKMTV ) {
	    $DATA['page_nav'][] = (object) array( 'url' => '//tv.'. UKM_HOSTNAME .'/fylke/'. $UKMTV,
                                          'title' => 'Film',
                                          'icon'  => 'ukmtv_black',
                                          'description' => 'Film fra '. $pl->get('pl_name').' i UKM-TV',
                                          'target'		=> '_blank'
                                      );
    }
    // HAR PROGRAM
	if( in_array( $VIEW, array('fylke/fylke_pre','fylke/fylke_aktiv','fylke/fylke_post')) || $pl->har_program() ) {
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'program/',
											   'title'		 	=> 'Program',
											   'icon'			=> 'calendar',
											   'description'	=> 'Se program for fylkesmønstringen'
											  );
	}
	// HAR INNSLAG
	$innslag = $pl->innslag();
	if( sizeof( $innslag ) > 0 && $VIEW == 'fylke/kommune_post' ) {
	    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
	                                          'title'		=> 'Hvem deltok?',
	                                          'icon'		=> 'people',
	                                          'description' => 'Se alle som deltok på fylkesmønstringen.'
	                                      );
	} elseif( sizeof( $innslag ) > 0 ) {
	    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
	                                          'title'		=> 'Hvem deltar?',
	                                          'icon'		=> 'people',
	                                          'description' => 'Se alle som deltar på fylkesmønstringen.'
	                                      );
	}
	
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#kontaktpersoner',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'i',
										   'description'	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!',
										   'id'				=> 'show_kontaktpersoner'
										  );

require_once('UKM/statistikk.class.php');
$stat = $pl->statistikk();										  
$total = $stat->getTotal($pl->get('season'));
$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= 'i '. $monstring->navn;
$DATA['stat_pameldte'] = $stat; 
