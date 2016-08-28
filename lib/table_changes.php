<div class="ajax-loader ajaxed"></div>
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
	$ebay_scan = arrayDB('SELECT DISTINCT scan FROM ebay_results ORDER BY scan DESC LIMIT 1');
	$ebay_scan = $ebay_scan[0]['scan'];
		// echo "<br><pre>\n";
		// print_r($scans);
		// echo '</pre>';
// foreach ($scans as $value) {
// 	echo '<li><a href="/index.php?action=table&scan=',$value['hash'],'" class="ppp-link">Парс ',$value['id'],' От ',$value['date'],' </a></li>';
// }
?>
</ul>
</div>
<div class="ch-tab-navigator">
	<div class="ch-tab active" data-tab="platitable1">All</div>
	<div class="ch-tab" data-tab="platitable2">Подорожал</div>
	<div class="ch-tab" data-tab="platitable3">Подешевел</div>
	<div class="ch-tab" data-tab="platitable5">Появился</div>
	<div class="ch-tab" data-tab="platitable6">Пропал</div>
	<div class="ch-tab" data-tab="platitable7">Не появился</div>
	<div class="ch-tab" data-tab="platitable4">Не изменился</div>
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
games.id as game_id, games.ebay_id, games.woo_id,
ebay.itemid1 as e_id1, ebay.title1 as e_title1, ebay.price1 as e_price1,
ebay.itemid2 as e_id2, ebay.title2 as e_title2, ebay.price2 as e_price2,
ebay.itemid3 as e_id3, ebay.title3 as e_title3, ebay.price3 as e_price3,
ebay.itemid4 as e_id4, ebay.title4 as e_title4, ebay.price4 as e_price4,
ebay.itemid5 as e_id5, ebay.title5 as e_title5, ebay.price5 as e_price5
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

	$res = arrayDB($queryNew);

$gameStayed      = [];
$gameAppeared    = [];
$gameDisappeared = [];
$gameChangedP    = [];
$gameChangedM    = [];
$gameChangedZ    = [];
	foreach ($res as $key => $val) {
		if ($val['newPrice'] == 0 || $val['oldPrice'] == 0) { // Цена не изменилась
			
			if ($val['newPrice'] == 0 && $val['oldPrice'] == 0) {
				$gameStayed[] = $val;
			}elseif ($val['newPrice'] != 0 && $val['oldPrice'] == 0) {
				$gameAppeared[] = $val;
			}else{
				$gameDisappeared[] = $val;
			}

		}else{

			if ($val['newPrice'] > $val['oldPrice']) {
				$gameChangedP[] = $val;
			}elseif($val['newPrice'] < $val['oldPrice']){
				$gameChangedM[] = $val;
			}else{
				$gameChangedZ[] = $val;
			}

		}
	}

//$ids_arr = unserialize(file_get_contents(__DIR__.'/../settings/ids_arr.txt'));
$ids_arr = include(__DIR__.'/../settings/ids_arr.php');

function drowPlatiTable($res,$ids_arr){
?>
<div class="ppp-block">
	<input class="search" placeholder="Search">&nbsp;&nbsp;&nbsp;
</div>
<div class="ppp-block">
<table class="ppp-table changes" style="width: 100%;">
	<thead><tr>
		<th class="sort asc" data-sort="row1">#</th>
		<th class="sort" data-sort="row2">Product title</th>
		<th>eBay</th>
		<th>Woo</th>
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

	if (isset($ids_arr[$value['e_id1']])) $gig1 = 'gig';
	if (isset($ids_arr[$value['e_id2']])) $gig2 = 'gig';
	if (isset($ids_arr[$value['e_id3']])) $gig3 = 'gig';
	if (isset($ids_arr[$value['e_id4']])) $gig4 = 'gig';
	if (isset($ids_arr[$value['e_id5']])) $gig5 = 'gig';

	$good_e = isset($value['ebay_id']) ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty';
	$good_w = isset($value['woo_id']) ? 'glyphicon glyphicon-star' : 'glyphicon glyphicon-star-empty';

	echo   '<tr
			   data-gameid="',$value['game_id'],'"
			   data-ebayid="',$value['ebay_id'],'"
			   data-wooid="',$value['woo_id'],'">
				<td class="row1">',$n++,'</td>
				<td class="row2 tch-merged">',$value['name'],'</td>
				<td><a href="#ebayModal" class="tc-ebay ',$good_e,'"></a></td>
				<td><a href="#wooModal" class="tc-woo ',$good_w,'"></a></td>
				<td title="',$value['n_name'],'" class="text-center p0">
					<a href="#mChange" class="mChange tch-mbtn glyphicon glyphicon-ok"></a>
					<a href="#mRemove" class="mRemove tch-mbtn glyphicon glyphicon-remove"></a>
				</td>
				<td class="row3">',round($value['differ'],2),'</td>
				<td class="row5" title="',$value['n_name'],'">',$value['newPrice'],'</td>
				<td class="row7" title="',$value['o_name'],'">',$value['oldPrice'],'</td>
				<td class="row8" title="',$value['n_name'],'"><a href="http://www.plati.ru/itm/',$value['item1_id'],'?ai=163508" target="_blank">Ссылка</a></td>
				<td class="',$gig1,'" iid="',$value['e_id1'],'" title="',$value['e_title1'],'">',$value['e_price1'],'</td>
				<td class="',$gig2,'" iid="',$value['e_id2'],'" title="',$value['e_title2'],'">',$value['e_price2'],'</td>
				<td class="',$gig3,'" iid="',$value['e_id3'],'" title="',$value['e_title3'],'">',$value['e_price3'],'</td>
				<td class="',$gig4,'" iid="',$value['e_id4'],'" title="',$value['e_title4'],'">',$value['e_price4'],'</td>
				<td class="',$gig5,'" iid="',$value['e_id5'],'" title="',$value['e_title5'],'">',$value['e_price5'],'</td>
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
?>
<!--===========platitable1===================-->
<div id="platitable1" class="platitable fade visible in">
<?php drowPlatiTable($res,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable2===================-->
<div id="platitable2" class="platitable fade">
<?php drowPlatiTable($gameChangedP,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable3===================-->
<div id="platitable3" class="platitable fade">
<?php drowPlatiTable($gameChangedM,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable4===================-->
<div id="platitable4" class="platitable fade">
<?php drowPlatiTable($gameChangedZ,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable5===================-->
<div id="platitable5" class="platitable fade">
<?php drowPlatiTable($gameAppeared,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable6===================-->
<div id="platitable6" class="platitable fade">
<?php drowPlatiTable($gameDisappeared,$ids_arr); ?>
</div> <!-- platitable -->

<!--===========platitable7===================-->
<div id="platitable7" class="platitable fade">
<?php drowPlatiTable($gameStayed,$ids_arr); ?>
</div> <!-- platitable -->

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
          <div class="col-sm-3 fcol fcol3"><table><tr></tr></table></div>
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
      	<form class="btn-group" id="fChange">
	        <button type="button" class="btn btn-info" id="fBanaddon">to ADDon</button>
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
	  page: 2000
	};

	var userList = new List('platitable1', options);
	var userList = new List('platitable2', options);
	var userList = new List('platitable3', options);
	var userList = new List('platitable4', options);
	var userList = new List('platitable5', options);
	var userList = new List('platitable6', options);
	var userList = new List('platitable7', options);



</script>