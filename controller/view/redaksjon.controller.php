<?php

$args = array(
    'blog_id'      => $GLOBALS['blog_id'],
    'role'         => '',
    'meta_key'     => '',
    'meta_value'   => '',
    'meta_compare' => '',
    'meta_query'   => array(),
    'include'      => array(),
    'exclude'      => array(),
    'orderby'      => 'login',
    'order'        => 'ASC',
    'offset'       => '',
    'search'       => '',
    'number'       => '',
    'count_total'  => false,
    'fields'       => 'all',
    'who'          => ''
);

$wpUsers = get_users($args);

foreach($wpUsers as $wpUser) {
    $users[] = new WPOO_Author(get_userdata($wpUser->ID));
}

$DATA['users'] = $users;