<?php





	$opts = [
	  'http'=>[
	    'method'=>"GET",
	    'header'=>"Cookie: session-13=qbhm4mffr3jkdqc1a81glp9bus\r\n"
	  ]
	];
	$context = stream_context_create($opts);

	$url = 'https://b2b.cdvet.de/uromix-forte-10kg';

	$res = file_get_contents($url, false, $context);

	echo strlen($res);