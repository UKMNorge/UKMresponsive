var kommuneValg = {
    gammelHtml: "",
    nyHtml: "",
    
    vis: function(kategori, pl_id, ico, kommuner ) {
        
        gammelHtml = jQuery('#paamelding_container').html();
        
        nyHtml = '<h4>Hvor vil du delta med '+kategori.toString().toLowerCase()+'?</h4>';
        nyHtml += '<ul>';
        
        kommuner.forEach(function(kommune) {
            nyHtml += '<a href="http://pamelding.ukm.no/quickstart.php?steg=kontaktperson&type=' + ico + '&kommune=' + kommune.id + '&pl_id=' + pl_id + '"><li>'+ kommune.name +'</li></a>';
        });
        
        nyHtml += '</ul>';
        nyHtml += '<button class="btn" onclick="kommuneValg.skjul()">Avbryt</button>';
         
        jQuery('#paamelding_container').html(nyHtml);
    },
    skjul: function() {
        jQuery('#paamelding_container').html(gammelHtml);
    }
}
