<?php

use baibaratsky\WebMoney\WebMoney;
use baibaratsky\WebMoney\Signer;
use baibaratsky\WebMoney\Request\Requester\CurlRequester;
use baibaratsky\WebMoney\Api\X\X2;

header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(100); // Указываем скрипту, чтобы не обрывал связь.



function zaplatit($invid)
{

   $request = new X2\Request;

  $sign = new Signer('568398645946', __DIR__.'/adds/kwms/568398645946.kwm', KWM46_PASSWORD);

    echo "<pre>";
    print_r($sign);
  print_r(get_class_methods($request));
    echo "</pre>";

  //===================================================================================
  // <select name="c_purse" style="width:350px" cnt="1">
  //   <option value="R046889215238">R046889215238 (66.00 - Рубли)</option>
  //   <option value="R337227083600">R337227083600&nbsp;&nbsp;(730.05 - place4game/Расходы)</option>
  // </select>

  $webMoney = new WebMoney(new CurlRequester);

  $request->setSignerWmid('568398645946');
  $request->setTransactionExternalId(12); // Unique ID of the transaction in your system
  $request->setPayerPurse('R337227083600');
  $request->setPayeePurse('R781352104789');
  $request->setAmount(3.33); // Payment amount
  //$request->setDescription('Test payment');
  $request->setInvoiceId($invid);

  $request->sign($sign);

  if ($request->validate()) {
      /** @var X2\Response $response */
      $response = $webMoney->request($request);

    echo "<pre>";
    print_r($response);
    echo "</pre>";
      $cod = $response->getReturnCode();
      if ($cod === 0) {
          echo 'Successful payment, transaction id: ' . $response->getTransactionId();
      } else {
          echo 'Payment error: ' . $response->getReturnDescription();
          echo "<hr>";
          var_dump($cod);
      }
  } else {
    echo "<pre>";
    print_r($request->getErrors());
    echo "</pre>";
  }

}

//zaplatit(0);