<?php
require_once('UKM/monstring.class.php');

$DATA['kommune'] = get_option('kommune');

// HENT ALLE POSTS
        $DATA['posts'] = array();

        // LOAD PAGE DATA
        the_post();
        $DATA['page'] = new WPOO_Post( $post );

        // LOAD POSTS
        $paged = (get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
        
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
        $monstring->pl_id = $pl->get('pl_id'); 
        $monstring->starter = $pl->get('pl_start');
        $monstring->slutter = $pl->get('pl_stop');
        $monstring->sted = $pl->get('pl_place');
        $monstring->kommuner = $pl->get('kommuner');
        $monstring->frist_1 = $pl->frist(1);
        $monstring->frist_2 = $pl->frist(2);
        $monstring->bandtypesdetails = $pl->getAllBandTypesDetailedNew();
        $monstring->kontaktpersoner = $pl->kontakter();
        
        foreach($monstring->kontaktpersoner as $i => $kp) {
	        $monstring->kontaktpersoner[$i]->info['name'] = $kp->get('name');
        }
        
        $DATA['monstring'] = $monstring;

// HVILKEN PERIODE ER KOMMUNESIDEN I?      

        $utenforsesong = mktime(0,0,0,12,1,get_option('season')-1)>time();
        if (!$pl->registered()) {
            $VIEW = 'kommune_ikke_klar';
        } else if ($utenforsesong) {
            $VIEW = 'kommune_pre_pamelding'; 
        } else if ( time() < $pl->frist()) {
            $VIEW = 'kommune_pamelding';
        } elseif( time() > $pl->get('pl_deadline') && time() < $pl->get('pl_start') ) {
            $VIEW = 'kommune_pre';
        } elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
            $VIEW = 'kommune';
        } else { // if( time() > $pl->get('pl_stop') ) {
            $VIEW = 'kommune_post';
        }
    
        
// DEBUG
$VIEW = 'kommune_post';

        $DATA = array_merge($DATA, $pl->pameldingsikoner());

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


        $DATA['page_nav'][] = (object) array( 'url' => '#',
                                            'title' => 'Program',
                                            'icon'  => 'table',
                                            'description' => 'Se program for lokalmønstringen'
                                           );
        $DATA['page_nav'][] = (object) array( 'url' => '#',
                                              'title' => 'Hvem deltar?',
                                              'icon' => 'hvem',
                                              'description' => 'Se alle som deltar på lokalmønstringen.'
                                          );
        $DATA['page_nav'][] = (object) array( 'url' => '#',
                                              'title' => 'Kontaktpersoner',
                                              'icon'  => 'info',
                                              'description' => 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!'
                                          );
?>