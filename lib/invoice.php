<?php

use baibaratsky\WebMoney\WebMoney;
use baibaratsky\WebMoney\Signer;
use baibaratsky\WebMoney\Request\Requester\CurlRequester;
use baibaratsky\WebMoney\Api\X\X2;

header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(100); // Указываем скрипту, чтобы не обрывал связь.
?>

<?php
// require_once 'lib/PHPExcel.php';
// require_once 'lib/array_DB.php';
// require_once 'lib/simple_html_dom.php';



function get_invoice($itemid){

  $endpoint = 'https://shop.digiseller.ru/xml/create_invoice.asp';
// 568398645946
// 103239093088
  $xml = "<digiseller.request>
            <id_good>$itemid</id_good>
            <wm_id>568398645946</wm_id>
            <email>germanez2000@rambler.ru</email>
            <id_parnter>163508</id_parnter>
            <curr>WMR</curr>
            <lang>ru-RU</lang>
          </digiseller.request>";


  $opts = array('http' =>
    array(
      'method'  => 'POST',
      'header'  => "Content-Type: text/xml\r\n",
      'content' => $xml,
      'timeout' => 60
    )
  );
                          
  $context  = stream_context_create($opts);
  $responseXML = file_get_contents($endpoint, false, $context, -1, 40000);


  var_dump($responseXML);
  $responseObj = simplexml_load_string($responseXML);



  echo "<pre>";
  print_r($responseObj);
  echo "</pre>";

}

$itemid = '2174988';
get_invoice($itemid);


 $request = new X2\Request;

  echo "<pre>";
  print_r(new Signer('103239093088', __DIR__.'/adds/kwms/103239093088.kwm', KWM88_PASSWORD));
print_r(get_class_methods($request));
  echo "</pre>";