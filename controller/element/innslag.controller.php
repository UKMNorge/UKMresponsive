<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');
require_once('UKM/forestilling.class.php');

function generate_list_data( $innslag, $pl, $current_c_id=false ) {
	$data = new stdClass();
	
	// LOAD OBJECT
	$innslag = new innslag( $innslag['b_id'] );
	$innslag->loadGeo();

	// BASIS-DATA
	$data->ID			= $innslag->g('b_id');
	$data->navn 		= $innslag->g('b_name');
	$data->kategori		= $innslag->g('bt_name');
	$data->sjanger		= $innslag->g('b_sjanger');
	if( $pl->g('type') == 'land' ) {
		$data->sted 	= $innslag->g('fylke').', '. $innslag->g('kommune');
	} else {
		$data->sted 	= $innslag->g('kommune');
	}

	// UTFYLLENDE DATA
	$data->beskrivelse	= $innslag->g('b_description');
	
	// TITLER
	$titler = $innslag->titler( $pl->g('pl_id') );
	foreach( $titler as $tittel ) {
		$t = new stdClass();
		$t->tittel		= $tittel->g('tittel');
		$parentes		= $tittel->g('parentes');
		$t->parentes	= substr( $parentes, 1, strlen( $parentes )-2 );
		$t->parentes	= str_replace(' - ', '<br />', $t->parentes);
		$data->titler[]	= $t;
	}
	
	// DELTAKERE
	$personer = $innslag->personer( $pl->g('pl_id') );
	foreach( $personer as $person) {
		$person = new person( $person['p_id'], $innslag->g('b_id') );
		$p = new stdClass();
		$p->navn		= $person->g('p_firstname') .' '. $person->g('p_lastname');
		$p->alder		= $person->getAge( $pl );
		$p->rolle		= $person->g('instrument');
		
		$data->personer[]= $p;
	}
	
	// HENDELSER
	if( $current_c_id ) {
		$hendelser = $innslag->forestillinger( $pl->g('pl_id') );
		$data->hendelser	= array();
		foreach( $hendelser as $c_id => $nr ) {
			if( $c_id == $current_c_id ) {
				continue;
			}
			$hendelse = new forestilling( $c_id );
			$h = new stdClass();
			$h->navn 		= $hendelse->g('c_name');
			$h->start		= $hendelse->g('c_start');
			$h->nr			= $nr;
			
			$data->hendelser[] = $h;
		}
		if( sizeof( $data->hendelser ) == 0 ) {
			$data->hendelser_kun_denne = true;
		}
	}
	
	// RELATERT MEDIA
	$media = $innslag->related_items();

	// FILMER
	if( isset( $media['tv'] ) && sizeof( $media['tv'] ) > 0) {
		foreach( $media['tv'] as $tv_id => $tv ) {
			$tv->iframe('1100px');
			$media['tv'][ $tv_id ] = $tv;
		}
		$data->UKMTV = $media['tv'];	
	}

	// BILDER
	if(isset($media['image']) && is_array($media['image'])) {
		$imageCounter = 0;
		global $blog_id;
		$pl_id = get_option('pl_id');
		foreach($media['image'] as $id => $item){			
			$url = $item['blog_url'].'/files/';
			$large = (!isset($item['post_meta']['sizes']['large'])
							? $item['post_meta']['file']
							: $item['post_meta']['sizes']['large']['file']
							);
							
			if( strlen( $large ) > 0 ) {
				$photo = new stdClass();
				$photo->url 	= new stdClass();

				if( isset( $item['post_meta']['sizes']['large']) ) {
					$photo->width = $item['post_meta']['sizes']['large']['width'];					
					$photo->height = $item['post_meta']['sizes']['large']['height'];					
				} else {
					#$data = @getimagesize( $large );
					if($data) {
						list($width, $height, $type, $attr) = $data;
						$photo->width = $width;
						$photo->height = $height;
					} else {
						$photo->width = null;
						$photo->height = null;
					}
				}

				if( isset( $item['post_meta']['sizes']['thumbnail']) && isset( $item['post_meta']['sizes']['thumbnail']['file'] ) ) {
					$photo->thumb = $url. $item['post_meta']['sizes']['thumbnail']['file'];					
				} else {				
					$photo->url->thumb 	= $url . $large;
				}
				
				$photo->url->full	= $url . $large;
				$photo->id 			= $item['rel_id'];
				$photo->pl_type		= $item['pl_type'];
				$photo->foto		= isset($item['post_meta']['author']) ? $item['post_meta']['author'] : '';

/*
				$b = new stdClass();
				$b->full 	= $url . $large;
				$b->foto	= isset($item['post_meta']['author']) ? $item['post_meta']['author'] : '';
				$b->pl_type	= $item['pl_type'];
*/
				
				$data->bilder[ $item['pl_type'] ][] = $photo;
			}
		}
	}
	
	// ARTIKLER
	if(isset($media['post']) && is_array($media['post'])) {
		foreach( $media['post'] as $artikkel ) {
			$a = new stdClass();
			$a->url 	= $artikkel['post_meta']['link'];
			$a->tittel 	= base64_decode($artikkel['post_meta']['title']);
			
			$data->artikler[] = $a;
		}
	}	
	return $data;
}