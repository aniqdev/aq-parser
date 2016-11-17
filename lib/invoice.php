<?php
ini_get('safe_mode') or set_time_limit(100); // Указываем скрипту, чтобы не обрывал связь.


function do_payment($itemid){

  $platiObj = new PlatiRuBuy();
  $inv_res = $platiObj->getInvoice($itemid);

  if($inv_res['success']){

  // payInvoice возвращает массив
  $pay_resp = $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);

  $receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i='.$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid'];

//==============================================================
// item sender
$product = '';
if($pay_resp['success']) $received_item = get_item_xml($receive_item_link);
if($pay_resp['success'] === 'OK' && $received_item['success'] === 'OK'){

  $product = $received_item['result'];
  echo "<pre>";
  print_r($product);
  echo "</pre>";

  if (isset($_POST['tch-order-orderid']) && isset($_POST['tch-order-itemid']) && $_POST['tch-order-orderid'] && $_POST['tch-order-itemid']) {
    sugest_send_product($product);
  }else{
    echo 'Отсутствуют данные о заказе. Видимо товар куплен напрямую';
  }

}elseif($pay_resp['success'] === 'OK'){

  echo "<pre>";
  print_r($received_item);
  echo "</pre>";

}else{
  echo "<pre>Платежь не прошел. Возможно закончились деньги</pre>";
}
//==============================================================

    echo '<iframe class="invoice-iframe" src="',$inv_res['inv']['link'],'&oper=checkpay">
        Ваш браузер не поддерживает плавающие фреймы!
     </iframe>';
  }
    echo "<hr><pre>";
    print_r($inv_res);
    echo "</pre>";

  if($inv_res['success']){
    echo "<hr><pre>";
    print_r($pay_resp);
    echo "</pre>";
  }

    unset($inv_res['xml']);
    unset($inv_res['xml1251']);
    // echo "<hr><pre>";
    // print_r($inv_res);
    // print_r($pay_resp);
    // echo "</pre>";

  $parser_order_id      = isset($_POST['tch-order-orderid']) ? _esc($_POST['tch-order-orderid']) : '0';
  $ebay_game_id         = isset($_POST['tch-order-itemid'])  ? _esc($_POST['tch-order-itemid']) : '0';
  $platiru_invoice_json = isset($inv_res)                    ? _esc(json_encode($inv_res)) : '0';
  $web_pay_json         = isset($pay_resp)                   ? _esc(json_encode($pay_resp)) : '0';
  $product_frame_link   = isset($inv_res['inv']['link'])     ? _esc($inv_res['inv']['link']) : null;
  $product_api_link     = isset($receive_item_link)          ? _esc($receive_item_link) : null;

  arrayDB("INSERT INTO ebay_invoices (parser_order_id, 
                                      ebay_game_id, 
                                      platiru_invoice_json, 
                                      web_pay_json, 
                                      product_frame_link, 
                                      product_api_link) 
                               VALUES('$parser_order_id', 
                                      '$ebay_game_id', 
                                      '$platiru_invoice_json', 
                                      '$web_pay_json', 
                                      '$product_frame_link', 
                                      '$product_api_link')");


}


function do_invoice(){

  if (!isset($_GET['platiid']) ||
    strlen($_GET['platiid']) < 3 ||
    (int)$_GET['platiid'] === 0)
      return 'Что-то не так с id товара';

  if(!isset($_POST['csrf-buy-time']) ||
    isset($_SESSION[md5($_POST['csrf-buy-time'])]) ||
    $_SESSION['csrf-buy-token'] !== $_POST['csrf-buy-token'])
      return '<h3>Данная покупка уже оплачена! Посмотрите историю покупок<h3>
              <h4>Если нет, нажмите повторно кнопку "Buy"';

  $platiid = $_GET['platiid'];
  do_payment($platiid);
  $_SESSION[md5($_POST['csrf-buy-time'])] = 'done';
  return '';
}

echo do_invoice();

// $platiObj->payInvoice('639060131','R781352104789');
