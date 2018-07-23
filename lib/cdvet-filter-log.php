<?php


$res = arrayDB("SELECT * FROM cdvet_filter_log ORDER BY id DESC LIMIT 200");

?>
<style>
.filter-table {
    border-collapse: collapse;
    /* font-size: 10px; */
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
<div class="container">
	<h2>cdvet filter log</h2>
	<table class="filter-table op-orders-table">
		<tr>
			<th>user</th>
			<th>enter</th>
			<th>animal</th>
			<th>category</th>
			<th>search</th>
			<th>choice</th>
			<th>time</th>
		</tr>
<?php
foreach ($res as $val) {
	echo '<tr>';
	echo '<td class="user-col">',$val['user'],'</td>';
	echo '<td>',$val['enter'],'</td>';
	echo '<td>',$val['animal'],'</td>';
	echo '<td>',$val['category'],'</td>';
	echo '<td>',$val['search'],'</td>';
	echo '<td>',$val['ebay_title'],'</td>';
	echo '<td>',$val['created_at'],'</td>';
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

</script>