<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Curl\Curl;

$curl = new Curl();
$curl->setUserAgent('Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36');
$curl->setReferrer('http://store.steampowered.com/');
$curl->setHeader('Host', 'steamcommunity.com');
$curl->setHeader('Accept-Encoding', 'gzip, deflate, sdch');
$curl->setCookie('Steam_Language', 'english');
$curl->setCookie('sessionid', '4625eab8832115eb4352ee5a');
$curl->setCookie('strInventoryLastContext', '753_1');
$curl->setCookie('webTradeEligibility', '%7B%22allowed%22%3A0%2C%22reason%22%3A40%2C%22allowed_at_time%22%3A1479725537%2C%22steamguard_required_days%22%3A15%2C%22sales_this_year%22%3A0%2C%22max_sales_per_year%22%3A200%2C%22forms_requested%22%3A0%2C%22new_device_cooldown_days%22%3A7%7D');
$curl->setCookie('timezoneOffset', '10800,0');
$curl->setCookie('steamRememberLogin', '76561198325659089%7C%7C1b7181b1f140d527f7969ed5d0340737');
$curl->setCookie('steamLogin', '76561198325659089%7C%7C02944BF8316B54FE5358A6E3B9E029312C49DB4F');
$curl->setCookie('app_impressions', '289070@1_4_4__100|379770@1_4_4__100_1|289070@1_4_4__100');
$curl->get('http://steamcommunity.com/profiles/76561198325659089/inventory/');

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage;
} else {
    echo($curl->response);
}