
<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

//date_default_timezone_set('Europe/London');

/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';
require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

// $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;  /* here i added */
// $cacheEnabled = PHPExcel_Settings::setCacheStorageMethod($cacheMethod);
// if (!$cacheEnabled)
// {
//     echo "### WARNING - Sqlite3 not enabled ###" . PHP_EOL;
// }
$arquivo = $_GET['arquivo']; 
$objPHPExcel = new PHPExcel();
$inputFileType = PHPExcel_IOFactory::identify('uploads/'.$arquivo);

$objReader = PHPExcel_IOFactory::createReader($inputFileType);  

$objReader->setReadDataOnly(true);
//$objReader->setLoadSheetsOnly(1);
/**  Load $inputFileName to a PHPExcel Object  **/  
$objPHPExcel = $objReader->load('uploads/'.$arquivo);

$total_sheets=$objPHPExcel->getSheetCount(); 

$allSheetName=$objPHPExcel->getSheetNames(); 
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0); 
$highestRow = $objWorksheet->getHighestRow(); 
$highestColumn = $objWorksheet->getHighestColumn();  
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

for ($row = 1; $row <= $highestRow;++$row) {  
    for ($col = 0; $col <$highestColumnIndex;++$col) {  
        $value=$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();  
        $arraydata[$row-1][$col]=$value; 
    }  
}
//print_r($arraydata[1][1]);
$size = sizeof($arraydata);

for ($i=0; $i < $size; $i++) {
  $arrayAddress[$i] = $arraydata[$i][1];
}

$arrayAddress = implode(",", $arrayAddress);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Coordenadas dos alunos de CCOMP</title>
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>

    var geocoder;
    var map;
    var cepCsv = "<?php echo $arrayAddress; ?>";
    var arrayCoordenadas = [];
    var cont = 0;

    function init() {
      geocoder = new google.maps.Geocoder();
      pegaCEP();
    }

    function pegaCEP() {
      var ceps = [];
      ceps = cepCsv.split(",");

      $('#address').html('<p>Convertendo...</p>');

      $.each(ceps, function(i){
        converteCEP(ceps[i], true);
      }); 
    }

    function converteCEP(address,ativo){
      geocoder.geocode( { 'address': address+",Brazil"}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          arrayCoordenadas[cont] = results[0].geometry.location;
          cont++;
          $('#address').html('<p>'+arrayCoordenadas+'</p>');
        } else if (status === google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {    
              setTimeout(function() {
                  converteCEP(address, ativo);
              }, 200);
          } else {
          console.log('Erro: ' + status);
        }
      });
    }

    google.maps.event.addDomListener(window, 'load', init);
    
    </script>
  </head>
  <body>
    <div id="panel">
      <div id="address"></div>
      <!-- <input type="button" value="Processar" onclick="pegaCEP()"> -->
    </div>
    <div id="map-canvas"></div>
  </body>
</html>