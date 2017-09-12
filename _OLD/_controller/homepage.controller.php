<?php
require_once(THEME_PATH .'controller/page.controller.php');

class homepage extends page {
	var $posts_per_page = 7;
	
	public function __construct() {
		echo 'hei';
#		$this->posts = new posts();
	}
	
	public function getPosts() {
		return $this->posts;
	}	
}