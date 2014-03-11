<?php
switch( get_option('ukm_top_page') ) {

	###############################################
	## MENU OF internsjonalt.UKM.no FRONTPAGE
	case 'internasjonalt':
		$DATA['page_nav'] = array();
		break;

	###############################################
	## MENU OF om.UKM.no FRONTPAGE
	case 'ambassadorer':
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'materiell/',
											   'title'		 	=> 'Materiell',
											   'icon'			=> 'file',
											   'description'	=> 'Lag UKM-materiell som plakater, flyers, last ned videoer++'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'medambassadorer/',
											   'title'		 	=> 'Andre ambassadører',
											   'icon'			=> 'ambassador',
											   'description'	=> 'Se alle ambassadører, sortert etter lokalmønstring'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'https://www.facebook.com/groups/270639562974566/',
											   'title'		 	=> 'Facebook-gruppen',
											   'icon'			=> 'facebook',
											   'description'	=> 'Møt andre ambassadører og delta i diskusjoner',
											   'target'			=> '_blank'
											  );

		break;

	###############################################
	## MENU OF om.UKM.no FRONTPAGE
	case 'voksneogpresse':
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'kontakt/',
											   'title'		 	=> 'Kontakt',
											   'icon'			=> 'info',
											   'description'	=> 'Kontaktpersoner i UKM-nettverket'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'om/',
											   'title'		 	=> 'Finn ut mer om UKM',
											   'icon'			=> 'star',
											   'description'	=> 'Bakgrunnsstoff, verdier og historikk'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'grafisk-profil/',
											   'title'		 	=> 'Grafisk profil',
											   'icon'			=> 'palette',
											   'description'	=> 'UKM-logo, skrifter og farger'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'presse/',
											   'title'		 	=> 'PRESSE',
											   'icon'			=> 'pen',
											   'description'	=> 'Informasjon, bilder med mer'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'prosjekter/',
											   'title'		 	=> 'Prosjekter',
											   'icon'			=> 'project',
											   'description'	=> 'Nasjonale prosjekter og satsninger'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> $DATA['url']['base'].'/internasjonalt/',
											   'title'		 	=> 'Internasjonalt',
											   'icon'			=> 'project',
											   'description'	=> 'Alt om UKMs internasjonale utvekslinger'
											  );

		break;

	
	###############################################
	## MENU OF UKM.no FRONTPAGE
	default: 
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'din_monstring/',
											   'title'		 	=> 'Finn din mønstring',
											   'icon'			=> 'maps',
											   'description'	=> 'UKM har lokalmønstringer over hele landet'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'hva-er-ukm/',
											   'title'		 	=> 'Finn ut mer om UKM',
											   'icon'			=> 'star',
											   'description'	=> 'Hvem kan delta? Hva kan du gjøre?'
											  );
/*
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'http://tv.ukm.no',
											   'title'		 	=> 'UKM-TV',
											   'icon'			=> 'ukmtv_black',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.',
											   'target'			=> '_blank'
											  );
*/
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'urg/',
											   'title'		 	=> 'URG - Ung ressursgruppe',
											   'icon'			=> 'rabbit',
											   'description'	=> 'Si din mening'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'ambassador/',
											   'title'		 	=> 'Ambassadører',
											   'icon'			=> 'star',
											   'description'	=> 'For deg som vil spre ordet om UKM'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'internasjonalt/',
											   'title'		 	=> 'Internasjonalt',
											   'icon'			=> 'flag',
											   'description'	=> 'Alt om UKMs internasjonale utvekslinger'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'tidligere-nettsider/',
											   'title'		 	=> 'UKM tidligere år',
											   'icon'			=> 'kulturminne',
											   'description'	=> 'Bilder, video og info'
											  );

	break;											   
}