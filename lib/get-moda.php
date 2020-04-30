<?php 
if (isset($_POST['action']) && $_POST['action'] === 'iterate-list') {
	include __DIR__.'/get-moda-list.php';
	return;
}
if (isset($_POST['action']) && $_POST['action'] === 'iterate-meta') {
	include __DIR__.'/get-moda-meta.php';
	return;
}
?>
<div class="container" style="width: 1500px;">
	<div class="row">
		<div class="col-sm-6">
			<?php include __DIR__.'/get-moda-list.php'; ?>
		</div>
		<div class="col-sm-6">
			<?php include __DIR__.'/get-moda-meta.php'; ?>
		</div>
	</div>
</div>