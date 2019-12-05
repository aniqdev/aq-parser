ajax_hot_do_woocommerce_api_request
<?php










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