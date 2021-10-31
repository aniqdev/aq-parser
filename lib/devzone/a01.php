<?php ini_get('safe_mode') or set_time_limit(1300);
// use PHPUnit\Framework\TestCase;



sa(json_decode(''));


return;
function alphanumeric(string $string): bool {
  return preg_match('/^[\d\w]{3,}$/', $string);
}



sa('True => '.(alphanumeric('Mazinkaiser') ? 'TRUE' : 'FALSE')); // assertTrue
sa('False => '.(alphanumeric('hello world_') ? 'TRUE' : 'FALSE'));
sa('True => '.(alphanumeric('PassW0rd') ? 'TRUE' : 'FALSE'));
sa('False => '.(alphanumeric('     ') ? 'TRUE' : 'FALSE'));
sa('False => '.(alphanumeric('фыв111') ? 'TRUE' : 'FALSE'));





return;
function solution(array $a, array $b, $ndx = 0): float {
	return array_reduce($a, function($accum, $item) use ($b, &$ndx) {
		return $accum += pow(abs($item - $b[$ndx++]), 2);
	}, 0) / count($a);
}

sa(9 .' => '. solution([1, 2, 3], [4, 5, 6]));
sa(16.5 .' => '. solution([10, 20, 10, 2], [10, 25, 5, -2]));
sa(1 .' => '. solution([0, -1], [-1, 0]));

return;
function findMissing_2($list) {
   return (reset($list) + end($list)) / 2 * (count($list) + 1) - array_sum($list);
}

function findMissing($list) {
	$len = count($list); // 4
	$array_sum = array_sum($list); // 11
	// sa('$array_sum: '.$array_sum);
	$first_last_sum = $list[0] + $list[$len - 1]; // 6
	// sa('$first_last_sum: '.$first_last_sum);
	$len_p1 = $len + 1; // 5
	// sa('$len_p1: '.$len_p1);
    return $first_last_sum * $len_p1 / 2 - $array_sum;

	$smalest_dif = abs($list[1] - $list[0]);
	foreach ($list as $key => $current) {
		if (isset($last)) {
			if(abs($current - $last) < $smalest_dif){
				$smalest_dif = abs($current - $last);
			}
		}
		$last = $current;
	}
	$result = 0;
	unset($last);
	foreach ($list as $key => $current) {
		if (isset($last)) {
			if(abs($current - $last) > $smalest_dif){
				$result = ($current + $last) / 2;
			}
		}
		$last = $current;
	}
	return $result;
}

sa(findMissing([1, 2, 3, 5]));
sa(findMissing([1, 3, 5, 9, 11]));
sa(findMissing([100, 200, 300, 500]));
sa(findMissing([-300, -100, 0, 100, 200, 300, 400, 500]));
sa(findMissing([ 500, 400, 300, 200, 100, -100, -200, -300]));



return;
sa(count_chars('aaываыы'));

return;
$a = array("apple", "banana");
$b = array(1 => "banana", 0 => "apple");

sa($a);
sa($b);

var_dump($a == $b); // bool(true)
echo '<hr>';
var_dump($a === $b); // bool(false)

return;
$arr = [
	1 => 'a',
	'2' => 'b',
];

foreach ($arr as $key => $value) {
	var_dump($key);
}

$arr[2] = 'X';

sa($arr);





return;
function anagrams_3(string $word, array $words): array {
	return array_values(array_filter($words, function($wordd) use($word){
		return count_chars($word, 1) == count_chars($wordd, 1);
	}));
}


function anagrams_2(string $word, array $words): array {
	return array_values(array_filter($words, function($wordd) use($word){
		$word_arr2 = str_split($wordd);
		$word_arr1 = str_split($word);
		sort($word_arr1);
		sort($word_arr2);
		return implode($word_arr1) === implode($word_arr2);
	}));
}


function anagrams(string $word, array $words): array {
	return array_filter($words, function($wordd) use($word){
		$word_arr2 = str_split($wordd);
		$word_arr1 = str_split($word);
		$alowed_letters = [];
		foreach ($word_arr1 as $key => $letter) {
			@$alowed_letters[$letter] += 1;
		}
		foreach($word_arr2 as $wordd_letter){
			if(isset($alowed_letters[$wordd_letter]) && $alowed_letters[$wordd_letter] > 0){
				$alowed_letters[$wordd_letter] -= 1;
			}	else {
				return false;
			}
		}
		return true;
	});
}


sa(anagrams('a', ['a', 'b', 'c', 'd']));
sa(anagrams('racer', ['carer', 'arcre', 'carre', 'racrs', 'racers', 'arceer', 'raccer', 'carrer', 'cerarr']));
sa(anagrams('abba', ['aabb', 'abcd', 'bbaa', 'dada']));
sa(anagrams('racer', ['crazer', 'carer', 'racar', 'caers', 'racer']));
sa(anagrams('laser', ['lazing', 'lazy',  'lacer']));
echo('<hr>');
sa(anagrams_2('a', ['a', 'b', 'c', 'd']));
sa(anagrams_2('racer', ['carer', 'arcre', 'carre', 'racrs', 'racers', 'arceer', 'raccer', 'carrer', 'cerarr']));
sa(anagrams_2('abba', ['aabb', 'abcd', 'bbaa', 'dada']));
sa(anagrams_2('racer', ['crazer', 'carer', 'racar', 'caers', 'racer']));
sa(anagrams_2('laser', ['lazing', 'lazy',  'lacer']));

return;
// final class AnagramsTest extends TestCase {
//   public function testExamples() {
//     // $this->assertEquals(['a'], anagrams('a', ['a', 'b', 'c', 'd']));
//     $this->assertEquals(['carer', 'arcre', 'carre'], anagrams('racer', ['carer', 'arcre', 'carre', 'racrs', 'racers', 'arceer', 'raccer', 'carrer', 'cerarr']));
//     $this->assertEquals(['aabb', 'bbaa'], anagrams('abba', ['aabb', 'abcd', 'bbaa', 'dada']), 'Your function should work for an example provided in the Kata Description');
//     $this->assertEquals(['carer', 'racer'], anagrams('racer', ['crazer', 'carer', 'racar', 'caers', 'racer']), 'Your function should work for an example provided in the Kata Description');
//     $this->assertEquals([], anagrams('laser', ['lazing', 'lazy',  'lacer']), 'Your function should work for an example provided in the Kata Description');
//   }
// }

// (new AnagramsTest)->testExamples();


return;
$dom = file_get_html('https://rozetka.com.ua/home_textile/c169823/');

sa([
	'asd' => array_map(function($el){return $el->plaintext;}, $dom->find('.goods-tile__title')),
]);





return;
$dom = file_get_html('https://rozetka.com.ua/mirson_2200003480504/p299087928/');



sa($product = [
	'title' => $dom->find('.product__heading', 0)->plaintext, // plaintext, innertext, outertext
	'Product' => json_decode($dom->find('[data-seo="Product"]', 0)->innertext, true), // plaintext, innertext, outertext
	// 'old_price' => $dom->find('.super-offer__price--old', 0)->plaintext, // plaintext, innertext, outertext
]);

foreach ($product['Product']['image'] as $img_src) {
	echo "<img src='$img_src' style='max-width: 200px; margin: 15px;'>";
}

sa($product['Product']['image']);


return;
'римские пекарни = 330
остров = 100
дамаск = 215
бренди / болт = -35
Итого = 610';




function connect(string $host, string $db, string $user, string $password): PDO
{
	try {
		$dsn = "pgsql:host=$host;port=5432;dbname=$db;";

		// make a database connection
		return new PDO(
			$dsn,
			$user,
			$password,
			[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
		);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

$pg_sql = connect($host = 'localhost', $db = 'postgres', $user = 'postgres', $password = 'kajmad');

sa($pg_sql);



return;
/*
перевозки Днепр-ЛНР Ира:
050 085 0301
*/
'29го 07
750грн
700руб
22-00
памятник
wv красный 0522 николай
0665408886';


$asd = function(){
	return function(){
		return 2;
	};
};

var_dump($asd()());
// sa(asd);


return;
sa($_SERVER);

function parse_css($css)
{
	preg_match_all("/\{.+\}/sU", $css, $matches);
	$all_css_vals = [];
	foreach ($matches[0] as $selector) {
		$selector = trim($selector, '{}');
		$selector = preg_replace('/\/\*.+\*\\//sU', '', $selector);
		$values = explode(';', $selector);
		foreach ($values as $value) {
			$value = trim(explode(':', $value)[0]);
			if($value && allow_css_value($value)){
				$value = str_replace(['*'], '', $value);
				@$all_css_vals[$value]++;
			}
		}
	}
	return $all_css_vals;
}

$css = file_get_contents('css/style.css');
$arr1 = parse_css($css);

$css = file_get_contents('css/bootstrap.css');
$arr2 = parse_css($css);

$sums = array();
		
foreach (array_keys($arr1 + $arr2) as $c) {
	$sum = (isset($arr1[$c]) ? $arr1[$c] : 0) + (isset($arr2[$c]) ? $arr2[$c] : 0);
	if($sum >= 5) $sums[$c] = $sum;
}
// var_dump($sums);

arsort($sums, SORT_NUMERIC);

sa(count($sums));
sa($sums);



function allow_css_value($string){
	foreach ([
		'-o-',
		'-ms-',
		'-moz-',
		'-webkit-',
		'base64',
	] as $vendor_prefix) {
		if(str_contains($string, $vendor_prefix)) return false;
	}
	return true;
}



return;
$json = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input.json');

$json = json_decode($json, 1);

sa(count($json));

sa($json);



return;
// $cdvet_feed = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input.json');
$cdvet_feed = file_get_contents(ROOT.'/Files/input.json');

$cdvet_feed = json_decode($cdvet_feed, 1);

sa(count($cdvet_feed));
// sa(($cdvet_feed));


foreach ($cdvet_feed as $variants) {
	foreach ($variants as $variant) {
		sa([
			(count($variants) > 1) ? 'VARIABLE':'SIMPLE',
			@$variant['price'],
			@$variant['price_unit'],
			get_unit_price($variant),
		]);
	}
}

function get_unit_price($variant)
{

		$price_unit = @$variant['price_unit'];
		if ($price_unit) {
			$inhalt = str_replace('Inhalt:', '', $price_unit);
			// $inhalt = preg_replace('pattern', replacement, subject);
			$inhalt = trim($inhalt);
			if (strpos($inhalt,')')) {
				$amount = explode('(', $inhalt)[0];
				$res = preg_replace('/.+\(/', '(', $inhalt);
			}else{
				$amount = $inhalt;
				$res = $variant['price'] . '€** / ' . $inhalt;
			}
			$res = shortify_units($res);

			$price_4_unit = explode('/', $res)[0];
			$price_4_unit = preg_replace('/[^\d ,]/', '', $price_4_unit);
			$price_4_unit = str_replace(',','.',$price_4_unit);
			// sa($price_4_unit);
			$price_4_unit = get_price_by_formula($price_4_unit);
			// sa($price_4_unit);
			$price_4_unit = str_replace('.',',',$price_4_unit);
			$itog = $price_4_unit . ' € /' . explode('/', $res)[1];
			
		}elseif(@$variant['price']){
			$itog = str_replace('.', ',', $variant['price']). ' € / 1 St.';
		}else{
			$itog = '0,00 € / 1 St.';
		}
		return $itog;
}

function shortify_units($str)
{
	return str_ireplace(
		['(', ')', ' * ', 'Liter', 'Kilogramm', 'Gramm','Stück'],
		['',  '',  ' ',   'l',     'kg',        'g',    'St.'  ],
		$str);
}
function get_price_by_formula($price, $tax = 19)
{
	$price = (float)$price * 1.25;
	if($tax == 5 || $tax == 7) $price = $price * 1.07;
	if($tax == 16 || $tax == 19) $price = $price * 1.19;
	$price = round($price, 2);
	$int = (int)$price;
	$cents = $price*100 % 100;
	$cents = $cents < 50 ? 49 : 99;
	$cents = $cents / 100;
	return (string)($int + $cents);
}
// 1. "deliveryTime" cо значением "Lieferung in 1-3 Werktagen" если товар в  наличие и "Lieferung in 5 Werktagen" если нет
// 2. "eans" со значениями дублирующие "gtin"
// 3. "categoryPath" со значением "Hundefutter" + ">" + "(название последнего уровня категории в которой находится товар)"
// 4. "basePrice" с уже вычисленным значением цены за юнит. Пример "9,16 € / 100 g"

return;
$url_get_csrf = 'https://b2b.cdvet.de/csrftoken';

$res = file_get_contents($url_get_csrf);

foreach ($http_response_header as $key => $header) {
	$header = explode(':', $header);
	// sa($header[0]);
	if ($header[0] === 'x-csrf-token') {
		sa($header[0]);
		$csrf_token = trim($header[1]);
		sa($csrf_token);
	}
}

sa($http_response_header);
$url_login = 'https://b2b.cdvet.de/PrivateLogin/login/sTarget/PrivateLogin/sTargetAction/redirectLogin';

// sa(htmlspecialchars($res));

$res = post_curl($url_login, [
	'email' => 'gp-development@yandex.com',
	'password' => 'rekaMore71',
	'__csrf_token' => $csrf_token
], $headers = []);

// sa($_GET['headerLine']);
sa($http_response_header);
sa($res);



return;


// $json = file_get_contents('Files/test-json.json');

// sa($json);

// $arr = json_decode('{"@context":"https:\/\/schema.org\/","@graph":[{"@context":"https:\/\/schema.org\/","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"item":{"name":"\u0413\u043b\u0430\u0432\u043d\u0430\u044f","@id":"http:\/\/cdvet-feed.loc"}},{"@type":"ListItem","position":2,"item":{"name":"Hund","@id":"http:\/\/cdvet-feed.loc\/product-category\/hund\/"}},{"@type":"ListItem","position":3,"item":{"name":"ArthroGreen Classic","@id":"http:\/\/cdvet-feed.loc\/product\/arthrogreen-classic\/"}}]},{"@context":"https:\/\/schema.org\/","@type":"Product","@id":"http:\/\/cdvet-feed.loc\/product\/arthrogreen-classic\/#product","name":"ArthroGreen Classic","url":"http:\/\/cdvet-feed.loc\/product\/arthrogreen-classic\/","description":"Zur bedarfsgerechten F\u00fctterung von gelenkempfindlichen Hunden und Katzen. Besondere Versorgung von Gelenken, Muskeln, Sehnen - mit Gr\u00fcnlippmuschel, Pulver","image":"http:\/\/cdvet-feed.loc\/wp-content\/uploads\/2021\/01\/image_285_1_1280x1280.png","sku":8001,"offers":[{"@type":"Offer","price":"19.49","priceValidUntil":"2022-12-31","priceSpecification":{"price":"19.49","priceCurrency":"EUR","valueAddedTaxIncluded":"true"},"priceCurrency":"EUR","availability":"http:\/\/schema.org\/InStock","url":"http:\/\/cdvet-feed.loc\/product\/arthrogreen-classic\/","seller":{"@type":"Organization","name":"cdVet","url":"http:\/\/cdvet-feed.loc"}}],"test_structura":"ArthroGreen"}]}', true);

// sa($arr);
// sa($arr['age']);


// $arr = [
// 	'key' => 'value',
// 	'num' => 23534,
// 	'boolean' => true,
// 	'array' => [1,2,3,4]
// ];

// sa(json_encode($arr, 128));

// file_put_contents('Files/test-json.json', json_encode($arr, 128));


// return;
$json = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input.json');

$json = json_decode($json, 1);

sa(count($json));

sa($json);




return;
$generator = Faker\Factory::create();
$generator->seed(1);
$documentor = new Faker\Documentor($generator);
?>
<?php foreach ($documentor->getFormatters() as $provider => $formatters): ?>

### `<?php echo $provider ?>`

<?php foreach ($formatters as $formatter => $example): ?>
		<?php echo str_pad($formatter, 23) ?><?php if ($example): ?> // <?php echo $example ?> <?php endif; ?>

<?php endforeach; ?>
<?php endforeach;


return;
$json = file_get_contents('https://eor.pp.ua/input.json');

$arr = json_decode($json, 1);

foreach ($arr as &$variants) {
	foreach ($variants as &$variant) {
		$variant['images'] = json_decode($variant['images']);
		unset($variant['description_html']);
	}
}

sa($arr);





return;
$res = arrayDB("select title,ebay_id,shop_id as cdvet_id,vat from cdvet where vat in(5,16) order by vat");
draw_table_with_sql_results($res);



return;
$arr = readExcel('csv/apartments.xlsx');

if ($arr[1] && $arr[2]){

	$first_row = $arr[1];
	unset($arr[1]);

	sa($first_row);
	$arr = array_map(function($excel_row) use ($first_row)
	{
		$new_row = [];
		foreach ($excel_row as $key => $value) {
			$new_row[$first_row[$key]] = $value;
		}
		return $new_row;
	}, $arr);
	sa($arr);
}








return;
$res = file_get_contents('csv/cdvet-products-09.12.20.json');
$res = json_decode($res, true);

// sa($res);
// return;

$parrents_arr = [];
foreach ($res as $key => $value) {
	$parrents_arr[$value['A']] = [
				'categoryId' => $value['A'],
				'parentID' => $value['B'],
				'metatitle' => $value['E'],
				'description' => $value['C'],
				// 'position' => $value['D'],
	];
}

sa($parrents_arr);
return;

// $final_arr = [];
// foreach ($res as $key => $val) {
// 	if ($val['B'] == '1651') {
// 		$final_arr[$val['A']] = [];
// 	}
// }

$hund_cats = [];

foreach ($parrents_arr['1651'] as $key => &$value) {
	if (isset($parrents_arr[$value['categoryId']])) {
		foreach ($parrents_arr[$value['categoryId']] as $key => &$value2) {
			if (isset($parrents_arr[$value2['categoryId']])) {
				$value2['children'] =  $parrents_arr[$value2['categoryId']];
			}else{
				$value2['children'] =  0;
			}
			$hund_cats[$value2['categoryId']] = $res[$value2['categoryId']];
		}
		$value['children'] = $parrents_arr[$value['categoryId']];
	}else{
		$value['children'] = 0;
	}
	$hund_cats[$value['categoryId']] = $res[$value['categoryId']];
}

sa(JSON_PRETTY_PRINT);
sa($parrents_arr['1651']);

$hund_cats = array_map(function($value)
{
	return [
				'categoryId' => $value['A'],
				'parentID' => $value['B'],
				'metatitle' => $value['E'],
				'description' => $value['C'],
				'dev_cat_id' => '111',
				'prod_cat_id' => '222',
		];
}, $hund_cats);
sa($hund_cats);
// file_put_contents('csv/hundefutter_cats.json', json_encode($hund_cats, JSON_PRETTY_PRINT));

// sa($res);


return;
$res = file_get_contents('csv/cdvet-products-09.12.20.json');
$res = json_decode($res, true);
$res = array_column($res, null, 'A');
$res = array_map(function($el)
{
	return [
		'shop_id' => $el['A'],
		'price' => $el['K'],
		'tax' => $el['F'],
		'cats' => $el['R'],
	];
}, $res);



sa($res);

return;






return;
$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);
sa(count($cdvet_feed));
sa($cdvet_feed[30]);
foreach (explode('|', $cdvet_feed[30][16]) as $key => $img_src) {
	echo "<img src='$img_src' style='width:100px'>";
}


$res = arrayDB("SELECT shop_id,ebay_id,extra_field2,title FROM cdvet");

foreach ($res as $key => &$value) {
	if (isset($cdvet_feed[$value['shop_id']])) {
		$value['volume'] = $cdvet_feed[$value['shop_id']][8].$cdvet_feed[$value['shop_id']][7];
		$value['volume'] = str_replace(
			['0.5l','0.25l','0.72kg','0.2l','0.6kg','0.8kg','0.25kg','0.5kg','0.7kg','0.4kg','0.3kg','0.35kg'],
			['500ml','250ml','720g','200ml','600g', '800g', '250g',  '500g', '700g', '400g', '300g',  '350g'],
			$value['volume']);
		$digits_title = preg_replace('/\D/', '', $value['title']);
		$digits_volume = preg_replace('/\D/', '', $value['volume']);
		$value['good'] = $digits_title === $digits_volume ? 'goodd' : 'badd';
		if(strpos($value['title'], $value['volume']) !== false) $value['good'] = 'goodd';
		$value['shop title'] = $cdvet_feed[$value['shop_id']][4];
	}else{
		$value['volume'] = $value['shop title'] = $value['good'] = '-';
		if($value['extra_field2'] === 'update_pics1') $value['extra_field2'] = 'not_in_feed';
	}
}

draw_table_with_sql_results($res, true);



return;
$file_path = 'csv/Kategorie-Metadescription-1.1.xls';

$sheet_1 = readExcel($file_path);
$sheet_2 = readExcel($file_path, 1);

$sheet_1 = array_column($sheet_1, null, 'A');

foreach ($sheet_1 as $key => &$value) {
	$value['count'] = 0;
}
foreach ($sheet_2 as $key => &$value) {
	$value['E'] = '';
}

$not_existing_cats = [];
foreach ($sheet_2 as $key => $row) {
	$cats_arr = explode('|', $row['D']);
	if (in_array(1651, $cats_arr)) {
		$sheet_2[$key]['E'] = 'in Hund';
	}
	foreach ($cats_arr as $key => $cat_id) {
		if ($cat_id) {
			if (isset($sheet_1[$cat_id])) {
				$sheet_1[$cat_id]['count'] += 1;
			}else{
				if(isset($not_existing_cats[$cat_id])){
					$not_existing_cats[$cat_id] += 1;
				}else{
					$not_existing_cats[$cat_id] = 0;
				}
			}
		}
	}
}

// sa($sheet_2);

// $cats_arr = [];
// for ($i=2; $i <= count($sheet_1); $i++) { 
// 	$cats_arr[] = $sheet_1[$i]['A'];
// }
// sa($cats_arr);

?>
<div class="container-fluid">
	<table class="ppp-table">
		<?php foreach ($sheet_2 as $key => $row): break;?>
			<tr>
				<td><?= $row['A'] ?></td>
				<td><?= $row['E'] ?></td>
			</tr>
		<?php endforeach ?>
	</table>
	<div class="row">
		<div class="col-sm-4"><?php // sa($sheet_1); ?></div>
		<div class="col-sm-4"><?php // sa($sheet_2); ?></div>
		<div class="col-sm-4"><?php // sa($not_existing_cats); ?></div>
	</div>
</div>
<?php


return;
$res = Cdvet::GetSellerList();
$res = array_map(function($el)
{
	return $el['ItemID'];
}, $res);
sa($res);






return;
$res = unserialize('a:2:{s:8:"pa_value";a:6:{s:4:"name";s:8:"pa_value";s:5:"value";s:0:"";s:8:"position";i:0;s:10:"is_visible";i:0;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:1;}s:7:"pa_size";a:6:{s:4:"name";s:7:"pa_size";s:5:"value";s:0:"";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:1;}}');

sa($res);

$res = unserialize('a:2:{s:8:"pa_value";a:6:{s:4:"name";s:8:"pa_value";s:5:"value";s:0:"";s:8:"position";i:0;s:10:"is_visible";i:0;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:1;}s:7:"pa_size";a:6:{s:4:"name";s:7:"pa_size";s:5:"value";s:0:"";s:8:"position";i:1;s:10:"is_visible";i:1;s:12:"is_variation";i:1;s:11:"is_taxonomy";i:1;}}');

sa($res);

$res = unserialize('a:8:{s:10:"wc_notices";N;s:4:"cart";s:455:"a:1:{s:32:"47d5e07a1b44a9638bab53f869cd4c9a";a:11:{s:3:"key";s:32:"47d5e07a1b44a9638bab53f869cd4c9a";s:10:"product_id";i:2977;s:12:"variation_id";i:2991;s:9:"variation";a:1:{s:17:"attribute_pa_size";s:4:"60-g";}s:8:"quantity";i:1;s:9:"data_hash";s:32:"4da846832186be7554db60b9d3723c11";s:13:"line_tax_data";a:2:{s:8:"subtotal";a:0:{}s:5:"total";a:0:{}}s:13:"line_subtotal";d:6.27;s:17:"line_subtotal_tax";i:0;s:10:"line_total";d:6.27;s:8:"line_tax";i:0;}}";s:11:"cart_totals";s:402:"a:15:{s:8:"subtotal";s:4:"6.27";s:12:"subtotal_tax";d:0;s:14:"shipping_total";s:4:"0.00";s:12:"shipping_tax";i:0;s:14:"shipping_taxes";a:0:{}s:14:"discount_total";i:0;s:12:"discount_tax";i:0;s:19:"cart_contents_total";s:4:"6.27";s:17:"cart_contents_tax";i:0;s:19:"cart_contents_taxes";a:0:{}s:9:"fee_total";s:4:"0.00";s:7:"fee_tax";i:0;s:9:"fee_taxes";a:0:{}s:5:"total";s:4:"6.27";s:9:"total_tax";d:0;}";s:15:"applied_coupons";s:6:"a:0:{}";s:22:"coupon_discount_totals";s:6:"a:0:{}";s:26:"coupon_discount_tax_totals";s:6:"a:0:{}";s:21:"removed_cart_contents";s:6:"a:0:{}";s:8:"customer";s:726:"a:26:{s:2:"id";s:1:"1";s:13:"date_modified";s:25:"2020-10-04T17:32:14+00:00";s:8:"postcode";s:0:"";s:4:"city";s:0:"";s:9:"address_1";s:0:"";s:7:"address";s:0:"";s:9:"address_2";s:0:"";s:5:"state";s:0:"";s:7:"country";s:2:"DE";s:17:"shipping_postcode";s:0:"";s:13:"shipping_city";s:0:"";s:18:"shipping_address_1";s:0:"";s:16:"shipping_address";s:0:"";s:18:"shipping_address_2";s:0:"";s:14:"shipping_state";s:0:"";s:16:"shipping_country";s:2:"DE";s:13:"is_vat_exempt";s:0:"";s:19:"calculated_shipping";s:0:"";s:10:"first_name";s:0:"";s:9:"last_name";s:0:"";s:7:"company";s:0:"";s:5:"phone";s:0:"";s:5:"email";s:12:"dsfg@sdf.gfh";s:19:"shipping_first_name";s:0:"";s:18:"shipping_last_name";s:0:"";s:16:"shipping_company";s:0:"";}";}');

sa($res);

foreach ($res as $key => $val) {
	sa(unserialize($val));
}

return;
$Woo = new WooCommerceApi([
	'store_url' => 'http://cdvet-feed.loc/',
	'api_key' => CDVET_WOO_KEY,
	'api_secret' => CDVET_WOO_SECRET
]);



$data = [
		'regular_price' => '13.33',
];


// $res = $Woo->addProduct($data);
$res = $Woo->updateProduct($id = 24, $data);

sa($res);








return;
// for ($i=1; $i < 96; $i++) {

// 	sa($i);
// 	$res = Ebay_shopping2::findItemsAdvanced(0, 'fiedifighters_de', $i, 100);
// 	$res = gml_clean_result(json_decode($res,1));
// 	echo($res['findItemsAdvancedResponse']['ack']);

// 	if ($res['findItemsAdvancedResponse']['ack'] !== 'Success') {
// 		break;
// 	}

// 	foreach ($res['findItemsAdvancedResponse']['searchResult']['item'] as $key => $item) {
// 		// arrayDB("INSERT IGNORE INTO temp_ebay_pics_parser SET ebay_id = '$item[itemId]'");
// 		$item['title'] = _esc($item['title']);
// 		arrayDB("UPDATE temp_ebay_pics_parser SET title = '$item[title]' WHERE ebay_id = '$item[itemId]'");
// 	}

// }

// $res = Ebay_shopping2::findItemsAdvanced(0, 'fiedifighters_de', $page = 36, $perPage = 100);

// $res = gml_clean_result(json_decode($res,1));

// sa($res);


// foreach ($res['findItemsAdvancedResponse']['searchResult']['item'] as $key => $item) {
// 	sa($item['itemId']);
// 	arrayDB("INSERT IGNORE INTO temp_ebay_pics_parser SET ebay_id = '$item[itemId]'");
// }




return;
sa(strlen(md5('11')));
sa(md5('11'));

sa(strlen(md5('11',1)));
sa(base64_encode(md5('11',1)));
sa(base64_decode(base64_encode(md5('11',1))));




return;
$video = 'https://v19.tiktokcdn.com/b0c0b102ec2a2387f8ded16199647476/5ed3b6fd/video/tos/useast2a/tos-useast2a-ve-0068c001/d295e9ecd94c429c829bb5437b86f5db/?a=1233&br=4656&bt=2328&cr=0&cs=0&dr=0&ds=3&er=&l=20200531075351010189072216250CD704&lr=tiktok_m&mime_type=video%2Fmp4&qs=0&rc=M3g6eTpydjlldTMzZjczM0ApZWloOWY4NDxnNzhpZjc7OGdxb282YWBzZTZfLS0xMTZzcy9eL14zMDUtY2AuMTNjMjU6Yw%3D%3D&vl=&vr=';

$res = AqsBot::setChatId('-1001449047445')->sendVideo([
	'video' => $video,
	'parse_mode' => 'HTML',
	'caption' => 'Девчулички, знакома ситуация? 🥰😂 @dava_m 🧸 #рек #бузова #buzova',
]);

$res = json_decode($res,1);
sa($res);


return;
$media = [
	['type'=>'photo', 'media'=>'https://ireland.apollo.olxcdn.com/v1/files/sqvcip3x3xry1-UA/image;s=644x461','parse_mode' => 'HTML','caption' => 'Довготривала оренда 1-к квартири у новобудові
<b>8 500 грн.</b>'],
	['type'=>'photo', 'media'=>'https://ireland.apollo.olxcdn.com:443/v1/files/php94jg1lmg21-UA/image;s=1000x700']
];

$media = json_encode($media);

var_dump($media);

$res = AqsBot::setChatId('-1001287057345')->sendMediaGroup([
	'media' => $media,
// 	'parse_mode' => 'HTML',
// 	'caption' => 'Довготривала оренда 1-к квартири у новобудові
// <b>8 500 грн.</b>'
]);

sa($res);

$res = json_decode($res);

sa($res);


return;


$photo = ['https://ireland.apollo.olxcdn.com/v1/files/sqvcip3x3xry1-UA/image;s=644x461','https://ireland.apollo.olxcdn.com:443/v1/files/php94jg1lmg21-UA/image;s=1000x700'];

$res = AqsBot::setChatId('-1001287057345')->sendPhoto([
	'photo' => $photo,
	'parse_mode' => 'HTML',
	'caption' => 'Довготривала оренда 1-к квартири у новобудові
<b>8 500 грн.</b>'
]);

sa($res);

$res = json_decode($res);

sa($res);


return;

$text = 'Все будет хорошо!!!!!';

$res = AqsBot::setChatId('-1001287057345')->sendMessage($text);

sa($res);

$res = json_decode($res);

sa($res);




return;


sa(get_moda_meta_progress());



return;
$res = Ebay_shopping2::findItemsAdvanced_moda($categoryId = '169291', $page = 1, $perPage = 100);

$res = json_decode($res,1);

sa($res);


return;
$src = 'https://parser.gig-games.de/steam-images/apps-351920/header.jpg';

$res = (new Ebay_shopping2)->imageUpload($src);

sa($res);







return;
function ajax_b24rest()
{
	
	if ($dev = true) {
		$login = CRM_LOGIN;
		$password = CRM_PASSWORD;
		$domen = 'b24-1cbkwk.bitrix24.ru';
	}else{
		$login = 'webline24w@gmail.com';
		$password = 'bitr62fbfcvdfbdVDbd';
		$domen = 'rasio.bitrix24.ru';
	}

	$query = [
		'TITLE' => 'Расчитать стоимость доставки(тест)', // сохраняем нашу метку и формируем заголовок лида
		'LOGIN' => $login,
		'PASSWORD' => $password,
		'NAME' => $_POST['page'],   // сохраняем имя
		'PHONE_WORK' => $_POST['phone'], // сохраняем телефон
		'COMMENTS' => $_POST['baza_name'] . '(' . $_POST['baza_price'] . ') | ' . $_POST['selects_text'],
		// 'EMAIL_WORK' => 'asd@asd.df', // сохраняем почту
		// 'UF_CRM_1583922274191' => $_POST['selects_text'],
		// 'UF_CRM_1584012198249[0]' => $_POST['baza_name'] . '(' . $_POST['baza_price'] . ')', // dev:UF_CRM_1584007151734 UF_CRM_1584011488714 | prod:UF_CRM_1584010025644 UF_CRM_1584010694056 UF_CRM_1584012198249
		// 'UF_CRM_1584011530360' => $_POST['selects_text'], // dev:UF_CRM_1584007253990 UF_CRM_1584011530360 | prod:UF_CRM_1584010216506 UF_CRM_1584010712263
		'OPPORTUNITY' => $_POST['total_sum'],
		'CURRENCY_ID' => 'RUB',
		'ADDRESS' => $_POST['address'],
	];


	$resp = post_curl('https://'.$domen.'/crm/configs/import/lead.php', $query);

	$resp = str_replace("'", '"', $resp);

	$resp = json_decode($resp, 1);

	if ($resp['error'] == '201') {
		$_POST['form_message'] = 'Ваша заявка принята в обработку.';
	}else{
		$_POST['form_message'] = 'На сайте возникли технические трудности. Свяжитесь с нами по телефону или электронной почте.';
	}

	echo json_encode($_POST);
	die;
}












return;
	$multi_curl = new \Curl\MultiCurl();

		$multi_curl->success(function($instance) {

		$res = json_decode($instance->response,1);

		// $res = clean_result($res);

		sa($res);

	});

	$multi_curl->error(function($instance) {
		global $_ERRORS;
		$_ERRORS[] = 'THAT WAS multi_curl ERROR!!!';
			$_ERRORS[] = $instance->errorMessage;
	});

	// for ($offs=0; $offs < 701; $offs += 100) { 
	// 	$url = get_google_url($word, $offs);
	// 	$multi_curl->addGet($url);
	// }

	$url = Ebay_shopping2::findItemsAdvanced_moda_url($categoryId = '169291', $page = 1, $perPage = 100);

	$multi_curl->addGet($url);

	$multi_curl->start();




function cr_ccallback($item)
{
	if (is_array($item) && isset($item[0]) && count($item) === 1) {
		// var_dump($item[0]);
		return $item[0];
	}elseif (is_array($item)) {
		return array_map('cr_ccallback', $item);
		return $item;
	}else{
		return $item;
	}
}


function clean_result($res = [])
{
	if(!$res) return $res;
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	$res = array_map('cr_ccallback', $res);
	return $res;
}




return;
$res = (new Ebay_shopping2())->GetCategories(['CategorySiteID' => '77']);

// sa($res);

sa(count($res['CategoryArray']['Category']));



foreach ($res['CategoryArray']['Category'] as $key => $val) {

	$val['CategoryName'] = _esc($val['CategoryName']);

	arrayDB("INSERT IGNORE INTO moda_cats SET
		CategoryID = '{$val['CategoryID']}',
		CategoryLevel = '{$val['CategoryLevel']}',
		CategoryName_DE = '{$val['CategoryName']}',
		CategoryParentID = '{$val['CategoryParentID']}'");
}


// return;
foreach ($res['CategoryArray']['Category'] as $key => $val) {

	$val['CategoryName'] = _esc($val['CategoryName']);

	arrayDB("UPDATE moda_cats SET
		CategoryName_DE = '{$val['CategoryName']}'
		WHERE CategoryID = '{$val['CategoryID']}'");
}



return;
foreach ($res['CategoryArray']['Category'] as $key => $val) {

	$val['CategoryName'] = _esc($val['CategoryName']);

	arrayDB("INSERT IGNORE INTO moda_cats SET
		CategoryID = '{$val['CategoryID']}',
		CategoryLevel = '{$val['CategoryLevel']}',
		CategoryName = '{$val['CategoryName']}',
		CategoryParentID = '{$val['CategoryParentID']}'");
}


// return;

// Women = 260010 

$res = Ebay_shopping2::findItemsAdvanced_moda($categoryId = '169291', $page = 1, $perPage = 100);

$res = json_decode($res,1);

// $counter = 0;

function ccallback($item)
{
	if (is_array($item) && isset($item[0]) && count($item) === 1) {
		// var_dump($item[0]);
		return $item[0];
	}elseif (is_array($item)) {
		return array_map('ccallback', $item);
		return $item;
	}else{
		return $item;
	}
}

$res = array_map('ccallback', $res);
$res = array_map('ccallback', $res);
$res = array_map('ccallback', $res);
$res = array_map('ccallback', $res);
$res = array_map('ccallback', $res);

sa($res['findItemsAdvancedResponse']['itemSearchURL']);
// sa($res['findItemsAdvancedResponse']['searchResult']['item'][3]);
sa($res);






return;
$res = arrayDB("SELECT year,count(*) FROM `steam_de` WHERE `os` = '' group by year");

sa(explode(',', ''));



return;
	$sql_query = "SELECT count(*) FROM `steam_de` WHERE title <> ''   AND `os` REGEXP 'win|mac' AND `year` = '2035'";

	$res = arrayDB($sql_query);

	if(isset($res[0]['title'])) $res = array_map(function($el){
		$el['slug'] = get_gig_game_url_title($el['title']);
		return $el;
	}, $res);

	sa([
			'count' => isset($res[0]['count(*)']) ? $res[0]['count(*)'] : count($res),
			'results' => $res,
			// 'pagination' => $pagination,
			'sql_query' => $sql_query,
			'ERRORS' => $_ERRORS,
	]);




return;
$content = '[dd-owl-carousel id="1" title="Carousel Title"]';

preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );

sa($matches);

$shortcode_tags = [
		'embed' => '__return_false',
		'dd-owl-carousel' => [
						'0' => [
										'plugin_name:Owl_Carousel_2_Public:private' => 'owl-carousel-2',
										'version:Owl_Carousel_2_Public:private' => '1.0.8'
								],
						'1' => 'dd_owl_carousel_two'
				]
	];

$tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

sa($tagnames);

$ignore_html = false;

$content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

sa($content);



return;
	$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
		'function' => 'ajax_hot_do_woocommerce_api_request',
		'method' => 'get',
		'endpoint' => "orders",
		'data' => [
			'per_page' => '100',
			// 'order' => 'asc',
		],
	]);

	sa($item);



return
		$dest = get_steam_images_dir_path('app', '640900');
		var_dump(file_exists($dest.'/header.jpg'));
		echo "<hr>";
		var_dump(filesize($dest.'/header.jpg'));
		$img_exists = (file_exists($dest.'/header.jpg') && filesize($dest.'/header.jpg') > 30000);
		echo "<hr>";
		var_dump($img_exists);



return
	$woo_id = '14351';


	$data = [
		'stock_status' => 'outofstock', // instock / outofstock
	];

	$item = post_curl('https://hot-body.net/parser/ajax-controller.php', [
		'function' => 'ajax_hot_do_woocommerce_api_request',
		'method' => 'put',
		'endpoint' => "products/$woo_id",
		'data' => $data,
	]);

	sa($item);


return
$_POST['wooId'] = 14351;
$_POST['price'] = 1.71;

$Woo = new WooCommerceApi();
$woo_item = $Woo->updateProductPrice((int)$_POST['wooId'], (float)$_POST['price']);

sa($woo_item);



return
$_POST['wooId'] = 14339;


$ret = post_curl('https://hot-body.net/parser/ajax-controller.php', [
	'function' => 'ajax_hot_do_woocommerce_api_request',
	'method' => 'get',
	'endpoint' => 'products/'.$_POST['wooId'],
]);

sa($ret);




return
$game = arrayDB("SELECT * FROM steam_de WHERE link = 'http://store.steampowered.com/app/615650/' LIMIT 1")[0];

sa($game);



$data = [
	'name' => $game['title'],
	'type' => 'simple',
	'regular_price' => $game['reg_price'],
	'description' => $game['desc'],
	'short_description' => $game['specs'],
	'categories' => [['id'=>82]],
	// 'images' => [
	// 	['src' => $img_src]
	// ]
];

if (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header-80p.jpg')) {
	$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
	$data['images'] = [['src' => $img_src]];
}elseif (file_exists(get_steam_images_dir_path($game['type'], $game['appid']).'/header.jpg')) {
	$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';
	$data['images'] = [['src' => $img_src]];
}else{

}

	$img_src = get_steam_images_dir_url($game['type'], $game['appid']).'/header-80p.jpg';

sa($img_src);

$data = [
	'name' => $game['title'],
	'type' => 'simple',
	'regular_price' => $game['reg_price'],
	'description' => $game['desc'],
	'short_description' => $game['specs'],
	'categories' => [['id'=>82]],
	'images' => [
		['src' => $img_src]
	],
	'stock_status' => 'outofstock',
];


$res = post_curl('https://hot-body.net/parser/ajax-controller.php', [
	'function' => 'ajax_hot_do_woocommerce_api_request',
	'method' => 'post',
	'endpoint' => 'products',
	'data' => $data,
]);

// $res = do_woocommerce_api_request('post', 'products', $data);

sa($res);




return;
$WooCommerceApi = new WooCommerceApi;


$data = [
	'name' => 'Test product',
	'type' => 'simple',
	'regular_price' => '999.99',
	'description' => 'Test product description ',
	'short_description' => 'Test product short_description ',
	'categories' => [['id'=>82]],
	'images' => [
		['src' => 'https://cartsandtools.com/wp-content/uploads/Garden_Cart_2.jpg']
	]
];

$data_ = [
		'name' => 'Premium Quality',
		'type' => 'simple',
		'regular_price' => '21.99',
		'description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.',
		'short_description' => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
		'categories' => [
				[
						'id' => 9
				],
				[
						'id' => 14
				]
		],
		'images' => [
				[
						'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg'
				],
				[
						'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg'
				]
		]
];

$res = $WooCommerceApi->addProduct($data);

var_dump($res);
sa($res);