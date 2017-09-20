<?php
	
	$meny = wp_get_nav_menu_object( $WP_TWIG_DATA['page']->getPage()->meta->temp_nav_id );	
	$WP_TWIG_DATA['meny'] = wp_get_nav_menu_items( $WP_TWIG_DATA['page']->getPage()->meta->temp_nav_id );
	
#	var_dump( $WP_TWIG_DATA['meny']  );