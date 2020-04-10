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
  <WarningLevel>High</WarningLevel>';

  //если указаны ID конкретных сообщений, то выдаем их полный текст
  if($MessageID){
	  $post .= '<DetailLevel>ReturnMessages</DetailLevel>';
		$post .= '<MessageIDs>
					<MessageID>'.$MessageID.'</MessageID>
					<MessageID>83585538220</MessageID>
					<MessageID>83575798490</MessageID>
				</MessageIDs>';
		// $post .= '<ExternalMessageIDs>
		// 			<ExternalMessageID>1400861478010</ExternalMessageID>
		// 		</ExternalMessageIDs>';
	  }
  //в противном случае выдаем лишь заголовки
  else{
	$post .= '<DetailLevel>ReturnSummary</DetailLevel>';
  }
$post .= '</GetMyMessagesRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	"X-EBAY-API-CALL-NAME: GetMyMessages",
	"X-EBAY-API-SITEID: 0",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

$message = GetMessages('83671097850');

if(isset($message['Messages']['Message']['Sender']))
	$message['Messages']['Message'] = [$message['Messages']['Message']];

foreach ($message['Messages']['Message'] as $key => &$Message) {

	$mess_text = $Message['Text'];
	$dom = str_get_html($mess_text);
	$client_msg = $dom->find('#UserInputtedText', 0)->innertext;
	echo "<pre>";
	echo $client_msg;
	echo '</pre>';
	// unset($Message['Text']);
	// unset($Message['Content']);
}
// $mess_text = $message['Messages']['Message']['Text'];
// $dom = str_get_html($mess_text);
// $client_msg = $dom->find('#UserInputtedText', 0)->innertext;
// $mess_content = $message['Messages']['Message']['Content'];
// file_put_contents('lib/adds/mess-text.html', $mess_text);
// unset($message['Messages']['Message']['Text']);
// unset($message['Messages']['Message']['Content']);
print_r($message);

?>
</pre>
<div class="ppp-block">
<?php
//echo $client_msg;
?>
</div>
<iframe src="lib/adds/mess-text.html" frameborder="0" style="width:100%;height:750px;background:#fff"></iframe>
<style>
	body{
		    padding-top: 50px!important;
	}
</style>