<?php
$delimitador = ',';
$cerca = '"';

// Abrir arquivo para leitura
$f = fopen('QUESTIONARIO_ENEM_2012.csv', 'r');
if ($f) {
 
    // Lê cabeçalho do arquivo e transforma em tabelas
    $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
    $size = count($cabecalho);
    $headerSQL = implode(",", $cabecalho);
    
    //echo $headerSQL;
    // Enquanto nao terminar o arquivo
    while (!feof($f)) {
 
        // Ler uma linha do arquivo
        $linha = fgetcsv($f, 0, $delimitador, $cerca);
        if (!$linha) {
            continue;
        }
 
        // Montar registro com valores indexados pelo cabecalho
        $registro = array_combine($cabecalho, $linha);
 
        // Obtendo o nome
        //echo $registro['NU_INSCRICAO'].PHP_EOL;
        //var_dump($registro) ;
    }
    fclose($f);
}