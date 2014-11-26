<?php

class StyrerommetController {
    
    public $post;
    
    public function __construct()
    {
        global $post;
        the_post();
        $this->post = new WPOO_Post($post);
    }
    
    public function renderAction()
    {
        return array(
            'post' => $this->post,
            'files' => $this->getFiles()
        );   
    }
    
    public function getFiles()
    {
    	$args = array(
    		'orderby'		 => 'post_title',
    		'order'          => 'ASC',
    		'post_type'      => 'attachment',
    		'post_parent'    => $this->post->ID,
    		'post_mime_type' => 'application/pdf',
    		'numberposts'	 => -1,
    	);
    	
        $attachments = get_posts($args);
        
        if($attachments) {
            foreach($attachments as $attachment) {
                $mmsort[$attachment->post_title] = array(
                    'title' => $attachment->post_title,
                    'url' => wp_get_attachment_url($attachment->ID)
                );
            }
            krsort($mmsort);
        }
        
        return $mmsort;
    }
}

$controller = new StyrerommetController();
$DATA = array_merge($DATA, $controller->renderAction());