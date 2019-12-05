<?php

define('EVE_TOKEN', '1|CfDJ8HHFK/DOe6xKoNPHamc0mCVO8egh/94p/ZUmI1TukDTF2K5gNaAeKqhpqPPDq81YQJzqRb4+KPy6s5ZEDQnZtGuZOkTnl61j0A1os91ndbQQijnwpqSlqXfOKEKq4Yg8FLwz1J0g8d7Gi/I7qhIyDNM3luJFJb5PoZiP2mDJGqQ+');



$url = 'https://esi.evetech.net/latest/corporations/98148549/divisions/';


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, []);  //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
    'Authorization: Bearer ' . EVE_TOKEN,
    'User-Agent: my-test-agent'
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$server_output = curl_exec ($ch);

curl_close ($ch);
	    // Authorization: Bearer {access token}



sa($url);

sa($server_output);


return;
'{
    "access_token": "1|CfDJ8HHFK/DOe6xKoNPHamc0mCXathKPfC71QDA6+ZmB6b/lgTkzHxmGdN0JoC9vh5+Vh/2yL/QEceFNuYiXw7mb52XIBsPawymFoxUOKO54mIrFJ+T6HupqyjRaGi0oMwXuFXO4Ll3+83VhgeohDCKJk/sa4hOKhPcgZlF40Cr1Jcse",
    "token_type": "Bearer",
    "expires_in": 1199,
    "refresh_token": "u6RYTaEPSCB4OnAqxS6WRHpkwJOdDU4CrYECkyX6bLE"
}';
'{
    "access_token": "1|CfDJ8HHFK/DOe6xKoNPHamc0mCX5P3aCxJlPybqQgl49RdoO6ZKsHArOfB4ujvuVofhdX+QKPDuV1yUYopemWkkkb4OyTwkssVm7uuVqMTnozi08iRYOFFtm51Ac9jbj5Z/XQWVogvZWsOHwijY0ckzer8KczoSJ9Hmg3+zxq8CvlYcH",
    "token_type": "Bearer",
    "expires_in": 1199,
    "refresh_token": "qlCm19osDnIMyH0VE5Ed-PCFK8nCbDKeqqXOYSBV9oo"
}';
// Basic: ZDZmNzIzZWU5MDY5NDliMGFiZGY4MzJlNmJkNTdkOWU6eFN2dUJObWM1aG5KOWtCb3daNWdhOVQ4WnJNdm1oeDN1SVZrYWRsOQ==
$res = post_curl('https://login.eveonline.com/oauth/token', [
	'grant_type' => 'authorization_code',
	'code' => '6y5YHr3XkHVb9HQGlh5fQoVOBGQSnYj1JLk3jx3_AbKGl_KaYScGtBP3yaXTExuC'
]);

sa($res);


return;

$url = 'https://esi.evetech.net/latest/alliances/498125261/icons/';

$res = file_get_contents($url);

$res = json_decode($res, true);

sa($url);

sa($res);

echo('<hr>');


// return;

$url = 'https://esi.evetech.net/latest/alliances/99005338/corporations/';

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



