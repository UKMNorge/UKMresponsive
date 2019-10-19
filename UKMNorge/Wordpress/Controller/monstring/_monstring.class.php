<?php

use UKMNorge\Geografi\Fylker;
use UKMNorge\Geografi\Kommune;
use UKMNorge\Nettverk\Omrade;

/** 
 *	STATIC HELPER CLASS FOR frontpage.controller.php
**/
abstract class monstringController {
	static $pl_id = false;
	static $monstring;

	static $template = null;
	static $state = 'pre_pamelding';

	static $pameldingApen = false;
	static $pameldingStarter = null;
	
	static $harProgram = null;

	static $harInfoPage = null;
    static $infoPage = null;
    
    static $geo_id = null;
    static $omrade = null;
    static $fylke = null;
    static $kommune = null;


	abstract public static function setState( $state );
#	abstract public static function _loadPameldingApen();

	public static function init( $pl_id, $geo_id=null ) {
        static::$pl_id = $pl_id;
        if( is_numeric( $pl_id ) ) {
            static::_loadMonstring();
            static::_loadPameldingStarter();
        }
        if( $geo_id != null ) {
            static::$geo_id = $geo_id;
        }
    }
    public static function harMonstring() {
        return is_numeric( static::$pl_id );
    }

    public static function getFylke() {
        if( null == static::$fylke ) {
            if( static::erFylke() ) {
                static::$fylke = Fylker::getById( static::$geo_id );
            }
            elseif( static::erKommune() ) {
                static::$fylke = static::getKommune()->getFylke();
            }
        }
        return static::$fylke;
    }

    public static function getOmrade() {
        if( static::$omrade == null ) {
            static::$omrade = new Omrade( static::getType(), static::getGeoId() );
        }
        return static::$omrade;
    }

    public static function getKommune() {
        if( null == static::$kommune ) {
            if( static::erKommune()) {
                static::$kommune = new Kommune( static::getGeoId() );   
            }
            if( static::erFylke() ) {
                throw new Exception(
                    'Beklager, kan ikke hente kommune for et fylke'
                );
            }
        }
        return static::$kommune;
    }

    /**
     * Hent hvilket område dette er 
     *
     * @return Int $omrade
     */
    public static function getGeoId() {
        return static::$geo_id;
    }

    /**
     * Hent hvilken type controller dette er
     *
     * @throws Exception ukjent controller
     * @return String $omrade_type
     */
    public static function getType() {
        if( get_called_class() == 'kommuneController' ) {
            return 'kommune';
        }
        if( get_called_class() == 'fylkeController' ) {
            return 'fylke';
        }
        throw new Exception(
            'Beklager, ukjent type monstringController'
        );
    }

    public static function erFylke() {
        return static::getType() == 'fylke';
    }
    public static function erKommune() {
        return static::getType() == 'kommune';
    }
	
	
	public static function _loadPameldingStarter() {
		$configDatePameldingStarter = str_replace('YYYY', (static::getMonstring()->getSesong()-1), WP_CONFIG::get('pamelding')['starter'] );
		static::$pameldingStarter = DateTime::createFromFormat( 'd.m.Y H:i:s', $configDatePameldingStarter .' 00:00:00' );
	}
	
	public static function getUKMTV() {
        if( !static::harMonstring() ) {
            return false;
        }
		require_once('UKM/tv_files.class.php');
		
		// Hent filer fra mønstringen
		$tv_files = new tv_files( 'place', static::getMonstring()->getId() );
		$tv_files->limit(1);
		$tv_files->fetch();
		
		return $tv_files->num_videos > 0;
	}
	
	
	public static function getPameldingStarter() {
		return static::$pameldingStarter;
	}
	public static function erPameldingStartet() {
		$now = new DateTime('now');
		return static::getPameldingStarter() < $now;
	}
	
	public static function getTemplate() {
		return static::$template;
	}
	
	public static function getState() {
		return static::$state;
	}
	
	public static function getMonstring() {
        if( !static::harMonstring() ) {
            return false;
        }
		return static::$monstring;
	}
	
	public static function getPameldingApen() {
		return static::$pameldingApen;
	}
		
	public static function harProgram() {
        if( !static::harMonstring() ) {
            return false;
        }
		static::_loadProgram();
		return static::$harProgram;
	}
	
	
	public static function harPameldte() {
        if( !static::harMonstring() ) {
            return false;
        }
		return static::$monstring->getInnslag()->harInnslag();//->getAntall( true ) > 0;
	}
	
	private static function _loadProgram() {
		if( null === static::$harProgram ) {
			static::$harProgram = static::getMonstring()->getProgram()->getAntall() > 0;
		}
	}
	
	private static function _loadMonstring() {
		static::$monstring = new monstring_v2( static::$pl_id );
	}
	
	public static function getInfoPage() {
		if( null === static::$harInfoPage ) {
			static::_loadInfoPage();
		}
		return static::$infoPage;
	}
	public static function harInfoPage() {
		$page = get_page_by_path('info');
		
		return null != $page;
	}

	public static function _loadInfoPage() {
		$page = get_page_by_path('info');
		static::$infoPage = new page( $page );
		static::$harInfoPage = ( is_object( $page ) && $page->post_status == 'publish' );
	}
	
	
		/**
	 * Bruker siden live-modulen? vis lenke / embedkode
	 *
	 */	
	public static function getLive() {
		// Inntil mønstringen er startet må det stå en tekst,
		// men ikke lenke.
		$link		= get_option('ukm_live_link');
		$embedcode	= get_option('ukm_live_embedcode');
			
		$show_embed = false;
		if( $embedcode ) {
			$perioder 	= get_option('ukm_hendelser_perioder');
			foreach( $perioder as $p ) {
				if( $p->start < time() && $p->stop > time() ) {
					$show_embed = true;
					break;
				}
			}
		}
			
		if( $show_embed ) {
			return 
				[
					'type' => 'embed',
					'code' => stripslashes( $embedcode )
				];
		}
		if( $link ) {
			return 
				[
					'type' => 'link',
					'link' => $link
				];
		}
		return false;
	}
}