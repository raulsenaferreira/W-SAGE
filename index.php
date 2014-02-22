<!DOCTYPE html>
<html dir="ltr" lang="pt-BR">
	<head>
		<title>TCC (teste) - Raul Sena Ferreira</title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	     
		<link href="css/default.css" rel="stylesheet" type="text/css" />
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" src="scripts/jquery-2.0.3.js"></script>
	    <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false" style=""></script>
		<script type="text/javascript" src="scripts/OpenLayers.js"></script>
		<script type="text/javascript" src="scripts/wsage.js"></script>
		<script type="text/javascript" src="scripts/bootstrap.min.js"></script>

		<script type="text/javascript" src="scripts/heatmap.js"></script>
		<script type="text/javascript" src="scripts/heatmap-gmaps.js"></script>
		<script type="text/javascript" src="scripts/heatmap-openlayers.js"></script>
	</head>

	<body onload="init()">
		<!-- mapa de desenho -->
		<div id="map"></div>
		<!-- botões de busca -->
		<input type="button" value="Buscar" id="enviar" onclick="enviaDados();">
		<input type="button" value="Nova Busca" id="reset" onclick="novaBusca();">
		<!-- guarda informaçoes dos pontos -->
		<input id="pontos">
		<!-- mapa de pontos -->
		<div id="map2"></div>
		<!-- envio de dados para o banco -->
		<form id="consultarPoligono" method="post" name="consultarPoligono"  action="">
			<input id="poligono" type="hidden" name="poligono">
			<input type="hidden" name="submitted" id="submitted" value="true" />
		</form>	
		<!-- Enviando arquivo xls, xlsx ou csv para exibição no mapa público -->
		<div id="publico">
			<img id="loading" src="loading.gif" style="display:none;">
			<form method="post" action="doajaxfileupload.php" enctype="multipart/form-data">
				<label>Arquivo</label>
				<input type="file" name="arquivo" />
				<input type="submit" value="Enviar" />
			</form>
		</div>
	</body>
</html>