<?php

namespace UKMNorge\DesignBundle\Services;

use UKMNorge\DesignBundle\Utils\Sitemap;
use \Symfony\Component\HttpKernel\Config\FileLocator;

class SitemapService {	
	private $fileLocator;
	
	public function __construct(FileLocator $fileLocator) {
		$this->fileLocator = $fileLocator;
		
		Sitemap::loadFromYamlFile( $this->fileLocator->locate('@UKMDesignBundle/Resources/config/sitemap.yml' ) );
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
}
