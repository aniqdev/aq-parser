<?php

$scans =  arrayDB("SELECT distinct scan FROM hundefutter_changes ORDER BY id DESC LIMIT 30");
// sa($scans);

if(!$scans) return;

if(!isset($_GET['scan'])) $_GET['scan'] = $scans[0]['scan'];
$scan = _esc($_GET['scan']);

$res = arrayDB("SELECT * FROM hundefutter_changes WHERE scan = $scan");
// sa($res);

$data  = [];
foreach ($res as $key => $res_line) {
	$data[$res_line['action']][] = $res_line;
}
// sa($data);


?>
<style>
li.active{ background: #285353; }
</style>
<div class="container" style="max-width: 1400px;">
	<div class="row">
		<div class="col-md-9">
			<?php if(isset($data['changed'])): ?>
			<h3 class="text-center">changed</h3>
			<table class="table">
				<tr>
					<th>shop id</th>
					<th>compare</th>
					<th>title</th>
					<th>attribute</th>
				</tr>
				<?php foreach($data['changed'] as $res_line): ?>
					<tr>
						<td><?= $res_line['feed_id'] ?></td>
						<td><a href="?action=hundefutter-feed-compare&url=<?= $res_line['url'] ?>" target="_blank"><i class="glyphicon glyphicon-th-list"></i></a></td>
						<td><a href="<?= $res_line['url'] ?>" target="_blank"><?= $res_line['title'] ?></a></td>
						<td><?= $res_line['field'] ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>
			<?php if(isset($data['appeared'])): ?>
			<h3 class="text-center">appeared</h3>
			<table class="table">
				<tr>
					<th>shop id</th>
					<th>title</th>
				</tr>
				<?php foreach($data['appeared'] as $res_line): ?>
					<tr>
						<td><?= $res_line['feed_id'] ?></td>
						<td><a href="<?= $res_line['url'] ?>" target="_blank">
							<?= $res_line['title'] ?>
							<i> <?= $res_line['variant'] ?></i>
						</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>
			<?php if(isset($data['disappeared'])): ?>
			<h3 class="text-center">disappeared</h3>
			<table class="table">
				<tr>
					<th>shop id</th>
					<th>title</th>
				</tr>
				<?php foreach($data['disappeared'] as $res_line): ?>
					<tr>
						<td><?= $res_line['feed_id'] ?></td>
						<td><a href="<?= $res_line['url'] ?>" target="_blank">
							<?= $res_line['title'] ?>
							<i> <?= $res_line['variant'] ?></i>
						</a></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>
		</div>
		<div class="col-md-3">
			<ul class="list-unstyled text-right">
				<?php foreach($scans as $scan): ?>
				<li class="<?= hc_is_active($scan['scan']) ?>"><a href="?action=hundefutter-changes&scan=<?= $scan['scan'] ?>">
					<?= date('d.m.Y H:i', $scan['scan']) ?>
				</a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>



<?php

function hc_is_active($scan)
{
	return $scan === $_GET['scan'] ? 'active' : '';
}