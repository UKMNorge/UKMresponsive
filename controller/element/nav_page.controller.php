<?php
switch( get_option('ukm_top_page') ) {

	###############################################
	## MENU OF internsjonalt.UKM.no FRONTPAGE
	case 'internasjonalt':
		$DATA['page_nav'] = array();
		break;

	###############################################
	## MENU OF om.UKM.no FRONTPAGE
	case 'voksneogpresse':
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Kontakt',
											   'icon'			=> 'mobile',
											   'description'	=> 'Finn kontaktpersoner i administrasjonen, fylket og styret'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Hva er UKM?',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Strategiplan',
											   'icon'			=> 'mobile',
											   'description'	=> 'Se UKM Norges strategiplan'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'PRESSE',
											   'icon'			=> 'mobile',
											   'description'	=> 'Informasjon, logoer, bilder med mer'
											  );
		break;

	
	###############################################
	## MENU OF UKM.no FRONTPAGE
	default: 
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Hva er UKM?',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Alt om URG',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Tidligere nettsider',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'UKM-TV',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Whatevva',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
	break;											   
}