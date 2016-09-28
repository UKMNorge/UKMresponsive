<?php

class SEO {

	var $site_name = false;
	var $section = false;
	var $type = 'webpage';# : 'article';
	var $image = false;
	var $author = false;
	var $title = false;
	var $description = false;
	
	public function __construct( $canonical ) {
		$this->canonical = $canonical;
		
		$this->_loadDefaultConfig();
	}
	
	private function _loadDefaultConfig() {
		require(THEME_PATH .'config/seo.config.php');
		foreach( $SEO as $key => $val ) {
			$functionName = 'set'.ucfirst($key);
			if( method_exists( $this, $functionName ) ) {
				$this->$functionName( $val );
			}
		}
		unset( $SEO );
	}
			
/*
	MOVE TO LAYOUT ? 
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
*/

/*
	MOVE TO POST-CONTROLLER
	public function post( $WPOO_post ) {
		$this->SEO->setTitle( $WPOO_post->title );
		$this->SEO->setDescription( $WPOO_post->lead );
		if( isset( $WPOO_post->og_image ) )
			$this->setImage( $WPOO_post->og_image );
	}
		
	public function article( $WPOO_post ) {
		$this->setType('type', 'article');
		$this->set('author', $WPOO_post->author->link);
		$this->set('published_time', $WPOO_post->date);
		$this->set('modified_time', $WPOO_post->raw->post_modified_gmt);
	}
*/	
	
	public function setSitename( $sitename ) {
		$this->sitename = $sitename;
	}
	public function getSitename() {
		return $this->sitename;
	}
	
	public function setAuthor( $author ) {
		$this->author = $author;
	}
	public function getAuthor() {
		return $this->author;
	}
	
	public function setSection( $section ) {
		$this->section = $section;
	}
	public function getSection() {
		return $this->section;
	}
	
	public function setTitle( $title ) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	
	public function setImage( $image ) {
		if( is_object( $image ) ) {
			foreach( $image as $key => $val ) {
				$this->{'image_'.$key} = $val;
			}
		} else {
			$this->image = $image;
		}

	}
	public function getImage( $image ) {
		return $this->image;
	}
	
	public function setDescription( $description ) {
		$this->description = $description;
	}
	public function getDescription() {
		return $this->description;
	}
}	