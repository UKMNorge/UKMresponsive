<?php

class UKMresponsive {
	var $title = false;
	
	var $nav_show_menu_button = true;

	public function __construct( $view ) {
		$this->SEO = new SEO(false);
		$this->paths = new paths();
		$this->blog = new blog();
		
		$this->setView( $view );
		$this->_loadWPOptions();
		$this->_loadNav();
	}
	
	public function getPaths() {
		return $this->paths;
	}
	
	/**
	 * setView
	 * @param String view
	 * æreturn void
	 *
	**/
	public function setView( $view ) {
		$this->view = $view;
	}
	/**
	 * getView
	 * @param void
	 * @return selected view filename
	**/
	public function getView() {
		return $this->view;
	}
	
	public function setTitle( $title ) {
		$this->title = $title;
		$this->SEO->setTitle( $title );
	}
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * getEnvironment
	 * @param void
	 * @return string (prod/dev/other)
	**/
	public function getEnvironment() {
		return CURRENT_ENVIRONMENT;
	}

	/**
	 * Last inn options som tilhører bloggen
	 */
	protected function _loadWPoptions() {
		global $page;
		$this->kommune = get_option('kommune');
		$this->pl_id = get_option('pl_id');
		$this->site_type = get_option('site_type');
	}
	public function getKommune() {
		return $this->kommune;
	}
	public function getPLID() {
		return $this->pl_id;
	}
	public function getSiteType() {
		return $this->site_type;
	}
	
	
	public function getBlog() {
		return $this->blog;
	}
	
	private function _loadNAV() {
		require( THEME_PATH .'config/sitemap.config.php');
		$this->nav = $sitemap;
		unset( $sitemap );
		return $this;
	}
	public function getNav() {
		return $this->nav;
	}
	/**
	 * getNavButtonShow
	 *
	 * Returnerer bool hvorvidt menyknappen skal vises eller ikke
	 * Brukes mest sannsynlig kun av siden "hovedmeny" som viser menyen 
	 * - da er knappen overflødig og gir uventet funksjonalitet
	 * 
	 * @return bool show/hide
	 *
	**/
	public function getNavButtonShow() {
		return $this->nav_show_menu_button;
	}
	public function setNavButtonShow( $status ) {
		$this->nav_show_menu_button = $status;
		return $this;
	}

/* MOVE TO LAYOUT
	
	public function section( $home ) {
		switch( $home ) {
			case 'arrangorer':
				$this->set( 'section', 'UKM for arrangører' );
				$this->set( 'analytics', 'UA-46216680-3');
				break;
			case 'voksneogpresse':
				$this->set( 'section', 'UKM for voksne og presse' );
				$this->set( 'analytics', 'UA-46216680-2');
				break;
			case 'derdubor':
				$this->set( 'section', 'UKM der du bor' );
				$this->set( 'analytics', 'UA-46216680-1');
				break;
			default:
				$this->set( 'section', 'UKM for ungdom' );
				$this->set( 'analytics', 'UA-46216680-1');
				break;
		}
	}
*/
}