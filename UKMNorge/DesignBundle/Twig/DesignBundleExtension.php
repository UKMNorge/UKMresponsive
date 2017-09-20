<?php
namespace UKMNorge\DesignBundle\Twig;

use UKMNorge\DesignBundle\Utils\Sitemap;

class DesignBundleExtension extends \Twig_Extension
{

    public function getName() {
        return "DesignBundleExtension";
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('UKMpath', array($this, 'UKMpathFilter')),
            
        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('UKMasset', array($this, "UKMasset")),
            new \Twig_SimpleFunction('THEME_CONFIG', array($this, "theme_config")),
            new \Twig_SimpleFunction('UKMroute', array($this, "theme_config")),
        );
    }

    public function UKMasset($path)
    {
        return '//grafikk.ukm.no/'. $path;
    }
    
    public function theme_config( $key ) {
            return 'HENT CONFIG HER: '. $key;
    }
    
    public function UKMpathFilter( $path ) {
        return $path;
    }
    
    public function ukm_route( $section, $page ) {
	    return Sitemap::getPage( $section, $page );
    }
}