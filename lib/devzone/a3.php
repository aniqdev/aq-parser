<?php




$xcel = readExcel('csv/CS-aktuell.xlsx');
$pairs_arr = [];
foreach ($xcel as $value) {
	if($value['B']) $pairs_arr[$value['A']] = $value['B'];
}
function is_pair_of($prod_id1, $prod_id2)
{
	global $pairs_arr;
	if (isset($pairs_arr[$prod_id1]) && strpos($pairs_arr[$prod_id1], $prod_id2) !== false) {
		return '1';
	}
	return '0';
}

sa(is_pair_of('146381', '148444'));
sa(is_pair_of('146381', '148443'));
sa(count($pairs_arr));
sa($pairs_arr);

return;
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

