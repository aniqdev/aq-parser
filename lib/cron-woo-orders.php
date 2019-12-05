<?php



$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
	'function' => 'ajax_hot_do_woocommerce_api_request',
	'method' => 'get',
	'endpoint' => "orders",
	'data' => [
		'per_page' => '100'
	],
]);

sa($item);

// развернули массив чтоб сохранять заказы в порядке их создания
$item['res'] = array_reverse($item['res']);

if (is_array($item['res']) && $item['res']) {

	foreach ($item['res'] as $order) {

		$order_id = $order['id'];

		$goods_html = '';
		foreach ($order['line_items'] as $line_item) {
			$goods_html .= $line_item['name'].' ('.$line_item['total'].' x '.$line_item['quantity'].')<br>';
		}
		$goods_html = _esc($goods_html);
		
		$goods_json = _esc(json_encode($order['line_items']));

		array_walk_recursive($order, function(&$item)
		{
			$item = _esc($item);
		});

		// continue;
		$set_query = "SET
			order_id = '$order[id]',
			status = '$order[status]',
			total_price = '$order[total]',
			currency = '$order[currency]',
			goods_html = '$goods_html',
			goods_json = '$goods_json',
			payment_method = '$order[payment_method]',
			first_name = '{$order['billing']['first_name']}',
			last_name = '{$order['billing']['last_name']}',
			email = '{$order['billing']['email']}',
			address = '{$order['billing']['address_1']}',
			charname = '{$order['billing']['city']}',
			postcode = '{$order['billing']['postcode']}',
			country = '{$order['billing']['country']}',
			date_created = '{$order['date_created']}',
			date_modified = '{$order['date_modified']}'";

		$check = arrayDB("SELECT id FROM woo_orders WHERE order_id = '$order_id'");

		if ($check) {
			arrayDB("UPDATE woo_orders $set_query WHERE order_id='$order_id'");
		}else{
			arrayDB("INSERT INTO woo_orders $set_query");

			// количество товаров в заказе
			$total_qtty = 0;
			foreach ($order['line_items'] as $item) {
				$total_qtty += (int)$item['quantity'];
			}

			$gig_order_id = DB::getInstance()->lastid();
			foreach ($order['line_items'] as $key => $item) {
				// continue;

				for ($i=0; $i < $item['quantity']; $i++) {

					$npp = ($key === 0 && $i === 0) ? 1 : ++$npp;
					
					arrayDB("INSERT INTO woo_order_items SET
						gig_order_id = '$gig_order_id',
						woo_order_id = '$order[id]',
						woo_order_item_id = '$item[id]',
						name = '$item[name]',
						product_id = '$item[product_id]',
						variation_id = '$item[variation_id]',
						quantity = '$item[quantity]',
						price = '$item[price]',
						subtotal_price = '$item[subtotal]',
						total_price = '$item[total]',
						npp = '$npp',
						total_qtty = '$total_qtty'
						");
				}
			}
		}

	}
}

