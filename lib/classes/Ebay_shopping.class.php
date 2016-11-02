<?php

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


		static function findItemsAdvanced_URL($request, $seller, $page = 1, $categoryId = false){
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
				 
				return $url;
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