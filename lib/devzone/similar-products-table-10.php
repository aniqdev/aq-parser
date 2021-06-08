<style>
td{
	border: 1px solid #ccc;
	padding: 2px 6px;
}
</style>
<?php ini_get('safe_mode') or set_time_limit(1300);




$res = readExcel('csv/B2.xlsx');


unset($res[1]);
// sa($res);

$orders = [];
foreach ($res as $row) {
	// $orderId = $row['A'];
	// unset($row['A']);
	// $orders[$orderId][] = $row;
	$orders[$row['A']][] = $row['B'];
}

// sa($orders);
sa('total_orders: ' . count($orders));
echo "<table>";
for ($i=2; $i <= 15; $i++) {
	$pair_orders_arr = [];
	$pair_arr = [];
	$products_arr = [];
	foreach ($orders as $order => $products) {
		if (count($products) > 1) {
			foreach ($products as $key => $product_id) {
				if(!isset($products_arr[$product_id])) $products_arr[$product_id] = [];
				foreach ($products as $key => $productId) {
					if($product_id == $productId) continue;
					if(!isset($products_arr[$product_id][$productId])){
						$products_arr[$product_id][$productId] = 1;
						//---------------------------------------------
						$pair_id = get_pair_id($product_id, $productId);
						if(!isset($pair_orders_arr[$pair_id])) $pair_orders_arr[$pair_id] = [];
						if(!isset($pair_orders_arr[$pair_id][$order])) $pair_orders_arr[$pair_id][$order] = 1;
						else $pair_orders_arr[$pair_id][$order]++;
					}
					else{
						$products_arr[$product_id][$productId]++;
						//---------------------------------------------
						$pair_id = get_pair_id($product_id, $productId);
						if(!isset($pair_orders_arr[$pair_id][$order])) $pair_orders_arr[$pair_id][$order] = 1;
						else $pair_orders_arr[$pair_id][$order]++;
						if ($products_arr[$product_id][$productId] === $i) {
							$pair_arr[$pair_id] = &$pair_orders_arr[$pair_id];
						}
					}
				}
			}
		}
	}

	$pair_orders_arr_final = [];
	foreach ($pair_arr as $pair_id => $orderss) {
		foreach ($orderss as $order => $countX2) {
			if(!isset($pair_orders_arr_final[$order])) $pair_orders_arr_final[$order] = 0;
			$pair_orders_arr_final[$order] += 1;
		}
	}

	
	$pair_orders_count = count($pair_orders_arr_final);
	// sa($i . ' - ' . $pair_orders_count);
	echo "<tr>";
		echo '<td>';
		echo $i;
		echo "</td>";
		echo '<td>';
		echo $pair_orders_count;
		echo '</td>';
	echo "</tr>";
}
echo "</table>";
	// sa($pair_arr);
	// arsort($pair_orders_arr_final);
	// sa($pair_orders_arr_final);



return;

// сортировка внутри продукта-------------
foreach ($products_arr as &$product_arr) {
	arsort($product_arr);
}

// сортировка всех продуктов по максимальному совпадению
// -----------------------------------------------------
$final_arr = [];
foreach ($products_arr as $key => $value) {
	$final_arr[$key] = array_shift($value);
}
arsort($final_arr);
foreach ($products_arr as $key => $value) {
	$final_arr[$key] = $value;
}
// sa($final_arr);
// -----------------------------------------------------
// sa('16 of a ' . count($final_arr));
$index = 1;
$fp = fopen('csv/similar-products-table.csv', 'w');

echo "<table>";
foreach ($final_arr as $key => $value) {
	// if($index > 16) break; $index++;
	$total = 0;
	foreach ($value as $prod_id => $count) $total = $total + $count;
	foreach ($value as $prod_id => $count) {
		if ($count > 4) {
			// echo "<tr>";
			// 	echo '<td>';
			// 	echo $key;
			// 	echo "</td>";
			// 	echo '<td>';
			// 	echo $prod_id;
			// 	echo '</td>';
			// 	echo '<td>';
			// 	echo $count;
			// 	echo '</td>';
			// 	echo '<td>';
			// 	echo $total;
			// 	echo '</td>';
			// echo "</tr>";
			fputcsv($fp, [
				$key,
				$prod_id,
				$count,
				$total,
			], ';');
		}
	}
}
echo "</table>";

fclose($fp);







function get_pair_id($id1, $id2)
{
	$pair_id = [$id1, $id2];
	sort($pair_id);
	return implode('-', $pair_id);
}