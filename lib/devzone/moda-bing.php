<?php



$count = modaDB("SELECT count(*) FROM wp_posts where post_type = 'fashion'");
$count = $count ? (int)$count[0]['count(*)'] : 0;

sa($count);


if ($_POST) _do_action();

function _do_action()
{
	// sa($_POST['limit']);
	// $res = modaDB("SELECT post_title,post_name,post_type FROM wp_posts where post_type = 'fashion' limit $_POST[limit]");
	$res = modaDB("SELECT DISTINCT t.*, tt.*
    FROM wp_terms AS t
    INNER JOIN wp_term_taxonomy AS tt
    ON t.term_id = tt.term_id
    WHERE tt.taxonomy = 'fashion_category'");

	// sa($res);
	$json = [
		'siteUrl' => 'https://modetoday.de',
		'apikey' => '8862dacf9aa24d06962618518bed5788',
		'urlList' => ['https://modetoday.de']
	];

	foreach ($res as $key => $post) {
		$json['urlList'][] = 'https://modetoday.de/fashion_category/' . $post['slug'].'/'; // post_name
	}

	sa(count($json['urlList']));

	$json = json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES); // JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES

	sa($json);

	// return;

	$url = 'https://ssl.bing.com​/webmaster/api.svc/json/SubmitUrlbatch?​siteurl='.urlencode('https://modetoday.de').'&apikey=8862dacf9aa24d06962618518bed5788';

	$post_res = post_curl($url, $json, ['Content-Type: application/json; charset=utf-8', 'Host: ssl.bing.com​']);
	sa($post_res);
}


?>

<div class="container"><br>
	<form action="?action=devzone/moda-bing" method="POST">
		<?php 
			echo ' <button type="submit" class="btn btn-primary" name="limit" value="500">Go!</button>';
		?>
	</form><hr>
</div>

<?php
