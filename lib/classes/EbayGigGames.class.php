<?php

/**
* 
*/
class EbayGigGames
{
	private static $api_url = 'https://api.ebay.com/ws/api.dll';

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
	
	function __construct()
	{
		# code...
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


}



