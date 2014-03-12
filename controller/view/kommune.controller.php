<?php
require_once('UKM/monstring.class.php');

$DATA['kommune'] = get_option('kommune');

// HENT ALLE POSTS
	$DATA['posts'] = array();
	
	// LOAD PAGE DATA
	the_post();
	$DATA['page'] = new WPOO_Post( $post );
	
	// LOAD POSTS
    $paged = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    
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
    $monstring->pl_id = $pl->get('pl_id'); 
    $monstring->navn = $pl->get('pl_name'); 
    $monstring->starter = $pl->get('pl_start');
    $monstring->slutter = $pl->get('pl_stop');
    $monstring->sted = $pl->get('pl_place');
    $monstring->kommuner = $pl->get('kommuner');
    $monstring->frist_1 = $pl->get('pl_deadline');
    $monstring->frist_1_aktiv = $pl->subscribable('pl_deadline');
    $monstring->frist_2 = $pl->get('pl_deadline2');
    $monstring->frist_2_aktiv = $pl->subscribable('pl_deadline2');
    $monstring->bandtypesdetails = $pl->getAllBandTypesDetailedNew();
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
    
    
// HAR UKM-TV-SIDE? (opplastede videoer?)
	$kategori = ''. $pl->g('pl_name').' '.$pl->g('season');
	$sql = new SQL("SELECT `tv_id` FROM `ukm_tv_files`
					WHERE `tv_category` LIKE '#kategori%'",
					array('kategori' => $kategori) );
	$res = $sql->run();
	$UKMTV = mysql_num_rows( $res ) > 0 ? $kategori : false;

// HVILKEN PERIODE ER KOMMUNESIDEN I?      

        $utenforsesong = mktime(0,0,0,12,1,get_option('season')-1)>time();
        if (!$pl->registered()) {
            $VIEW = 'kommune_ikke_klar';
        } else if ($utenforsesong) {
            $VIEW = 'kommune_pre_pamelding'; 
        } elseif( time() < $pl->get('pl_deadline') || time() < $pl->get('pl_start') ) {
            $VIEW = 'kommune_pamelding';
        } elseif( time() > $pl->get('pl_deadline') && time() > $pl->get('pl_start') ) {
            $VIEW = 'kommune_pre';
        } elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
            $VIEW = 'kommune_aktiv';
        } else {
            $VIEW = 'kommune_post';
        }
    
// PÅMELDINGSIKONER
$DATA = array_merge($DATA, $pl->pameldingsikoner());
		
// PAGE NAV

/*
        $DATA['page_nav'][] = (object) array( 'url' => 'bilder/',
                                              'title' => 'Bilder',
                                              'icon'  => 'kamera',
                                              'description' => 'Bilder fra '. $pl->get('pl_name').''
                                          );
*/
if( $UKMTV ) {
    $DATA['page_nav'][] = (object) array( 'url'			=> '//tv.ukm.no',
                                          'title'		=> 'Film',
                                          'icon' 		=> 'ukmtv_black',
                                          'description' => 'Film fra fra '. $pl->get('pl_name').' i UKM-TV'
                                      );
}

if( in_array( $VIEW, array('kommune_pre','kommune_aktiv','kommune_post')) || $pl->har_program() ) {
$DATA['page_nav'][] = (object) array( 'url' 			=> 'program/',
									   'title'		 	=> 'Program',
									   'icon'			=> 'calendar',
                                        'description'	=> 'Se program for lokalmønstringen'
                                       );
}
$innslag = $pl->innslag();
if( sizeof( $innslag ) > 0 && $VIEW == 'kommune_post' ) {
    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
                                          'title'		=> 'Hvem deltok?',
                                          'icon'		=> 'people',
                                          'description' => 'Se alle som deltok på lokalmønstringen.'
                                      );
} elseif( sizeof( $innslag ) > 0 ) {
    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
                                          'title'		=> 'Hvem deltar?',
                                          'icon'		=> 'people',
                                          'description' => 'Se alle som deltar på lokalmønstringen.'
                                      );
}


$DATA['page_nav'][] = (object) array( 'url' 			=> '#kontaktpersoner',
                                      'title' 			=> 'Kontaktpersoner',
                                      'icon'  			=> 'i',
                                      'description' 	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!',
										   'id'			=> 'show_kontaktpersoner'
                                  );
?>