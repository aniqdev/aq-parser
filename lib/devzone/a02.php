<?php ini_set('max_execution_time', 300);

$_GET['similar_products'] = json_decode(file_get_contents('csv/similar_products_18-06.json'), true);
// sa($_GET['similar_products']);
// return;
function is_similar_items($item1, $item2)
{
	if($item1 === $item2) return false;
	if(!isset($_GET['similar_products'][$item1])) return false;
	if (in_array($item2, $_GET['similar_products'][$item1])) {
		return true;
	}
	return false;
}

// sa($similar_products);

// $artikles = readExcel('csv/Artikel.xlsx');

// sa($artikles);

$orders = readExcel('csv/orders .xlsx');

// sa($orders);

$orders_keys = [];
foreach ($orders as $key => $order){
	if($order['A']) $orders_keys[$order['A']][] = $order['B'];
}
// sa(count($orders_keys));
// sa($orders_keys);

// $orders_items_count = [];
// foreach ($orders_keys as $order => $items){
// 	$orders_items_count[$order] = count($items);
// }
// arsort($orders_items_count);
// sa($orders_items_count);
// return;


?>
<div class="container">
	<pre>
		Таблица содержит заказы зодержащие совпадения из Artike.xlsx
		Всего заказов: <?php count($orders_keys) ?>
		Заказов с совпадениями: 544
	</pre>
	<table class="ppp-table">
		<?php $key = 0; $affected = 0;
		foreach ($orders_keys as $order => $items) {
			// if($key > 1000) break; $key++;
			$similar_count = 0;
			$similar_pairs = [];
			foreach ($items as $item1) {
				foreach ($items as $item2) {
					if (is_similar_items($item1, $item2)) {
						$similar_count++;
						$similar_pairs[] = "$item1 ($item2)";
					}
				}
			}
			if(!$similar_count) continue;
			$affected++;
			echo '<tr>';
			echo '<td>';
			echo $order;
			echo '</td>';
			echo '<td>';
			echo implode('<br>', $items);
			echo '</td>';
			echo '<td>';
			echo $similar_count;
			echo '</td>';
			echo '<td>';
			echo implode('<br>', $similar_pairs);
			echo '</td>';
			echo '</tr>';
		}
		?>
	</table>
	<?php //sa('Total: ' . $affected); ?>
</div>
<?php
// $similar_products = [];
// foreach ($artikles as $key => $row) {
// 	if ($key != 1 && $row['B']) {
// 		$similar_products[$row['A']] = explode('|', $row['B']);
// 	}
// }
// sa($similar_products);
// file_put_contents('csv/similar_products_18-06.json', json_encode($similar_products, 128));




return;
?><table>
	<tr>
		<td>Age, month[1-11]</td>
		<td><input value="1" type="text" id="months"></td>
	</tr>
	<tr>
		<td>Bread size</td>
		<td>
			<select class="t3" value="0" name="breed" id="breed">
				<option value="1">Средняя (вес взрослой собаки 20кг)</option>
				<option value="2">Крупная (вес взрослой собаки 35 кг)</option>
				<option value="3">Очень крупная (вес взрослой собаки 60 кг)</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" id="do_calc" value="Calculate"></td>
	</tr>
	<tr>
		<td>Weight, kg</td>
		<td><input type="text" readonly="readonly" id="output_weight"></td>
	</tr>
	<tr>
		<td>Percentage of weight</td>
		<td><input type="text" readonly="readonly" id="output_percent"></td>
	</tr>
</table>

<script>
document.all.do_calc.onclick = function() {

var i = 0
var breed = document.all.breed.value
var months = +document.all.months.value
if(months <= 0) mon6ths = 1
if(months > 11) months = 11
document.all.months.value = months
var data = [
	{"pkID": 2527,"months": 1,"weightmedium": 1.8,"percentmedium": 9,"weightlarge": 2.5,"percentlarge": 7,"weightgiant": 3.6,"percentgiant": 6},
	{"pkID": 2528,"months": 2,"weightmedium": 4.4,"percentmedium": 22,"weightlarge": 7,"percentlarge": 29,"weightgiant": 8.4,"percentgiant": 14},
	{"pkID": 2529,"months": 3,"weightmedium": 7.4,"percentmedium": 37,"weightlarge": 12.3,"percentlarge": 35,"weightgiant": 15.6,"percentgiant": 26},
	{"pkID": 2530,"months": 4,"weightmedium": 10.4,"percentmedium": 52,"weightlarge": 16.8,"percentlarge": 48,"weightgiant": 22.8,"percentgiant": 38},
	{"pkID": 2531,"months": 6,"weightmedium": 14,"percentmedium": 70,"weightlarge": 22.8,"percentlarge": 65,"weightgiant": 36,"percentgiant": 60},
	{"pkID": 2532,"months": 12,"weightmedium": 19,"percentmedium": 95,"weightlarge": 30.8,"percentlarge": 88,"weightgiant": 48,"percentgiant": 80}
]
for(var i=0; i < data.length-1; ++i) {
	if(data[i].months <= months)
	if (data[i+1].months > months) {
		switch(breed)
		{	
			case "1":
			setWeight( data[i].weightmedium + (data[i+1].weightmedium-data[i].weightmedium)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			setPercent( data[i].percentmedium + (data[i+1].percentmedium-data[i].percentmedium)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			break;
			case "2":
			setWeight( data[i].weightlarge + (data[i+1].weightlarge-data[i].weightlarge)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			setPercent( data[i].percentlarge + (data[i+1].percentlarge-data[i].percentlarge)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			break;
			case "3":
			setWeight( data[i].weightgiant + (data[i+1].weightgiant-data[i].weightgiant)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			setPercent( data[i].percentgiant + (data[i+1].percentgiant-data[i].percentgiant)*(months-data[i].months)/(data[i+1].months - data[i].months) );
			break;
		}
		break;
	}		
}
}
function setWeight($val){
	document.all.output_weight.value = Math.round(($val + Number.EPSILON) * 100) / 100
}
function setPercent($val){
	document.all.output_percent.value = Math.round(($val + Number.EPSILON) * 100) / 100
}
</script>

<?php 

return;
?>
<style>
#test{
	position:absolute;
	/* left:-10000px; */
	/* top:-10000px; */
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	box-sizing:border-box;
	overflow:auto;
	white-space:pre-wrap;
}
</style>
<div id="count"></div>
<textarea id="t" cols="30" rows="10">Можно ввести фразу длиной в несколько тысяч символов и в зависимости от ширины textarea — в результате может быть разное количество строк. При этом количество переводов строки при таком вводе равно нулю, так как пользователь не нажимал "ввод". Я имел ввиду такую ситуацию.</textarea>
<script>
$(function () {
	var cl = console.log
	var div = $('<div id="test">').appendTo($(document.body)),
		textarea = $('#t').get(0),
		cs = getComputedStyle(textarea, null),
		lh = parseFloat(cs.lineHeight),
		styles = {};
cl(cs.paddingBottom)
// cl(textarea)
	if (!lh) {
		div.html(' ');
		lh = div.get(0).clientHeight - parseInt(cs.paddingTop) - parseInt(cs.paddingBottom);
	}

	$([
		'paddingTop',
		'paddingRight',
		'paddingBottom',
		'paddingLeft',
		'fontSize',
		'fontFamily',
		'lineHeight',
		'transform'
	]).each(function (index, property) {
		styles[property] = cs[property];
	});

	textarea.addEventListener('mouseup', handler, false);
	handler();
	textarea.addEventListener('DOMAttrModified', handler, false);

	function handler (e) {
		styles.width = textarea.clientWidth;
		div.html(textarea.value.replace(/[\r\n]+/, '<br>')).css(styles);
		$('#count').html(Math.round(div.get(0).clientHeight / lh));
	}
});
</script>
<?php ini_get('safe_mode') or set_time_limit(1300);






return;
$res = readExcel('csv/B1.xlsx');


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

foreach ($products_arr as &$product_arr) {
	arsort($product_arr);
}

$final_arr = [];
foreach ($products_arr as $key => $value) {
	$final_arr[$key] = array_shift($value);
}
arsort($final_arr);
foreach ($products_arr as $key => $value) {
	$final_arr[$key] = $value;
}
sa($final_arr);





return;
$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => true]);

// $feed_new = file_get_contents('cdvet/cdvet-feed-3239e.json');

// $feed_new = json_decode($feed_new, true);

$feed_items = [];
foreach ($feed_new as $key => $value) {
	// if(is_dev() && $key > 60) break;
	$feed_items[$value[14]][] = $value;
}
// foreach ($feed_items as $key => &$feed_item) {
// 	uasort($feed_item, 'cs_cmp');
// }

sa(count($feed_items));

sa($feed_items);

foreach ($feed_items as $link => $variants) {
	if (count($variants) > 1){ // variable

	}else{ // single

	}
}






return;
$feed_new = csvToArr('https://www.cdvet.de/backend/export/index/productckeck?feedID=47&hash=a4dc5afc43b82eefd412334d8ed3239e', ['max_str' => 0,'encoding' => 'windows-1250', 'del_first' => true]);

// $feed_new = file_get_contents('cdvet/cdvet-feed-3239e.json');

// $feed_new = json_decode($feed_new, true);

$feed_items = [];
$res = [];
$units = [];
foreach ($feed_new as $key => $feed_item) {

	// if(is_dev() && $key > 60) break;
	// $feed_items[$feed_item[14]][] = $feed_item;
	$unit_name = $feed_item[7];
	$units[$unit_name]++;
	$v_base = '100 '.$feed_item[7];
	if($feed_item[7] === 'kg') $v_base = '1 kg';
	if($feed_item[7] === 'l') $v_base = '1 l';
	if($feed_item[7] === 'Stck.') $v_base = '1 Stck.';
	$res[$feed_item[14]] = [
		'volume' => $feed_item[8].' '.$feed_item[7],
		'v_base' => $v_base,
	];
}

sa($units);
sa($res);



return;
$json = file_get_contents('./csv/input-last.json');

$json = json_decode($json, 1);

sa($json);

$json = json_encode($json, JSON_PRETTY_PRINT);

file_put_contents('./csv/input-last.json', $json);







return;
// sa(explode('.', 'string'));

$payload = [
  "user_id" => "234",
];

$token = jwt_token_get_lite($payload);

sa($token);

$check = jwt_token_check_lite($token);

var_dump($check);

$data = jwt_token_data_lite($token);

xa($data);

$json = base64url_decode('eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ');

sa(json_decode($json));







return;
$str = 'cdVet® EquiGreen HuminoVet Pulver 0124kg Pferd Hygiene/Reinigung Spezialtonerden';

$res = preg_replace('/\D/', '', $str);

sa($res);





return;
$res = update_cdvet_pics($shop_id = '1215', $ebay_id = '253453555515');
sa($res);


function update_cdvet_pics($shop_id, $ebay_id)
{
	$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);
	if (isset($cdvet_feed[$shop_id])){
		$img_arr = explode('|', $cdvet_feed[$shop_id][16]);
		$img_arr = array_filter($img_arr, function ($url){
			return filter_var($url, FILTER_VALIDATE_URL) and (stripos($url, '.jpg') || stripos($url, '.png'));
		});
		sa($img_arr);
		if (count($img_arr) > 0) {
			return Cdvet::updateItemPictures($ebay_id, $img_arr);
		}
	}
}



return;
$res = Ebay_shopping2::getSingleItem_test('253252045583', true);
unset($res['Item']['ReturnPolicy']);
unset($res['Item']['BusinessSellerDetails']['TermsAndConditions']);
// sa($res);

$res2 = Ebay_shopping2::getSingleItem_test('253956809233', true);
unset($res2['Item']['ReturnPolicy']);
unset($res2['Item']['BusinessSellerDetails']['TermsAndConditions']);
sa($res2);

?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<?php sa($res['Item']['BusinessSellerDetails']['VATDetails']['VATPercent']); ?>
		</div>
		<div class="col-sm-6">
			<?php sa($res2['Item']['BusinessSellerDetails']['VATDetails']); ?>
		</div>
	</div>
</div>
<?php


return;
$ebay_items = arrayDB("SELECT ebay_id from temp_ebay_pics_parser where pics_hashes = '' limit 1000");

foreach ($ebay_items as $key => $ebay_item) {
	
	$ebay_id = $ebay_item['ebay_id'];

	$res = Ebay_shopping2::getSingleItem($ebay_id, 1);

	if ($res['Ack'] === 'Success') {
		$hashes = gmp_get_picture_hashes($res['Item']['PictureURL']);
		sa($hashes);
		$hashes = _esc($hashes);
		if (!$hashes) {
			$flag = _esc(implode('<br>', $res['Item']['PictureURL']));
		}
		arrayDB("UPDATE temp_ebay_pics_parser SET pics_hashes = '$hashes', flag = '$flag' WHERE ebay_id = '$ebay_id'");

		// $title = _esc($res['Item']['Title']);
		// arrayDB("UPDATE temp_ebay_pics_parser SET title = '$title' WHERE ebay_id = '$ebay_id'");
	}

}






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

