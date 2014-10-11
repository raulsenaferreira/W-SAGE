<?php
	include 'conexao.php';
	
	$naturalidade = trim($_POST['naturalidade']);
	$query = "select distinct naturalidade from alunos_rural where  naturalidade is not null;";
	$result = pg_query($query);
	$JSON = json_encode(pg_fetch_all($result));
		
	pg_free_result($result);
		
	print_r($JSON);
	
?>
