<?php

/**
* 
*/
class Cdvet
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

	public static function addItem($item = [])
	{
		if(!$item) return false;

		$post = '<?xml version="1.0" encoding="utf-8"?>
			<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<WarningLevel>High</WarningLevel>
			  <RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			  </RequesterCredentials>
			  <Item>';
		if (isset($item['SKU']) && $item['SKU']) {
			$post .= '<SKU>'.$item['SKU'].'</SKU>';
		}
		$post .= '<VATDetails>
				      <BusinessSeller>true</BusinessSeller>
				      <VATPercent>'.$item['VATPercent'].'</VATPercent>
			    </VATDetails>
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
					<PostalCode>49584</PostalCode>
					<PaymentMethods>PayPal</PaymentMethods>
					<PaymentMethods>MoneyXferAccepted</PaymentMethods>
					<PayPalEmailAddress>buchhaltung@cdvet.de</PayPalEmailAddress>
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

		$result = self::request(self::$api_url, $post, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function get_gehalte($long_desc)
	{
		if(stripos($long_desc, 'Analytische Bestandteile und Gehalte') === false) return '';
		
		$pattern = '/.+Analytische Bestandteile und Gehalte(.+?)<\/\w{1,3}>.*/s';
		$long_desc = preg_replace($pattern, '$1', $long_desc);
		$long_desc = preg_replace('/<[^>].+?>/', '', $long_desc);
		$long_desc = preg_replace('/(\d),(\d)/', '$1.$2', $long_desc);

		return trim(str_replace(':', '', $long_desc));
	}


	public static function get_zusammen($long_desc)
	{
		if(stripos($long_desc, 'Zusammensetzung') === false) return '';

		if (substr_count($long_desc, '<div>') === 1){
			$long_desc = preg_replace('/.+Zusammensetzung(.+?)<\/\w{1,3}>.*/s', '$1', $long_desc);
		}else{
			$long_desc = preg_replace('/.+Zusammensetzung(.+?)<\/div>.*/s', '$1', $long_desc);
		}

		$long_desc = preg_replace('/<[^>].+?>/', '', $long_desc);

		$strpos = strpos($long_desc, 'Analytische');
		if($strpos) $long_desc = substr($long_desc, 0, $strpos);

		return trim(str_replace(':', '', $long_desc));
	}



	public static function cd_ebay_cat_sort($cats)
	{
		$cats_arr = [];
		foreach ($cats as $k => $row) {
			if ((int)$row['D']) {
				$cats_arr[$row['D']][$row['E']]['main_cat_name'] = $row['F'];
				$cats_arr[$row['D']][$row['E']]['cat_name'] = $row['A'];
				$cats_arr[$row['D']][$row['E']]['eBayKategorie'] = $row['C'];
				$cats_arr[$row['D']][$row['E']]['eBayShopKAtegorieID'] = $row['B'];
			}
		}
		return $cats_arr;
	}


	public static function get_ebay_cat($cat_str_list, &$categories)
	{	
		$how_match_first = 0;
		$ret = [];
		$item_cats = explode('|', $cat_str_list);
		// shuffle($item_cats);
		foreach ($item_cats as $val) {
			if (isset($categories[$val])) { // главное совпадение
				foreach ($item_cats as $value) {
					if (isset($categories[$val][$value])) { // первое совпадение
						$ret[$val][] = $categories[$val][$value];
					}
				}
				$how_match_first++;
			}
		}
		// $ret['how match first'] = $how_match_first;
		return $ret;
	}


/*
	$item = [
		'desc_title' => '',
		'chosen_desc_pics' => [],
		'desc_top' => '',
		'desc_bot' => '',
	]
*/
	public static function prepare_description($item = [])
	{	
		$desc = file_get_contents('http://hot-body.net/ebay-css/cdvet/index.html');

		$desc = str_replace('style.css', '//hot-body.net/ebay-css/cdvet/style.css', $desc);

		$desc = preg_replace('/cv-title">.+?<\/h1>/', 'cv-title">'.$item['desc_title'].'</h1>', $desc);

		
		$item['chosen_desc_pics'] = array_map(function($el){
			return str_replace('http:', '', $el);
		}, $item['chosen_desc_pics']);

		$images_html = '';

		foreach ($item['chosen_desc_pics'] as $k => $pic) {
			if($k>3) break;
			$images_html .= '
				<div class="gig-pic-small gig-pic-small'.($k+1).'">
					<img src="'.$pic.'" alt="">
				</div>'.PHP_EOL;
		}

		foreach ($item['chosen_desc_pics'] as $k => $pic) {
			if($k>3) break;
			$images_html .= '
				<div class="big-pic-hover big-pic-hover'.($k+1).'">
					<img src="'.$pic.'" alt="" class="gig-pic-big gig-pic-big'.($k+1).'">
				</div>'.PHP_EOL;
		}

		$desc = preg_replace('/(gig-gallery">).+?(<\/div><!-- gig-gallery -->)/s', '${1}'.$images_html.'${2}', $desc);

		$desc = preg_replace('/(cv-desc-top">).+?(<\/div><!-- cv-desc-top -->)/s', '${1}'.PHP_EOL.$item['desc_top'].PHP_EOL.'${2}', $desc);

		$desc = preg_replace('/(cv-desc-bot">).+?(<\/div><!-- cv-desc-bot -->)/s', '${1}'.PHP_EOL.$item['desc_bot'].PHP_EOL.'${2}', $desc);


		return $desc;
	}


	public static function get_geeignet($cat_ids)
	{
		$ret = [];
		foreach ($cat_ids as $cat) {
			$ret[] = $cat[0]['main_cat_name'];
		}
		return implode(', ', $ret);
	}


	public static function get_zweck($cat_ids)
	{
		$ret = [];
		foreach ($cat_ids as $cat) {
			foreach ($cat as $subcat) {
				$ret[$subcat['cat_name']] = $subcat['cat_name'];
			}
		}
		return implode(', ', $ret);
	}


	public static function get_title(&$row, &$cat_ids, &$cdvet_feed)
	{

		$title = 'cdVet® '.str_ireplace('cdvet', '', html_entity_decode($row['C']));

		$volume = str_replace(' ', '', $cdvet_feed[$row['A']][8].$cdvet_feed[$row['A']][7]);

		$temp = str_replace($volume, '', $title).' '.$volume;
		if(strlen($temp) < 81) $title = $temp;

		$temp = $title.' '.$cat_ids[$_POST['chosen_cat_id']][0]['main_cat_name'];
		if(strlen($temp) < 81) $title = $temp;

		$temp = $title.' '.$cat_ids[$_POST['chosen_cat_id']][0]['cat_name'];
		if(strlen($temp) < 81) $title = $temp;

		$sostav_str = self::get_zusammen($row['I-initial']);

		if ($sostav_str) {
			foreach (explode(',', $sostav_str) as $key => $ingr) {
				$ingr = trim($ingr);
				$temp = $title.' '.$ingr;
				if(strlen($temp) < 81) $title = $temp;
			}
		}
		

		return preg_replace('/\s+/', ' ', $title);
	}


	public static function add_buttons($k, $cats, $shop_id, &$added_arr_sorted, &$added_arr)
	{
		// if ($shop_id === 270) {
		// 	sa($shop_id);
		// 	// sa($added_arr_sorted);
		// 	sa($cats);
		// }
		$btns = '';
		foreach ($cats as $key => $cat) {
			// пропускаем новые добавленные
			if(in_array($key, ['300707','304198','300698']) && in_array($shop_id, $added_arr)) continue;
			$ok = ''; $not = '';
			if (isset($added_arr_sorted[$shop_id][$key])) {
				$ok = '<i class="glyphicon glyphicon-ok"></i>';
			}else{
				$not = 'not_added';
			}
			$btns .= '<button class="js-cdadd '.$not.'" lang="'.$k.'" name="'.$key.'">'.$cat[0]['main_cat_name'].$ok.'</button><br>';
		}
		return $btns;
	}


	public static function get_units($unit_mathes)
	{
		$UnitType = @$unit_mathes[2] ? trim($unit_mathes[2]) : '';
		$UnitQuantity = @$unit_mathes[1] ? trim(+$unit_mathes[1]) : '';

		if ($UnitType === 'g' && $UnitQuantity >= 250) {
			$UnitType = 'kg';
			$UnitQuantity = $UnitQuantity/1000;
		}

		if ($UnitType === 'g' && $UnitQuantity < 250) {
			$UnitType = '100g';
			$UnitQuantity = $UnitQuantity/100;
		}

		if ($UnitType === 'ml' && $UnitQuantity >= 250) {
			$UnitType = 'l';
			$UnitQuantity = $UnitQuantity/1000;
		}

		if ($UnitType === 'ml' && $UnitQuantity < 250) {
			$UnitType = '100ml';
			$UnitQuantity = $UnitQuantity/100;
		}
		
		return [
			'UnitType' => $UnitType,
			'UnitQuantity' => str_replace('.', ',', $UnitQuantity),
		];
	}


	public static function sort_added()
	{
		$added_arr = arrayDB("SELECT * FROM cdvet");

		$sorted_arr = [];

		foreach ($added_arr as $k => $val) {
			$sorted_arr[$val['shop_id']][$val['cat_id']] = 1;
		}

		return $sorted_arr;
	}


	public static function get_added_shop_ids()
	{
		return array_column(arrayDB("SELECT shop_id FROM cdvet"), 'shop_id');
	}

	// массовое редактирование цен и количества товаров
	// Array
	// (
	//     [0] => Array
	//         (
	//             [ItemID] => 253201322474
	//             [StartPrice] => 40.03
	//             [Quantity] => 4
	//         )
	// )
	public static function reviseInventoryStatus($items_arr = [])
	{
		if (!isset($items_arr[0]['ItemID'])) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<ReviseInventoryStatusRequest xmlns="urn:ebay:apis:eBLBaseComponents">';

		foreach ($items_arr as $k => $item) {
			$xml .= '<InventoryStatus>
						<ItemID>'.$item['ItemID'].'</ItemID>';
				if(isset($item['StartPrice'])){
					$xml .= '<StartPrice>'.$item['StartPrice'].'</StartPrice>';			
				}
				if(isset($item['Quantity'])){
					$xml .= '<Quantity>'.$item['Quantity'].'</Quantity>';
				}
			$xml .= '</InventoryStatus>';
		}
		$xml .=  '<RequesterCredentials>
					<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <Version>837</Version>
				  <ErrorLanguage>en_US</ErrorLanguage>
				  <WarningLevel>High</WarningLevel>
				</ReviseInventoryStatusRequest>';

		$headers = self::getHeaders('ReviseInventoryStatus');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function removeFromSale($item_id)
	{

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Quantity>0</Quantity>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';


		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function changeQuantity($item_id, $Quantity = 2)
	{

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Quantity>'.$Quantity.'</Quantity>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';


		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function changePostalCode($item_id, $postal_code)
	{

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<PostalCode>'.$postal_code.'</PostalCode>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';


		$headers = self::getHeaders('ReviseItem');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);

	}

	public static function updateItemTitle($item_id, $title = false)
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$title) return false;
		$version = '837';

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Title>'.htmlspecialchars($title).'</Title>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>'.$version.'</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem', $version); // 983
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
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<Description>'.htmlspecialchars($desc, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Description>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem'); // 983
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	public static function updateItemSubtitle($item_id, $subtitle = false)
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$subtitle) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<SubTitle>'.htmlspecialchars($subtitle, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</SubTitle>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem'); // 983
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	public static function updateItemPictures($item_id, $pics_arr = [])
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$pics_arr) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>';
		$xml .= '<PictureDetails>';
				foreach($pics_arr as $picture){
					if($picture) $xml .= '<PictureURL>'.$picture.'</PictureURL>';
				}
		$xml .= '</PictureDetails>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem'); // 983
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}

	public static function updateItemSpecifics($item_id, $ItemSpecifics = [])
	{
		$item_id = preg_replace('/\D/', '', $item_id);
		if(!$item_id || !$ItemSpecifics) return false;

		$xml = '<?xml version="1.0" encoding="utf-8"?>
		<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
			<RequesterCredentials>
				<eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
			</RequesterCredentials>
			<Item ComplexType="ItemType">
				<ItemID>'.$item_id.'</ItemID>
				<ItemSpecifics>';
				foreach($ItemSpecifics as $key => $specific){
					if($specific){
						$xml .= '<NameValueList><Name>'.$key.'</Name>';
						if(is_array($specific)){
							foreach($specific as $value){
								$xml .= '<Value>'.htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
							}
						}
						else{
							$xml .= '<Value>'.htmlspecialchars($specific, ENT_XML1 | ENT_QUOTES, 'UTF-8').'</Value>';
								
						}
						$xml .= '</NameValueList>';
					}
				}
				$xml.= '</ItemSpecifics>
			</Item>
			<MessageID>1</MessageID>
			<WarningLevel>High</WarningLevel>
			<Version>837</Version>
		</ReviseItemRequest>​';

		$headers = self::getHeaders('ReviseItem'); // 983
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	public static function GetSellerListRequest($page=1, $entires=25){

		// if now 2016-10-15T12:32:57.376Z
		// <EndTimeFrom>2016-07-16T21:59:59.005Z</EndTimeFrom>
		// <EndTimeTo>2016-11-14T21:59:58.005Z</EndTimeTo>
		$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
		    <eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ErrorLanguage>en_US</ErrorLanguage>
		  <WarningLevel>High</WarningLevel>
		  <GranularityLevel>Coarse</GranularityLevel>
		  <EndTimeFrom>'.date('Y-m-d\TH:i:s.B\Z', time()-(60*60*24*30*2)).'</EndTimeFrom>
		  <EndTimeTo>'.date('Y-m-d\TH:i:s.B\Z', time()+(60*60*24*30*2)).'</EndTimeTo>
		  <IncludeWatchCount>true</IncludeWatchCount>
		  <Pagination> 
		  	<PageNumber>'.$page.'</PageNumber>
		    <EntriesPerPage>'.$entires.'</EntriesPerPage> 
		  </Pagination> 
		</GetSellerListRequest>';

		$headers = self::getHeaders('GetSellerList', '967'); // 983
		$res = self::request(self::$api_url, $post, $headers);
		$res = json_decode(json_encode(simplexml_load_string($res)), true);
		if(isset($res['ItemArray']['Item']) && !isset($res['ItemArray']['Item'][0])){
			$res['ItemArray']['Item'] = [$res['ItemArray']['Item']];
		}
		return $res;
	}


	public static function remove_dublicate_words($title)
	{
		$words = explode(' ', $title);
		$words_exist = [];
		foreach ($words as $key => &$word) {
			if(isset($words_exist[$word])) $word = '';
			$words_exist[$word] = $word;
		}
		$title = implode(' ', $words);
		return str_replace('  ', ' ', $title);

		// регулярка удаляющая первое из дублей (не подходит)
		// return preg_replace('/(\b\w+\b)(?=.*?\1)/i', '', $title);
	}


	public static function replace_parasite($title)
	{
		if (stripos($title, 'Parasitenabwehr') !== false) {
			$title = str_ireplace('Parasitenabwehr', '', $title);

			foreach (['Flöhe', 'Zecken', 'Milben', 'Mücken', 'Insekten', 'Bremsen'] as $add) {
				if(strlen($title . ' ' .  $add) <= 80) $title .= ' ' .  $add;
			}
		}
		return str_replace('  ', ' ', $title);
	}


	public static function GetSellerList()
	{
		$_GET['item_arr'] = [];
		$res = Cdvet::GetSellerListRequest(1, 200);

		foreach ($res['ItemArray']['Item'] as $key => &$item) {
			$_GET['item_arr'][] = $item;
		}

		$pages = $res['PaginationResult']['TotalNumberOfPages'];
		if(!$pages) return;

		$multi_curl = ef_get_milticurl_handler();
		$multi_curl->success(function($instance) {
			foreach (json_decode(json_encode($instance->response->ItemArray), true)['Item'] as $item) {
				$_GET['item_arr'][] = $item;
			}
		});

		$ebay_api_url = 'https://api.ebay.com/ws/api.dll';

		for ($i=2; $i <= $pages; $i++) {
			$multi_curl->addPost($ebay_api_url, cdvet_GetList_post_data($i));
		}

		$multi_curl->start();

		return($_GET['item_arr']);
	}


	public static function GetFeedbacByUserId($item_id = '')
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>
				<GetFeedbackRequest xmlns="urn:ebay:apis:eBLBaseComponents">
				  <RequesterCredentials>
				    <eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
				  </RequesterCredentials>
				  <UserID>gig-games</UserID>
				</GetFeedbackRequest>';


		$headers = self::getHeaders('GetFeedback');
		$result = self::request(self::$api_url, $xml, $headers);
		return json_decode(json_encode(simplexml_load_string($result)), true);
	}


	private static function sql_query_for_search($and_where)
	{
		return "SELECT cdvet_feed.title,ebay_id,cdvet.shop_id,
			cat_id,
			price,
			`desc` as zusammen,
			short_desc,
			categories,
			instock,
			UnitQuantity,
			UnitType,
			ebay_cat,
			link,
			image
			from cdvet
			join cdvet_feed
			on cdvet.shop_id = cdvet_feed.shop_id 
			WHERE instock = 'instock'
				AND link <> ''
				AND image <> ''
				$and_where
			LIMIT 20";
	}


	public static function filter_search()
	{
		if (isset($_POST['q']) && $_POST['q']) {
			if(strlen(trim($_POST['q'])) < 3) return '';

			$query = _esc(str_replace(' ', '%', trim($_POST['q'])));

			$and_where = "AND cdvet_feed.title LIKE '%$query%'";
			$ret1 = arrayDB(self::sql_query_for_search($and_where));
			$and_where = "AND cdvet_feed.`desc` LIKE '%$query%'";
			$ret2 = arrayDB(self::sql_query_for_search($and_where));
			$and_where = "AND MATCH (cdvet_feed.title) AGAINST ('$query')";
			$ret3 = arrayDB(self::sql_query_for_search($and_where));
			$and_where = "AND MATCH (cdvet_feed.desc) AGAINST ('$query')";
			$ret4 = arrayDB(self::sql_query_for_search($and_where));

			$ret = array_merge($ret1, $ret2, $ret3, $ret4);
			$ret = array_column($ret, null, 'ebay_id');
			$ret = array_values($ret);
		}
		if (isset($_POST['ebay_category']) && $_POST['ebay_category']) {
			$ebay_category = _esc(trim($_POST['ebay_category']));
			$and_where = "AND ebay_cat = '$ebay_category'";
			$ret = arrayDB(self::sql_query_for_search($and_where));
		}
		

		$wrap_file = include ROOT.'/lib/adds/cdvet-perenos.php';
		$from = array_map(function($el){return str_replace('|', '', $el);}, $wrap_file);
		$to = array_map(function($el){return str_replace('|', '&shy;', $el);}, $wrap_file);
		$temp_arr = [];
		foreach ($ret as &$el){
			$units = self::get_units([0,$el['UnitQuantity'],$el['UnitType']]);

			$el['title'] = $el['title'].' '.((strpos($el['title'], $el['UnitQuantity'].$el['UnitType']))?'':+$el['UnitQuantity'].$el['UnitType']);
			$el['categories'] = array_unique(explode('|', $el['categories']));
			$el['zusammen'] = Cdvet::get_zusammen($el['zusammen']);
			$el['short_desc'] = htmlspecialchars($el['short_desc']);
			$el['title'] = str_replace($from, $to, $el['title']);
			$el['short_desc'] = str_replace($from, $to, $el['short_desc']);
			$el['ppu'] = ($el['price']+3)/str_replace(',', '.', $units['UnitQuantity']); // price per unit
			$el['cost'] = '(EUR '.number_format($el['ppu'],2,',',"'").' / '.$units['UnitType'].')';
			$el['price'] = str_replace('.', ',', $el['price'] + 3);
			// $el['units'] = $units;
		} 

		// global $_ERRORS;
		return json_encode($ret);
	}



	public static function filter_search_site()
	{		$where_and = '';
		if (isset($_POST['sq']) && $_POST['sq']) {
			if(strlen(trim($_POST['sq'])) < 3) return '';
			$query = _esc(trim($_POST['sq']));
			$where_and = "title LIKE '%$query%' AND";
		}
		if (isset($_POST['cdvet_category']) && $_POST['cdvet_category']) {
			$category = _esc(trim($_POST['cdvet_category']));
			$where_and = "categories LIKE '%$category%' AND";
		}
		
		$ret = arrayDB("SELECT title,
			shop_id,
			price,
			`desc` as zusammen,
			short_desc,
			categories,
			instock,
			UnitQuantity,
			UnitType,
			link,
			image
			FROM cdvet_feed_full
			WHERE $where_and instock = 'instock'
				AND link <> ''
				AND image <> ''
				AND UnitQuantity > 0
				AND price > 0
			ORDER BY UnitQuantity");

		$wrap_file = include ROOT.'/lib/adds/cdvet-perenos.php';
		$from = array_map(function($el){return str_replace('|', '', $el);}, $wrap_file);
		$to = array_map(function($el){return str_replace('|', '&shy;', $el);}, $wrap_file);
		$temp_arr = [];
		foreach ($ret as &$el){
			$units = self::get_units([0,$el['UnitQuantity'],$el['UnitType']]);

			$el['categories'] = array_unique(explode('|', $el['categories']));
			$el['zusammen'] = Cdvet::get_zusammen($el['zusammen']);
			$el['short_desc'] = htmlspecialchars($el['short_desc']);
			$el['title'] = str_replace($from, $to, $el['title']);
			$el['short_desc'] = str_replace($from, $to, $el['short_desc']);
			$el['ppu'] = $el['price']/str_replace(',', '.', $units['UnitQuantity']); // price per unit
			$el['cost'] = '(EUR '.number_format($el['ppu'],2,',',"'").' / '.$units['UnitType'].')';
			$el['price_num'] = $el['price'];
			$el['price'] = str_replace('.', ',', $el['price']);
			$el['units'] = $units;
			$temp_arr[$el['link']][] = $el;
		} 
		$temp_arr = array_map(function($el){
			$ret = $el[0];
			$ret['available_volumes'] = [];
			$ret['price'] = 99999;
			$ret['ppu'] = 99999;
			foreach ($el as $val) {
				if($val['price_num'] < $ret['price']) $ret['price'] = $val['price_num'];
				if($val['ppu'] < $ret['ppu']){
					$ret['ppu'] = $val['ppu'];
					$ret['units'] = $val['units'];
				}
				$x = in_array($val['UnitType'], ['kg','l']) ? 100000 : 100;
				$ret['available_volumes'][$val['UnitQuantity']*$x] = +$val['UnitQuantity'].$val['UnitType'];
			}
			ksort($ret['available_volumes']);
			$ret['available_volumes'] = implode('/', $ret['available_volumes']);
			$ret['price'] = str_replace('.', ',', $ret['price']);
			$ret['cost'] = number_format($ret['ppu'],2,',',"'").'/'.$ret['units']['UnitType'];
			if(count($el) > 1){ // добавляем приставки
				$ret['price'] = 'ab €' . $ret['price'];
				$ret['cost'] = 'ab €' . $ret['cost'];
			}else{
				$ret['price'] = '€' . $ret['price'];
				$ret['cost'] = '€' . $ret['cost'];
			}
			return $ret;
		}, $temp_arr);
		$temp_arr = array_values($temp_arr);
// sa($temp_arr);
		return json_encode($temp_arr);
	}




}



