var kommuneValg=kommuneValg||{};kommuneValg.gammelHtml="",kommuneValg.nyHtml="",kommuneValg.vis=function(m,l,n,a,e){kommuneValg.gammelHtml=jQuery("#paamelding_container").html(),kommuneValg.nyHtml="<h4>Hvor vil du delta med "+l.toString().toLowerCase()+"?</h4>",kommuneValg.nyHtml+="<ul>",e.forEach(function(l){kommuneValg.nyHtml+='<a href="http://pamelding.ukm.no/quickstart.php?steg='+m+"&type="+a+"&kommune="+l.id+"&plid="+n+'" target="_blank"><li>'+l.name+"</li></a>"}),kommuneValg.nyHtml+="</ul>",kommuneValg.nyHtml+='<button class="btn" onclick="kommuneValg.skjul()">Avbryt</button>',jQuery("#paamelding_container").html(kommuneValg.nyHtml)},kommuneValg.skjul=function(){jQuery("#paamelding_container").html(kommuneValg.gammelHtml)};