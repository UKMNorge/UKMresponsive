<?php






// FUNCTIONS FILE USED BY WORDPRESS TO REGISTER HOOKS ++
// USE functions_theme FOR FUNCTIONS RELATED TO THEME WHICH WP DOES NOT NEED TO EXEC ON-THE-FLY




add_theme_support( 'post-thumbnails' );

// POST IMAGE GALLERIES
require_once('functions/shortcode.gallery.php');

function add_remove_contactmethods( $contactmethods ) {
    $contactmethods['facebook'] = 'Facebook';
	$contactmethods['Title'] = 'Tittel';
    return $contactmethods;
}
add_filter('user_contactmethods','add_remove_contactmethods',10,1);



// SET MAX CONTENT WIDTH (IMAGES + OEMBED)
if ( ! isset( $content_width ) )
	$content_width = 1200;

add_action('wp_head', 'registerUKMTV');
function registerUKMTV() {
	wp_oembed_add_provider( 'http://tv.ukm.no/*', 'http://oembed.ukm.no/' );
}
wp_oembed_add_provider( 'http://tv.ukm.no/*', 'http://oembed.ukm.no/' );
add_filter( 'redirect_canonical', 'my_disable_redirect_canonical' );

function my_disable_redirect_canonical( $redirect_url ) {

	if ( is_singular( 'client' ) )
		$redirect_url = false;

	return $redirect_url;
}