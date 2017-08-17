<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.



// заказы за 2 дня
$ord_arr = post_curl('http://hood.gig-games.de/api/listOrder', ['statusChange','startDate'=> date('m/d/Y', time()-60*60*24)]);

// все заказы
// $ord_arr = post_curl('http://hood.gig-games.de/api/listOrder', ['statusChange']);

// если только 1 заказ
if (isset($ord_arr['orderItems'])) $ord_arr = [$ord_arr];

// если нет заказов
if (!is_array($ord_arr)) {
	sa($ord_arr);
	return;
}

foreach ($ord_arr as $key => $order):
	sa($order);
	$dtls_orderID            = _esc($order['details']['orderID']);
	$dtls_quantity           = _esc($order['details']['quantity']);
	$dtls_date               = _esc(str_replace(["{ts '","'}"], '', $order['details']['date']));
	$dtls_price              = _esc($order['details']['price']);
	$orderStatusBuyer        = _esc($order['details']['orderStatusBuyer']);
	$orderStatusActionBuyer  = _esc($order['details']['orderStatusActionBuyer']);
	$orderStatusSeller       = _esc($order['details']['orderStatusSeller']);
	$orderStatusActionSeller = _esc($order['details']['orderStatusActionSeller']);
	$dtls_paymentProvider    = _esc($order['details']['paymentProvider']);
	$dtls_comments           = _esc(json_encode($order['details']['comments']));

	$br_accountName  = _esc($order['buyer']['accountName']);
	$br_email        = _esc($order['buyer']['email']);
	$br_salutation   = _esc($order['buyer']['salutation']);
	$br_firstName    = _esc($order['buyer']['firstName']);
	$br_lastName     = _esc($order['buyer']['lastName']);
	$br_comment      = _esc($order['buyer']['comment']);
	$br_address      = _esc($order['buyer']['address']);
	$br_city         = _esc($order['buyer']['city']);
	$br_zip          = _esc($order['buyer']['zip']);
	$br_phone        = _esc($order['buyer']['phone']);
	$br_country      = _esc($order['buyer']['country']);
	$countryTwoDigit = _esc($order['buyer']['countryTwoDigit']);

	$json_shipAddress = _esc(json_encode($order['shipAddress']));
	$json_orderItems  = _esc(json_encode($order['orderItems']));

	$check = arrayDB("SELECT id FROM hood_orders WHERE dtls_orderID = '$dtls_orderID' LIMIT 1");

	if ($check) {
		$done = arrayDB("UPDATE hood_orders SET
			dtls_date = '$dtls_date',
			orderStatusBuyer = '$orderStatusBuyer',
			orderStatusActionBuyer = '$orderStatusActionBuyer',
			orderStatusSeller = '$orderStatusSeller',
			orderStatusActionSeller = '$orderStatusActionSeller',
			dtls_comments = '$dtls_comments'
			WHERE dtls_orderID = '$dtls_orderID'");
		var_dump('updated: ' . $done);
	}else{
		$done = arrayDB("INSERT INTO hood_orders (dtls_orderID,dtls_quantity,dtls_date,dtls_price,
			orderStatusBuyer,orderStatusActionBuyer,orderStatusSeller,orderStatusActionSeller,
			dtls_paymentProvider,dtls_comments,br_accountName,br_email,br_salutation,br_firstName,br_lastName,
			br_comment,br_address,br_city,br_zip,br_phone,br_country,
			countryTwoDigit,json_shipAddress,json_orderItems)
			VALUES ('$dtls_orderID','$dtls_quantity','$dtls_date','$dtls_price',
			'$orderStatusBuyer','$orderStatusActionBuyer','$orderStatusSeller','$orderStatusActionSeller',
			'$dtls_paymentProvider','$dtls_comments','$br_accountName','$br_email','$br_salutation','$br_firstName','$br_lastName',
			'$br_comment','$br_address','$br_city','$br_zip','$br_phone','$br_country',
			'$countryTwoDigit','$json_shipAddress','$json_orderItems')");
		var_dump('inserted: ' . $done);
	}

endforeach;

?>