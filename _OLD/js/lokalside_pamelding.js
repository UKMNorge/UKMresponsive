var kommuneValg = kommuneValg || {};

kommuneValg.gammelHtml = "",
kommuneValg.nyHtml =  "",
kommuneValg.vis = function(steg, kategori, pl_id, ico, kommuner ) {
    kommuneValg.gammelHtml = jQuery('#paamelding_container').html();
    
    kommuneValg.nyHtml = '<h4>Hvilken kommune/bydel representerer du?</h4>';
    kommuneValg.nyHtml += '<ul>';
    
    kommuner.forEach(function(kommune) {
        kommuneValg.nyHtml += '<a href="http://pamelding.ukm.no/quickstart.php?steg='+ steg +'&type=' + ico + '&kommune=' + kommune.id + '&plid=' + pl_id + '" target="_blank"><li>'+ kommune.name +'</li></a>';
    });
    
    kommuneValg.nyHtml += '</ul>';
    kommuneValg.nyHtml += '<button class="btn" onclick="kommuneValg.skjul()">Avbryt</button>';
     
    jQuery('#paamelding_container').html(kommuneValg.nyHtml);
};

kommuneValg.skjul = function() {
    jQuery('#paamelding_container').html(kommuneValg.gammelHtml);
};

