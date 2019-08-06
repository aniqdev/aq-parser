<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
?>
<br>
<form method="POST" enctype="multipart/form-data" action="/index.php?action=google-words-downloader">
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input name="file1" type="file" id="exampleInputFile">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <div class="form-group">
    <label for="exampleInputFile2">File input</label>
    <input name="file2" type="file" id="exampleInputFile2">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <a class="btn btn-default" href="/index.php?action=google-words-downloader">reload</a>
</form>
<br>

<?php


sa($_FILES);

if (isset($_FILES['file1']) && $_FILES['file1']['size']) {
	$arr = file($_FILES['file1']['tmp_name']);
	$arr = array_map(function($val){
		$val = str_getcsv($val, ';');
		return trim($val[0]);
	}, $arr);
	sa($arr);
	foreach ($arr as $word) {
		$word = _esc($word);
		arrayDB("INSERT INTO gp_keywords SET word = '$word'");
	}
}

if (isset($_FILES['file2']) && $_FILES['file2']['size']) {
	$arr = file($_FILES['file2']['tmp_name']);
	$arr = array_map(function($val){
		$val = str_getcsv($val, ';');
		return trim($val[0]);
	}, $arr);
	sa($arr);
	foreach ($arr as $word) {
		$word = _esc($word);
		arrayDB("INSERT INTO gp_keywords_2 SET word = '$word'");
	}
}



?>