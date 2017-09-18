<?php

## Skal hente ut alle påmeldte innslag til påmeldte-oversikten.
require_once('UKM/monstring.class.php');

// Laster 229 innslag på 4.6ms.
$time = microtime(true);
$monstring = new monstring_v2(get_option('pl_id'));
$innslag = $monstring->getInnslag()->getAll();
#echo "Antall innslag: ". sizeof($innslag).".<br />";
#echo "Time taken: ". (microtime(true) - $time)*1000 . "ms. <br />";

$WP_TWIG_DATA['innslagListe'] = $innslag;
$WP_TWIG_DATA['monstring'] = $monstring;