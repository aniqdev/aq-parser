<?php

function findItemsAdvanced($request, $seller, $page = 1, $perPage = 100, $categoryId = false){
		 $url = "http://svcs.ebay.com/services/search/FindingService/v1";
		 $url .= "?OPERATION-NAME=findItemsAdvanced";
//   $url .= "?OPERATION-NAME=findItemsByKeywords";
//   $url .= "?OPERATION-NAME=findItemsByCategory";
		 $url .= "&SERVICE-VERSION=1.0.0";
		 $url .= "&SECURITY-APPNAME=Konstant-Projekt1-PRD-bae576df5-1c0eec3d";
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
		 $url .= "&paginationInput.entriesPerPage=".$perPage;
		 $url .= "&paginationInput.pageNumber=".$page;
//     $url .= "&sortOrder=currentPrice";


		// Открываем файл с помощью установленных выше HTTP-заголовков
		$json = file_get_contents($url);
		return $json;
		//return json_decode($json);
}