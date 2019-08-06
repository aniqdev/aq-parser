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

	$games_arr = arrayDB("SELECT genres,tags,year,developer,publisher,specs,lang,os 
		FROM $steam_table ORDER BY id DESC LIMIT 5000");
	if(!$games_arr) continue;
	update_filter_values($games_arr, $steam_table);
}




function update_filter_values(&$games_arr, $steam_table)
{
/////////////////////////////////////////////////////////////////////////////////
	$insert_query = 'INSERT INTO filter_values_all (steam_table,name,value,count) VALUES '.PHP_EOL;
	// ==============================================================================
	// Сохраняем значения годов
	$years_res = [];
	foreach ($games_arr as $val) {
		if(strlen(trim($val['year'])) > 3) @$years_res[$val['year']] += 1;
	}
	sa('year: ' . count($years_res));
	sa($years_res);
	foreach ($years_res as $year => $count) {
		$insert_query .= "('$steam_table','year','$year','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения жанров
	$genres_res = [];
	foreach ($games_arr as $k => $v) {
		$genreses = explode(',', $v['genres']);
		foreach ($genreses as $val) {
			if($val) @$genres_res[$val] += 1;
		}
	}
	sa('genres: ' . count($genres_res));
	sa($genres_res);
	foreach ($genres_res as $genre => $count) {
		$genre = _esc(trim($genre));
		$insert_query .= "('$steam_table','genres','$genre','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения тегов
	$tags_res = [];
	foreach ($games_arr as $k => $v) {
		$tags = explode(',', $v['tags']);
		foreach ($tags as $val) {
			if($val) @$tags_res[$val] += 1;
		}
	}
	sa('tags: ' . count($tags_res));
	sa($tags_res);
	foreach ($tags_res as $tag => $count) {
		$tag = _esc(trim($tag));
		$insert_query .= "('$steam_table','tags','$tag','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения developers
	// $developer_res = [];
	// foreach ($games_arr as $val) {
	// 	if($val['developer']) @$developer_res[$val['developer']] += 1;
	// }
	// sa(count($developer_res));
	// sa($developer_res);
	// foreach ($developer_res as $developer => $count) {
	// 	$developer = _esc($developer);
	// 	$insert_query .= "('$steam_table','developer','$developer','$count'),".PHP_EOL;
	// }



	// ==============================================================================
	// Сохраняем значения publishers
	// $publisher_res = [];
	// foreach ($games_arr as $val) {
	// 	if($val['publisher']) @$publisher_res[$val['publisher']] += 1;
	// }
	// sa(count($publisher_res));
	// sa($publisher_res);
	// foreach ($publisher_res as $publisher => $count) {
	// 	$publisher = _esc($publisher);
	// 	$insert_query .= "('$steam_table','publisher','$publisher','$count'),".PHP_EOL;
	// }



	// ==============================================================================
	// Сохраняем значения specifics
	$specs_res = [];
	foreach ($games_arr as $k => $v) {
		$tags = explode(',', $v['specs']);
		foreach ($tags as $val) {
			if($val) @$specs_res[$val] += 1;
		}
	}
	sa('specs: ' . count($specs_res));
	sa($specs_res);
	foreach ($specs_res as $specific => $count) {
		$specific = _esc(trim($specific));
		$insert_query .= "('$steam_table','specs','$specific','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения языки
	$lang_res = [];
	foreach ($games_arr as $k => $v) {
		$tags = explode(',', $v['lang']);
		foreach ($tags as $val) {
			if($val && strpos($val, '#') === false) @$lang_res[$val] += 1;
		}
	}
	sa('lang: ' . count($lang_res));
	sa($lang_res);
	foreach ($lang_res as $lang => $count) {
		$lang = _esc(trim($lang));
		$insert_query .= "('$steam_table','lang','$lang','$count'),".PHP_EOL;
	}



	// ==============================================================================
	// Сохраняем значения os
	$os_res = [];
	foreach ($games_arr as $k => $v) {
		$tags = explode(',', $v['os']);
		foreach ($tags as $val) {
			if($val && strpos($val, '#') === false) @$os_res[$val] += 1;
		}
	}
	sa('os: ' . count($os_res));
	sa($os_res);
	foreach ($os_res as $os => $count) {
		$os = _esc(trim($os));
		$insert_query .= "('$steam_table','os','$os','$count'),".PHP_EOL;
	}

	// sa(count(explode(PHP_EOL, $insert_query)));


	$insert_query = substr(trim($insert_query), 0, -1);
	// sa($insert_query);
	var_dump(arrayDB($insert_query));
	unset($insert_query);
	echo "<hr><br>=============================================================================";

/////////////////////////////////////////////////////////////////////////////////
}


sa($_ERRORS);









?>