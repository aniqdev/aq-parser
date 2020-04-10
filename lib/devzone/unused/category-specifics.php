<?php

$arr = [];
if (isset($_GET['cat_id'])) {
	$arr = get_category_specifics_sorted($_GET['cat_id']);
}

?>
<div class="container">
	<h2>Category specifics</h2>
	<form class="row">
		<input type="hidden" name="action" value="<?= @$_GET['action'];?>">
	  <div class="col-sm-6">
	    <div class="input-group">
	      <input type="text" class="form-control" placeholder="Search for..." name="cat_id" value="<?= @$_GET['cat_id'];?>">
	      <span class="input-group-btn">
	        <button class="btn btn-default" type="submit">Go!</button>
	      </span>
	    </div><!-- /input-group -->
	  </div><!-- /.col-sm-6 -->
	  <div class="col-sm-6">
	  	<?= count($arr); ?>
	  </div>
	</form><!-- /.row -->
	<br>

<?php if (isset($_GET['cat_id'])) sa($arr); ?>

</div>