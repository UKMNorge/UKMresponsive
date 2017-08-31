<?php

class nav {
	private $id = false;
	private $url = false;
	private $target = false;
	private $icon = false;
	private $description = false;

	private $children = array();
	private $children_order = array();

	public function __construct( $id, $url=false, $title=false ) {
		$this->setId( $id );
		$this->setUrl( $url );
		$this->setTitle( $title );
	}	

	/**
	 * add
	 *
	 * Opprett og legg til child-nav (med evt egne children)
	 * OBS: Returnerer nyopprettet child-objekt, ikke $this
	 * Kan senere aksesseres via $this->child( $id )
	 *
	 * @param order
	 * @param nav_object
	 * @return CHILD OBJECT
	 * 
	**/
	public function add( $order=false, $id, $url=false, $title=false ) {

		// Opprettes child uten order vil gi $order string og ingen title (maks 3 parametre)
		if( !is_numeric( $order ) && is_string( $order ) && false === $title ) {
			$title = $url;
			$url = $id;
			$id = $order;
			$order = sizeof( $this->children );
		}

		// Opprett nytt nav-objekt
		$navitem = new nav( $id, $url, $title );
				
		$this->children[ $navitem->getId() ] = $navitem;
		
		// Kalkuler rekkefølge (ikke skriv over eksisterende)
		$order = (float) $order;
		while( isset( $this->children_order[ $order ] ) ) {
			$order += 0.01;
		}
		$this->children_order[ $order ] = $navitem->getId();
		
		return $navitem;
	}

	/**
	 * remove
	 *
	 * Fjerner child med id $navitem_id og evt underlagte barn av denne.
	 *
	 * @param navitem_id	
	 * @return this
	 *
	**/
	public function remove( $id ) {
		if( isset( $this->children[ $id ] ) ) {
			unset( $this->children[ $id ] );	
		}
		return $this;
	}
	
	/**
	 * child
	 * 
	 * Returner et gitt child-objekt
	 *
	 * @param $id
	 * @return object child or false
	 *
	**/
	public function child( $id ) {
		if( !isset( $this->children[ $id ] ) ) {
			return false;
		}
		return $this->children[ $id ];
	}
	  
	
	/**
	 * build
	 * 
	 * Bygger og returnerer et menyarray i sortert rekkefølge
	 *
	 * @return array menu
	 *
	**/
		
	public function build() {
		ksort( $this->children_order );
		
		$menu = array();
		foreach( $this->children_order as $navitem_id ) {
			if( $this->child( $navitem_id ) ) {
				$menu[] = $this->child( $navitem_id );
			}
		}
		return $menu;
	}
	
	public function setId( $id ) {
		$this->id = $id;
		return $this;
	}
	public function getId() {
		return $this->id;
	}
	
	public function setUrl( $url ) {
		$this->url = $url;
		return $this;
	}
	public function getUrl() {
		return $this->url;
	}
	
	public function setTitle( $title ) {
		$this->title = $title;
		return $this;
	}
	public function getTitle() {
		return $this->title;
	}
	
	public function setTarget( $target ) {
		$this->target = $target;
		return $this;
	}
	public function getTarget() {
		return $this->target;
	}
	public function getTargetProperty() {
		return $this->getTarget() ? 'target="'. $this->getTarget() .'"' : '';
	}
	
	public function setIcon( $icon ) {
		$this->icon = $icon;
		return $this;
	}
	public function getIcon() {
		return $this->icon;
	}
	
	public function setDescription( $description ) {
		$this->description = $description;
		return $this;
	}
	public function getDescription() {
		return $this->description;
	}
}