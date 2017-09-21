<?php

use UKMNorge\DesignBundle\Utils\Sitemap;
use UKMNorge\DesignBundle\Utils\SEO;

require_once('header.php');

global $post, $post_id;
$WP_TWIG_DATA['post'] = new WPOO_Post( $post);

$authorlist = '';
// LOAD MULTI-AUTHORS LIST
if ( isset( $WP_TWIG_DATA['post']->meta->ukm_ma ) ) {
	$list = json_decode( $WP_TWIG_DATA['post']->meta->ukm_ma, true);
	$authors = array();

	foreach ($list as $user_login => $role) {
		$user = get_user_by('login', $user_login);
		if ($user) {
			$authors[$user_login] = new WPOO_Author($user);
			$authors[$user_login]->role = $role;
			$authorlist .= ucfirst( $authors[ $user_login ]->display_name ) .', ';
		}
	}
	$WP_TWIG_DATA['authors'] = $authors;

}
// MULTI-AUTHORS NOT DEFINED, USE POST OWNER
 else {
	$WP_TWIG_DATA['authors'] = $WP_TWIG_DATA['post']->author;
	$authorlist .= ucfirst( $WP_TWIG_DATA['post']->author->display_name ) .', ';
}
$authorlist = rtrim( $authorlist, ', ');

SEO::setType('article');
SEO::setTitle( $WP_TWIG_DATA['post']->title );
SEO::setDescription( addslashes( preg_replace( "/\r|\n/", "", strip_tags( $WP_TWIG_DATA['post']->lead ) ) ) );
SEO::setAuthor( $authorlist );
SEO::setPublished( $WP_TWIG_DATA['post']->raw->post_date_gmt );



echo WP_TWIG::render( 'Post/fullpage', $WP_TWIG_DATA );
wp_footer();
if(is_user_logged_in() ) {
	echo '<style>body {margin-top: 33px;} @media (max-width:782px) {body {margin-top: 48px;}}</style>';
}

if( WP_ENV == 'dev' ) {
	echo '<script language="javascript">console.debug("'.basename(__FILE__).'");</script>';
}