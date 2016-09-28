<?php


/*
$sections = [array('url'=>'/', 'title'=>'Ungdom', 'pages'=>array(array('url'=>'/test/', 'title'=>'Test1'), array('url'=>'/test2/', 'title'=>'Test2'))),
			array('url'=>'/om/', 'title'=>'for ungdom', 'pages'=>array())];
			
echo Yaml::dump( $sections );
*/

foreach( $data['section'] as $section ) {
	echo $section['section']['title'] .' ('. $section['section']['url'] .')<br />';
}
echo '</pre>';
