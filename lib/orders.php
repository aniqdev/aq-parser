<h3>Orders</h3>
<pre>
<?php

$ord_obj = new EbayOrders;

$ord_arr = $ord_obj->getOrders(['order_status'=>'All','NumberOfDays'=>2]);

//print_r($ord_arr);

if(isset($_POST['OrderID'])){
	if(isset($_POST['MarkAsShipped'])){
		//помечаем как отправленный
		if($_POST['MarkAsShipped'] === "+"){ $ord_obj->MarkAsShipped($_POST['OrderID']); }
		//помечаем как НЕ отправленный
		else { $ord_obj->MarkAsShipped($_POST['OrderID'], "false"); }
	}
	elseif(isset($_POST['MarkAsPaid'])) {
		//помечаем как оплаченный
		if($_POST['MarkAsPaid'] === "+"){ $ord_obj->MarkAsPaid($_POST['OrderID']); }
		//помечаем как НЕ оплаченный
		else { $ord_obj->MarkAsPaid($_POST['OrderID'], "false"); }
	}
}

?>
</pre>
<div class="ppp-block" style="max-width:100%;">
<table class="orders-table">
	<tr>
		<th>#</th>
		<th>Order id</th>
		<th>Game title</th>
		<th>Price</th>
		<th>BuyerUserID</th>
		<th>Buyer email</th>
		<th>Buyer name</th>
		<th>PaidTime</th>
		<th>ShippedTime</th>
		<th>Pt</th>
		<th>St</th>
	</tr>
<?php
foreach ($ord_arr['OrderArray']['Order'] as $key => $value) {
	//унифицируем структуру данных для заказов с одним и множеством товаров
	$transactions = [];
	if(!isset($value['TransactionArray']['Transaction'][0])) $transactions[0] = $value['TransactionArray']['Transaction'];
	else $transactions = $value['TransactionArray']['Transaction'];


	echo "<tr><td>",$key+1,"</td><td>", $value['OrderID'], "</td><td>";
	foreach($transactions as $transaction){
		echo "<p>".$transaction['Item']['Title']."</p>";
	}
	echo 	"</td><td>";		
	foreach($transactions as $transaction){
		echo "<p>".$transaction['TransactionPrice']."</p>";
	}
	echo "</td>
		<td>",$value['BuyerUserID'],"</td>
		<td>",$transactions[0]['Buyer']['Email'],"</td>
		<td>",$transactions[0]['Buyer']['UserFirstName']." ".$transactions[0]['Buyer']['UserLastName'],"</td>
		<td>",@$value['PaidTime'],"</td>
		<td>",@$value['ShippedTime'],"</td>
		<td><form method='POST'>
			<input type='submit' name='MarkAsPaid' value='".(isset($value['PaidTime']) ? "-" : "+")."'>
			<input type='hidden' name='OrderID' value='".$value['OrderID']."'>
		</form></td>
		<td><form method='POST'>
			<input type='submit' name='MarkAsShipped' value='".(isset($value['ShippedTime']) ? "-" : "+")."'>
			<input type='hidden' name='OrderID' value='".$value['OrderID']."'>
		</form></td>
		</tr>";
}

?>
</table>
</div>