<?php
require_once('UKM/monstring.class.php');

// CHECK FOR MOBILE
require_once(THEME_PATH . 'class/mobiledetect.class.php');
$mobileDetect = new Mobile_Detect();
$DATA['isMobile'] = $mobileDetect->isMobile();
//BRUKERGENERERING
if(isset($_GET['generateusers']) && isset($_GET['run']) && $_GET['generateusers'] == md5('ja') && $_GET['run'] == 1) {
  include('wp-content/plugins/UKMfestivalen/users.php');
  UKMFestivalen_brukere_opprett();
  die();
}

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
        		$wpoopost->meta[$key] = $val[0];
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

// HVILKEN PERIODE ER SSIDEN I?
	$DATA['state'] = 'pre';
	if( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$DATA['state'] = 'active';
	} elseif( time() > $pl->get('pl_stop') ) {
		$DATA['state'] = 'post';
	}

	$DATA['state'] = 'pre';


	// FØR MØNSTRINGEN
	if( $DATA['state'] == 'pre' ) {
		$VIEW = 'festival/homepage_pre';
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
	
	$DATA['page_nav'][] = (object) array( 'url' 			=> '//om.'. UKM_HOSTNAME .'/kontakt/administrasjonen/',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'i',
										   'description'	=> 'Har du spørsmål om UKM-festivalen? Disse kan hjelpe!',
										  );


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
