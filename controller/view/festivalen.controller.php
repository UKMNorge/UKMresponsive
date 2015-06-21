<?php
require_once(THEME_PATH.'functions/blocks.inc.php');
require_once('UKM/monstring.class.php');

// CHECK FOR MOBILE
require_once(THEME_PATH . 'class/mobiledetect.class.php');
$mobileDetect = new Mobile_Detect();
$DATA['isMobile'] = $mobileDetect->isMobile();

	$DATA['blog']['css_extra'][] = 'less/css/festival14.css';
	$DATA['hide_stat'] = true; // Skjuler påmeldte-tall oppe til høyre
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
    $posts = query_posts('posts_per_page=18&cat=1&paged='.$paged);
    while(have_posts()) {
        the_post();
        $wpoopost = new WPOO_Post($post);
        $metadata = get_post_custom($post->id);
        $wpoopost->blog = new stdClass();
        $wpoopost->blog->link = get_bloginfo('url');
        $wpoopost->blog->name = get_bloginfo('name');
        if( is_array( $metadata ) ) {
        	foreach( $metadata as $key => $val ) {
	        	if( is_array( $wpoopost->meta ) ) {
	        		$wpoopost->meta[$key] = $val[0];
	        	} elseif( is_object( $wpoopost->meta ) ) {
		        	$wpoopost->meta->$key = $val[0];
	        	}
        	}
        }
        $DATA['posts'][] = $wpoopost; 
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


// HENT ALLE POSTS FRA VI MØTER-KATEGORIEN
	$DATA['meet'] = array();
	
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
    $posts = query_posts('posts_per_page=4&cat=3&paged='.$paged);
    while(have_posts()) {
        the_post();
        $wpoopost = new WPOO_Post($post);
#        $metadata = get_post_custom($post->id);
        $wpoopost->blog = new stdClass();
        $wpoopost->blog->link = get_bloginfo('url');
        $wpoopost->blog->name = get_bloginfo('name');
/*        if( is_array( $metadata ) ) {
        	foreach( $metadata as $key => $val ) {
        		$wpoopost->meta[$key] = $val[0];
        	}
        }
        */
        $DATA['meet'][] = $wpoopost; 
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
    
    $monstring->varer = new stdClass();
    $monstring->varer->dager = $pl->dager();
    
    $remaining = $monstring->starter - time();
    $monstring->lengetil = new stdClass();
    $monstring->lengetil->dager = floor($remaining / 86400);
    $monstring->lengetil->timer = floor(($remaining % 86400) / 3600);
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

	$UKMTV = false;

// HVILKEN PERIODE ER SIDEN I?
	$DATA['state'] = 'pre';
	if( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$DATA['state'] = 'active';
	} elseif( time() > $pl->get('pl_stop') ) {
		$DATA['state'] = 'post';
	}

	$DATA['state'] = 'live';


	// FØR MØNSTRINGEN
	if( $DATA['state'] == 'live' ) {
		$VIEW = 'festival/homepage_festival';
	} else {
		// HAR UKM-TV-SIDE? (opplastede videoer?)
		$kategori = 'UKM-Festivalen '.$pl->g('season');
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
	}

	// HAR UKM-TV SIDE
	if( $UKMTV ) {
	    $DATA['page_nav'][] = (object) array( 'url' => '//tv.ukm.no/samling/'. urlencode($UKMTV),
                                          'title' => 'Film',
                                          'icon'  => 'ukmtv_black',
                                          'description' => 'Film fra fra '. $pl->get('pl_name').' i UKM-TV'
                                      );
    }
    // HAR PROGRAM
	if( $pl->har_program() ) {
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'program/',
											   'title'		 	=> 'Program',
											   'icon'			=> 'calendar',
											   'description'	=> 'Se program for festivalen'
											  );
	}
	
	// SKAL DELTAKERINFO VISES
	if( get_option('vis_deltakerinfo_mode_pre') ) {
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'deltakerinfo/',
											   'title'		 	=> 'Deltakerinfo',
											   'icon'			=> 'i',
											   'description'	=> 'Viktig informasjon til alle deltakere'
											  );
	}
	
	// SKAL FESTIVALINFO VISES
	if( get_option('vis_festivalinfo_meny_mode_pre') ) {
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'om-ukm-festivalen/',
											   'title'		 	=> 'Om UKM-festivalen',
											   'icon'			=> 'star',
											   'description'	=> 'Hva er egentlig UKM-festivalen?'
											  );
	}

	// SKAL WORKSHOPS VISES
	if( get_option('vis_workshops_meny_mode_pre') ) {
		$category_ws = get_category_by_slug('workshop');

		$DATA['page_nav'][] = (object) array( 'url' 			=> 'category/workshop/',
											   'title'		 	=> 'Workshops',
											   'icon'			=> 'rocket',
											   'description'	=> 'Info om UKM-festivalens '. $category_ws->category_count .'workshops'
											  );
	}	
	
	// HAR INNSLAG
	$innslag = $pl->innslag();
	if( sizeof( $innslag ) > 0 ) {
		$DATA['har_pameldte'] = true;
	    $DATA['page_nav'][] = (object) array( 'url'			=> 'pameldte/',
	                                          'title'		=> 'Hvem delt'. ( $DATA['state']=='post' ? 'ok' : 'ar' ) .'?',
	                                          'icon'		=> 'people',
	                                          'description' => 'Se alle som delt'. ( $DATA['state']=='post' ? 'ok' : 'ar' ) .' på festivalen.'
	                                      );
	} else {
		$DATA['har_pameldte'] = false;
	}
	
	$DATA['page_nav'][] = (object) array( 'url' 			=> (date('m') == 6 ? '/festivalen/kontakt/' : '//om.'. UKM_HOSTNAME .'/kontakt/administrasjonen/'),
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'i',
										   'description'	=> 'Har du spørsmål om UKM-festivalen? Disse kan hjelpe!',
										  );
										  
										  
if( $DATA['state'] == 'pre' ) {
	// JUMBO-IMAGE
	$description = $monstring->sted .', '
				 . date('d.', $monstring->starter) .' - '
				 . date('d.', $monstring->slutter) .' '
				 . (date('M', $monstring->slutter ) == 'Jun' ? 'Juni' : date('M', $monstring->slutter ));
	$DATA['ukmfestivalen_jumboimage'] = block_jumbo_image('top',
										  'UKM-festivalen', 
										  $description, 
										  'https://farm4.staticflickr.com/3857/14566213135_5faa5b154d_z.jpg',
										  'https://farm4.staticflickr.com/3857/14566213135_5faa5b154d_b.jpg',
										  'https://farm4.staticflickr.com/3857/14566213135_6495f5efc2_h.jpg',
										  'https://farm4.staticflickr.com/3857/14566213135_6495f5efc2_h.jpg'
										   );

	if( get_option('vis_festivalinfo_forside_mode_pre') ) {
		$hva_er_side = get_page_by_path( 'om-ukm-festivalen' );
		setup_postdata( $hva_er_side );
        $DATA['hva_er_side'] = new WPOO_Post( $hva_er_side );
        $DATA['hva_er_side']->blocks = setup_blocks_from_subpages( $hva_er_side->ID );
	}
	
	if( get_option('vis_workshopsinfo_forside_mode_pre') ) {
		$category_ws = get_category_by_slug('workshop');
		$category_ws_description = category_description( $category_ws->term_id );
		$DATA['workshops_info'] = true;
		$DATA['workshops_info_count'] = $category_ws->category_count;
		$DATA['workshops_info_description'] = $category_ws_description;
	}

	if( get_option('vis_workshops_forside_mode_pre') ) {
		$category_ws = get_category_by_slug('workshop');

	    $posts = query_posts('posts_per_page=3&cat='. $category_ws->term_id.'&orderby=rand');
	    while(have_posts()) {
	        the_post();
	        $wpoopost = new WPOO_Post($post);
	        $metadata = get_post_custom($post->id);
	        $wpoopost->blog = new stdClass();
	        $wpoopost->blog->link = get_bloginfo('url');
	        $wpoopost->blog->name = get_bloginfo('name');
	        $DATA['workshops'][] = $wpoopost; 
	    }
	    $npl = get_next_posts_link();
	    if($npl) {
	        $npl = explode('"',get_next_posts_link()); 
	        $DATA['workshops_nextpost']=$npl[1];
	    }
	}
}

/*
// Timeline sendeskjema

$schedule = array();
$count = 0;
$keys = array();

$days = array(
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday'
);

$transDays = array(
    'Mandag',
    'Tirsdag',
    'Onsdag',
    'Torsdag',
    'Fredag',
    'Lørdag',
    'Søndag'
);

$handle = fopen(THEME_PATH . '/config/sendeskjema.csv', "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if($count == 0) {
            $keys = explode(',', $line);
        }
        else {
            $data = explode(',', $line);
            $dataArr = array();
            $c = 0;
            foreach($keys as $key) {
                $dataArr[trim($key)] = trim($data[$c]);
                ++$c;
            }
            $schedule[] = $dataArr;
        }
        ++$count;
    }
} else {
    // error opening the file.
} 
fclose($handle);

$today = date("d.m.Y");
$tomorrow = date("d.m.Y", strtotime('+1 day', strtotime($today)));
$aftertomorrow = date("d.m.Y", strtotime('+2 day', strtotime($today)));
$currentTime = date("Hi");
$startToday = array();
$endToday = array();

$timeline = array();

foreach($schedule as $item) {
    if($item['dato'] == $today) {
        $time = str_replace(':', '', $item['slutttid']);
        if($time >= $currentTime) {
            $item['dag'] = 'I dag';
            $timeline[] = $item;
            $startToday[] = $item['starttid'];
            $endToday[] = $item['slutttid'];
        }
    }
    if($item['dato'] == $tomorrow || $item['dato'] == $aftertomorrow) {
        if($item['dato'] == $tomorrow) {
            $item['dag'] = 'I morgen';
        }
        else {
            $item['dag'] = str_replace($days, $transDays, strftime("%A"));
        }
        $timeline[] = $item;
    }
}

$DATA['timeline'] = $timeline;
$DATA['schedule'] = $schedule;
*/