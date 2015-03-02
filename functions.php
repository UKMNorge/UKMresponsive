<?php
// FUNCTIONS FILE USED BY WORDPRESS TO REGISTER HOOKS ++
// USE functions_theme FOR FUNCTIONS RELATED TO THEME WHICH WP DOES NOT NEED TO EXEC ON-THE-FLY

if( function_exists('is_admin') ) {
	if( is_admin() && !in_array(get_option('site_type'), array('kommune','fylke','land')) ) {
		add_action('admin_menu', 'UKMresponsive_menu');
	}
}

function UKMresponsive_menu() {
	$page = add_menu_page('Oppsett', 'Oppsett', 'administrator', 'UKMresponsive_layout', 'UKMresponsive_layout', 'http://ico.ukm.no/art-supplies-menu.png',40);
}


function UKMresponsive_layout() {
	require_once('wp-admin/layout.php');
}

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

// SIKRE HOMEPAGE PAGINATION
	update_option('posts_per_page', 5);
	update_option('show_on_front', 'posts');
	update_option('page_on_front',2);