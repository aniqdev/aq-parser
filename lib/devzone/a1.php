<?php






echo 'wellcome to a1 file';





return
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


