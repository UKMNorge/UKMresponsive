<?php
require_once('UKM/facebook.class.php');
error_reporting(E_ALL);
ini_set('display_errors', true);

$FB = new MariusMandalFacebook( UKM_FACE_APP_ID, UKM_FACE_APP_SECRET, 'http://ukm.no/ambassador/' );

$DATA['ambassador']['loginLink'] = $FB->loginLink();