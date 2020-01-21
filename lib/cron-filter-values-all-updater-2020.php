<?php
ini_get('safe_mode') or set_time_limit(1200); // Указываем скрипту, чтобы не обрывал связь.


//=============================================================================================
// обновляем переменные фильтра
arrayDB('TRUNCATE filter_values_all');

$tables_arr = [
	'steam_de',
	'steam_en',
	'steam_fr',
	'steam_es',
	'steam_it',
	'steam_ru',
];

foreach ($tables_arr as $steam_table) {
	update_filter_values($steam_table);
}


function char_validatorr($characteristic, $value)
{
	if (!$value) return false; // если пустая строка

	if ($characteristic === 'year' && ($value < 1995 || $value > date('Y'))) return false; // Год от 1000 до текущего

	return true;
}

function update_filter_values($steam_table)
{
/////////////////////////////////////////////////////////////////////////////////
	arrayDB("UPDATE `$steam_table` SET `os` = 'win' WHERE `os` = ''");  // костыль: игры без ОС пишем Windows

	$insert_query = 'INSERT INTO filter_values_all (steam_table,name,value,count) VALUES '.PHP_EOL;

	$characteristics = [
		'year' => 'str',
		'genres' => 'array',
		'tags' => 'array',
		'specs' => 'array',
		'lang' => 'array',
		'os' => 'array',
	];

	// ==============================================================================
	// Сохраняем значения годов
	foreach ($characteristics as $characteristic => $format) {

		$characteristics_res = arrayDB("SELECT `$characteristic`,count(*) FROM $steam_table group by `$characteristic`");

		$result_arr = [];

		foreach ($characteristics_res as $set) {

			if ($format === 'str') {
				$value = _esc($set[$characteristic]);
				$is_valid = char_validatorr($characteristic, $value);
				$count = _esc($set['count(*)']);
				if ($is_valid) {
					$insert_query .= "('$steam_table','$characteristic','$value','$count'),".PHP_EOL;
				}
			}

			if ($format === 'array') {
				$explodes = explode(',', $set[$characteristic]);
				foreach ($explodes as $val) {
					$value = _esc(trim($val));
					$count = _esc($set['count(*)']);
					$is_valid = char_validatorr($characteristic, $value);
					if ($is_valid) {
						@$result_arr[$value] += (int)$count;
					}
				}
			}
		}

		if($steam_table === 'steam_de'){
			sa("$characteristic: " . count($result_arr));
			sa($result_arr);
		}
		foreach ($result_arr as $value => $count) {
			$insert_query .= "('$steam_table','$characteristic','$value','$count'),".PHP_EOL;
		}
	}


	$insert_query = substr(trim($insert_query), 0, -1);
	// sa($insert_query);
	var_dump(arrayDB($insert_query));
	// sa($insert_query);
	unset($insert_query);
	echo "<hr><br>=============================================================================";


/////////////////////////////////////////////////////////////////////////////////
}


sa($_ERRORS);









?>