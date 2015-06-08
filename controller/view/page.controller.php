<?php
	# OBS : INKLUDERES AV INDEX.PHP ETTER POST.CONTROLLER

require_once( THEME_PATH .'/functions/blocks.inc.php');
$DATA['blocks'] = setup_blocks_from_subpages( $post->ID );