<?php

$pl = new monstring( get_option('pl_id') );

$monstring = new stdClass();
$monstring->navn = str_replace('UKM','',$pl->g('pl_name'));
$monstring->season = $pl->g('season');
$monstring->start = $pl->starter();
$monstring->slutt = $pl->slutter();
$monstring->type_tekst = $pl->g('type') == 'kommune' ? 'lokal' : 'fylkes';


$DATA['monstring'] = $monstring;
	
$DATA['jumbo'] = (object) array('header' => 'Program',
								'content' => 'UKM ' . $pl->g('pl_name')
								);
								
if( isset($_GET['hendelse'] ) ) {
	require_once('UKM/forestilling.class.php');
	$VIEW = 'program_rekkefolge';
	
	$con = new forestilling( $_GET['hendelse'] );

	$hendelse = new stdClass();
	$hendelse->navn		= $con->g('c_name');
	$hendelse->start	= $con->g('c_start');
	$hendelse->sted		= $con->g('c_place');
	$hendelse->offentlig= $con->g('c_visible_detail')=='true';

	$BC->addJumbo = false;
	$BC->add( $DATA['url']['current'], $DATA['jumbo']->header );
	$BC->add( $DATA['url']['current'].'?hendelse='.$_GET['hendelse'], $hendelse->navn );



	$DATA['hendelse'] = $hendelse;
   	$SEO->set('title', $hendelse->navn .' @ UKM '. $monstring->navn );
   	$SEO->set('description', 'Detaljprogram for '. $hendelse->navn .'. '. date('d.m.Y H:i', $hendelse->start) 
   							. ( empty($hendelse->sted) ? '' : ' @ '. $hendelse->sted) 
   			  );
   	$SEO->set('canonical', $DATA['url']['current'].'?hendelse='.$_GET['hendelse'] );

	if( $hendelse->offentlig ) {
		$alle_innslag = $con->innslag();
		
		foreach( $alle_innslag as $innslag ) {
			$stdClass = generate_list_data( $innslag, $pl, $_GET['hendelse']);
			$DATA['rekkefolge'][] = $stdClass;
		}
	} else {
		$VIEW = 'program_nodetail';
	}
} else {
   	$SEO->set('title', 'Program UKM ' . $monstring->navn );
   	$SEO->set('description', 'Rammeprogram for UKM '. $monstring->navn .' ('.$monstring->start.' - '.$monstring->slutt.')');

	$VIEW = 'program'; 

	$hendelser = $pl->forestillinger();
	
	foreach( $hendelser as $con ) {
		$hendelse = new stdClass();
		$hendelse->offentlig 	= $con['c_visible_detail']=='true';
		$hendelse->ID			= $con['c_id'];
		$hendelse->navn			= $con['c_name'];
		$hendelse->start		= $con['c_start'];
		$hendelse->sted			= $con['c_place'];
		
		$dato = array();
		$dato['d'] = date('n', $con['c_start']);
		$dato['m'] = date('j', $con['c_start']);
		$dato['Y'] = date('Y', $con['c_start']);
	
		$hendelse->group = mktime(0,0,0, $dato['d'], $dato['m'], $dato['Y']);
		
		$DATA['program'][ $hendelse->group ][] = $hendelse;
	}
	
	
	if( isset( $DATA['program'] ) && sizeof($DATA['program']) < 4 ) {
		$DATA['dag_max_col'] = sizeof($DATA['program']);
	} else {
		$DATA['dag_max_col'] = 3;
	}
}