<?php
require_once('UKM/sql.class.php');

class ArrangorLogonController {
    
    protected $version = '2013_10';
    protected $cookies;
    
    public function __construct()
    {
        if(!session_id()) {
            session_start();
        }
        
        $this->cookies = array(
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
        
        $data = array();
        $data['login'] = true;
        
        if(!empty($_POST['ukm_user']) && !empty($_POST['ukm_pass'])) {
            $user = $_POST['ukm_user'];
            $pass = $_POST['ukm_pass'];
            
            try {
                $this->login($user, $pass);
            }
            catch(Exception $e) {
                // login failed
                $data['login'] = false;
            }
        }
        
        return $data;
    }
    
    public function isLoggedIn()
    {
        if(!isset($_COOKIE['UKM' . $this->version . '_username'])) {
            return false;
        }
        return true;
    }
    
    public function login($user, $pass)
    {
        global $wpdb;
        
        $row = $wpdb->get_results(
            $query = $wpdb->prepare(
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
        
        $row = (array) $row[0];
        
        if(!isset($row['kommune'])) {
            throw new Exception('Login failed');
        }
        
        $query = 'SELECT `pl_id` ' .
                 'FROM `smartukm_rel_pl_k` ' .
                 'WHERE `k_id` = "#kommune" ' .
                 'ORDER BY `season` DESC ' .
                 'LIMIT 1';
                 
        $sql = new SQL($query, array('kommune' => $row['kommune']));
        
        echo $sql->debug();
        $pl_id = $sql->run('field', 'pl_id');
	
/*
        if(!isset($pl_id)) {
            throw new Exception('Could not fetch pl_id');
        }
*/
        
        $row['pl_id'] = $pl_id;
        $row['pl_type'] = $row['kommune'] == 0 ? 'fylke' : 'kommune';
        
        if($row['kommune'] == 0 && $row['pl_fylke'] == 0) {
            $row['pl_type'] = 'administrator';
        }
        
		foreach($row as $key => $val) {
		    $this->setCookie('UKM' . $this->version . '_' . $key, utf8_encode($val), time()+3600*24*3*6);
        }
        
        // Login success
        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/arrangor/?logon=true');
    }
    
    public function logout()
    {
        $this->setCookie($this->cookies);
        $this->unsetCookie($this->cookies);
        $this->redirect('http://'.$_SERVER['HTTP_HOST'].'/arrangor/?UKMstatus=logget_ut');
    }
    
    public function setCookie($cookie, $value = '', $time = 1) 
    {
        if(is_array($cookie)) {
            foreach($cookie as $c) {
                setcookie($c, $value, $time);
            }
        }
        else {
            setcookie($cookie, $value, $time);
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