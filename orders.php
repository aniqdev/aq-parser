<?php
$App_ID = "Vladimir-testappl-SBX-69a5ca62d-b54d8de3User";
$Dev_ID = "c0c4ac4b-b6ac-4129-90f7-c48d41cae1e1";
$Cert_ID = "SBX-9a5ca62d27f5-2b6c-4b4c-bcc4-3575";

$url  = "https://api.sandbox.ebay.com/ws/api.dll";

$token = "AgAAAA**AQAAAA**aAAAAA**0IzFVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GjDJKKoAidj6x9nY+seQ**hOwDAA**AAMAAA**5fIgQRcKUKMfCA/qQ4HIkcDo/19W2bsbkvjk+fMZTeaawjfbG3lFUheBNjwuDJLoUB8BcFSdLb0JoU2YPkROU/+ovb0OcJJNFK+GR6DURUMOY0K76gI76fO2WlInWgOnwWM/1B4AJHwoAtUKKtLWBOlI+YEAXTkaSIiu7uDWr5sq4oh0/5ypPtN+lPJZJ8mppzHiuevQ62TsHqBQzUVWa2vr46zqQIrCBX3W0abJfiEvbtf/vkP+b61nocTmjwrs5Eq1TE8ADue30RLUgUoZJmTiOoPobd5q5fGQ8/pBVsywZ3QQzIw1FB5WmAjdxlYtY/gXXjJ1zbHMXathQ1j04N1CQ4HFjNWSgxnjI9eaT0hjThMLRNX/l6aOOjizrdV+yF7PSvm0eFm/tKt+8/jkPEWX7keWzabVoJMg6/mFB0HHnr5HyYxPr9UEAqfCq6yl75R1SwX5E7wz2StUXMuqk6xHV1ykc6YP5mPDG6c+eBUo7sJDqXgPNBr0v3A0dlsW5lORQq4M2P47/7a5GzoEeFs2sDY6m7+1arRyzb0q8JP4Muo61XofMPyoE8GOHBLiPYlAtgpS8SLUH5mpiMGFJQER7XYBF0Z59Ez+nU7r68iezBorlILVkdZGHQOu/Bhik8lwh8Wla+E4CC2WM04nD5XdprIWTaghHg5AXTRrEz7ahqVUxMvhk2qKPF5vpo1+TP5529dGidD9QavQ+nSTX+9c0mInimX6zyjWYH+0BkH7YCzq4z8oFWG0LJ0GCUCv";

$url = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

$token = 'AgAAAA**AQAAAA**aAAAAA**lW+DVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFloqjAZOKoQydj6x9nY+seQ**A1sDAA**AAMAAA**bJZNblCzYfoH41ej+oYjKvaiSIEgGgjXtz5xYJH+Nn6AeKYxrNyVhcIKlc8PDqUdVZMBsG3COT8cmmTUmWECC4wEm1RFzyxmwBppednB5xFBjl7Tt2iHwVq9Joq5fXHe9QVC1KTyrZVnCRL2ViKpUPyRJOAxjfW4R/8ld72LE9F1teRHyeeTYy26Js/vXh4r1ZkNoHIrmCWGwZ/x84FQEr7d4XMwuhaKsQZWhYhXKahQT3SreaYcXsygdQdWwvC/XZ5kuFbh6/UPXPrrDc5LsozMw18CGMF/eNY4ozP1Sq/xhBoWBjrlUpMdKAf9e+t1q3/fBcYnjGRaL5vNUGFIVRWLohfuYf5vZSlPFmbaYI8+Vtl8O7f1Qp9fYYyxdRU4DNRdwc55vgq9lSsrJRqiRY1E3BFbjljoj5tJ06BQ4zRoVHbnzvYiJ8+AcMAT4sLHVwf+9/QljLk6jqev/vwjkaJzQZ9cN/WwADeEv3j6EC9kAkAoBx7JPbB0REWdAtoHdqFKByQk35mbbkcWAI/VQfsqBO0lqo77CR1vkZideodUZvzXT7icbtrnTdZW2rvqJNvwSsnYIOgoIifbA2PiMuHtWvG91Cctsz+IE7wRQ4pFycAAWf4lsdQ1jkgiHW5tEz7XW7afDPxpPL1MyVZTtbzLBacmHsVch61gWDcBhadjbizx2xTJUzHW7UyIqp4Q7b/4v0P4bNyje2uD79alLH6YTlkbOT88DaGR/TPR/CQS/eouhfoqVMWWLN4BVjA8';

$request = '<?xml version="1.0" encoding="utf-8"?>
<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$token.'</eBayAuthToken>
  </RequesterCredentials>
  <OrderIDArray>
    <OrderID>121526107522-1677951284002</OrderID>
    <OrderID>111978074461-1558681181001</OrderID>
  </OrderIDArray>
  <OrderRole>Seller</OrderRole>
  <OrderStatus>Completed</OrderStatus>
  <DetailLevel>ReturnAll</DetailLevel>
  <Pagination> 
    <EntriesPerPage>55</EntriesPerPage> 
  </Pagination> 
</GetOrdersRequest>';

  // <OrderIDArray>
  //   <OrderID>122005720344-1667076997002</OrderID>
  // </OrderIDArray>

  // <CreateTimeFrom>2016-08-01T20:34:44.000Z</CreateTimeFrom>
  // <CreateTimeTo>2016-09-10T20:34:44.000Z</CreateTimeTo>

$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
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
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
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
