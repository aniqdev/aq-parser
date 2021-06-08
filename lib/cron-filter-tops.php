<?php


$limit = 5;


$strategie_top = arrayDB("SELECT id, title, type, appid, reg_price
		FROM steam_de
		WHERE genres LIKE '%strategie%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$strategie_top = array_map('cft_add_link', $strategie_top);

$ids_arr2 = array_map(function($el){return $el['id'];}, $strategie_top);
$ids_str2 = implode(',', $ids_arr2);
$where_and = '';
if($ids_str2) $where_and = "steam_de.id NOT IN($ids_str2) AND";


$abenteuer_top = arrayDB("SELECT id, title, type, appid, reg_price
		FROM steam_de
		WHERE $where_and genres LIKE '%abenteuer%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$abenteuer_top = array_map('cft_add_link', $abenteuer_top);

$ids_arr3 = array_map(function($el){return $el['id'];}, $abenteuer_top);
$ids_arr3 = array_merge($ids_arr2,$ids_arr3);
$ids_str3 = implode(',', $ids_arr3);
$where_and = '';
if($ids_str3) $where_and = "steam_de.id NOT IN($ids_str3) AND";


$rpg_top = arrayDB("SELECT id, title, type, appid, reg_price
		FROM steam_de
		WHERE $where_and genres LIKE '%rpg%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$rpg_top = array_map('cft_add_link', $rpg_top);

$ids_arr4 = array_map(function($el){return $el['id'];}, $rpg_top);
$ids_arr4 = array_merge($ids_arr3,$ids_arr4);
$ids_str4 = implode(',', $ids_arr4);
$where_and = '';
if($ids_str4) $where_and = "steam_de.id NOT IN($ids_str4) AND";


$top_2015 = arrayDB("SELECT id, title, type, appid, reg_price
		FROM steam_de
		WHERE $where_and year = 2015 
		ORDER BY o_reviews DESC LIMIT $limit");

$top_2015 = array_map('cft_add_link', $top_2015);


$tops_arr = [
	'strategie_top' => $strategie_top,
	'abenteuer_top' => $abenteuer_top,
	'rpg_top' => $rpg_top,
	'top_2015' => $top_2015
];

file_put_contents(__DIR__.'/adds/filter-tops.json', json_encode($tops_arr));


sa($tops_arr);






function cft_add_link($el){
	$el['link'] = get_search_parner_link($el['title']);
	return $el;
}