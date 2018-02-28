<?php
/** 
	STATIC HELPER CLASS FOR frontpage.controller.php
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


	abstract public static function setState( $state );
#	abstract public static function _loadPameldingApen();

	public static function init( $pl_id ) {
		self::$pl_id = $pl_id;
		self::_loadMonstring();
		self::_loadPameldingStarter();
	}
	
	
	public static function _loadPameldingStarter() {
		$configDatePameldingStarter = str_replace('YYYY', (self::getMonstring()->getSesong()-1), WP_CONFIG::get('pamelding')['starter'] );
		self::$pameldingStarter = DateTime::createFromFormat( 'd.m.Y H:i:s', $configDatePameldingStarter .' 00:00:00' );
	}
	
	public static function getUKMTV() {
		require_once('UKM/tv_files.class.php');
		
		// Hent filer fra mønstringen
		$tv_files = new tv_files( 'place', self::getMonstring()->getId() );
		$tv_files->limit(1);
		$tv_files->fetch();
		
		return $tv_files->num_videos > 0;
	}
	
	
	public static function getPameldingStarter() {
		return self::$pameldingStarter;
	}
	public static function erPameldingStartet() {
		$now = new DateTime('now');
		return self::getPameldingStarter() < $now;
	}
	
	public static function getTemplate() {
		return self::$template;
	}
	
	public static function getState() {
		return self::$state;
	}
	
	public static function getMonstring() {
		return self::$monstring;
	}
	
	public static function getPameldingApen() {
		return self::$pameldingApen;
	}
		
	public static function harProgram() {
		self::_loadProgram();
		return self::$harProgram;
	}
	
	private static function _loadProgram() {
		if( null === self::$harProgram ) {
			self::$harProgram = self::getMonstring()->getProgram()->getAntall() > 0;
		}
		return self;
	}
	
	private static function _loadMonstring() {
		self::$monstring = new monstring_v2( self::$pl_id );
	}
	
	public static function getInfoPage() {
		if( null === self::$harInfoPage ) {
			self::_loadInfoPage();
		}
		return self::$infoPage;
	}
	public static function harInfoPage() {
		$page = get_page_by_path('info');
		
		return null != $page;
	}

	public static function _loadInfoPage() {
		$page = get_page_by_path('info');
		self::$infoPage = new page( $page );
		self::$harInfoPage = ( is_object( $page ) && $page->post_status == 'publish' );
		return self;
	}
	
	
		/**
	 * Bruker siden live-modulen? vis lenke / embedkode
	 *
	 */	
	public static function getLive() {
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
					'code' => $embedcode
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