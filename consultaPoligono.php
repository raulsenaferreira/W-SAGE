<?php
	include 'conexao.php';
	
	$params = "";
	// Montagem da query e envio dos dados
	if(isset($_POST['submitted'])) {

		$fem = trim($_POST['fem']);
		$mas = trim($_POST['mas']);
		$ativo = trim($_POST['ativo']);
		$inativo = trim($_POST['inativo']);
		$formado = trim($_POST['formado']);
		$cra_aluno = trim($_POST['cra_aluno']);
		$cod_curso = trim($_POST['cod_curso']);
		$periodo_cronologico = trim($_POST['periodo_cronologico']);
		$naturalidade = trim($_POST['naturalidade']);
		$poligono = trim($_POST['poligono']);

		if($mas != "" && $fem != "") {
			$params = " OR sexo = '".$mas."' OR sexo = '".$fem."'";
		}
		if($fem != "") {
			$params = " AND sexo = '".$fem."'";
		}
		if($mas != "") {
			$params = " AND sexo = '".$mas."'";
		}
		if($ativo != "" && $inativo != "" && $formado != "") {
			$params .= " OR situacao = '".$ativo."' OR situacao = '".$inativo."' OR situacao = '".$formado."'";
		}
		else if($ativo != "" && $formado != ""){
			$params .= " OR situacao = '".$ativo."' OR situacao = '".$formado."'";
		}
		else if($inativo != "" && $formado != ""){
			$params .= " OR situacao = '".$inativo."' OR situacao = '".$formado."'";
		}
		else if($ativo != "") {
			$params .= " AND situacao = '".$ativo."'";
		}
		else if($inativo != "") {
			$params .= " AND situacao != '".$inativo."'";
		}
		else if($formado != "") {
			$params .= " AND situacao = '".$formado."'";
		}
		if($cra_aluno != "") {
			$params .= " AND cra >= '".$cra_aluno."'";
		}
		if($cod_curso != "") {
			$params .= " AND cod_curso = '".$cod_curso."'";
		}
		if($periodo_cronologico != "") {
			$params .= " AND periodo_cronologico = '".$periodo_cronologico."'";
		}
		if($naturalidade != "") {
			$params .= " AND naturalidade ilike '".$naturalidade."'";
		}
		// envia restrição do polígono caso este tenha sido desenhado no mapa
		if(!empty($poligono)) {
			$query = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE ST_Intersects(geom,ST_Transform(ST_GeomFromText('".$poligono."',3857),4326))".$params.";";
		} 
		else {
			$query = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE latitude != 0".$params.";";
		}

		$result = pg_query($query);
		$JSON = json_encode(pg_fetch_all($result));
		
		pg_free_result($result);
		// seta variável de envio como TRUE
		$sent = true;
		print_r($JSON);
	}
?>
