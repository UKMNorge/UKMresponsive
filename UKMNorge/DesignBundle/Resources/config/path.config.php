<?php
/* CONFIG FILES MUST BE RUNNABLE MULTIPLE TIMES DURING ONE PAGELOAD */
/* CONFIG FILES MAY BE READABLE BY PUBLIC API, AND NEVER CONTAIN SENSITIVE INFORMATION OF ANY KIND */

$PATH = array();

$PATH['_base']		= get_theme_root().'/UKMresponsive_v2/';
$PATH['base']		= CURRENT_UKM_DOMAIN;

$PATH['_theme_dir']	= get_theme_root().'/UKMresponsive/';
$PATH['theme_dir'] 	= get_stylesheet_directory_uri().'/';

$PATH['blog']		= get_bloginfo('url').'/';
