<?php

$host = '107.170.124.51';
$port = '5432';
$database = 'tccdb_cloud';
$user = 'postgres';
$password = 'raul$0128$raul';

$connectString = 'host=' . $host . ' port=' . $port . ' dbname=' . $database . 
	' user=' . $user . ' password=' . $password;

$link = pg_connect($connectString);

if(!$link){
	die('Erro de conexão: ' . pg_last_error());
}

?>
