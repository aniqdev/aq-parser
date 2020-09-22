<?php



_do_action();
function _do_action()
{
	$res = modaDB("SELECT post_title,post_name,post_type FROM wp_posts where post_type = 'fashion' order by RAND() limit 450");

	$json = [
		'siteUrl' => 'https://modetoday.de',
	];

	foreach ($res as $key => $post) {
		$json['urlList'][] = 'https://modetoday.de/fashion/' . $post['post_name'].'/'; // post_name
	}

	sa(count($json['urlList']));

	$json = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES); // JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES

	// return;

	$url = 'https://ssl.bing.com​/webmaster/api.svc/json/SubmitUrlbatch?​siteurl='.urlencode('https://modetoday.de').'&apikey=8862dacf9aa24d06962618518bed5788';

	$post_res = post_curl($url, $json, ['Content-Type: application/json; charset=utf-8']);

	xa($post_res);
}


