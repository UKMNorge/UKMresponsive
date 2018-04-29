<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

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
		if( defined( 'FULLSTORY_PATH' ) ) {
			$loader->addPath( FULLSTORY_PATH );
		}
		if( defined( 'UKMKONKURRANSE_PATH' ) ) {
			$loader->addPath( UKMKONKURRANSE_PATH .'/twig/' );
		}

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
		if( self::getDebug() ) {
			$environment['cache'] = false;
		}

		$twig = new Twig_Environment($loader, $environment);
		$twig->addGlobal('Sitemap', new Sitemap);
		$twig->addGlobal('SEO', new SEO);
		$twig->addGlobal('THEME_CONFIG', new WP_CONFIG);
		
		// Add dato-filter
		$filter_dato = new Twig_SimpleFilter('dato', 'WP_TWIG_date');
		$twig->addFilter($filter_dato);
		
		// Add maned-filter
		$filter_maned = new Twig_SimpleFilter('maned', 'WP_TWIG_maned');
		$twig->addFilter($filter_maned);
		
		// Add path-filter
		$filter_path = new Twig_SimpleFilter('UKMpath', 'UKMpath');
		$twig->addFilter($filter_path);

		// Add telefon-filter
		$filter_telefon = new Twig_SimpleFilter('telefon', 'UKMtelefon');
		$twig->addFilter($filter_telefon);
			
		// Add asset-function
		$function_ukmasset = new Twig_SimpleFunction('UKMasset', function( $path ) {
			if( 'ukm.dev' == UKM_HOSTNAME ) {
				return URL_THEME . '/_GRAFIKK_UKM_NO/'. $path;
			}
			return '//grafikk.ukm.no/UKMresponsive/'. $path;
		});
		$twig->addFunction($function_ukmasset);

		// Add GET-function
		$function_get = new Twig_SimpleFunction('GET', function( $var ) {
			if( !isset( $_GET[ $var ] ) ) {
				return false;
			}
			return $_GET[ $var ];

		});
		$twig->addFunction($function_get);
		
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