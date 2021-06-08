<?php



$feed_1 = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input.json');

$feed_1 = json_decode($feed_1, 1);

sa(count($feed_1));

$feed_1i = [];
foreach($feed_1 as $feed_variants){
	foreach ($feed_variants as $feed_variant) {
		$feed_1i[$feed_variant['id']] = $feed_variant;
	}
}

$feed_2 = file_get_contents('http://cdvet-parser.gig-games.de/b2b/input-last.json');

$feed_2 = json_decode($feed_2, 1);

sa(count($feed_2));

$feed_2i = [];
foreach($feed_2 as $feed_variants){
	foreach ($feed_variants as $feed_variant) {
		$feed_2i[$feed_variant['id']] = $feed_variant;
	}
}


// sa(($feed_2i));

$scan = time();

// was changed
$true_count = 0;
$changed_count = 0;
foreach ($feed_2i as $f_id => $feed_item) {
	foreach ($feed_item as $name => $value) {
		if (isset($feed_1i[$f_id]) && isset($feed_2i[$f_id])) {
			if ($feed_1i[$f_id][$name] !== $feed_2i[$f_id][$name]) {
				$changed_count++;
				// sa('Найдено несовпадение у товара <b>'.$feed_1i[$f_id]['name'].'</b> '.$feed_1i[$f_id]['variant'].' ('.$f_id.'). В поле - <i>'.$name.'</i>');
				$title = _esc($feed_1i[$f_id]['name']);
				$variant = _esc($feed_1i[$f_id]['variant']);
				$url = _esc($feed_1i[$f_id]['url']);
				arrayDB("INSERT INTO hundefutter_changes SET 
					action = 'changed',
					feed_id = '$f_id',
					title = '$title',
					variant = '$variant',
					url = '$url',
					field = '$name',
					scan = '$scan'");
			}
		}
	}
}

// was appeared
$appeared = array_diff_key($feed_2i, $feed_1i);
$appeared_count = count($appeared);
sa($appeared_count);
foreach ($appeared as $f_id => $feed_item) {
		$title = _esc($feed_item['name']);
		$variant = _esc($feed_item['variant']);
		$url = _esc($feed_item['url']);
		arrayDB("INSERT INTO hundefutter_changes SET 
			action = 'appeared',
			feed_id = '$f_id',
			title = '$title',
			variant = '$variant',
			field = '',
			url = '$url',
			scan = '$scan'");
}

// was disappeared
$disappeared = array_diff_key($feed_1i, $feed_2i);
$disappeared_count = count($disappeared);
sa($disappeared_count);
foreach ($disappeared as $f_id => $feed_item) {
		$title = _esc($feed_item['name']);
		$variant = _esc($feed_item['variant']);
		$url = _esc($feed_item['url']);
		arrayDB("INSERT INTO hundefutter_changes SET 
			action = 'disappeared',
			feed_id = '$f_id',
			title = '$title',
			variant = '$variant',
			field = '',
			url = '$url',
			scan = '$scan'");
}

sa($true_count);
sa($changed_count);


$tg_message = '<a href="https://marser.gig-games.de/index.php?action=hundefutter-changes"><b>hundefutteruvm</b></a>'.PHP_EOL; // strlen === 100
if($changed_count) $tg_message .= 'Изменилось - '.$changed_count.PHP_EOL;
if($appeared_count) $tg_message .= 'Появилось - '.$appeared_count.PHP_EOL;
if($disappeared_count) $tg_message .= 'Пропало - '.$disappeared_count.PHP_EOL;
if(strlen($tg_message) < 105) $tg_message .= 'Сегодня без изменений';
AutomaticGroupBot::sendMessage($tg_message);