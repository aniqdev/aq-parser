<?php


if (isset($_GET['migration']) && $_GET['migration'] === 'create') {

	$tables = arrayDB("SHOW tables");
	// sa($tables);
	$create_code_arr = [];

	foreach ($tables as $table) {

		$table = _esc($table['Tables_in_gig_parser']);

		if(strpos($table, 'zybaq2k218') !== false) continue;

		$create_code = arrayDB("SHOW CREATE TABLE `$table`")[0]['Create Table'];

		$create_code = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $create_code);

		$create_code = preg_replace('/AUTO_INCREMENT=\d+/', 'AUTO_INCREMENT=0', $create_code);

		$create_code_arr[] = $create_code;
	}
		
	$bytes = file_put_contents(ROOT.'/migrations.json', json_encode($create_code_arr));

	sa(count($create_code_arr));
	sa($create_code_arr);
	var_dump($bytes);
}


if (isset($_GET['migration']) && $_GET['migration'] === 'migrate'){

	$create_code_json = file_get_contents(ROOT.'/migrations.json');

	$create_code_arr = json_decode($create_code_json, true);

	foreach ($create_code_arr as $query) {
		sa(arrayDB($query));
	}
}

?>
<div class="container">
	<br><br>
	<form class="btn-group" role="group" aria-label="..." method="GET">
		<input type="hidden" name="action" value="<?= $_GET['action']; ?>">
		<button type="submit" name="migration" value="create" class="btn btn-primary">create migraions</button>
		<button type="submit" class="btn btn-default">reload</button>
		<button type="submit" name="migration" value="migrate" class="btn btn-success">..migrate..</button>
	</form>
</div>