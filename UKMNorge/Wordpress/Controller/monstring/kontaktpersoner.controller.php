<?php
require_once('UKM/monstring.class.php');


$FYLKE = new monstring_v2( get_option('pl_id') );

$WP_TWIG_DATA['monstring'] 	= $FYLKE;
