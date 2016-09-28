<?php
function UKMpath( $path ) {
	return str_replace(array('UKMDesignBundle',':'), array('','/'), $path );
}

function UKMgrafikk( $path ) {
	return '//ukm.dev/wp-content/themes/UKMresponsive/'. $path;
}