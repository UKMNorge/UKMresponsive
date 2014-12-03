<?php

$DATA['page']['title'] = single_cat_title('', false);

if( !isset( $DATA['jumbo'] ) ) {
	$DATA['jumbo'] = (object) array('header' => single_cat_title('', false),
									'content' => get_bloginfo('blog_name')
								   );
}

#the_post();
#$DATA['page'] = new WPOO_Post( $post );
$DATA['page'] = [];

$category = get_queried_object();
$DATA['page']->description = $category->description;

$per_page = get_option( 'category_'. $category->term_id .'_posts_per_page' );
if( !$per_page ) {
	$per_page = 12;
}

$posts = query_posts('posts_per_page='.$per_page.'&paged='.$paged.'&cat='.$category->term_id);

while(have_posts()) {
	the_post();
	$posten = new WPOO_Post($post);
	$metadata = get_post_custom($post->ID);
	if( is_array( $metadata ) ) {
		foreach( $metadata as $key => $val ) {
			$posten->meta[$key] = $val[0];
		}
	}
	$DATA['posts'][] = $posten;
}

$npl = get_next_posts_link();
if($npl) {
    $npl = explode('"',$npl); 
    $DATA['nextpost']=$npl[1];
}

$ppl = get_previous_posts_link();
if($ppl) {
    $ppl = explode('"',$ppl); 
    $DATA['prevpost']=$ppl[1];
}
wp_reset_postdata(); 