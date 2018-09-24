<?php

/**
* 
*/
class EbayGigGames
{
	private static $api_url = 'https://api.ebay.com/ws/api.dll';

	private static $token = EBAY_GIG_TOKEN;

	private static $_instance = null;

    function __construct () { }

    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function setToken($new_token)
    {
    	self::$token = $new_token;
        return self::getInstance();
    }

    public static function setTokenByName($token_name)
    {
    	$tokens = [
    		'gig-games' => EBAY_GIG_TOKEN,
    		'cdvet' => EBAY_CDVET_TOKEN,
    	];
    	self::$token = $tokens[$token_name];
        return self::getInstance();
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

	private static function getHeaders($api_call_name, $api_version = '837', $site_id = '77')
	{		
		return [
			'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $api_version,
			'X-EBAY-API-DEV-NAME: ' . 'c1f2f124-1232-4bc4-bf9e-8166329ce649',
			'X-EBAY-API-APP-NAME: ' . 'Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
			'X-EBAY-API-CERT-NAME: ' . 'PRD-ae576df59071-a52d-4e1b-8b78-9156',
			'X-EBAY-API-CALL-NAME: ' . $api_call_name,
			'X-EBAY-API-SITEID: ' . $site_id,
		];
	}

	public static function proto($item_id = '')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <UserID>gig-games</UserID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function RelistItemRequest($item_id = '')
	{
		if(!$item_id) return false;
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<RelistItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <ErrorLanguage>en_US</ErrorLanguage>
				  <WarningLevel>High</WarningLevel>
				  <Version>837</Version>
				  <Item>
				    <ItemID>'.trim($item_id).'</ItemID>
				  </Item>
				</RelistItemRequest>';


		$headers = self::getHeaders('RelistItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	// 'ItemID' => $_POST['ebay_id'],	(required)
	// 'Title' => $_POST['title'],
	// 'StartPrice' => $_POST['price'],
	// 'Quantity' => $_POST['quantity'],
	public static function updateItemBaseData($data_arr = [])
	{
		if(!$data_arr || !$data_arr['ItemID']) return;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.self::$token.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$data_arr['ItemID'].'</ItemID>';
				if(isset($data_arr['StartPrice']))
					$xml .= '<Title>'.htmlspecialchars($data_arr['Title']).'</Title>';		
				if(isset($data_arr['StartPrice']))
					$xml .= '<StartPrice>'.(float)$data_arr['StartPrice'].'</StartPrice>';	
				if(isset($data_arr['Quantity']))
					$xml .= '<Quantity>'.(int)$data_arr['Quantity'].'</Quantity>';
			$xml .= '</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function updateItemSpecifics($item_id, $specifics=[])
	{
		if(!$item_id || !$specifics) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.self::$token.'</eBayAuthToken>
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
			<Version>837</Version>
			<ErrorLanguage>en_US</ErrorLanguage>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function updateItemDescription($item_id, $desc = false)
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$desc) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.self::$token.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Description>'.htmlspecialchars($desc, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Description>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


			      // <SellerShippingProfile>
			      //   <ShippingProfileID></ShippingProfileID>
			      //   <ShippingProfileName></ShippingProfileName>
			      // </SellerShippingProfile>
	public static function updateItemShippingProfileID($ItemID, $ShippingProfileID)
	{
		if(!$ItemID || !$ShippingProfileID) return;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
			    <ItemID>'.$ItemID.'</ItemID>
				<SellerProfiles>
			      <SellerShippingProfile>
			        <ShippingProfileID>'.$ShippingProfileID.'</ShippingProfileID>
			      </SellerShippingProfile>
			    </SellerProfiles>
    		</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';


		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function test()
	{
		return self::$token;
	}


}



