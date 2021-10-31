<style>
td{
	border: 1px solid #ccc;
	padding: 2px 6px;
}
</style>
<?php ini_get('safe_mode') or set_time_limit(1300);

//***********************************************************************************************
$xcel = readExcel('csv/CS-aktuell.xlsx');
$pairs_arr = [];
foreach ($xcel as $value) {
	if($value['B']) $pairs_arr[$value['A']] = $value['B'];
}
function is_pair_of($prod_id1, $prod_id2)
{
	global $pairs_arr;
	if (isset($pairs_arr[$prod_id1]) && strpos($pairs_arr[$prod_id1], $prod_id2) !== false) {
		return 'YES';
	}
	return 'no';
}
//***********************************************************************************************


$res = readExcel('csv/B3.xlsx');


unset($res[1]);
// sa($res);

$orders = [];
foreach ($res as $row) {
	// $orderId = $row['A'];
	// unset($row['A']);
	// $orders[$orderId][] = $row;
	if($row['A']) $orders[$row['A']][] = $row['B'];
}

// sa($orders);

$products_arr = [];

$total_orders = count($orders);
$proper_orders = 0;
foreach ($orders as $products) {
	if (count($products) > 1) {
		foreach ($products as $key => $product_id) {
			if(!isset($products_arr[$product_id])) $products_arr[$product_id] = [];
			foreach ($products as $key => $productId) {
				if($product_id == $productId) continue;
				if(!isset($products_arr[$product_id][$productId])) $products_arr[$product_id][$productId] = 1;
				else $products_arr[$product_id][$productId]++;
			}
		}
	}
}

foreach ($products_arr as &$product_arr) {
	arsort($product_arr);
}

$final_arr = [];
foreach ($products_arr as $key => $value) {
	$final_arr[$key] = array_shift($value);
}
arsort($final_arr);
foreach ($products_arr as $key => $value) {
	if($key) $final_arr[$key] = $value;
}
// sa($final_arr);
// return;

$fp = fopen('csv/similar-products-table.csv', 'w');
?>
<div class="container">
<a href="csv/similar-products-table.csv" download>download csv</a>
<table class="ppp-table">
	<tr>
		<th>prod 1</th>
		<th>prod 2</th>
		<th>pairs</th>
		<th>total</th>
		<th>is similar</th>
	</tr>
<?php
foreach ($final_arr as $prod_id1 => $value) {
	$total = 0;
	foreach ($value as $prod_id2 => $count) $total = $total + $count;
	foreach ($value as $prod_id2 => $count) {
		if ($count < 5) continue;
		$is_pair_of = is_pair_of( (string)$prod_id1, (string)$prod_id2);
		echo "<tr>";
			echo '<td>';
			echo $prod_id1;
			echo "</td>";
			echo '<td>';
			echo $prod_id2;
			echo '</td>';
			echo '<td>';
			echo $count;
			echo '</td>';
			echo '<td>';
			echo $total;
			echo '</td>';
			echo '<td>';
			echo $is_pair_of;
			echo '</td>';
		echo "</tr>";

		fputcsv($fp, [
			$prod_id1,
			$prod_id2,
			$count,
			$total,
			$is_pair_of,
		], ';');
	}
}
fclose($fp);
?>
</table>
</div>
