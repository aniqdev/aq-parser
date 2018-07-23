<?php
require 'facebook_config.php';
require 'facebook_DB.class.php';


$db = DB::getInstance();

if(!@$_GET['ebay_id']) return;

$ebay_id = $db->escape($_GET['ebay_id']);

$res = $db->query("SELECT title,`desc` FROM games
	JOIN steam_de
	ON games.steam_link = steam_de.link
	WHERE ebay_id = '$ebay_id'");

echo "<pre>";
print($res);
echo "</pre>";


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>gig games</title>

	<meta property="og:url"           content="https://gig-games.de/facebook.html">
	<meta property="og:type"          content="article">
	<meta property="og:title"         content="gig-games">
	<meta property="og:description"   content="Computerspiele zu Hammerpreisen">
	<meta property="og:image"         content="https://gig-games.de/images/gig-games-facebook.jpg">
	<meta property="og:site_name"     content="Gig games" />
</head>
<body>

</body>
</html>