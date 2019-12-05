<?php





$url = 'https://esi.evetech.net/latest/characters/151590509/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


// return;

$url = 'https://esi.evetech.net/latest/alliances/498125261/icons/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


// return;

$url = 'https://esi.evetech.net/latest/alliances/1424550893/corporations/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


// return;

$url = 'https://esi.evetech.net/latest/alliances/1028876240/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


// return;

$url = 'https://esi.evetech.net/latest/corporations/238510404/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');

