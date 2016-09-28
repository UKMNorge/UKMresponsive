<?php
require_once(THEME_PATH . 'class/blocks.class.php');
require_once(THEME_PATH . 'class/UKMresponsive.class.php');

class page extends UKMresponsive {
	
	public function __construct( $view ) {
		parent::__construct( $view );
		
		global $post, $post_id;
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