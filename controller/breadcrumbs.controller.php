<?php
require_once(THEME_PATH . 'class/breadcrumbs.class.php');

$BC = new crumbs();

$site_type = get_option( 'site_type' );

switch( $site_type ) {
	case 'kommune':	
		$pl = new monstring( get_option('pl_id') );		
		$fpl = $pl->videresendTil(true);

		$BC->home('derdubor');
		$BC->add( $fpl->g('link'), 'UKM '. $fpl->g('pl_name') );
		$BC->add( $pl->g('link'), 'UKM '. $pl->g('pl_name') );
	break;

	case 'fylke':		
		$pl = new monstring( get_option('pl_id') );		

		$BC->home('derdubor');
		$BC->add( $pl->g('link'), 'UKM '. $pl->g('pl_name') );
	break;

	default:
		$BC->home('ungdom');
		break;
}

if( !$site_type ) {
	if( !empty( $DATA['top_page'] ) && in_array( $DATA['top_page'], $NAV_TOP ) ) {
		$BC->home( $DATA['top_page'] );
	} else {
		$BC->add( get_site_url() , get_bloginfo('blog_name') );
	}
}