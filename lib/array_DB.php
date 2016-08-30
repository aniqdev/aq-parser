<?php
include_once __DIR__.'/../config.php';
require_once __DIR__.'/class.db.php';

// функция для работы с SQLite3
function aqSqlite($query,$multiquery = false){
		
		$db = new SQLite3(__DIR__.'/../sqlite/mydb.db');
		$results = $db->query($query);
		$res = array();
		if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0) {
				while ($row = $results->fetchArray(SQLITE3_ASSOC)) { // SQLITE3_BOTH, SQLITE3_ASSOC, SQLITE3_NUM
						$res[] = $row;
				}
		}

		$db->close();
		return $res;
}

function aqMysqli($query, $multiquery = false){

		if ($multiquery){

				$mysqli = new mysqli(db_HOST, db_USER, db_PASS, db_NAME);

				if ($mysqli->connect_errno) die ($mysqli->connect_error);
				
				$res = $mysqli->multi_query($query);

				$mysqli->close();

		}else{

				if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0) {
						return DB::getInstance()->get_results($query);
				}else{
						return DB::getInstance()->query($query);
				}
				

		}

}


function arrayDB($query,$multiquery = false){
		if (USE_DB === 'sqlite') {
				return aqSqlite(trim($query));
		}elseif (USE_DB === 'mysqli') {
				return aqMysqli(trim($query),$multiquery);
		}else{
				echo "data base aq_error!";
		}
} // arrayDB

function _esc($str){
		return DB::getInstance()->escape($str);
}

function BlackListFilter($blacklist,$itemID){
		foreach ($blacklist as $v) if ($itemID == $v['item_id']) return false;
		return true;
}

$_ERRORS = array();
// error handler function
function aqErrorHandler($errno, $errstr, $errfile, $errline){

		global $_ERRORS;
		if (!(error_reporting() & $errno)) {
				// This error code is not included in error_reporting
				return;
		}

		switch ($errno) {
		case E_USER_ERROR:
				$text = "<b>My ERROR</b> [$errno] $errstr <br>\n";
				$text .= "  Fatal error on line $errline in file $errfile <br>\n";
				$text .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ") <br>\n";
				$text .= "Aborting...";
				$_ERRORS[]['text'] = $text;
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_ERROR';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;
				exit(1);
				break;

		case E_USER_WARNING:
				$_ERRORS[]['text'] = "<b>My WARNING</b> [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_WARNING';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;

				break;

		case E_USER_NOTICE:
				$_ERRORS[]['text'] = "<b>My NOTICE</b> [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_NOTICE';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;
				break;

		default:
				$_ERRORS[]['text'] = "Unknown error type: [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'UNKNOW_TYPE';
				$_ERRORS[count($_ERRORS)-1]['line'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file'] = $errfile;
				break;
		}

		/* Don't execute PHP internal error handler */
		return true;
}
// set to the user defined error handler
set_error_handler("aqErrorHandler");


class Ebay_shopping{

		//     // Создаем контекст для file_get_contents()
		// private static $opts = array(
		//           "http"=>array(
		//             "method"=>"GET",
		//             "header"=>"Accept-language: en\r\nCookie: foo=bar\r\n"
		//           )
		//         );
		// private static $context;

		// private static function context(){
		//     self::$context = stream_context_create($opts);
		// }


		static function findItemsAdvanced($request, $seller, $page = 1, $categoryId = false){
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
				 $url .= "&paginationInput.entriesPerPage=25";
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
		//  $url .= '&siteid=77';
				$url .= '&version=515';
				$url .= '&ItemID='.$itemId;
				// $url .= '&IncludeSelector=Details';
				// $url .= '&IncludeSelector=Details,Description';
				$url .= '&IncludeSelector=Details,TextDescription';


				// Открываем файл с помощью установленных выше HTTP-заголовков
				$json = file_get_contents($url);
				return $json;
		}


} // class Ebay_shopping 1

class Ebay_shopping2{

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

				$endpoint = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

				$auth_token = 'AgAAAA**AQAAAA**aAAAAA**lW+DVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFloqjAZOKoQydj6x9nY+seQ**A1sDAA**AAMAAA**bJZNblCzYfoH41ej+oYjKvaiSIEgGgjXtz5xYJH+Nn6AeKYxrNyVhcIKlc8PDqUdVZMBsG3COT8cmmTUmWECC4wEm1RFzyxmwBppednB5xFBjl7Tt2iHwVq9Joq5fXHe9QVC1KTyrZVnCRL2ViKpUPyRJOAxjfW4R/8ld72LE9F1teRHyeeTYy26Js/vXh4r1ZkNoHIrmCWGwZ/x84FQEr7d4XMwuhaKsQZWhYhXKahQT3SreaYcXsygdQdWwvC/XZ5kuFbh6/UPXPrrDc5LsozMw18CGMF/eNY4ozP1Sq/xhBoWBjrlUpMdKAf9e+t1q3/fBcYnjGRaL5vNUGFIVRWLohfuYf5vZSlPFmbaYI8+Vtl8O7f1Qp9fYYyxdRU4DNRdwc55vgq9lSsrJRqiRY1E3BFbjljoj5tJ06BQ4zRoVHbnzvYiJ8+AcMAT4sLHVwf+9/QljLk6jqev/vwjkaJzQZ9cN/WwADeEv3j6EC9kAkAoBx7JPbB0REWdAtoHdqFKByQk35mbbkcWAI/VQfsqBO0lqo77CR1vkZideodUZvzXT7icbtrnTdZW2rvqJNvwSsnYIOgoIifbA2PiMuHtWvG91Cctsz+IE7wRQ4pFycAAWf4lsdQ1jkgiHW5tEz7XW7afDPxpPL1MyVZTtbzLBacmHsVch61gWDcBhadjbizx2xTJUzHW7UyIqp4Q7b/4v0P4bNyje2uD79alLH6YTlkbOT88DaGR/TPR/CQS/eouhfoqVMWWLN4BVjA8';

				$xml = '<?xml version="1.0" encoding="utf-8"?>
				<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					<RequesterCredentials>
						<eBayAuthToken>'.$auth_token.'</eBayAuthToken>
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

				$ch  = curl_init($endpoint);     
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

				$endpoint = 'https://api.ebay.com/ws/api.dll';//https://api.sandbox.ebay.com/ws/api.dll

				$auth_token = 'AgAAAA**AQAAAA**aAAAAA**lW+DVw**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFloqjAZOKoQydj6x9nY+seQ**A1sDAA**AAMAAA**bJZNblCzYfoH41ej+oYjKvaiSIEgGgjXtz5xYJH+Nn6AeKYxrNyVhcIKlc8PDqUdVZMBsG3COT8cmmTUmWECC4wEm1RFzyxmwBppednB5xFBjl7Tt2iHwVq9Joq5fXHe9QVC1KTyrZVnCRL2ViKpUPyRJOAxjfW4R/8ld72LE9F1teRHyeeTYy26Js/vXh4r1ZkNoHIrmCWGwZ/x84FQEr7d4XMwuhaKsQZWhYhXKahQT3SreaYcXsygdQdWwvC/XZ5kuFbh6/UPXPrrDc5LsozMw18CGMF/eNY4ozP1Sq/xhBoWBjrlUpMdKAf9e+t1q3/fBcYnjGRaL5vNUGFIVRWLohfuYf5vZSlPFmbaYI8+Vtl8O7f1Qp9fYYyxdRU4DNRdwc55vgq9lSsrJRqiRY1E3BFbjljoj5tJ06BQ4zRoVHbnzvYiJ8+AcMAT4sLHVwf+9/QljLk6jqev/vwjkaJzQZ9cN/WwADeEv3j6EC9kAkAoBx7JPbB0REWdAtoHdqFKByQk35mbbkcWAI/VQfsqBO0lqo77CR1vkZideodUZvzXT7icbtrnTdZW2rvqJNvwSsnYIOgoIifbA2PiMuHtWvG91Cctsz+IE7wRQ4pFycAAWf4lsdQ1jkgiHW5tEz7XW7afDPxpPL1MyVZTtbzLBacmHsVch61gWDcBhadjbizx2xTJUzHW7UyIqp4Q7b/4v0P4bNyje2uD79alLH6YTlkbOT88DaGR/TPR/CQS/eouhfoqVMWWLN4BVjA8';

				$xml = '<?xml version="1.0" encoding="utf-8"?>
				<ReviseItemRequest xmlns="urn:ebay:apis:eBLBaseComponents">
					<RequesterCredentials>
						<eBayAuthToken>'.$auth_token.'</eBayAuthToken>
					</RequesterCredentials>
					<Item ComplexType="ItemType">
						<ItemID>'.$item_id.'</ItemID>
						<Quantity>0</Quantity>
					</Item>
					<MessageID>1</MessageID>
					<WarningLevel>High</WarningLevel>
					<Version>837</Version>
				</ReviseItemRequest>​';

				$ch  = curl_init($endpoint);     
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


} // class Ebay_shopping 2


function setGigGamesIdsToFile(){

		$seller = 'gig-games';
		$ebay_obj = new Ebay_shopping2();
		$result_arr = $ebay_obj->getSellerInfo($seller);

		if ($result_arr['status'] === 'OK') {
				$ids_arr = array();
				for ($i=1; $i <= $result_arr['totalPages']; $i++) { 
						$items = $ebay_obj->getProductsBySeller($seller, $i);
						foreach ($items['items'] as $item) {
								$ids_arr[$item['itemId']] = $item['itemId'];
						}
				}
				file_put_contents(__DIR__.'/../settings/ids_arr.txt', serialize($ids_arr));

		}elseif($result_arr['status'] === 'error'){
				$aq_page1_msg = $result_arr['errorMsg'];

		}

} // setGigGamesIdsToFile


function setGigGamesIdsToFileT(){

		$seller = 'gig-games';
		$ebay_obj = new Ebay_shopping2();
		$result_arr = $ebay_obj->getSellerInfo($seller);
		print_r($result_arr);
		print_r($ebay_obj->getProductsBySeller($seller, 1));
		if ($result_arr['status'] === 'OK') {
				$ids_arr = array();
				// for ($i=1; $i <= $result_arr['totalPages']; $i++) { 
				//     $items = $ebay_obj->getProductsBySeller($seller, $i);
				//     var_dump($i);
				//     var_dump(count($items['items']));
				//     foreach ($items['items'] as $item) {
				//         if (isset($ids_arr[$item['itemId']])) {
				//             print_r($item);
				//             print_r($ids_arr);
				//             break 2;
				//             break;
				//         }
				//         $ids_arr[$item['itemId']] = $item;

				//     }
				// }
				// var_dump(count($ids_arr));
				// print_r($ids_arr);
				// file_put_contents(__DIR__.'/../settings/ids_arr.txt', serialize($ids_arr));

		}elseif($result_arr['status'] === 'error'){
				$aq_page1_msg = $result_arr['errorMsg'];

		}

}

// возвращает количество строк в CSV файле
// какобычно индекс последней строки на 1 меньше
function csvCount($file_path){
		$i = 0;
		$fh = fopen($file_path,'r') or die($php_errormsg); 
		while (!feof($fh)) { 
				fgets($fh);
						$i++;
		}
		fclose($fh) or die($php_errormsg); 
		return $i;
}


// возвращает массив строки CSV файла по индексу
function csvGetRowByIndex($file_path, $index=0, $delimetr=',', $encoding='windows-1251'){
		$z = 0; $str = array();
		$i = $index; //нужная строка 
		$fh = fopen($file_path,'r') or die($php_errormsg); 
		while ((! feof($fh)) && ($z <= $i)) { 
				
				if ($z === $i) {
						$str = fgetcsv($fh, 0, $delimetr);
						if($encoding === 'windows-1251') foreach ($str as &$cell) $cell = iconv('Windows-1251', 'UTF-8', $cell);
				}else fgets($fh);
				$z++;
		}
		fclose($fh) or die($php_errormsg);
		return $str; 
}


function readExcel($path){
		
		// Открываем файл
		$xls = PHPExcel_IOFactory::load($path);
		// Устанавливаем индекс активного листа
		$xls->setActiveSheetIndex(0);
		// Получаем активный лист
		$sheet = $xls->getActiveSheet();
		 
		$Excel_table = array();
		// Получили строки и обойдем их в цикле
		$rowIterator = $sheet->getRowIterator();
		foreach ($rowIterator as $kR=>$row) {
				// Получили ячейки текущей строки и обойдем их в цикле
				$cellIterator = $row->getCellIterator();

				$Excel_table[$kR] = array();
				foreach ($cellIterator as $kC=>$cell) {
						$Excel_table[$kR][$kC] = $cell->getCalculatedValue();
				}
		}
		return $Excel_table;
}


// ============== Пример использования функции 
// В cell и value пердавать либо 2 строки либо 2 массива 
// $cell = array('G3','H3','I3','J3','K3','L3','M3');
// $value = array('Фото яндекса','м1','д1','м2','д2','м3','д3');
// writeCell(FILES_DIR.'file.xls', $cell, $value);
function writeCell($file_path, $cell, $value){
		$Xlsvsfkii_Failik = PHPExcel_IOFactory::load(FILES_DIR.$file_path);
		$Xlsvsfkii_Failik->setActiveSheetIndex(0);

if (is_array($cell) && is_array($value)) {
		foreach ($cell as $k => $onecell) {
				$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($onecell, $value[$k]);
		}
}else{
		$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($cell, $value);
}

		switch (strtolower(pathinfo($file_path)['extension'])) {
				case 'csv':
						$writeType = 'CSV';
						break;
				case 'xls':
						$writeType = 'Excel5';
						break;
				case 'xlsx':
						$writeType = 'Excel2007';
						break;
				
				default:
						$writeType = 'Excel2007';
						break;
		}
		$Zapisat = PHPExcel_IOFactory::createWriter($Xlsvsfkii_Failik, $writeType);
		$Zapisat->save(FILES_DIR.$file_path);
		 
		unset($Xlsvsfkii_Failik);
		unset($Zapisat);
}

//===================================================================================
//===================================================================================
class WooCommerceApi{
		
		function __construct(){

				$this->woocommerce = new \Automattic\WooCommerce\Client(
						'http://gig-games.de/', // Your store URL
						'ck_410bb472d79a017b47c7ff2b70cfee4120904b09', // Your consumer key
						'cs_db96dd892f781080643d93f966246b8a78704a4a', // Your consumer secret
						['version' => 'v3'] // WooCommerce API version
				);
		}

//--------------------------------------------------------------------
		public function addProduct($item){

				$data = [
						'product' => [
								'title' => '',
								'type' => 'simple',
								'regular_price' => '',
								'description' => '',
								'short_description' => '',
								'categories' => [],
								'images' => ['position' => '1', 'src' => 'http://vignette3.wikia.nocookie.net/madannooutovanadis/images/6/60/No_Image_Available.png/revision/latest?cb=20150730162527']
						]
				];

				$data = array_merge($data, $item);

				$this->woocommerce->post('products', $data);
		}

//--------------------------------------------------------------------
		public function checkProductById($item_id = 0){
				
				$item = '';
				try{
						$item = $this->woocommerce->get('products/'.(int)$item_id);
				}catch (Exception $e) {
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
				}

				return $item;
		}
//--------------------------------------------------------------------

		public function updateProductPrice($id, $price){
				
				$data = [
						'product' => [
								'regular_price' => $price,
								'in_stock' => true
						]
				];

				$item = '';
				try{
						$item = $this->woocommerce->put("products/$id", $data);
				}catch (Exception $e) {
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
				}

				return $item;
		}
//--------------------------------------------------------------------

		public function removeFromSale($id){
				
				$data = [
						'product' => [
								'in_stock' => false
						]
				];

				$item = '';
				try{
						$item = $this->woocommerce->put("products/$id", $data);
				}catch (Exception $e) {
						//echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
						//var_dump($e);
				}

				return $item;
		}


//--------------------------------------------------------------------

		public function run()    {


		}
}
//===================================================================================
//===================================================================================

// возвращает двумерный массив с первыми CSV файла
function csvToArr($file_path='', $options = array()){

		$config = array(
				'delimetr' => ';',
				'encoding' => 'utf-8',
				'max_str' => false,
				'del_first' => false,
				'output' => array()
				);
		$c = array_merge ( $config, $options );
		$fh = fopen($file_path,'r') or die($php_errormsg);
		$res = array(); $i = 0;
		
		while (!feof($fh)) {

				$str = fgetcsv($fh, 0, $c['delimetr']);

				$i++;
				if($c['del_first'] && $i === 1) continue;

				if(strtolower($c['encoding']) != 'utf-8' && $str) 
						foreach ($str as &$cell) 
								$cell = iconv($c['encoding'], 'UTF-8', $cell);


				$str2 = array();
				if($str)
				foreach ($c['output'] as $okey => $oval)
						$str2[$oval] = $str[$okey];

				if($str2) $res[] = $str2;
				elseif($str) $res[] = $str; 
				
				if($c['max_str'] && $i > $c['max_str']) break;

		}

		fclose($fh) or die($php_errormsg);

		return $res;
}

function arrToCsv($array, $file_path = 'result.csv', $options = array()){
		
		$config = array(
				'delimetr' => ',',
				'encoding' => 'utf-8',
				'keys_first_row' => true
				);
		$c = array_merge ( $config, $options );

				$count = count($array);
				$keys = array();
				$fp = fopen($file_path, 'w');
				if(!$fp) die('Не удалось получить доступ к '.$file_name);
				foreach ($array[0] as $key => $value) {
						$keys[] = $key;
				}
				if($c['encoding'] === 'windows-1251'){
						foreach ($keys as &$kcell) {
								$kcell = iconv('UTF-8', 'Windows-1251', $kcell);
						}
				}
				if($c['keys_first_row']) fputcsv($fp, $keys, $c['delimetr']);
				for ($i=0; $i < $count; $i++) {
						if($c['encoding'] === 'windows-1251'){
								foreach ($array[$i] as &$cell) {
										$cell = iconv('UTF-8', 'Windows-1251', $cell);
								}
						}
						fputcsv($fp, $array[$i], $c['delimetr']);
				}
				fclose($fp);
}

function showArray($array, $length = 10, $offset = 0){
		$res = array();
		$count = count($array);
		if ($length > $count) $length = $count;
		for ($i=$offset; $i < $length; $i++) { 
				$res[] = $array[$i];
		}
		echo "<pre>";
		print_r($res);
		echo "</pre>";
}


/**
* 
*/
class PlatiRuBuy
{
		
		function __construct()
		{
				# code...
		}

		private static function inv_counter()
		{
			$num = file_get_contents(__DIR__.'/adds/c.txt');
			file_put_contents(__DIR__.'/adds/c.txt', ++$num);
			return $num;
		}

		public function getInvoice($itemid)
		{
			$endpoint = 'https://shop.digiseller.ru/xml/create_invoice.asp';
		// 568398645946
		// 103239093088
			$xml = "<digiseller.request>
								<id_good>$itemid</id_good>
								<wm_id>568398645946</wm_id>
								<email>germanez2000@rambler.ru</email>
								<id_parnter>163508</id_parnter>
								<curr>WMR</curr>
								<lang>ru-RU</lang>
							</digiseller.request>";


			$opts = array('http' =>
				array(
					'method'  => 'POST',
					'header'  => "Content-Type: text/xml\r\n",
					'content' => $xml,
					'timeout' => 60
				)
			);
															
			$context  = stream_context_create($opts);
			$responseXML = file_get_contents($endpoint, false, $context);
			$responseObj = simplexml_load_string( str_replace('&', '&amp;', $responseXML) );

			if($responseXML === false){
				return [
					'success'=>false,
					'text'=>'Ошибка при парсинге XML',
					'xml'=>htmlentities(iconv('windows-1251', 'utf-8', $responseXML)),
				];
			}

			if ((string)$responseObj->retval !== '0') {
				return [
					'success'=>false,
					'text'=>'Ошибка при выписке счета',
					'xml'=>htmlentities(iconv('windows-1251', 'utf-8', $responseXML)),
					'retval'=>(string)$responseObj->retval,
					'retdesc'=>(string)$responseObj->retdesc,
					];
			}

			return [
				'success'=>'OK',
				'text'=>'счет выписан',
				'xml'=>htmlentities(iconv('windows-1251', 'utf-8', $responseXML)),
				'retval'=>(string)$responseObj->retval,
				'retdesc'=>(string)$responseObj->retdesc,
				'inv'=>(array)$responseObj->inv,
				];

		}

/*
21 - счет, по которому совершается оплата не найден
103 - транзакция с таким значением поля w3s.request/trans/tranid уже выполнялась
110 - нет доступа к интерфейсу
*/
		public function payInvoice($invid,$payeePurse)
		{

		  $request = new baibaratsky\WebMoney\Api\X\X2\Request;

		  $sign = new baibaratsky\WebMoney\Signer('568398645946', __DIR__.'/adds/kwms/568398645946.kwm', KWM46_PASSWORD);

		  //   <option value="R046889215238">R046889215238 (66.00 - Рубли)</option>
		  //   <option value="R337227083600">R337227083600&nbsp;&nbsp;(730.05 - place4game/Расходы)</option>
		  $webMoney = new baibaratsky\WebMoney\WebMoney(new baibaratsky\WebMoney\Request\Requester\CurlRequester);

		  $request->setSignerWmid('568398645946');
		  // Unique ID of the transaction in your system
		  $request->setTransactionExternalId(self::inv_counter());
		  $request->setPayerPurse('R046889215238');
		  $request->setPayeePurse($payeePurse);
		  $request->setAmount(0.01); // Payment amount
		  $request->setDescription('Test payment');
		  $request->setInvoiceId($invid);

		  $request->sign($sign);

		  if ($request->validate()) {
		      /** @var X2\Response $response */
		      $response = $webMoney->request($request);

		    echo "<pre>";
		    print_r($response);
		    echo "</pre>";
		      $cod = $response->getReturnCode();
		      if ($cod === 0) {
		          echo 'Successful payment, transaction id: ' . $response->getTransactionId();
		      } else {
		          echo 'Payment error: ' . $response->getReturnDescription();
		          echo "<hr>";
		          var_dump($cod);
		      }
		  } else {
		    echo "<pre>";
		    print_r($request->getErrors());
		    echo "</pre>";
		  }

		}

}