<pre>
<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
error_reporting(E_ALL);
ini_set('display_errors', 1);

$ebayObj = new EbayOrders();
$platiObj = new PlatiRuBuy();
$ordersObj = new EbayOrders();
$ebay2Obj = new Ebay_shopping2();
$botObj = new AutomaticBot('-195283152');

// 1. Получение списка подходящих заказов.
// Входные данные: void
$orders = arrayDB( "SELECT *, ebay_order_items.id as gig_order_item_id, ebay_orders.id as gig_order_id
					FROM ebay_orders 
					LEFT JOIN ebay_order_items
					ON ebay_orders.id = ebay_order_items.gig_order_id
					WHERE PaidTime<>0 AND shipped_time=0 AND OrderStatus='Completed' AND `show`='no' AND ExecutionMethod='default' 
					LIMIT 50");
print_r($orders);

$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
if($exrate) $dataex = $exrate[0]['value']; // 67
else{
	AutomaticBot::sendMessage(['text' => date('H:i:s').' Проблема с exrate (no exrate)']);
	die('no exrate');
} 
if($dataex < 40){
	AutomaticBot::sendMessage(['text' => date('H:i:s').' Проблема с exrate (< 40)']);
	die('exrate < 40');
} 



//=========================================================================================
//========== Начало основного цикла обработки =============================================
foreach ($orders as $k => $order):

	$username = $order['BuyerUserID'];
	$goods = json_decode($order['goods'],true);
	$address = json_decode($order['ShippingAddress'], true);
	$ebay_item_id = $order['ebay_id'];
	$ebay_item_title = clean_ebay_title2($order['title']);
	$gig_order_id = $order['gig_order_id'];
	$gig_order_item_id = $order['gig_order_item_id'];
	$ids = $order['gig_order_id'].'-'.$order['gig_order_item_id'];
	$continue = false;
	$chosen_item_id = 0;

// 0. Создаем список товаров ===  foreach CONSTUCTOR
	if ($order['npp'] == 1){
		$title_list = '';
		$product_list = '';
		$gig_order_item_id_list = [];
		$msg_email = html_entity_decode(get_messages_for_send_producr($address['Country'], 'mail'));
		$msg_ebay = html_entity_decode(get_messages_for_send_producr($address['Country'], 'ebay'));
		$email_slug = get_email_slug();
	}


// 1.1 Проверить исходник отправляемого имейла
// Входные данные:
	if (!$msg_email || stripos($msg_email, '{{PRODUCT}}') === false) {
		add_comment_to_order($gig_order_id, 'Please check out the original message: ', 'no');
		continue;
	}


// 1.2 Если отсутствует запись в ebay_order_items.
// Входные данные: ebay_id
	if(!$order['ebay_id']){
		add_comment_to_order($gig_order_id, 'there is no Item info in this order');
		AutomaticBot::sendMessage(['text' => date('H:i:s').' There is no Item info in this order: '.$gig_order_id]);
		$continue = true;
	}


// 2. Получение Рейтинга пользователя и даты регистрации.
// Входные данные: BuyerUserID
	if(!$order['BuyerUserID']){
		add_comment_to_order($gig_order_id, 'there is no BuyerUserID in this order');
		continue;
	}

	$stats = arrayDB("SELECT BuyerFeedbackScore,BayerRegistrationDate FROM ebay_orders WHERE id='$gig_order_id'");
	$BuyerFeedbackScore = $stats[0]['BuyerFeedbackScore'];
	$BayerRegistrationDate = $stats[0]['BayerRegistrationDate'];
	if ($BuyerFeedbackScore == 0 && $BuyerFeedbackScore !== '0') { // true если пустая строка
		$user = $ordersObj->GetUser($username, $ebay_item_id);
		if($user['Ack'] === 'Failure'){
			add_comment_to_order($gig_order_id, 'query to get buyer info returned Failure');
			continue;
		}
		if($user['User']['FeedbackPrivate'] === 'true') $BuyerFeedbackScore = 'priv';
		else $BuyerFeedbackScore = $user['User']['FeedbackScore'];
		$BayerRegistrationDate = $user['User']['RegistrationDate'];
		$d = new DateTime($BayerRegistrationDate);
		$d->add(date_interval_create_from_date_string('1 hour'));
		$BayerRegistrationDate = $d->format('Y-m-d H:i:s');
		arrayDB("UPDATE ebay_orders 
				SET BuyerFeedbackScore='$BuyerFeedbackScore',BayerRegistrationDate='$BayerRegistrationDate' 
				WHERE id='$gig_order_id'");

	}else{
		// var_dump($BuyerFeedbackScore);
		// var_dump('False<br>');
	}


// 2.2 Если стоимость заказа больше 20 евро.
// Входные данные: ebay_id
	if($order['total_price'] > 20){
		add_comment_to_order($gig_order_id, ' Order total price more then €20');
		continue;
	}


// 2.3 В заказе есть более 2 копий одного товара.
// Входные данные: ebay_id
	foreach ($goods as $good) {
		if($good['amount'] > 2){
			add_comment_to_order($gig_order_id, ' Order has product with amount more the 2');
			continue 2;
		}
	}


// 3.0 Исключение исков, плексов.
// Входные данные: $ebay_item_id
	if (is_eve($ebay_item_id)) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'ISK or PLEX');
		$continue = true;
	}


// 3.1 Исключение заказов с более 1 игрой в списке Games
// Входные данные: ebay_item_id
	$suitables = get_suitables2($ebay_item_id);
	$suitable = ['item1_id' => '0']; // костыль
	if (count($suitables) > 1) {
		add_comment_to_order($gig_order_id, 'MORE then one game is suitable from the Games Table');
		AutomaticBot::sendMessage(['text' => date('H:i:s').' MORE then one game is suitable from the Games Table: '.$ebay_item_id]);
		$continue = true;
	}elseif (count($suitables) < 1) {
		add_comment_to_order($gig_order_id, 'there are NO any games suitable from the Games Table');
		AutomaticBot::sendMessage(['text' => date('H:i:s').' there are NO any games suitable from the Games Table: '.$ebay_item_id]);
		$continue = true;
	}else $suitable = $suitables[0];


// 3.2 Проверка, есть ли товар на plati.ru.
// Входные данные: suitable
	$payed_price = (float)$order['price']; // 6.31
	if ($suitable['item1_id']){
		$chosen_item_id = $suitable['item1_id'];
		$chosen_item_price = $suitable['item1_price'];


// 3.3  Проверка, есть ли товар в Траст-листе.
// Входные данные: suitable
		if (!arrayDB("SELECT plati_id FROM gig_trustee_items WHERE plati_id = '"._esc($suitable['item1_id'])."'")) {
			add_comment_to_order([$gig_order_id, $gig_order_item_id], 'the Game is not in the Trust List');
			$continue = true;
		}


// 3.4  Исключение заказов с ценой менее 7% от рекомендуемой. Убирать товар с продажи.
// Входные данные: payed_price, chosen_item_price, dataex
		$border_price = formula($chosen_item_price, $dataex)*0.93;
		if ($payed_price < $border_price) {
			$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
			$rem = $is_removed ? 'has been' : 'was NOT';
			add_comment_to_order([$gig_order_id, $gig_order_item_id], "the price is more then 7% less then recommended. The item $rem removed from sale");
			$continue = true;
		}

	}else{

		$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
		$rem = $is_removed ? 'has been' : 'was NOT';
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Out of stock. The item $rem removed from sale');
		$continue = true;
	}


// 4. Исключение заказов без имейла.
// Входные данные: BuyerEmail
	if (!filter_var($order['BuyerEmail'], FILTER_VALIDATE_EMAIL)) {
		add_comment_to_order($gig_order_id, 'incorrect email address');
		continue;
	}



// 7. Исключение пользователей с низким рейтингом(меньше 5) если цена больше 5 евро.
// Входные данные: $BuyerFeedbackScore
	if ($BuyerFeedbackScore < 5 && $order['total_price'] > 5) {
		add_comment_to_order($gig_order_id, 'Buyers FeedbackScore less then 5 and order price more then 5eur');
		continue;
	}


// 7.2 Исключение новых аккаунтов(меньше месяца) если цена больше 5 евро.
	if ((time() - (new DateTime($BayerRegistrationDate))->getTimestamp()) < 60*60*24*30 && $order['total_price'] > 5) {
		add_comment_to_order($gig_order_id, 'This user account has been created in the previous 30 days');
		continue;
	}


// 8. Исключить заказы если пользователь совершил больше 3х покупок за неделю.
// Входные данные:
	$one_week_ago = date('Y-m-d H:i:s', time()-(60*60*24*7));
	$one_week_orders = arrayDB("SELECT id,ShippedTime  FROM ebay_orders WHERE ShippedTime > '$one_week_ago' AND  BuyerUserID = '$username' LIMIT 1");
	if (count($one_week_orders) > 2) {
		add_comment_to_order($gig_order_id, 'This user has made more than three purchases per week');
		continue;
	}




//================================================================================
//arrayDB("UPDATE ebay_orders SET `show`='yes' WHERE id='$gig_order_id'"); continue;













//================================================================================
	$botObj->sendMessage(['text' => date('H:i:s').' Начата процедура автоматической обработки заказа: '.$gig_order_id]);
	if(!$continue) arrayDB("UPDATE ebay_orders SET `ExecutionMethod`='automatic' WHERE id='$gig_order_id'");
//================================================================================

	arrayDB("INSERT INTO ebay_automatic_log (order_id, order_item_id, ebay_game_id) 
		VALUES ('$gig_order_id', '$gig_order_item_id', '$ebay_item_id')");
	$automatic_id = DB::getInstance()->lastid();

// 9. Получение счета от Плати.ру. Логировать итог.
// Входные данные:
	if (!$continue) {
		$inv_res = $platiObj->getInvoice($chosen_item_id);
		$invoice_resp_json = _esc(json_encode($inv_res));
		arrayDB("UPDATE ebay_automatic_log SET `invoice_resp`='$invoice_resp_json', plati_id='$chosen_item_id' WHERE id='$automatic_id'");
	}else{
		$inv_res = '';
	}


// 10. Оплата счета. Логировать итог.
// Входные данные:
	if(isset($inv_res['success']) && $inv_res['success']){
		$pay_resp = $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);
		$pay_resp['response'] = (array)$pay_resp['response'];
		$pay_resp_json = _esc(json_encode($pay_resp));
		arrayDB("UPDATE ebay_automatic_log SET `pay_resp`='$pay_resp_json' WHERE id='$automatic_id'");
	}else{
		$pay_resp = '';
	}


// 11. Получить товар товар. Логировать итог.
// Входные данные:
	if (isset($pay_resp['success']) && $pay_resp['success']) {
		$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i='.$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid'];
		$received_item = get_item_xml($receive_item_link);
		$product = $received_item['result'];
		arrayDB("UPDATE ebay_automatic_log 
			SET `received_item`='"._esc(json_encode($received_item))."',
			product_api_link='$receive_item_link' WHERE id='$automatic_id'");
	}else{
		$received_item = '';
		$product = '';
	}



// 12. Отправить товар. Логировать итог.
// Входные данные:
	if (isset($received_item['success']) && $received_item['success']) {
		$product = get_steam_key_from_text($product);
		$product = get_urls_from_text($product);
		$title_list .= $ebay_item_title.', ';
		$product_list .= product_html($ebay_item_title, $product);
		$gig_order_item_id_list[] = $gig_order_item_id;
	}

// Отправлять ли товар?
	if ($order['npp'] == $order['total'] && count($gig_order_item_id_list) > 0) {
		
		$msg_email = str_ireplace('{{PRODUCT}}', $product_list, $msg_email);
		$msg_email = str_ireplace('{{EMAIL_SLUG}}', $email_slug, $msg_email);
		$msg_email = str_ireplace('{{USER_EMAIL}}', $order['BuyerEmail'], $msg_email);
		$msg_email = fill_email_item_panel($msg_email);

		$msg_ebay = str_ireplace('{{EMAIL}}', $order['BuyerEmail'], $msg_ebay);


		// DEPRICATED для старого шаблона писем
		// $msg_email = key_link_replacer($msg_email);

		$email_body = _esc($msg_email);
		$email_alt_body = strip_tags($email_body);
		arrayDB("UPDATE ebay_automatic_log 
			SET email_slug='$email_slug', msg_ebay='"._esc($msg_ebay)."' WHERE id='$automatic_id'");

		$ebay_orderid = $order['order_id'];
		$user_email = _esc($order['BuyerEmail']);
		$email_subject = activation_data_for($address['Country']).substr(trim($title_list), 0, -1);

		$ebay_user = $order['BuyerUserID'];
		$ebay_item = $ebay_item_id;
		$ebay_subject = $email_subject;
		$ebay_body = $msg_ebay;

		// создание и отправка письма
		$mail = get_a3_smtp_object();
		$mail->addAddress($order['BuyerEmail']);
		$mail->addBCC('thenav@mail.ru');
		$mail->addBCC('store@gig-games.de');
		$mail->Subject = $email_subject;
		$mail->Body    = $msg_email;
		$mail->AltBody = $email_alt_body;

		$is_email_sent = $mail->send();
		$is_email_sent = $is_email_sent ? 1 : 0;

		$email_subject = _esc($email_subject);
		arrayDB("INSERT INTO gig_email_saver (email,email_slug,subject,body_html) 
			VALUES ('$user_email','$email_slug','$email_subject','$email_body')");

		//
		$userId = htmlspecialchars(stripslashes(strip_tags($ebay_user)));
		$itemId = htmlspecialchars(stripslashes($ebay_item));
		$subject = htmlspecialchars(stripslashes(substr($ebay_subject, 0, 100)));
		$body = htmlspecialchars(stripslashes(strip_tags($ebay_body)));

		$ebay_send_result = $ebayObj->SendMessage($userId, $itemId, $subject, $body);

		arrayDB("UPDATE ebay_automatic_log 
			SET email_send_resp='"._esc($is_email_sent)."', 
			ebay_send_resp='"._esc(json_encode($ebay_send_result))."' WHERE id='$automatic_id'");
	}else{
		arrayDB("UPDATE ebay_automatic_log 
			SET msg_email='"._esc('('.$order['npp'].'/'.$order['total'].')')."', 
			msg_ebay='"._esc('Multiple order')."' WHERE id='$automatic_id'");
		// arrayDB("UPDATE ebay_automatic_log 
		// 	SET email_send_resp='"._esc('Multiple order')."', 
		// 	ebay_send_resp='"._esc(json_encode('Multiple order'))."' WHERE id='$automatic_id'");		
	}

//------------------------------------------------------------

	if(isset($inv_res['success']) && !$inv_res['success']){
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Error during geting plti.ru invoice. '.$inv_res['retdesc']);
		AutomaticBot::sendMessage(['text' => date('H:i:s').'('.$gig_order_id.') Error during geting plti.ru invoice. '.$inv_res['retdesc']]);
		$ebay2Obj->removeFromSale($ebay_item_id);
		continue;
	}

	if (isset($pay_resp['success']) && !$pay_resp['success']) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Error during plti.ru invoice payment');
		AutomaticBot::sendMessage(['text' => date('H:i:s').'Error during plti.ru invoice payment: '.$gig_order_id]);
		continue;
	}

	if (isset($received_item['success']) && !$received_item['success']) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Can not get the product on the link: '.$receive_item_link);
		AutomaticBot::sendMessage(['text' => date('H:i:s').'Can not get the product on the link: '.$receive_item_link]);
		continue;
	}

// 13. Проверить наличие товара на Плати.ру.
// Входные данные:
	if (!$continue) {
		if ($chosen_item_id && $platiObj->isItemOnPlati($chosen_item_id)) {

			AutomaticBot::sendMessage(['text' => 'if 1: ' . $chosen_item_id]);
			$ebay2Obj->updateQuantity($ebay_item_id, 3);

		}else{

			AutomaticBot::sendMessage(['text' => 'else 1: ' . $chosen_item_id]);
			if ($suitable['item2_id'] && $platiObj->isItemOnPlati($suitable['item2_id'])) {
				$new_price = formula((float)$suitable['item2_price'], $dataex);
				if ($payed_price < $new_price) {
					AutomaticBot::sendMessage(['text' => 'if 3: ' . $payed_price . ' | ' . $new_price]);
					$ebay2Obj->updateProductPrice($ebay_item_id, $new_price);
				}
			}else{
				$ebay2Obj->removeFromSale($ebay_item_id);
			}
		}
	}

// 14. Пометить товар отправленным
// Входные данные:
	if (@$is_email_sent) {
		$ebay_shipped_resp = $ordersObj->MarkAsShipped($ebay_orderid);
		$ebay_shipped_resp_json = _esc(json_encode($ebay_shipped_resp));
		arrayDB("UPDATE ebay_automatic_log SET ebay_shipped_resp='$ebay_shipped_resp_json' WHERE id='$automatic_id'");
		if ($ebay_shipped_resp['Ack'] == 'Success') {
			arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$ebay_orderid'");
			foreach ($gig_order_item_id_list as $key => $order_item_id) {
				arrayDB("UPDATE ebay_order_items SET shipped_time=CURRENT_TIMESTAMP WHERE id='$order_item_id'");
			}
		}else{
			AutomaticBot::sendMessage(['text' => date('H:i:s').' MarkAsShipped = Failure. order: '.$gig_order_id]);
		}
	}

	$botObj->sendMessage(['text' => date('H:i:s').'. Завершина('.@$is_email_sent.') процедура автоматической обработки заказа: .'.$gig_order_id]);


// 15. Пишем ошибки
// Входные данные:
	arrayDB("UPDATE ebay_automatic_log SET `errors`='"._esc(json_encode($_ERRORS))."' WHERE id='$automatic_id'");


// 16. Пишем детальную информацию о заказе
// Входные данные:
	$product_frame_link   = isset($inv_res['inv']['link'])     ? _esc($inv_res['inv']['link']) : null;
	$product_api_link     = isset($receive_item_link)          ? _esc($receive_item_link) : null;
	arrayDB("INSERT INTO ebay_invoices (ExecutionMethod,
									  parser_order_id, 
									  ebay_game_id, 
									  platiru_invoice_json, 
									  web_pay_json, 
									  product_frame_link, 
									  product_api_link) 
							   VALUES('automatic',
									  '$gig_order_id', 
									  '$ebay_item_id', 
									  '$invoice_resp_json', 
									  '$pay_resp_json', 
									  '$product_frame_link', 
									  '$product_api_link')");

endforeach;






?>
</pre>