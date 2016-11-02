<pre>
<?php

use \Curl\MultiCurl;

// Requests in parallel with callback functions.
		// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$multi_curl = new MultiCurl();

$multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER , 0);
$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION , 1);

$multi_curl->success(function($instance) {
    echo 'call to "' . $instance->url . '" was successful.' . "\n";
    echo 'response:' . "\n";
    echo "<pre>";
    var_dump($instance);
    echo "</pre>";
});
$multi_curl->error(function($instance) {
	echo "<hr>";
    echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
    echo 'error code: ' . $instance->errorCode . "\n";
    echo 'error message: ' . $instance->errorMessage . "\n";
	echo "<hr>";
    echo "<pre>";
    var_dump($instance->curl->alfa);
    echo "</pre>";
});
$multi_curl->complete(function($instance) {
    echo 'call completed' . "\n";
});

// $multi_curl->addGet('https://www.google.com/search', array(
//     'q' => 'hello world',
// ));
// $multi_curl->addGet('https://duckduckgo.com/', array(
//     'q' => 'hello world',
// ));
$multi_curl->addGet('https://www.bing.com/search', array(
    'q' => 'hello world',
));

$multi_curl->start(); // Blocks until all items in the queue have been processed.

?>
</pre>