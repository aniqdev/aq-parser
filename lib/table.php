<?php
if (isset($_GET['del'])) {
	arrayDB("DELETE FROM items WHERE scan='"._esc($_GET['del'])."'");
}

?><div class="ppp-block ppp-right">
<ul class="ppp-parses">
<?php
	$scans = arrayDB('SELECT scan as hash,`date`,count(*) as count FROM items GROUP BY scan ORDER BY id DESC');
		// echo "<br><pre>\n";
		// print_r($scans);ORDER BY id DESC
		// echo '</pre>';
foreach ($scans as $value) {
	if (strlen($value['hash']) < 12) {
		$date = date('d-m-Y H:i:s', $value['hash']);
	}else{
		$date = $value['date'];
	}
	echo '	<li><a href="/index.php?action=table&del=',$value['hash'],'" title="Delete" class="delscan">×</a> | <a href="/index.php?action=table&scan=',$value['hash'],'" class="ppp-link">Парс от ',$date,' (',$value['count'],')</a></li>
';
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
	<input id="converter" size="6" maxlength="6" type="text" placeholder=" rur to eur">
	<label for="converter" class="converter"></label>
	<?php $dataex = '';
	$exrate = arrayDB("SELECT value FROM aq_settings WHERE name='exrate'");
	if($exrate) $dataex = $exrate[0]['value'];
	?>
	<form class="ppp-right exrate-form">
		<input size="6" maxlength="6" type="text" id="rateinp">
		<button id="rateset" dataex="<?php echo $dataex;?>">set</button>
	</form>
	<button class="copy-table-btn ppp-right" data-clipboard-target="#js-tbody">Copy table</button>
	<div class="radio-wrapper pull-right">
		<input type="radio" class="delim-inp" name="delimetr" value="dot" id="delim-inp1" checked="checked">
		<input type="radio" class="delim-inp" name="delimetr" value="coma" id="delim-inp2">
		<label for="delim-inp1" class="delim-inp1">dot(.)</label>
		<label for="delim-inp2" class="delim-inp2">coma(,)</label>
	</div>
	
</div>
<div class="ppp-block">
<table class="ppp-table tch-table-deligator">
	<thead><tr>
		<th class="sort asc" data-sort="row1">№</th>
		<th class="sort" data-sort="row2">Наименование товара</th>
		<th class="sort" data-sort="row3">Цена 1</th>
		<th>Links</th>
		<th class="sort" data-sort="row5">Цена 2</th>
		<th>Links</th>
		<th class="sort" data-sort="row7">Цена 3</th>
		<th>Links</th>
		<th class="sort" data-sort="row9">euro</th>
	</tr></thead>
	<tbody class="list euro" id="js-tbody">
<?php	
	if (isset($_GET['scan'])) {
		$scan = _esc(trim(strip_tags($_GET['scan'])));
	}elseif (isset($scans[0])){
		$scan = $scans[0]['hash'];
	}else{
		$scan = 0;
	}
	$res = arrayDB("SELECT games.name,  items.item1_price, items.item1_name, items.item1_desc, items.item1_id,
										items.item2_price, items.item2_name, items.item2_desc, items.item2_id,
										items.item3_price, items.item3_name, items.item3_desc, items.item3_id
						FROM games INNER JOIN items ON games.id=items.game_id
						WHERE items.scan='$scan'");

$n = 1;

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";

foreach ($res as $key => $value) {
	// $euro = (($value['item1_price']/$exrate)*1.0242+0.35)/((1-0.15)-0.019-0.08);
	// $euro = round($euro, 4);
	echo   '<tr>
				<td class="row1">',$n++,'</td>
				<td class="row2">',$value['name'],'</td>
				<td class="row3 js-change-delim" title="',$value['item1_name'],'">',$value['item1_price'],'</td>
				<td class="row4" title="',$value['item1_name'],'">
					<a href="http://www.plati.ru/itm/',$value['item1_id'],'?ai=163508" target="_blank">Ссылка</a>
					<a href="http://www.plati.ru/seller/info/',(int)$value['item1_desc'],'" target="_blank" title="seller">(™)</a>
				</td>
				<td class="row5 js-change-delim" title="',$value['item2_name'],'">',$value['item2_price'],'</td>
				<td class="row6" title="',$value['item2_name'],'">
					<a href="http://www.plati.ru/itm/',$value['item2_id'],'?ai=163508" target="_blank">Ссылка</a>
					<a href="http://www.plati.ru/seller/info/',(int)$value['item1_desc'],'" target="_blank" title="seller">(™)</a>
				</td>
				<td class="row7 js-change-delim" title="',$value['item3_name'],'">',$value['item3_price'],'</td>
				<td class="row8" title="',$value['item3_name'],'">
					<a href="http://www.plati.ru/itm/',$value['item3_id'],'?ai=163508" target="_blank">Ссылка</a>
					<a href="http://www.plati.ru/seller/info/',(int)$value['item1_desc'],'" target="_blank" title="seller">(™)</a>
				</td>
				<td class="row9 js-change-delim">',0,'</td>
			</tr>';
}// http://www.plati.ru/itm/1584702?ai=163508
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
</div> <!-- platitable -->

<style>
.radio-wrapper label{
	border: 1px solid #ccc;
	border-radius: 5px;
	background: #eaeaea;
	color: #222;
	padding: 2px 7px;
	cursor: pointer;
	-webkit-user-select: none;  /* Chrome all / Safari all */
	-moz-user-select: none;     /* Firefox all */
	-ms-user-select: none;      /* IE 10+ */
	user-select: none;          /* Likely future */ 
}
.radio-wrapper input{
	display: none;
}
.radio-wrapper #delim-inp1:checked~.delim-inp1,
.radio-wrapper #delim-inp2:checked~.delim-inp2{
	background: #AEDBAE;
    border: 1px solid green;
    cursor: default;
}
</style>

<script src="js/clipboard.min.js"></script>
<script>
	var options = {
	  valueNames: [ 'row1', 'row2', 'row3', 'row5', 'row7', 'row9' ],
	  page: 2000
	};

	var userList = new List('platitable', options);
	new Clipboard('.copy-table-btn');

	$(function() {
		$('.delim-inp').on('change', function(e) {
			if (this.value === 'dot') {
				$('.js-change-delim').each(function(i) {
					$(this).text($(this).text().replace(',','.'));
				});
			}else if (this.value === 'coma') {
				$('.js-change-delim').each(function(i) {
					$(this).text($(this).text().replace('.',','));
				});
			}
		});
	});
</script>