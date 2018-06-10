<?php

require_once('UKM/sql.class.php');

$response['action'] = $_POST['konkurranse'];
$response['sporsmal'] = $_POST['sporsmal'];

switch( $_POST['konkurranse'] ) {
	case 'svar':
		$SQLins = new SQLins('konkurranse_svar');
		$SQLins->add('sporsmal-key', $_POST['sporsmal']);
		$SQLins->add('mobil', $_POST['mobil'] );
		$SQLins->add('svar', $_POST['svar']);
		$res = $SQLins->run();
		break;
	case 'har':
	case 'get':
		$SQL = new SQL("
			SELECT `svar`
			FROM `konkurranse_svar`
			WHERE `sporsmal-key` = '#sporsmal'
			AND `mobil` = '#mobil'",
			[
				'sporsmal' => $_POST['sporsmal'],
				'mobil' => $_POST['mobil']
			]
		);
		$res = $SQL->run('field', 'svar');

		if( $_POST['konkurranse'] != 'har' ) {
			$response['result'] = $res;
			
			if( $_POST['sporsmal'] == 'korslaget-fylke' ) {
				$response['fylke'] = fylker::getByLink( $res )->getNavn();
			}
			if( strpos( $_POST['sporsmal'], 'onskereprise-' ) === 0 ) {
				require_once('UKM/monstring.class.php');
				$monstring = new monstring_v2( get_option('pl_id') );
				$response['innslag'] = $monstring->getInnslag()->get( $res )->getNavn();
			}
		}
		
		$response['harSvart'] = $res != null;
}

$response['success'] = true;