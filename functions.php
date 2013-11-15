<?php






// FUNCTIONS FILE USED BY WORDPRESS TO REGISTER HOOKS ++
// USE functions_theme FOR FUNCTIONS RELATED TO THEME WHICH WP DOES NOT NEED TO EXEC ON-THE-FLY










// SET MAX CONTENT WIDTH (IMAGES + OEMBED)
if ( ! isset( $content_width ) )
	$content_width = 1200;

add_action('wp_head', 'registerUKMTV');
function registerUKMTV() {
	wp_oembed_add_provider( 'http://tv.ukm.no/*', 'http://oembed.ukm.no/' );
}
wp_oembed_add_provider( 'http://tv.ukm.no/*', 'http://oembed.ukm.no/' );
