<?php

switch( get_option('homepage_nav') ) {
	
	###############################################
	## MENU OF UKM.no FRONTPAGE
	default: 
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring1',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring2',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring3',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring4',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring5',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
		$DATA['page_nav'][] = (object) array( 'url' 			=> '#',
											   'title'		 	=> 'Finn din mønstring6',
											   'icon'			=> 'mobile',
											   'description'	=> 'UKM består av én lokalmønstring for hver kommune. Finn din her.'
											  );
	break;											   
}