<?php
if( $JUMBO_POST_ID ) {
	$jumbo_header = get_post_meta($JUMBO_POST_ID, 'UKMjumbo_header', true);
	$jumbo_content = get_post_meta($JUMBO_POST_ID, 'UKMjumbo_content', true);
	
	if( $jumbo_header || $jumbo_content ) {
		$DATA['jumbo'] = (object) array('header' => $jumbo_header, 'content' => $jumbo_content);
	}
}
?>