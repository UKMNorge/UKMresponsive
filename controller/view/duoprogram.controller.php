<?php
$pl = new monstring( get_option('pl_id') );

$monstring = new stdClass();
$monstring->navn = str_replace('UKM','',$pl->g('pl_name'));
$monstring->season = $pl->g('season');
$monstring->start = $pl->starter();
$monstring->starter = $pl->g('pl_start');
$monstring->slutt = $pl->slutter();
$monstring->type_tekst = $pl->g('type') == 'kommune' ? 'lokal' : 'fylkes';


$DATA['monstring'] = $monstring;
	
$DATA['jumbo'] = (object) array('header' => 'Program',
								'content' => $pl->g('pl_name')
								);
								
require_once('UKM/forestilling.class.php');
$VIEW = 'duoprogram';

/*
	$BC->addJumbo = false;
$BC->add( $DATA['url']['current'], $DATA['jumbo']->header );
$BC->add( $DATA['url']['current'].'?hendelse='.$_GET['hendelse'], $hendelse->navn );

$SEO->set('title', $hendelse->navn .' @ UKM '. $monstring->navn );
$SEO->set('description', 'Program for '. $hendelse->navn .'. '. date('d.m.Y H:i', $hendelse->start) 
						. ( empty($hendelse->sted) ? '' : ' @ '. $hendelse->sted) 
		  );
$SEO->set('canonical', $DATA['url']['current'].'?hendelse='.$_GET['hendelse'] );
*/



$con = new forestilling( get_post_meta( get_the_ID(), 'event-left', true ) );
$hendelse = new stdClass();
$hendelse->id			= $con->g('c_id');
$hendelse->navn			= $con->g('c_name');
$hendelse->start		= $con->g('c_start');
$hendelse->sted			= $con->g('c_place');
$hendelse->offentlig	= $con->g('c_visible_detail')=='true';
$hendelse->embed 		= get_post_meta( get_the_ID(), 'embed-left', true );
$hendelse->rekkefolge	= [];
$DATA['hendelse_left'] = $hendelse;

if( $hendelse->offentlig ) {
	$alle_innslag = $con->innslag();
	
	foreach( $alle_innslag as $innslag ) {
		$stdClass = generate_list_data( $innslag, $pl, $hendelse->id);
		$hendelse->rekkefolge[] = $stdClass;
	}
}

/// /
$con = new forestilling( get_post_meta( get_the_ID(), 'event-right', true ) );
$hendelse = new stdClass();
$hendelse->id			= $con->g('c_id');
$hendelse->navn			= $con->g('c_name');
$hendelse->start		= $con->g('c_start');
$hendelse->sted			= $con->g('c_place');
$hendelse->offentlig	= $con->g('c_visible_detail')=='true';
$hendelse->embed 		= get_post_meta( get_the_ID(), 'embed-right', true );
$hendelse->rekkefolge	= [];

if( $hendelse->offentlig ) {
	$alle_innslag = $con->innslag();
	
	foreach( $alle_innslag as $innslag ) {
		$stdClass = generate_list_data( $innslag, $pl, $hendelse->id);
		$hendelse->rekkefolge[] = $stdClass;
	}
}

$DATA['hendelse_right'] = $hendelse;
