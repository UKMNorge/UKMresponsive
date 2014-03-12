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










function my_post_queries( $query ) {
  // do not alter the query on wp-admin pages and only alter it if it's the main query
  if (!is_admin() && $query->is_main_query()){

    // alter the query for the home and category pages 

    if(is_home()){
      $query->set('posts_per_page', 7);
    }
  }
}
add_action( 'pre_get_posts', 'my_post_queries' );