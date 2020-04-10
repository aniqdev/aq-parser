<?php

// sa($_SERVER);

if (isset($_GET['moda_desc']) && (int)$_GET['moda_desc']) {
	$moda_id = (int)$_GET['moda_desc'];
	echo get_moda_meta($moda_id, $meta_key = 'Description');
	return;
	die();
}


$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;

$moda_list = arrayDB("SELECT * FROM moda_list LIMIT $offset,1");

if($moda_list) $moda_list = $moda_list[0];

$moda_id = $moda_list['id'];

$moda_meta = get_moda_meta($moda_id);

$PictureURL = explode(',', $moda_meta['PictureURL']);

unset($moda_meta['PictureURL']);


?>
<style>
	table{ border-collapse: collapse; width: 100% }
	td,th{ border: 1px solid #777; padding: 0 5px; }
	td{	white-space: pre; }
	.pics .pic{
		max-width: 100%;
		max-height: 150px;
		margin: auto;
	    display: block;
	}
</style>

<div class="container">
	<hr>
	<?php aqs_pagination($table_name = 'moda_list', $count = 100); ?>
	<hr>
	<div class="row pics">
		<?php foreach ($PictureURL as $hash) { ?>
			<div class="col-sm-3">
				<img class="pic" src="<?= get_ebay_pic_url_by_hash($hash); ?>" alt=""><br>
			</div>
		<?php } ?>
	</div>
	<hr>
<?php

// sa($moda_list);
?>
<table>
	<tbody>
		<tr><th>name</th><th>value</th></tr>
<?php
// sa($moda_meta);
foreach ($moda_list as $key => $value) {
	echo "<tr><td>$key</td><td>$value</td></tr>";
}
// sa($moda_meta);
foreach ($moda_meta as $meta_key => $meta_value) {
	if ($meta_key === 'ItemSpecifics') {
		$ItemSpecifics = json_decode($meta_value, 1);
		continue;
	}
	if ($meta_key === 'Variations') {
		$Variations = json_decode($meta_value, 1);
		continue;
	}
	if ($meta_key === 'VariationsPics') {
		$VariationsPics = json_decode($meta_value, 1);
		continue;
	}
	if ($meta_key === 'Description') {
		$Description = $meta_value;
		continue;
	}
	echo "<tr><td>$meta_key</td><td>$meta_value</td></tr>";
}
?>
	</tbody>
</table>
<?php 
// sa($Variations);
?>
<h4>ItemSpecifics</h4>
<table>
	<tbody>
		<tr><th>name</th><th>value</th></tr>
<?php
foreach ($ItemSpecifics as $key => $meta) {
	echo "<tr><td>$meta[Name]</td><td>{$meta['Value'][0]}</td></tr>";
}
?>
	</tbody>
</table>

<h4>Variations</h4>
<table>
	<tbody>
		<tr><th>name</th><th>value</th></tr>
<?php
if($Variations) foreach ($Variations as $key => $Variation) {
	echo "<tr><td>$Variation[Name]</td><td>";
	foreach ($Variation['Value'] as $var) {
		echo $var.'<br>';
	}
	echo "</td></tr>";
}
?>
	</tbody>
</table>
<?php
if($VariationsPics) foreach ($VariationsPics as $meta) {
	echo '<hr><h4>'.$meta['VariationSpecificName'].'</h4>';
	foreach ($meta['VariationSpecificPictureSet'] as $pic) {
		echo '<div class="row">';
		echo '<hr><h6>'.$pic['VariationSpecificValue'].'</h6>';
		foreach ($pic['PictureURL'] as $PictureURL) {
			echo '<div class="col-sm-2">';
			echo '<img src="'.$PictureURL.'" style="max-width:100px;max-height:100px;margin:auto;"><br><br>';
			echo '</div>';
		}
		echo '</div>';
	}
}
?>
<?php
// sa($VariationsPics);
?>
<style>
iframe{
	width: 100%;
	min-height: 600px;
	background: white;
}
</style>
<h4>Description</h4>
<iframe src="<?= str_replace('index.p', 'a.p', $_SERVER['REQUEST_URI']).'&moda_desc='.$moda_id ?>" frameborder="0"></iframe>

</div>