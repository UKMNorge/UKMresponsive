<?php
require_once('WPOO/WPOO/Post.php');
require_once('WPOO/WPOO/Author.php');

$fylkesmonstringer = array();
if( true || (int) date('m') > 2 && (int) date('m') < 6 ) {
	$year = date('Y');
	$week = (int) date('W');
	$day = (int) date('N');
	
	// Vis denne ukes fylkesmønstringer
	$fylkesmonstringer_denne_uken = load_fm_data( $year, $week );
	
	// Vis forrige ukes fylkesmønstringer frem til onsdag
	if( $day < 4 ) {
		$fylkesmonstringer_forrige_uke = load_fm_data( $year, $week-1 );	
	} else {
		$fylkesmonstringer_forrige_uke = array();
	}
}
$DATA['fylkesmonstringer_denne_uken'] = $fylkesmonstringer_denne_uken;
$DATA['fylkesmonstringer_forrige_uke'] = $fylkesmonstringer_forrige_uke;



require_once('UKM/statistikk.class.php');

$stat = new statistikk();
$stat->setLand();
$total = $stat->getTotal(get_site_option('season'));

$stat = new stdClass();
$stat->tall 	= $total['persons'];
$stat->til		= get_site_option('season');

$DATA['stat_pameldte'] = $stat;



function load_fm_data( $year, $week ) {
	$fylkesmonstringer = get_site_option('UKMpush_to_front_uke_'. $year .'_'. ($week<10 ? '0':''). $week);
	$return_monstringer = array();
	if( is_array( $fylkesmonstringer ) ) {
		foreach( $fylkesmonstringer as $fm ) {
			$fylke = get_site_option('UKMpush_to_front_fylke_'.$fm);
			// Load all posts
			if( is_array( $fylke->posts ) ) {
				switch_to_blog( $fylke->ID );
				foreach( $fylke->posts as $post_id ) {
					$post	= get_post( $post_id );
					@$WPOO_post	= new WPOO_Post( $post );
			
					$fylke->postdata[] = $WPOO_post;
				}
				restore_current_blog();
			}
			
			// is live now?
			$fylke->live->now = false;
			$perioder = get_blog_option($fylke->ID, 'ukm_hendelser_perioder');
			$embedcode = get_blog_option($fylke->ID, 'ukm_live_embedcode');
			if( $embedcode && is_array( $perioder ) ) {
				foreach( $perioder as $p ) {
					if( $p->start < time() && $p->stop > time() ) {
						$fylke->live->now = true;
						break;
					}
				}
			}
			// Add to array
			$return_monstringer[] = $fylke;
		}
	}
	return $return_monstringer;
}