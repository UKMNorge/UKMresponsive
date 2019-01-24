<?php
namespace UKMNorge\DesignBundle\Utils;
	
class SEO {
	public static $canonical;
	public static $description;
	public static $author;
	public static $site_name;
	public static $type;
	public static $title;
	public static $section;
	public static $image;
	public static $published;
	
	public static $fb_admins;
	public static $fb_app_id;
	
	public static $google_site_verification;
	public static $google_analytics;
	
	public static $oembed = false;
	
	public static function getCanonical(){
		return self::$canonical;
	}
	public static function setCanonical( $canonical ){
		self::$canonical = $canonical;
	}
	
	public static function getDescription(){
		return self::$description;
	}
	public static function setDescription( $description ){
		self::$description = str_replace(["\r","\n"], "", strip_tags($description));
	}
	
	public static function getAuthor(){
		return self::$author;
	}
	public static function setAuthor( $author ){
		self::$author = $author;
	}
	
	public static function getSiteName(){
		return self::$site_name;
	}
	public static function setSiteName( $site_name ){
		self::$site_name = $site_name;
	}
	
	public static function getType(){
		return self::$type;
	}
	public static function setType( $type ){
		self::$type = $type;
	}
	
	public static function getTitle(){
		return self::$title;
	}
	public static function setTitle( $title ){
		self::$title = $title;
	}
	
	public static function getSection(){
		return self::$section;
	}
	public static function setSection( $section ){
		self::$section = $section;
	}
	
	public static function getImage(){
		return self::$image;
	}
	public static function setImage( $image ){
		self::$image = $image;
	}
	
	public static function getPublished(){
		return self::$published;
	}
	public static function setPublished( $published ){
		self::$published = $published;
	}


	public static function getFBAdmins() {
		return self::$fb_admins;
	}
	public static function setFBAdmins( $fb_admin_ids ) {
		self::$fb_admins = $fb_admin_ids;
	}
	public static function getFBAppId() {
		return self::$fb_app_id;
	}
	public static function setFBAppId( $fb_app_id ) {
		self::$fb_app_id = $fb_app_id;
	}
	
	
	
	public static function getGoogleSiteVerification() {
		return self::$google_site_verification;
	}
	public static function setGoogleSiteVerification( $site_verification_id ) {
		self::$google_site_verification = $site_verification_id;
	}
	
	public static function getGoogleAnalytics() {
		return self::$google_analytics;
	}
	public static function setGoogleAnalytics( $analytics ) {
		self::$google_analytics = $analytics;
	}
	
	public static function getOembed() {
		return self::$oembed;
	}
	public static function setOembed( $oembed ) {
		self::$oembed = $oembed;
	}
}