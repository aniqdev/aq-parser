<?php
header('Content-Type: text/html; charset=utf-8');
ini_get('safe_mode') or set_time_limit(60); // Указываем скрипту, чтобы не обрывал связь.
?>

<?php
define('DOCROOT', 'E:\xamp\htdocs\parser\www\test.php');
define('ROOT', __DIR__);
require_once('lib/array_DB.php');
require_once('lib/simple_html_dom.php');
require_once('lib/functions.class/functions.class.php');

$headers = array
    (
    'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '837',
    'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
    'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
    'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
    'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
    'X-EBAY-API-SITEID: ' . '77',
    'X-EBAY-API-RESPONSE-ENCODING: ' . 'JSON',
);

$endpoint = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

$auth_token = 'AgAAAA**AQAAAA**aAAAAA**lW+DVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFloqjAZOKoQydj6x9nY+seQ**A1sDAA**AAMAAA**bJZNblCzYfoH41ej+oYjKvaiSIEgGgjXtz5xYJH+Nn6AeKYxrNyVhcIKlc8PDqUdVZMBsG3COT8cmmTUmWECC4wEm1RFzyxmwBppednB5xFBjl7Tt2iHwVq9Joq5fXHe9QVC1KTyrZVnCRL2ViKpUPyRJOAxjfW4R/8ld72LE9F1teRHyeeTYy26Js/vXh4r1ZkNoHIrmCWGwZ/x84FQEr7d4XMwuhaKsQZWhYhXKahQT3SreaYcXsygdQdWwvC/XZ5kuFbh6/UPXPrrDc5LsozMw18CGMF/eNY4ozP1Sq/xhBoWBjrlUpMdKAf9e+t1q3/fBcYnjGRaL5vNUGFIVRWLohfuYf5vZSlPFmbaYI8+Vtl8O7f1Qp9fYYyxdRU4DNRdwc55vgq9lSsrJRqiRY1E3BFbjljoj5tJ06BQ4zRoVHbnzvYiJ8+AcMAT4sLHVwf+9/QljLk6jqev/vwjkaJzQZ9cN/WwADeEv3j6EC9kAkAoBx7JPbB0REWdAtoHdqFKByQk35mbbkcWAI/VQfsqBO0lqo77CR1vkZideodUZvzXT7icbtrnTdZW2rvqJNvwSsnYIOgoIifbA2PiMuHtWvG91Cctsz+IE7wRQ4pFycAAWf4lsdQ1jkgiHW5tEz7XW7afDPxpPL1MyVZTtbzLBacmHsVch61gWDcBhadjbizx2xTJUzHW7UyIqp4Q7b/4v0P4bNyje2uD79alLH6YTlkbOT88DaGR/TPR/CQS/eouhfoqVMWWLN4BVjA8';

$itemid = '121772951115';

$xml = '<?xml version="1.0" encoding="utf-8"?>
<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$auth_token.'</eBayAuthToken>
  </RequesterCredentials>
  <Item ComplexType="ItemType">
    <ItemID>'.$itemid.'</ItemID>
    <Title>BlazBlue: Calamity Trigger (PC) Steam Regfree MULTILANG</Title>
    <Quantity>0</Quantity>
    <StartPrice>4.55</StartPrice>
  </Item>
  <MessageID>1</MessageID>
  <WarningLevel>High</WarningLevel>
  <Version>837</Version>
</ReviseItemRequest>​';

$xml = '<?xml version="1.0" encoding="utf-8"?>
<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$auth_token.'</eBayAuthToken>
  </RequesterCredentials>
  <Item ComplexType="ItemType">
    <ItemID>'.$itemid.'</ItemID>
    <Quantity>4</Quantity>
    <Description>'.htmlspecialchars (file_get_contents('StarDrive.html')) .'</Description>
  </Item>
  <MessageID>1</MessageID>
  <WarningLevel>High</WarningLevel>
  <Version>837</Version>
</ReviseItemRequest>​';

$ch  = curl_init($endpoint);     
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);                  
curl_setopt($ch, CURLOPT_POST, true);              
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//$responseXML = curl_exec($ch);
curl_close($ch);


//var_dump($responseXML);
if (stripos($responseXML, 'Success') !== false)   $ack = true;
else  $ack = false;

var_dump($ack);
$responseObj = simplexml_load_string($responseXML);
echo "<pre>";
var_dump((string)$responseObj->Ack);
echo "<hr>";
print_r($responseObj);
echo "</pre>";