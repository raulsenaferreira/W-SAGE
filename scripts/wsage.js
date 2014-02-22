$(function(){
    var map, map3, coordenadas = "";
    var vetorCoordenadas;
    var heatmap, testData;
    var lol, geocoder;
});

// Carrega o 1º mapa
function init() {
    geocoder = new google.maps.Geocoder();
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

function heatMap(coordenadas){
    var arrayData = [];

    $.each(coordenadas , function(i){
        arrayData[i] = {count: 1, lat: coordenadas[i].st_y,  lng: coordenadas[i].st_x, count: 1};
    });
    
    
    //map3 = new google.maps.Map(document.getElementById("heatmapArea"), myOptions);
    
    //heatmap = new HeatmapOverlay(map3, {"radius":40, "visible":true, "opacity":95});
    
    testData={
        max: 46,
        data: arrayData
    };

    return testData;
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
    lol = heatMap(coordenadas);
    }
    
    //
    var transformedTestData = { max: lol.max , data: [] },
        data = lol.data,
        datalen = data.length,
        nudata = [];
 
    // in order to use the OpenLayers Heatmap Layer we have to transform our data into 
    // { max: , data: [{lonlat: , count: },...]}
    while(datalen--){
        nudata.push({
            lonlat: new OpenLayers.LonLat(data[datalen].lng, data[datalen].lat),
            count: data[datalen].count
        });
    }
    transformedTestData.data = nudata;
    var layer = new OpenLayers.Layer.OSM();
    // create our heatmap layer
    var heatmap = new OpenLayers.Layer.Heatmap( "Heatmap Layer", map2, layer, {visible: true, radius:40}, {isBaseLayer: false, opacity: 0.95, projection: new OpenLayers.Projection("EPSG:4326")});
    //map.addLayers([layer, heatmap]);

    
    
    //

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
            heatmap, 
            poi,
            layer
            
        ]);

    map2.zoomTo(4);
   //console.log("para debug: "+polygonLayer.features[0].geometry.getBounds()); 
   heatmap.setDataSet(transformedTestData);
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

function converteCEP(address,ativo){
  geocoder.geocode( { 'address': address+",Brazil"}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      //map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location,
          title: "Coordenadas: "+results[0].geometry.location
      });

      if (ativo == true){
        iconFile = 'green-dot.png'; 
        marker.setIcon(iconFile)
      }
      else{
        iconFile = 'red-dot.png'; 
        marker.setIcon(iconFile)
      }

    } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
          setTimeout(function() {
              converteCEP(address, ativo);
          }, 200);
      } else {
      console.log('Erro: ' + status);
    }
  });
}

function pegaCEP() {
  var ceps = [];
  var cepCsv = "39480000,02945070,25965690,25645045,25230480,21833140,26013440,22710266,20770061,26032190,21241310,26140330,22020020,22780680,20730030,20720000,21931440,23017000,25036040,24461190,23890000,24412000,25080340,22713550,20950312,21854210,26280340,24070180,26042120,26291042,21380310,26061060,21870210,26032220,23078070,25530100,23090031,25925000,25535450,26440571,20931005,26070428";
  ceps = cepCsv.split(",");
  
  $.each(ceps, function(i){
    converteCEP(ceps[i], true);
  }); 
  
  var cepCsv = "21235480,21020290,23059510,20241220,22290290,26070272,21630130,26225551,21540070,20715004,26116490,20771470,26230150,26255180,25051300,21910080,21040016,20511140,26280485,26210310,21635270,26085155,26276400,26022670,25212240,26551040,21645002,26070787,22725549,26276080,26263150,21512040,26031180,23895320,26285550,26215180,26281265,26053720,26276140,26030025,23936180,22735080,26021650,27175000,26285300,26030045,26035050,21380140,26010370,21370540,26298366,26070464,26320425,26460310,26021110,21511275,28396000,26031180,21515650,26070545,26510361,21765070,26170230,25575613,25915000,25995570,21620430,26086215,26553130,25550161,20950010,26040760,23092580,26262480,21221280,26010110,21910080,26086215,21830120,26012600,20785080,24754190,26032730,23093145,22790495,21645420,26600000,26545000,26010391,26030420,26010371,23073445,22763205,26291605,26556000,26285630,20970350,26183700,21710231,26285710,21021020,23010245,26011352,21620241,21341331,26050720,26020060,26060730,26225360,25510330,25085009,25561090,20950071,25510410,21620590,21520610,25975415,26032840,21230480,23040550,26022810,25560380,36280000,25515520,20780280,23059835,26584180,26255158,26582340,23045580,23934530,26282140,24452125,25550770,26130050,38408258";
  ceps = cepCsv.split(",");
  
  $.each(ceps, function(i){
    converteCEP(ceps[i], false);
  });   
}

function ajaxFileUpload() {
    $("#loading")
    .ajaxStart(function(){
        $(this).show();
    })
    .ajaxComplete(function(){
        $(this).hide();
    });

    $.ajaxFileUpload
    (
        {
            url:'doajaxfileupload.php',
            secureuri:false,
            fileElementId:'fileToUpload',
            dataType: 'json',
            data:{name:'logan', id:'id'},
            success: function (data, status)
            {
                if(typeof(data.error) != 'undefined')
                {
                    if(data.error != '')
                    {
                        alert(data.error);
                    }else
                    {
                        alert(data.msg);
                    }
                }
            },
            error: function (data, status, e)
            {
                alert(e);
            }
        }
    )
    
    return false;

}