<?php
$active = get_option( 'ukm_top_page' );
$NAV_TOP = array('ungdom','din_monstring','voksneogpresse','ukmtv','arrangorer');
	
if($active == 'ungdom' && is_page() && 'dinmonstring' == get_post_meta($post->ID, 'UKMviseng', true) ) 
	$active = 'din_monstring';
	
if( !$active ) 
	$active = 'din_monstring';

if( !in_array( $active, $NAV_TOP ) )
	$active = 'ungdom';

$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'for ungdom',
									'full_title'=> 'UKM for ungdom',
								    'active'	=> $active == 'ungdom');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/din_monstring/',
									'title' 	=> 'der du bor',
									'full_title'=> 'UKM der du bor',
								    'active'	=> $active == 'din_monstring');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/om/',
									'title' 	=> 'for voksne og presse',
									'full_title'=> 'UKM for voksne og presse',
								    'active'	=> $active == 'voksneogpresse');

$DATA['nav_top'][] = (object) array('url' 		=> '//tv.'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'TV',
									'full_title'=> 'UKM-TV',
								    'active'	=> $active == 'ukmtv');
								    
/*
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/internasjonalt/',
									'title' 	=> 'internasjonalt',
								    'active'	=> $active == 'internasjonalt');

$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/ambassador/',
									'title' 	=> 'ambassadører',
								    'active'	=> $active == 'ambassadorer');
*/
								    
$DATA['nav_top_right'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/arrangor/',
									'title' 	=> 'for arrangører',
									'full_title'=> 'UKM for arrangører',
								    'active'	=> $active == 'arrangorer');