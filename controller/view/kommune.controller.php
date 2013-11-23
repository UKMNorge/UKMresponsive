<?php
require_once('UKM/monstring.class.php');

$DATA['kommune'] = get_option('kommune');

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

// HVILKEN PERIODE ER KOMMUNESIDEN I?
	$VIEW = 'kommune_pre_lokal';
	if( time() > $siste_pamelding && time() < $siste ) {
		$VIEW = 'kommune_lokal';
	} elseif( time() > $siste && time() < $pl->get('pl_start') ) {
		$VIEW = 'kommune_pre';
	} elseif( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		$VIEW = 'kommune';
	} elseif( time() > $pl->get('pl_stop') ) {
		$VIEW = 'kommune_post';
	}

// PAGE NAV
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Program',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se program for lokalmønstringen'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Hvem deltar?',
										   'icon'			=> 'mobile',
										   'description'	=> 'Se alle som deltar på lokalmønstringen.'
										  );
	$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
										   'title'		 	=> 'Kontaktpersoner',
										   'icon'			=> 'mobile',
										   'description'	=> 'Har du spørsmål om UKM i '. $pl->get('pl_name').'? Disse kan hjelpe!'
										  );
	
