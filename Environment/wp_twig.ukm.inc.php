<?php
function UKMpath( $path ) {
	return str_replace(array('UKMDesignBundle',':'), array('','/'), $path );
}