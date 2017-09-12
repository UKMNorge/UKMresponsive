<?php
class block {
	public function __construct( $post ) {
		$this->page = new WPOO_Post( $post );
		
		$this->_setup();
	}
	
	
	private function _setup() {
	    $this->type = get_post_meta( $this->page->ID, 'UKM_block', true );
		$this->anchor = $this->page->raw->post_name;
	    
	    switch( $this->type ) {
		    case 'jumbo':
		    	$this->_setup_block_post();
		    	$this->_block_jumbo_image( $this->title, $this->content );
		    	break;
			case 'lead':
				$this->_lead();
				break;
		    case 'lead_center':
		    	$this->_lead_center();
				break;
			case 'image_left':
				$this->_image_oob_left();
				break;
			case 'image_right':
				$this->_image_oob_right();
				break;
			default:
				return false;
	    }
		
		return $this;
	}

	private function _setup_block_post() {
		$post = get_post( $this->page->ID );
	
		if( !is_object( $post ) ) {
			return;
		}
	
		$this->post = new stdClass();
		$this->post->ID = $this->page->ID;
		$this->post->data = new WPOO_Post( $post );
		$this->title = $this->post->data->title;
		if( isset( $this->post->data->meta->lead ) )
			$this->lead = $this->post->data->meta->lead;
		else		
			$this->lead = null;
		$this->content = $this->post->data->content;
	}
	
	private function _lead() {
		$this->_setup_block_post();	
		return $this;
	}
	
	private function _lead_center() {
		$this->_setup_block_post();	
		return $this;
	}

	private function _image_oob_left() {
		$this->_block_jumbo_image('', '');
		$this->_setup_block_post();	
		$this->type = 'oob_left';
		return $this;
	}

	private function _image_oob_right( ) {
		$this->_block_jumbo_image('', '');
		$this->_setup_block_post( $block, $post_id );
		$this->type = 'oob_right';
		return $this;
	}
	
	private function _block_jumbo_image( $title, $subtitle ) {
		$this->type = 'jumbo_image';
		$this->image = new stdClass();
		$this->image->xs = get_post_meta( $this->page->ID, 'image_xs', true);
		$this->image->sm = get_post_meta( $this->page->ID, 'image_sm', true);
		$this->image->md = get_post_meta( $this->page->ID, 'image_md', true);
		$this->image->lg = get_post_meta( $this->page->ID, 'image_lg', true);
		$this->title = $title;
		$this->subtitle = $subtitle;
		return $this;
	}


	/**
	 * _container_arrowbox
	 *
	 * En container som inneholder en annen blokk (_block_icons)
	 *
	 * @param block
	 *
	**/
	private function _container_arrowbox( $content_block ) {
		$this->type = 'container';
		$this->container = 'arrowbox';
		$this->contained = $content_block;
		
		return $block;
	}
	
	/**
	 * _block_icons
	 *
	 * Kun mulig å sette opp via hardkodet oppsett pga $icons som er et array ikoner
	 *
	**/
	private function _block_icons( $anchor, $icons, $title=false, $lead=false ) {
		$block = new stdClass();
		$block->anchor = $anchor;
		$block->type = 'icons';
		$block->icons = $icons;
		if( $title ) 
			$block->title = $title;
		if( $lead ) 
			$block->lead = $lead;
		
		return $block;
	}
}