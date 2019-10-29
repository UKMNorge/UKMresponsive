<?php
namespace UKMNorge\DesignBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use UKMNorge\DesignBundle\Utils\Sitemap;

class DesignBundleExtension extends AbstractExtension
{

	public function getName() {
		return "DesignBundleExtension";
	}
	
	public function getFilters()
	{
		return array(
			new TwigFilter('UKMpath', array($this, 'UKMpathFilter')),
            new TwigFilter('dato', array($this, 'dato')),
            new TwigFilter('oneline', [$this, 'oneline']),

		);
	}

	public function getFunctions() {
		return array(
			new TwigFunction('UKMasset', array($this, "UKMasset")),
			new TwigFunction('THEME_CONFIG', array($this, "theme_config")),
            new TwigFunction('UKMroute', array($this, "theme_config")),
            
		);
	}

	public function UKMasset($path)
	{
		if ( defined('UKM_HOSTNAME') && UKM_HOSTNAME == 'ukm.dev' ) {
			return '//ukm.dev/wp-content/themes/UKMresponsive/_GRAFIKK_UKM_NO/'. $path;
		}
		return '//grafikk.ukm.no/UKMresponsive/'. $path;
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
	
	public function dato($time, $format='d.M Y H:i') {
		if( !is_string( $time ) && get_class( $time ) == 'DateTime' ) 
		{
			$time = $time->getTimestamp();
		}
		elseif( is_string( $time ) && !is_numeric( $time ) ) 
		{
			$time = strtotime($time);
		}
		$date = date($format, $time);
	
		return str_replace(array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday',
								 'Mon','Tue','Wed','Thu','Fri','Sat','Sun',
								 'January','February','March','April','May','June',
								 'July','August','September','October','November','December',
								 'Jan','Feb','Mar','Apr','May','Jun',
								 'Jul','Aug','Sep','Oct','Nov','Dec'),
						  array('mandag','tirsdag','onsdag','torsdag','fredag','lørdag','søndag',
						  		'man','tir','ons','tor','fre','lør','søn',
						  		'januar','februar','mars','april','mai','juni',
						  		'juli','august','september','oktober','november','desember',
						  		'jan','feb','mar','apr','mai','jun',
						  		'jul','aug','sep','okt','nov','des'), 
						  $date);
    }
    
    /**
     * TWIG-filter |oneline
     * Fjerner linjeskift
     *
     * @param String $multiline
     * @return String $singelline
     */
    public function oneline( String $multiline ) {
        return str_replace(["\r","\n"],'', $multiline);
    }
}