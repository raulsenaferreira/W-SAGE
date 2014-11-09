<?php
	include 'conexao.php';
	
	$params = "";
	// Montagem da query e envio dos dados
	if(isset($_POST['submitted']) ) {
		$tipoProcessamento = trim($_POST['tipoProcessamento']);
		$campus = trim($_POST['campus']);
		$sexo = trim($_POST['sexo']);
		$situacao = trim($_POST['situacao']);
		$cra_aluno = trim($_POST['cra_aluno']);
		$cod_curso = trim($_POST['cod_curso']);
		$periodo_cronologico = trim($_POST['periodo_cronologico']);
		$naturalidade = trim($_POST['naturalidade']);
		$poligono = trim($_POST['poligono']);

		if($cod_curso != "") {
			$params .= " AND cod_curso = '".$cod_curso."'";
		}
		if($sexo != "") {
			$params .= " AND sexo = '".$sexo."'";
		}
		if($situacao != "") {
			$params .= " AND situacao = '".$situacao."'";
		}
		if($campus != "") {
			$params .= " AND campus = '".$campus."'";
		}
		if($cra_aluno != "") {
			$params .= " AND cra >= '".$cra_aluno."'";
		}
		if($naturalidade != "") {
			$params .= " AND naturalidade ilike '".$naturalidade."'";
		}
		
		if($tipoProcessamento=='python'){
			// envia restrição do polígono caso este tenha sido desenhado no mapa
			if(!empty($poligono)) {
				$query = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE ST_Intersects(geom,ST_Transform(ST_GeomFromText('".$poligono."',3857),4326))".$params.";";
			} 
			else {
				$query = "SELECT ST_X(geom), ST_Y(geom) from alunos_rural WHERE latitude != 0 ".$params.";";
			}

			exec('python cgi-bin/teste3.py "'.$query.'"' , $dataFromPython);
			
			if(empty($dataFromPython)){
				print_r("python não carregou");
			}
			else{
				print_r(json_encode($dataFromPython));
			}
		}
		else if($tipoProcessamento=='php'){
			// envia restrição do polígono caso este tenha sido desenhado no mapa
			if(!empty($poligono)) {
				$query = "SELECT ST_X(geom), ST_Y(geom), bolsista, nascimento, cra, naturalidade, cod_curso, sexo, forma_ingresso, campus from alunos_rural WHERE ST_Intersects(geom,ST_Transform(ST_GeomFromText('".$poligono."',3857),4326))".$params.";";
			} 
			else {
				$query = "SELECT ST_X(geom), ST_Y(geom), bolsista, nascimento, cra, naturalidade, cod_curso, sexo, forma_ingresso, campus from alunos_rural WHERE latitude != 0".$params.";";
			}

			$result = pg_query($query);
			$JSON = json_encode(pg_fetch_all($result));
			
			pg_free_result($result);
			// seta variável de envio como TRUE
			$sent = true;
			print_r($JSON);
		}
	}
?>
