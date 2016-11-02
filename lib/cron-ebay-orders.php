<?php ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.


//$ord_arr = $ord_obj->getOrders(['order_status'=>'Completed','OrderIDArray'=>['216865269010','216842562010']]);




function getOrderArray(){

	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders(['NumberOfDays'=>2,'SortingOrder'=>'Ascending']);

	//$ord_arr = $ord_obj->getOrders(['order_status'=>'Completed','OrderIDArray'=>['216865269010']]);

	//$ord_arr = $ord_obj->getOrders(['order_status'=>'All','CreateTimeFrom'=>'2016-09-17T00:00:00.000Z','CreateTimeTo'=>'2016-09-18T00:01:00.000Z']);

	if(!isset($ord_arr['Ack']))
		return ['success'=>0,'text'=>'нет данных от ebay api'];

	// echo "<pre>";
	// print_r($ord_arr);
	// echo "</pre>";

	if($ord_arr['Ack'] !== 'Success')
		return ['success'=>0,'text'=>'ebay api вернул fail','Errors'=>$ord_arr['Errors']];

	if(empty($ord_arr['OrderArray']))
		return ['success'=>0,'text'=>'нет заказов'];

	if(isset($ord_arr['OrderArray']['Order']['OrderID']))
		$ord_arr['OrderArray']['Order'] = [$ord_arr['OrderArray']['Order']];
	
	// echo "<pre>";
	// print_r($ord_arr['OrderArray']['Order'][0]);
	// echo "</pre>";
	return ['success'=>'OK','ord_arr'=>$ord_arr['OrderArray']['Order']];
}

function saveOrders($ord_arr = []){
	
	// echo "<pre>";
	// print_r($ord_arr);
	// echo "</pre>";
	foreach ($ord_arr as $order) {
		
		$order_id = $order['OrderID'];

		$PaymentMethod = $order['CheckoutStatus']['PaymentMethod'];

		$OrderStatus = $order['OrderStatus'];

		if(isset($order['TransactionArray']['Transaction']['Buyer']))
			$order['TransactionArray']['Transaction'] = [$order['TransactionArray']['Transaction']];

		$goods = [];
		foreach ($order['TransactionArray']['Transaction'] as $a => $transaction) {
			$item = [];
			$item['title'] = $transaction['Item']['Title'];
			$item['amount'] = $transaction['QuantityPurchased'];
			$item['price'] = $transaction['TransactionPrice'];
			$item['itemid'] = $transaction['Item']['ItemID'];
			$goods[] = $item;
		}
		$goods_json = _esc(json_encode($goods));

		$items_amo = $a+1;

		$total_price = $order['Total'];

		$ShippingAddress = _esc(json_encode($order['ShippingAddress']));

		$BuyerUserID = $order['BuyerUserID'];

		$BuyerEmail = $order['TransactionArray']['Transaction'][0]['Buyer']['Email'];

		$BuyerFirstName = _esc($order['TransactionArray']['Transaction'][0]['Buyer']['UserFirstName']);

		$BuyerLastName = _esc($order['TransactionArray']['Transaction'][0]['Buyer']['UserLastName']);

		$CreatedTime = $order['CreatedTime'];
		$d = new DateTime($CreatedTime);
		$CreatedTime = $d->format('Y-m-d H:i:s');

		$LastModTime = $order['CheckoutStatus']['LastModifiedTime'];
		$d = new DateTime($LastModTime);
		$LastModTime = $d->format('Y-m-d H:i:s');

		if(isset($order['PaidTime'])){
			$PaidTime = $order['PaidTime'];
			$d = new DateTime($PaidTime);
			$PaidTime = $d->format('Y-m-d H:i:s');
		}else $PaidTime = '';

		if(isset($order['ShippedTime'])){
			$ShippedTime = $order['ShippedTime'];
			$d = new DateTime($ShippedTime);
			$ShippedTime = $d->format('Y-m-d H:i:s');
		}else $ShippedTime = '';

		$check = arrayDB("SELECT id FROM ebay_orders WHERE order_id='$order_id'");
		if ($check) {
			$query = "UPDATE ebay_orders 
				SET PaidTime='$PaidTime',
				 BuyerFirstName='$BuyerFirstName',
				 BuyerLastName='$BuyerLastName',
				 BuyerEmail='$BuyerEmail',
				 ShippedTime='$ShippedTime',
				 LastModTime='$LastModTime',
				 OrderStatus='$OrderStatus',
				 PaymentMethod='$PaymentMethod',
				 ShippingAddress='$ShippingAddress'
				WHERE order_id='$order_id'";
			arrayDB($query);

	echo "<pre>";
	print_r($query);
	echo "</pre>";

		}else{
			arrayDB("INSERT INTO ebay_orders (id,
				order_id,
				OrderStatus,
				PaymentMethod,
				goods,
				items_amo,
				total_price,
				ShippingAddress,
				BuyerUserID,
				BuyerEmail,
				BuyerFirstName,
				BuyerLastName,
				CreatedTime,
				LastModTime,
				PaidTime,
				ShippedTime, addTime)
				VALUES(null,
				'$order_id',
				'$OrderStatus',
				'$PaymentMethod',
				'$goods_json',
				'$items_amo',
				'$total_price',
				'$ShippingAddress',
				'$BuyerUserID',
				'$BuyerEmail',
				'$BuyerFirstName',
				'$BuyerLastName',
				'$CreatedTime',
				'$LastModTime',
				'$PaidTime',
				'$ShippedTime',null)");
		}


	echo "<pre>";
	print_r($check);
	echo "last<br>";
	var_dump($BuyerUserID);
	var_dump($OrderStatus);
	var_dump($order_id);
	var_dump($total_price);
	print_r($goods);
	var_dump($PaidTime);
	var_dump($ShippedTime);
	echo "</pre>";
	}
}




$ord_array = getOrderArray();


	//echo json_encode($ord_array);
	echo "<pre>";
	if($ord_array['success'] === 'OK') saveOrders($ord_array['ord_arr']);
	else print_r($ord_array);
	echo "</pre>";