<?php








$feed_1 = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input.json');

$feed_1 = json_decode($feed_1, 1);

sa(count($feed_1));

// sa($feed_1);








return;
sa(calc_price(7.9, 5));

function calc_price($old_price, $tax)
{
  $price = (float)$old_price * 1.25;
  sa($price);
  if($tax == 5) $price = $price * 1.07;
  if($tax == 16) $price = $price * 1.19;
  sa($price);
  $price = round($price, 2);
  $int = (int)$price;
  $cents = $price*100 % 100;
  $cents = $cents < 50 ? 49 : 99;
  $cents = $cents / 100;
  return (string)($int + $cents);
}



return;
$speed_res = arrayDB("SELECT id,date_format(created_at, '%H:00') as daten, count(*) as count FROM `moda_cron_update` where created_at > (now() - interval  1 day)  group by hour(created_at) order by id");

sa($speed_res);




return;
require_once(ROOT.'/lib/crest-master/src/crest.php');

$result = CRest::installApp();


var_dump($result);

// sa(CRest::call('scope'));

return;
sa(ROOT.'/lib/crest-master/src/crest.php');
// put an example below
echo '<PRE>';
print_r(CRest::call(
   'crm.lead.add',
   [
      'fields' =>[
        'TITLE' => 'Название лида',//Заголовок*[string]
        'NAME' => 'Имя',//Имя[string]
        'LAST_NAME' => 'Фамилия',//Фамилия[string]
      ]
   ])
);

echo '</PRE>';





return;
define('EVE_TOKEN', '1|CfDJ8HHFK/DOe6xKoNPHamc0mCUUHq0abYfs6z9pL8akS2exbcWXXwNyJnbdbxSUwJCwsGU/9jYbaS9FHHepY7rRkABeHoJwxFgJyDo/YkPNaghzINc88SzC9VzlCxRv0z2RjwTIW4P/UDLaBB5KXNhjuOcV802xJ+jglN7/yj44I5BQ');

/*
https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=http://parser/?action=eve-oauth&client_id=d6f723ee906949b0abdf832e6bd57d9e&scope=esi-corporations.read_corporation_membership.v1

Base64
ZDZmNzIzZWU5MDY5NDliMGFiZGY4MzJlNmJkNTdkOWU6eFN2dUJObWM1aG5KOWtCb3daNWdhOVQ4WnJNdm1oeDN1SVZrYWRsOQ==
*/


$opts = array('http' =>
  array(
    'header'  => "Content-Type: text/json\r\n".
      "Authorization: Bearer ".EVE_TOKEN."\r\n",
  )
);
                       
$context  = stream_context_create($opts);
$url = 'https://esi.evetech.net/latest/corporations/238510404/members/?token='.EVE_TOKEN;
// $url = 'https://esi.evetech.net/latest/characters/2112625428/blueprints/?token='.EVE_TOKEN;
$res = file_get_contents($url, false, $context);
sa(error_get_last());
// $res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


return;

$opts = array('http' =>
  array(
    'header'  => "Content-Type: text/json\r\n".
      "Authorization: Bearer ".EVE_TOKEN."\r\n",
  )
);
                       
$context  = stream_context_create($opts);
$url = 'https://login.eveonline.com/oauth/verify';
$result = file_get_contents($url, false, $context);

$result = json_decode($result);

sa($result);

    // [CharacterID] => 2115651873
    // [CharacterName] => Ronin Good
    // [ExpiresOn] => 2019-09-09T06:08:16
    // [Scopes] => esi-corporations.read_corporation_membership.v1
    // [TokenType] => Character
    // [CharacterOwnerHash] => yikjZxcm3zB1svrAvBtxx+WJevc=
    // [IntellectualProperty] => EVE

return;

$url = 'https://esi.evetech.net/latest/corporations/238510404/members/?token=yikjZxcm3zB1svrAvBtxx+WJevc=';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


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


return;

$url = 'https://esi.evetech.net/latest/alliances/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');



