<?php
	$perioder = get_option('ukm_hendelser_perioder');
	$embedcode = get_option('ukm_live_embedcode');
	
	$show_embed = false;
	
	if( $embedcode ) {
		foreach( $perioder as $p ) {
			if( $p->start < time() && $p->stop > time() {
				$show_embed = true;
				break;
			}
		}
	}
	
	if( $show_embed ) {
		$DATA['embedcode'] = $embedcode;
	}
	
	var_dump( $DATA );
?>