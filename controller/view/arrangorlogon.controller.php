<?php
if( is_user_logged_in()) {
	header("Location: http://ukm.local/wp-admin/");
	exit();
}