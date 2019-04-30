<?php

ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
// $pic_matches = json_decode(file_get_contents('csv/jpeg_matches.json'), true);


	
$cdvet_feed = json_decode(file_get_contents('csv/cdvet_feed.json'), true);
// var_dump(isset($matches['2'])?'yes':'');
	// $is_pic = isset($matches[2])?'yes':'';
	// var_dump($is_pic);

if (isset($_POST['action']) && $_POST['action'] === 'get_xcel_info') {
	header('Content-Type: application/json');
	$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);
	$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

	$row = $cd_arr[$_POST['row']];
	$img_arr = explode('|', $cdvet_feed[$row['A']][16]);

	$img_arr = array_filter($img_arr, function ($url){
		return filter_var($url, FILTER_VALIDATE_URL) and (stripos($url, '.jpg') || stripos($url, '.png'));
	});

	$pic_url1 = '';$pic_url2 = '';$pic_url3 = '';
	if (isset($img_arr[0])) $pic_url1 = $img_arr[0];
	if (isset($img_arr[1])) $pic_url2 = $img_arr[1];
	if (isset($img_arr[2])) $pic_url3 = $img_arr[2];
	

	if (substr_count($row['I'], '<div>') === 1) {

		$row['I'] = preg_replace('/http[^ <\r\n]+\//', '', $row['I']);

		$row['I'] = preg_replace('/ style="margin:\s?(\d{1,2}pt\s?){4};\s?line-height:[^;]+?;\s?"/', '', $row['I']);
		$row['I'] = preg_replace('/ style="font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?color:\s?#000000;\s?"/', '', $row['I']);
		$row['I'] = preg_replace('/font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?color:\s?#000000;\s?/', '', $row['I']);
		$row['I'] = preg_replace('/font-family:[^;]+?;\s?font-size:\s?\d\.?\dpt;\s?/', '', $row['I']);
	
		$row['I'] = preg_replace('/<span>(.+?)<\/span>/', '${1}', $row['I']);
		$row['I'] = str_replace(['<div>','</div>'], '', $row['I']);

		$row['I'] = preg_replace('/<span>(.+?)<\/span>/', '${1}', $row['I']);		
		$row['I'] = str_replace(['<div>','</div>'], '', $row['I']);
		if(stripos($row['I'], 'Zusammensetzung') !== false){
			preg_match('/(.*)(<p[^\/]+Zusammensetzung.*)/s', $row['I'], $zus_matches);
			
		}elseif(stripos($row['I'], 'Inhaltstoffe') !== false){
			preg_match('/(.*)(<p[^\/]+Inhaltstoffe.*)/s', $row['I'], $zus_matches);
		}
	}else{
		$row['I'] = strip_tags($row['I'], '<u><p><a><div><br><br/><b><strong>');
		preg_match('/(.*)(<div[^\/]+Zusammensetzung.*)/s', $row['I'], $zus_matches);
	}
	$desc_top = isset($zus_matches[1]) ? $zus_matches[1] : $row['I'];
	$desc_bot = isset($zus_matches[2]) ? $zus_matches[2] : '';

    $sorted_cats = Cdvet::cd_ebay_cat_sort($categories);
    $cat_ids = Cdvet::get_ebay_cat($row['L'], $sorted_cats);

	$ustr = str_replace('.', ',', $cdvet_feed[$row['A']][8]) . $cdvet_feed[$row['A']][7];
	preg_match('/(\d*\.?\d+)\s?([^\s]+)/', str_replace(',', '.', $ustr), $unit_mathes);
	$units = Cdvet::get_units($unit_mathes);

	$row['I'] = html_entity_decode($row['I']);
	
	$specifics = [
		'EAN' => $row['K'],
		'Zusammensetzung' => insert_comas(Cdvet::get_zusammen($row['I'])),
		'Analytische Bestandteile und Gehalte' => insert_comas(Cdvet::get_gehalte($row['I'])),
		'Kurzbeschreibung' => $row['H'] ? insert_comas($row['H']) : '', // вставить запятые
		'Zweck' => Cdvet::get_zweck($cat_ids), // тут название категории
		'Formulierung' => '', // подумать как выделить
		'geeignet für' => Cdvet::get_geeignet($cat_ids), // "предназначен для" (Кошки, Собаки)
		'Herstellungsland und -region' => 'Deutschland',
		'Marke' => 'cdVet',
		'Maßeinheit' => $units['UnitType'], // UnitType
		'Anzahl der Einheiten' => $units['UnitQuantity'], // UnitQuantity
	];

	$title = Cdvet::get_title($row, $cat_ids, $cdvet_feed);
	$desc_title = 'cdVet® ' . str_ireplace('cdvet', '', $row['C']);

	echo json_encode([
			'shop_id' => $row['A'],
			'title' => $title,
			'title_length' => strlen($title),
			'desc_title' => $desc_title,
			'price' => ($row['G'] + 3),
			'show_picture' => $pic_url1,
			'main_pic1' => $pic_url1,
			'main_pic2' => $pic_url2,
			'main_pic3' => $pic_url3,
			'desc_pics' => $img_arr,
			'chosen_desc_pics' => $img_arr,
			'specifics' => $specifics,
			'desc_top' => $desc_top,
			'desc_bot' => $desc_bot,
			'cat_ids' => $cat_ids,
			'chosen_cat_id' => $_POST['chosen_cat_id'],
			'chosen_cat' => $cat_ids[$_POST['chosen_cat_id']],
			'additionaltext' => $row['H'],
			'configuratorOptions' => $row['N'],
			'alertHtml' => '',
			'tax_percent' => str_replace('.00', '', $row['F']),
			'volume' => $cdvet_feed[$row['A']][8].$cdvet_feed[$row['A']][7],
	]);
	return;
}


if (isset($_POST['rescan_excel'])) {
	// $excel = readExcel('csv/eBayArtikel.xlsx');  // старый файл
	// $excel = readExcel('csv/eBayArtikel21-02-2018.xlsx', 1); // новый файл
	$excel = readExcel('csv/ebayartikel15-10-2018.xlsx', 0); // новый файл
	file_put_contents('csv/eBayArtikel.json', json_encode($excel));
	$categories = readExcel('csv/eBayArtikel.xlsx', 1); // сохранение категорий
	file_put_contents('csv/eBayArtikel_s2.json', json_encode($categories));
	$feed_new = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 0,'encoding' => 'windows-1250']);
	$feed_new = array_column($feed_new, null, 0);
	file_put_contents('csv/cdvet_feed.json', json_encode($feed_new));
}


if (isset($_POST['action']) && $_POST['action'] === 'add_item') {
	header('Content-Type: application/json');
	if(defined('DEV_MODE')){
		echo json_encode(['resp'=>['Ack'=>'Success','ItemID'=>'253222330523'],
						  'ERRORS' => $_ERRORS]);
		return;
	} 
	$_POST['item']['chosen_desc_pics'] = array_filter($_POST['item']['chosen_desc_pics']);
	$_POST['item']['chosen_desc_pics'] = array_values($_POST['item']['chosen_desc_pics']);
	if (count($_POST['item']['chosen_desc_pics']) < 1) {
		echo json_encode(['resp' => 'There are no chosen pictures!',
					  'text_resp' => '<pre>There are no chosen pictures!</pre>',
					  'ERRORS' => $_ERRORS]);
		return;
	}
	$_POST['item']['specifics'] = array_filter($_POST['item']['specifics']);
	$_POST['item']['specifics'] = array_map(function ($el){
		return html_entity_decode($el);
	}, $_POST['item']['specifics']);

	if(@$_POST['item']['specifics']['Kurzbeschreibung'])
		$_POST['item']['specifics']['Kurzbeschreibung'] = explode(',', $_POST['item']['specifics']['Kurzbeschreibung']);

	if(@$_POST['item']['specifics']['Zusammensetzung'])
		$_POST['item']['specifics']['Zusammensetzung'] = explode(',', $_POST['item']['specifics']['Zusammensetzung']);

	if(@$_POST['item']['specifics']['Analytische Bestandteile und Gehalte'])
		$_POST['item']['specifics']['Analytische Bestandteile und Gehalte'] = explode(',', $_POST['item']['specifics']['Analytische Bestandteile und Gehalte']);

	$_POST['item']['specifics']['Zweck'] = cut_text($_POST['item']['specifics']['Zweck'], 65);
	$_POST['item']['specifics']['geeignet für'] = explode(',', $_POST['item']['specifics']['geeignet für']);
	// sa($_POST['item']);
	$main_pics = [];
	if($_POST['item']['main_pic1']) $main_pics[] = $_POST['item']['main_pic1'];
	if($_POST['item']['main_pic2']) $main_pics[] = $_POST['item']['main_pic2'];
	if($_POST['item']['main_pic3']) $main_pics[] = $_POST['item']['main_pic3'];

	$item = [
	    'Title' => html_entity_decode($_POST['item']['title']),
	    'Quantity' => 1,
	    'ConditionID' => 1000,
	    'Description' => Cdvet::prepare_description($_POST['item']),
	    'price' => $_POST['item']['price'],
	    'PictureURL' => $main_pics,
	    'BestOfferEnabled' => 'false',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => $_POST['item']['specifics'],
	    'CategoryID' => $_POST['item']['chosen_cat'][0]['eBayKategorie'],
	    'StoreCategory1' => $_POST['item']['chosen_cat'][0]['eBayShopKAtegorieID'],
	    'StoreCategory2' => @$_POST['item']['chosen_cat'][1]['eBayShopKAtegorieID'],
	    'VATPercent' => $_POST['item']['tax_percent'],
	    'SKU' => $_POST['item']['shop_id'],
	];
	// sa($item);
	$res = Cdvet::addItem($item);
	unset($res['Fees']);
	unset($item['Description']);

	$data_json = _esc(json_encode($_POST['item']));
	$ebay_resp_json = _esc(json_encode($res));
	$errors_json = _esc(json_encode($_ERRORS));
	arrayDB("INSERT INTO cdvet_add_log (data_json,ebay_resp_json,errors_json) VALUES('$data_json','$ebay_resp_json','$errors_json')");

	echo json_encode(['resp' => $res,
					  'text_resp' => sa($res, true),
					  'ERRORS' => $_ERRORS]);
	if (isset($res['ItemID'])) {
		$title = _esc($item['Title']);
		$ebay_id = $res['ItemID'];
		$shop_id = $_POST['item']['shop_id'];
		$cat_id = $_POST['item']['chosen_cat_id'];
		arrayDB("INSERT INTO cdvet (title,ebay_id,shop_id,cat_id) 
			VALUES('$title','$ebay_id','$shop_id','$cat_id')");
	}
	return;

} // if add_item




$added_arr_sorted = Cdvet::sort_added();

$cd_arr = json_decode(file_get_contents('csv/eBayArtikel.json'), true);

$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);
// sa($categories);
?>
<div class="container">
<br>
<form method="POST"><button type="submit" name="rescan_excel">rescan excel and feed</button>
<button type="button" id="add_all">add all</button></form>
<br>

<table class='ppp-table-collapse al-table js-tabledeligator'>
	<tr>
		<th>#</th>
		<th>row</th>
		<th>itemID</th>
		<th>mainID</th>
		<th title="name">Title</th>
		<th title="additionaltext">volume</th>
		<th>cats</th>
		<th title="in choosen category">icc</th>
		<th>pics</th>
		<th>Add</th>
		<th>Price</th>
		<!-- <th>keywords</th> -->
		<!-- <th>Options</th> -->
	</tr>
<?php

$sorted_cats = Cdvet::cd_ebay_cat_sort($categories);

// sa($sorted_cats);
$x = 1;
foreach ($cd_arr as $k => $cd_item):
	$img_arr = (isset($cdvet_feed[$cd_item['A']]))?explode('|', $cdvet_feed[$cd_item['A']][16]):[];
	$cats = Cdvet::get_ebay_cat($cd_item['L'], $sorted_cats);
	$new_cats_matches = array_intersect(explode('|', $cd_item['L']),['300698','300707','304198']);
	 
	if($k === 1 || !$img_arr){
		continue;
	}
	echo "<tr>";
	echo '<td>',$x++,'</td>';
	echo '<td>',$k,'</td>';
	echo '<td>',$cd_item['A'],'</td>';
	echo '<td>',$cd_item['B'],'</td>';
	echo '<td><div class="">',$cd_item['C'],'</div></td>';
	echo '<td>',$cd_item['D'],'</td>';
	echo '<td>',count($cats),'</td>';
	echo '<td title="'.implode(',', $new_cats_matches).'">',
			count($new_cats_matches),
		 '</td>';
	echo '<td>',count($img_arr),'</td>';
	echo '<td>',Cdvet::add_buttons($k, $cats, $cd_item['A'], $added_arr_sorted),'</td>';
	echo '<td>',$cd_item['G'],'</td>';
	// echo '<td><div class="clip" style="width:400px;">',$cd_item['J'],'</div></td>';
	// echo '<td>',$cd_item['N'],'</td>';
	echo "</tr>";

endforeach;

?>
</table>
</div>


<!--===========report_screen===================-->
<div id="report_screen"></div>
<!--===========/report_screen===================-->

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
        <h4 class="modal-title col555">cdvet (<b id="rrow"></b>)</h4>

      </div>
      <div class="modal-body" id="modal_body">

      </div>
      <div class="modal-footer">

      </div>
    </div>
  </div>
</div>
<!--===========/add modal===================-->


<style>
	.m010100{
		margin: 0 10px 10px 0;
	}
	.title-label{
		display: block;
		width: 100%;
	}
	.picture-input:hover,
	.picture-input:focus{
		width: 200%;
	    z-index: 3;
	    position: relative;
	}
</style>


<script src="/js/react.min.js"></script>
<script src="/js/react-dom.min.js"></script>
<script src="/js/babel-core.min.js"></script>

<script type="text/babel" src="js/add-cdvet.jsx?t=<?= filemtime('js/add-cdvet.jsx'); ?>"></script>