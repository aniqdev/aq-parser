<?php

/*
* 
*/
class EbayOrders
{
	
	private static $api_url = 'https://api.ebay.com/ws/api.dll';

	function __construct()
	{

	}


	private static function get_api_url($value='')
	{
		return self::$api_url;
	}


	private static function request($url, $post, $headers) 
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


	private static function getHeaders($api_name, $version = '967')
	{
		return array("X-EBAY-API-COMPATIBILITY-LEVEL: $version",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: $api_name",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");
	}


	public function getOrders($c = [])
	{
		$c = array_merge([  'order_status'=>'All',
							'EntriesPerPage'=>'200',
							'PageNumber'=>'1',
							'CreateTimeFrom'=>false,
							'CreateTimeTo'=>false,
							'NumberOfDays'=>false,
							'OrderIDArray'=>[],
							'SortingOrder'=>'Descending'], $c);

		if ($c['CreateTimeFrom'] && $c['CreateTimeTo']) {
			$period =   '<CreateTimeFrom>2016-09-13T00:00:00.000Z</CreateTimeFrom>'.
			 			'<CreateTimeTo>2016-09-16T00:00:00.000Z</CreateTimeTo>';
			$period =   '<CreateTimeFrom>'.$c['CreateTimeFrom'].'</CreateTimeFrom>'.
			 			'<CreateTimeTo>'.$c['CreateTimeTo'].'</CreateTimeTo>';
		}elseif($c['NumberOfDays']){
			$period = '<NumberOfDays>'.$c['NumberOfDays'].'</NumberOfDays>';
		}elseif($c['OrderIDArray'] && is_array($c['OrderIDArray'])){
			$period = '<OrderIDArray>';
			foreach ($c['OrderIDArray'] as $oid) {
				$period .=  "<OrderID>$oid</OrderID>";
			}
			$period .=  '</OrderIDArray>';
		}else{
			$period = '<NumberOfDays>2</NumberOfDays>';
		}

		$post = '<?xml version="1.0" encoding="utf-8"?>
			<GetOrdersRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			  <RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			  </RequesterCredentials>
			  <OrderStatus>'.$c['order_status'].'</OrderStatus>'.
			  $period.
			  '<OrderRole>Seller</OrderRole>
			  <Pagination> 
				<EntriesPerPage>'.$c['EntriesPerPage'].'</EntriesPerPage>
	      		<PageNumber>'.$c['PageNumber'].'</PageNumber>
			  </Pagination>
			  <SortingOrder>'.$c['SortingOrder'].'</SortingOrder>
			  <WarningLevel>Low</WarningLevel>
			</GetOrdersRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetOrders",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result = $this->request($this->get_api_url(), $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}




	//помечает товар(заказ) как доставленный покупателю
	public function MarkAsShipped($OrderID, $status = 'true'){
		
		if(defined('DEV_MODE')) return ['mode'=>'dev','Ack'=>'Success'];
		$post = '<?xml version="1.0" encoding="utf-8"?>
		<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
			<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <WarningLevel>High</WarningLevel>
		  <Shipped>'.$status.'</Shipped>
		  <OrderID>'.$OrderID.'</OrderID>
		</CompleteSaleRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: CompleteSale",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result = $this->request($this->get_api_url(), $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	//помечает товар(заказ) как оплаченный
	public function MarkAsPaid($OrderID, $status = 'true'){

		if(defined('DEV_MODE')) return ['mode'=>'dev','Ack'=>'Success'];
		$post = '<?xml version="1.0" encoding="utf-8"?>
		<CompleteSaleRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
			<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <WarningLevel>High</WarningLevel>
		  <Paid>'.$status.'</Paid>
		  <OrderID>'.$OrderID.'</OrderID>
		</CompleteSaleRequest>';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: CompleteSale",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result = $this->request($this->get_api_url(), $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	// отправка сообщения пользователю ибей
	public function SendMessage($user, $itemId, $subject, $body){

		$post = '<?xml version="1.0" encoding="utf-8"?>
		<AddMemberMessageAAQToPartnerRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
		    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ItemID>'.$itemId.'</ItemID>
		  <MemberMessage>
		    <Subject>'.strip_tags($subject).'</Subject>
		    <Body>'.strip_tags($body).'</Body>
		    <QuestionType>General</QuestionType>
		    <RecipientID>'.$user.'</RecipientID>
		  </MemberMessage>
		</AddMemberMessageAAQToPartnerRequest>';

		file_put_contents(__DIR__.'/../adds/last-sended-ebay-msg.xml', $post);

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: AddMemberMessageAAQToPartner",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result = $this->request($this->get_api_url(), $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public function GetUser($username, $ItemID){

		$post = '<?xml version="1.0" encoding="utf-8"?> 
		<GetUserRequest xmlns="urn:ebay:apis:eBLBaseComponents"> 
		  <RequesterCredentials> 
			<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken> 
		  </RequesterCredentials> 
		  <UserID>'.$username.'</UserID>
		  <ItemID>'.$ItemID.'</ItemID>
		  <DetailLevel>ReturnAll</DetailLevel>
		</GetUserRequest> ';

		$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetUser",
		"X-EBAY-API-SITEID: 0",
		"Content-Type: text/xml");

		$result = $this->request($this->get_api_url(), $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetFeedbackByUserId($user_id = 'gig-games')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <UserID>'.$user_id.'</UserID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetFeedbackByTransactionId($TransactionID = '')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <TransactionID>'.$TransactionID.'</TransactionID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetFeedbackByItemId($ItemID = '')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <ItemID>'.$ItemID.'</ItemID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetFeedbackByOrderLineItemID($OrderLineItemID = '')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <OrderLineItemID>'.$OrderLineItemID.'</OrderLineItemID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetItemsAwaitingFeedbackRequest($c = [])
	{
		$c = array_merge([  'EntriesPerPage'=>'200',
							'PageNumber'=>'1'], $c);

		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetItemsAwaitingFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <WarningLevel>High</WarningLevel>  
				  <Pagination>
				    <EntriesPerPage>'.$c['EntriesPerPage'].'</EntriesPerPage>
				    <PageNumber>'.$c['PageNumber'].'</PageNumber>
				  </Pagination>
				</GetItemsAwaitingFeedbackRequest>';


		$headers = self::getHeaders('GetItemsAwaitingFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function test()
	{
		return 'test';
	}

}