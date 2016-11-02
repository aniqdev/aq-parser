<?php
$App_ID = "Vladimir-testappl-SBX-69a5ca62d-b54d8de3User";
$Dev_ID = "c0c4ac4b-b6ac-4129-90f7-c48d41cae1e1";
$Cert_ID = "SBX-9a5ca62d27f5-2b6c-4b4c-bcc4-3575";

$url  = "https://api.sandbox.ebay.com/ws/api.dll";

$token = "AgAAAA**AQAAAA**aAAAAA**TCvJVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GjDJOGpAidj6x9nY+seQ**gOwDAA**AAMAAA**WGb9rPwe19/eeZjnVCwfTSyr39/UMNsPRt+fc5vIikBYz5TFE4HECldROutjvkAdAvzyXOQlh3plgg/32fUP7ZLydzmUkIi+wcPp8GUFxeDpO25i+xMhGy823qzxg9djBrk1Erdx9eqelPRtQLMmnssHzfDk2NkMWRGe8/CzQYeUm598MrfhB4ik7LyK1a8t9NXSnPW9+35FRulwxz8InDJyxYP9qG+gZCLicXEBtPCfl/bS5zKogdO4O5ymDPhWFm28qnkfx3VnhB8Mj5CmkOwVDpPqKrFwdabRJ4vP74wVc66GHdR2heLt0Sw5WfoabjOshUG9x/jeE0R1Vn4agtY9fyMp3T+s6GQ8hMTpFQTjHi1GT9x1lc5XusMPz1oyIic76xbDZ621g8QNMuxB3vCfcjSbo2ma5W2BMYap8f2a9CkByYvuZzaBKDZb4tuE3E/WyHbfCPbShMTwN7Qtj7CGAvbVYGK/j9A/LqydLgWuKMocnTLR4eNBdMpuGfLlbrS8xFqdrrAC7GcHUplrpEwcVgO5RhUVcXkmaHBSd8MN9+AJtvwgR2O0vcFeOi/yL2x4hPFHDQzfMppiOTXHAbDmXTwhCazTMTMKnb7OooM0xCdEDnzXZhOi2GDjFx0oNUnaqQzqbgDe536KjkbFAb5U7stlWD3JQkig+kA1DIGJlpnWN16Kay3fi8OnN4Nfba1F3VatyBkhEterKSkl4wibdD5wWovQscs7NafIYgJnz6qctjLg+36HKeNCi2Ie";

$url = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

include_once 'config.php';
$token = EBAY_GIG_TOKEN;

$request = '<?xml version="1.0" encoding="utf-8"?>
<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$token.'</eBayAuthToken>
  </RequesterCredentials>
      <CreateTimeFrom>2016-09-11T20:34:44.000Z</CreateTimeFrom>
      <CreateTimeTo>2016-09-12T20:34:44.000Z</CreateTimeTo>
  <OrderRole>Seller</OrderRole>
  <DetailLevel>ReturnAll</DetailLevel>
  <Pagination>
      <EntriesPerPage>30</EntriesPerPage>
      <PageNumber>1</PageNumber>
  </Pagination>
</GetOrdersRequest>';

  //<OrderStatus>Active</OrderStatus>
  //<OrderStatus>Completed</OrderStatus>

  // <OrderIDArray>
  //   <OrderID>122003069616-1679644116002</OrderID>
  //   <OrderID>111542599737-1560080370001</OrderID>
  // </OrderIDArray>

  // <CreateTimeFrom>2016-09-03T20:34:44.000Z</CreateTimeFrom>
  // <CreateTimeTo>2016-09-05T20:34:44.000Z</CreateTimeTo>
  
  // <CreateTimeFrom>2016-08-01T20:34:44.000Z</CreateTimeFrom>
  // <CreateTimeTo>2016-09-10T20:34:44.000Z</CreateTimeTo>

$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967", // 967,837
    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
"X-EBAY-API-CALL-NAME: GetOrders",
"X-EBAY-API-SITEID: 0",
"Content-Type: text/xml");

function request($url, $post) {
	global $headers;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

$xmlStr = request($url, $request);

$xmlObj = simplexml_load_string($xmlStr);

$jsonOrd = json_encode($xmlObj);

// echo "<hr>";
// echo htmlspecialchars($xmlStr);
// echo "<hr>";
// echo $jsonOrd;
// echo "<hr>";

$xmlArr = json_decode($jsonOrd, TRUE);

foreach ($xmlArr['OrderArray']['Order'] as $key => $value) {
	echo "<br>", $key, ' => ', $value['TransactionArray']['Transaction']['Item']['Title'];
}
echo "<hr>";

echo "<pre>";
print_r($xmlArr);
echo "</pre>";