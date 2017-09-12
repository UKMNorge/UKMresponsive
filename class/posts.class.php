<?php
class posts {
	var $posts_per_page = 12;
	var $paged = 1;
	var $nextpage = false;
	var $prevpage = false;
	
	public function __construct( $posts_per_page=null ) {
		$this->paged = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		
		if( $posts_per_page ) {
			$this->setPostsPerPage( $posts_per_page );
		}

		$this->_loadPosts();
	}
	
	public function setPostsPerPage( $posts_per_page ) {
		$this->posts_per_page = $posts_per_page;
	}
	public function getPostsPerPage() {
		return $this->posts_per_page;
	}
	
	public function getPaged() {
		return $this->paged > 1;
	}
	
	public function getPage() {
		return $this->paged;
	}
	
	public function getPageNext() {
		return $this->nextpage;
	}
	public function getPagePrev() {
		return $this->prevpage;
	}
	
	public function getFirst() {
		return $this->posts[0];
	}
	
	public function getAll() {
		return $this->posts;
	}
	
	public function getAntall() {
		return sizeof( $this->getAll() );
	}
	
	private function _loadPosts() {
		global $post;
		$this->posts = array();
		$this->page = array();
			    
	    $posts = query_posts('posts_per_page='.$this->getPostsPerPage().'&paged='.$this->getPage());
	    while(have_posts()) {
	       the_post();
	       $this->posts[] = new WPOO_Post($post); 
	    }
	    
	    $npl = get_next_posts_link();
	    if($npl) {
	        $npl = explode('"',get_next_posts_link()); 
	        $this->nextpage = $npl[1];
	    }
	    $ppl = get_previous_posts_link();
	    if($ppl) {
	        $ppl = explode('"',$ppl); 
	        $this->prevpage = $ppl[1];
	    }
	    wp_reset_postdata(); 
	}
}