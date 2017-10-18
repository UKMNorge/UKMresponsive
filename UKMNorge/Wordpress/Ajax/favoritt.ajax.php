<?php

if( isset( $_COOKIE['UKMfavoritt'] ) && is_numeric( $_COOKIE['UKMfavoritt'] ) ) {
	$WP_TWIG_DATA['monstring'] = new monstring_v2( $_COOKIE['UKMfavoritt'] );
	
	// Hent siste post
	$path = str_replace('//'. UKM_HOSTNAME, '', $WP_TWIG_DATA['monstring']->getLink() );
	$local_blog_id = get_blog_id_from_url( UKM_HOSTNAME, $path );
	if( is_numeric( $local_blog_id ) ) {
		switch_to_blog( $local_blog_id);
		$latest_post = wp_get_recent_posts('numberposts=1', OBJECT);
		if( is_array( $latest_post ) && sizeof( $latest_post ) == 1 ) {
			$WP_TWIG_DATA['post'] = new WPOO_Post( $latest_post[0] );
		}
		restore_current_blog();
	}

	$response['success'] = true;
	$response['html'] = WP_TWIG::render( 'Monstring/favoritt_forside', $WP_TWIG_DATA );
} else {
	$response['success'] = false;
}
?>