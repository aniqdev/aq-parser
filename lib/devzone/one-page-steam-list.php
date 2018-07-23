<?php






// В следующей строчке Steam_Language=german, можно указывать другие языки вместо german
$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
$context = stream_context_create($options);

// Основная ссылка, с которой мы парсим игры
$doc = file_get_contents('http://store.steampowered.com/search/?sort_by=Name_ASC&tags=-1&category1=998%2C996&page=379', false, $context);
//var_dump($doc);
$page = str_get_html($doc);

echo $page;