<?php

use baibaratsky\WebMoney\WebMoney;
use baibaratsky\WebMoney\Signer;
use baibaratsky\WebMoney\Request\Requester\CurlRequester;
use baibaratsky\WebMoney\Api\X\X2;

//header('Content-Type: text/html; charset=utf-8');
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
// 164322596678
  $xml = "<digiseller.request>
            <id_good>$itemid</id_good>
            <wm_id>164322596678</wm_id>
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
  $responseXML = file_get_contents($endpoint, false, $context);


  $responseObj = simplexml_load_string( str_replace('&', '&amp;', $responseXML) );



  echo "<pre>\r\n";
  var_dump( htmlentities( iconv('windows-1251', 'utf-8', $responseXML) ) );
  // var_dump( $responseXML );
  var_dump((string)$responseObj->retval);
  echo "<hr>";
  var_dump($responseObj);
  echo "</pre>";

}

$itemid = '1905477';
//get_invoice($itemid);

function do_payment($itemid){

  $platiObj = new PlatiRuBuy();
  $inv_res = $platiObj->getInvoice($itemid);

    echo "<pre>";
    // var_dump($inv_res['inv']['wm_inv']);
    // echo "<br>";
    // var_dump($inv_res['inv']['wm_purse']);
    // echo "<br>";
    print_r($inv_res);
    echo "</pre>";

  sleep(1);
  if($inv_res['success']){

    echo '<hr><iframe class="invoice-iframe" src="',$inv_res['inv']['link'],'">
        Ваш браузер не поддерживает плавающие фреймы!
     </iframe>';

    $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);

    // echo '<hr><iframe class="invoice-iframe" src="https://shop.digiseller.ru/xml/purchase.asp?id_i=',$inv_res['inv']['id'],'&uid=',$inv_res['inv']['uid'],'">
    //     Ваш браузер не поддерживает плавающие фреймы!
    //  </iframe>';



     // echo '<pre>';
     // echo file_get_contents('https://shop.digiseller.ru/xml/purchase.asp?id_i=',$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid']);
     // echo '</pre>';
  }
}

//var_dump(strlen($_GET['platiid']) > 3);

if (isset($_GET['platiid']) && strlen($_GET['platiid']) > 3 && (int)$_GET['platiid'] !== 0) {
  $platiid = $_GET['platiid'];
  do_payment($platiid);
}else{
  echo '<h3>Что-то пошло не так! Обратитесь к администратору<h3>';
}

     // echo '<pre>';
     // print_r($_SERVER);
     // echo '</pre>';

// $platiObj->payInvoice('639060131','R781352104789');
