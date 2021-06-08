<?php

$_GET['url'] = preg_replace('/\?number=.+/', '', $_GET['url']);
$_GET['url'] = str_replace('https://b2b.cdvet.de/', '', $_GET['url']);
$_GET['url'] = str_replace('https://www.cdvet.de/', '', $_GET['url']);
$_GET['url'] = 'https://b2b.cdvet.de/'.$_GET['url'];


$url = 'https://cdvet-parser.gig-games.de/b2b/input.json';
$json = file_get_contents($url);
$feed_new = json_decode($json, 1);

$feed_items = [];
foreach ($feed_new as $key => $value) {
	// if(is_dev() && $key > 60) break;
	$url = preg_replace('/\?number=.+/', '', $value[0]['url']);
	// $value = array_map('feed_item_adapter', $value);
	$feed_items[$url] = $value;
}

// sa(count($feed_items));
// sa($feed_items);

$url = 'https://cdvet-parser.gig-games.de/b2b/input-last.json';
$json = file_get_contents($url);
$feed_new = json_decode($json, 1);

$feed_items_last = [];
foreach ($feed_new as $key => $value) {
	// if(is_dev() && $key > 60) break;
	$url = preg_replace('/\?number=.+/', '', $value[0]['url']);
	// $value = array_map('feed_item_adapter', $value);
	$feed_items_last[$url] = $value;
}

// sa(count($feed_items_last));
// _sa($feed_items_last);
?>
<style>
.hfc-table-wrapper{
    color: #333;
    background: #eaeaea;
}
td.changed{
    background: #ffb837;
}
td.hfc-table-devider{
	height: 5px;
	background: #555;
}
</style>
<div class="container-fluid" style="max-width: 1600px;">
	<h1>hundefutter feed compare</h1>
	<form action="" class="form">
		<input type="hidden" name="action" value="<?= @$_GET['action'] ?>">
		<div class="input-group">
	      <input name="url" value="<?= @$_GET['url'] ?>" type="text" class="form-control" placeholder="Search for...">
	      <span class="input-group-btn">
	        <button class="btn btn-primary" type="button">Search</button>
	      </span>
	    </div><!-- /input-group -->
	</form>
	<div class="row">
		<div class="col-sm-6">
			<h3>Current</h3><?php sa(count($feed_items)); ?>
		</div>
		<div class="col-sm-6">
			<h3>Last</h3><?php sa(count($feed_items_last)); ?>
		</div>
	</div>
	<?php

$feed_items = isset($_GET['url']) ? @$feed_items[$_GET['url']] : [];
$feed_items_last = isset($_GET['url']) ? @$feed_items_last[$_GET['url']] : [];
	?>
	<div class="hfc-table-wrapper">
		<table class="table">
			<?php
			if($feed_items && $feed_items_last):
				foreach ($feed_items as $key => $variant):
					?><tr><td colspan="3" class="hfc-table-devider"></td></tr><?php
					foreach ($variant as $name => $value):
						$if_changed = '';
						if ($feed_items[$key][$name] !== $feed_items_last[$key][$name]) {
							$if_changed = 'changed';
						}
						?>
						<tr>
							<td class="<?= $if_changed ?>"><?= $name ?></td>
							<td><?= is_array($feed_items[$key][$name]) ? '' : $feed_items[$key][$name] ?></td>
							<td><?= is_array($feed_items_last[$key][$name]) ? '' : $feed_items_last[$key][$name] ?></td>
						</tr>
						<?php
					endforeach;
				endforeach;
			endif;
			?>
		</table>
	</div>
</div>