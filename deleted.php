<?php

use UKMNorge\Arrangement\Arrangement;
use UKMNorge\Arrangement\Load;
use UKMNorge\Geografi\Fylker;
use UKMNorge\Geografi\Kommune;
use UKMNorge\Wordpress\Blog;

require_once('header.php');
require_once('UKM/Autoloader.php');

$template = '404/deleted';

$links = [];
switch (get_option('site_type')) {
        // Dette var en fylkesside
    case 'fylke':
        $fylke = Fylker::getById(get_option('fylke'));
        // Vi vet at dette fylket er overtatt av et annet - go there
        if ($fylke->erOvertatt()) {
            $ny_url = $fylke->getOvertattAv()->getLink(false);
            if( Blog::eksisterer( $ny_url ) ) {
                header("Location: " . $ny_url);
                echo '<script type="javascript">window.location.href = "' . $ny_url . '";</script>';
                exit();
            }
        }
        $links[] = [
            'link' => $fylke->getLink(false),
            'navn' => $fylke->getNavn()
        ];
        break;

        // Dette var en kommuneside
    case 'kommune':
        $kommuner = explode(',', get_option('kommuner'));

        // Komuneside for én kommune - go there
        if (sizeof($kommuner) == 1 ) {
            $kommune = new Kommune($kommuner[0]);
            if( $kommune->erOvertatt() ) {
                $kommune = $kommune->getOvertattAv();
            }
            $ny_url = $kommune->getLink();
            if( Blog::eksisterer( $ny_url ) ) {
                header("Location: " . $ny_url);
                echo '<script type="javascript">window.location.href = "' . $ny_url . '";</script>';
                exit();
            }
        }

        // Kommuneside for flere kommuner - list
        foreach ($kommuner as $kommune_id) {
            $kommune = new Kommune($kommune_id);
            if( $kommune->erOvertatt() ) {
                $kommune = $kommune->getOvertattAv();
            }
            $links[] = [
                'link' => $kommune->getLink(),
                'navn' => $kommune->getNavn(),
                'tidligere' => $kommune->getTidligereNavnListe()
            ];
        }

        // Info om fylket kommunen er i
        if( get_option('fylke') ) {
            $fylke = Fylker::getById(get_option('fylke'));
            // Hvis fylket har blitt overtatt, vis dette
            if ($fylke->erOvertatt()) {
                $fylke = $fylke->getOvertattAv();
            }

            $links[] = [
                'link' => $fylke->getLink(false),
                'navn' => $fylke->getNavn()
            ];
        }
        break;
        // Dette var et arrangement
    case 'arrangement':
        // og vi vet hvilket arrangement det var (pre 2020 kunne dette oppstå)
        if (get_option('pl_id')) {
            $arrangement = new Arrangement(get_option('pl_id'));

            // det var et fylkesarrangement
            if ($arrangement->getEierType() == 'fylke') {
                $fylke = $arrangement->getFylke();
                // Vi vet at dette fylket er overtatt av et annet - go there
                if ($fylke->erOvertatt() && Blog::eksisterer($fylke->getOvertattAv()->getLink())) {
                    $ny_url = $fylke->getOvertattAv()->getLink(false);
                    header("Location: " . $ny_url);
                    echo '<script type="javascript">window.location.href = "' . $ny_url . '";</script>';
                    exit();
                }
                // Hvis fylket ikke er overtatt, foreslå fylkessiden (skal vel egentlig ikke skje)
                $links[] = [
                    'link' => $fylke->getLink(false),
                    'navn' => $fylke->getNavn()
                ];
            } elseif ($arrangement->getEierType() == 'kommune') {
                // Kommuneside for flere kommuner - list
                foreach ($arrangement->getKommuner()->getAll() as $kommune) {
                    if( $kommune->erOvertatt() ) {
                        $kommune = $kommune->getOvertattAv();
                    }
                    $links[] = [
                        'link' => $kommune->getLink(),
                        'navn' => $kommune->getNavn(),
                        'tidligere' => $kommune->getTidligereNavnListe()
                    ];
                }
            }
            // Lands-arrangement?
            else {
                $links[] = [
                    'link' => UKM_HOSTNAME,
                    'navn' => 'UKM.no - forsiden'
                ];
            }
        }

        if (get_option('fylke')) { }
        break;
}


$WP_TWIG_DATA['links'] = $links;
echo WP_TWIG::render($template, $WP_TWIG_DATA);

wp_footer();
if (is_user_logged_in()) {
    echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}
