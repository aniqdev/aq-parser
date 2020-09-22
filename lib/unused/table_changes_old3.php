<style>
.ppp-block,.ppp-table{
    max-width: initial;
}
.gig{
	background: #eaeaea;
	color: #555;
	font-weight: bold;
}
</style>

<div class="ppp-block ppp-right">
<?php $dataex = '';
$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
if($exrate) $dataex = $exrate[0]['value'];
?>
	<form class="ppp-right exrate-form">
		<input size="6" maxlength="6" type="text" id="rateinp">
		<button id="rateset" dataex="<?php echo $dataex;?>">set</button>
	</form>
<ul class="ppp-parses">
<?php
	$scans = arrayDB('SELECT scan as hash,`date` from items group by scan order by id desc');
	$ebay_scan = arrayDB('SELECT scan FROM ebay_results ORDER BY id DESC LIMIT 1');
	$ebay_scan = $ebay_scan[0]['scan'];
?>
</ul>
</div>
<div class="ch-tab-navigator">
	<a href="?action=table_changes&tab=1" class="ch-tab <?= tab_active('tab','1');?>">All</a>
	<a href="?action=table_changes&tab=2" class="ch-tab <?= tab_active('tab','2');?>">Подорожал</a>
	<a href="?action=table_changes&tab=3" class="ch-tab <?= tab_active('tab','3');?>">Подешевел</a>
	<a href="?action=table_changes&tab=5" class="ch-tab <?= tab_active('tab','5');?>">Появился</a>
	<a href="?action=table_changes&tab=6" class="ch-tab <?= tab_active('tab','6');?>">Пропал</a>
	<a href="?action=table_changes&tab=7" class="ch-tab <?= tab_active('tab','7');?>">Не появился</a>
	<a href="?action=table_changes&tab=4" class="ch-tab <?= tab_active('tab','4');?>">Не изменился</a>
	<form method="POST" class="choose-old">
		<select name="old">
			<?php $scans_q = count($scans);
				for ($i=2; $i < $scans_q ; $i++) { 
					echo '<option value="',$scans[$i]['hash'],'">Парс от ',$scans[$i]['date'],'</option>';
				}
				// foreach ($scans as $key => $value) {
				// 	echo '<option value="',$value['hash'],'">Парс ',$value['id'],' От ',$value['date'],'</option>';
				// }
			?>
		</select>
		<input type="submit">
	</form>
</div>

<div class="ch-tab-panel" id="js-tch-deligator">
<?php	
	if (isset($_GET['scan'])) {
		$scan = _esc(trim(strip_tags($_GET['scan'])));
	}else{
		$scan = $scans[0]['hash'];
	}

	isset($scans[0]) ? $scanNew = $scans[0]['hash'] : $scanNew = 0;

	if(isset($_POST['old'])) {
		$scanOld = _esc(trim(strip_tags($_POST['old'])));
	}elseif(isset($scans[1])) {
		$scanOld = $scans[1]['hash'];
	}else{
		$scanOld = 0;
	}

$queryNew = "SELECT 
new.item1_id, games.name, new.newPrice, new.newPrice-old.oldPrice as differ, 
old.oldPrice, new.item1_name as n_name, old.item1_name as o_name,
games.id as game_id, games.ebay_id, woo_id, hood_id,
itemid1, title1, price1,
itemid2, title2, price2,
itemid3, title3, price3,
itemid4, title4, price4,
itemid5, title5, price5
FROM games 
INNER JOIN (SELECT items.game_id,items.item1_price as newPrice, items.item1_id, items.item1_name
			FROM items 
			WHERE items.scan='$scanNew') as new
ON games.id=new.game_id

INNER JOIN (SELECT items.game_id,items.item1_price as oldPrice, items.item1_name
			FROM items 
			WHERE items.scan='$scanOld') as old
ON games.id=old.game_id

LEFT OUTER JOIN (SELECT * FROM ebay_results WHERE scan='$ebay_scan') as ebay
ON games.id=ebay.game_id";

// file_put_contents(__DIR__.'/adds/query.txt', $queryNew);

	$res = arrayDB($queryNew);

// $gameStayed      = [];
// $gameAppeared    = [];
// $gameDisappeared = [];
// $gameChangedP    = [];
// $gameChangedM    = [];
// $gameChangedZ    = [];
	// foreach ($res as $key => $val) {
	// 	if ($val['newPrice'] == 0 || $val['oldPrice'] == 0) { 
			
	// 		if ($val['newPrice'] == 0 && $val['oldPrice'] == 0) { // Игра НЕ появилась
	// 			$gameStayed[] = $val;
	// 		}elseif ($val['newPrice'] != 0 && $val['oldPrice'] == 0) { // Игра появилась
	// 			$gameAppeared[] = $val;
	// 		}else{
	// 			$gameDisappeared[] = $val; // Игра пропала
	// 		}

	// 	}else{

	// 		if ($val['newPrice'] > $val['oldPrice']) { // Игра подорожала
	// 			$gameChangedP[] = $val;
	// 		}elseif($val['newPrice'] < $val['oldPrice']){ // Игра подешевела
	// 			$gameChangedM[] = $val;
	// 		}else{
	// 			$gameChangedZ[] = $val; // Цена не изменилась
	// 		}

	// 	}
	// }

$res = array_filter($res, function ($val){

	switch (isset($_GET['tab']) ? $_GET['tab'] : '2') {
		case '1': return true; break;
		case '2': if ($val['newPrice'] > $val['oldPrice']) return true; break; // Игра подорожала
		case '3': if ($val['newPrice'] < $val['oldPrice']) return true; break; // Игра подешевела
		case '4': if ($val['newPrice'] == $val['oldPrice'] && $val['newPrice'] != 0) return true; break; // Цена не изменилась
		case '5': if ($val['newPrice'] != 0 && $val['oldPrice'] == 0) return true; break; // Игра появилась
		case '6': if ($val['newPrice'] == 0 && $val['oldPrice'] != 0) return true; break; // Игра пропала
		case '7': if ($val['newPrice'] == 0 && $val['oldPrice'] == 0) return true; break; // Игра НЕ появилась
		default: return false;
	}
});

$ids_arr = arrayDB("SELECT item_id FROM ebay_games");
foreach ($ids_arr as $k => &$v) $v = $v['item_id'];
$ids_arr = array_flip($ids_arr);

function drowPlatiTable($res,&$ids_arr){
?>
<div class="ppp-block">
	<input class="search" placeholder="Search">&nbsp;&nbsp;&nbsp;
	total: <?= count($res);?>
</div>
<div class="ppp-block">
<table class="ppp-table changes" style="width: 100%;">
	<thead><tr>
		<th class="sort asc" data-sort="row1">#</th>
		<th class="sort" data-sort="row2">Product title</th>
		<th>eBay</th>
		<th>Woo</th>
		<th>Hood</th>
		<th title="One Click Price Changer">All</th>
		<th class="sort" data-sort="row3">Differ</th>
		<th class="sort" data-sort="row5">New Price</th>
		<th class="sort" data-sort="row7">Old Price</th>
		<th>Link</th>
		<th>ebay 1</th>
		<th>ebay 2</th>
		<th>ebay 3</th>
		<th>ebay 4</th>
		<th>ebay 5</th>
		<th>Link</th>
	</tr></thead>
	<tbody class="list tch-table-deligator">
<?php
$n = 1;

foreach ($res as $key => $value) {

	$gig1 = '';$gig2 = '';$gig3 = '';$gig4 = '';$gig5 = '';

	if (isset($ids_arr[$value['itemid1']])) $gig1 = 'gig';
	if (isset($ids_arr[$value['itemid2']])) $gig2 = 'gig';
	if (isset($ids_arr[$value['itemid3']])) $gig3 = 'gig';
	if (isset($ids_arr[$value['itemid4']])) $gig4 = 'gig';
	if (isset($ids_arr[$value['itemid5']])) $gig5 = 'gig';

	$good_e = $value['ebay_id'] ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty';
	$good_w = $value['woo_id'] ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty';
	$good_h = $value['hood_id'] ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty';

	global $dataex;
	$europrice = formula ($value['newPrice'], $dataex);
	echo   '<tr
			   data-gameid="',$value['game_id'],'"
			   data-ebayid="',$value['ebay_id'],'"
			   data-wooid="',$value['woo_id'],'"
			   data-hoodid="',$value['hood_id'],'"
			   data-plati1id="',$value['item1_id'],'">
				<td class="row1">',$n++,'</td>
				<td class="row2 tch-merged">',$value['name'],'</td>
				<td><a href="#ebayModal" class="tc-ebay ',$good_e,'"></a></td>
				<td><a href="#wooModal" class="tc-woo ',$good_w,'"></a></td>
				<td><a href="#hoodModal" class="tc-hood ',$good_h,'"></a></td>
				<td title="',$value['n_name'],'" class="text-center p0">
					<a href="#mChange" class="mChange tch-mbtn glyphicon glyphicon-ok"></a>
					<a href="#mRemove" class="mRemove tch-mbtn glyphicon glyphicon-remove"></a>
				</td>
				<td class="row3">',round($value['differ'],2),'</td>
				<td class="row5" title="',$value['n_name'],'">',$value['newPrice'],'</td>
				<td class="row7" title="',$value['o_name'],'">',$value['oldPrice'],'</td>
				<td class="row8" title="',$value['n_name'],'"><a href="http://www.plati.ru/itm/',$value['item1_id'],'?ai=163508" target="_blank">Ссылка</a></td>
				<td class="',$gig1,'" iid="',$value['itemid1'],'" title="',$value['title1'],'">',$value['price1'],'</td>
				<td class="',$gig2,'" iid="',$value['itemid2'],'" title="',$value['title2'],'">',$value['price2'],'</td>
				<td class="',$gig3,'" iid="',$value['itemid3'],'" title="',$value['title3'],'">',$value['price3'],'</td>
				<td class="',$gig4,'" iid="',$value['itemid4'],'" title="',$value['title4'],'">',$value['price4'],'</td>
				<td class="',$gig5,'" iid="',$value['itemid5'],'" title="',$value['title5'],'">',$value['price5'],'</td>
				<td><a href="http://www.ebay.de/sch/i.html?_odkw=Rust+Steam&LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_sacat=1249&_nkw=',$value['name'],'" target="_blank">Ссылка</a></td>
			</tr>';
}
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
<?php
} // drowPlatiTable()
echo '<div id="platitable1" class="platitable">';
// switch (isset($_GET['tab']) ? $_GET['tab'] : '2') {
// 	case '1': drowPlatiTable($res,$ids_arr); break;
// 	case '2': drowPlatiTable($gameChangedP,$ids_arr); break;
// 	case '3': drowPlatiTable($gameChangedM,$ids_arr); break;
// 	case '4': drowPlatiTable($gameChangedZ,$ids_arr); break;
// 	case '5': drowPlatiTable($gameAppeared,$ids_arr); break;
// 	case '6': drowPlatiTable($gameDisappeared,$ids_arr); break;
// 	case '7': drowPlatiTable($gameStayed,$ids_arr); break;
// }
drowPlatiTable($res,$ids_arr);
?>
</div> <!-- /platitable -->
</div> <!-- /ch-tab-panel -->

<!--===========ebay modal===================-->
<div class="modal fade" id="ebayModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">eBay change price <b class="js-modal-europrice"></b></h4>
      </div>
      <div class="modal-body">

        <form class="form-inline" id="js-ebay-check-form">
		  <div class="form-group js-ebay-id-input-holder">
		    <label for="js-ebay-item-id-input">eBay Item Id: </label>
		    <input type="text" class="form-control" id="js-ebay-item-id-input" placeholder="Item Id">
		  </div>
		  <button type="submit" class="btn btn-success" id="js-ebay-check">Check!</button>
		</form><br><hr>
		<p class="titles-info">parser title:</p>
		<h3 class="modal-parser-title">...</h3><hr>
		<p class="titles-info">eBay title:</p>&nbsp;
		<b id="js-modal-ebay-price"></b>
		<h3 class="js-modal-ebay-title"><img src="images/more-loading.gif" alt="loading"></h3><hr>
		<p class="titles-info">Plati.ru title:</p>
		<h3 class="modal-plati-title">...</h3>

      </div>
      <div class="modal-footer">

		<form class="form-inline" id="js-ebay-change-form">
			<div class="form-group">
				<label for="js-ebay-item-price-input">€</label>
				<input type="text" class="form-control" id="js-ebay-item-price-input" placeholder="Item Price">
			</div>
			<button disabled type="button" class="btn btn-default" id="js-ebay-remove">Remove from Sale</button>
			<button disabled type="submit" class="btn btn-primary" id="js-ebay-change-price">Change Price</button>
		</form>

      </div>
    </div>
  </div>
</div>
<!--===========/ebay modal===================-->

<!--===========woo modal===================-->
<div class="modal fade" id="wooModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">WooCommerce change price <b class="js-modal-europrice"></b></h4>
      </div>
      <div class="modal-body">

        <form class="form-inline" id="woo-check-form">
		  <div class="form-group js-woo-id-input-holder">
		    <label for="woo-item-id-input">WooCommerce Item Id: </label>
		    <input type="text" class="form-control" id="woo-item-id-input" placeholder="Item Id">
		  </div>
		  <button type="submit" class="btn btn-success" id="woo-check">Check!</button>
		</form><br><hr>
		<p class="titles-info">parser title:</p>
		<h3 class="modal-parser-title">...</h3><hr>
		<p class="titles-info">WooCommerce title:</p>
		<b id="js-modal-woo-price"></b>
		<h3 class="modal-woo-title"><img src="images/more-loading.gif" alt="loading"></h3><hr>
		<p class="titles-info">Plati.ru title:</p>
		<h3 class="modal-plati-title">...</h3>

      </div>
      <div class="modal-footer">

		<form class="form-inline" id="woo-change-form">
			<div class="form-group">
				<label for="woo-item-price-input">€</label>
				<input type="text" class="form-control" id="woo-item-price-input" placeholder="Item Price">
			</div>
			<button disabled type="button" class="btn btn-default" id="woo-remove">Remove from Sale</button>
			<button disabled type="submit" class="btn btn-primary" id="woo-change-price">Change Price</button>
		</form>

      </div>
    </div>
  </div>
</div>
<!--===========/woo modal===================-->

<!--===========hood modal===================-->
<div class="modal fade" id="hoodModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title col555">Hood add id</h4>
      </div>
      <div class="modal-body">

        <form class="form-inline" id="hood-check-form">
		  <div class="form-group js-hood-id-input-holder">
		    <label for="hood-item-id-input">Hood Item Id: </label>
		    <input type="text" class="form-control" id="hood-item-id-input" placeholder="Item Id">
		  </div>
		  <button type="submit" class="btn btn-success" id="hood-check">Check!</button>
		</form><hr>
		<p class="titles-info">Hood.de title:</p>&nbsp;
		<b id="modal-hood-price"></b>
		<h3 id="modal-hood-title">...</h3>

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/hood modal===================-->


<!--=========== Merged modal ===================-->
<div class="modal fade" role="dialog" id="mergedModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="gridSystemModalLabel">Merged Price Changer</h4>
      </div>
      <div class="modal-body">
        <div class="row frow frow1">
          <div class="col-sm-2">Parser</div>
          <div class="col-sm-7 fcol fcol2 clip"></div>
          <div class="col-sm-3 fcol fcol3"><a id="m-ebli" href="#" target="_blank" style="color: #ca8e3a;"><table><tr></tr></table></a></div>
        </div>
        <div class="row frow frow2">
          <div class="col-sm-2">eBay</div>
          <div class="col-sm-7 fcol fcol2 clip"><img src="images/more-loading.gif" alt="loading"></div>
          <div class="col-sm-1 fcol fcol3"><b>.</b></div>
          <div class="col-sm-2 fcol fcol4"><input id="js-fEprice" type="text" class="form-control h28"></div>
        </div>
        <div class="row frow frow3">
          <div class="col-sm-2">WooComm</div>
          <div class="col-sm-7 fcol fcol2 clip"><img src="images/more-loading.gif" alt="loading"></div>
          <div class="col-sm-1 fcol fcol3"><b>.</b></div>
          <div class="col-sm-2 fcol fcol4"><input id="js-fWprice" type="text" class="form-control h28"></div>
        </div>
        <div class="row frow frow5">
          <div class="col-sm-2">Hood</div>
          <div class="col-sm-7 fcol fcol2 clip"><img src="images/more-loading.gif" alt="loading"></div>
          <div class="col-sm-1 fcol fcol3"><b>.</b></div>
          <div class="col-sm-2 fcol fcol4"><input id="js-fHprice" type="text" class="form-control h28"></div>
        </div>
        <div class="row frow frow4">
          <div class="col-sm-2">Plati.ru <b>: <i id="consec"></i></b></div>
          <div class="col-sm-7 fcol fcol2 clip">
          	<a class="jsm-plati-title" herf="" target="_blank"></a>
          	<a class="jsm-arr jsm-arr-left glyphicon glyphicon-chevron-left" id="arrleft"></a>
          	<a class="jsm-arr jsm-arr-right glyphicon glyphicon-chevron-right" id="arrright"></a>
          </div>
          <div class="col-sm-3 fcol fcol3 fcol4"></div>
        </div>
      </div>
      <div class="modal-footer">
      	<form action="" id="fBuyItem" target="_blank" class="pull-left" method="POST">
      		<input type="hidden" name="tch-order-itemid" value="" id="tch-order-itemid">
      		<input type="hidden" name="tch-order-orderid" value="" id="tch-order-orderid">
      		<input type="hidden" name="csrf-buy-time" value="" id="csrf-buy-time">
      		<input type="hidden" name="csrf-buy-token" value="<?= $_SESSION['csrf-buy-token'];?>">
      		<button type="submit" class="btn btn-success">Buy</button>
      	</form>
      	<script>
			$('#fBuyItem').submit(function(){if(!confirm("Покупаем?"))return false;});
		</script>
      	<form class="btn-group" id="fChange">
	        <button type="button" class="btn btn-info" id="fBanaddon">to Add-on</button>
	        <button type="button" class="btn btn-warning" id="fBlacklist">to Blacklist</button>
	        <button type="button" class="btn btn-danger" id="fRemove">Remove from Sale</button>
	        <button type="submit" class="btn btn-primary">Change Price</button>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div>
</div>
<!--=========== /Merged modal ===================-->

<script>
	var options = {
	  valueNames: [ 'row1', 'row2', 'row3', 'row5', 'row7', 'row9' ],
	  page: 5000
	};

	var userList = new List('platitable1', options);
	// var userList = new List('platitable2', options);
	// var userList = new List('platitable3', options);
	// var userList = new List('platitable4', options);
	// var userList = new List('platitable5', options);
	// var userList = new List('platitable6', options);
	// var userList = new List('platitable7', options);

</script>

<!-- <script src="js/react.min.js"></script>
<script src="js/react-dom.min.js"></script>
<script src="js/babel-core.min.js"></script>

<script src="js/table_changes.jsx" type="text/babel"></script> -->