<?php

class AuthorController {
    
    protected $authorId;
    
    public function __construct()
    {   
        global $post;
        $this->authorId = $post->post_author;
    }
    
    public function renderAction() 
    {
        $data = array();
        $data['author'] = new WPOO_Author(get_userdata($this->authorId));
        
        return $data;
    }
}

$controller = new AuthorController();
$DATA = array_merge($DATA, $controller->renderAction());