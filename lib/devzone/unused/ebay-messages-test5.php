<pre>
<?php
ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

$url = 'https://api.ebay.com/ws/api.dll';
$token = EBAY_GIG_TOKEN;

function request($url, $post, $headers) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 170);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}






?>
</pre>