<?php ini_set('max_execution_time', 300);
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

		// Картинки
		$app_id = $res['appid'];
		$app_sub = $res['type'];
		if($app_sub === 'dlc') $app_sub = 'app';
		// если это бандл то картинки берутся с первой игры
		if ($app_sub === 'sub') {
			$includes_arr = explode(',', $res['includes']);
			if($includes_arr){
				$app_id = $includes_arr[0];
				$app_sub = 'app';
			}
		}
		// steam-images checker
		$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
		$res['img_checker'] = array_values(json_decode($checker, true));

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
$table = 'steam_de';
$where = get_steam_miracle_where2($table);

$steam_arr = arrayDB("SELECT steam_de.id, steam_de.appid, steam_de.type, steam_de.title, steam_de.o_rating, steam_de.o_reviews, steam_de.link,steam_de.notice,
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
					FROM $table LEFT JOIN steam_items ON steam_de.id=steam_items.game_id
					WHERE $where")[0]['count'];


?>
<div class="container">
	<div class="row">
		<div class="col-sm-6"><?php aqs_pagination('steam_de', $count);?>
			<small title="включая пропущенные игры с одним изображением" style="position:absolute;left:220px;margin-top:-15px;">(*)</small>
		</div>
		<div class="col-sm-6">
			<button class="btn btn-primary" id="repInterval">Re-parse 100</button>
		</div>
	</div>

	<br>
	<div class="form-inline row" name="bundle_form">
	  <div class="form-group col-xs-6" style="width:50%;">
	    <label for="sagas">Steam link: </label>
	    <input name="bundle_link" type="text" class="form-control" id="bundle_link" placeholder="http://store.steampowered.com/bundle/2433/Strategy_Game_of_the_Year_Bundle/" style="width:80%;">
	  </div>
	  <div class="col-xs-6">
		  <button id="bundle_save" class="btn btn-success">Save</button>
		  <button id="bundle_add" class="btn btn-info" disabled="disabled">Add</button>
	  </div>
	</div>
	<br>
</div>
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
		<th title="notice isset">nn</th>
	</tr>
<?php
function table_inners(&$steam_arr){

foreach ($steam_arr as $k => $steam_item):
	if(steam_images_count($steam_item['appid'], $steam_item['type']) < 2) continue;
	$is_notice = ($steam_item['notice']) ? '<i title="notice isset" class="glyphicon glyphicon-pencil" aria-hidden="true"></i>' : '';
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
	echo '<td>',$is_notice,'</td>';
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

<!-- react 15 -->
<!-- <script src="https://unpkg.com/react@15/dist/react.min.js"></script> -->
<!-- <script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script> -->


<!-- react 16 -->
<script src="https://unpkg.com/react@16/umd/react.development.js"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.26.0/babel.min.js"></script>

<script type="text/babel" src="js/steam-list.js"></script>