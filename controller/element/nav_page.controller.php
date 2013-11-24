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
											   'description'	=> 'Finn kontaktpersoner i administrasjonen, fylket og styret'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'om/',
											   'title'		 	=> 'Hva er UKM?',
											   'icon'			=> 'star',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'presse/',
											   'title'		 	=> 'PRESSE',
											   'icon'			=> 'pen',
											   'description'	=> 'Informasjon, logoer, bilder med mer'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'strategiplan/',
											   'title'		 	=> 'Strategiplan',
											   'icon'			=> 'hus',
											   'description'	=> 'Se UKM Norges strategiplan'
											  );
		break;

	
	###############################################
	## MENU OF UKM.no FRONTPAGE
	default: 
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'din_monstring/',
											   'title'		 	=> 'Finn din mønstring',
											   'icon'			=> 'maps',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'hva-er-ukm/',
											   'title'		 	=> 'Hva er UKM?',
											   'icon'			=> 'star',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'urg/',
											   'title'		 	=> 'Alt om URG',
											   'icon'			=> 'rabbit',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'tidligere-nettsider/',
											   'title'		 	=> 'Tidligere nettsider',
											   'icon'			=> 'kulturminne',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> 'http://tv.ukm.no',
											   'title'		 	=> 'UKM-TV',
											   'icon'			=> 'play',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.',
											   'target'			=> '_blank'
											  );
	break;											   
}