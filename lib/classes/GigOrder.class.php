<?php

/**
* 
*/
class GigOrder
{
	
	var $order_info = [];
	var $good_info = [];
	var $plati_info = [];
	var $ebay_info = [];
	var $errors = [];
	var $msg_subject = '';
	var $msg_email = '';
	var $msg_ebay = '';
	var $product = '';
	var $frame_link = '';
	var $curr_price = '';

	function __construct($opts=[])
	{
		if (isset($opts['gig_order_id'])) {

			$gig_order_id = (int)$opts['gig_order_id'];
			$gig_order_item_id = (int)$opts['gig_order_item_id'];
			$this->setOrderInfo($gig_order_id);
			$this->setGoodInfo($gig_order_item_id);
			$this->setPlatiInfo($this->good_info['ebay_id']);
			$this->setEbayInfo($this->good_info['ebay_id']);
		}
	}


	public function __toString()
	{	
		return json_encode($this);
	}


	public function setOrderInfo($gig_order_id)
	{
		$order_info = arrayDB("SELECT * FROM ebay_orders WHERE id = '$gig_order_id'");
		if(!$order_info) return false;
		$order_info = $order_info[0];
		$this->order_info = $order_info;
		$this->order_info['goods'] = json_decode($order_info['goods'], true);
		$this->order_info['ShippingAddress'] = json_decode($order_info['ShippingAddress'], true);
		if (!filter_var($this->order_info['BuyerEmail'], FILTER_VALIDATE_EMAIL)) {
			$this->errors[] = 'incorrect email address!';
		}
	}


	public function setGoodInfo($gig_order_item_id)
	{
		$item_info = arrayDB("SELECT * FROM ebay_order_items WHERE id = '$gig_order_item_id'");
		if(!$item_info) return false;
		$item_info = $item_info[0];
		$this->good_info = $item_info;
	}


	public function setPlatiInfo($ebay_id)
	{

		$query = "SELECT * FROM items 
					WHERE game_id = (select id from games where ebay_id = '$ebay_id' LIMIT 1)
					ORDER BY id DESC
					LIMIT 1";
		$query = "SELECT items.*,games.name from items
					JOIN games
					ON games.id = items.game_id
					WHERE ebay_id ='$ebay_id'
					ORDER BY items.id DESC
					LIMIT 1";

		$res = arrayDB($query);
		if($res) $res = $res[0];
		$exrate = arrayDB("SELECT * FROM aq_settings WHERE name='exrate'");
		if($exrate) $exrate = (float)$exrate[0]['value'];
		$res['item1_recom'] = formula($res['item1_price'], $exrate);
		$res['item2_recom'] = formula($res['item2_price'], $exrate);
		$res['item3_recom'] = formula($res['item3_price'], $exrate);
		$this->plati_info = $res;
	}


	public function setEbayInfo($ebay_id)
	{
		$query = "SELECT * FROM ebay_results 
					WHERE game_id = (select id from games where ebay_id = '$ebay_id' LIMIT 1)
					ORDER BY id DESC
					LIMIT 1";
		$res = arrayDB($query);
		if($res) $res = $res[0];
		$this->ebay_info = $res;
	}


	public function buy($plati_id)
	{			
		$platiObj = new PlatiRuBuy();

		$plati_id = (int)$plati_id;
		if (!$plati_id) {
			$this->errors[] = 'Thomething wrong with plati_id: ' . $plati_id;
			return false;
		}

		$this->country = $this->order_info['ShippingAddress']['Country'];
		$this->msg_email = html_entity_decode(get_messages_for_send_producr($country, 'mail'));
		$this->msg_ebay = html_entity_decode(get_messages_for_send_producr($country, 'ebay'));

		if (!$this->msg_email || stripos($this->msg_email, '{{PRODUCT}}') === false) {
			$this->errors[] = 'Please check out the original message!';
			return false;
		}
		$item_title = cut_steam_from_title($this->good_info['title']);
		$this->msg_subject = "Activation data for: $item_title";

		if(defined('DEV_MODE')) return false;

	// 9. Получение счета от Плати.ру. Логировать итог.
	// Входные данные: $plati_id
		$inv_res = $platiObj->getInvoice($plati_id);
		$invoice_resp_json = _esc(json_encode($inv_res));
		// тут нужно логировать результат
		if(!$inv_res['success']){
			$this->errors[] = 'Error during geting plti.ru invoice. '.$inv_res['retdesc'];
			return false;
		}

	// 10. Оплата счета. Логировать итог.
	// Входные данные: $inv_res
		$pay_resp = $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);
		$pay_resp['response'] = (array)$pay_resp['response'];
		$pay_resp_json = _esc(json_encode($pay_resp));
		// тут нужно логировать результат
		if (!$pay_resp['success']) {
			$this->errors[] = 'Error during plti.ru invoice payment';
			return false;
		}
		$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i='.$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid'];

	// 11. Получить товар товар. Логировать итог.
	// Входные данные:
		$received_item = get_item_xml($receive_item_link);
		if (!$received_item['success']) {
			$this->errors[] = 'Can not get the product on the link: '.$receive_item_link;
			return false;
		}
		$product = $received_item['result'];
		$product = get_steam_key_from_text($product);
		$product = get_urls_from_text($product);
		$this->product = $product;

		$msg_email = key_link_replacer($msg_email);

		$this->msg_email = str_ireplace('{{PRODUCT}}', $product, $this->msg_email);
		$this->msg_ebay = str_ireplace('{{EMAIL}}', $this->order_info['BuyerEmail'], $this->msg_ebay);

		unset($inv_res['xml']);
		unset($inv_res['xml1251']);

		$parser_order_id      = isset($this->order_info['id'])     ? _esc($this->order_info['id']) : '0';
		$ebay_game_id         = isset($this->good_info['ebay_id']) ? _esc($this->good_info['ebay_id']) : '0';
		$platiru_invoice_json = isset($inv_res)                    ? _esc(json_encode($inv_res)) : '0';
		$web_pay_json         = isset($pay_resp)                   ? _esc(json_encode($pay_resp)) : '0';
		$product_frame_link   = isset($inv_res['inv']['link'])     ? _esc($inv_res['inv']['link']) : null;
		$product_api_link     = isset($receive_item_link)          ? _esc($receive_item_link) : null;

		$ebay_order_id      = isset($this->order_info['order_id']) ? _esc($this->order_info['order_id']) : 0;
		$parser_game_id     = isset($this->plati_info['game_id'])  ? _esc($this->plati_info['game_id']) : 0;

		$this->frame_link = $product_frame_link;

		arrayDB("INSERT INTO ebay_invoices (ExecutionMethod,
										  ebay_order_id,
										  parser_order_id, 
										  ebay_game_id, 
										  parser_game_id,
										  platiru_invoice_json, 
										  web_pay_json, 
										  product_frame_link, 
										  product_api_link) 
								   VALUES('manually',
										  '$ebay_order_id',
										  '$parser_order_id', 
										  '$ebay_game_id',
										  '$parser_game_id',
										  '$platiru_invoice_json', 
										  '$web_pay_json', 
										  '$product_frame_link', 
										  '$product_api_link')");

		arrayDB("UPDATE ebay_orders SET ExecutionMethod='manually' WHERE id='$parser_order_id'");

		if ($received_item['success'] === 'OK' && $received_item['typegood'] === '1'){
			$platiid = _esc($plati_id);
			$trustee_check = arrayDB("SELECT id FROM gig_trustee_items WHERE plati_id='$platiid'");
			if (!$trustee_check) arrayDB("INSERT INTO gig_trustee_items (plati_id) VALUES ('$platiid')");
			else arrayDB("UPDATE `gig_trustee_items` SET `counter` = `counter` + 1 WHERE `plati_id` = '$platiid'");
		}

		return true;
	}


	public function send($value)
	{
		# code...
	}

	public function current_price_only()
	{
		$Ebay = new Ebay_shopping2();
		$ibey_item = $Ebay->getSingleItem($this->order_info['order_id']);
		$itemArr = json_decode($ibey_item, true);
		if($itemArr['Ack'] === 'Success'){
			return $itemArr['Item']['ConvertedCurrentPrice']['Value'];
		}else{
			return 'Not exists!';
		}
	}

	public function setCurrentPrice()
	{
		$Ebay = new Ebay_shopping2();
		$ibey_item = $Ebay->getSingleItem($this->good_info['ebay_id']);
		$itemArr = json_decode($ibey_item, true);
		if($itemArr['Ack'] === 'Success'){
			$this->curr_price = $itemArr['Item']['ConvertedCurrentPrice']['Value'];
		}else{
			$this->curr_price = 'Not exists!';
		}
	}


}








?>