<?php
require_once(THEME_PATH . 'class/UKMresponsive.class.php');
require_once(THEME_PATH . 'controller/page.class.php');

class hovedmeny extends page {
	
	public function __construct( $view ) {
		parent::__construct( $view );
		
		global $post, $post_id;
		$this->page = new WPOO_Post( $post );
		
		$this->setNavButtonShow( false );
	}
}