var kommuneValg = kommuneValg || {};

kommuneValg.gammelHtml = "",
kommuneValg.nyHtml =  "",
kommuneValg.vis = function(kategori, pl_id, ico, kommuner ) {
    kommuneValg.gammelHtml = jQuery('#paamelding_container').html();
    
    kommuneValg.nyHtml = '<h4>Hvor vil du delta med '+kategori.toString().toLowerCase()+'?</h4>';
    kommuneValg.nyHtml += '<ul>';
    
    kommuner.forEach(function(kommune) {
        kommuneValg.nyHtml += '<a href="http://pamelding.ukm.no/quickstart.php?steg=kontaktperson&type=' + ico + '&kommune=' + kommune.id + '&pl_id=' + pl_id + '"><li>'+ kommune.name +'</li></a>';
    });
    
    kommuneValg.nyHtml += '</ul>';
    kommuneValg.nyHtml += '<button class="btn" onclick="kommuneValg.skjul()">Avbryt</button>';
     
    jQuery('#paamelding_container').html(kommuneValg.nyHtml);
};

kommuneValg.skjul = function() {
    jQuery('#paamelding_container').html(kommuneValg.gammelHtml);
};

