<?php
require_once(THEME_PATH.'functions/blocks.inc.php');
$blocks = array();
$DATA['blog']['css_extra'][] = 'less/css/festival14.css';

	// JUMBO TOP IMAGE	
	$blocks[] = block_jumbo_image('top',
								  'UKM Media', 
								  ' - et mediehus av og for ungdom', 
								  'https://farm4.staticflickr.com/3839/14564592764_30baef7413_c.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_e8386105bf_h.jpg',
								  'https://farm4.staticflickr.com/3839/14564592764_9f6c78dc01_k.jpg'
								   );

	$blocks[] = block_lead( 'lead', UKM_HOSTNAME=='ukm.no' ? 2 : 5);

	$blocks[] = block_image_oob_left('konsept',
									 UKM_HOSTNAME=='ukm.no' ? 13 : 18,
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_c.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_b.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_c4e3a3a7f6_b.jpg',
									 'https://farm9.staticflickr.com/8451/8021270983_639215e84e_h.jpg'
									);

	
	$blocks[] = block_lead( 'flerkamera', UKM_HOSTNAME=='ukm.no' ? 25 : 5);

	$blocks[] = block_lead_center( 'hdbussene', UKM_HOSTNAME=='ukm.no' ? 27 : 13);

	$blocks[] = block_lead( 'videoreportasjer', UKM_HOSTNAME=='ukm.no' ? 29 : 5);

	$blocks[] = block_lead_center( 'studio', UKM_HOSTNAME=='ukm.no' ? 33 : 13);

	$blocks[] = block_image_oob_right('foto',
									 UKM_HOSTNAME=='ukm.no' ? 35 : 9,
									 'https://farm4.staticflickr.com/3840/14533175723_732541e923_c.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_732541e923_b.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_025ebd9485_h.jpg',
									 'https://farm4.staticflickr.com/3840/14533175723_0bf9a226c8_k.jpg'
									);

	$blocks[] = block_lead( 'tekst', UKM_HOSTNAME=='ukm.no' ? 37 : 5);

	$blocks[] = block_lead_center( 'promo', UKM_HOSTNAME=='ukm.no' ? 40 : 5);

// SEND TO TWIG	
	$DATA['blocks'] = $blocks;


function media_icon( $anchor, $title, $icon_url ) {
	$icon = new stdClass();
	$icon->anchor = $anchor;
	$icon->title = $title;
	$icon->icon = $icon_url;

	return $icon;
}