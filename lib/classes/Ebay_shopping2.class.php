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


		static function getSingleItem($itemId, $as_array = 0){
				$url = 'http://open.api.ebay.com/shopping';
				$url .= '?callname=GetSingleItem';
				$url .= '&responseencoding=JSON';
				$url .= '&appid=Aniq6478a-a8de-47dd-840b-8abca107e57';
		 $url .= '&siteid=77';
				$url .= '&version=515';
				$url .= '&ItemID='.$itemId;
				$url .= '&IncludeSelector=Details,ItemSpecifics';
		//  $url .= '&IncludeSelector=Details,Description';
		//  $url .= '&IncludeSelector=Details,TextDescription';


				// Открываем файл с помощью установленных выше HTTP-заголовков
				$json = file_get_contents($url);
				if($as_array) return json_decode($json, true);
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

		public function updateProductPrice2($item_id, $price)
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

			return simplexml_load_string($responseXML);
		}

		public function removeFromSale($item_id)
		{
				$item_id = preg_replace('/\D/', '', $item_id);
				if(!$item_id) return false;


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
		$completed_arr = [];
		$pics_arr = [];
		$regex = '#/[^s]/(.+)/#';
		foreach ($res2['ItemArray']['Item'] as $key => $item) {
			if($item['SellingStatus']['ListingStatus'] === 'Completed') 
				$completed_arr[] = $item['ItemID'];
			else $ids_arr[$item['ItemID']] = $item['Title'];
			if (isset($item['PictureDetails']['PictureURL'])) {
				if (!is_array($item['PictureDetails']['PictureURL'])) {
					$item['PictureDetails']['PictureURL'] = [$item['PictureDetails']['PictureURL']];
				}
				$found = preg_match($regex, $item['PictureDetails']['PictureURL'][0], $matches);
				$pics_arr[$item['ItemID']] = $matches[1];
			}
		}

		$pages = $res2['PaginationResult']['TotalNumberOfPages'];

		for ($i=2; $i <= $pages; $i++) { 
			$res2 = $this->GetSellerListRequest($i, 200);
			foreach ($res2['ItemArray']['Item'] as $key => $item) {
				if($item['SellingStatus']['ListingStatus'] === 'Completed')
					$completed_arr[] = $item['ItemID'];
				else $ids_arr[$item['ItemID']] = $item['Title'];
				if (isset($item['PictureDetails']['PictureURL'])) {
					if (!is_array($item['PictureDetails']['PictureURL'])) {
						$item['PictureDetails']['PictureURL'] = [$item['PictureDetails']['PictureURL']];
					}
					$found = preg_match($regex, $item['PictureDetails']['PictureURL'][0], $matches);
					$pics_arr[$item['ItemID']] = $matches[1];
				}
			}
		}

		return ['active' => $ids_arr, 
				'completed' => $completed_arr,
				'pictres' => $pics_arr];
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



	public function GetMessages($folder = 'inbox', $EntriesPerPage = 100)
	{
		$FolderID = '0';
		if ($folder === 'outbox') {
			$FolderID = '1';
		}
		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
						"X-EBAY-API-CALL-NAME: GetMyMessages",
						"X-EBAY-API-SITEID: 0",
						"Content-Type: text/xml");

		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetMyMessagesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <WarningLevel>High</WarningLevel>
				  <FolderID>'.$FolderID.'</FolderID>
				  <Pagination>
				    <EntriesPerPage>'.$EntriesPerPage.'</EntriesPerPage>
				    <PageNumber>1</PageNumber>
				  </Pagination>
				  <DetailLevel>ReturnHeaders</DetailLevel>
				</GetMyMessagesRequest>';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	function GetMessageBody($MessageID = ""){

		$post = '<?xml version="1.0" encoding="utf-8"?>
				<GetMyMessagesRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <WarningLevel>High</WarningLevel>
					<DetailLevel>ReturnMessages</DetailLevel>
					<MessageIDs>
						<MessageID>'.$MessageID.'</MessageID>
					</MessageIDs>
				</GetMyMessagesRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
		"X-EBAY-API-CALL-NAME: GetMyMessages",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result_xml = $this->request($this->api_url, $post, $headers);
		$result_arr = json_decode(json_encode(simplexml_load_string($result_xml)), true);
		if($result_arr['Ack'] === 'Failure') return false;

		if(isset($result_arr['Messages']['Message']['Sender']))
			$result_arr['Messages']['Message'] = [$result_arr['Messages']['Message']];

		$message = $result_arr['Messages']['Message'][0];
		if(!$message) return false;

		$mess_text = $message['Text'];
		$dom = str_get_html($mess_text);
		$transId = '0';
		if (preg_match('/Transaktionsnummer: (.+)<br>/', $mess_text, $matches)) $transId = $matches[1];
		$msg_body = $dom->find('#UserInputtedText', 0)->innertext;
		return ['msg_body'=>$msg_body,'transId'=>$transId];
	}


	//ответ на вопрос от пользователя
	function AnswerQuestion($RecipientID, $ParentMessageID, $text, $is_public = 'false'){

	$post = '<?xml version="1.0" encoding="utf-8"?>
			<AddMemberMessageRTQRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			  <RequesterCredentials>
			    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			  </RequesterCredentials>
			  <MemberMessage>
			    <Body>'.htmlspecialchars($text).'</Body>
			    <DisplayToPublic>'.$is_public.'</DisplayToPublic>
			    <EmailCopyToSender>true</EmailCopyToSender>
			    <ParentMessageID>'.$ParentMessageID.'</ParentMessageID>
			    <RecipientID>'.$RecipientID.'</RecipientID>
			  </MemberMessage>
			</AddMemberMessageRTQRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
			"X-EBAY-API-CALL-NAME: AddMemberMessageRTQ",
			"X-EBAY-API-SITEID: 0",
			"Content-Type: text/xml");

		$result = $this->request($this->api_url, $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function updateItemTitle($item_id, $title)
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id) return false;

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
				<Title>'.htmlspecialchars($title).'</Title>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	//функция для получения специализированных настроек для категории (в нашем случае для категории Компьютерные игры)
	public function GetCategorySpecifics($categoryId){

		$post = '<?xml version="1.0" encoding="utf-8"?>
			<GetCategorySpecificsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<WarningLevel>High</WarningLevel>
			  <RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			  </RequesterCredentials>
			  <CategorySpecific>
				<CategoryID>'.$categoryId.'</CategoryID>
			  </CategorySpecific>
			  <MaxValuesPerName>999</MaxValuesPerName>
			</GetCategorySpecificsRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
		    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
		    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
		    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetCategorySpecifics",
		"X-EBAY-API-SITEID: 77",
		"Content-Type: text/xml");

		$result = $this->request($this->api_url, $post, $headers);
		// return $result;
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function UpdateCategorySpecifics($item_id, $specifics=[])
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$specifics) return false;

		$headers = array
				(
				'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '941',
				'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
				'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
				'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
				'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
				'X-EBAY-API-SITEID: ' . '0',
		);

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>';
				$xml .= '<ItemSpecifics>';
				foreach($specifics as $key => $specific){
					if($specific){
						$xml .= '<NameValueList><Name>'.$key.'</Name>';
						if(is_array($specific)){
								foreach($specific as $value){
									$xml .= '<Value>'.htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
								}

						}
						else{
							
							$xml .= '	<Value>'.htmlspecialchars($specific, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
								
						}
						$xml .= '</NameValueList>';
					}
				}
				$xml.= '</ItemSpecifics>';
			$xml .= '</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>941</Version>
			<ErrorLanguage>en_US</ErrorLanguage>
		</ReviseItemRequest>​';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function updateItemPictureDetails($item_id, $urls = [])
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$urls || !is_array($urls)) return false;

		$headers = array
				(
				'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '983',
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
				<PictureDetails>';
					foreach ($urls as $url) {
						$xml .= '<PictureURL>'.trim($url).'</PictureURL>';
					}
		$xml .= '</PictureDetails>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>983</Version>
		</ReviseItemRequest>​';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function addItem($item = [])
	{
		if(!$item) return false;

		$post = '<?xml version="1.0" encoding="utf-8"?>
			<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<WarningLevel>High</WarningLevel>
			  <RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			  </RequesterCredentials>
			  <Item>
				    <ProductListingDetails>
						<EAN>Nicht zutreffend</EAN>
					</ProductListingDetails>
			   <Title>'.htmlspecialchars($item['Title']).'</Title>
				<Description>'.htmlspecialchars($item['Description'], ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Description>
				<PrimaryCategory>
				  <CategoryID>'.$item['CategoryID'].'</CategoryID>
				</PrimaryCategory>
				<ConditionID>'.$item['ConditionID'].'</ConditionID>
				<Currency>EUR</Currency>
				<ListingType>FixedPriceItem</ListingType>
				<Quantity>'.$item['Quantity'].'</Quantity>';
		
		//если указана одна из категорий нашего магазина, то добавляем это в листинг
		if($item['StoreCategory1'] || $item['StoreCategory2']){
			$post .= '<Storefront>';
			if(isset($item['StoreCategory1'])) $post .= '<StoreCategoryID>'.$item['StoreCategory1'].'</StoreCategoryID>';
			if(isset($item['StoreCategory2'])) $post .= '<StoreCategory2ID>'.$item['StoreCategory1'].'</StoreCategory2ID>';
			$post .= '</Storefront>';
		}
		
		$post .= '<PictureDetails>';
				foreach($item['PictureURL'] as $picture){
					if($picture) $post .= '<PictureURL>'.$picture.'</PictureURL>';
				}
				$post .= '</PictureDetails>
					<StartPrice currencyID="EUR">'.$item['price'].'</StartPrice>
					<BestOfferDetails>
						<BestOfferEnabled>'.$item['BestOfferEnabled'].'</BestOfferEnabled>
					</BestOfferDetails>
					<Site>Germany</Site>
					<Country>DE</Country>
					<DispatchTimeMax>3</DispatchTimeMax>
					<ListingDuration>'.$item['ListingDuration'].'</ListingDuration>
					<PostalCode>51145</PostalCode>
					<PaymentMethods>PayPal</PaymentMethods>
					<PaymentMethods>MoneyXferAccepted</PaymentMethods>
					<PayPalEmailAddress>konstantin@gig-games.de</PayPalEmailAddress>
					<ReturnPolicy>
						<ReturnsAcceptedOption>ReturnsAccepted</ReturnsAcceptedOption>
						<RefundOption>MoneyBack</RefundOption>
						<ReturnsWithinOption>Days_30</ReturnsWithinOption>
						<Description>If you are not satisfied, return the book for refund.</Description>
						<ShippingCostPaidByOption>Buyer</ShippingCostPaidByOption>
					</ReturnPolicy>
					<ShippingDetails>
						<ShippingType>Flat</ShippingType>
						<ShippingServiceOptions>
							<ShippingServicePriority>1</ShippingServicePriority>
							<ShippingService>DE_Express</ShippingService>
							<ShippingServiceCost>0</ShippingServiceCost>
						</ShippingServiceOptions>
					</ShippingDetails>
					';

				

				if($item['specific']){
					$post .= '<ItemSpecifics>';
					foreach($item['specific'] as $key=>$specific){
						if($specific){
							$post .= '<NameValueList><Name>'.$key.'</Name>';
							if(is_array($specific)){
									foreach($specific as $value){
										$post .= '<Value>'.htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
									}

							}
							else{
								
								$post .= '	<Value>'.htmlspecialchars($specific, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
									
							}
							$post .= '</NameValueList>';
						}
					}
					$post.= '</ItemSpecifics>';
				}
				$post .=	'
				</Item>
	  <Version>967</Version>
				</AddItemRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
		    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
		    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
		    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
			"X-EBAY-API-CALL-NAME: AddItem",
			"X-EBAY-API-SITEID: 77",
			"Content-Type: text/xml");
		file_put_contents(__DIR__.'/../adds/add-item-post.xml', $post);

		$result = $this->request($this->api_url, $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function updateItemDescription($item_id, $desc = false)
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$desc) return false;

		$headers = array
				(
				'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '983',
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
				<Description>'.htmlspecialchars($desc, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Description>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>983</Version>
		</ReviseItemRequest>​';

		$result = $this->request($this->api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function test($item_id, $desc = false)
	{

		$headers = array
				(
				'X-EBAY-API-COMPATIBILITY-LEVEL: ' . '983',
				'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
				'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
				'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
				'X-EBAY-API-CALL-NAME: ' . 'ReviseItem',
				'X-EBAY-API-SITEID: ' . '77',
				'Content-Type' . 'application/x-www-form-urlencoded',
				'Authorization:' . 'Bearer v^1.1#i^1#f^0#r^0#p^3#I^3#t^H4sIAAAAAAAAAOVXW2wUVRje7Q2x1AspKlBgmUI0kNk9Z3Z2dnforixtkaaUrt1y1QpnZs5sB2ZnNnNpuyHGUgJGCEF9ECQBGiWoGOUqGo3hAdRoICEBAkqABEPUhMRbjBq0emZ7YVu1pS0PTdyXzZzzX77/+7//zBzQXjR+zuZFm38tcY/L62wH7XluNywG44sK596Xnzel0AVyDNyd7bPaCzryv60wUUpN8w3YTOuaiT1tKVUz+exihLINjdeRqZi8hlLY5C2RT8TqFvOMF/BpQ7d0UVcpT01VhBICmAtxAoBBFgUhwmRV643ZqEcoUeAAgkJIYnFACCGG7JumjWs000KaFaEY4kkDlgZcI+B4P+Ah9MJweBXlWYYNU9E1YuIFVDQLl8/6GjlYB4eKTBMbFglCRWtiCxP1sZqq6iWNFb6cWNEeHhIWsmyz/1OlLmHPMqTaePA0ZtaaT9iiiE2T8kW7M/QPysd6wYwAfpZqjpUCTJgLSjAAkBAI3xUqF+pGClmD43BWFImWs6Y81izFygzFKGFDWItFq+dpCQlRU+Vx/p60karICjYiVPWC2MqlieoGypOIxw29RZGw5FQKuRAT9ocAAZtUknTSQdSToztQD8MDklTqmqQ4fJmeJbq1ABPAeCAtTA4txKheqzdisuWAybUL9dEXWOX0s7uBttWsOS3FKcKBJ/s4NPm9arjd/7umByAxEMsoKDIywBL4dz04sz48TUSdtsTicR8WUIZOIWMdttIqEjEtEmrtFDYUiWdZgeWQKNN+JiTSRJkiHQ4HMM1gDnMM8rNAZv8nsrAsQxFsC/dJY+BGtr4IlRD1NI7rqiJmqIEm2VOmRwhtZoRqtqw07/O1trZ6W/1e3Uj6GACgb0Xd4oTYjFOI6rNVhjamlawkRHI4E3veyqQJmjaiOJJcS1JRvyHFkWFlElhVyUKvXvthiw5c/Y8iTafIsVWe42+SACiteB1Je0U95dMRGV9naXUWsedOjHwmIcjbPRAkstfASNI1NTMS52H4KFoLEZVuZIZM6Mz6YAGGkRSJom5r1khq7HEdhodsq7Kiqs7sjCRhjvtwYGpIzViKaPalHJXwY+l0jTS2hF+rd78KaHKIrsXrLEjHG6poAeFAkJPkAA1FgLHol0ZVt4RbFBGvVsZY7ZqtqqOqqy451koKsWGOCUI/R7wqfGTWy0dRXhVuGWtyFaHMyJBhacj4GZoVRJYW5DCmQ5Dj/ExYxBwbHlVLK1WFHBGNmbH2glqkmxYe3RRWkg/DsVWUc9T0njRhEIQ0CjASzWIo0CEhGKLDMMDdackDFnI+tP7xae3rf62NurI/2OF+D3S4D5ObMfCB2bAczCzKX1qQP2GKqVjYqyDZaypJjdzWDOxdhzNppBh5Re6nyg69tTrnIt3ZBB7pu0qPz4fFOfdqUHZ7pxDe/3AJDAIWcIDzAwhXgfLbuwXwoYLS0i/3iNOfPvfb8cu0q3rSvAdPntpTC0r6jNzuQldBh9vV2bDo1slrXx90HS4AO6rWnO7aun1h158lO6e+Gom4jx6rfOly3by80khw4vIZpdPqZqmn761auW/+jydubt93dqKveEPpsa3bZt3TvGL6F51nrz62683tG5der71w8NbeFxuZ2j8enfHy9ZvNP2/ad7JV+uqKd+Jrv/Cuj5ovHjh+KX3epZS9c+X32qufH3jj0oXaV/bv+L6pKbxbmAwvpt9u6Zr9IY1vHtlWO+eT4mvlR6Y2TMqfkjn7wdwn9hecuHFGnnv4fXPj+p0fn3rh+aOvryn/qxBNmF/ftPfipS2h5T/hM7b97MxPd3UJn40r8+1/d/cD35zfMLliQ/DAuaZpR9bbz2g/PL7iRtuh576bvSnZ3b6/AdLalI/iEAAA',
		);

		$xml = '';

		$url = 'https://api.ebay.com/sell/analytics/v1/traffic_report?filter=marketplace_ids:%7BEBAY_US%7D,date_range:%5B20160601..20160828%5D&dimension=DAY&metric=LISTING_IMPRESSION_SEARCH_RESULTS_PAGE,LISTING_IMPRESSION_STORE,SALES_CONVERSION_RATE';

		$params = [
			'filter' => 'marketplace_ids:{EBAY_DE},date_range:[20170220..20170320],listing_ids:{111613110094}',
			'dimension' => 'DAY',
			'metric' => 'LISTING_IMPRESSION_SEARCH_RESULTS_PAGE,LISTING_IMPRESSION_TOTAL,SALES_CONVERSION_RATE,SALES_CONVERSION_RATE',
		];
		$url = 'https://api.ebay.com/sell/analytics/v1/traffic_report?' . http_build_query($params);

		// $url = 'https://api.ebay.com/sell/analytics/v1/traffic_report?filter=marketplace_ids:%7BEBAY_US%7D,date_range:%5B20161007..20161009%5D&dimension=LISTING&metric=LISTING_IMPRESSION_SEARCH_RESULTS_PAGE,LISTING_IMPRESSION_STORE,SALES_CONVERSION_RATE&sort=LISTING_IMPRESSION_STORE';

		$res = $this->request($url, $xml, $headers);
   		//$res = file_get_contents($url);
		return json_decode($res,1);
		return json_decode(json_encode(simplexml_load_string($res)), true);
	}


	public function getToken($item_id, $desc = false)
	{

		$headers = array
				(
				'Content-Type:' . 'application/x-www-form-urlencoded',
				'Authorization:' . 'Basic '.base64_encode('Konstant-Projekt1-PRD-bae576df5-1c0eec3d:PRD-ae576df59071-a52d-4e1b-8b78-9156'),
		);

		$xml = http_build_query(['grant_type'=>'authorization_code',
    'code'=>'v^1.1#i^1#p^3#f^0#r^1#I^3#t^Ul41Xzg6QkU1QTM1OTlCOTM4MDk5MzA4QkY5ODkzRTU0NUQyNENfMl8xI0VeMjYw',
    'redirect_uri'=>'Konstantin_Falk-Konstant-Projek-dtvnra']);

		// $xml = http_build_query(['grant_type'=>'authorization_code',
  //   'code'=>'v^1.1#i^1#f^0#I^3#p^3#r^1#t^Ul41XzM6RjU4ODc3OEJFRkZENDZBNjQ2OEJDMTQ2OUNCQTEwRThfMF8xI0VeMjYw',
  //   'redirect_uri'=>'Konstantin_Falk-Konstant-Projek-dtvnra']);

		$url = 'https://api.ebay.com/identity/v1/oauth2/token';

		$res = $this->request($url, $xml, $headers);
   		//$res = file_get_contents($url);
		return json_decode($res,1);
		return json_decode(json_encode(simplexml_load_string($res)), true);
	}

} // class Ebay_shopping 2