<?php
class blog {
	public function __construct() {
		$this->_loadWPInfo();
	}
	
	public function setTitle( $title ) {
		$this->title = $title;
		return $this;
	}
	public function getTitle() {
		return $this->title;
	}
	
	public function setUrl( $url ) {
		$this->url = $url;
		return $this;
	}
	public function getUrl() {
		return $this->url;
	}
	
	public function setFeed( $feed ) {
		$this->feed = $feed;
		return $this;
	}
	public function getFeed() {
		return $this->feed;
	}

	private function _loadWPInfo() {
		$this->setTitle( get_bloginfo('name') );
		$this->setUrl( get_bloginfo('url').'/' );
		$this->setFeed( get_bloginfo('rss_url') );
	}
}