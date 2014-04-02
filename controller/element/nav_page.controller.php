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
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//om.ukm.no/kontakt/',
											   'title'		 	=> 'Kontakt',
											   'icon'			=> 'i',
											   'description'	=> 'Kontaktpersoner i UKM-nettverket'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//om.ukm.no/om/',
											   'title'		 	=> 'Finn ut mer om UKM',
											   'icon'			=> 'star',
											   'description'	=> 'Bakgrunnsstoff, verdier og historikk'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//om.ukm.no/grafisk-profil/',
											   'title'		 	=> 'Grafisk profil',
											   'icon'			=> 'palette',
											   'description'	=> 'UKM-logo, skrifter og farger'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//om.ukm.no/presse/',
											   'title'		 	=> 'PRESSE',
											   'icon'			=> 'pencil',
											   'description'	=> 'Informasjon, bilder med mer'
											  );
/*
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'prosjekter/',
											   'title'		 	=> 'Prosjekter',
											   'icon'			=> 'folder',
											   'description'	=> 'Nasjonale prosjekter og satsninger'
											  );
*/
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//internasjonalt.ukm.no/',
											   'title'		 	=> 'Internasjonalt',
											   'icon'			=> 'globe',
											   'description'	=> 'Alt om UKMs internasjonale utvekslinger'
											  );

		break;

	
	###############################################
	## MENU OF UKM.no FRONTPAGE
	default: 
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/din_monstring/',
											   'title'		 	=> 'Finn din mønstring',
											   'icon'			=> 'maps',
											   'description'	=> 'UKM har lokalmønstringer over hele landet'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/festivalen/',
											   'title'		 	=> 'UKM-festivalen',
											   'icon'			=> 'rocket',
											   'description'	=> 'Info om UKM-årets høydepunkt!'
											  );
											  
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/hva-er-ukm/',
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
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/urg/',
											   'title'		 	=> 'URG - Ung ressursgruppe',
											   'icon'			=> 'kanin',
											   'description'	=> 'Si din mening'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/ambassador/',
											   'title'		 	=> 'Ambassadører',
											   'icon'			=> 'heart',
											   'description'	=> 'For deg som vil spre ordet om UKM'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//internasjonalt.ukm.no/',
											   'title'		 	=> 'Internasjonalt',
											   'icon'			=> 'globe',
											   'description'	=> 'Alt om UKMs internasjonale utvekslinger'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '//ukm.no/tidligere-ar/',
											   'title'		 	=> 'UKM tidligere år',
											   'icon'			=> 'monitor',
											   'description'	=> 'Bilder, video og info'
											  );

	break;											   
}