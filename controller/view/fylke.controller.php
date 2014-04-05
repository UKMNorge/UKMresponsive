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

	$DATA['livelink'] = get_option('ukm_live_link');

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
	
$DATA['console']['fm_view'][] = $VIEW;

$SEO->title('UKM '. $monstring->navn);
$description = 'Nyheter og informasjon om UKM '. $monstring->navn;
if( $pl->registered() ) {
	$description .= ' - '. $monstring->starter_tekst.', '. $monstring->sted;
}
$SEO->description( $description );

if( $VIEW == 'fylke_aktiv' && $_SERVER['REMOTE_ADDR'] == '195.204.59.106' ) {
	error_reporting( E_ALL );
	ini_set('display_errors',true);
	require_once(THEME_PATH.'controller/developer_include.php');
}

/*
    $DATA['page_nav'][] = (object) array( 'url' => 'bilder/',
                                          'title' => 'Bilder',
                                          'icon'  => 'kamera',
                                          'description' => 'Bilder fra '. $pl->get('pl_name').''
                                      );
*/
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
											   'description'	=> 'Se program for fylkesmønstringen'
											  );
	}
	// HAR INNSLAG
	$innslag = $pl->innslag();
	if( sizeof( $innslag ) > 0 && $VIEW == 'kommune_post' ) {
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
$total = $stat->getTotal(get_option('season'));
$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= 'i '. $monstring->navn;
$DATA['stat_pameldte'] = $stat; 