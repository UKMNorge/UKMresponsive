<?php
log_time( 'Controller init' );
function log_time($logpoint) {
	if( $_SERVER['REMOTE_ADDR'] == '81.0.146.162' ) {
 		$t = microtime(true);
		$micro = sprintf("%06d",($t - floor($t)) * 1000000);
		$d = new DateTime( date('Y-m-d H:i:s.'.$micro,$t) );

		$log = $d->format("Y-m-d H:i:s.u"); // note at point on "u"
		
		error_log('PAMELDTE: '. $log .' => '. $logpoint);
	}
}
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');

log_time( 'Init monstring' );
$pl = new monstring( get_option('pl_id') );

$monstring = new stdClass();
$monstring->navn = str_replace('UKM','',$pl->g('pl_name'));
$monstring->type_tekst = $pl->g('type') == 'kommune' ? 'lokal' : 'fylkes';


$DATA['monstring'] = $monstring;
	
$DATA['jumbo'] = (object) array('header' => 'Påmeldte',
								'content' => 'UKM ' . $pl->g('pl_name')
								);
log_time( 'Load innslag' );
$alle_innslag = $pl->innslag();
log_time( 'Loaded innslag' );
if(isset( $_GET['type'] ) && $_GET['type'] == 'false')
	unset( $_GET['type'] );

if( isset( $_GET['type'] ) ) {
	$sql = new SQL("SELECT `bt_name`
					FROM `smartukm_band_type`
					WHERE `bt_id` = '#id'",
				   array('id' => $_GET['type'])
				  );
	$DATA['active_filter'] = utf8_encode( strtolower( $sql->run('field','bt_name') ) );
	$DATA['active_filter_id'] = $_GET['type'];
	$DATA['list_filtered'] = true;
	$SEO->set('description', 'Alle '. $DATA['active_filter'].'-innslag på UKM '. $monstring->navn);
	$SEO->set('canonical', $DATA['url']['current'].'?type='.$DATA['active_filter_id']);
} else {
	$DATA['active_filter'] = 'alle innslag';
	$DATA['active_filter_id'] = 'false';
	$DATA['list_filtered'] = false;
	$SEO->set('description', 'Alle påmeldte til UKM '. $monstring->navn);
}

$SEO->set('title', 'Påmeldte til UKM '. $monstring->navn );


$BC->addJumbo = false;
$BC->add( $DATA['url']['current'], $DATA['jumbo']->header );
$BC->add( $DATA['url']['current'].'?type='.$DATA['active_filter_id'], ucfirst($DATA['active_filter']) );


$DATA['typer'] = array();
log_time( 'LOOP innslag' );
foreach( $alle_innslag as $innslag ) {
	// Innslag data, add to list
	log_time( 'Innslag: gen list data' );
	$stdClass = generate_list_data( $innslag, $pl, true);
	log_time( 'Innslag: generated list data' );
	// Innslag types
	$typer[ $innslag['bt_id'] ] = $stdClass->kategori;


	// Skip if not within filter
	if( isset( $_GET['type'] ) && $innslag['bt_id'] != $_GET['type'] )
		continue;
		
	$DATA['rekkefolge'][] = $stdClass;
}

$DATA['typer'] = $typer;
log_time( 'Controller end' );
