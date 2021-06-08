<div class="container"><br><br>
<?php 


// sa($_POST);
$report = '';
if ($_POST && isset($_POST['gift_keys_add_key'])) {
	if ($_POST['key']) $report = Gift_keys::save_key();
	if ($_FILES && $_FILES['file']['size']) $report = Gift_keys::save_keys();
}

echo @$report;

?>
	<div class="row">
		<div class="col-sm-6">
			<form class="form-inline" id="add_key_form" method="POST" enctype="multipart/form-data" action="<?= $_SERVER['REQUEST_URI']; ?>" >
				<input type="hidden" name="gift_keys_add_key" value="gift_keys_add_key">
			    <div class="form-group">
			      <input type="text" class="form-control" placeholder="key" name="key">
			    </div>
			    <div class="form-group">
			      <input type="text" class="form-control" placeholder="game" name="game">
			    </div><br><br>
			    <div class="form-group">
			      <label for="exampleInputFile">File input</label>
			      <input type="file" class="form-control" id="fileInput" name="file">
			    </div><br><br>
			    <button type="submit" class="btn btn-primary">Add key</button>
			</form><br><br>
		</div>
		<div class="col-sm-6">
			<div class="text-right clearfix">		
				<a href="csv/add-keys-sample.txt" download>add-keys-sample.txt</a><br><br>
				<img src="images/add-keys-sample.jpg" class="thumbnail pull-right" alt="add-keys-sample.jpg">
			</div>
		</div>
	</div>

<?php

$table = 'gift_keys';
$limit = aqs_pagination($table);
$res = arrayDB("SELECT * FROM $table ORDER BY id DESC LIMIT $limit");
date_default_timezone_set('UTC');
?>
	<table class="table">
		<tr>
			<th>id</th>
			<th>key</th>
			<th>game</th>
			<th>publicity date</th>
		</tr>
		<?php foreach ($res as $rec): ?>
			<tr>
				<td><?= $rec['id']; ?></td>
				<td><?= $rec['key']; ?></td>
				<td><?= $rec['game']; ?></td>
				<td title="<?= $rec['public_date']; ?>"><?= date('Y-m-d H:i', (string)$rec['public_date']); ?></td>
			</tr>
		<?php endforeach; ?>
	</table>

</div>