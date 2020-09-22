<link rel="stylesheet" href="css/add-item.css">
<?php

$url  = "https://api.sandbox.ebay.com/ws/api.dll";

$token = "AgAAAA**AQAAAA**aAAAAA**TCvJVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GjDJOGpAidj6x9nY+seQ**gOwDAA**AAMAAA**WGb9rPwe19/eeZjnVCwfTSyr39/UMNsPRt+fc5vIikBYz5TFE4HECldROutjvkAdAvzyXOQlh3plgg/32fUP7ZLydzmUkIi+wcPp8GUFxeDpO25i+xMhGy823qzxg9djBrk1Erdx9eqelPRtQLMmnssHzfDk2NkMWRGe8/CzQYeUm598MrfhB4ik7LyK1a8t9NXSnPW9+35FRulwxz8InDJyxYP9qG+gZCLicXEBtPCfl/bS5zKogdO4O5ymDPhWFm28qnkfx3VnhB8Mj5CmkOwVDpPqKrFwdabRJ4vP74wVc66GHdR2heLt0Sw5WfoabjOshUG9x/jeE0R1Vn4agtY9fyMp3T+s6GQ8hMTpFQTjHi1GT9x1lc5XusMPz1oyIic76xbDZ621g8QNMuxB3vCfcjSbo2ma5W2BMYap8f2a9CkByYvuZzaBKDZb4tuE3E/WyHbfCPbShMTwN7Qtj7CGAvbVYGK/j9A/LqydLgWuKMocnTLR4eNBdMpuGfLlbrS8xFqdrrAC7GcHUplrpEwcVgO5RhUVcXkmaHBSd8MN9+AJtvwgR2O0vcFeOi/yL2x4hPFHDQzfMppiOTXHAbDmXTwhCazTMTMKnb7OooM0xCdEDnzXZhOi2GDjFx0oNUnaqQzqbgDe536KjkbFAb5U7stlWD3JQkig+kA1DIGJlpnWN16Kay3fi8OnN4Nfba1F3VatyBkhEterKSkl4wibdD5wWovQscs7NafIYgJnz6qctjLg+36HKeNCi2Ie";


$url = 'https://api.ebay.com/ws/api.dll';
$token = EBAY_GIG_TOKEN;

//фунция для оправки запросов
function request($url, $post, $headers) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}


//139973
//функция для получения специализированных настроек для категории (в нашем случае для категории Компьютерные игры)
function GetCategorySpecifics($categoryId){
	global $url, $token;
	$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetCategorySpecificsRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<WarningLevel>High</WarningLevel>
		  <RequesterCredentials>
			<eBayAuthToken>'.$token.'</eBayAuthToken>
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

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}


//функция для получения специализированных настроек для категории (в нашем случае для категории Компьютерные игры)
function GetItem(){
	global $url, $token;
	$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
			<eBayAuthToken>'.$token.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ItemID>110185454062</ItemID>
		  <IncludeItemSpecifics>true</IncludeItemSpecifics>
		</GetItemRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
	"X-EBAY-API-CALL-NAME: GetItem",
	"X-EBAY-API-SITEID: 77",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}


//Функция для получения информации о ebay store (нам там нужны кастомные категории)
function GetStore(){
	global $url, $token;

	//название магазина
	$store_name = "gig-games";  //!!!!!поменяйте это значение на название своего магазина

	$post = '<?xml version="1.0" encoding="utf-8"?>
		<GetStoreRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<WarningLevel>High</WarningLevel>
		  <RequesterCredentials>
			<eBayAuthToken>'.$token.'</eBayAuthToken>
		  </RequesterCredentials>
		  <UserID>'.$store_name.'</UserID>
		</GetStoreRequest>';

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
	"X-EBAY-API-CALL-NAME: GetStore",
	"X-EBAY-API-SITEID: 77",
	"Content-Type: text/xml");

	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}




///////////////////////////////////////////////////////
//////самая главная функция, добавление товара/////////
///////////////////////////////////////////////////////
function AddItem($item){
	global $url, $token;
	$post = '<?xml version="1.0" encoding="utf-8"?>
		<AddItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<WarningLevel>High</WarningLevel>
		  <RequesterCredentials>
			<eBayAuthToken>'.$token.'</eBayAuthToken>
		  </RequesterCredentials>
		  <Item>
			    <ProductListingDetails>
					<EAN>Nicht zutreffend</EAN>
				</ProductListingDetails>
		   <Title>'.$item['Title'].'</Title>
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
		if($item['StoreCategory1'])	$post .= '<StoreCategoryID>'.$item['StoreCategory1'].'</StoreCategoryID>';
		if($item['StoreCategory2'])	$post .= '<StoreCategory2ID>'.$item['StoreCategory1'].'</StoreCategory2ID>';
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

	
	$result = request($url, $post, $headers);
	return json_decode(json_encode(simplexml_load_string($result)), true);
}

//не нашел куда пристроить этот код. Возможно, на US сайте не работает, либо в sandbox
//		<ShippingDetails>
//		<SalesTax>
//				<SalesTaxPercent>'.$item['SalesTaxPercent'].'</SalesTaxPercent>
//			</SalesTax>
//		</ShippingDetails>

///////////////////////////////////
//////если мы отправили форму//////
///////////////////////////////////
if($_POST){
	$item = array();
	$item['Title'] = $_POST['title']; //название, рандом добавлен для теста, чтобы не ругался на одинаковые названия
	$item['CategoryID'] = $_POST['CategoryID']; //ID категории
	$item['Quantity'] = $_POST['Quantity']; //Количество
	$item['ConditionID'] = $_POST['ConditionID']; //Состояние
	$item['Currency'] = $_POST['Currency']; //Валюта (на данный момент не используется)
	$item['Description'] = $_POST['Description']; //описание (HTML разрешен)
	$item['price'] = $_POST['price']; //цена
	$item['PictureURL'] = $_POST['PictureURL']; //ссылки на картинку, должна быть хотя бы одна
	$item['BestOfferEnabled'] = $_POST['BestOfferEnabled']; //разрешаем торговаться, Best Offers
	$item['SalesTaxPercent'] = $_POST['SalesTaxPercent']; //процент налога (на данный момент не используется)
	$item['ListingDuration'] = $_POST['ListingDuration']; //на какое время выставляем товар
	$item['specific'] = $_POST['specific']; //спецификации
	$item['feature'] = $_POST['feature']; //еще спецификации, в немного другом формате
	$item['StoreCategory1'] = $_POST['StoreCategory1']; //категория из НАШЕГО магазина #1
	$item['StoreCategory2'] = $_POST['StoreCategory2']; //категория из НАШЕГО магазина #2

	$item['feature'] = array_diff($item['feature'], array(''));
	
	if($item['feature']){
		$item['specific']['Features'] = $item['feature'];
	}

	if($_POST['extra_specific']){
		foreach($_POST['extra_specific'] as $key=> $specific){
			if($_POST['extra_specific'][$key] && $_POST['extra_value'][$key]){
				$item['specific'][$_POST['extra_specific'][$key]] = $_POST['extra_value'][$key];
			}
		}
	}

	//print_r($item);
	//print_R($_POST);


	//$AddItem = AddItem($item);

	echo "<pre>";
	print_r($item);
	echo "</pre>";
}

//получаем информация о категории( спецификации, features)
$category_sepcifics = GetCategorySpecifics(139973);//139973, 1249, 10176860010
// echo "<pre>";
// print_r($category_sepcifics);
// echo "</pre>";
//получаем инфо о магазине (кастомные категории)
$storeInfo = GetStore();

?>
<form method="post">

  <header>
    <h2>Добавить товар</h2>
  </header>
  
  <div>
    <label class="desc" id="title" for="title">Название</label>
    <div>
      <input id="title" name="title" type="text" class="field text fn" value="" tabindex="1" maxlength="80" required>
    </div>
  </div>
   <div>
    <label class="desc" id="title1" for="Field1">Категория</label>
    <div>
      <select id="CategoryID" name="CategoryID" class="field select medium" tabindex="11"> 
      <option value="139973">Компьютерные игры</option>
    </select>
    </div>
  </div>
  <div>
    <label class="desc" for="StoreCategory1">Доп. Категория 1</label>
    <div>
      <select id="StoreCategory1" name="StoreCategory1" class="field select medium" tabindex="11"> 
	  <option value=""></option>
      <?php
		foreach($storeInfo['Store']['CustomCategories']['CustomCategory'] as $category){
			echo "<option value='$category[CategoryID]'>$category[Name]</option>";
		}
	  ?>
		</select>
    </div>
  </div>
  <div>
    <label class="desc" for="StoreCategory2">Доп. Категория 2</label>
    <div>
      <select id="StoreCategory2" name="StoreCategory2" class="field select medium" tabindex="11"> 
	  <option value=""></option>
      <?php
		foreach($storeInfo['Store']['CustomCategories']['CustomCategory'] as $category){
			echo "<option value='$category[CategoryID]'>$category[Name]</option>";
		}
	  ?>
		</select>
    </div>
  </div>
  <div>
    <label class="desc" id="title1" for="Field1">UPC</label>
    <div>
      <select id="upc" name="upc" class="field select medium" tabindex="11"> 
      <option value="">none</option>
    </select>
    </div>
  </div>
   <div>
    <label class="desc" for="ConditionID">Состояние</label>
    <div>
      <select id="ConditionID" name="ConditionID" class="field select medium" tabindex="11"> 
      <option value="1000">Новый</option>
    </select>
    </div>
  </div>
  <div>
    <label class="desc"  for="Photo"><h2>Фото</h2></label>
  </div>
	<div>
  <label class="desc" for="Photo1">№1</label>
    <div>
       <input id="Photo1" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1" required><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo2">№2</label>
    <div>
       <input id="Photo2" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc"  for="Photo3">№3</label>
    <div>
       <input id="Photo3" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo4">№4</label>
    <div>
       <input id="Photo4" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo5">№5</label>
    <div>
       <input id="Photo5" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo6">№6</label>
    <div>
       <input id="Photo6" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo7">№7</label>
    <div>
       <input id="Photo7" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo8">№8</label>
    <div>
       <input id="Photo8" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo9">№9</label>
    <div>
       <input id="Photo9" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo10">№10</label>
    <div>
       <input id="Photo10" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo11">№11</label>
    <div>
       <input id="Photo11" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>
  <div>
  <label class="desc" for="Photo12">№12</label>
    <div>
       <input id="Photo12" name="PictureURL[]" type="text" class="field text fn" value="" tabindex="1"><br>
    </div>
  </div>

 
  <div>
    <label class="desc" id="Description" for="Description">
      Описание
    </label>
    <div>
      <textarea id="Description" name="Description" rows="10" cols="50" tabindex="4" required></textarea>
    </div>
  </div>
    <div>
    <label class="desc" for="ListingDuration">Продолжительность</label>
    <div>
      <select id="ListingDuration" name="ListingDuration" class="field select medium" tabindex="11"> 
      <option value="GTC">Максимальная</option>
      <option value="Days_1">1 день</option>
      <option value="Days_3">3 дня</option>
      <option value="Days_5">5 дней</option>
      <option value="Days_7">7 дней</option>
      <option value="Days_10">10 дней</option>
      <option value="Days_14">14 дней</option>
      <option value="Days_21">21 день</option>
      <option value="Days_30">30 дней</option>
      <option value="Days_60">60 дней</option>
      <option value="Days_90">90 дней</option>
      <option value="Days_120">120 дней</option>
    </select>
    </div>
  </div>
  <div>
    <label class="desc" for="eBayPlus">eBayPlus</label>
    <div>
      <input id='eBayPlus' name='eBayPlus' type='checkbox' value='true' tabindex='7' checked>
    </div>
  </div>
  <div>
    <label class="desc" for="price">Цена</label>
    <div>
      <input id="price" name="price" type="text" class="field text fn" value="" size="8" tabindex="1" required>
    </div>
  </div>
   <div>
    <label class="desc" for="Currency">Валюта</label>
    <div>
      <select id="Currency" name="Currency" class="field select medium" tabindex="11"> 
      <option value="EUR">Евро</option>
    </select>
    </div>
  </div>
   <div>
    <label class="desc" for="ListingType">ListingType</label>
    <div>
      <select id="ListingType" name="ListingType" class="field select medium" tabindex="11"> 
      <option value="FixedPriceItem">Fixed Price</option>
    </select>
    </div>
  </div>
  
   <div>
    <label class="desc" for="duration">BestOfferEnabled</label>
    <div>
      <input id='BestOfferEnabled' name='BestOfferEnabled' type='checkbox' value='true' tabindex='7' checked>
    </div>
  </div>
  
  <div>
    <label class="desc" for="price">SalesTaxPercent</label>
    <div>
      <input id="SalesTaxPercent" name="SalesTaxPercent" type="text" class="field text fn" value="" size="8" tabindex="1" required>
    </div>
  </div>
 <div>
    <label class="desc" for="price">Quantity</label>
    <div>
      <input id="Quantity" name="Quantity" type="number" class="field text fn" value="" size="8" tabindex="1" value="1" required>
    </div>
  </div>
  <div>
    <label class="ebayplus" for="ebayplus"><h2>Спецификации</h2></label>
    <div>
     
    </div>
  </div>
  <?php
	foreach($category_sepcifics['Recommendations']['NameRecommendation'] as $specific){
		if($specific['ValueRecommendation']){
			if($specific['Name'] != "Features"){
				echo  "<div>
						<fieldset>
						  <legend class='desc'>
							$specific[Name]
						  </legend>
						  <div>
						  <select name='specific[$specific[Name]]' class='field select medium' tabindex='11'> 
						  ";
						  echo "<option value=''></option>";
				foreach($specific['ValueRecommendation'] as $key=>$option){
					echo "<option value='$option[Value]'>$option[Value]</option>";
				}
				echo "		</select>
						</div>
						</fieldset>
					  </div>";
			}
			else{
				echo  "<div>
						<fieldset>
						  <legend class='desc'>
							$specific[Name]
						  </legend>
						  <div>";
						  echo "<table><tr>";
				foreach($specific['ValueRecommendation'] as $key=>$option){	
					echo "<td><input id='specific_{$key}' name='feature[]' type='checkbox' value=\"$option[Value]\" tabindex='7'><label class='choice' for='specific_{$key}'>$option[Value]</label></td>";
					if($key%3==2) echo "</tr><tr>";
				}
				echo "<tr><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td>";
				echo "<tr><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td>";
				echo "<tr><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td><td><input name='feature[]' type='text' value='' tabindex='7' placeholder='extra feature'></td>";
				echo "</tr></table>";
				echo "		</div>
						</fieldset>
					  </div>";
			}
		}else{
			echo  "<div>
				<label class='desc' for='specific'>$specific[Name]</label>
				<div>
				  <input id='title' name='specific[$specific[Name]]' type='text' class='field text fn' value='' tabindex='1' maxlength='80'>
				</div>
			  </div>";
		}
	}
  ?>
	<div>
		<input  name="extra_specific[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra specific">
		<div>
		  <input  name="extra_value[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra value">
		</div>
	  </div>

	  <div>
		<input  name="extra_specific[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra specific">
		<div>
		  <input  name="extra_value[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra value">
		</div>
	  </div>
	  <div>
		<input  name="extra_specific[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra specific">
		<div>
		  <input  name="extra_value[]" class="field text fn" value="" tabindex="1" maxlength="65" type="text" placeholder="extra value">
		</div>
	  </div>
  
  <div>
		<div>
  		<input id="saveForm" name="saveForm" type="submit" value="Submit">
    </div>
	</div>
  
</form>