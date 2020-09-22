<?php ini_get('safe_mode') or set_time_limit(1300);






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