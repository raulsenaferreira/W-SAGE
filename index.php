<!DOCTYPE html>
<html dir="ltr" lang="pt-BR">
	<head>
		<title>W-SAGE (Web tool for Spatial Analysis of GEodata)</title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	     
		<link href="css/default.css" rel="stylesheet" type="text/css" />
		<link href="css/style.css" rel="stylesheet" type="text/css" />
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
		<link href="css/wsage.css" rel="stylesheet" type="text/css" />
		
	</head>

	<body onload="init()" role="document">
		<div  id="top" class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<img src="imagens/wsage_logo.jpg" alt="Wsage-Logo" id="wsageLogo"> 
				</div>
				<div id="nav">
				     <ul class="nav navbar-nav">
				         <li class="navBarLiClass"><a class="navBarLinkClass" 
				         	 href="#visualization" style="border-left: 1px solid #EEEEEE;">Mapa</a></li>
				         <li class="navBarLiClass"><a class="navBarLinkClass" href="#grafh">Gráfico</a></li>
				         <li class="navBarLiClass"><a class="navBarLinkClass" href="#contact">Contato</a></li>
				     </ul>
				</div>
			</div>			
		</div>
		<div>
		<div id="content" class="bs-docs-header" role="main">
			<div class="container">
				<div id="visualization">
					<a id="visualization"></a>
					<div class="page-header" style="padding-top: 60px;">
						<h1> Mapa </h1>
					</div>
					
					<div id="map"></div>
					<!-- botões de busca -->
					<ul id="mapMenu" class="listaSemMarcador">
						<li class="liButtonMap"> 
							<input type="button" class="btn btn-primary mapMenuButton" value="Buscar" 
							       id="enviar" data-toggle="modal" data-target="#myModal"> 
						</li>
						<li class="liButtonMap" >
							<input type="button" class="btn btn-primary mapMenuButton" value="Nova Busca" 
								   id="reset" onclick="novaBusca();">
						</li>
						<li class="liButtonMap" onclick="activePolygonDraw(0);">
							<div class="btn btn-primary mapMenuButton">
								<input type="image" class="btn btn-primary imageButton" 
								   src="imagens/hand.png" alt="Hand"/>
							</div>
						</li>
						<li class="liButtonMap" onclick="activePolygonDraw(1);">
							<div class="btn btn-primary  mapMenuButton">
								<input type="image" class="imageButton" 
								   src="imagens/polygon.png" alt="Polygon"/>
							</div>
						</li>
					</ul>

					<div style="clear:both"></div>

					<div class="well aviosMap">
				    	<ul class="listaSemMarcador">
				    		<li> Use o botão com a <img src="imagens/hand.png" style="width:15px;height:15px;"> para poder navegar no mapa. </li>
				    		<li> Use o botão com o <img src="imagens/polygon.png" style="width:15px;height:15px;"> para conseguir desenhar um polígono no mapa.</li>
				    		<li> Use o <i> shift</i> ou um duplo clique do mouse, para poder fechar o polígono.</li>
				    	</ul>
					</div>

					<!-- guarda informaçoes dos pontos -->
					<input id="pontos">
					<input id="pdfs">
					<!-- envio de dados para o banco -->
					<form id="consultarPoligono" method="post" name="consultarPoligono"  action="">
						<input id="poligono" type="hidden" name="poligono">
						<input type="hidden" name="submitted" id="submitted" value="true" />
						<div id="filtros">
							<!-- MODAL -->
							<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
							    <div class="modal-content">
							      <div class="modal-header">
							        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							        <h4 class="modal-title">Escolha o filtro de busca</h4>
							      </div>
							      <div class="modal-body">
							        <p>Você deseja buscar por&hellip;</p>
							        <p>Sexo:
								        <input type="checkbox" name="fem" value="F">Feminino
								        <input type="checkbox" name="mas" value="M">Masculino
							        </p>
							        <p>Situação:
								        <input type="checkbox" name="ativo" value="1">Ativo
								        <input type="checkbox" name="inativo" value="1">Inativo
								        <input type="checkbox" name="formado" value="6">Formado
							        </p>
							        <p class="texto">CR Acumulado acima de:
							        	<input type="text" name="cra_aluno">
							        </p>
							        <p class="texto">Código do Curso:
							        	<input type="text" name="cod_curso">
							        </p>
							        <p class="texto">Periodo Cronológico:
							        	<input type="text" name="periodo_cronologico">
							        </p>
							        <p class="texto">Naturalidade:
							        	<input class="naturalidade" type="text" name="naturalidade">
							        </p>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="enviaDados();">Buscar</button>
							      </div>
							    </div><!-- /.modal-content -->
							  </div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							</div>
					</form>						
				</div>

				

				<div id="grafh">
				  <a id="portfolio"></a>
					<div class="page-header">
						<h1> Gráficos </h1>
					</div>

					<div id="chart1" >

					</div>
					<div id="chart2" >

					</div>
				</div>

				<div id="page3">
				  <a id="contact"></a>
				  <div class="page-header">
					<h1> Contato </h1>
				  </div>
				    
				</div>
			</div>	
		</div>
		<script type="text/javascript" src="scripts/jquery-2.0.3.js"></script>
		<script async type="text/javascript" src="scripts/jquery-ui.min.js"></script>
	    <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false" style=""></script>
		<script type="text/javascript" src="scripts/OpenLayers.js"></script>
		<script async type="text/javascript" src="scripts/bootstrap.min.js"></script>
		<script type="text/javascript" src="scripts/heatmap.js"></script>
		<script async type="text/javascript" src="scripts/heatmap-gmaps.js"></script>
		<script type="text/javascript" src="scripts/heatmap-openlayers.js"></script>
		<script async type="text/javascript" src="scripts/wsage.js"></script>
		<!--<script type="text/javascript" src="scripts/d3.min.js"></script>
		<script type="text/javascript" src="scripts/smoothScroll.js"></script>
		<script type="text/javascript" src="scripts/wsage_chart.js"></script>
		<script type="text/javascript" src="scripts/wsage_chart2.js"></script>-->
	</body>
</html>




