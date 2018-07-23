<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.
	header('Content-Type: application/json');
	header('Access-Control-Allow-Origin: *');

function get_filter_order()
{
	if(!isset($_POST['order_by']) || !$_POST['order_by']) return '';

	switch ($_POST['order_by']) {
		case 'reviews_asc': return 'ORDER BY o_reviews ASC';
		case 'reviews_desc': return 'ORDER BY o_reviews DESC';
		case 'rating_asc': return 'ORDER BY o_rating ASC';
		case 'rating_desc': return 'ORDER BY o_rating DESC';
		case 'price_asc': return 'ORDER BY ebay_price ASC';
		case 'price_desc': return 'ORDER BY ebay_price DESC';
		case 'advantage_asc': return 'ORDER BY advantage ASC';
		case 'advantage_desc': return 'ORDER BY advantage DESC';
		
		default: return '';
	}
}

function get_filter_from()
{
	$steam_table = _esc($_POST['steam_table']);
	// если тронуто поле reviews
	if($_POST['max_reviews']){
		$count = arrayDB("SELECT count(*) from `$steam_table` WHERE ebay_id <> ''")[0]['count(*)'];
		$offset = round($count * $_POST['max_reviews'][0] / 100);
		$limit = round($count * ($_POST['max_reviews'][1] - $_POST['max_reviews'][0]) / 100);
		return "(select * from `$steam_table` WHERE ebay_id <> '' order by o_reviews limit $offset,$limit) ss ";
	}

	return "`$steam_table`";
}

function get_filter_where()
{
	$sql_query = '';
	if(@$_POST['fields']) foreach ($_POST['fields'] as $field => $field_values) {
		if(strpos('genres,tags,specs,lang,os', $field) === false || !$field_values) continue;
		$field = _esc($field);
		$field_values = _esc(implode('|', $field_values));
		$sql_query .= " AND `$field` REGEXP '$field_values'";
	}

	if ($_POST['type']) {
		$type = _esc($_POST['type']);
		$sql_query .= " AND `type` = '$type'";
	}

	if ($_POST['year']) {
		$year = (int)$_POST['year'];
		$sql_query .= " AND `year` = '$year'";
	}elseif ($_POST['year_from'] || $_POST['year_to']) {
		$year_from = (int)$_POST['year_from'];
		if($year_from) $sql_query .= " AND `year` >= '$year_from'";
		$year_to = (int)$_POST['year_to'];
		if($year_to) $sql_query .= " AND `year` <= '$year_to'";
	}

	if ($_POST['rating']) {
		$rating_from = (int)$_POST['rating'][0];
		$rating_to = (int)$_POST['rating'][1];
		$sql_query .= " AND `o_rating` >= '$rating_from'";
		$sql_query .= " AND `o_rating` <= '$rating_to'";
	}

	if ($_POST['our_price']) {
		$price_from = (int)$_POST['our_price'][0];
		$price_to = (int)$_POST['our_price'][1];
		$sql_query .= " AND `ebay_price` >= '$price_from'";
		$sql_query .= " AND `ebay_price` <= '$price_to'";
	}

	if ($_POST['steam_price']) {
		$price_from = (int)$_POST['steam_price'][0];
		$price_to = (int)$_POST['steam_price'][1];
		$sql_query .= " AND `reg_price` >= '$price_from'";
		$sql_query .= " AND `reg_price` <= '$price_to'";
	}

	if ($_POST['advantage']) {
		$price_from = (int)$_POST['advantage'][0];
		$price_to = (int)$_POST['advantage'][1];
		$sql_query .= " AND `advantage` >= '$price_from'";
		$sql_query .= " AND `advantage` <= '$price_to'";
	}

	return $sql_query;
}

if (isset($_POST['action']) && ($_POST['action'] === 'get_filter_count' || $_POST['action'] === 'get_filter_results')){

	$from = get_filter_from();

	if (trim($_POST['search'])) {
		// обрежем строку до 65 символов
		$search = _esc(substr(trim($_POST['search']), 0, 65));
		$where = " AND title LIKE '%$search%' ";
	}else{
		$where = get_filter_where();
	}

	if($_POST['action'] === 'get_filter_count'){
		$what = 'count(*)';
		$limit = '';
		$order_by = '';
		$pagination = '';
	} 
	if($_POST['action'] === 'get_filter_results'){
		insert_filter_log($action = 'query');
		$what = 'title,developer,publisher,reg_price,old_price,`release`,o_rating,ebay_id,ebay_price,advantage';
		$limit = 'LIMIT '.(int)$_POST['offset'] . ',' . (int)$_POST['limit'];
		$order_by = get_filter_order();
		$pagination = aqs_pagination_api((int)$_POST['offset'], (int)$_POST['limit'], (int)$_POST['count'], ['visible_pages'=>2]);
	} 

	$sql_query = "SELECT $what FROM $from WHERE ebay_id <> '' AND ebay_price > 0 AND advantage > 0 AND instock = 'yes' $where $order_by $limit";

	$res = arrayDB($sql_query);

	echo json_encode([
			'count' => @$res[0]['count(*)'],
			'results' => $res,
			'pagination' => $pagination,
			// 'sql_query' => $sql_query,
			'ERRORS' => $_ERRORS,
		]);
    return;
}


if (isset($_POST['action']) && $_POST['action'] === 'get_filter_data') {

	insert_filter_log('enter');
		// sa($_POST);
	$steam_table = _esc($_POST['steam_table']);

	$filter_data = arrayDB("SELECT * FROM filter_values WHERE steam_table = '$steam_table' ORDER BY value");
	$to_json_arr = [];
	foreach ($filter_data as $v) {
		// $to_json_arr[$v['name']][$v['value']] = $v['count'];
		$to_json_arr[$v['name']][] = ['v' => $v['value'], 'c' => $v['count']];
	}
	$to_json_arr['picture_hashes'] = arrayDB("SELECT item_id,picture_hash FROM ebay_games");
	$to_json_arr['picture_hashes'] = array_column($to_json_arr['picture_hashes'], 'picture_hash', 'item_id');

	$to_json_arr['max_reviews'] = arrayDB("SELECT o_reviews from steam_de where ebay_id <> '' order by o_reviews desc limit 1")[0]['o_reviews'];


	echo json_encode($to_json_arr);
	return;
}


if (isset($_POST['action']) && $_POST['action'] === 'get_translations'){
	
	$res = arrayDB("SELECT * FROM filter_langs");
	$to_json_arr['translations']['steam_de'] = array_column($res, 'de', 'slug');
	$to_json_arr['translations']['steam_en'] = array_column($res, 'en', 'slug');
	$to_json_arr['translations']['steam_fr'] = array_column($res, 'fr', 'slug');
	$to_json_arr['translations']['steam_es'] = array_column($res, 'es', 'slug');
	$to_json_arr['translations']['steam_it'] = array_column($res, 'it', 'slug');

	echo json_encode($to_json_arr);
	return;
}


if (isset($_POST['action']) && $_POST['action'] === 'game_click'){
	insert_filter_log('game');
}



if (isset($_POST['action']) && $_POST['action'] === 'get_chart_data'){
	echo get_filter_chart_json();
}



if (isset($_POST['action']) && $_POST['action'] === 'get_tops'){

	echo file_get_contents(__DIR__.'/adds/filter-tops.json');
	return;

	
	// $month_top = one_month_top($limit = 5);

	// $strategie_top = get_top_by_genre('strategie', $limit = 5);
	// $abenteuer_top = get_top_by_genre('abenteuer', $limit = 5);
	// $rpg_top = get_top_by_genre('rpg', $limit = 5);

	// $top_2015 = get_top_2015($limit = 5);

	// echo json_encode(['month_top' => $month_top,
	// 				  'strategie_top' => $strategie_top,
	// 				  'abenteuer_top' => $abenteuer_top,
	// 				  'rpg_top' => $rpg_top,
	// 				  'top_2015' => $top_2015]);
}


