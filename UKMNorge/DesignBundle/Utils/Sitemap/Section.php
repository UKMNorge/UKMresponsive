<?php

namespace UKMNorge\DesignBundle\Utils\Sitemap;

use UKMNorge\DesignBundle\Utils\Sitemap\Pages;
use UKMNorge\DesignBundle\Utils\Sitemap\Page;

class Section {
	var $id = null;
	var $url = null;
	var $name = null;
	var $pages = null;
	
	public function __construct( $id, $data ) {
		$this->id = $id;
		$this->url = $data['url'];
		$this->name = $data['title'];
		
		$this->pages = new Pages();
		$this->_loadPages( $data );
	}
	
	public function getId() {
		return $this->id;
	}
	public function getUrl() {
		return $this->url;
	}
	public function getName() {
		return $name;
	}
	
	private function _loadPages( $data ) {
		if( isset( $data['pages'] ) && is_array( $data['pages'] ) ) {
			foreach( $data['pages'] as $id => $page ) {
				$this->pages->add( new Page( $id, $page ) );
			}
		}
	}
}
