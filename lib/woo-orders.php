<?php




$orders_arr = arrayDB("SELECT * FROM woo_orders ORDER BY id DESC LIMIT 100");


?>

<link rel="stylesheet" href="css/woo-orders.css?t=<?= filemtime(ROOT.'/css/woo-orders.css') ?>">
<div class="container-fluid">
	<h1>WooCommerce orders</h1>
	<table class="table table-striped aqs-adaptive-table">
		<tr>
			<th>order id</th>
			<th>status</th>
			<th>total price</th>
			<th>goods</th>
			<th>payment method</th>
			<th>customer</th>
			<th>email</th>
			<th>country</th>
			<th>charname</th>
			<th>created</th>
			<th>modified</th>
		</tr>
<?php

foreach ($orders_arr as $order) {
	echo '<tr>';
	echo '<td data-title="order id">'.$order['order_id'].'</td>';
	echo '<td data-title="status">'.$order['status'].'</td>';
	echo '<td data-title="total price">'.$order['total_price'].'</td>';
	echo '<td data-title="goods">'.$order['goods_html'].'</td>';
	echo '<td data-title="payment method">'.$order['payment_method'].'</td>';
	echo '<td data-title="customer">'.$order['first_name'].'</td>';
	echo '<td data-title="email">'.$order['email'].'</td>';
	echo '<td data-title="country">'.$order['country'].'</td>';
	echo '<td data-title="charname">'.$order['charname'].'</td>';
	echo '<td data-title="date created" title="'.$order['date_created'].'">'.date_shorter($order['date_created']).'</td>';
	echo '<td data-title="date modified" title="'.$order['date_modified'].'">'.date_shorter($order['date_modified']).'</td>';
	echo '</tr>';
}

?>
	</table>
</div>