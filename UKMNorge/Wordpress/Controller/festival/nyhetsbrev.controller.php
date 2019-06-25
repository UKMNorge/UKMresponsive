<?php
use UKMNorge\DesignBundle\Utils\SEO;
use UKMNorge\DesignBundle\Utils\SEOImage;

#$WP_TWIG_DATA['HEADER']->background->url = 'http://ukm.dev/festivalen/wp-content/uploads/sites/60/2018/06/Screen-Shot-2018-06-19-at-21.11.18.png';#'//grafikk.ukm.no/UKMresponsive/img/festival/forsidebilde.jpg';
$WP_TWIG_DATA['HEADER']->hideSection = true;
$WP_TWIG_DATA['HEADER']->hideLogo = true;


$current_date = $WP_TWIG_DATA['page']->getPage()->meta->dato;





require_once('UKM/tv.class.php');
// Tøffelvideo
$WP_TWIG_DATA['mandag_toffel'] = new TV(14744);

$view_template = 'Festival/Nyhetsbrev/2019_'. $current_date;