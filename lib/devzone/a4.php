<?php



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

$postdata = http_build_query(
    array(
        'var1' => 'some content',
        'var2' => 'doh'
    )
);

$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('http://example.com/submit.php', false, $context);

sa($http_response_header);
sa($result);
