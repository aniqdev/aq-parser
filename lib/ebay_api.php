<html>
<head>
<title>eBay Search Results</title>
	<meta charset="utf-8">
	<style type="text/css">body { font-family: arial,sans-serif;} </style>
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
</head>
<body>
<h1>eBay Search Results</h1>
<div id="pagination"></div>
<div id="results"></div>
<script>
//Parse the response and build an HTML table to display search results
function _cb_findItemsAdvanced(root) {

      var items = root.findItemsAdvancedResponse[0].searchResult[0].item || [];
      //console.log(items);
      var html = [];
      html.push('<table width="100%" border="1" cellspacing="0" cellpadding="3"><tbody>');
      for (var i = 0; i < items.length; ++i) {
        var item     = items[i];
        var title    = item.title;
        var pic      = item.galleryURL;
        var viewitem = item.viewItemURL;
        var price    = item.sellingStatus[0].currentPrice[0].__value__;

        if (null != title && null != viewitem) {
          html.push('<tr><td>' + 
          '<img src="' + pic + '" border="0">' + 
          '</td>' +
          '<td><a href="' + viewitem + '" target="_blank">' + title + '</a></td>'+ 
          '<td>' + price + "$" + '</td></tr>');
        }
      }
      html.push('</tbody></table>');
      document.getElementById("results").innerHTML = html.join("");

}  // End _cb_findItemsByKeywords() function
//Construct the request
//Replace MyAppID with your Production AppID

var seller = 'gig-games';
var page = 1;
if (location.hash.slice(1)) console.log(location.hash.slice(1));
else console.log('false');
var  url = "http://svcs.ebay.com/services/search/FindingService/v1";
	 url += "?OPERATION-NAME=findItemsAdvanced";
	 url += "&SERVICE-VERSION=1.0.0";
	 url += "&SECURITY-APPNAME=Aniq6478a-a8de-47dd-840b-8abca107e57";
	 url += "&GLOBAL-ID=EBAY-DE";
	 url += "&RESPONSE-DATA-FORMAT=JSON";
	 url += "&callback=_cb_findItemsAdvanced";
	 url += "&REST-PAYLOAD";
//	 url += "&categoryId=139973"; // video game  
//	 url += "&keywords="+encodeURI(request); // change value to game title
 	 url += "&itemFilter(0).name=Seller";
	 url += "&itemFilter(0).value="+seller;
	 url += "&paginationInput.entriesPerPage=8";
	 url += "&paginationInput.pageNumber="+page;
	 console.log(url);

//Submit the request
 s=document.createElement('script'); // create script element
 s.src= url;
 document.body.appendChild(s);

 $( document ).ready(function() {
    // console.dir( location.search.slice(1).split('&') );
    // console.log( location.hash.slice(1) );
});
</script>

</body>
</html>