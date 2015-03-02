<?php
require_once('WPOO/WPOO/Post.php');
require_once('WPOO/WPOO/Author.php');

if( function_exists('UKMpush_to_front_load_all_fm_data') ) {	
	$fylkesmonstringer = array();
	$fylkesmonstringer_denne_uken = array();
	$fylkesmonstringer_forrige_uke = array();
	if( (int) date('m') > 2 && (int) date('m') < 6 ) {
		$year = date('Y');
		$week = (int) date('W');
		$day = (int) date('N');
		
		// Vis denne ukes fylkesmønstringer
		$fylkesmonstringer_denne_uken = UKMpush_to_front_load_all_fm_data( $year, $week );
		
		// Vis forrige ukes fylkesmønstringer frem til onsdag
		if( $day < 4 ) {
			$fylkesmonstringer_forrige_uke = UKMpush_to_front_load_all_fm_data( $year, $week-1 );	
		}
	}
	$DATA['fylkesmonstringer_denne_uken'] = $fylkesmonstringer_denne_uken;
	$DATA['fylkesmonstringer_forrige_uke'] = $fylkesmonstringer_forrige_uke;
}


require_once('UKM/statistikk.class.php');

$stat = new statistikk();
$stat->setLand();
$total = $stat->getTotal(get_site_option('season'));

$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= get_site_option('season');

$DATA['stat_pameldte'] = $stat;