<?php

$requested = $_SERVER['REDIRECT_URL'];

$local_url = explode( '/', $requested );
array_splice( $local_url, 3 );
$local_url = implode('/', $local_url ).'/';

$local_real_url = $wpdb->get_var($wpdb->prepare("SELECT `realpath` FROM `ukm_uri_trans` WHERE `path` = '%s'", $local_url));

if( !is_null( $local_real_url ) && $local_real_url != $local_url ) {
#	 echo ("Location: http://". UKM_HOSTNAME . str_replace( $local_url, $local_real_url, $requested ));
	header("Location: http://". UKM_HOSTNAME . str_replace( $local_url, $local_real_url, $requested ));

}
