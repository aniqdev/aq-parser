<?php
if (isset($_GET['del'])) {
	arrayDB("DELETE FROM ebay_results WHERE scan='"._esc($_GET['del'])."'");
}

?>
<div class="ppp-block ppp-right">
<ul class="ppp-parses">
<?php
	$scans = arrayDB('SELECT scan,count(*) as count FROM ebay_results GROUP BY scan ORDER BY id DESC');
		// echo "<br><pre>\n";
		// print_r($scans);
		// echo '</pre>';
foreach ($scans as $key => $value) {
	$d = date('d.m.Y H:i', $value['scan']);
	echo '<li>
	<a href="/index.php?action=ebay_table&del=',$value['scan'],'" title="Delete" class="delscan">×</a> | 
	<a href="/index.php?action=ebay_table&scan=',$value['scan'],'" class="ppp-link">Парс от ',$d,' (',$value['count'],')</a>
	</li>';
}
?>
</ul>
<script>
	$('.delscan').click(function(){ if (!confirm("Удалять?")) return false; });
</script>
</div>
<div id="platitable" class="platitable">
<div class="ppp-block">
	<input class="search" placeholder="Search">&nbsp;&nbsp;&nbsp;

</div>
<div class="ppp-block">
<table class="ppp-table">
	<thead>
		<tr>
			<th>№</th>
			<th>Наименование товара</th>
			<th>Цена 1</th>
			<th>Цена 2</th>
			<th>Цена 3</th>
			<th>Цена 4</th>
			<th>Цена 5</th>
			<th>Link</th>
		</tr>
	</thead>
	<tbody class="list euro">
<?php	
	if (isset($_GET['scan'])) {
		$scan = _esc(trim(strip_tags($_GET['scan'])));
	}elseif (isset($scans[0])){
		$scan = $scans[0]['scan'];
	}else{
		$scan = 0;
	}
	// echo $scan;
	$query = 	       "SELECT games.name,  ebay_results.itemid1, ebay_results.title1, ebay_results.price1,
											ebay_results.itemid2, ebay_results.title2, ebay_results.price2,
											ebay_results.itemid3, ebay_results.title3, ebay_results.price3,
											ebay_results.itemid4, ebay_results.title4, ebay_results.price4,
											ebay_results.itemid5, ebay_results.title5, ebay_results.price5
						FROM games INNER JOIN ebay_results ON games.id=ebay_results.game_id
						WHERE ebay_results.scan='$scan'";
	$res = arrayDB($query);
	 $n = 1;

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

foreach ($res as $key => $value) {

echo   '<tr data-idarr="[',$value['itemid1'],',',$value['itemid2'],',',$value['itemid3'],',',$value['itemid4'],',',$value['itemid5'],']">
			<td>',$n++,'</td>
			<td class="row1 tit trr">',$value['name'],'</td>
			<td class="tableitem" data-itemid="',$value['itemid1'],'" title="',$value['title1'],'">',$value['price1'],'</td>
			<td class="tableitem" data-itemid="',$value['itemid2'],'" title="',$value['title2'],'">',$value['price2'],'</td>
			<td class="tableitem" data-itemid="',$value['itemid3'],'" title="',$value['title3'],'">',$value['price3'],'</td>
			<td class="tableitem" data-itemid="',$value['itemid4'],'" title="',$value['title4'],'">',$value['price4'],'</td>
			<td class="tableitem" data-itemid="',$value['itemid5'],'" title="',$value['title5'],'">',$value['price5'],'</td>
			<td><a href="http://www.ebay.de/sch/i.html?_odkw=Rust+Steam&LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_sacat=1249&_nkw=',$value['name'],'" target="_blank">Ссылка</a></td>
		</tr>';
}	// http://www.ebay.de/sch/i.html?_odkw=Rust+Steam&LH_PrefLoc=2&_sop=2&LH_BIN=1&_osacat=1249&_from=R40&_trksid=p2045573.m570.l1313.TR0.TRC0.H0.TRS0&_nkw=Rust+Steam&_sacat=1249
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
</div> <!-- platitable -->
<script>
	var options = {
	  valueNames: [ 'row1' ],
	  page: 2000
	};

	var userList = new List('platitable', options);
</script>
<div class="layout trig toclos"></div>

	<div class="popup trig">
		<div class="closer toclos">×</div>
		<img class="loader" src="http://www.internwise.eu/templates/softgreen/media/loader1.gif" alt="">
		<div class="innerinfo">
			<div>Продано за неделю <b class="weekSells"></b>
				<span class="alpha"></span>
			</div>
			<h3>График продаж</h3>
			<div id="ebay_chart1" style="width: 1000px; height: 200px;">Нет данных для построения грфика</div>
			<h3>График изманения цен</h3>
			<div id="ebay_chart2" style="width: 1000px; height: 200px;">Нет данных для построения грфика</div>
			<table class="infotable">
				<thead>
					<tr>
						<th>Наименование</th>
						<th>Цена</th>
						<th>Кол-во</th>
						<th>Время продажи</th>
					</tr>
				</thead>
				<tbody class="infotablebody"></tbody>
			</table>
		</div>
	</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script src="js/chart.js"></script>

<div class="inform"></div>