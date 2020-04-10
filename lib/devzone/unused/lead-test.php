<?php


// CRM_LOGIN
// CRM_PASSWORD
// b24-1cbkwk.bitrix24.ru

if ($dev = true) {
	$login = CRM_LOGIN;
	$password = CRM_PASSWORD;
	$domen = 'b24-1cbkwk.bitrix24.ru';
}else{
	$login = 'webline24w@gmail.com';
	$password = 'bitr62fbfcvdfbdVDbd';
	$domen = 'rasio.bitrix24.ru';
}

$query = [
	'TITLE' => 'Расчитать стоимость доставки(тест)', // сохраняем нашу метку и формируем заголовок лида
	'NAME' => 'Ivan',   // сохраняем имя
	'PHONE_WORK' =>'78964564523', // сохраняем телефон
	'EMAIL_WORK' => 'asd@asd.df', // сохраняем почту
	'UF_CRM_1583922274191' => 'Текст новой строки
	Текст новой строки<br>Текст новой строки', // сохраняем ИНН
	'OPPORTUNITY' => '120.50',
	'CURRENCY_ID' => 'RUB',
	'ADDRESS' => 'ADDRESS ADDRESS',
	'LOGIN' => $login,
	'PASSWORD' => $password,
	'asd' => 'qwe',
];


$resp = post_curl('https://'.$domen.'/crm/configs/import/lead.php', $query);

$resp = str_replace("'", '"', $resp);

sa($resp);


sa(json_decode($resp, 1));


