<?php

// Sjekk om URLen finnes i kort-lista vår
$requested = rtrim($_SERVER['REDIRECT_URL'], '/').'/';

$local_url = explode( '/', $requested );
array_splice( $local_url, 3 );
$local_url = implode('/', $local_url ).'/';

$local_real_url = $wpdb->get_var($wpdb->prepare("SELECT `realpath` FROM `ukm_uri_trans` WHERE `path` = '%s'", $local_url));

if( !is_null( $local_real_url ) && $local_real_url != $local_url ) {
#	 echo ("Location: http://". UKM_HOSTNAME . str_replace( $local_url, $local_real_url, $requested ));
	header("Location: http://". UKM_HOSTNAME . str_replace( $local_url, $local_real_url, $requested ));
}

$season = (date("n") > 7) ? date('Y')+1 : date('Y');
if( UKM_HOSTNAME == 'ukm.dev' ) {
	$season = 2014;
}

// Sjekk om URLen er en gammel PL-ID:
$uri = $_SERVER['REQUEST_URI'];
$pl_id = null;
$match = preg_match("/pl[0-9]*/", $uri, $pl_id);
$pl_id = trim($pl_id[0], "pl");

if( 0 != $match && null != $pl_id && is_numeric($pl_id) ) {
	// Hvis vi har kommet hit har vi uansett ikke noen side for forespørselen, og kan dermed trygt redirecte til siden for ny mønstring.
	// Sjekk at mønstringen ikke er i år.
	$monstring = new monstring_v2($pl_id);
	if( $season != $monstring->getSesong() ) {

		// Hent pl_ider for denne sesongen for alle kommuner som var en del av den mønstringen.
		$sql = new SQL("SELECT `pl_id` FROM `smartukm_rel_pl_k`
						WHERE `k_id` IN 
							(SELECT `k_id` FROM `smartukm_rel_pl_k` 
								WHERE `pl_id` = '#pl_id'
							)
						AND `season` = '#season'
						ORDER BY `season` DESC ", array("pl_id" => $pl_id, 'season' => $season) 
						);
		
		#echo $sql->debug();
		$res = $sql->run();
		if( mysql_num_rows($res) > 0 ) {
			$pl_ids = array();
			while ( $row = mysql_fetch_assoc($res) ) {
				if( !in_array($row['pl_id'], $pl_ids) ) {
					$pl_ids[] = $row['pl_id'];
				}
			}
			if( count($pl_ids) == 1 ) {
				// TODO: Flash-message om at gamle sider finnes her og her?
				header( "Location: http://". UKM_HOSTNAME . "/pl".$pl_ids[0] . "/" );
			} elseif (count($pl_ids) > 1) {
				// Vi har flere enn èn mønstring for disse kommunene, redirect til derdubor
				header( "Location: http://". UKM_HOSTNAME ."/derdubor" );
			}
			else {
				// Fant ingen nyere mønstringer, ikke gjør noe. (Vis 404 som vanlig).
			}
		}
	}
}