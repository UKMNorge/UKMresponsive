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
    'orderby'      => 'title',
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
$DATA['groups'] = array('journalist', 'fotograf', 'flerkamera', 'videorepotasje');
$DATA['ledere'] = array('redaktør', 'bildefikser', 'bildesorterer', 'teknisk ansvarlig', 'tekstansvarlig', 'fotoansvarlig', 'oppgaveansvarlig', 'nyhetsbrevansvarlig', 'videoredaktør');