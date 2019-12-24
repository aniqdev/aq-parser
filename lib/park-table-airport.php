<div class="ppp-block ppp-right">
<ul class="ppp-parses">
<?php

if (isset($_GET['limit']) && $_GET['limit']>1 && $_GET['limit']<=1000){
	$lim = mysql_escape_string(trim(strip_tags((int)$_GET['limit'])));
	setcookie("park_airport_limit", $lim, time()+(60*60*24*365));
}elseif(isset($_COOKIE["park_airport_limit"])){
	$lim = $_COOKIE["park_airport_limit"];
}else $lim = 50;

$res = arrayDB("SELECT results,scan FROM park_airport ORDER BY id DESC LIMIT $lim");

?>
</ul>
</div>
<div id="platitable" class="platitable">
<div class="ppp-block clearfix">
<div class="col-xs-10">
	<input value="<?php echo $lim;?>" id="range" type="range" min="10" max="1000" step="10">
	<span class="range-go"></span>
</div>
<div class="col-xs-2 park-btn-panel">
	<button id="park-pars-airport">Спарсить</button>
</div>
</div>
<div class="ppp-block">

<?php
	$res = array_reverse($res);
	foreach ($res as $key => $value) {
		// echo "<pre>";
		// echo date('d.m.Y',$value['scan']);
		// echo "</pre>";
		$r = json_decode($value['results'], true);
		echo '<i class="scans" data-scan="',date('d.m.Y H:i',$value['scan']),'"></i>',"\r\n";
		echo '<i class="diffs" data-diff="[',$r[0]['diff'],',',$r[1]['diff'],',',$r[2]['diff'],',',$r[3]['diff'],']"></i>',"\r\n";
	}

?>
	<div id="chart_airport"></div>
</div> <!-- ppp-block -->

<div class="ppp-block">
	<div id="chart_airport2"></div>
</div> <!-- ppp-block -->

<div class="ppp-block">
	<div id="chart_airport3"></div>
</div> <!-- ppp-block -->

<div class="ppp-block">
	<div id="chart_airport4"></div>
</div> <!-- ppp-block -->

<div class="ppp-block">
	<div id="chart_airport5"></div>
</div> <!-- ppp-block -->
</div> <!-- platitable -->

<style>
#park-pars-airport.parkactive:after{
	content:'';
	background: url(http://filmaclips.net/img/loading.gif) center no-repeat;
	width: 50px;
	height: 10px;
	display: block;
	margin: 3px -3px;
	float: right;
}
#chart_airport{
	width: 100%;
	height: 200px;
}
.ppp-block{
	overflow: initial;
}
input#range {
	width: 90%;
}
.range-go a{
    padding: 2px 16px;
    align-items: flex-start;
    text-align: center;
    cursor: pointer;
    color: buttontext;
    border-image-source: initial;
    border-image-slice: initial;
    border-image-width: initial;
    border-image-outset: initial;
    border-image-repeat: initial;
    background-color: buttonface;
    box-sizing: border-box;
    border: 2px outset buttonface;
    text-rendering: auto;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    margin: 0em 0em 0em 0em;
    font: 13.3333px Arial;
    -o-appearance: button;
    -moz-appearance: button;
    -webkit-appearance: button;
        font-weight: bold;
}
.range-go a:hover{
	text-decoration: none;
}
</style>


<script src="//www.google.com/jsapi"></script>
<script src="js/park.js"></script>
<script>

$('#range').trigger('change');

// ============================ chart start ==================

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(qert);
function drawChartAirport(dataArray, elementID) {

		var data = google.visualization.arrayToDataTable(dataArray);
		var options = {
			title: 'Company Performance',
			hAxis: {title: 'Date',  titleTextStyle: {color: '#333'}},
			vAxis: {minValue: 0},
			pointSize: 5
		};
		var chart = new google.visualization.AreaChart(document.getElementById(elementID));
		chart.draw(data, options);
}

park.diffArr = [['Parking', 'P1', 'P2', 'P3', 'PN']];
park.diffP1 = [['Parking', 'P1']];
park.diffP2 = [['Parking', 'P2']];
park.diffP3 = [['Parking', 'P3']];
park.diffPN = [['Parking', 'PN']];
function qert () {
	$('.diffs').each(function(i){
	park.diffArr[i+1] = $(this).data('diff');
	park.diffP1[i+1] = [$(this).data('diff')[0]];
	park.diffP2[i+1] = [$(this).data('diff')[1]];
	park.diffP3[i+1] = [$(this).data('diff')[2]];
	park.diffPN[i+1] = [$(this).data('diff')[3]];
	});

	$('.scans').each(function(i){
	park.diffArr[i+1].unshift($(this).data('scan'));
	park.diffP1[i+1].unshift($(this).data('scan'));
	park.diffP2[i+1].unshift($(this).data('scan'));
	park.diffP3[i+1].unshift($(this).data('scan'));
	park.diffPN[i+1].unshift($(this).data('scan'));
	});
	console.dir(park.diffArr);
	console.dir(park.diffP1);
	drawChartAirport(park.diffArr, 'chart_airport');
	drawChartAirport(park.diffP1, 'chart_airport2');
	drawChartAirport(park.diffP2, 'chart_airport3');
	drawChartAirport(park.diffP3, 'chart_airport4');
	drawChartAirport(park.diffPN, 'chart_airport5');
}

// ============================ chart end ==================
</script>