<?php
require_once('wp_twig.dateFilter.inc.php');
require_once('wp_twig.ukm.inc.php');

class WP_TWIG {
	private static $debug = false;
	private static $templateDir = false;
	private static $cacheDir = false;
	
	static function setDebug( $status ) {
		self::$debug = $status;
		return self;
	}
	static function getDebug() {
		return self::$debug;
	}
	
	static function setTemplateDir( $dir ) {
		self::$templateDir = $dir;
		return $self;
	}
	static function getTemplateDir() {
		return self::$templateDir;
	}
	
	static function getCacheDir() {
		return self::$cacheDir;
	}
	static function setCacheDir( $dir ) {
		self::$cacheDir = $dir;
		return self;
	}
	
	static function render( $template, $data ) {
		if( false == self::getTemplateDir() ) {
			throw new Exception('Cannot render Twig. Missing templateDir parameter');
		}
		// Loader + filesystem
		require_once('Twig/Autoloader.php');
		Twig_Autoloader::register();
		$loader = new Twig_Loader_Filesystem( self::getTemplateDir() );
		
		if( self::getDebug() ) {
			$environment['debug'] = self::getDebug();
		} else {
			$environment = [];
		}

		// Cache
		if( false == self::getCacheDir() ) {
			self::setCacheDir( sys_get_temp_dir() .'/wp_twig_cache/' );
		}
		if( !file_exists( self::getCacheDir() ) ) {
			mkdir( self::getCacheDir(), 0777 );
		}
		$environment['cache'] = self::getCacheDir();
		$environment['auto_reload'] = true;

		$twig = new Twig_Environment($loader, $environment);
		
		// Add dato-filter
		$filter = new Twig_SimpleFilter('dato', 'WP_TWIG_date');
		$twig->addFilter($filter);
		
		// Add path-filter
		$filter = new Twig_SimpleFilter('UKMpath', 'UKMpath');
		$twig->addFilter($filter);
	
		// Add asset-filter
		$filter = new Twig_SimpleFilter('UKMgrafikk', 'UKMgrafikk');
		$twig->addFilter($filter);
	
		// Debug
		if( self::getDebug() ) {
			$twig->addExtension( new Twig_Extension_Debug() );
		}

		// Set language
		putenv('LC_ALL=nb_NO');
		setlocale(LC_ALL, 'nb_NO');
		
		return $twig->render($template .'.html.twig', $data);
	}
}