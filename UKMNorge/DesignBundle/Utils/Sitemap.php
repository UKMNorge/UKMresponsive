<?php
	
namespace UKMNorge\DesignBundle\Utils;

use Symfony\Component\Yaml\Yaml;
use UKMNorge\DesignBundle\Utils\Sitemap\Section;

class Sitemap {
	static $config = [];
	static $sections = [];
	
	
	public static function loadFromYamlfile( $filepath ) {
		self::$config = Yaml::parse( file_get_contents( $filepath ) );
		
		foreach( self::$config as $key => $val ) {
			self::addSection( new Section( $key, $val ) );
		}
	}
	
	public static function getSection( $id ) {
		foreach( self::$sections as $order => $section ) {
			if( is_object( $section ) && $id == $section->getId() ) {
				return $section;
			}
		}
		return false;
	}
	
	public static function addSection( $section ) {
		if( !self::getSection( $section->getId() ) ) {
			self::$sections[] = $section;
		}
		return sizeof( self::$sections );
	}
	
	public static function getSections() {
		return self::$sections;
	}
	
	public static function getPage( $sectionId, $pageId=null ) {
		$section = self::getSection( $sectionId );
		if( is_object( $section ) ) {
			if( null == $pageId ) {
				return $section->getUrl();
			}
			return $section->getPage( $pageId );
		}
	}
}