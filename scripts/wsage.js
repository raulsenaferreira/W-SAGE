$(function(){
    var map;
    var poligono;
    var coordenadas = "";
    var vetorCoordenadas;
    var heatmap;
    var geocoder;
    var controls;
    var mapLayers; 
    var testPDF;
    preencheNaturalidade();
});

// Carrega o 1º mapa
function init() {
    criaMapa();
}

// botão de nova busca
function novaBusca(){
    $('#map').html("");
    criaMapa();
}
//autocomplete de naturalidade
function preencheNaturalidade(){
    $.getJSON('source.php', function(data){
        var naturalidades = [];
         
        $(data).each(function(key, value) {
            naturalidades.push(value.naturalidade);
        });
         
        $('.naturalidade').autocomplete({ source: naturalidades, minLength: 2});
    });
}
/*função que percorre os filtros e verifica quais parâmetros estão marcados
 para ser usado na consulta */
function preencheFiltros(){
    var data = { 'filtros' : []};
    $("#filtros input:checked").each(function() {
      data['filtros'].push("&"+$(this).attr('name')+"="+$(this).val());
    });
    
    $("#filtros .texto input").each(function() {
        if ($(this).val()!='') {
            data['filtros'].push("&"+$(this).attr('name')+"="+$(this).val());
        }
    });
    //console.log(data['filtros']);
    return data['filtros'];
}

// envia polígono desenhado por AJAX para a classe de consulta ao BD.
function enviaDados() {
    var situacao = preencheFiltros();
    situacao = situacao.toString();
    situacao = situacao.replace(/,/g,'');

    $('#map').html('');
    preencheCamposCoordenadas();
    var dados = 'poligono='+$('#poligono').val()+
    '&submitted='+$('#submitted').val()+
    situacao; 

    $.ajax({                 
        type: 'POST',                 
        //dataType: 'json',                 
        url: 'consultaPoligono.php',                 
        async: true,                 
        data: dados,                 
        success: function(response) {
            $("#pontos").attr('value',response);
            //console.log(dados);
            enviaDadosPython(dados);
        }             
    });

           
}

function enviaDadosPython(dados){
    console.log(dados);
    $.ajax({                 
        type: 'POST',                 
        //dataType: 'json',                 
        url: 'consultaPDF.php',                 
        async: true,                 
        data: dados,
        success: function(response) {
            $("#pdfs").attr('value',response);
            //console.log(dados);
            carregaPontosMapa();
        }             
    });

    
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
        polygonList = [],
        pointList = [],
        multipolygon = [],
        poligonoPostGis = $('#poligono').val(),
        source = new Array(), 
        arrayDeCoord = new Array(), 
        vector = new OpenLayers.Layer.Vector('multiPolygon'),
        poi = new OpenLayers.Layer.Markers( "Markers" ),
        size = new OpenLayers.Size(15,15),
        offset = new OpenLayers.Pixel(-(size.w/2), -size.h),
        icon = new OpenLayers.Icon('scripts/img/marker.png',size, offset);

    criaMapa();

    tCoordenadas = $("#pontos").val();
    
    if(tCoordenadas != undefined && tCoordenadas != ""){
        coordenadas = JSON.parse(tCoordenadas);
    }

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
            // var urlGoogle = "https://maps.google.com.br/maps?q="+latitude+","+longitude;
            // var nome = coordenadas[i].name;
            // var descricao = coordenadas[i].description;
  
            //marcador (pontos)
            poi.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(longitude, latitude).transform('EPSG:4326', 'EPSG:3857'),icon.clone()));
            /*poi.events.register(
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
            );*/            
        });
        
        var pdfs = JSON.parse($("#pdfs").val());
        testPDF = pdfs;
        heatMap(coordenadas, pdfs);
    }

    mapLayers[mapLayers.length] = vector;
    mapLayers[mapLayers.length] = poi;
    map.addLayers(mapLayers);
}


// Funcao para ativar e desativar o poligono.
function activePolygonDraw(active) {
    if(active == 0){
        poligono.deactivate();
    }else{
        poligono.activate();
    }
} 

function criaMapa(){
    geocoder = new google.maps.Geocoder();
   
    //Requisitando ao openlayer para criar um mapa.
    map = new OpenLayers.Map('map')
   
    //Definindo os mapas que seram exibidos.
    polygonLayer = new OpenLayers.Layer.Vector("Mostrar Poligono");

    mapLayers=[
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
    ];

    map.addLayers(mapLayers);

    //Adicionando os controles, vai permitir desenhar o poligono.
    poligono = new OpenLayers.Control.DrawFeature(polygonLayer, OpenLayers.Handler.Polygon); 
    map.addControl(new OpenLayers.Control.LayerSwitcher());
    map.addControl(new OpenLayers.Control.MousePosition()); 
    map.addControl(poligono);


    //Fazendo o mapa iniciar no Brasil
    var lonlat = new OpenLayers.LonLat(-58.6324594,-15.7956343).transform('EPSG:4326', 'EPSG:3857');
    map.setCenter(lonlat, 4); 
}


// captura região desenhada e converte em Polygon ou Multipolygon
function preencheCamposCoordenadas(){
    try{
        var coordenadaPoligono = "";
            

        if(polygonLayer.features[0] != undefined){
        	var tamPolygon = polygonLayer.features.length;
        	
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
        }
    } catch(e){
        console.log(e);
    }   
}

function heatMap(coordenadas, pdfs){ 
    arrayData = [];
	var arrayPDF = JSON.parse(pdfs);
	
    $.each(coordenadas , function(i){
    //console.log("pdf: "+arrayPDF);
        arrayData[i] = {lat: coordenadas[i].st_y,  lng: coordenadas[i].st_x, count: arrayPDF[i]};//arrayPDF[i]
        //console.log(arrayPDF[i]);
    });    
    
    //map3 = new google.maps.Map(document.getElementById("heatmapArea"), myOptions);
    //heatmap = new HeatmapOverlay(map3, {"radius":40, "visible":true, "opacity":95});
    
    testData={
        max: 35,
        data: arrayData
    };

    var transformedTestData = { max: testData.max , data: [] },
        data = testData.data,
        datalen = data.length,
        nudata = [];
 
    // in order to use the OpenLayers Heatmap Layer we have to transform our data into 
    while(datalen--){
        nudata.push({
            lonlat: new OpenLayers.LonLat(data[datalen].lng, data[datalen].lat),
            count: data[datalen].count
        });
    }

    transformedTestData.data = nudata;

    var layer = new OpenLayers.Layer.OSM();
    var heatmap = new OpenLayers.Layer.Heatmap( "Heatmap Layer", map, layer, {visible: true, radius:32}, {isBaseLayer: false, opacity: 100, projection: new OpenLayers.Projection("EPSG:4326")});
    
    mapLayers[mapLayers.length] = layer;
    mapLayers[mapLayers.length] = heatmap;

    heatmap.setDataSet(transformedTestData);
}