<?php
$TWIGdata = array();
$TWIGdata['customizable'] =  !in_array(get_option('site_type'), array('kommune','fylke','land'));
$TWIGdata['site_type'] = get_option('site_type');
echo TWIG('content.twig.html', $TWIGdata, dirname(__FILE__));
