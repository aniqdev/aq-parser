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

$products_arr = [];
foreach ($orders as $products) {
	if (count($products) > 2) {
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

sa($orders);