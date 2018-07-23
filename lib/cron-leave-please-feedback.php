<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.


$order_arr = GetItemsAwaitingFeedbacks();

if(defined('DEV_MODE')){
	sa($order_arr);
	return;
}














// return;
//=====================================================================================================
//=====================================================================================================
$ebayObj = new EbayOrders();
$orders_7days = arrayDB("SELECT awaiting_orders.id,UserID, ItemID, TransactionID, EndTime, ShippingAddress as Country, PaidTime, ShippedTime, 7days_sent, 14days_sent
	from awaiting_orders
	left join ebay_orders
	on awaiting_orders.OrderLineItemID = ebay_orders.order_id
	WHERE 
	7days_sent = 0 AND
	last_seen > NOW() - INTERVAL 2 HOUR AND
	ShippedTime < NOW() - INTERVAL 7 DAY AND
	ShippedTime > NOW() - INTERVAL 8 DAY AND
	CommentType = 'Positive'");

$orders_7days = array_map(function($el)
{
	$el['Country'] = @json_decode($el['Country'],true)['Country'];
	return $el;
}, $orders_7days);



// orders_7days
foreach ($orders_7days as $key => $record) {

	if(!is_trusted_country($record['Country'])) continue;

	if(is_problematic_user($record['UserID'])) continue;

	$userId = htmlspecialchars(stripslashes(strip_tags($record['UserID'])));
	$itemId = htmlspecialchars(stripslashes($record['ItemID']));
	$subject = htmlspecialchars(stripslashes('Sind Sie zufrieden geblieben?'));

	$body  = get_text_template('feedback_7days', 'DE');

	$link = 'https://www.ebay.de/fdbk/leave_single_feedback?item_id='.$record['ItemID'].'&transaction_id='.$record['TransactionID'];

	$body = str_replace('{{feedback_link}}', $link, $body);

	$body = htmlspecialchars(stripslashes(strip_tags($body)));

	$resp = $ebayObj->SendMessage($userId, $itemId, $subject, $body);
	sa($resp);

	$id = $record['id'];
	if (isset($resp['Ack']) && $resp['Ack'] != 'Failure') {
		arrayDB("UPDATE awaiting_orders 
			SET 7days_sent = CURRENT_TIMESTAMP
			WHERE id = '$id'");
	}
}




//=====================================================================================================
$orders_14days = arrayDB("SELECT awaiting_orders.id,UserID, ItemID, TransactionID, EndTime, ShippingAddress as Country, PaidTime, ShippedTime, 7days_sent, 14days_sent
	from awaiting_orders
	left join ebay_orders
	on awaiting_orders.OrderLineItemID = ebay_orders.order_id
	WHERE 
	14days_sent = 0 AND
	last_seen > NOW() - INTERVAL 2 HOUR AND
	ShippedTime < NOW() - INTERVAL 14 DAY AND
	ShippedTime > NOW() - INTERVAL 15 DAY AND
	CommentType = 'Positive'");

$orders_14days = array_map(function($el)
{
	$el['Country'] = @json_decode($el['Country'],true)['Country'];
	return $el;
}, $orders_14days);


// orders_14days
foreach ($orders_14days as $key => $record) {

	if(!is_trusted_country($record['Country'])) continue;

	if(is_problematic_user($record['UserID'])) continue;

	$userId = htmlspecialchars(stripslashes(strip_tags($record['UserID'])));
	$itemId = htmlspecialchars(stripslashes($record['ItemID']));
	$subject = htmlspecialchars(stripslashes('Sind Sie zufrieden geblieben?'));

	$body  = get_text_template('feedback_14days', 'DE');

	$link = 'https://www.ebay.de/fdbk/leave_single_feedback?item_id='.$record['ItemID'].'&transaction_id='.$record['TransactionID'];

	$body = str_replace('{{feedback_link}}', $link, $body);

	$body = htmlspecialchars(stripslashes(strip_tags($body)));

	$resp = $ebayObj->SendMessage($userId, $itemId, $subject, $body);
	sa($resp);

	$id = $record['id'];
	if (isset($resp['Ack']) && $resp['Ack'] != 'Failure') {
		arrayDB("UPDATE awaiting_orders 
			SET 14days_sent = CURRENT_TIMESTAMP
			WHERE id = '$id'");
	}
}