<?php
require_once('UKM/facebook.class.php');
error_reporting(E_ALL);
ini_set('display_errors', true);

$DATA['ambassador']['loginLink'] = $FACEBOOK_v4->loginLink( 'http://'. UKM_HOSTNAME .'/ambassador/' );