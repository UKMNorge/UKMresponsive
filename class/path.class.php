<?php

class paths extends collection {
	public function __construct() {
		$this->_loadConfig();
	}
	
	public function create( $id, $path ) {
		$pathObject = new path( $id, $path );

		$this->add( $id, $pathObject );
		return $this;
	}
	
	private function _loadConfig() {
		require( THEME_PATH .'/config/path.config.php');
		
		foreach( $PATH as $id => $val ) {
			$this->create( $id, $val );
		}
	}
}

class path {
	var $id = false;
	var $path = false;
	
	public function __construct( $id, $path ) {
		$this->setId( $id );
		$this->setPath( $path );
	}
	
	public function __toString() {
		return $this->getPath();
	}
	
	public function setId( $id ) {
		$this->id = $id;
		return $this;
	}
	public function getId() {
		return $this->id;
	}
	
	public function setPath( $path ) {
		$this->path = $path;
		return $this;
	}
	public function getPath() {
		return $this->path;
	}
}
