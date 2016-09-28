<?php
// EXAMPLE
#$sitemap->add( 1, 'ungdom', '//'.UKM_HOSTNAME.'/', 'for ungdom' );
#$sitemap->child('ungdom')->addIcon('')->addDescription('');


$sitemap = new nav( 'sitemap' );

// UKM DER DU BOR
// Må tas først, da ungdom-menyen "derdubor" refererer til denne
$sitemap->add( 3, 'derdubor', '//'.UKM_HOSTNAME.'/derdubor/', 'der du bor' );
$sitemap->child('derdubor')->add( 'forsiden', $sitemap->child('derdubor')->getUrl(), 'Velg ditt fylke');

// UKM FOR UNGDOM
$sitemap->add( 1, 'ungdom', '//'.UKM_HOSTNAME.'/', 'for ungdom' );
$sitemap->child('ungdom')->add( 'forsiden', $sitemap->child('ungdom')->getUrl(), 'Forsiden');
$sitemap->child('ungdom')->add( 'derdubor', $sitemap->child('derdubor')->getUrl(), ucfirst($sitemap->child('derdubor')->getTitle() ))
						 ->setDescription('Ditt lokale UKM og UKM i ditt fylke')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-maps.png');
$sitemap->child('ungdom')->add( 'festivalen', '//'.UKM_HOSTNAME.'/festivalen/', 'UKM-festivalen')
						 ->setDescription('Info om UKM-årets høydepunkt')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-rocket.png');
$sitemap->child('ungdom')->add( 'hvaer', '//'.UKM_HOSTNAME.'/hva-er-ukm/', 'Hva er UKM?')
						 ->setDescription('Hvem kan delta, hva kan du delta med?')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-star.png');
$sitemap->child('ungdom')->add( 'urg', '//'.UKM_HOSTNAME.'/urg/', 'URG - Ung ressursgruppe')
						 ->setDescription('Si din mening')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-kanin.png');
$sitemap->child('ungdom')->add( 'ambassadorer', '//ambassador.'.UKM_HOSTNAME.'/', 'Ambassadører')
						 ->setDescription('For deg som vil spre ordet om UKM')
						 ->setIcon('http://ambassador.ukm.no/');
$sitemap->child('ungdom')->add( 'kjendiser', '//'.UKM_HOSTNAME.'/blog/category/kjente-ukmere/', 'Kjente UKMere')
						 ->setDescription('Tidligere deltakere')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-people.png');
$sitemap->child('ungdom')->add( 'tidligere', '//'.UKM_HOSTNAME.'/tidligere-ar/', 'Tidligere år')
						 ->setDescription('Bilder, film og artikler')
						 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-monitor.png');

// UKM FOR VOKSNE OG PRESSE
$sitemap->add( 2, 'voksneogpresse', '//om.'.UKM_HOSTNAME.'/', 'for voksne og presse' );
$sitemap->child('voksneogpresse')->add( 'forsiden', $sitemap->child('voksneogpresse')->getUrl(), 'Forsiden');
$sitemap->child('voksneogpresse')->add( 'kontakt', '//om'.UKM_HOSTNAME.'/kontakt/', 'Kontakt')
								 ->setDescription('Kontaktpersoner i UKM-nettverket')
								 ->setIcon('http://om.ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-i.png');
$sitemap->child('voksneogpresse')->add( 'om', '//om'.UKM_HOSTNAME.'/om/', 'Finn ut mer om UKM' )
								 ->setDescription('Bakgrunsstoff, verdier og historikk')
								 ->setIcon('http://om.ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-star.png');
$sitemap->child('voksneogpresse')->add( 'profil', 'http://instrato.no/direct.php?id=213a83aeffd508fd', 'Grafisk profil')
								 ->setDescription('UKM-logo, skrifter og farger')
								 ->setIcon('http://om.ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-palette.png')
								 ->setTarget('_blank');
$sitemap->child('voksneogpresse')->add( 'pressebilder', 'http://www.flickr.com/photos/ukm/albums', 'Pressebilder')
								 ->setDescription('UKM-bilder til fri bruk')
								 ->setIcon('http://om.ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-palette.png')
								 ->setTarget('_blank');
$sitemap->child('voksneogpresse')->add( 'kjendiser', $sitemap->child('ungdom')->child('kjendiser')->getUrl(), $sitemap->child('ungdom')->child('kjendiser')->getTitle() )
								 ->setDescription( $sitemap->child('ungdom')->child('kjendiser')->getDescription() )
								 ->setIcon( $sitemap->child('ungdom')->child('kjendiser')->getIcon() );
$sitemap->child('voksneogpresse')->add( 'internasjonalt', '//internasjonalt.'.UKM_HOSTNAME.'/', 'Internasjonalt')
								 ->setDescription('Alt om UKMs internasjonale utviklinger')
								 ->setIcon('http://om.ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-globe.png');

// UKM-TV
$sitemap->add( 4, 'tv', '//tv.'.UKM_HOSTNAME.'/', 'UKM-TV' );
$sitemap->child('tv')->add( 'forsiden', '//tv.'.UKM_HOSTNAME.'/', 'Forsiden');
$sitemap->child('tv')->add( 'festivalen', '//tv.'.UKM_HOSTNAME.'/festivalen/', 'fra UKM-festivalen' )
#					 ->setDescription()
					 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-rocket.png');
$sitemap->child('tv')->add( 'fylke', '//tv.'.UKM_HOSTNAME.'/fylke/', 'fra fylkesmønstringer' )
#					 ->setDescription()
					 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/file.png');
$sitemap->child('tv')->add( 'lokal', '//tv.'.UKM_HOSTNAME.'/lokal/', 'fra UKM lokalt' )
#					 ->setDescription()
					 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/file.png');
$sitemap->child('tv')->add( 'info', '//tv.'.UKM_HOSTNAME.'/info/', 'infovideoer' )
#					 ->setDescription()
					 ->setIcon('http://ukm.no/wp-content/themes/UKMresponsive/img/nav/nav-i.png');

// UKM MEDIA
$sitemap->add( 5, 'media', '//media.'.UKM_HOSTNAME.'/', 'UKM Media' );
$sitemap->child('media')->add('forsiden', $sitemap->child('media')->getUrl(), 'Forsiden');
$sitemap->child('media')->add('hvaer', '//media.'.UKM_HOSTNAME.'/hva-er/', 'Hva er UKM Media?')
;#						->setDescription()
#						->setIcon();
$sitemap->child('media')->add('kontakt', '//media.'.UKM_HOSTNAME.'/kontakt/', 'Kontakt og tips')
;#						->setDescription()
#						->setIcon();

$sitemap->add( 5, 'festivalen', '//'.UKM_HOSTNAME.'/festivalen/', 'UKM-festivalen');
$sitemap->child('festivalen')->add('forsiden', $sitemap->child('festivalen')->getUrl(), 'Forsiden');

// ENGELSK
$sitemap->add( 6, 'english', '//'.UKM_HOSTNAME.'/about/', 'English' );
$sitemap->child('english')->add( 'forsiden', $sitemap->child('english')->getUrl(), 'Short intro of UKM');

// ARRANGØRER
$sitemap->add( 6, 'arrangorer', '//'.UKM_HOSTNAME.'/wp-login.php', 'Arrangører' );
$sitemap->child('arrangorer')->add('logginn', $sitemap->child('arrangorer')->getUrl(), 'Logg inn');