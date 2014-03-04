<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');

$pl = new monstring( get_option('pl_id') );

$monstring = new stdClass();
$monstring->navn = $pl->g('pl_name');
$monstring->type_tekst = $pl->g('type') == 'kommune' ? 'lokal' : 'fylkes';


$DATA['monstring'] = $monstring;
	
$DATA['jumbo'] = (object) array('header' => 'Påmeldte',
								'content' => 'UKM ' . $pl->g('pl_name')
								);

$alle_innslag = $pl->innslag();

if(isset( $_GET['type'] ) && $_GET['type'] == 'false')
	unset( $_GET['type'] );

if( isset( $_GET['type'] ) ) {
	$sql = new SQL("SELECT `bt_name`
					FROM `smartukm_band_type`
					WHERE `bt_id` = '#id'",
				   array('id' => $_GET['type'])
				  );
	$DATA['active_filter'] = strtolower( utf8_encode( $sql->run('field','bt_name') ) );
	$DATA['active_filter_id'] = $_GET['type'];
	$DATA['list_filtered'] = true;
} else {
	$DATA['active_filter'] = 'alle innslag';
	$DATA['active_filter_id'] = 'false';
	$DATA['list_filtered'] = false;
}

$DATA['typer'] = array();
foreach( $alle_innslag as $innslag ) {
	// Innslag data, add to list
	$stdClass = generate_list_data( $innslag, $pl, false);
	// Innslag types
	$typer[ $innslag['bt_id'] ] = $stdClass->kategori;


	// Skip if not within filter
	if( isset( $_GET['type'] ) && $innslag['bt_id'] != $_GET['type'] )
		continue;
		
	$DATA['rekkefolge'][] = $stdClass;
}

$DATA['typer'] = $typer;
