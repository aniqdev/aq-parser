<?php

$all_items = arrayDB("SELECT tt.*, ebay_games.picture_hash FROM (select title,price,ebay_id,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> ''
order by count desc");

echo "<input type='hidden' id='json_data' value=\"",htmlentities(json_encode($all_items)),"\">";


$one_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 7 DAY
order by count desc
limit 10");

$two_week_res = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,shipped_time,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> '' AND shipped_time > NOW() - INTERVAL 14 DAY
order by count desc
limit 10");

$one_month_res = one_month_top();

$top_items = [];

foreach ($one_week_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 1) break;
}

foreach ($two_week_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 3) break;
}

foreach ($one_month_res as $key => $value) {
	if (!in_multi_array($value['ebay_id'], $top_items)) $top_items[] = $value;
	if (count($top_items) > 5) break;
}

//sa($one_week_res);
// echo "<input type='hidden' id='top_data' value=\"",htmlentities(json_encode($all_items)),"\">";

$top12 = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash FROM (select title,price,ebay_id,count(*) as count from ebay_order_items group by ebay_id) tt
JOIN ebay_games
ON tt.ebay_id = ebay_games.item_id
WHERE picture_hash <> ''
order by count desc
limit 12");
?>

<div class="container">
		<h3>Sales report</h3>
		<h4>One week:</h4>
		<table class="table">
			<tr>
				<th>#</th>
				<th>pic</th>
				<th>item id</th>
				<th>title</th>
				<th>price</th>
				<th>count</th>
			</tr>
		<?php
foreach ($one_week_res as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td><img class="img50" src="http://i.ebayimg.com/images/g/',$value['picture_hash'],'/s-l50.jpg"></td>';
	echo '<td>',$value['ebay_id'],'</td>';
	echo '<td><a href="http://www.ebay.de/itm/',$value['ebay_id'],'" target="_blank">',$value['title'],'</a></td>';
	echo '<td>',$value['price'],'</td>';
	echo '<td>',$value['count'],'</td>';
	echo '</tr>';
}
		?>
		</table>


		<h4>Two weeks:</h4>
		<table class="table">
			<tr>
				<th>#</th>
				<th>pic</th>
				<th>item id</th>
				<th>title</th>
				<th>price</th>
				<th>count</th>
			</tr>
		<?php
foreach ($two_week_res as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td><img class="img50" src="http://i.ebayimg.com/images/g/',$value['picture_hash'],'/s-l50.jpg"></td>';
	echo '<td>',$value['ebay_id'],'</td>';
	echo '<td><a href="http://www.ebay.de/itm/',$value['ebay_id'],'" target="_blank">',$value['title'],'</a></td>';
	echo '<td>',$value['price'],'</td>';
	echo '<td>',$value['count'],'</td>';
	echo '</tr>';
}
		?>
		</table>


		<h4>One month:</h4>
		<table class="table">
			<tr>
				<th>#</th>
				<th>pic</th>
				<th>item id</th>
				<th>title</th>
				<th>price</th>
				<th>count</th>
			</tr>
		<?php
foreach ($one_month_res as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td><img class="img50" src="http://i.ebayimg.com/images/g/',$value['picture_hash'],'/s-l50.jpg"></td>';
	echo '<td>',$value['ebay_id'],'</td>';
	echo '<td><a href="http://www.ebay.de/itm/',$value['ebay_id'],'" target="_blank">',$value['title'],'</a></td>';
	echo '<td>',$value['price'],'</td>';
	echo '<td>',$value['count'],'</td>';
	echo '</tr>';
}
		?>
		</table>


		<h4>Total Top:</h4>
		<table class="table">
			<tr>
				<th>#</th>
				<th>pic</th>
				<th>item id</th>
				<th>title</th>
				<th>price</th>
				<th>count</th>
			</tr>
		<?php
foreach ($top12 as $key => $value) {
	echo '<tr>';
	echo '<td>',($key+1),'</td>';
	echo '<td><img class="img50" src="http://i.ebayimg.com/images/g/',$value['picture_hash'],'/s-l50.jpg"></td>';
	echo '<td>',$value['ebay_id'],'</td>';
	echo '<td><a href="http://www.ebay.de/itm/',$value['ebay_id'],'" target="_blank">',$value['title'],'</a></td>';
	echo '<td>',$value['price'],'</td>';
	echo '<td>',$value['count'],'</td>';
	echo '</tr>';
}
		?>
		</table>
</div>

<div id="main-component"></div>



<style>
img{
	width: 78px;
	height: 78px;
	margin-bottom: 3px;
}
.img50{
	height: 35px;
    width: 35px;
    margin: -7px;
}
.form-inline .form-group,
.btn{
	margin-right: 5px;
	margin-bottom: 5px;
}
.pos-rel{
	position: relative;
}
.auto-res{
    background: #c6c6c6;
    position: absolute;
    left: 102%;
    display: block;
    width: 600px;
    top: 0;
    color: #555;
    z-index: 1;
}
.auto-res>div{
    border-bottom: 1px solid #b8b8b8;
    border-top: 1px solid #e2e2e2;
    padding: 3px 15px;
    cursor: pointer;
}
.auto-res>div:hover{
    background: #6e6e6e;
    color: #eaeaea;
}
</style>

<script src="https://unpkg.com/react@15/dist/react.min.js"></script>
<script src="https://unpkg.com/react-dom@15/dist/react-dom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-core/5.8.23/browser.min.js"></script>

<script type="text/babel" src="js/ebay-panels.babel"></script>
