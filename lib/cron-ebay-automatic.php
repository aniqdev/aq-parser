<pre>
<?php
ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
error_reporting(E_ALL);
ini_set('display_errors', 1);

$platiObj = new PlatiRuBuy();
$ordersObj = new EbayOrders();
$ebay2Obj = new Ebay_shopping2();
$botObj = new AutomaticBot('-195283152');

// 1. Получение списка подходящих заказов.
// Входные данные: void
$orders = arrayDB("SELECT * FROM ebay_orders WHERE PaidTime<>0 AND ShippedTime=0 AND OrderStatus='Completed' AND `show`='no' AND ExecutionMethod='default' LIMIT 20");
print_r($orders);

$trust_array = arrayDB("SELECT plati_id FROM gig_trustee_items");
$trust_list = [];foreach ($trust_array as $key => $trustee) {
	$trust_list[$trustee['plati_id']] = $trustee['plati_id'];
}

$items = arrayDB("SELECT * FROM (SELECT * FROM items WHERE scan = (select scan from items order by id desc limit 1)) as its
JOIN games
ON games.id=its.game_id");

$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
if($exrate) $dataex = $exrate[0]['value']; // 67
else{
	$botObj->sendMessage(['text' => 'Проблема с exrate']);
	die('no exrate');
} 
if($dataex < 40){
	$botObj->sendMessage(['text' => 'Проблема с exrate']);
	die('exrate < 40');
} 



//=========================================================================================
//========== Начало основного цикла обработки =============================================
foreach ($orders as $k => $order):

	$gig_order_id = $order['id'];


// 2. Получение Рейтинга пользователя и даты регистрации.
// Входные данные: BuyerUserID
	if(!$order['BuyerUserID']){
		add_comment_to_order($gig_order_id, 'there is no BuyerUserID in this order');
		continue;
	}

	$username = $order['BuyerUserID'];
	$goods = json_decode($order['goods'],true);
	$address = json_decode($order['ShippingAddress'], true);
	$ebay_item_id = $goods[0]['itemid'];
	$ebay_item_title = $goods[0]['title'];

	$stats = arrayDB("SELECT BuyerFeedbackScore,BayerRegistrationDate FROM ebay_orders WHERE id='$gig_order_id'");
	$BuyerFeedbackScore = $stats[0]['BuyerFeedbackScore'];
	$BayerRegistrationDate = $stats[0]['BayerRegistrationDate'];
	if ($BuyerFeedbackScore == 0 && $BuyerFeedbackScore !== '0') {
		$user = $ordersObj->GetUser($username, $ebay_item_id);
		echo "<hr>";
		print_r($user);
		if($user['Ack'] === 'Failure'){
			add_comment_to_order($order['id'], 'query to get buyer info returned Failure');
			continue;
		}
		if($user['User']['FeedbackPrivate'] === 'true') $BuyerFeedbackScore = 'priv';
		else $BuyerFeedbackScore = $user['User']['FeedbackScore'];
		$BayerRegistrationDate = $user['User']['RegistrationDate'];
		$d = new DateTime($BayerRegistrationDate);
		$d->add(date_interval_create_from_date_string('1 hour'));
		$BayerRegistrationDate = $d->format('Y-m-d H:i:s');
		print_r(['<hr>',$BuyerFeedbackScore,$BayerRegistrationDate]);
		arrayDB("UPDATE ebay_orders SET BuyerFeedbackScore='$BuyerFeedbackScore',BayerRegistrationDate='$BayerRegistrationDate' WHERE id='$gig_order_id'");

	}else{
		var_dump($BuyerFeedbackScore);
		var_dump('False<br>');
	}


// 3.0 Исключение исков, плексов.
// Входные данные: $ebay_item_id
	$plexes = [ '121962440805',
				'121962439324',
				'112090853589',];
	if (in_array($ebay_item_id, $plexes)) {
		add_comment_to_order($gig_order_id, 'ISK or PLEX');
		continue;
	}


// 3.1 Исключение заказов с более 1 позицией
// Входные данные: ebay_item_id
	$suitables = [];
	foreach ($items as $i => $item) {
		if ($item['ebay_id'] == $ebay_item_id) {
			$suitables[] = $item;
		}
	}
	if (count($suitables) > 1) {
		add_comment_to_order($gig_order_id, 'MORE then one game is suitable from the Games Table');
		continue;
	}
	if (count($suitables) < 1) {
		add_comment_to_order($gig_order_id, 'there are NO any games suitable from the Games Table');
		continue;
	}
	$suitable = $suitables[0];


// 3.2 Проверка, есть ли товар в Траст-листе.
// Входные данные: ebay_item_id
	if ($suitable['item1_id'] && isset($trust_list[$suitable['item1_id']])) {
		$chosen_item_id = $suitable['item1_id'];
		$chosen_item_price = $suitable['item1_price'];
	}else{

		add_comment_to_order($gig_order_id, 'the Game is not in the Trust List');
		continue;
	}


// 3.3  Исключение заказов с ценой менее 7% от рекомендуемой. Убирать товар с продажи.
// Входные данные: payed_price, chosen_item_price, dataex

	$payed_price = (float)$goods[0]['price']; // 6.31
	$border_price = formula($chosen_item_price, $dataex)*0.93;
	if ($payed_price < $border_price) {
		$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
		$rem = $is_removed ? 'have been' : 'was NOT';
		add_comment_to_order($gig_order_id, "the price is more then 7% less then recommended. The item $rem removed from sale");
		continue;
	}


// 4. Исключение заказов без имейла.
// Входные данные: BuyerEmail
	if (!filter_var($order['BuyerEmail'], FILTER_VALIDATE_EMAIL)) {
		add_comment_to_order($gig_order_id, 'incorrect email address');
		continue;
	}


// 5. Исключить заказы с несколькими товарами.
// Входные данные: $goods
	if (count($goods) > 1) {
		add_comment_to_order($gig_order_id, 'more then one game in this order');
		continue;
	}
	if (count($goods) < 1) {
		add_comment_to_order($gig_order_id, 'less then one game in this order');
		continue;
	}


// 6. Исключить заказы с множественными товарами.
// Входные данные: $goods
	if ($goods[0]['amount'] > 1) {
		add_comment_to_order($gig_order_id, 'game amount more then one');
		continue;
	}


// 7. Исключение пользователей с низким рейтингом(меньше 5).
// Входные данные: $BuyerFeedbackScore
	if ($BuyerFeedbackScore < 5) {
		add_comment_to_order($gig_order_id, 'Buyers FeedbackScore less then 5');
		continue;
	}


// 7.2 Исключение новых аккаунтов(меньше месяца).
	if (time() - (new DateTime($BayerRegistrationDate))->getTimestamp() < 60*60*24*30) {
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




// 8.2 Проверить исходник отправляемого имейла
// Входные данные:
	$msg_email = html_entity_decode(get_messages_for_send_producr($address['Country'], 'mail'));
	$msg_ebay = html_entity_decode(get_messages_for_send_producr($address['Country'], 'ebay'));

	if (!$msg_email || stripos($msg_email, '{{PRODUCT}}') === false) {
		add_comment_to_order($gig_order_id, 'Please check out the original message: ', 'no');
		continue;
	}




//================================================================================
//arrayDB("UPDATE ebay_orders SET `show`='yes' WHERE id='$gig_order_id'"); continue;



//================================================================================
	$botObj->sendMessage(['text' => date('H:i:s').' Начата процедура автоматической обработки заказа: '.$gig_order_id]);
arrayDB("UPDATE ebay_orders SET `ExecutionMethod`='automatic' WHERE id='$gig_order_id'");
//================================================================================

	arrayDB("INSERT INTO ebay_automatic_log (order_id,ebay_game_id) 
		VALUES ('$gig_order_id','$ebay_item_id')");

// 9. Получение счета от Плати.ру. Логировать итог.
// Входные данные:
	$inv_res = $platiObj->getInvoice($chosen_item_id);
	$invoice_resp_json = _esc(json_encode($inv_res));
	arrayDB("UPDATE ebay_automatic_log SET `invoice_resp`='$invoice_resp_json', plati_id='$chosen_item_id' WHERE order_id='$gig_order_id'");
	if(!$inv_res['success']){
		add_comment_to_order($gig_order_id, 'Error during geting plti.ru invoice. '.$inv_res['retdesc']);
		continue;
	}


// 10. Оплата счета. Логировать итог.
// Входные данные:
	$pay_resp = $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);
	$pay_resp['response'] = (array)$pay_resp['response'];
	$pay_resp_json = _esc(json_encode($pay_resp));
	arrayDB("UPDATE ebay_automatic_log SET `pay_resp`='$pay_resp_json' WHERE order_id='$gig_order_id'");
	if (!$pay_resp['success']) {
		add_comment_to_order($gig_order_id, 'Error during plti.ru invoice payment');
		continue;
	}
	$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i='.$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid'];


// 11. Получить товар товар. Логировать итог.
// Входные данные:
	$received_item = get_item_xml($receive_item_link);
	arrayDB("UPDATE ebay_automatic_log 
		SET `received_item`='"._esc(json_encode($received_item))."',
		product_api_link='$receive_item_link' WHERE order_id='$gig_order_id'");
	if (!$received_item['success']) {
		add_comment_to_order($gig_order_id, 'Can not get the product on the link: '.$receive_item_link, 'no');
		continue;
	}
	$product = $received_item['result'];


// 12. Отправить товар. Логировать итог.
// Входные данные:

	$item_title = cut_steam_from_title($ebay_item_title);

	//$product = iconv('CP1251', 'UTF-8', $product);
	$product = get_steam_key_from_text($product);
	$product = get_urls_from_text($product);

	$msg_email = str_ireplace('{{PRODUCT}}', $product, $msg_email);
	$msg_ebay = str_ireplace('{{EMAIL}}', $order['BuyerEmail'], $msg_ebay);

	$msg_email = key_link_replacer($msg_email);

	arrayDB("UPDATE ebay_automatic_log 
		SET msg_email='"._esc($msg_email)."', msg_ebay='"._esc($msg_ebay)."' WHERE order_id='$gig_order_id'");

	$ebay_orderid = $order['order_id'];
	$user_email = $order['BuyerEmail'];
	$email_subject = "Activation data for: $item_title";
	$email_body = $msg_email;

	$ebay_user = $order['BuyerUserID'];
	$ebay_item = $ebay_item_id;
	$ebay_subject = "Activation data for: $item_title";
	$ebay_body = $msg_ebay;

	//
	$mail = get_a3_smtp_object();
	$mail->addAddress($order['BuyerEmail']);
	$mail->addBCC('thenav@mail.ru');
	$mail->addBCC('store@gig-games.de');
	$mail->Subject = $email_subject;
	$mail->Body    = $email_body;
	$mail->AltBody = strip_tags($email_body);

	$is_email_sent = $mail->send();
	$is_email_sent = $is_email_sent ? 1 : 0;

	//
	$ebayObj = new EbayOrders();
	$userId = htmlspecialchars(stripslashes(strip_tags($ebay_user)));
	$itemId = htmlspecialchars(stripslashes($ebay_item));
	$subject = htmlspecialchars(stripslashes($ebay_subject));
	$body = htmlspecialchars(stripslashes(strip_tags($ebay_body)));

	$ebay_send_result = $ebayObj->SendMessage($userId, $itemId, $subject, $body);

	arrayDB("UPDATE ebay_automatic_log 
		SET email_send_resp='"._esc($is_email_sent)."', 
		ebay_send_resp='"._esc(json_encode($ebay_send_result))."' WHERE order_id='$gig_order_id'");

// 13. Проверить наличие товара на Плати.ру.
// Входные данные:
	if ($platiObj->isItemOnPlati($chosen_item_id)) {

		$ebay2Obj->updateQuantity($ebay_item_id, 3);

	}else{

		if ($suitable['item2_id'] && $platiObj->isItemOnPlati($suitable['item2_id'])) {

			$ebay2Obj->updateProductPrice($ebay_item_id, formula((float)$suitable['item2_price'], $dataex));
		}else{
			$ebay2Obj->removeFromSale($ebay_item_id);
		}
	}

// 14. Пометить товар отправленным
// Входные данные:
	if ($is_email_sent) {
		$ebay_shipped_resp = $ordersObj->MarkAsShipped($ebay_orderid);
		$ebay_shipped_resp_json = _esc(json_encode($ebay_shipped_resp));
		arrayDB("UPDATE ebay_automatic_log SET ebay_shipped_resp='$ebay_shipped_resp_json' WHERE order_id='$gig_order_id'");
		if ($ebay_shipped_resp['Ack'] == 'Success') {
			arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$ebay_orderid'");
		}
	}

	$botObj->sendMessage(['text' => date('H:i:s').'. Завершина('.$is_email_sent.') процедура автоматической обработки заказа: .'.$gig_order_id]);


// 15. Пишем ошибки
// Входные данные:
	arrayDB("UPDATE ebay_automatic_log SET `errors`='"._esc(json_encode($_ERRORS))."' WHERE order_id='$gig_order_id'");


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