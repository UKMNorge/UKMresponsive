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
		$configDatePameldingStarter = str_replace('YYYY', self::getMonstring()->getSesong(), WP_CONFIG::get('pamelding')['starter'] );
		self::$pameldingStarter = DateTime::createFromFormat( 'd.m.Y H:i:s', $configDatePameldingStarter .' 00:00:00' );
	}
	
	
	public static function getPameldingStarter() {
		return self::$pameldingStarter;
	}
	
	public static function getTemplate() {
		return self::$template;
	}
	
	public static function getMonstring() {
		return self::$monstring;
	}
	
	public static function getPameldingApen() {
#		self::_loadPameldingApen();
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
			self::_loadFylkeInfo();
		}
		return self::$infoPage;
	}
	public static function harInfoPage() {
		if( null === self::$harInfoPage ) {
			self::_loadInfoPage();
		}
		return self::$harInfoPage;
	}

	public static function _loadInfoPage() {
		$page = get_page_by_path('info');
		self::$infoPage = new page( $page );
		self::$harInfoPage = ( is_object( $page ) && $page->post_status == 'publish' );
		return self;
	}
}