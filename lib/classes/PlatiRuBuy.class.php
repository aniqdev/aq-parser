<?php

class PlatiRuBuy
{
		
	function __construct()
	{
			# code...
	}

	private static function inv_counter()
	{
		$num = file_get_contents(__DIR__.'/../adds/c.txt');
		file_put_contents(__DIR__.'/../adds/c.txt', ++$num);
		return $num;
	}

	private static function getInvoiceFakeData()
	{
		return json_decode('{"success":"OK","text":"\u0441\u0447\u0435\u0442 \u0432\u044b\u043f\u0438\u0441\u0430\u043d","xml":"&lt;?xml version=&quot;1.0&quot; encoding=&quot;windows-1251&quot;?&gt;&lt;digiseller.response&gt;&lt;retval&gt;0&lt;\/retval&gt;&lt;retdesc&gt;&lt;\/retdesc&gt;&lt;inv&gt;&lt;id&gt;56400198&lt;\/id&gt;&lt;name&gt;&lt;![CDATA[Left 4 Dead 2 (Steam ROW Gift)]]&gt;&lt;\/name&gt;&lt;type_good&gt;1&lt;\/type_good&gt;&lt;wm_id&gt;164322596678&lt;\/wm_id&gt;&lt;link&gt;https:\/\/www.oplata.info\/info\/buy.asp?id_i=56400198&amp;uid=020D09EDAD87490F966DFE50CE3B4776&lt;\/link&gt;&lt;wm_inv&gt;661443515&lt;\/wm_inv&gt;&lt;wm_purse&gt;R781352104789&lt;\/wm_purse&gt;&lt;uid&gt;020D09EDAD87490F966DFE50CE3B4776&lt;\/uid&gt;&lt;\/inv&gt;&lt;\/digiseller.response&gt;","xml1251":"&lt;?xml version=&quot;1.0&quot; encoding=&quot;windows-1251&quot;?&gt;&lt;digiseller.response&gt;&lt;retval&gt;0&lt;\/retval&gt;&lt;retdesc&gt;&lt;\/retdesc&gt;&lt;inv&gt;&lt;id&gt;56400198&lt;\/id&gt;&lt;name&gt;&lt;![CDATA[Left 4 Dead 2 (Steam ROW Gift)]]&gt;&lt;\/name&gt;&lt;type_good&gt;1&lt;\/type_good&gt;&lt;wm_id&gt;164322596678&lt;\/wm_id&gt;&lt;link&gt;https:\/\/www.oplata.info\/info\/buy.asp?id_i=56400198&amp;uid=020D09EDAD87490F966DFE50CE3B4776&lt;\/link&gt;&lt;wm_inv&gt;661443515&lt;\/wm_inv&gt;&lt;wm_purse&gt;R781352104789&lt;\/wm_purse&gt;&lt;uid&gt;020D09EDAD87490F966DFE50CE3B4776&lt;\/uid&gt;&lt;\/inv&gt;&lt;\/digiseller.response&gt;","retval":"0","retdesc":"","inv":{"id":"56400198","name":{},"type_good":"1","wm_id":"164322596678","link":"https:\/\/www.oplata.info\/info\/buy.asp?id_i=56400198&uid=020D09EDAD87490F966DFE50CE3B4776","wm_inv":"661443515","wm_purse":"R781352104789","uid":"020D09EDAD87490F966DFE50CE3B4776"}}', true);
	}

	public static function getInvoice($itemid, $currency = 'WMZ')
	{
		if(defined('DEV_MODE')) return self::getInvoiceFakeData();

		$currency = $_GET['PlatiRuBuy_currency'] = what_currency($itemid);

		$endpoint = 'https://shop.digiseller.ru/xml/create_invoice.asp';
	// 568398645946
	// 103239093088
    // 164322596678
		$xml = '<digiseller.request>
					<id_good>'.$itemid.'</id_good>
					<wm_id>'.BA_WMID.'</wm_id>
					<email>'.BA_MAIL.'</email>
					<id_partner>'.BA_PARTNER_ID.'</id_partner>
					<curr>'.$currency.'</curr>
					<lang>ru-RU</lang>
				</digiseller.request>';


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
		$responseObj = simplexml_load_string( str_replace(['&','windows-1251'], ['&amp;','utf-8'], $responseXML) );

		if($responseXML === false){
			return [
				'success'=>false,
				'text'=>'Ошибка при парсинге XML',
				'xml'=>htmlentities($responseXML),
			];
		}

		if ((string)$responseObj->retval !== '0') {
			return [
				'success'=>false,
				'text'=>'Ошибка при выписке счета',
				'xml'=>htmlentities($responseXML),
				'retval'=>(string)$responseObj->retval,
				'retdesc'=>(string)$responseObj->retdesc,
				// 'retdesc'=>(string)$responseObj->retdesc,
				];
		}

		return [
			'success'=>'OK',
			'text'=>'счет выписан',
			'xml'=>htmlentities($responseXML),
			'retval'=>(string)$responseObj->retval,
			'retdesc'=>(string)$responseObj->retdesc,
			'inv'=>(array)$responseObj->inv,
			];

	}


	private static function payInvoiceFakeData()
	{
		return json_decode('{"success":"OK","transaction_id":1403636339,"response":{}}', true);
	}

	/*
	21 - счет, по которому совершается оплата не найден
	103 - транзакция с таким значением поля w3s.request/trans/tranid уже выполнялась
	110 - нет доступа к интерфейсу
	*/
	public static function payInvoice($invid, $payeePurse)
	{
	  if(defined('DEV_MODE')) return self::payInvoiceFakeData();

	  $payerPurse = PlatiRuBuy_Purses()[$_GET['PlatiRuBuy_currency']];
	  if(!$payerPurse) $payerPurse = BA_PAYER_PURSE;

	  if(!$invid || !$payeePurse) return ['success' => false, 'text' => 'wrong parameters'];

	  $request = new baibaratsky\WebMoney\Api\X\X2\Request;

	  $sign = new baibaratsky\WebMoney\Signer(BA_SIGNER_WMID, __DIR__.'/../adds/kwms/'.BA_SIGNER_WMID.'.kwm', KWM46_PASSWORD);

	  //   <option value="R046889215238">R046889215238 (66.00 - Рубли)</option>
	  //   <option value="R337227083600">R337227083600&nbsp;&nbsp;(730.05 - place4game/Расходы)</option>
	  $webMoney = new baibaratsky\WebMoney\WebMoney(new baibaratsky\WebMoney\Request\Requester\CurlRequester);

	  $request->setSignerWmid(BA_SIGNER_WMID);
	  // Unique ID of the transaction in your system
	  $request->setTransactionExternalId(self::inv_counter());
	  $request->setPayerPurse($payerPurse);
	  $request->setPayeePurse($payeePurse);
	  $request->setAmount(0.01); // Payment amount
	  $request->setDescription('api payment ' . time());
	  $request->setInvoiceId($invid);

	  $request->sign($sign);
	  $ret = [];
	  if ($request->validate()) {

	      $response = $webMoney->request($request);

	      $cod = $response->getReturnCode();
	      if ($cod === 0) {
	      	$ret['success'] = 'OK';
	      	$ret['transaction_id'] = $response->getTransactionId();
	      } else {
	      	$ret['success'] = false;
	      	$ret['code'] = $cod;
	      	$ret['text'] = $response->getReturnDescription();
	      }
	      $ret['response'] = $response;
	  } else {
	    $ret['success'] = false;
	    $ret['text'] = getErrors();
	  }
	  return $ret;
	}


	public static function received_item_fake_data()
	{
		return json_decode('{"success":"OK","typegood":"1","result":"Syberia II I7TMZ-MGH54-HA4HY"}', true);
	}


	public static function isItemOnPlati($item_id)
	{	
		return isItemOnPlati($item_id);

		// Это не работет
		$dom = file_get_html('https://plati.market/itm/'.$item_id);
		$is_class_there = $dom->find('.goods_order_form_quanuty');
		return(!!$is_class_there);
	}

}