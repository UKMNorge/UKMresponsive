<?php

namespace UKMNorge\DesignBundle\Services;

use Exception;
use UKMNorge\DesignBundle\Utils\Sitemap;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;


class SitemapService {	
	private $cacheDir = null;
	private $cacheFile = null;
	private $cacheTime = 3600; // 1H in seconds
	private $githubUrl = 'https://raw.githubusercontent.com/UKMNorge/UKMresponsive/master/UKMNorge/DesignBundle/Resources/config/sitemap.yml';
	private $fileLocator;
	
	public function __construct(FileLocator $fileLocator, $cacheDir) {
		$this->fileLocator = $fileLocator;
		$this->cacheDir = $cacheDir .'/ukmdesignbundle/';
		$this->cacheFile = $this->cacheDir . 'sitemap.yml';
		
		/**
		 * LOAD SITEMAP.YML
		 * 
		 * Step 1: Check for locally cached sitemap file
		 *         This file will be fetched from github.com at $this->cacheTime interval
		 *         (required the cron job is correctly setup)
		 *         https://APP_URL/cron/designbundle/sync_sitemap/
		 *
		 * Step 2: If step 1 failed, fetch the local sitemap file
		 *         This file is also fetched for github.com, but since the process is manual
		 *         A minor functions test is required; Since sitemap is included in all routes
		 *         the test would require dev to open one page in the app to see that it still opens.
		 *
		**/
		$loadInstallSitemap = true; // The one mentioned in step 2

		// check for step 1-file, and try loading it
		if( file_exists( $this->cacheFile ) ) {
			try {
				Sitemap::loadFromYamlFile( $this->cacheFile );
				$loadInstallSitemap = false;
			} catch ( ParseException $e ) {
				$loadInstallSitemap = true;
			}
		}

		// If step 1 failed, load file mentioned in step 2
		if( $loadInstallSitemap ) {
			try {
				Sitemap::loadFromYamlFile( $this->fileLocator->locate('@UKMDesignBundle/Resources/config/sitemap.yml' ) );
			} catch( ParseException $e ) {
				throw new \Exception('Auch! Loading sitemap failed hard. Please contact support@ukm.no or site owner');
			}
		}
	}
	
	public function getSections() {
		return Sitemap::getSections();
	}
	public function getSection( $id ) {
		return Sitemap::getSection( $id );
	}
	public function getPage( $sectionId, $pageId ) {
		return Sitemap::getPage( $sectionId, $pageId );
	}
	public function addSection( $section ) {
		return Sitemap::addSection( $section );
	}	
	
	public function loadFromGithub($timeout=1) {
		$data = $this->_doLoadFromGithub( $timeout );
		
		$this->_doStoreYaml( $data );
	}
	
	public function getGithubLink() {
		return $this->githubUrl;
	}
	
	private function _doStoreYaml( $data ) {
		$fs = new Filesystem();
		$this->_requireCacheDir( $fs );

		try {
			$fs->dumpFile( $this->cacheFile, $data);
		} catch( IOException $e ) {
			throw new Exception('Kunne ikke skrive sitemap-fil', 3);
		}
		return true;
	}
	
	private function _requireCacheDir( $fs ) {
		// Opprett cachedir
		if( !file_exists( $this->cacheDir ) ) {
			try {
				$fs->mkdir( $this->cacheDir );
			} catch (IOException $e) {
				throw new Exception('Kunne ikke opprette cacheDir', 2);
			}
		}
		return true;
	}
	
	// TODO: check header 200 OK
	// https://stackoverflow.com/a/41135574
	private function _doLoadFromGithub( $timeout ) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->githubUrl);
		curl_setopt($curl, CURLOPT_REFERER, $_SERVER['PHP_SELF']);
		curl_setopt($curl, CURLOPT_USERAGENT, "UKMNorge API");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_HEADER, 0);

		$result = curl_exec( $curl );
		
		if( !empty( $result ) ) {
			return $result;
		}
		throw new Exception('Kunne ikke hente data fra Github', 1);
	}
}
