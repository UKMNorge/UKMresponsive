<?php
class posts {
	public function __construct() {
		$this->_loadPosts();	
	}
	
	public function getFirst() {
		return $this->posts[0];
	}
	
	private function _loadPosts() {
		global $post;
		
		$this->posts = array();
		$this->page = array();
		
	    $paged = (get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	    
	    $posts = query_posts('posts_per_page='.$this->posts_per_page.'&paged='.$paged);
	    var_dump( $posts );
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