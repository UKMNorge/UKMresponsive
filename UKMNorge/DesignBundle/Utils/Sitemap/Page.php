<?php

namespace UKMNorge\DesignBundle\Utils\Sitemap;

class Page {
	var $id = null;
	var $url = null;
	var $title = null;
	var $description = null;
	var $target = false;
	
	public function __construct( $data ) {
		$this->id = $data['id'];
		$this->url = $data['url'];
		$this->title = $data['title'];
		if( isset( $data['description'] ) ) {
			$this->description = $data['description'];
		}
		if( isset( $data['target'] ) ) {
			$this->target = $data['target'];
		}
	}
	
	public function getId() {
		return $this->id;
	}
	public function getUrl() {
		if( defined('UKM_HOSTNAME') && UKM_HOSTNAME != 'ukm.no' ) {
			return str_replace(array('ukm.no', 'egoego.no'), array(UKM_HOSTNAME, 'egoego.dev'), $this->url);
		}
		return $this->url;
	}
	public function getTitle() {
		return $this->title;
	}
	public function getDescription() {
		return $this->description;
	}
	public function getTarget() {
		return $this->target;
	}
	public function getTargetProperty() {
		return $this->target == false ? '' : 'target="'. $this->target .'"' ;
	}
	public function __toString() {
		return $this->getUrl();
	}
}
