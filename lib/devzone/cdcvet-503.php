<?php

header( ( $_SERVER['SERVER_PROTOCOL'] == 'HTTP/1.1' ? 'HTTP/1.1' : 'HTTP/1.0' ) . ' 503 Service Unavailable', true, 503 );
header( "Retry-After: 3600" );

?>
<!doctype html>
<html class="no-js" lang="de-DE">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Website temporarily offline</title>
	<!-- HERE YOU CAN ADD SOME OPTIONAL RESOURCES -->

<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">

<style>
	/* html { background: lightslategrey; } */
	body {
		font-family: 'Ubuntu', sans-serif;
		color: #555;
		background: transparent;
		text-align: center;
		position: absolute;
		left: 50%; top: 50%;
		-webkit-transform: translate(-50%, -50%);
		        transform: translate(-50%, -50%);
	}
</style>
</head>
<body>
	<img src="https://www.cdvet.de/media/image/47/f3/7b/Logo_180x180_optimized.png" alt="">
	<h2>Die Website ist vorübergehend aufgrund von Wartungsarbeiten nicht verfügbar.</h2>
	<h3> Sie können uns weiterhin telefonisch unter +49 (0) 5901 9796-100 erreichen</h3>
</body>
</html>