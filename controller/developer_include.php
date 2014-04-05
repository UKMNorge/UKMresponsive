<?php

	$pl_id = get_blog_option( $fylke->ID, 'pl_id' );
	
	$pl = new monstring( $pl_id );
	if( time() > $pl->get('pl_start') && time() < $pl->get('pl_stop') ) {
		
		$perioder = get_blog_option($fylke->ID, 'ukm_hendelser_perioder');
		$embedcode = get_blog_option($fylke->ID, 'ukm_live_embedcode');
		
		$is_live = false;
		
		if( $embedcode ) {
			if(is_array( $perioder ) ) {
				foreach( $perioder as $p ) {
					if( $p->start < time() && $p->stop > time() ) {
						$is_live = true;
						break;
					}
				}
			}
		}
		
		if( $is_live ) {
			echo $fylke->ID .': live';
			$fylke->live->now = true;
		} else {
			$fylke->live->now = false;
			echo $fylke->ID .': notlive';
		}
	}
?>