<?php





sa(microtime());
sa(microtime(1));





return;
$payload = [
  "user_id" => "123",
];

$token = jwt_token_get_lite($payload);

sa($token);

$check = jwt_token_check_lite($token);

var_dump($check);

$data = jwt_token_data_lite($token);

xa($data);

$json = base64url_decode('eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ');

sa(json_decode($json));




$header = json_encode([
  "alg" => "HS256",
  "typ" => "JWT"
]);
$input1 = base64url_encode($header);

$input2 = base64url_encode(json_encode([
  "sub" => "1234567890",
  "name" => "John Doe",
  "iat" => 1516239022
])); // JSON_PRETTY_PRINT

$secret = 'hello';

$sign = hash_hmac('sha256', $input1.'.'.$input2, $secret, true);

$sign = base64url_encode($sign);

$token = $input1.'.'.$input2.'.'.$sign;

$res64 = hash_hmac('sha256', $input1.'.'.$input2, base64url_encode($secret));
// $res64 = base64url_encode($res64);

sa($token);
sa($header);
sa($input1);
sa($input2);
sa($sign);
sa($res64);
sa(base64url_encode($res64));


return;
$res = Ebay_shopping2::findItemsAdvanced_moda($categoryId = '169291', $page = 1, $perPage = 100);

$res = json_decode($res,1);

sa($res);





return;
/**
 * 
 */
class ClassName3
{
	
	function __construct()
	{
		# code...
	}

	public function FunctionName3($value='')
	{
		echo "FunctionName4";
	}
}


/**
 * 
 */
class ClassName2 extends ClassName3
{
	
	function __construct()
	{
		# code...
	}

	public function FunctionName2($value='')
	{
		echo "FunctionName2";
	}
}


/**
 * 
 */
class ClassName1 extends ClassName2
{
	
	function __construct()
	{
		# code...
	}

	public function FunctionName1($value='')
	{
		echo "FunctionName1";
	}
}

sa(get_class_methods('ClassName1'));






return;
$moda_arr = arrayDB("SELECT * FROM moda_list LIMIT 20000,20");


$results = [];
foreach ($moda_arr as $key => $moda) {
	$moda_meta = get_moda_meta($moda['id'], $meta_key = false);

	// sa($moda_meta);
	$results[] = [
		'moda_id' => $moda['id'],
		'QuantitySold' => $moda_meta['QuantitySold'],
		'HitCount' => $moda_meta['HitCount'],
	];
}

sa($results);







return;
$itemId = '292910188330';

$res = Ebay_shopping2::getSingleItem_moda($itemId, $as_array = 1);

// echo $res['Item']['Description'];
sa($res);

// $res['Item']['Description'] = 'Description HERE!!!';

foreach ($res['Item']['Variations'] as $key => $value) {
	// sa($key);
	// sa($value);
}

// sa($res);
// sa($res['Item']['Variations']['VariationSpecificsSet']['NameValueList']);

return;
$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					-- WHERE status = 'processing'
					LIMIT 500");

sa(count($orders));


foreach ($orders as $key => $order) {
	unset($order['goods_json']);
	sa($order);
}



return;
$smarty = new Smarty();

if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
    $pro = 'https';
} else {
    $pro = 'http';
}
$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
$current_url =  $pro."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];

$smarty->assign("current_url",$current_url);
$smarty->display('test.tpl');

return
// return
// $item_id = '253453544903';

// $specs = parse_item_specifics($item_id);

// sa($specs);

// $specs['Kurzbeschreibung'] = explode(',', $specs['Kurzbeschreibung']);
// $specs['Zusammensetzung'] = explode(',', $specs['Zusammensetzung']);
// $specs['Analytische Bestandteile und Gehalte'] = explode(',', $specs['Analytische Bestandteile und Gehalte']);
// // unset($specs['Artikelzustand']);

// sa($specs);

// return
// $resp = Cdvet::updateItemSpecifics($item_id, $specs);

// sa($resp);


// return
$res = Cdvet::updateItemSubtitle('21021187702', 'CdVet');

sa($res);



return;
$res = Ebay_shopping2::getSingleItem('121946647051');


sa(json_decode($res, 1));

