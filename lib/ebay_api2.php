<?php

	/* ищет товары по ключевым словам ВО ВСЕХ store	
	// в качестве ключевых слов понимает слова, partnumber или itemID
	// не понимает доставку за пределы США
	*/
/**
* 
*/
class Ebay_shopping{
	
	function __construct(){
		# code...
	}


	static function findItemsAdvanced($request){
	    $headers = array(
	      'X-EBAY-SOA-SERVICE-NAME:FindingService',
	      'X-EBAY-SOA-OPERATION-NAME:findItemsAdvanced',                              
	      'X-EBAY-SOA-SERVICE-VERSION:1.12.0',
	      'X-EBAY-SOA-GLOBAL-ID:EBAY-DE',
	      'X-EBAY-SOA-SECURITY-APPNAME:Aniq6478a-a8de-47dd-840b-8abca107e57', // AppID будет жить здесь 
	      "X-EBAY-API-REQUEST-ENCODING: json",
	      "RESPONSE-DATA-FORMAT=json",
	      'Content-Type: text/xml;charset=utf-8',
	    );
	    $endpoint = 'http://svcs.ebay.com/services/search/FindingService/v1?';
	   

	    $xmlRequest  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
	    $xmlRequest .= "<findItemsAdvancedRequest xmlns='http://www.ebay.com/marketplace/search/v1/services'>";

	    $xmlRequest .= "<categoryId>139973</categoryId>"; // 10063 - запчасти для мотоциклов // 6028 - запчасти к транспорту вообще
	    $xmlRequest .= "<descriptionSearch>false</descriptionSearch>"; // иногда может найти полную ерунду, отключено
	    $xmlRequest .= "<keywords>".$request."</keywords>"; // самое главное: текст вашего запроса
	    $xmlRequest .= "<itemFilter><name>Condition</name><value>New</value></itemFilter>"; // состояние товара: новый, б/у и их варианты
	    $xmlRequest .= "<itemFilter><name>FeedbackScoreMin</name><value>3000</value></itemFilter>"; // отсекаем новичков
	    $xmlRequest .= "<itemFilter><name>ListingType</name><value>FixedPrice</value></itemFilter>";  // отсекаем аукционные торги
	    $xmlRequest .= "<itemFilter><name>AvailableTo</name><value>RU</value></itemFilter>"; 
		// эта фича частенько не работает и пропускает товары, которые продавец на самом деле не отправляет в Россию. Сейчас таких продавцов стало намного больше, поэтому мы отстроимся от них в другом запросе еще раз. Чтобы гарантированно не попасть.

	    $xmlRequest .= "<itemFilter><name>PaymentMethod</name><value>PayPal</value></itemFilter>"; 
		// в некоторых странах существуют платежные системы популярнее Paypal. Для нас они недоступны либо труднодоступны, поэтому лучше сразу отсечь продавцов-смельчаков, которые не принимают Paypal. Пусть eBay разбирается с ними сам.

	    $xmlRequest .= "<itemFilter><name>HideDuplicateItems</name><value>true</value></itemFilter>";
		// нам же не нужны дубликаты в выдаче? Уберем их.
		
	   //$xmlRequest .= "<itemFilter><name>ExcludeSeller</name><value>storename</value></itemFilter>"; // на всякий случай: можно исключить вредных и нехороших продавцов из результатов выдачи. Даже если они прикидываются белыми и пушистыми.

	    $xmlRequest .= "<outputSelector>SellerInfo</outputSelector>"; // посмотрим что за продавцы попали в наши сети

	    $xmlRequest .= "</findItemsAdvancedRequest>";

	    $xmlRequest2 = '
<?xml version="1.0" encoding="utf-8"?>
<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>AgAAAA**AQAAAA**aAAAAA**JNIUVg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AHkISmCZaAow6dj6x9nY+seQ**mAMDAA**AAMAAA**/j4gDX1RRhkCa01HjkvcNdrnFY6KhwyFeALnWqzB06zIrqhykwJnxd7YlnvojMa8+OZUrLdd9xSDZ7a9AsafI6Z8vaRUmFWBhBtRE/X7T72vRJWdMEzRYdGyMIlW5vQZfiJXzuyer1KZMSxWaB3xRWpeIY+MlQ6DU2DvEDfkQciwcega6DaBKX+Zr1or/9r/uCVLcOZJZhHhZhPWSiuWTrxDP4o9r2EPeaqdZUBfjiYc6ku1kiyUYLFDj3zR3IYsE4eBn0xdHzPxcFN7Cmr2756DoM9Agi+Sh0mD5q43gyP/oFtmd5qV3wWiG3Oa5YgsDnXYjeWS0Z6x2WbE0vCzd50nMsZbxUvbbV3o29G7/FY/klLQdmwg7CZisV3Va6wC8ctpOhgd8izLJGAUjpEkHpJW99d7riwfeBsR2FUelQKwAUShIJ4qe+qTh2AhqwAvqXUyCjbk1eqTiWzWZExaa3Met099M1FyFnuC8UIINe8xvifS5aFx1UoP5dqBld3vgudpA+u4VvWGXILXa4KbYMjzhZW/oSjzy7/1iwQcChTRLh+gtCO3NzP5IhJr8bRoSoRc28zr+yBZfFe+SPZJsCIVf9Ih4VqDaztjtc6TUKteJalqD2wkzn3TMjOossQ/ys6vIAOjWWFhYXntQHN5rU6di3daGUZk2OKzd/ah5v53XUJjdbOajPClHkBBljjLQwn2w+njifjjoN+HW0Garnj+xoBSRwaVfWn6764aIfqRyDMQWfQS50yINF+LGZNT</eBayAuthToken>
  </RequesterCredentials>
  <ErrorLanguage>en_US</ErrorLanguage>
  <WarningLevel>High</WarningLevel>
  <GranularityLevel>Coarse</GranularityLevel> 
  <StartTimeFrom>2010-02-12T21:59:59.005Z</StartTimeFrom> 
  <StartTimeTo>2016-02-26T21:59:59.005Z</StartTimeTo> 
  <IncludeWatchCount>true</IncludeWatchCount> 
  <Pagination> 
    <EntriesPerPage>2</EntriesPerPage> 
  </Pagination> 
</GetSellerListRequest>';

		$xmlRequest3 = '
<?xml version="1.0" encoding="utf-8"?>
<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
  <RequesterCredentials>
    <eBayAuthToken>AgAAAA**AQAAAA**aAAAAA**JNIUVg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AHkISmCZaAow6dj6x9nY+seQ**mAMDAA**AAMAAA**/j4gDX1RRhkCa01HjkvcNdrnFY6KhwyFeALnWqzB06zIrqhykwJnxd7YlnvojMa8+OZUrLdd9xSDZ7a9AsafI6Z8vaRUmFWBhBtRE/X7T72vRJWdMEzRYdGyMIlW5vQZfiJXzuyer1KZMSxWaB3xRWpeIY+MlQ6DU2DvEDfkQciwcega6DaBKX+Zr1or/9r/uCVLcOZJZhHhZhPWSiuWTrxDP4o9r2EPeaqdZUBfjiYc6ku1kiyUYLFDj3zR3IYsE4eBn0xdHzPxcFN7Cmr2756DoM9Agi+Sh0mD5q43gyP/oFtmd5qV3wWiG3Oa5YgsDnXYjeWS0Z6x2WbE0vCzd50nMsZbxUvbbV3o29G7/FY/klLQdmwg7CZisV3Va6wC8ctpOhgd8izLJGAUjpEkHpJW99d7riwfeBsR2FUelQKwAUShIJ4qe+qTh2AhqwAvqXUyCjbk1eqTiWzWZExaa3Met099M1FyFnuC8UIINe8xvifS5aFx1UoP5dqBld3vgudpA+u4VvWGXILXa4KbYMjzhZW/oSjzy7/1iwQcChTRLh+gtCO3NzP5IhJr8bRoSoRc28zr+yBZfFe+SPZJsCIVf9Ih4VqDaztjtc6TUKteJalqD2wkzn3TMjOossQ/ys6vIAOjWWFhYXntQHN5rU6di3daGUZk2OKzd/ah5v53XUJjdbOajPClHkBBljjLQwn2w+njifjjoN+HW0Garnj+xoBSRwaVfWn6764aIfqRyDMQWfQS50yINF+LGZNT</eBayAuthToken>
  </RequesterCredentials>
  <!-- Insert a valid ItemID from a search (on Production or Sandbox, whichever is fitting). -->
  <ItemID>110043671232</ItemID>
</GetItemRequest>';

	     $session  = curl_init($endpoint);                       
	    curl_setopt($session, CURLOPT_POST, true);              
	    curl_setopt($session, CURLOPT_POSTFIELDS, $xmlRequest3); 
	    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);    
	    curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
	    return $responseXML = curl_exec($session);
	    curl_close($session);
	}
} // class Ebay_shopping


header('X-Accel-Buffering: no');
ob_get_flush();

$responseXML = Ebay_shopping::findItemsAdvanced('Need for Speed'); 
	// "cobra exhaust c90t" — это значит "глушитель марки Cobra для мотоцикла Suzuku Boulevard C90T". Если вы вставите в запрос "iPhone", то в этой категории вы не найдете ни одного айфона: вам будет нужно указать <categoryId>9355</categoryId>.

$responseXML = simplexml_load_string($responseXML); 
echo "<pre>";
var_dump ($responseXML);
echo "</pre>";
?>