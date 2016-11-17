<pre>
<?php
ini_get('safe_mode') or set_time_limit(180); // Указываем скрипту, чтобы не обрывал связь.

$url = 'https://api.ebay.com/ws/api.dll';
$token = EBAY_GIG_TOKEN;

function request($url, $post, $headers) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 170);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function GetMessages($MessageID = ""){
	global $url, $token;

	$post = '<?xml version="1.0" encoding="utf-8"?>
<GetMyMessagesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>'.$token.'</eBayAuthToken>
  </RequesterCredentials>
  <WarningLevel>High</WarningLevel>
  <Pagination>
    <EntriesPerPage>1000</EntriesPerPage>
    <PageNumber>1</PageNumber>
  </Pagination>';

  //если указаны ID конкретных сообщений, то выдаем их полный текст
  if($MessageID){
	  $post .= '<DetailLevel>ReturnMessages</DetailLevel>';
		$post .= '<MessageIDs>
					<MessageID>'.$MessageID.'</MessageID>
				</MessageIDs>';
	  }
  //в противном случае выдаем лишь заголовки
  else{
	$post .= '<DetailLevel>ReturnHeaders</DetailLevel>';
  }
$post .= '</GetMyMessagesRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	"X-EBAY-API-CALL-NAME: GetMyMessages",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

$messages = GetMessages();

$mtype = [];
$m_ids = [];
foreach ($messages['Messages']['Message'] as $m) {
	$mtype[@$m['MessageType']] += 1;
	$m_ids[$m['MessageID']] = $m['ReceiveDate'];
}

print_r($mtype);
// print_r($m_ids);
print_r($messages);

?>
</pre>