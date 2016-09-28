<?php
	class page {
	
	public function __construct() {
	}
	
	public function getView() {
		return 'kommune/kommune_'. $this->view;
	}
	
	/**
	 * Returnerer data-arrayet for controlleren
	 *
	 **/
	public function renderData() {
		$data = array();
		foreach( $this->renderData as $view_key => $object_key ) {
			if( isset( $this->$object_key ) ) {
				$data[ $view_key ] = $this->$object_key;
			}
		}
		$data['page_nav'] = $this->__buildNavigation();
		return $data;
	}

	public function SEO( $SEO ) {
		$this->_requireMonstring();
		
		$description = 'Nyheter og informasjon om UKM '. $this->monstring->navn;
		if( $this->pl->registered() ) {
			$description .= ' - '. $this->monstring->starter_tekst.', '. $this->monstring->sted;
		}
		$SEO->description( $description );
		
		return $SEO;
	}
	
	public function JUMBO( $JUMBO ) {
		
		return $JUMBO;
	}
	
	protected function registerNav( $key, $object ) {
		$this->nav[ $key ] = $object;
	}
	/**
	 * Loop alle mulige menyer, og inkluder de definerte
	 *
	 **/
	protected function __buildNavigation() {
		$this->page_nav = array();
		
		foreach( $this->navOrder as $menuKey ) {
			if( isset( $this->nav[$menuKey] ) ) {
				$this->page_nav[] = $this->nav[$menuKey];
			}
		}
		return $this->page_nav;
	}

	/**
	 * Last inn options som tilhører bloggen
	 *
	 */
	protected function _loadWPoptions() {
		$this->kommune = get_option('kommune');
		$this->pl_id = get_option('pl_id');
	}
	
	/**
	 * Last inn posts fra bloggen
	 *
	 */
	protected function _loadPosts() {
		global $post;
		
		$this->renderData['page'] = 'page';	
		$this->renderData['posts'] = 'posts';	

		$this->posts = array();
		$this->page = array();
		
	    $paged = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	    
	    $posts = query_posts('posts_per_page='.$this->posts_per_page.'&paged='.$paged);
	    while(have_posts()) {
	       the_post();
	       $this->posts[] = new WPOO_Post($post); 
	    }
	    
	    $npl = get_next_posts_link();
	    if($npl) {
	        $npl = explode('"',get_next_posts_link()); 
	        $this->nextpost = $npl[1];
			$this->renderData['nextpost'] = 'nextpost';	
	    }
	    $ppl = get_previous_posts_link();
	    if($ppl) {
	        $ppl = explode('"',$ppl); 
	        $this->prevpost = $ppl[1];
			$this->renderData['prevpost'] = 'prevpost';	
	    }
	}
}