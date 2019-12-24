<?php



function ebay_woo_order_fabrica($order)
{
	return array_merge([
		'id' => 0,
	    'order_id' => '122790399527-1967773213002',
	    'OrderStatus' => 'Completed',
	    'PaymentMethod' => 'PayPal',
	    'ExecutionMethod' => 'default',
	    'goods' => '[{"title":"Thief PC spiel Steam Download Digital Link DE\/EU\/USA Key Code Gift","amount":"1","price":"10.5","itemid":"122790399527"}]',
	    'items_amo' => 1,
	    'total_price' => 10.50,
	    'ShippingAddress' => '{"Name":"Florian Gubisch","Street1":"Ockersh\u00e4user Allee 35","Street2":"","CityName":"Marburg","StateOrProvince":"","Country":"DE","CountryName":"Deutschland","Phone":"Invalid Request","PostalCode":"35037","AddressID":"3221056411017","AddressOwner":"eBay","ExternalAddressID":""}]',
	    'BuyerUserID' => 'flori-zigori',
	    'BuyerEmail' => 'thenav@mail.ru',
	    'BuyerFirstName' => explode(' ', $order['first_name'])[0],
	    'BuyerLastName' => @explode(' ', $order['first_name'])[1],
	    'BuyerFeedbackScore' => 0,
	    'show' => 'no',
	    'comment' => '',
	    'is_notified' => 'no',
	    'BayerRegistrationDate' => 0,
	    'CreatedTime' => '2018-06-19 22:42:22',
	    'LastModTime' => '2018-06-20 06:33:17',
	    'PaidTime' => '2018-06-19 22:42:38',
	    'ShippedTime' => '2018-06-20 06:33:17',
	    'addTime' => '2018-06-20 09:48:56',
	    'block_ip' => 0,
	    'block_time' => 0,
	    'gig_order_id' => 1097,
	    'title' => $order['name'],
	    'price' => 10.50,
	    'amount' => 1,
	    'ebay_id' => '122790399527',
	    'shipped_time' => '0000-00-00 00:00:00',
	    'npp' => 1,
	    'total' => 1,
	    'item_comment' => '',
	    'user_id' => '',
	    'is_trusted' => '',
	    'is_problematic' => '',
	    'gig_item_id' => 1191,
	], $order);
}