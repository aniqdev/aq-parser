<?php

class Ebay_shopping2{


	private $api_url = 'https://api.ebay.com/ws/api.dll';

	private function request($url, $post, $headers) 
	{
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


		static function findItemsAdvanced($request, $seller, $page = 1, $perPage = 100, $categoryId = false){
				 $url = "http://svcs.ebay.com/services/search/FindingService/v1";
				 $url .= "?OPERATION-NAME=findItemsAdvanced";
		//   $url .= "?OPERATION-NAME=findItemsByKeywords";
		//   $url .= "?OPERATION-NAME=findItemsByCategory";
				 $url .= "&SERVICE-VERSION=1.0.0";
				 $url .= "&SECURITY-APPNAME=Aniq6478a-a8de-47dd-840b-8abca107e57";
				 $url .= "&GLOBAL-ID=EBAY-DE";
				 $url .= "&RESPONSE-DATA-FORMAT=JSON";
				 $url .= "&REST-PAYLOAD";
		//   $url .= "&IncludeSelector=Details,Description,TextDescription";
				if ($seller != '0') {
				 $url .= "&itemFilter(0).name=Seller";
				 $url .= "&itemFilter(0).value=".$seller;
				}
				 if($categoryId) $url .= "&categoryId=".$categoryId;
				 if($request != '0') $url .= "&keywords=".rawurlencode($request);
				 $url .= "&paginationInput.entriesPerPage=$perPage";
				 $url .= "&paginationInput.pageNumber=".$page;
		//     $url .= "&sortOrder=currentPrice";


				// Открываем файл с помощью установленных выше HTTP-заголовков
				$json = file_get_contents($url);
				return $json;
				//return json_decode($json);
		}


		static function getSingleItem($itemId){
				$url = 'http://open.api.ebay.com/shopping';
				$url .= '?callname=GetSingleItem';
				$url .= '&responseencoding=JSON';
				$url .= '&appid=Aniq6478a-a8de-47dd-840b-8abca107e57';
		 $url .= '&siteid=77';
				$url .= '&version=515';
				$url .= '&ItemID='.$itemId;
				$url .= '&IncludeSelector=Details';
		//  $url .= '&IncludeSelector=Details,Description';
		//  $url .= '&IncludeSelector=Details,TextDescription';


				// Открываем файл с помощью установленных выше HTTP-заголовков
				$json = file_get_contents($url);
				return $json;
		}

		public function getSellerInfo($seller){
				$result = array(
						'status' => 'OK',
						'totalPages' => 0,
						'totalEntries' => 0
				);

				$json = self::findItemsAdvanced(0, $seller, 1, 1);

				$respArr = json_decode($json, true);

				if ($respArr != null && isset($respArr['findItemsAdvancedResponse'][0]['errorMessage'])) {
						$result['status'] = 'error';
						$result['errorMsg'] = $respArr['findItemsAdvancedResponse'][0]['errorMessage'][0]['error'][0]['message'][0];
				}else{
						$result['totalEntries'] = $respArr['findItemsAdvancedResponse'][0]['paginationOutput'][0]['totalEntries'][0];
						$result['totalPages'] = ceil($result['totalEntries']/100);
						$result['item0Id'] = $respArr['findItemsAdvancedResponse'][0]['searchResult'][0]['item'][0]['itemId'][0];
				}

				return $result;
		}

		public function getProductsBySeller($seller, $page = 1){
				$result = array(
						'status' => 'OK',
						'totalPages' => 0,
						'totalEntries' => 0,
						'curPage' => 0,
						'count' => 0,
						'items' => array()            
				);

				$json = self::findItemsAdvanced(0, $seller, $page);

				$respArr = json_decode($json, true);

				if ($respArr != null && isset($respArr['findItemsAdvancedResponse'][0]['errorMessage'])) {
						$result['status'] = 'error';
						$result['errorMsg'] = $respArr['findItemsAdvancedResponse'][0]['errorMessage'][0]['error'][0]['message'][0];
				}else{
						$result['totalEntries'] = $respArr['findItemsAdvancedResponse'][0]['paginationOutput'][0]['totalEntries'][0];
						$result['totalPages'] = ceil($result['totalEntries']/100);
						$result['curPage'] = $respArr['findItemsAdvancedResponse'][0]['paginationOutput'][0]['pageNumber'][0];
						$result['count'] = $respArr['findItemsAdvancedResponse'][0]['searchResult'][0]['@count'];
						$items = $respArr['findItemsAdvancedResponse'][0]['searchResult'][0]['item'];
						foreach ($items as $key => $item) {
								$result['items'][$key]['itemId'] = $item['itemId'][0];
								$result['items'][$key]['title']  = $item['title'][0];
								$result['items'][$key]['galleryURL']  = $item['galleryURL'][0];
								if(isset($item['galleryPlusPictureURL'][0]))
								$result['items'][$key]['galleryPlusPictureURL']  = $item['galleryPlusPictureURL'][0];
								else $result['items'][$key]['galleryPlusPictureURL'] = '';
								$result['items'][$key]['viewItemURL']  = $item['viewItemURL'][0];
								$result['items'][$key]['price']  = $item['sellingStatus'][0]['currentPrice'][0]['__value__'];
								$result['items'][$key]['currency']  = $item['sellingStatus'][0]['currentPrice'][0]['@currencyId'];
								$result['items'][$key]['convertedPrice']  = $item['sellingStatus'][0]['convertedCurrentPrice'][0]['__value__'];
								$result['items'][$key]['convertedCurrency']  = $item['sellingStatus'][0]['convertedCurrentPrice'][0]['@currencyId'];
								//$result['items'][$key]['price']  = $item['title'][0];
						}
				}

				return $result;
		}

		public function updateProductPrice($item_id, $price)
		{
				if(!$price || !$item_id) return false;

				$item_id = preg_replace('/\D/', '', $item_id);

				$headers = array
						(
						'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '837',
						'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
						'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
						'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
						'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
						'X-EBAY-API-SITEID: ' . '77',
				);

				$xml = '<?xml version="1.0" encoding="utf-8"?>
				<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					<RequesterCredentials>
						<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
					</RequesterCredentials>
					<Item ComplexType="ItemType">
						<ItemID>'.$item_id.'</ItemID>
						<Quantity>3</Quantity>
						<StartPrice>'.$price.'</StartPrice>
					</Item>
					<MessageID>1</MessageID>
					<WarningLevel>High</WarningLevel>
					<Version>837</Version>
				</ReviseItemRequest>​';

				$ch  = curl_init($this->api_url);     
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);                  
				curl_setopt($ch, CURLOPT_POST, true);              
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$responseXML = curl_exec($ch);
				curl_close($ch);


				//var_dump($responseXML);
				if (stripos($responseXML, 'Success') !== false) return true;
				return false;
		}

		public function removeFromSale($item_id)
		{
				if(!$item_id) return false;

				$item_id = preg_replace('/\D/', '', $item_id);

				$headers = array
						(
						'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '837',
						'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
						'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
						'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
						'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
						'X-EBAY-API-SITEID: ' . '77',
				);

				$xml = '<?xml version="1.0" encoding="utf-8"?>
				<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					<RequesterCredentials>
						<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
					</RequesterCredentials>
					<Item ComplexType="ItemType">
						<ItemID>'.$item_id.'</ItemID>
						<Quantity>0</Quantity>
					</Item>
					<MessageID>1</MessageID>
					<WarningLevel>High</WarningLevel>
					<Version>837</Version>
				</ReviseItemRequest>​';

				$ch  = curl_init($this->api_url);     
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);                  
				curl_setopt($ch, CURLOPT_POST, true);              
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				$responseXML = curl_exec($ch);
				curl_close($ch);


				//var_dump($responseXML);
				if (stripos($responseXML, 'Success') !== false) return true;
				return false;
		}


		// отправка сообщения пользователю ибей
	public function GetSellerListRequest($page=1, $entires=25){

		// if now 2016-10-15T12:32:57.376Z
		// <EndTimeFrom>2016-07-16T21:59:59.005Z</EndTimeFrom>
		// <EndTimeTo>2016-11-14T21:59:58.005Z</EndTimeTo>
		$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
		    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ErrorLanguage>en_US</ErrorLanguage>
		  <WarningLevel>High</WarningLevel>
		  <GranularityLevel>Coarse</GranularityLevel>
		  <EndTimeFrom>'.date('Y-m-d\TH:i:s.B\Z', time()-2592000*3).'</EndTimeFrom>
		  <EndTimeTo>'.date('Y-m-d\TH:i:s.B\Z', time()+2592000).'</EndTimeTo> 
		  <IncludeWatchCount>true</IncludeWatchCount>
		  <Pagination> 
		  	<PageNumber>'.$page.'</PageNumber>
		    <EntriesPerPage>'.$entires.'</EntriesPerPage> 
		  </Pagination> 
		</GetSellerListRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetSellerList",
		"X-EBAY-API-SITEID: 77",
		"Content-Type: text/xml");

		$result = $this->request($this->api_url, $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	/* результат выполнения функции
	Array
	(
	    [121606826889] => Call of Cthulhu: The Wasted Land (PC) Steam  -Region free- Multilanguage
	    . . .
	    [111630067823] => Two Worlds 2 II Collection (PC) Steam  -Region free- Multilanguage
	)
	*/
	public function GetSellerItemsArray()
	{
		$res2 = $this->GetSellerListRequest(1, 200);

		$ids_arr = [];
		foreach ($res2['ItemArray']['Item'] as $key => $item) {
			$ids_arr[$item['ItemID']] = $item['Title'];
		}

		$pages = $res2['PaginationResult']['TotalNumberOfPages'];

		for ($i=2; $i <= $pages; $i++) { 
			$res2 = $this->GetSellerListRequest($i, 200);
			foreach ($res2['ItemArray']['Item'] as $key => $item) {
				$ids_arr[$item['ItemID']] = $item['Title'];
			}
		}

		return $ids_arr;
	}


	public function updateQuantity($item_id,$amount)
	{
		if(!$item_id) return false;

		$item_id = preg_replace('/\D/', '', $item_id);

		$headers = array
				(
				'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '837',
				'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
				'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
				'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
				'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
				'X-EBAY-API-SITEID: ' . '77',
		);

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Quantity>'.$amount.'</Quantity>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

} // class Ebay_shopping 2