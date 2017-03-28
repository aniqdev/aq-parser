<?php

aqs_pagination('ebay_automatic_log');

$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
$limit = @$_GET['limit'] ? (int)$_GET['limit'] : 10;

if(isset($_GET['offset']) && isset($_GET['limit'])){
	$limit = 'LIMIT '.(int)$_GET['offset'].','.(int)$_GET['limit'];
}else{
	$limit = 'LIMIT 10';
}

$automaticArr = arrayDB("SELECT 
    ebay_orders.goods,
    ebay_orders.BuyerUserID,
    ebay_orders.BuyerEmail,
    ebay_order_items.title,
    ebay_automatic_log.*
FROM ebay_automatic_log
JOIN ebay_orders
ON ebay_automatic_log.order_id=ebay_orders.id
JOIN ebay_order_items
ON ebay_automatic_log.order_item_id=ebay_order_items.id
ORDER BY ebay_automatic_log.id DESC $limit");

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
		<th title="Buyer User ID">BuyerUserID</th>
		<th title="Buyer Email">BuyerEmail</th>
		<th title="Plati.ru invoice result">invoiced</th>
		<th title="Result of payment">paid</th>
		<th title="Product received">received</th>
		<th title="Email message">msg_email</th>
		<th title="Email sending result">emaild</th>
		<th title="Ebay sending result">ebayd</th>
		<th title="Marked as shipped">mrkd</th>
		<th title="Timestamp">Timestamp</th>
	</tr>
<?php
function table_inners($automaticArr){

foreach ($automaticArr as $key => $automatie):

	$goods = json_decode($automatie['goods']);
	$invoice_resp = json_decode($automatie['invoice_resp']);
	$pay_resp = json_decode($automatie['pay_resp'],true);
	$received_item = json_decode($automatie['received_item']);
	$ebay_send_resp = json_decode($automatie['ebay_send_resp']);
	$ebay_shipped_resp = json_decode($automatie['ebay_shipped_resp']);

	echo "<tr>";
	echo '<td>',$automatie['id'],'</td>';//ebay_game_id
	echo '<td><a href="?',query_to_orders_page(['order_id'=>$automatie['order_id']]),'" target="_blank">',$automatie['order_id'],'</a></td>';
	echo '<td><a href="http://www.ebay.de/itm/',$automatie['ebay_game_id'],'" target="_blank"><div class="op-titles">',$automatie['title'],'</div></a></td>';
	echo '<td><a href="http://www.plati.com/itm/',$automatie['plati_id'],'" target="_blank">',$automatie['plati_id'],'</a></td>';
	echo '<td>',$automatie['BuyerUserID'],'</td>';
	echo '<td>',$automatie['BuyerEmail'],'</td>';
	echo '<td>',$invoice_resp->success?'<a href="'.$invoice_resp->inv->link.'" target="_blank" title="open plati.ru order page">'.$invoice_resp->success.'</a>':'<div title="'.$invoice_resp->retdesc.'">Fail</div>','</td>';
	echo '<td title="transaction id: ',$pay_resp['transaction_id'],'">',$pay_resp["success"],'</td>';
	echo '<td title="',$received_item->result,'">',$received_item->success,' : ',$received_item->typegood,'</td>';
	echo '<td title="',htmlspecialchars($automatie['msg_email']),'">',substr(strip_tags($automatie['msg_email']),0,5),'</td>';
	echo '<td>',$automatie['email_send_resp']?'OK':'Fail','</td>';
	echo '<td>',$ebay_send_resp->Ack==='Success'?'OK':$ebay_send_resp->Ack,'</td>';
	echo '<td>',$ebay_shipped_resp->Ack==='Success'?'OK':$ebay_shipped_resp->Ack,'</td>';
	echo '<td>',$automatie['created_at']?date_shorter_dots($automatie['created_at']):'','</td>';
	echo "</tr>";

endforeach;
}
@table_inners($automaticArr);
echo "</table>";
?>