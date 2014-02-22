$(function(){
    var map;
    var vetorCoordenadas;
});

// Carrega o 1º mapa
function init() {
    var lonlat = new OpenLayers.LonLat(-58.6324594,-15.7956343).transform('EPSG:4326', 'EPSG:3857');

    polygonLayer = new OpenLayers.Layer.Vector("Mostrar Poligono");
    poligono = new OpenLayers.Control.DrawFeature(polygonLayer, OpenLayers.Handler.Polygon);

    map = new OpenLayers.Map('map', {
        controls: [ new OpenLayers.Control.LayerSwitcher(), new OpenLayers.Control.Navigation(),  poligono],
        projection: 'EPSG:3857',
        center: lonlat
        });

    map.addLayers([
            new OpenLayers.Layer.Google(
                "Google Hybrid",
                {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
            ),
            new OpenLayers.Layer.Google(
                "Google Physical",
                {type: google.maps.MapTypeId.TERRAIN}
            ),
            new OpenLayers.Layer.Google(
                "Google Streets", // the default
                {numZoomLevels: 20}
            ), 
            new OpenLayers.Layer.Google(
                "Google Satellite",
                {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
            ),
            polygonLayer
        ]);

    map.zoomTo(4);
    poligono.activate();
   //console.log("para debug: "+polygonLayer.renderer.extent);
}

// captura região desenhada e converte em Polygon ou Multipolygon
function preencheCamposCoordenadas(){
    try{
        var coordenadaPoligono = "",
            tamPolygon = polygonLayer.features.length;

        if(tamPolygon > 1){
            var multipolygon = "";

            for(var i = 0; i < tamPolygon; i++){
                coordenadaPoligono = polygonLayer.features[i].geometry + "";
                multipolygon += coordenadaPoligono;
            }

            multipolygon = multipolygon.replace(/polygon/gi, ",");
            multipolygon = multipolygon.replace(",","");
            multipolygon = "MULTIPOLYGON("+multipolygon+")";
            multipolygon.split("{",1);
            $('#poligono').val(multipolygon);
        }
        else{
            coordenadaPoligono = polygonLayer.features[0].geometry + "";
            coordenadaPoligono.split("{",1);
            $('#poligono').val(coordenadaPoligono);
        }
    } catch(e){
        console.log(e);
    }   
}

// Carrega os pontos retornados do banco no 2º mapa
function carregaPontosMapa() {

    var multuPolygonGeometry,
        multiPolygonFeature,
        isPolygon,
        ultimoPoligono,
        coordenadasDesenhadas,
        tCoordenadas,
        patternPolygon = /POLYGON(?=.)/,
        coordenadas = "", 
        polygonList = [],
        pointList = [],
        multipolygon = [],
        poligonoPostGis = $('#poligono').val(),
        source = new Array(), 
        arrayDeCoord = new Array(), 
        lonlat = new OpenLayers.LonLat(-58.6324594,-15.7956343).transform('EPSG:4326', 'EPSG:3857'),   
        vector = new OpenLayers.Layer.Vector('multiPolygon'),
        poi = new OpenLayers.Layer.Markers( "Markers" ),
        size = new OpenLayers.Size(15,15),
        offset = new OpenLayers.Pixel(-(size.w/2), -size.h),
        icon = new OpenLayers.Icon('scripts/img/marker.png',size, offset);

    tCoordenadas = $("#pontos").val();
    
    if(tCoordenadas != undefined && tCoordenadas != ""){
        coordenadas = JSON.parse($("#pontos").val());
    }

    map2 = new OpenLayers.Map('map2', 
        {
            controls: [ new OpenLayers.Control.LayerSwitcher(), new OpenLayers.Control.Navigation()],
            projection: 'EPSG:3857',
            center: lonlat
        });

    //inicio redesenho do polígono no 2º mapa
    if(poligonoPostGis!=null && poligonoPostGis!=""){
        try{
            isPolygon = patternPolygon.test(poligonoPostGis);

            if(isPolygon == true){
                //polígono
                poligonoPostGis = poligonoPostGis.replace("POLYGON((","");
                poligonoPostGis = poligonoPostGis.replace(/\)\)/gi,"");
                coordenadasDesenhadas = poligonoPostGis.split(",");

                $.each(coordenadasDesenhadas, function(i){
                    var coord = coordenadasDesenhadas[i].split(" ");
                    source[i] = {x: Number(coord[0]), y: Number(coord[1])};
                });

                arrayDeCoord[0] = source;

                for (var i = arrayDeCoord.length; i--;) {
           
                    for (var j=0; j<arrayDeCoord[i].length; j+=1) {
                        var point = new OpenLayers.Geometry.Point(arrayDeCoord[i][j].x, arrayDeCoord[i][j].y);
                        pointList.push(point);
                    }

                    var linearRing = new OpenLayers.Geometry.LinearRing(pointList);
                    var polygon = new OpenLayers.Geometry.Polygon([linearRing]);

                    polygonList.push(polygon);
                }

                multuPolygonGeometry = new OpenLayers.Geometry.MultiPolygon(polygonList);
                multiPolygonFeature = new OpenLayers.Feature.Vector(multuPolygonGeometry);

                vector.addFeatures(multiPolygonFeature);
            }
            else{
                //multipolígono
                poligonoPostGis = poligonoPostGis.replace(/\(\(/gi,"");
                multipolygon = poligonoPostGis.split(")),");
                ultimoPoligono = multipolygon.length - 1;
                multipolygon[ultimoPoligono] = multipolygon[ultimoPoligono].replace(/\)\)/gi,"");

                $.each(multipolygon, function(k){
                    coordenadasDesenhadas = multipolygon[k].split(",");

                    $.each(coordenadasDesenhadas, function(i){
                        var coord = coordenadasDesenhadas[i].split(" ");
                        source[i] = {x: Number(coord[0]), y: Number(coord[1])};
                    });

                    arrayDeCoord[k] = source;

                    for (var i = arrayDeCoord.length; i--;) {
           
                        for (var j=0; j<arrayDeCoord[i].length; j+=1) {
                            var point = new OpenLayers.Geometry.Point(arrayDeCoord[i][j].x, arrayDeCoord[i][j].y);
                            pointList.push(point);
                        }

                        var linearRing = new OpenLayers.Geometry.LinearRing(pointList);
                        var polygon = new OpenLayers.Geometry.Polygon([linearRing]);

                        polygonList.push(polygon);
                    }

                    multuPolygonGeometry = new OpenLayers.Geometry.MultiPolygon(polygonList);
                    multiPolygonFeature = new OpenLayers.Feature.Vector(multuPolygonGeometry);

                    vector.addFeatures(multiPolygonFeature);
                });
            }
        }catch(e){
            console.log(e);
        } 
    }
    else{
        vector = new OpenLayers.Layer.Vector('multiPolygon');
    }
    //fim redesenho do polígono no mapa
    //inserindo pontos no mapa
    if(coordenadas[0]){
        $.each(coordenadas , function(i){
            //atributos JSON
            latitude = coordenadas[i].st_y;
            longitude = coordenadas[i].st_x;
            var urlGoogle = "https://maps.google.com.br/maps?q="+latitude+","+longitude;
            var nome = coordenadas[i].name;
            var descricao = coordenadas[i].description;
            //marcador (pontos)
            poi.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(longitude, latitude).transform('EPSG:4326', 'EPSG:3857'),icon.clone()));
            poi.events.register(
                'mousemove', 
                poi, 
                function(evt) {
                    link = '<a href="'+urlGoogle+'" target="_blank"></a>';
                    idElemento = this.markers[i].icon.imageDiv.attributes[0].nodeValue;
                    elemento = $("#"+idElemento+" img");
                    
                    if ($("#"+idElemento+" a").length){
                        //console.log("já tem link");
                    }
                    else{
                        $(elemento).wrap(link);
                        $(elemento).addClass("imagem");
                        $(elemento).attr('data-title', nome);
                        $(elemento).attr('data-html',true);
                        $(elemento).attr('data-trigger',"hover");
                        $(elemento).attr('data-delay',"{show: 500, hide: 100}"); 
                        $(elemento).attr('data-placement',"left");
                        $(elemento).attr('data-content',descricao);
                        $(elemento).css("height","15px");
                        $(elemento).css("width","15px");
                        $(".imagem").popover();
                        OpenLayers.Event.stop(evt); 
                    }
                }
            );            
        });
    }
    
    map2.addLayers([
            new OpenLayers.Layer.Google(
                "Google Hybrid",
                {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20}
            ),
            new OpenLayers.Layer.Google(
                "Google Physical",
                {type: google.maps.MapTypeId.TERRAIN}
            ),
            new OpenLayers.Layer.Google(
                "Google Streets", // the default
                {numZoomLevels: 20}
            ), 
            new OpenLayers.Layer.Google(
                "Google Satellite",
                {type: google.maps.MapTypeId.SATELLITE, numZoomLevels: 22}
            ),
            vector, 
            poi
        ]);

    map2.zoomTo(4);
   //console.log("para debug: "+polygonLayer.features[0].geometry.getBounds()); 
}

// envia polígono desenhado por AJAX para a classe de consulta ao BD.
function enviaDados() {
    $('#map2').html('');
    preencheCamposCoordenadas();
    var dados = 'poligono='+$('#poligono').val()+'&submitted='+$('#submitted').val(); 

    $.ajax({                 
        type: 'POST',                 
        //dataType: 'json',                 
        url: 'consultaPoligono.php',                 
        async: true,                 
        data: dados,                 
        success: function(response) {
            $("#pontos").attr('value',response);
            carregaPontosMapa();                 
        }             
    });       
}

// botão de nova busca
function novaBusca(){
    $("#poligono").val("");

    limparMapas();
    $('#map2').html("");
}

//reseta mapas
function limparMapas(){
    //reseta as layers, se houver
    try{
        polygonLayer.destroyFeatures();
        polygonLayer.drawFeature();
    } catch(e){
        console.log(e);
    }   
}