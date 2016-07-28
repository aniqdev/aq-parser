<div class="ppp-block ppp-right">
<ul class="ppp-parses">
<?php
	$scans = arrayDB('SELECT DISTINCT scan FROM park_results ORDER BY scan DESC');
		// echo "<br><pre>\n";
		// print_r($scans);
		// echo '</pre>';
foreach ($scans as $key => $value) {
	$d = date('d.m.Y H:i', $value['scan']*60);
	echo '<li><a href="/index.php?action=park-table&scan=',$value['scan'],'" class="ppp-link">Парс ',count($scans)-$key,' От ',$d,' </a></li>';
}
if (isset($_GET['scan'])) 	$scan = mysql_escape_string(trim(strip_tags($_GET['scan'])));
elseif (isset($scans[0]))	$scan = $scans[0]['scan'];
else						$scan = 0;

function getClass($firm){
	return 'cl'.substr(md5($firm),0,8);
}

?>
</ul>
</div>
<div id="platitable" class="platitable">
<div class="ppp-block clearfix">
<div class="col-xs-10">
<?php
	$art = 0;
	$res = arrayDB("SELECT * FROM park_results WHERE scan='$scan' AND art='$art'");
	 foreach (json_decode($res[0]['results'], true) as $key => $value) {
	 	echo '<div class="inp-wrap"><input type="color" class="park-col-inpt park-col-inp',$key,'" 
	 	id="',getClass($value['name']),
	 	'" value="#ff8080"><b> ',$value['name'],'</b></div>',"\r\n";
	 }
?>
</div>
<div class="col-xs-2 park-btn-panel">
	<button id="park-pars">Спарсить</button>
	<fieldset>
	<legend>Гамма</legend>
		<label><input type="radio" name="colors" value="c1">Ярко</label><br>
		<label><input type="radio" name="colors" value="c2" checked="checked">Тускло</label>
	</fieldset>
</div>
</div>
<div class="ppp-block"><h3>Außenparkplatz</h3></div>
<div class="ppp-block">
<?php
	$art = 1;
	$res = arrayDB("SELECT * FROM park_results WHERE scan='$scan' AND art='$art'");
?>
<table class="ppp-table">
	<thead>
		<tr>
			<th class="tage">Tage</th>
			<?php
				foreach (json_decode($res[0]['results'], true) as $k => $value){
					echo '<th>Цена ',$k+1,'</th>';
				}
			?>
		</tr>
	</thead>
	<tbody class="list">
<?php

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";


	$n = 1;
   foreach ($res as $key => $value) {

$dec = json_decode($value['results'], true);
echo   '<tr>
			<td class="tage">',$n++,'</td>';
			foreach ($dec as $val) {
				echo '<td class="',getClass($val['name']),'" title="',$val['name'],'">',$val['price'],'</td>';
			}
echo   '</tr>';

	// echo "<br><pre>\n";
	// print_r($dec);
	// echo "</pre>";
   }
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
<!-- ================================================================ -->
<!-- ======================== table 2 =============================== -->
<div class="ppp-block"><h3>Parkhaus</h3></div>
<div class="ppp-block">
<?php
	$art = 3;
	$res = arrayDB("SELECT * FROM park_results WHERE scan='$scan' AND art='$art'");
?>
<table class="ppp-table">
	<thead>
		<tr>
			<th class="tage">Tage</th>
			<?php
				foreach (json_decode($res[0]['results'], true) as $k => $value){
					echo '<th>Цена ',$k+1,'</th>';
				}
			?>
		</tr>
	</thead>
	<tbody class="list">
<?php

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";


	$n = 1;
   foreach ($res as $key => $value) {

$dec = json_decode($value['results'], true);
echo   '<tr>
			<td class="tage">',$n++,'</td>';
			foreach ($dec as $val) {
				echo '<td class="',getClass($val['name']),'" title="',$val['name'],'">',$val['price'],'</td>';
			}
echo   '</tr>';

	// echo "<br><pre>\n";
	// print_r($dec);
	// echo "</pre>";
   }
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
<!-- ================================================================ -->
<!-- ======================== table 3 =============================== -->
<div class="ppp-block"><h3>Hallenparkplatz</h3></div>
<div class="ppp-block">
<?php
	$art = 4;
	$res = arrayDB("SELECT * FROM park_results WHERE scan='$scan' AND art='$art'");
?>
<table class="ppp-table">
	<thead>
		<tr>
			<th class="tage">Tage</th>
			<?php
				foreach (json_decode($res[0]['results'], true) as $k => $value){
					echo '<th>Цена ',$k+1,'</th>';
				}
			?>
		</tr>
	</thead>
	<tbody class="list">
<?php

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";


	$n = 1;
   foreach ($res as $key => $value) {

$dec = json_decode($value['results'], true);
echo   '<tr>
			<td class="tage">',$n++,'</td>';
			foreach ($dec as $val) {
				echo '<td class="',getClass($val['name']),'" title="',$val['name'],'">',$val['price'],'</td>';
			}
echo   '</tr>';

	// echo "<br><pre>\n";
	// print_r($dec);
	// echo "</pre>";
   }
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
<!-- ================================================================ -->
<!-- ======================== table 4 =============================== -->
<div class="ppp-block"><h3>Valet-Parking</h3></div>
<div class="ppp-block">
<?php
	$art = 6;
	$res = arrayDB("SELECT * FROM park_results WHERE scan='$scan' AND art='$art'");
?>
<table class="ppp-table">
	<thead>
		<tr>
			<th class="tage">Tage</th>
			<?php
				foreach (json_decode($res[0]['results'], true) as $k => $value){
					echo '<th>Цена ',$k+1,'</th>';
				}
			?>
		</tr>
	</thead>
	<tbody class="list">
<?php

	// echo "<pre>";
	// print_r($res);
	// echo "</pre>";


	$n = 1;
   foreach ($res as $key => $value) {

$dec = json_decode($value['results'], true);
echo   '<tr>
			<td class="tage">',$n++,'</td>';
			foreach ($dec as $val) {
				echo '<td class="',getClass($val['name']),'" title="',$val['name'],'">',$val['price'],'</td>';
			}
echo   '</tr>';

	// echo "<br><pre>\n";
	// print_r($dec);
	// echo "</pre>";
   }
		// echo "<br><pre>\n";
		// print_r($res);
		// echo '</pre>';
?>
	</tbody>
</table>
</div> <!-- ppp-block -->
<!-- ================================================================ -->
</div> <!-- platitable -->

<script src="js/park.js"></script>
<script>
	// var options = {
	//   valueNames: [ 'row1' ],
	//   page: 50
	// };

	// var userList = new List('platitable', options);
</script>
<style>
th input{
	width: 100%;
	margin: -1px -5px;
}
.ppp-table td{
	color: #000;
}
.ppp-table .tage{
	color: #eaeaea;
}
.inp-wrap {
    width: 45%;
    float: left;
}
.park-btn-panel button{
	width: 100%;
	margin-bottom: 3px;
}
#park-pars.parkactive:after{
	content:'';
	background: url(http://filmaclips.net/img/loading.gif) center no-repeat;
	width: 50px;
	height: 10px;
	display: block;
    margin: 3px -3px;
    float: right;
}
fieldset {
    margin: 0;
}
.ppp-block>h3{
	margin: 0;
}
</style>