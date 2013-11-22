<?php

switch( get_option('homepage_nav') ) {
	
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