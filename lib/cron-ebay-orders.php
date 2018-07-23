<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.


//$ord_arr = $ord_obj->getOrders(['order_status'=>'Completed','OrderIDArray'=>['216865269010','216842562010']]);


function saveOrders($ord_arr = []){
	
	$order_id_checker = 0;
	$update_query = '';
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

		$BuyerEmail = _esc($order['TransactionArray']['Transaction'][0]['Buyer']['Email']);

		$BuyerFirstName = _esc($order['TransactionArray']['Transaction'][0]['Buyer']['UserFirstName']);

		$BuyerLastName = _esc($order['TransactionArray']['Transaction'][0]['Buyer']['UserLastName']);

		$CreatedTime = $order['CreatedTime'];
		$d = new DateTime($CreatedTime);
		$d->add(date_interval_create_from_date_string('1 hour'));
		$CreatedTime = $d->format('Y-m-d H:i:s');

		$LastModTime = $order['CheckoutStatus']['LastModifiedTime'];
		$d = new DateTime($LastModTime);
		$d->add(date_interval_create_from_date_string('1 hour'));
		$LastModTime = $d->format('Y-m-d H:i:s');

		if(isset($order['PaidTime'])){
			$PaidTime = $order['PaidTime'];
			$d = new DateTime($PaidTime);
			$d->add(date_interval_create_from_date_string('1 hour'));
			$PaidTime = $d->format('Y-m-d H:i:s');
		}else $PaidTime = '';

		if(isset($order['ShippedTime'])){
			$ShippedTime = $order['ShippedTime'];
			$d = new DateTime($ShippedTime);
			$d->add(date_interval_create_from_date_string('1 hour'));
			$ShippedTime = $d->format('Y-m-d H:i:s');
		}else $ShippedTime = '';

		$check = arrayDB("SELECT id FROM ebay_orders WHERE order_id='$order_id'");
		if ($check) {
			$update_query .= "UPDATE ebay_orders 
				SET PaidTime='$PaidTime',
				 BuyerFirstName='$BuyerFirstName',
				 BuyerLastName='$BuyerLastName',
				 BuyerEmail='$BuyerEmail',
				 ShippedTime='$ShippedTime',
				 LastModTime='$LastModTime',
				 OrderStatus='$OrderStatus',
				 PaymentMethod='$PaymentMethod',
				 ShippingAddress='$ShippingAddress'
				WHERE order_id='$order_id';";
			// arrayDB($query);

	// echo "<pre>";
	// print_r($query);
	// echo "</pre>";

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

			$total = 0;
			foreach ($goods as $key => $value) {
				$total += (int)$value['amount'];
			}
			$gig_order_id = DB::getInstance()->lastid();
			foreach ($goods as $key => $good) {
				for ($i=0; $i < $good['amount']; $i++) { 

					if ($order_id_checker === $order['OrderID']) $npp++;
					else{ $order_id_checker = $order['OrderID']; $npp = 1; }

					$title = _esc($good['title']);
					$price = _esc($good['price']);
					$amount = _esc($good['amount']);
					$ebay_id = _esc($good['itemid']);
					arrayDB("INSERT INTO ebay_order_items (gig_order_id,title,price,amount,ebay_id,npp,total)
							VALUES('$gig_order_id','$title','$price','$amount','$ebay_id','$npp','$total')");
				}
				// убираем товар со стим_де
				arrayDB("UPDATE steam_de SET instock = 'no' WHERE $ebay_id = 'ebay_id'");
			}
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
	// echo $update_query;
	echo "</pre>";
	}
	arrayDB($update_query, true);
}




$ord_array = getOrderArray();


	//echo json_encode($ord_array);
	echo "<pre>";
	if($ord_array['success'] === 'OK') saveOrders($ord_array['ord_arr']);
	else print_r($ord_array);
	print_r($_ERRORS);
	echo "</pre>";