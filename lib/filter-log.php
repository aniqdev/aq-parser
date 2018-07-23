<?php

if (isset($_POST['action']) && $_POST['action'] === 'download_excel') {

	$rows = (int)$_POST['rows'];
	if($rows < 1) return;
	$res = arrayDB("SELECT * FROM filter_log ORDER BY id DESC LIMIT $rows");
	// sa($res);
	$inputArr = [];
	// костыль
	$letters = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD'];
	foreach ($res as $k => $row) {
		$i = 0;
		if ($k === 0) {
			foreach ($row as $key => $value) {
				$inputArr[1][$letters[$i++]] = $key;
			}
		}
		$i = 0;
		foreach ($row as $value) {
			$inputArr[$k+2][$letters[$i++]] = $value;
		}
		
	}
	saveExcel('Files/filter-log-proto.xlsx', 'Files/filter-log.xlsx', $inputArr);
	return;
}


$res = arrayDB("SELECT * FROM filter_log ORDER BY id DESC LIMIT 200");

?>
<style>
.filter-table {
    border-collapse: collapse;
    font-size: 10px;
    width: 100%;
}
.filter-table td {
    border: 1px solid #999;
    padding: 1px 2px;
}
.user-col{
	color: #fff;
    text-shadow: 1px 1px 3px #000;
}
.form-control.fl-rows{
	width: 80px;
}
</style>
<div class="container-fluid">
	<div id="chart_here"></div><br>
	<form class="form-inline">
	  <div class="form-group">
	    <input type="number" class="form-control fl-rows" id="rows_input" placeholder="rows" value="200">
	  </div>
	  <button id="download_excel_btn" type="button" class="btn btn-primary">Generate Excel</button>
	  <a href="Files/filter-log.xlsx">download</a>
	</form>
	<table class="filter-table op-orders-table">
		<tr>
			<th>time</th>
			<th>user</th>
			<th>enter</th>
			<th>genres</th>
			<th>tags</th>
			<th>specs</th>
			<th>app_dlc</th>
			<th>langs</th>
			<th>os</th>
			<th>year</th>
			<th>year_from</th>
			<th>year_to</th>
			<th>reviews_from</th>
			<th>reviews_to</th>
			<th>rating_from</th>
			<th>rating_to</th>
			<th>our_price_from</th>
			<th>our_price_to</th>
			<th>steam_price_from</th>
			<th>steam_price_to</th>
			<th>advantage_from</th>
			<th>advantage_to</th>
			<th>search</th>
			<th>sorting</th>
			<th>ebay_id</th>
			<th>ebay_title</th>
		</tr>
<?php
foreach ($res as $val) {
	echo '<tr>';
	echo '<td>',$val['created_at'],'</td>';
	echo '<td class="user-col">',$val['user'],'</td>';
	echo '<td>',$val['enter'],'</td>';
	echo '<td>',$val['genres'],'</td>';
	echo '<td>',$val['tags'],'</td>';
	echo '<td>',$val['specs'],'</td>';
	echo '<td>',$val['app_dlc'],'</td>';
	echo '<td>',$val['langs'],'</td>';
	echo '<td>',$val['os'],'</td>';
	echo '<td>',$val['year'],'</td>';
	echo '<td>',$val['year_from'],'</td>';
	echo '<td>',$val['year_to'],'</td>';
	echo '<td>',$val['reviews_from'],'</td>';
	echo '<td>',$val['reviews_to'],'</td>';
	echo '<td>',$val['rating_from'],'</td>';
	echo '<td>',$val['rating_to'],'</td>';
	echo '<td>',$val['our_price_from'],'</td>';
	echo '<td>',$val['our_price_to'],'</td>';
	echo '<td>',$val['steam_price_from'],'</td>';
	echo '<td>',$val['steam_price_to'],'</td>';
	echo '<td>',$val['advantage_from'],'</td>';
	echo '<td>',$val['advantage_to'],'</td>';
	echo '<td>',$val['search'],'</td>';
	echo '<td>',$val['sorting'],'</td>';
	echo '<td>',$val['ebay_id'],'</td>';
	echo '<td>',$val['ebay_title'],'</td>';
	echo '<tr>';
}
?>
	</table>
</div>

<script>
function hashCode(str) { // java String#hashCode
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
       hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
} 

function intToRGB(i){
    var c = (i & 0x00FFFFFF).toString(16).toUpperCase();
    return "00000".substring(0, 6 - c.length) + c;
}



$('.user-col').map(function(i,el) {
	var text = el.innerHTML;
	var color = intToRGB(hashCode(text));
	el.style = 'background:#'+color;
	// console.log(el);
});

// ======================================================
function downloadURI(uri, name) {
  var link = document.createElement("a");
  link.download = name;
  link.href = uri;
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  delete link;
}
$('#download_excel_btn').on('click', function() {
	$.post(location.href.replace('index','ajax') ,
		{action: 'download_excel', rows:$('#rows_input').val()},
		function() {
			downloadURI('Files/filter-log.xlsx', 'filter-log.xlsx');
		});	
});

</script>
<!--Load the AJAX API-->
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
<script src="//www.google.com/jsapi"></script>
<script type="text/javascript">
	// google.charts.load('current', {'packages':['line']});
	google.load("visualization", "1", {packages:["corechart"]});
	// google.charts.setOnLoadCallback(function(){
	google.setOnLoadCallback(function(){

		$.post('ajax.php?action=ajax-ebay-filter',
			{action:'get_chart_data'},
			function(data) {fl_drawChart(data);},'json');

	    // $('#show-chrt').on('click', function(e) {
	    // 	$('.chart-wrapper').toggleClass('active');
	    // })
	});

  function fl_drawChart(danye) {

    var data = google.visualization.arrayToDataTable(danye);
    // console.log(data);
    var options = {
      title: 'Filter analitics',
      curveType: 'function',
      legend: { position: 'bottom' },
	  pointSize: 5
    };
    // var chart = new google.charts.Line(document.getElementById('chart_here'));
	var chart = new google.visualization.AreaChart(document.getElementById('chart_here'));
    chart.draw(data, options);
  }
</script>