<?php

class SEO {

	var $debug = true;
	var $site_name = 'UKM.no';
	var $section = 'for ungdom';
	var $type = 'webpage';# : 'article';
	var $image = 'http://grafikk.ukm.no/profil/logo/UKM-logo_stor.png';
	var $author = 'http://ukm.no/blog/author/ukm-norge/';
	var $title = 'UKM.no';
	var $description = '400 festivaler hvor ungdom deltar med all slags kultur. Norges viktigste visningsarena for unge talenter';
	
	public function __construct( $canonical ) {
		$this->canonical = $canonical;
		
	}
	
	public function set( $key, $val ) {
		$this->$key = $val;
	}
	
	public function title( $val ) {
		$this->set('title', $val);
	}
	public function description( $val ) {
		$this->set('description', $val);
	}
	
	public function setImage( $data ) {
		if( is_object( $data ) ) {
			foreach( $data as $key => $val ) {
				$this->{'image_'.$key} = $val;
			}
		} else {
			$this->image = $data;
		}
	}
	
	public function jumbo( $post_id ) {	
		$jumboheader = get_post_meta($post_id, 'UKMjumbo_header', true);
		$jumbocontent = get_post_meta($post_id, 'UKMjumbo_content', true);
		
		if( $jumboheader )
			$this->set('title', $jumboheader );
			
		$metadescription = get_post_meta($post_id, 'UKMdescription', true);
		if( $metadescription ) {
			$this->set('description', $metadescription );
		} elseif( $jumbocontent) {
			$this->set('description', $jumbocontent );
		}
	}
	
	public function post( $WPOO_post ) {
		$this->set('title', $WPOO_post->title );
		$this->set('description', $WPOO_post->lead );
		$this->setImage( $WPOO_post->og_image );
	}
	
	public function section( $home ) {
		switch( $home ) {
			case 'arrangorer':
				$this->set( 'section', 'UKM for arrangører' );
				break;
			case 'voksneogpresse':
				$this->set( 'section', 'UKM for voksne og presse' );
				break;
			case 'derdubor':
				$this->set( 'section', 'UKM der du bor' );
				break;
			default:
				$this->set( 'section', 'UKM for ungdom' );
				break;
		}
	}
	
	public function article( $WPOO_post ) {
		$this->set('type', 'article');
		$this->set('author', $WPOO_post->author->link);
		$this->set('published_time', $WPOO_post->date);
		$this->set('modified_time', $WPOO_post->raw->post_modified_gmt);
	}
}