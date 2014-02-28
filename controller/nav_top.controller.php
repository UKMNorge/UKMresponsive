<?php
$active = get_option( 'ukm_top_page' );

if($active == 'ungdom' && is_front_page() )
	$active = 'ungdom';
	
if(!$active)
	$active = 'din_monstring';

$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'for ungdom',
								    'active'	=> $active == 'ungdom');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/din_monstring/',
									'title' 	=> 'der du bor',
								    'active'	=> $active == 'din_monstring');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/om/',
									'title' 	=> 'voksne og presse',
								    'active'	=> $active == 'voksneogpresse');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/internasjonalt/',
									'title' 	=> 'internasjonalt',
								    'active'	=> $active == 'internasjonalt');

$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/ambassador/',
									'title' 	=> 'ambassadører',
								    'active'	=> $active == 'ambassadorer');
								    
$DATA['nav_top_right'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/arrangor/',
									'title' 	=> 'arrangører',
								    'active'	=> $active == 'arrangor');