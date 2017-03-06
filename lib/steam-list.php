<?php
if (isset($_GET['steam-link'])) {
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
	$context = stream_context_create($options);
	echo file_get_contents($_GET['steam-link'], false, $context);
	die();
}
if (isset($_GET['steam-id'])) {
	$sid = $_GET['steam-id'];
	$res = arrayDB("SELECT steam_de.*,
						steam_items.item1_price, steam_items.item1_name, steam_items.item1_desc, steam_items.item1_id,
						steam_items.item2_price, steam_items.item2_name, steam_items.item2_desc, steam_items.item2_id,
						steam_items.item3_price, steam_items.item3_name, steam_items.item3_desc, steam_items.item3_id
					FROM steam_de LEFT JOIN steam_items ON steam_de.id=steam_items.game_id
					WHERE steam_de.id = '$sid' LIMIT 1");
	if ($res) {
		$res = $res[0];
		$res['desc'] = add_dlc_addon_to_desc($res);
		if ($res['type'] === 'dlc') {
			$res['title'] .= (stripos($res['title'], 'dlc') === false) ? ' AddOn/DLC':' AddOn';
		}
		$res['title_long'] = add_words_to_game_name($res['title']);
		$exrate = arrayDB("SELECT * FROM aq_settings WHERE name='exrate'");
		if($exrate) $exrate = (float)$exrate[0]['value'];
		$res['item1_recom'] = formula($res['item1_price'], $exrate);
		$res['item2_recom'] = formula($res['item2_price'], $exrate);
		$res['item3_recom'] = formula($res['item3_price'], $exrate);
		echo json_encode($res);
	}else{
		echo "{}";
	}
	die();
}

$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
$limit = @$_GET['limit'] ? (int)$_GET['limit'] : 10;

if(isset($_GET['offset']) && isset($_GET['limit'])){
	$limit = (int)$_GET['offset'].','.(int)$_GET['limit'];
}else{
	$limit = '10';
}


// if (isset($_GET['scan'])) {
// 	$scan = _esc(trim(strip_tags($_GET['scan'])));
// }else{
// 	$scan = arrayDB("SELECT scan FROM steam_items order by id desc limit 1")[0]['scan'];
// }

$where = get_steam_miracle_where();

$steam_arr = arrayDB("SELECT steam_de.id, steam_de.type, steam_de.title, steam_de.o_rating, steam_de.o_reviews, steam_de.link, steam_de.is_on_ebay,
						steam_items.item1_price, steam_items.item1_name, steam_items.item1_desc, steam_items.item1_id,
						steam_items.item2_price, steam_items.item2_name, steam_items.item2_desc, steam_items.item2_id,
						steam_items.item3_price, steam_items.item3_name, steam_items.item3_desc, steam_items.item3_id
					FROM steam_de LEFT JOIN steam_items ON steam_de.id=steam_items.game_id
					WHERE $where ORDER BY o_reviews DESC LIMIT $limit"); //ORDER BY o_rating DESC

// var_dump($scan);
// var_dump($limit);
//var_dump($steam_arr[0]);
//sa();

$count = arrayDB("SELECT count(steam_de.id) as count
					FROM steam_de LEFT JOIN steam_items ON steam_de.id=steam_items.game_id
					WHERE $where")[0]['count'];


?>
<div class="col-sm-4"><?php aqs_pagination('steam_de', $count);?></div>
<div class="col-sm-4">
	<button class="btn btn-primary" id="repInterval">Re-parse 100</button>
</div>

<br>

<table class='ppp-table-collapse al-table js-tabledeligator' style="width: 100%;font-size: 13px;">
	<tr>
		<th>id</th>
		<th title="Game name">Title</th>
		<th>Buttons</th>
		<th title="Rating">Rating</th>
		<th title="Reviews">Reviews</th>
		<th class="sort" data-sort="row3">Цена 1</th>
		<th>Links</th>
		<th class="sort" data-sort="row5">Цена 2</th>
		<th>Links</th>
		<th class="sort" data-sort="row7">Цена 3</th>
		<th>Links</th>
		<th>eBay link</th>
	</tr>
<?php
function table_inners(&$steam_arr){

foreach ($steam_arr as $k => $steam_item):

	echo "<tr>";
	echo '<td class="sid">',$steam_item['id'],'</td>';
	echo '<td><div class="js-ssm" lang="',$steam_item['link'],'">',$steam_item['title'],'</div></td>';
	echo '<td><button class="js-add">add</button> ',$steam_item['type'],'</td>';
	echo '<td>',$steam_item['o_rating'],'</td>';
	echo '<td>',$steam_item['o_reviews'],'</td>';
	echo '<td>',$steam_item['item1_price'],'</td>';
	echo '<td><a 
				class="adb adb1"
				href="http://www.plati.ru/itm/',$steam_item['item1_id'],'?ai=163508" 
				target="_blank"
				title="',$steam_item['item1_name'],'"
			   >link</a></td>';
	echo '<td>',$steam_item['item2_price'],'</td>';
	echo '<td><a 
				class="adb adb2"
				href="http://www.plati.ru/itm/',$steam_item['item2_id'],'?ai=163508" 
				target="_blank"
				title="',$steam_item['item2_name'],'"
			   >link</a></td>';
	echo '<td>',$steam_item['item3_price'],'</td>';
	echo '<td><a 
				class="adb adb3"
				href="http://www.plati.ru/itm/',$steam_item['item3_id'],'?ai=163508" 
				target="_blank"
				title="',$steam_item['item3_name'],'"
			   >link</a></td>';
	echo '<td><a href="http://www.ebay.de/sch/i.html?_odkw=Rust+Steam&LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_sacat=1249&_nkw=',$steam_item['title'],'" target="_blank">Ссылка</a></td>';
	echo "</tr>";

endforeach;
}
table_inners($steam_arr);
echo "</table>";
?>




<!--===========steam modal===================-->
<div class="modal fade" id="steamModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">steam</h4>

      </div>
      <div class="modal-body">

<iframe src="" id="steam-frame" frameborder="0"></iframe>

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/steam modal===================-->

<!--===========add modal===================-->
<div class="modal fade" id="addModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">steam</h4>

      </div>
      <div class="modal-body" id="modal_body">

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/add modal===================-->




<script>
$(function() {

	var steam_frame = $('#steam-frame');
	var steam_modal = $('#steamModal');
	$('.js-tabledeligator').on('click', '.js-ssm', function(e) {
		//steam_frame.attr('src', 'about:blank');
		steam_frame.attr('src', 'ajax.php?action=steam-list&steam-link='+this.lang);
		steam_modal.modal('show');
	});	

})
</script>

<script src="https://unpkg.com/react@15/dist/react.min.js"></script>
<script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>

<script type="text/babel" src="js/steam-list.js"></script>