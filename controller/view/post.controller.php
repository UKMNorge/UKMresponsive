<?php
// LOAD PAGE DATA
the_post();
$DATA['post'] = new WPOO_Post( $post );

/* TODO: Definere:
    post.author.company_name
    post.author.facebook_url
*/