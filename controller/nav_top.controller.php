<?php
$active = 'ungdom';


$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'ungdom',
								    'active'	=> $active == 'ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//'.$_SERVER['HTTP_HOST'].'/din_monstring/',
									'title' 	=> 'der du bor',
								    'active'	=> $active == 'ukm.no/din_monstring');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//om.'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'voksne og presse',
								    'active'	=> $active == 'om.ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//internasjonalt.'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'internasjonalt',
								    'active'	=> $active == 'internasjonalt.ukm.no');

$DATA['nav_top'][] = (object) array('url' 		=> '//ambassador.'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'ambassadører',
								    'active'	=> $active == 'ambassador.ukm.no');
								    
$DATA['nav_top'][] = (object) array('url' 		=> '//arrangor.'.$_SERVER['HTTP_HOST'].'/',
									'title' 	=> 'arrangører',
								    'active'	=> $active == 'arrangor.ukm.no');