<?php
$args = array(
/* 			'orderby'		 => 'post_title', */
			'order'          => 'ASC',
			'post_type'      => 'page',
			'post_parent'    => $post->ID,
			'numberposts'	 => -1,
		);

$kontakter = get_posts($args);

foreach( $kontakter as $kontakt ) {
	$k = new stdClass();
	$k->navn = $kontakt->post_title;
	$k->tittel 	= get_post_meta( $kontakt->ID, 'UKMkontakt_tittel', true);
	$k->tekst	= $kontakt->post_content;
	$k->epost	= get_post_meta( $kontakt->ID, 'UKMkontakt_epost', true);
	$k->mobil 	= get_post_meta( $kontakt->ID, 'UKMkontakt_mobil', true);
	$k->facebook= get_post_meta( $kontakt->ID, 'UKMkontakt_facebook', true);

	$post_thumbnail_id = get_post_thumbnail_id( $kontakt->ID ); 
	$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, 'medium' );
	$k->bilde = $post_thumbnail[0];
	$DATA['kontakter'][] = $k;
}