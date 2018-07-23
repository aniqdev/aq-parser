<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');


if (isset($_POST['action']) && $_POST['action'] === 'get_steam_list'){
	$search_query = _esc($_POST['search_query']);
	$res = arrayDB("SELECT id,title,link FROM steam_de WHERE title LIKE '%$search_query%' LIMIT 10");
	echo json_encode($res);
	return;
}


if (isset($_POST['action']) && $_POST['action'] === 'set_steam_link'){
	$game_id = (int)$_POST['game_id'];
	$steam_link = _esc($_POST['steam_link']);
	arrayDB("UPDATE games SET steam_link = '$steam_link' WHERE id = '$game_id'");
	return;
}

