<?php
require_once('UKM/sql.class.php');

class ArrangorLogonController {
    
    protected $version;
    protected $cookies;
    
    public function __construct()
    {
        if(!session_id()) {
            session_start();
        }
        
        $this->version = get_option('season');
        
        $this->cookies = array(
            'UKM_username', 'UKM_email',
            'UKM_kommune', 'UKM_pl_id',
            'UKM_pl_type', 'UKM_pl_fylke',
            'UKM' . $this->version . '_username',
            'UKM' . $this->version . '_email',
            'UKM' . $this->version . '_kommune',
            'UKM' . $this->version . '_pl_id',
            'UKM' . $this->version . '_pl_type',
            'UKM' . $this->version . '_pl_fylke'
        );
    }
    
    public function renderAction()
    {
        if(isset($_GET['logout'])) {
            $this->logout();
        }
        
        if(!empty($_POST['ukm_user']) && !empty($_POST['ukm_pass'])) {
            $user = $_POST['ukm_user'];
            $pass = $_POST['ukm_pass'];
            
            $this->login($user, $pass);
        }
    }
    
    public function login($user, $pass)
    {
        global $wpdb;
        
        $wpdb->query(
            $wpdb->prepare(
                'SELECT '.
                '`b`.`b_name` AS `username`, ' .
                '`b`.`b_email` AS `email`, ' .
                '`b`.`b_kommune` AS `kommune`, ' .
                '`b`.`b_fylke` AS `pl_fylke` ' .
                'FROM `ukm_brukere` AS `b` ' .
                'WHERE `b_name` = %s ' .
                'AND `b_password` = %s ',
                $user, $pass
            )
        );
        
        $query = 'SELECT '.
                 '`b`.`b_name` AS `username`, ' .
				 '`b`.`b_email` AS `email`, ' .
				 '`b`.`b_kommune` AS `kommune`, ' .
				 '`b`.`b_fylke` AS `pl_fylke` ' .
				 'FROM `ukm_brukere` AS `b` ' .
				 'WHERE `b_name` = "#user" ' .
				 'AND `b_password` = "#pass" ';
				 
		
		$res = $wpdb->query($qry);
        
        $sql = new SQL($query);
        
        $result = $sql->run('array');
        
        
    }
    
    public function logout()
    {
        $this->setCookie($this->cookies);
        $this->unsetCookie($this->cookies);
        $this->redirect("Location: " . get_permalink() . '?UKMstatus=logget_ut');
    }
    
    public function setCookie($cookie, $value = '') 
    {
        if(is_array($cookie)) {
            foreach($cookie as $c) {
                setcookie($c, $value, 1);
            }
        }
        else {
            setcookie($cookie, $value, 1);
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
    
    public function redirect($url)
    {
        header("location: " . $url);
        die();
    }
    
    
    
}