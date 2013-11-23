<?php

class AuthorController {
    
    protected $authorId;
    
    public function __construct()
    {
        $this->authorId = the_author_meta('ID');
    }
    
    public function renderAction() 
    {
        $data[$author] = new WPOO_Author(get_userdata($this->authorId));
        
        return $data;
    }
}

$controller = new AuthorController();
$DATA = array_merge($data, $controller->renderAction());