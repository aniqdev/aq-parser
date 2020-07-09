<style>
.moda-cat-lists ul{
	padding-left: 50px;
}
.moda-cat-lists ul ul{
	 border-left: 1px dashed #888;
}   
</style>
<div class="container moda-cat-lists">
	<h2 class="text-center">Hund categories</h2><hr>
	<div class="row">
		<div class="col-sm-6">
<?php
// CategoryID,CategoryLevel,CategoryName,CategoryName_DE,CategoryParentID
$CategoryParentID = '20742';
// $CategoryParentID = '20749';
// $CategoryParentID = '1281'; // Pet Supplies
draw_cats_recursion($CategoryParentID);
?>
		</div>
		<div class="col-sm-6">
<?php
draw_cats_recursion($CategoryParentID, 'CategoryName_DE');
?>
		</div>
	</div>
</div>
<?php
function draw_cats_recursion($CategoryParentID, $cat_name_field = 'CategoryName')
{
	$res = arrayDB("SELECT * from moda_cats where CategoryParentID= '$CategoryParentID'");
	if ($res) {
		echo "<ul>";
		foreach ($res as $val) {
			if($val['CategoryID'] === '20749') echo "<li><mark title='".$val['CategoryID']."'>".$val[$cat_name_field].'</mark>';
			else echo "<li><span title='".$val['CategoryID']."'>".$val[$cat_name_field];
			if($val['CategoryID'] != $val['CategoryParentID']) draw_cats_recursion($val['CategoryID']);
			echo "</li>";
		}
		echo "</ul>";
	}
}
