<?php


$is_fresh = arrayDB("SELECT last_seen, 
if(last_seen > now() - interval 1 day, 1, 0) as fresh
from awaiting_orders order by last_seen desc limit 1");

if (!$is_fresh[0]['fresh']) {
	echo '<div class="alert alert-danger" role="alert"> <strong>Oh snap!</strong> Отсутствуют свежие данные! </div>';
}


$res = arrayDB("SELECT awaiting_orders.id, gig_order_id, UserID, ItemID, Title, TransactionID, PaidTime, ShippedTime, 7days_sent, 14days_sent, ShippingAddress as Country, last_seen, TIMEDIFF(NOW(), last_seen) as 'how long'
	from awaiting_orders
	left join (SELECT id as gig_order_id,order_id,PaidTime,ShippedTime,ShippingAddress FROM ebay_orders WHERE LastModTime > NOW() - INTERVAL 70 DAY) tt
	on awaiting_orders.OrderLineItemID = tt.order_id
	where EndTime > NOW() - INTERVAL 60 DAY
	 	and EndTime + INTERVAL 59 DAY > last_seen
		and last_seen < NOW() - INTERVAL 1 DAY
		and bonus_sent = 0
	ORDER BY last_seen DESC");


$res = array_map(function($el)
{	
	$el['Country'] = @json_decode($el['Country'],true)['Country'];
	return $el;
}, $res);

$first_row_thead = true;

if(!$res) return;
?>
<table class="ppp-table-collapse" style="margin:auto"><tr>
	<th>№</th>
	<th>UserID</th>
	<th>ItemID</th>
	<th>TransactionID</th>
	<th>PaidTime</th>
	<th>ShippedTime</th>
	<th>7days_sent</th>
	<th>14days_sent</th>
	<th>Country</th>
	<th>last_seen</th>
	<th>how long</th>
	<th title="remove order from the table">R</th>
</tr>
<?php
foreach ($res as $kr => $row) {
  echo '<tr id="id'.$row['TransactionID'].$row['ItemID'].'"><td>',$kr+1,'</td>';
	echo '<td><a href="?action=ebay-messages&correspondent=',$row['UserID'],'" target="_blank">',$row['UserID'],'</a></td>';
	if($row['gig_order_id']) echo '<td title="',htmlspecialchars($row['Title']),'"><a href="?action=orders-page&list_type=all&order_id=',$row['gig_order_id'],'&modal_type=chat&item_id=',$row['ItemID'],'&offset=0&limit=100" target="_blank">',$row['ItemID'],'</a></td>';
	else echo '<td title="',htmlspecialchars($row['Title']),'">',$row['ItemID'],'</td>';
	echo '<td>',$row['TransactionID'],'</td>';
	echo '<td>',$row['PaidTime'],'</td>';
	echo '<td>',$row['ShippedTime'],'</td>';
	echo '<td>',$row['7days_sent'],'</td>';
	echo '<td>',$row['14days_sent'],'</td>';
	echo '<td>',$row['Country'],'</td>';
	echo '<td>',$row['last_seen'],'</td>';
	echo '<td>',$row['how long'],'</td>';
	echo '<td><button class="js-remove-row" title="bonus sent" name="'.$row['TransactionID'].$row['ItemID'].'" value="'.$row['id'].'">×</button></td>';
  echo '</tr>';
}
echo '</table>';
?>

<script>$(function(){AwaitingList.init()})</script>