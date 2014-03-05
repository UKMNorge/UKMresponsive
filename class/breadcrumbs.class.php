<?php

class crumbs {
	public $crumbs = array();
	public $addJumbo = true;
	
	public function __construct(){}
	
	
	public function add($url,$title) {
		$this->crumbs[] = (object) array('link'=> $this->fixLocal($url), 'title'=>$title);
	}
	
	public function fixLocal( $url ) {
		return str_replace('ukm.no', $_SERVER['HTTP_HOST'], $url );
	}
	
	public function home( $home ) {
		$this->crumbs = array();
		switch( $home ) {
			case 'arrangorer':
				$this->add( 'http://'. $_SERVER['HTTP_HOST'] .'/arrangor/', 'UKM for arrangører' );
				break;
			case 'voksneogpresse':
				$this->add( 'http://'. $_SERVER['HTTP_HOST'] .'/om/', 'UKM for voksne og presse' );
				break;
			case 'derdubor':
				$this->add( 'http://'. $_SERVER['HTTP_HOST'] .'/din_monstring/', 'UKM der du bor' );
				break;
			default:
				$this->add( 'http://'. $_SERVER['HTTP_HOST'] .'/', 'UKM for ungdom' );
				break;
		}
	}
	public function get() {
		return $this->crumbs;
	}
}