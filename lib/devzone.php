<br><div class="container text-center"><?php 

$files = scandir(__DIR__.'/devzone');

for ($i=2; $i < count($files); $i++) {
	$text = str_ireplace('.php', '', $files[$i]);
	echo '<p class="col-xs-2"><a class="devzone-btn" href="?action=devzone/',$text,'" title="',$text,'">',$text,'</a></p>';
}



?></div>