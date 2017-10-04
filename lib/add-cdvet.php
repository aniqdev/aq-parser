<?php

$matches = json_decode(file_get_contents('csv/matches.json'), true);
// var_dump(isset($matches['2'])?'yes':'');
	// $is_pic = isset($matches[2])?'yes':'';
	// var_dump($is_pic);
if (isset($_POST['action']) && $_POST['action'] === 'get_xcel_info') {
	
	$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
	$row = $cd_arr[$_POST['row']];

	$pic_url = '';
	if (isset($matches[$_POST['row']])) {
		$pic_url = 'http://hot-body.net/Produktbilder_JPG/' . $matches[$_POST['row']][2];
	}
	$row['PictureURL'] = $pic_url;
	$urls = explode('|', $row['M']);
	$row['desc_pics'] = array_filter($urls, function ($url){
		return filter_var($url, FILTER_VALIDATE_URL) and (stripos($url, '.jpg') || stripos($url, '.png'));
	});
	$row['I'] = strip_tags($row['I'], '<u><p><a><div><br><br/><b><strong>');

	preg_match('/(.*)(<div[^\/]+Zusammensetzung.*)/s', $row['I'], $matches);
	$row['desc_top'] = isset($matches[1]) ? $matches[1] : $row['I'];
	$row['desc_bot'] = isset($matches[2]) ? $matches[2] : '';

	$row['specifics'] = [
		'EAN' => $row['K'],
		'Zusammensetzung' => get_zusammen($row['I']),
		// 'Analytische Bestandteile und Gehalte' => ($row['I']), //что делать с запятыми?
		'Kurzbeschreibung' => $row['H'], // вставить запятые
		'Zweck' => $row['A'], // тут название категории
		'Formulierung' => '', // подумать как выделить
	]

	echo json_encode($row);
	return;
}


if (isset($_POST['rescan_excel'])) {
	$excel = readExcel('csv/eBayArtikel.xlsx');
	file_put_contents('csv/eBayArtikel.json', json_encode($excel));
	$excel_s2 = readExcel('csv/eBayArtikel.xlsx', 1);
	file_put_contents('csv/eBayArtikel_s2.json', json_encode($excel_s2));
}


if (isset($_POST['action']) && $_POST['action'] === 'add_item') {
	$item = [
	    'Title' => 'Название',
	    'Quantity' => 3,
	    'ConditionID' => 1000,
	    'Description' => 'Дескрипшн',
	    'price' => '9.99',
	    'PictureURL' => [],
	    'BestOfferEnabled' => 'true',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => [],
	    'CategoryID' => '139973',
	    'StoreCategory1' => '10866044010',
	];
	Cdvet::addItem($item);
}


$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
	// $cd_arr = readExcel('csv/eBayArtikel.xlsx');

$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);
// sa($categories);
?>
<br>
<form method="POST"><button type="submit" name="rescan_excel">rescan xcel</button></form>
<br>

<table class='ppp-table-collapse al-table js-tabledeligator' style="width: 100%;font-size: 13px;">
	<tr>
		<th>row</th>
		<th>itemID</th>
		<th>mainID</th>
		<th title="name">Title</th>
		<th title="additionaltext">additional</th>
		<th>pic</th>
		<th>Add</th>
		<th>Price</th>
		<th title="keywords">keywords</th>
		<th>Options</th>
	</tr>
<?php

$sorted_cats = cd_ebay_cat_sort($categories);

sa($sorted_cats);

foreach ($cd_arr as $k => $cd_item):

	$cat = get_ebay_cat($cd_item, $sorted_cats);
	if($k === 1) continue;
	echo "<tr>";
	echo '<td>',$k,'</td>';
	echo '<td>',$cd_item['A'],'</td>';
	echo '<td>',$cd_item['B'],'</td>';
	echo '<td><div class="">',$cd_item['C'],'</div></td>';
	echo '<td>',$cd_item['D'],'</td>';
	echo '<td>',(isset($matches[$k])?'yes':''),'</td>';
	echo '<td><button class="js-cdadd" lang="',$k,'">add</button></td>';
	echo '<td>',$cd_item['G'],'</td>';
	echo '<td><div class="clip" style="width:400px;">',$cd_item['J'],'</div></td>';
	echo '<td>',$cd_item['N'],'</td>';
	echo "</tr>";

endforeach;

?>
</table>



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
        <h4 class="modal-title col555">cdvet</h4>

      </div>
      <div class="modal-body" id="modal_body">

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/add modal===================-->


<script src="js/react.min.js"></script>
<script src="js/react-dom.min.js"></script>
<script src="js/babel-core.min.js"></script>

<script type="text/babel" src="js/add-cdvet.jsx?t=<?= filemtime('js/add-cdvet.jsx'); ?>"></script>