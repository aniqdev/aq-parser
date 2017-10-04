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

		public function getInvoice($itemid)
		{
			$endpoint = 'https://shop.digiseller.ru/xml/create_invoice.asp';
		// 568398645946
		// 103239093088
        // 164322596678
			$xml = "<digiseller.request>
						<id_good>$itemid</id_good>
						<wm_id>164322596678</wm_id>
						<email>germanez2000@rambler.ru</email>
						<id_partner>163508</id_partner>
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

/*
21 - счет, по которому совершается оплата не найден
103 - транзакция с таким значением поля w3s.request/trans/tranid уже выполнялась
110 - нет доступа к интерфейсу
*/
		public function payInvoice($invid,$payeePurse)
		{

		  $request = new baibaratsky\WebMoney\Api\X\X2\Request;

		  $sign = new baibaratsky\WebMoney\Signer('568398645946', __DIR__.'/../adds/kwms/568398645946.kwm', KWM46_PASSWORD);

		  //   <option value="R046889215238">R046889215238 (66.00 - Рубли)</option>
		  //   <option value="R337227083600">R337227083600&nbsp;&nbsp;(730.05 - place4game/Расходы)</option>
		  $webMoney = new baibaratsky\WebMoney\WebMoney(new baibaratsky\WebMoney\Request\Requester\CurlRequester);

		  $request->setSignerWmid('568398645946');
		  // Unique ID of the transaction in your system
		  $request->setTransactionExternalId(self::inv_counter());
		  $request->setPayerPurse('R337227083600');
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


	function isItemOnPlati($item_id)
	{	
		return isItemOnPlati($item_id);

		// Это не работет
		$dom = file_get_html('https://plati.market/itm/'.$item_id);
		$is_class_there = $dom->find('.goods_order_form_quanuty');
		return(!!$is_class_there);
	}

}