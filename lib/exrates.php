<?php


if (isset($_GET['save']) && $_GET['save'] === 'exrates') {
	// sa($_GET);

	$wmr = $_GET['WMR'];
	$wmr = preg_replace('/[^0-9,\.]/', '', $wmr);
	$wmr = str_replace(',', '.', $wmr);
	$wmr = (float)rtrim($wmr, '.');

	$wmz = $_GET['WMZ'];
	$wmz = preg_replace('/[^0-9,\.]/', '', $wmz);
	$wmz = str_replace(',', '.', $wmz);
	$wmz = (float)rtrim($wmz, '.');

	set_setting('exrate_wmr', $wmr);
	set_setting('exrate_wmz', $wmz);
	// sa($wmr);
	// sa($wmz);
}

$wmr = get_setting('exrate_wmr');
$wmz = get_setting('exrate_wmz');

?>
<br><br><br><br><br><br>
<div class="container">
	<h4>Exrates</h4>
	<div class="col-sm-12 col-md-8 col-lg-6">
		<form class="form-horizontal">
			<input type="hidden" name="action" value="<?= @$_GET['action']; ?>">
		  <div class="form-group">
		    <label for="input3" class="col-sm-4 control-label">WMZ => WME</label>
		    <div class="col-sm-8">
		      <input name="WMZ" value="<?= $wmz; ?>" type="text" class="form-control" id="input3" placeholder="WMZ => WME">
		    </div>
		  </div>
		  <div class="form-group">
		    <label for="input4" class="col-sm-4 control-label">WMR => WME</label>
		    <div class="col-sm-8">
		      <input name="WMR" value="<?= $wmr; ?>" type="text" class="form-control" id="input4" placeholder="WMR => WME">
		    </div>
		  </div>
		  <div class="form-group">
		    <div class="col-sm-offset-4 col-sm-8">
		      <button type="submit" name="save" value="exrates" class="btn btn-default">Save</button>
		    </div>
		  </div>
		</form>
	</div>
</div>