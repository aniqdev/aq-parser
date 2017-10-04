<?php

/**
* 
*/
class Cdvet
{
	
	function __construct()
	{
		# code...
	}
	public static function addItem($item = [])
	{
		if(!$item) return false;

		$post = '<?xml version="1.0" encoding="utf-8"?>
			<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<WarningLevel>High</WarningLevel>
			  <RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
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

		$result = $this->request($this->api_url, $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}



}





?>