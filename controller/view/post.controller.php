<?php
// LOAD PAGE DATA
the_post();
$DATA['post'] = new WPOO_Post( $post );

$DATA['post']->author->company_name = 'UKM Norge';

/* TODO: Definere:
    post.author.image
    post.author.company_name
    post.author.facebook_url
*/