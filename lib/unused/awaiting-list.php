<?php


$is_fresh = arrayDB("SELECT last_seen, 
if(last_seen > now() - interval 1 day, 1, 0) as fresh
from awaiting_orders order by last_seen desc limit 1");

if (!$is_fresh[0]['fresh']) {
	echo '<div class="alert alert-danger" role="alert"> <strong>Oh snap!</strong> Отсутствуют свежие данные! </div>';
}


$res = arrayDB("SELECT awaiting_orders.id, UserID,
 ItemID, TransactionID,
  PaidTime, ShippedTime, 7days_sent, 14days_sent, ShippingAddress as Country, last_seen, TIMEDIFF(NOW(), last_seen) as 'how long', EndTime, 
	IF(EndTime + INTERVAL 60 DAY < NOW() and EndTime + INTERVAL 59 DAY < last_seen, 'old.', '') as '2 month'
	-- ,EndTime + INTERVAL 59 DAY as '59 days', EndTime + INTERVAL 60 DAY as '60 days', NOW()
	from awaiting_orders
	left join (SELECT order_id,PaidTime,ShippedTime,ShippingAddress FROM ebay_orders WHERE LastModTime > NOW() - INTERVAL 70 DAY) tt
	on awaiting_orders.OrderLineItemID = tt.order_id
	ORDER BY EndTime ASC");


$res = array_map(function($el)
{	
	$el['Country'] = @json_decode($el['Country'],true)['Country'];
	return $el;
}, $res);

$first_row_thead = true;

echo '<table class="ppp-table-collapse" style="margin:auto"><tr>';
if($first_row_thead){
	echo '<th>№</th>';
	foreach ($res[0] as $key => $value) echo "<th>",$key,"</th>";
}else{
	echo '<td>1</td>';
	foreach ($res[0] as $val) echo "<td>",$val,"</td>";
}
echo "</tr>";
foreach ($res as $kr => $row) {
	if(!$first_row_thead && $kr === 0) continue;
	echo '<tr><td>',$kr+1,'</td>';
	foreach ($row as $kc => $col) {
		echo '<td>',$col,'</td>';
	}
	echo '</tr>';
}
