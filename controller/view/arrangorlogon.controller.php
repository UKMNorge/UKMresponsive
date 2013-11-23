<?php

class ArrangorLogonController {
    
    protected $version;
    
    public function __construct()
    {
        if(!session_id()) {
            session_start();
        }
        
        $this->version = get_option('season');
        
        
    }
    
    public function login()
    {
        
    }
    
    public function logout()
    {
        
    }
    
    public function setCookie($cookie) 
    {
        if(is_array($cookie)) {
            foreach($cookie as $c) {
                setcookie($c, '', 1);
            }
        }
        else {
            setcookie($cookie, '', 1);
        }
    }
    
    public function unsetCookie($cookie) 
    {
        if(is_array($cookie)) {
            foreach($cookie as $c) {
                unset($_COOKIE[$c]);
            }
        }
        else {
            unset($_COOKIE[$c]);
        }
    }
    
    
    
}