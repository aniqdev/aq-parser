<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TVT8N2C"
	height="0" width="0" style="display:none;visibility:hidden"></iframe>
<?php



$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					WHERE status = 'processing'
					LIMIT 500");


$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					WHERE status = 'processing' 
						AND `show`='no' 
						AND shipped_time = '0'
						AND ExecutionMethod='default' 
					LIMIT 500");

$orders = arrayDB( "SELECT *
                    FROM woo_orders 
                    LEFT JOIN woo_order_items
                    ON woo_orders.id = woo_order_items.gig_order_id 
                    WHERE status = 'processing'
                    AND `show` = 'yes' 
                    LIMIT 500");

sa(count($orders));
foreach ($orders as $key => $order) {
	sa($order);
	// var_dump(is_eve_by_title($order['name']));
}


sa(count($orders));
sa($orders);

return
define('TRUSTED_CLIENTS_FILE', 'Files/_test2.json');

$client_id = '8108';

$is_trusted = is_trusted($client_id);

var_dump($is_trusted);

set_trusted($client_id, false);

$is_trusted = is_trusted($client_id);

var_dump($is_trusted);


// for ($i=0; $i < 10000; $i++) {
// 	set_trusted(rand(1000, 10000), false);
// }




function is_trusted($client_id)
{
	$clients_arr = file(TRUSTED_CLIENTS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	return in_array((int)$client_id, $clients_arr);
}


function set_trusted($client_id, $is_trusted)
{
	$clients_arr = file(TRUSTED_CLIENTS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	if ($is_trusted) {
		$clients_arr[] = $client_id;
	}else{
		$clients_arr = array_flip($clients_arr);
		unset($clients_arr[$client_id]);
		$clients_arr = array_flip($clients_arr);
	}

	file_put_contents(TRUSTED_CLIENTS_FILE, implode(PHP_EOL, $clients_arr));
}

return;
$looking_for = '4050';


echo "<hr>";

$arr = '';

for ($i=0; $i < 10000; $i++) { 
	$arr .= rand(1000, 10000) . PHP_EOL;
}

$start = microtime(true);

$res = file_put_contents('Files/_test2.json', $arr);

var_dump($res);

$arr = file('Files/_test2.json');
var_dump(in_array((int)$looking_for, $arr));

for ($i=0; $i < 10; $i++) { 
	echo ",".$arr[$i];
}

$time = microtime(true) - $start;
sa($time);




echo "<hr>";



$arr = [];

for ($i=0; $i < 10000; $i++) { 
	$arr[] = rand(1000, 10000);
}

$start = microtime(true);

$res = file_put_contents('Files/_test1.json', json_encode($arr));

var_dump($res);

$file = file_get_contents('Files/_test1.json');

$arr = json_decode($file);
var_dump(in_array($looking_for, $arr));

for ($i=0; $i < 10; $i++) { 
	echo ",".$arr[$i];
}

$time = microtime(true) - $start;
sa($time);

echo "<hr>";