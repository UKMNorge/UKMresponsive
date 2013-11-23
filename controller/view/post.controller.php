<?php
// LOAD PAGE DATA
the_post();
$DATA['post'] = new WPOO_Post( $post );

$DATA['post']->author->image = 'http://placehold.it/85x64';
$DATA['post']->author->company_name = 'UKM Norge';

/* TODO: Definere:
    post.author.image
    post.author.company_name
    post.author.facebook_url
*/