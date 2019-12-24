<?php

aqs_pagination('woo_automatic_log');

$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
$limit = @$_GET['limit'] ? (int)$_GET['limit'] : 10;

if(isset($_GET['offset']) && isset($_GET['limit'])){
	$limit = 'LIMIT '.(int)$_GET['offset'].','.(int)$_GET['limit'];
}else{
	$limit = 'LIMIT 10';
}

$automaticArr = arrayDB("SELECT 
    woo_orders.first_name,
    woo_orders.email,
    woo_order_items.name,
    woo_order_items.shipped_time,
    woo_automatic_log.*
FROM woo_automatic_log
JOIN woo_orders
ON woo_automatic_log.order_id=woo_orders.id
JOIN woo_order_items
ON woo_automatic_log.order_item_id=woo_order_items.id
ORDER BY woo_automatic_log.id DESC $limit");

?><br>
<!-- <pre>
<?php
	// print_r($automaticArr[0]);
	// var_dump(json_decode($automaticArr[0]['pay_resp'],true));
	//print_r($automaticArr);
?>
</pre> -->
<table class='ppp-table-collapse al-table' style="width: 100%;font-size: 13px;">
	<tr>
		<th>id</th>
		<th title="Order ID">order</th>
		<th title="Game name">title</th>
		<th title="Plati.ru ID">plati_id</th>
		<th title="Buyer User ID">first_name</th>
		<th title="Buyer Email">email</th>
		<th title="Plati.ru invoice result">invoiced</th>
		<th title="Result of payment">paid</th>
		<th title="Product received">received</th>
		<th title="amount">amount</th>
		<th title="Email sending result">emaild</th>
		<th title="Ebay sending result">ebayd</th>
		<th title="Marked as shipped">mrkd</th>
		<th title="shipped_time">shipped</th>
	</tr>
<?php
function table_inners($automaticArr){

foreach ($automaticArr as $key => $order):

	$invoice_resp = json_decode($order['invoice_resp']);
	$pay_resp = json_decode($order['pay_resp'],true);
	$received_item = json_decode($order['received_item']);
	$ebay_send_resp = json_decode($order['ebay_send_resp']);
	$ebay_shipped_resp = json_decode($order['ebay_shipped_resp']);

	echo "<tr>";
	echo '<td>',$order['id'],'</td>';//ebay_game_id
	echo '<td><a href="?',query_to_orders_page(['order_id'=>$order['order_id']]),'" target="_blank">',$order['order_id'],'</a></td>';
	echo '<td><a href="http://www.ebay.de/itm/',$order['ebay_game_id'],'" target="_blank"><div class="op-titles">',$order['name'],'</div></a></td>';
	echo '<td><a href="http://www.plati.com/itm/',$order['plati_id'],'" target="_blank">',$order['plati_id'],'</a></td>';
	echo '<td>',$order['first_name'],'</td>';
	echo '<td>',$order['email'],'</td>';
	echo '<td>',$invoice_resp->success?'<a href="'.$invoice_resp->inv->link.'" target="_blank" title="open plati.ru order page">'.$invoice_resp->success.'</a>':'<div title="'.$invoice_resp->retdesc.'">Fail</div>','</td>';
	echo '<td title="transaction id: ',$pay_resp['transaction_id'],'">',$pay_resp["success"],'</td>';
	echo '<td title="',_esc_attr($received_item->result),'">',$received_item->success,' : ',$received_item->typegood,'</td>';
	echo '<td>',strip_tags($order['out_of']),'</td>';
	echo '<td>',$order['email_send_resp']?'OK':'Fail','</td>';
	echo '<td>',$ebay_send_resp->Ack==='Success'?'OK':$ebay_send_resp->Ack,'</td>';
	echo '<td>',$ebay_shipped_resp->Ack==='Success'?'OK':$ebay_shipped_resp->Ack,'</td>';
	echo '<td>',$order['shipped_time']?date_shorter_dots($order['shipped_time']):'','</td>';
	echo "</tr>";

endforeach;
}
@table_inners($automaticArr);
echo "</table>";
?>