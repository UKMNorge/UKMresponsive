<?php
require_once('UKM/monstring.class.php');
require_once(THEME_PATH .'class/page.class.php');

class lokalside extends page {
	var $renderData = array();
	var $posts_per_page = 7;
	var $nav = array();
	var $navOrder = array(	'kontaktpersoner',
							'ukmtv',
							'program',
							'pameldte',
							'fylke',					
						);


	public function __construct( ) {
		$this->_loadWPoptions();
		
		$this->_viewSelector();
		switch( $this->view ) {
			// FØR MØNSTRINGEN ER REGISTRERT
			case 'ikke_klar':
				$this->_loadTidligereArAdvarsel();
				break;
			case 'pre_pamelding':
				$this->_loadTidligereArAdvarsel();
				break;
			case 'pamelding':
            	$this->posts_per_page = 6;
				$this->_loadProgram();
				$this->_loadPameldte();
				$this->_loadStatistikk();
				break;
			case 'pre':
				$this->_loadProgram();
				$this->_loadPameldte();
				$this->_loadStatistikk();
				break;
			case 'aktiv':
				$this->_loadUKMTV();
				$this->_loadProgram();
				$this->_loadPameldte();
				$this->_loadStatistikk();
				break;
			case 'post':
				$this->_loadUKMTV();
				$this->_loadProgram();
				$this->_loadPameldte( true );
				$this->_loadStatistikk();
				break;
		}
		$this->_loadPosts();
		$this->_baseNav();
		$this->_loadKontaktpersoner();
	}
			
	/**
	 * Velg hvilket view som skal vises
	 *
	 */
	private function _viewSelector() {
		$this->_requireMonstring();
		$utenforsesong = mktime(0,0,0,12,1,get_option('season')-1)>time();
		
        if( !$this->pl->registered() ) {
            $this->view = 'ikke_klar';
        } elseif ( $utenforsesong ) {
            $this->view = 'pre_pamelding'; 
        } elseif( time() < $this->pl->get('pl_deadline') || time() < $this->pl->get('pl_deadline2') ) {
            $this->view = 'pamelding';
        } elseif( time() > $this->pl->get('pl_deadline') && time() < $this->pl->get('pl_start') ) {
            $this->view = 'pre';
        } elseif( time() > $this->pl->get('pl_start') && time() < $this->pl->get('pl_stop') ) {
            $this->view = 'aktiv';
        } else {
            $this->view = 'post';
        }
	}
	
	/**
	 * Vis advarsel-boks "leter du etter tidligere år?
	 *
	 */
	private function _loadTidligereArAdvarsel() {
		if( 8 < date('m') && date('m') < 12 ) {
			$this->renderData['tidligereArAdvarsel'] = 'tidligereArAdvarsel';	
			$this->tidligereArAdvarsel = true;
		}
	}
	
	/**
	 * Last inn informasjon om mønstringen og fylkesmønstringen
	 * 
	 */
	private function _loadMonstring() {
		$this->renderData['monstring'] = 'monstring';	

	    $pl = new monstring( get_option('pl_id') );
		$this->pl = $pl;
	    $this->monstring = new StdClass();
	    $this->monstring->pl_id = $pl->get('pl_id');
	    $this->monstring->navn = str_replace('UKM','',$pl->g('pl_name'));
	    $this->monstring->starter = $pl->get('pl_start');
	    $this->monstring->slutter = $pl->get('pl_stop');
	    $this->monstring->sted = $pl->get('pl_place');
	    $this->monstring->kommuner = $pl->get('kommuner');
	    $this->monstring->frist_1 = $pl->get('pl_deadline');
	    $this->monstring->frist_1_aktiv = $pl->subscribable('pl_deadline');
	    $this->monstring->frist_2 = $pl->get('pl_deadline2');
	    $this->monstring->frist_2_aktiv = $pl->subscribable('pl_deadline2');
	    $this->monstring->bandtypesdetails = $pl->getAllBandTypesDetailedNew();
	    $this->monstring->starter_tekst = $pl->starter();
	
	    $fpl =  $pl->videresendTil(true);
	    $this->videresend_til = $fpl;
	    
	    $this->monstring->fylke = new stdClass();
	    $this->monstring->fylke->navn = $fpl->g('pl_name');
	    $this->monstring->fylke->link = $fpl->g('link');
	}
	
	/**
	 * Last inn kontaktpersoner som tilhører mønstringen
	 */
	private function _loadKontaktpersoner() {
		$this->renderData['kontaktpersoner'] = 'kontaktpersoner';	
		$this->kontaktpersoner = array();
		$this->_requireMonstring();
				
	    $kontaktpersoner = $this->pl->kontakter();
	    if (is_array($kontaktpersoner)) {
	        foreach ( $kontaktpersoner as $kontakt ) {
	            $k           = new stdClass();
	            $k->navn     = $kontakt->get( 'name' );
	            $k->tittel   = $kontakt->get( 'title' );
	            if( empty( $k->tittel ) ) {
		            $k->tittel = 'Lokalkontakt';
	            }
	            $k->bilde    = $kontakt->get( 'image' );
	            $k->mobil    = $kontakt->get( 'tlf' );
	            $k->epost    = $kontakt->get( 'email' );
	            $k->facebook = $kontakt->get( 'facebook' );
	
	            $kontakter[] = $k;
	        }
	    }
        $this->kontaktpersoner = $kontakter;
	}
	
	/**
	 * Last inn statistikk for mønstringen
	 *
	 **/
	private function _loadStatistikk() {
		require_once('UKM/statistikk.class.php');
		$this->_requireMonstring();
		$this->renderData['stat_pameldte'] = 'stat_pameldte';		

		$stat = $this->pl->statistikk();										  
		$total = $stat->getTotal($this->pl->get('season'));

		$stat = new stdClass();
		$stat->tall 	= $total['persons'];
		$stat->til		= $this->monstring->navn;
		$this->stat_pameldte = $stat; 
	}
	
	/**
	 * Bruker siden live-modulen? vis lenke / embedkode
	 *
	 */	
	private function _liveLink() {
		$this->renderData['livelink'] = 'embedcode';		

		$this->livelink = get_option('ukm_live_link');
		$perioder 		= get_option('ukm_hendelser_perioder');
		$embedcode 		= get_option('ukm_live_embedcode');
			
		$show_embed = false;
		if( $embedcode ) {
			foreach( $perioder as $p ) {
				if( $p->start < time() && $p->stop > time() ) {
					$show_embed = true;
					break;
				}
			}
		}
			
		if( $show_embed ) {
			$DATA['embedcode'] = $embedcode;
		}
	}


	/** 
	 * Hvis mønstringen har UKM-TV, vis menyelement
	 *
	 */
	private function _loadUKMTV() {
		$this->_requireMonstring();
		require_once('UKM/tv_files.class.php');
		
		// Hent filer fra mønstringen
		$tv_files = new tv_files( 'place', $this->pl->get('pl_id') );
		$tv_files->limit(1);
		$tv_files->fetch();
		
		$this->ukmtv = $tv_files->num_videos > 0;
		
		// Registrer meny
		if( $this->ukmtv ) {
			$nav = new stdClass();
			$nav->url 			= '//tv.ukm.no';
			$nav->title			= 'Film';
			$nav->icon 			= 'ukmtv_black';
			$nav->description	= 'Film fra '. $this->pl->get('pl_name').' i UKM-TV';
			$nav->target		= '_blank';
			
			$this->registerNav( 'ukmtv', $nav );
	    }
	}
	
	/**
	 * Hvis mønstringen har program, vis menyelement
	 *
	 */	
	private function _loadProgram() {
		$this->_requireMonstring();
		
		if($this->pl->har_program() ) {
			$nav = new stdClass();
			$nav->url			= 'program/';
			$nav->title 		= 'Program';
			$nav->icon 			= 'calendar';
			$nav->description	= 'Se program for lokalmønstringen';
			
			$this->registerNav( 'program', $nav );
		}
	}
	
	/**
	 * Hvis mønstringen har påmeldte, vis menyelement
	 *
	 */	
	private function _loadPameldte( $in_the_past = false) {
		$this->_requireMonstring();
		
		$innslag = $this->pl->innslag();
		if( sizeof( $innslag ) > 0 ) {
			$nav = new stdClass();
			$nav->url 			= 'pameldte/';
			$nav->title			= 'Hvem '. ($in_the_past ? 'deltok' : 'deltar' ) .'?';
			$nav->icon			= 'people';
			$nav->description	= 'Se alle som '. ($in_the_past ? 'deltok' : 'deltar' ) .'.';
			$this->registerNav( 'pameldte', $nav );
		    
		}
	}	
	
	/**
	 * Basis-navigasjon (er med på alle sider)
	 *
	 */
	private function _baseNav() {
		$this->_requireMonstring();
		
		$nav = new stdClass();
		$nav->url 			= '#kontaktpersoner';
		$nav->title			= 'Kontaktpersoner';
		$nav->icon 			= 'i';
		$nav->description 	= 'Har du spørsmål om UKM i '. $this->pl->get('pl_name').'? Disse kan hjelpe!';
		$nav->id 			= 'show_kontaktpersoner';
		$this->registerNav( 'kontaktpersoner', $nav );
		
		
		$nav = new stdClass();
		$nav->url 			= $this->monstring->fylke->link;
		$nav->title 		= 'UKM '. $this->monstring->fylke->navn;
		$nav->icon 			= 'maps';
		$nav->description 	= 'Info om UKM i '. $this->monstring->fylke->navn;
		$this->registerNav( 'fylke', $nav );
	}

	/**
	 * Last inn mønstringen hvis ikke allerede gjort
	 *
	 */
	protected function _requireMonstring() {
		if( !isset( $this->pl ) ) {
			$this->_loadMonstring();
		}
	}
}




#$DATA = array_merge($DATA, $pl->pameldingsikoner());




$lokalside = new lokalside();
$VIEW = $lokalside->getView();
$DATA = array_merge( $DATA, $lokalside->renderData() );
$SEO = $lokalside->SEO( $SEO );
