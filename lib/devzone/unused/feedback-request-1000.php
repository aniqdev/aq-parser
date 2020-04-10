<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.



$ebayObj = new EbayOrders();


$orders_8days = arrayDB("SELECT awaiting_orders.id,UserID,ItemID,TransactionID,EndTime,ShippingAddress,PaidTime,ShippedTime,7days_sent,14days_sent from awaiting_orders
	left join ebay_orders
	on awaiting_orders.OrderLineItemID = ebay_orders.order_id
	WHERE 
	PaidTime < NOW() - INTERVAL 7 DAY AND
	-- PaidTime > NOW() - INTERVAL 8 DAY AND
	ShippedTime > 0 AND
	CommentType = 'Positive'
	ORDER BY ShippedTime ASC");

$orders_8days = array_map(function($el)
{	
	$el['Country'] = json_decode($el['ShippingAddress'],true)['Country'];
	unset($el['ShippingAddress']);
	return $el;
}, $orders_8days);

// draw_table_with_sql_results($orders_8days, 1);



if(defined('DEV_MODE')) return;
foreach ($orders_8days as $key => $record) {

	// if( $key >= 200 ) break;

	if($record['7days_sent'] != 0 && $record['14days_sent'] != 0) continue;

	if(!is_trusted_country($record['Country'])) continue;

	$userId = htmlspecialchars(stripslashes(strip_tags($record['UserID'])));
	$itemId = htmlspecialchars(stripslashes($record['ItemID']));
	$subject = htmlspecialchars(stripslashes('Sind Sie zufrieden geblieben?'));

	$body = 'Ich hoffe Sie sind mit uns zufrieden geblieben. Lust auf ein gratis Spiel?  Bitte hinterlassen Sie eine positive Bewertung und als kleines Dankeschön schicken wir Ihnen einen zufälligen Steam-Schlüssel.
 
Ich bemühe mich vom ganzen Herzen unseren Service besser zu gestalten und immer auszuweiten. Es würde uns sehr helfen, wenn Sie uns positiv bewerten. 

Bewertung abgeben:
https://www.ebay.de/fdbk/leave_single_feedback?item_id='.$record['ItemID'].'&transaction_id='.$record['TransactionID'].'

Mit freundlichem Gruß Konstantin Falke';

	$body = htmlspecialchars(stripslashes(strip_tags($body)));

	$resp = $ebayObj->SendMessage($userId, $itemId, $subject, $body);
	sa($resp);

	$id = $record['id'];
	if ($resp['Ack'] != 'Failure') {
		arrayDB("UPDATE awaiting_orders 
			SET 7days_sent = CURRENT_TIMESTAMP,
				14days_sent = CURRENT_TIMESTAMP
			WHERE id = '$id'");
	}
}
