<?php

$DATA['page']['title'] = single_cat_title('', false);

the_post();
$DATA['page'] = new WPOO_Post($post);

$posts = query_posts('posts_per_page=12&paged='.$paged);

while(have_posts()) {
   the_post();
   $DATA['posts'][] = new WPOO_Post($post); 
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