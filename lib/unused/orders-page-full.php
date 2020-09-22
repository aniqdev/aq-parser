<div class="ppp-block" style="max-width:100%;">
<table class="orders-table">
	<tr>
		<th>#</th>
		<th>Order id</th>
		<th>Order status</th>
		<th>Amnt</th>
		<th>Game title</th>
		<th>Price</th>
		<th>Item ID</th>
		<th>Total</th>
		<th>Payment Method</th>
		<th>BuyerUserID</th>
		<th>Buyer email</th>
		<th>Buyer name</th>
		<th>Paid Time</th>
		<th>Shipped Time</th>
		<th>Pt</th>
		<th>St</th>
	</tr>
<?php

$orders = arrayDB("SELECT * FROM ebay_orders ORDER BY id DESC LIMIT 500");

foreach ($orders as $key => $order) {
	$goods = json_decode($order['goods'], true);
	//showArray($goods);
	echo '<tr>',
			'<td>',$key+1,'</td>',
			'<td>',$order['order_id'],'</td>',
			'<td>',$order['OrderStatus'],'</td>',
			'<td>';foreach($goods as $g) echo $g['amount'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo $g['title'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo $g['price'],'<br>';echo '</td>',
			'<td>';foreach($goods as $g) echo $g['itemid'],'<br>';echo '</td>',
			'<td>',$order['total_price'],'</td>',
			'<td>',$order['PaymentMethod'],'</td>',
			'<td>',$order['BuyerUserID'],'</td>',
			'<td>',$order['BuyerEmail'],'</td>',
			'<td>',$order['BuyerFirstName'],' ',$order['BuyerLastName'],'</td>',
			'<td>',$order['PaidTime'],'</td>',
			'<td>',$order['ShippedTime'],'</td>',
			'<td><button>*</button></td>',
			'<td><button>*</button></td>',
		 '</tr>';
}

?>
</table>
</div>