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
if($dataex < 1){
	AutomaticBot::sendMessage(['text' => date('H:i:s').' Проблема с exrate ( < 1 )']);
	die('exrate < 1');
} 



//=========================================================================================
//========== Начало основного цикла обработки =============================================
foreach ($orders as $k => $order):

	// if($k == 1) break;
	$username = $order['BuyerUserID'];
	$is_trusted_user = is_trusted_user($username);
	$goods = json_decode($order['goods'],true);
	$address = json_decode($order['ShippingAddress'], true);
	$is_trusted_country = is_trusted_country($address['Country']);
	$ebay_item_id = $order['ebay_id'];
	$ebay_item_title = clean_ebay_title2($order['title']);
	$gig_order_id = $order['gig_order_id'];
	$gig_order_item_id = $order['gig_order_item_id'];
	$continue = false;
	$chosen_item_id = 0;
	$chosen_item_price = 0;
	$from_warehouse = false;
	$wh_tolerant = false;
	$border_price = 0;

// 0. Создаем список товаров ===  foreach CONSTUCTOR
	if ($order['npp'] == 1){
		$title_list = '';
		$product_list = '';
		$gig_order_item_id_list = [];
		$msg_email = html_entity_decode(get_messages_for_send_producr($address['Country'], 'mail'));
		$msg_ebay = html_entity_decode(get_messages_for_send_producr($address['Country'], 'ebay'));
	}

// 1.1 Проверить исходник отправляемого имейла
// Входные данные:
	if (!$msg_email || stripos($msg_email, '{{PRODUCT}}') === false) {
		add_comment_to_order($gig_order_id, 'Please check out the original message!');
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
		add_comment_to_order($gig_order_id, 'there is no BuyerUserID in this order', false);
		send_identify_message($order, $address['Country']);
		continue;
	}

	$stats = arrayDB("SELECT BuyerFeedbackScore,BayerRegistrationDate FROM ebay_orders WHERE id='$gig_order_id'");
	$BuyerFeedbackScore = $stats[0]['BuyerFeedbackScore'];
	$BayerRegistrationDate = $stats[0]['BayerRegistrationDate'];
	if ($BuyerFeedbackScore === '') { // true если пустая строка
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


// 2.2 Если стоимость заказа больше 4 евро (10 для немецких).
// Входные данные: ebay_id
	$max_order_price = 4;
	if($is_trusted_country) $max_order_price = 10;

	// больше пороги для доверенных пользователей
	if ($is_trusted_user) {
		$max_order_price = 10;
		if($is_trusted_country) $max_order_price = 20;
	}
	
	if($order['total_price'] > $max_order_price){
		add_comment_to_order($gig_order_id, ' Order total price more then €'.$max_order_price, false);
		if(!$is_trusted_user) send_identify_message($order, $address['Country']);
		continue;
	}


// 2.3 В заказе есть более 2 копий одного товара.
// Входные данные: ebay_id
	foreach ($goods as $good) {
		if($good['amount'] > 2 && !$is_trusted_user){
			add_comment_to_order($gig_order_id, ' Order has product with amount more the 2', false);
			send_identify_message($order, $address['Country']);
			continue 2;
		}
	}


// 3.0 Исключение исков, плексов.
// Входные данные: $ebay_item_id
	if (is_eve($ebay_item_id)) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'ISK or PLEX');
		$continue = true;
	}



// 6. Исключение заказов без имейла.
// Входные данные: BuyerEmail
	if (!filter_var($order['BuyerEmail'], FILTER_VALIDATE_EMAIL)) {
		add_comment_to_order($gig_order_id, 'incorrect email address');
		continue;
	}



// 7. Исключение пользователей с низким рейтингом(меньше 5) если цена больше 5 евро.
// Входные данные: $BuyerFeedbackScore
	if ((!$is_trusted_user && $BuyerFeedbackScore < 5 && $order['total_price'] > 5) || ($is_trusted_user && $order['total_price'] > 20)) {
		add_comment_to_order($gig_order_id, 'Buyers FeedbackScore less then 5 and order price more then 5eur', false);
		if(!$is_trusted_user) send_identify_message($order, $address['Country']);
		continue;
	}


// 7.2 Исключение новых аккаунтов(меньше 30 дней) если цена больше 5 евро.
	if (!$is_trusted_user && (time() - (new DateTime($BayerRegistrationDate))->getTimestamp()) < 60*60*24*30 && $order['total_price'] > 5) {
		add_comment_to_order($gig_order_id, 'This user account has been created in the previous 30 days', false);
		send_identify_message($order, $address['Country']);
		continue;
	}


// 8. Исключить заказы если пользователь совершил больше 3х покупок за неделю.
// Входные данные:
	$one_week_ago = date('Y-m-d H:i:s', time()-(60*60*24*7));
	$one_week_orders = arrayDB("SELECT id,ShippedTime  FROM ebay_orders WHERE ShippedTime > '$one_week_ago' AND  BuyerUserID = '$username' LIMIT 10");
	$max_orders_per_week  = 3;
	// для недоверительных стран фильтр строже (1 покупка в неделю)
	if (!$is_trusted_country) $max_orders_per_week = 1;
	// поднимаем лимиты для доверительных пользователей
	if ($is_trusted_user) {
		$max_orders_per_week  = 10;
		// для недоверительных стран фильтр строже (3 покупка в неделю)
		if (!$is_trusted_country) $max_orders_per_week = 3;
	}
	if (count($one_week_orders) > $max_orders_per_week) {
		add_comment_to_order($gig_order_id, "This user has made more than $max_orders_per_week purchases per week", false);
		send_identify_message($order, $address['Country']);
		continue;
	}


// 9. Исключить заказы если немец, сумма заказов в течение недели >10€
// Входные данные: $is_trusted_user
	$one_week_sum = arrayDB("SELECT sum(total_price) as sum FROM ebay_orders WHERE ShippedTime >  NOW() - INTERVAl 1 WEEK AND  BuyerUserID = '$username'");
	$max_order_week_price = 10;
	if($is_trusted_user) $max_order_week_price = 30;
	if ($is_trusted_country && $one_week_sum && ($one_week_sum[0]['sum'] + $order['total_price']) > $max_order_week_price){
		add_comment_to_order($gig_order_id, "This user has made purchases more than 10 euros for last week", false);
		send_identify_message($order, $address['Country']);
		continue;
	}



// 10.1 Исключение заказов с более 1 игрой в списке Games
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


// 10.2 Проверка, есть ли товар на plati.ru.
// Входные данные: suitable
	$payed_price = (float)$order['price']; // 6.31
	if ($suitable['item1_id']){
		$chosen_item_id = $suitable['item1_id'];
		$chosen_item_price = $suitable['item1_price'];


// 10.3  Проверка, есть ли товар в Траст-листе.
// Входные данные: suitable
		if (!arrayDB("SELECT plati_id FROM gig_trustee_items WHERE plati_id = '"._esc($suitable['item1_id'])."'")) {
			add_comment_to_order([$gig_order_id, $gig_order_item_id], 'the Game is not in the Trust List');
			$wh_tolerant = $continue = true;
		}


// 10.4  Исключение заказов с ценой менее 7% от рекомендуемой. Убирать товар с продажи.
// Входные данные: payed_price, chosen_item_price, dataex
		$border_price = formula($chosen_item_price, $dataex)*0.93;
		if ($payed_price < $border_price) {
			$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
			$rem = $is_removed ? 'has been' : 'was NOT';
			add_comment_to_order([$gig_order_id, $gig_order_item_id], "the price is more then 7% less then recommended. The item $rem removed from sale");
			$wh_tolerant = $continue = true;
		}

	}else{ // товара нет на плати.ру

		$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
		$rem = $is_removed ? 'has been' : 'was NOT';
		add_comment_to_order([$gig_order_id, $gig_order_item_id], "Out of stock. The item $rem removed from sale");
		$wh_tolerant = $continue = true;
	}


// 11. есть ли товар на складе
// Входные данные: ebay_item_id, payed_price, border_price

	// подумать что с этим делать
	// if (!$continue || ($continue && $wh_tolerant)) {
	if (!$continue) {

		$warehouse = get_warehouse($ebay_item_id);
		// товар плати.ру есть но на складе дешевле
		if ($chosen_item_price && $warehouse && $warehouse['price'] < $chosen_item_price) {
			// Исключение заказов с ценой менее 7% от рекомендуемой. Убирать товар с продажи.
			$border_price = formula($warehouse['price'], $dataex) * 0.93;
			if ($payed_price < $border_price) {
				$is_removed = $ebay2Obj->removeFromSale($ebay_item_id);
				$rem = $is_removed ? 'has been' : 'was NOT';
				add_comment_to_order([$gig_order_id, $gig_order_item_id], "the price is more then 7% less then recommended. The item $rem removed from sale");
				$wh_tolerant = $continue = true;
			}else{
				$from_warehouse = true;
			}
		}
		// товара плати.ру нет а на складе есть
		if (!$chosen_item_price && $warehouse) {
			$from_warehouse = true;
		}
	}




//================================================================================
//arrayDB("UPDATE ebay_orders SET `show`='yes' WHERE id='$gig_order_id'"); continue;













//================================================================================
	// $botObj->sendMessage(date('H:i:s').' Начата процедура автоматической обработки заказа: '.$gig_order_id);
	if(!$continue) arrayDB("UPDATE ebay_orders SET `ExecutionMethod`='automatic' WHERE id='$gig_order_id'");
//================================================================================

	arrayDB("INSERT INTO ebay_automatic_log (order_id, order_item_id, ebay_game_id) 
		VALUES ('$gig_order_id', '$gig_order_item_id', '$ebay_item_id')");
	$automatic_id = DB::getInstance()->lastid();

// 9. Получение счета от Плати.ру. Логировать итог.
// Входные данные:
	if (!$continue && !$from_warehouse) {
		$inv_res = $platiObj->getInvoice($chosen_item_id);
		$invoice_resp_json = _esc(json_encode($inv_res));
		arrayDB("UPDATE ebay_automatic_log SET `invoice_resp`='$invoice_resp_json', plati_id='$chosen_item_id' WHERE id='$automatic_id'");
	}else{
		$inv_res = '';
		$invoice_resp_json = '';
	}


// 10. Оплата счета. Логировать итог.
// Входные данные:
	// добавлено правило - оплаичвать только текстовые товары
	// 17.09.2018 убрано правило  "&& $inv_res['inv']['type_good'] == '1'"
	if(isset($inv_res['success']) && $inv_res['success']){
		$pay_resp = $platiObj->payInvoice($inv_res['inv']['wm_inv'],$inv_res['inv']['wm_purse']);
		$pay_resp['response'] = (array)$pay_resp['response'];
		$pay_resp_json = _esc(json_encode($pay_resp));
		arrayDB("UPDATE ebay_automatic_log SET `pay_resp`='$pay_resp_json' WHERE id='$automatic_id'");
	}else{
		$pay_resp = '';
		$pay_resp_json = '';
	}


// 11. Получить товар товар. Логировать итог.
// Входные данные:
	if (isset($pay_resp['success']) && $pay_resp['success']) {
		$receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i='.$inv_res['inv']['id'].'&uid='.$inv_res['inv']['uid'];
		$received_item = get_item_xml($receive_item_link);
		$product = $received_item['result'];
		// если товар - картинка
		if ($received_item['typegood'] === '2') { 
			$received_item['success'] = false;
			$product = '';
		}
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
		// в переменной $secret_hash - хеш от последнего купленного товара в заказе
		$secret_hash = $gig_order_id.'-'.$gig_order_item_id.'-'.get_secret_hash($gig_order_item_id);

	}elseif($from_warehouse) {

		$title_list .= $ebay_item_title.', ';
		$product_list .= product_html($ebay_item_title, $warehouse['steam_key']);
		$gig_order_item_id_list[] = $gig_order_item_id;
		// в переменной $secret_hash - хеш от последнего купленного товара в заказе
		$secret_hash = $gig_order_id.'-'.$gig_order_item_id.'-'.get_secret_hash($gig_order_item_id);
		warehouse_status_sold($warehouse['id'], $gig_order_id, $gig_order_item_id);
	}

	$games_count = count($gig_order_item_id_list);
	// Отправлять ли товар?
	if ($order['npp'] == $order['total'] && $games_count > 0) {
		
		$msg_email = str_replace('{{PRODUCT}}', $product_list, $msg_email);
		$msg_email = str_replace('{{USER_EMAIL}}', $order['BuyerEmail'], $msg_email);
		$msg_email = str_replace('{{MISTER}}', $order['BuyerFirstName'], $msg_email);
		$msg_email = fill_email_item_panel($msg_email);
		$msg_email = str_replace('{{PRIVATE_MAIL_LINK}}', private_mail_link($secret_hash), $msg_email);
		if ($is_trusted_country) {
			if ($games_count === 1) {
				$msg_email = str_replace('{{HERE_IS_GAMES}}', 'Hier ist dein Spiel', $msg_email);
			}else{
				$msg_email = str_replace('{{HERE_IS_GAMES}}', 'Hier sind deine Spiele', $msg_email);
			}
		}else{
			if ($games_count === 1) {
				$msg_email = str_replace('{{HERE_IS_GAMES}}', 'Here is you game', $msg_email);
			}else{
				$msg_email = str_replace('{{HERE_IS_GAMES}}', 'Here is you games', $msg_email);
			}
		}

		$msg_ebay = str_replace('{{EMAIL}}', $order['BuyerEmail'], $msg_ebay);
		$msg_ebay = str_replace('{{PRIVATE_PAGE_LINK}}', private_mail_link($secret_hash), $msg_ebay);

		$email_body = _esc(str_replace('<!-- facebook_paragraph -->', get_facebook_paragraph($ebay_item_id, $address['Country']), $msg_email));

		$msg_email = str_replace('<!-- mail_link_block -->', mail_link_block($secret_hash, $address['Country']), $msg_email);

		arrayDB("UPDATE ebay_automatic_log 
			SET email_slug='$secret_hash', msg_ebay='"._esc($msg_ebay)."' WHERE id='$automatic_id'");

		$msg_email_2018 = get_mail2018_template($address['Country']);
		$msg_email_2018 = str_replace('{{PRIVATE_MAIL_LINK}}', private_mail_link($secret_hash), $msg_email_2018);

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
		$mail->Body    = $msg_email_2018;
		$mail->AltBody = strip_tags($msg_email_2018);

		$is_email_sent = $mail->send() ? 1 : 0;

		$email_subject = _esc($email_subject);
		arrayDB("INSERT INTO gig_email_saver (email,email_slug,subject,body_html,errors) 
			VALUES ('$user_email','$secret_hash','$email_subject','$email_body','"._esc(json_encode($_ERRORS))."')");

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



// 13. Пометить товар отправленным
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

//------------------------------------------------------------

	if(isset($inv_res['success']) && !$inv_res['success']){
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Error during geting plti.ru invoice. '.$inv_res['retdesc']);
		AutomaticBot::sendMessage(['text' => date('H:i:s').'('.$gig_order_id.') Error during geting plti.ru invoice. '.$inv_res['retdesc']]);
		$ebay2Obj->removeFromSale($ebay_item_id);
		continue;
	}elseif (isset($inv_res['success']) && !$inv_res['success'] 
		&& $received_item['typegood'] === '2') {
		// фильтруем товар файл
		add_comment_to_order([$gig_order_id, $gig_order_item_id], '<div style="background:green;">Товар был оплачен. Товар - картинка<br>'.$received_item['result'].'</div>');
		AutomaticBot::sendMessage(['text' => date('H:i:s').'('.$gig_order_id.') Товар был оплачен. Товар - файл']);
		continue;
	}

	if (isset($pay_resp['success']) && !$pay_resp['success']) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Error during plti.ru invoice payment');
		AutomaticBot::sendMessage(['text' => date('H:i:s').'Error during plti.ru invoice payment: '.$gig_order_id]);
		continue;
	}

	if (isset($received_item['success']) && !$received_item['success']) {
		add_comment_to_order([$gig_order_id, $gig_order_item_id], 'Can not get the product on the link: '.$receive_item_link . ' <br>To receive item manually follow: ' . $inv_res['inv']['link']);
		AutomaticBot::sendMessage(['text' => date('H:i:s').'Can not get the product on the link: '.$receive_item_link]);
		continue;
	}

// 14. Проверить наличие товара на Плати.ру.
// Входные данные:
	if (!$continue) { 
		// также проверка товара на складе
		$warehouse = get_warehouse($ebay_item_id);
		if (!$warehouse && $chosen_item_id && $platiObj->isItemOnPlati($chosen_item_id)) {

			// AutomaticBot::sendMessage(['text' => 'if 1: ' . $chosen_item_id]);
			$ebay2Obj->updateQuantity($ebay_item_id, 3);

		}else{

			// AutomaticBot::sendMessage(['text' => 'else 1: ' . $chosen_item_id]);
			if ($suitable['item2_id'] && $platiObj->isItemOnPlati($suitable['item2_id'])) {
				$new_price = formula((float)$suitable['item2_price'], $dataex);
				if ($payed_price < $new_price) {
					// AutomaticBot::sendMessage(['text' => 'if 3: ' . $payed_price . ' | ' . $new_price]);
					$ebay2Obj->updateProductPrice($ebay_item_id, $new_price);
				}
			}else{
				$ebay2Obj->removeFromSale($ebay_item_id);
			}
		}
	}

	// $botObj->sendMessage(date('H:i:s').'. Завершина('.@$is_email_sent.') процедура автоматической обработки заказа: .'.$gig_order_id);


// 15. Пишем ошибки
// Входные данные:
	arrayDB("UPDATE ebay_automatic_log SET `errors`='"._esc(json_encode($_ERRORS))."' WHERE id='$automatic_id'");


// 16. Пишем детальную информацию о заказе
// Входные данные:
	$product_frame_link   = isset($inv_res['inv']['link'])     ? _esc($inv_res['inv']['link']) : null;
	$product_api_link     = isset($receive_item_link)          ? _esc($receive_item_link) : null;
	$ebay_order_id = _esc($order['order_id']);
	arrayDB("INSERT INTO ebay_invoices (ExecutionMethod,
									  ebay_order_id,
									  parser_order_id,
									  ebay_game_id,
									  platiru_invoice_json,
									  web_pay_json,
									  product_frame_link,
									  product_api_link)
							   VALUES('automatic',
							   		  '$ebay_order_id',
									  '$gig_order_id', 
									  '$ebay_item_id', 
									  '$invoice_resp_json', 
									  '$pay_resp_json', 
									  '$product_frame_link', 
									  '$product_api_link')");

endforeach;





print_r($_ERRORS);
?>
</pre>