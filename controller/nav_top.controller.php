<?php
$active = 'ungdom';


$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'ungdom',
								    'active'	=> $active == 'ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/din_monstring/',
									'title' 	=> 'der du bor',
								    'active'	=> $active == 'ukm.no/din_monstring');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/om/',
									'title' 	=> 'voksne og presse',
								    'active'	=> $active == 'om.ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/internasjonalt/',
									'title' 	=> 'internasjonalt',
								    'active'	=> $active == 'internasjonalt.ukm.no');

$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/ambassador/',
									'title' 	=> 'ambassadører',
								    'active'	=> $active == 'ambassador.ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/arrangor/',
									'title' 	=> 'arrangører',
								    'active'	=> $active == 'arrangor.ukm.no');