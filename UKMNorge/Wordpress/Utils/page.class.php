<?php
require_once(PATH_THEME . 'UKMNorge/Wordpress/Utils/blocks.class.php');

class page {
	
	public function __construct( $page=null) {
		if( null === $page ) {
			global $post, $post_id;
		} else {
			$post = $page;
		}
		$this->page = new WPOO_Post( $post );
		
		$this->_setup_blocks();
	}
	
	public function getPage() {
		return $this->page;
	}
	
	public function getPageBlocks() {
		return $this->blocks;
	}
	
	/**
	 * _setup_blocks
	 * 
	 * Hvis gitt side har undersider som benytter sidemaler (Blocks)
	 * skal disse listes ut i sidevisningen
	 *
	 * @return void
	 *
	**/ 
	private function _setup_blocks() {
		$this->blocks = new blocks( $this->getPage()->ID );
	}
}