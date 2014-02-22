<?php
	include 'conexao.php';

	// envio dos dados
	if(isset($_POST['submitted'])) {

		$poligono = trim($_POST['poligono']);

		// envia restrição do polígono caso este tenha sido desenhado no mapa
		if($poligono === '') {
			$query = 'SELECT name, ST_X(wkb_geometry), ST_Y(wkb_geometry), description FROM agencias';
		} 
		else {
			$query = "SELECT DISTINCT name, ST_X(wkb_geometry), ST_Y(wkb_geometry), description FROM agencias WHERE ST_Intersects(wkb_geometry,ST_Transform(ST_GeomFromText('".$poligono."',3857),4326))";
		}

		$result = pg_query($query);
		$JSON = json_encode(pg_fetch_all($result));
		
		pg_free_result($result);
		// seta variável de envio como TRUE
		$sent = true;
		//retorno do AJAX
		print_r($JSON) ;
	}
?>