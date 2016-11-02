<style>
	.table_dark {
	  font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
	  font-size: 14px;
	  width: 640px;
	  text-align: left;
	  border-collapse: collapse;
	  background: #252F48;
	  margin: 10px;
	}
	.table_dark th {
	  color: #EDB749;
	  border-bottom: 1px solid #37B5A5;
	  padding: 12px 17px;
	}
	.table_dark td {
	  color: #CAD4D6;
	  border-bottom: 3px solid #37B5A5;
	  border-right:3px solid #37B5A5;
	  padding: 7px 17px;
	}
	.table_dark tr:last-child td {
	  border-bottom: none;
	}
	.table_dark td:last-child {
	  border-right: none;
	}
	.table_dark tr:hover td {
	  text-decoration: underline;
	}
</style>
<?php
$url  = "https://api.sandbox.ebay.com/ws/api.dll";

$token = "AgAAAA**AQAAAA**aAAAAA**TCvJVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GjDJOGpAidj6x9nY+seQ**gOwDAA**AAMAAA**WGb9rPwe19/eeZjnVCwfTSyr39/UMNsPRt+fc5vIikBYz5TFE4HECldROutjvkAdAvzyXOQlh3plgg/32fUP7ZLydzmUkIi+wcPp8GUFxeDpO25i+xMhGy823qzxg9djBrk1Erdx9eqelPRtQLMmnssHzfDk2NkMWRGe8/CzQYeUm598MrfhB4ik7LyK1a8t9NXSnPW9+35FRulwxz8InDJyxYP9qG+gZCLicXEBtPCfl/bS5zKogdO4O5ymDPhWFm28qnkfx3VnhB8Mj5CmkOwVDpPqKrFwdabRJ4vP74wVc66GHdR2heLt0Sw5WfoabjOshUG9x/jeE0R1Vn4agtY9fyMp3T+s6GQ8hMTpFQTjHi1GT9x1lc5XusMPz1oyIic76xbDZ621g8QNMuxB3vCfcjSbo2ma5W2BMYap8f2a9CkByYvuZzaBKDZb4tuE3E/WyHbfCPbShMTwN7Qtj7CGAvbVYGK/j9A/LqydLgWuKMocnTLR4eNBdMpuGfLlbrS8xFqdrrAC7GcHUplrpEwcVgO5RhUVcXkmaHBSd8MN9+AJtvwgR2O0vcFeOi/yL2x4hPFHDQzfMppiOTXHAbDmXTwhCazTMTMKnb7OooM0xCdEDnzXZhOi2GDjFx0oNUnaqQzqbgDe536KjkbFAb5U7stlWD3JQkig+kA1DIGJlpnWN16Kay3fi8OnN4Nfba1F3VatyBkhEterKSkl4wibdD5wWovQscs7NafIYgJnz6qctjLg+36HKeNCi2Ie";


$url = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

include_once 'config.php';
$token = EBAY_GIG_TOKEN;


function request($url, $post, $headers) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function GetOrders(){
	global $url, $token;
	$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
			<eBayAuthToken>'.$token.'</eBayAuthToken>
		  </RequesterCredentials>
		  <CreateTimeFrom>2016-09-11T20:34:44.000Z</CreateTimeFrom>
		  <CreateTimeTo>2016-09-12T20:34:44.000Z</CreateTimeTo>
		  <OrderRole>Seller</OrderRole>
		  <DetailLevel>ReturnAll</DetailLevel>
		  <Pagination> 
			<EntriesPerPage>55</EntriesPerPage> 
		  </Pagination> 
		</GetOrdersRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
	"X-EBAY-API-CALL-NAME: GetOrders",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

//помечает товар(заказ) как доставленный покупателю
function MarkAsShipped($OrderID, $status = true){
	global $url, $token;

	$post = '<?xml version="1.0" encoding="utf-8"?>
	<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	  <RequesterCredentials>
		<eBayAuthToken>'.$token.'</eBayAuthToken>
	  </RequesterCredentials>
	  <WarningLevel>High</WarningLevel>
	  <Shipped>'.$status.'</Shipped>
	  <OrderID>'.$OrderID.'</OrderID>
	</CompleteSaleRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	"X-EBAY-API-CALL-NAME: CompleteSale",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

//помечает товар(заказ) как оплаченный
function MarkAsPaid($OrderID, $status = true){
	global $url, $token;

	$post = '<?xml version="1.0" encoding="utf-8"?>
	<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">
	  <RequesterCredentials>
		<eBayAuthToken>'.$token.'</eBayAuthToken>
	  </RequesterCredentials>
	  <WarningLevel>High</WarningLevel>
	  <Paid>'.$status.'</Paid>
	  <OrderID>'.$OrderID.'</OrderID>
	</CompleteSaleRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	"X-EBAY-API-CALL-NAME: CompleteSale",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

//получает инфу о юзерах, включая адрес, рейтинг отзывов.
function GetUser($username, $ItemID){
	global $url, $token;

	$post = '<?xml version="1.0" encoding="utf-8"?> 
	<GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents"> 
	  <RequesterCredentials> 
		<eBayAuthToken>'.$token.'</eBayAuthToken> 
	  </RequesterCredentials> 
	  <UserID>'.$username.'</UserID>
	  <ItemID>'.$ItemID.'</ItemID>
	  <DetailLevel>ReturnAll</DetailLevel>
	</GetUserRequest> ';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	"X-EBAY-API-CALL-NAME: GetUser",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);

	return json_decode(json_encode(simplexml_load_string($result)), true);
}


if(isset($_POST) && isset($_POST['OrderID'])){
	if(isset($_POST['MarkAsShipped'])){
		//помечаем как отправленный
		if($_POST['MarkAsShipped'] == "Mark as Shipped"){ MarkAsShipped($_POST['OrderID']); }
		//помечаем как НЕ отправленный
		else { MarkAsShipped($_POST['OrderID'], "false"); }
	}
	elseif(isset($_POST['MarkAsPaid'])) {
		//помечаем как оплаченный
		if($_POST['MarkAsPaid'] == "Mark as Paid"){ MarkAsPaid($_POST['OrderID']); }
		//помечаем как НЕ оплаченный
		else { MarkAsPaid($_POST['OrderID'], "false"); }
	}
}

$xmlArr = GetOrders();
//массив с информацией о юзерах, чтобы каждый раз не брать инфу с апи
$users = array();

echo "<table class='table_dark'><thead><th>OrderID</th><th>Title</th><th>OrderStatus</th><th>Total</th><th>Buyer Email</th><th>Buyer Name</th><th>Buyer address</th><th>Reviews</th><th>PaidTime</th><th>ShippedTime</th><th></th><th></th></thead>";

foreach ($xmlArr['OrderArray']['Order'] as $key => $value) {
	//унифицируем структуру данных для заказов с одним и множеством товаров
	$transactions = [];
	if(!isset($value['TransactionArray']['Transaction'][0])) $transactions[0] = $value['TransactionArray']['Transaction'];
	else $transactions = $value['TransactionArray']['Transaction'];

	//получаем инфу о юзере, если ее еще нет
	if(!isset($users[$value['BuyerUserID']])) $users[$value['BuyerUserID']] = GetUser($value['BuyerUserID'], $transactions[0]['Item']['ItemID']);
		
	echo "<tr><td>", $value['OrderID'], "</td><td>";

	foreach($transactions as $transaction){
		echo "<p>".$transaction['Item']['Title']."</p>";
	}
	echo 	"</td>
		<td>",$value['OrderStatus'], "</td><td>";
		
	foreach($transactions as $transaction){
		echo "<p>".$transaction['TransactionPrice']."</p>";
	}
	echo "</td>
		<td>",$transactions[0]['Buyer']['Email'],"</td>
		<td>",$transactions[0]['Buyer']['UserFirstName']." ".$transactions[0]['Buyer']['UserLastName'],"</td>
		<td>".$users[$value['BuyerUserID']]['User']['RegistrationAddress']['CountryName']." ".$users[$value['BuyerUserID']]['User']['RegistrationAddress']['CityName']." ".$users[$value['BuyerUserID']]['User']['RegistrationAddress']['Street']." ".$users[$value['BuyerUserID']]['User']['RegistrationAddress']['Street1']."</td>
		<td>".$users[$value['BuyerUserID']]['User']['FeedbackScore']."</td>
		<td>",@$value['PaidTime'],"</td>
		<td>",@$value['ShippedTime'],"</td>
		<td><form method='POST'><input type='submit' name='MarkAsShipped' value='".(isset($value['ShippedTime']) ? "Mark as NOT Shipped": "Mark as Shipped")."'><input type='hidden' name='OrderID' value='".$value['OrderID']."'></form></td>
		<td><form method='POST'><input type='submit' name='MarkAsPaid' value='".(isset($value['PaidTime']) ? "Mark as NOT Paid": "Mark as Paid")."'><input type='hidden' name='OrderID' value='".$value['OrderID']."'></form></td>
		</tr>";
}
echo "</table>";

//echo "<pre>";
//print_r($xmlArr);
//echo "</pre>";