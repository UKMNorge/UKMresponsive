<?php
// LOAD PAGE DATA
the_post();
$DATA['page'] = new wp_get_post( $post );
