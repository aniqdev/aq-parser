<?php


$limit = 5;

$month_top = arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash, steam_de.ebay_price, steam_de.id
		FROM (select title,price,ebay_id,shipped_time,count(*) as count 
				from ebay_order_items
				where shipped_time > NOW() - INTERVAL 1 MONTH
				group by ebay_id) tt
	JOIN ebay_games
	ON tt.ebay_id = ebay_games.item_id
	JOIN steam_de
	ON tt.ebay_id = steam_de.ebay_id
	WHERE picture_hash <> ''
	order by count desc
	limit $limit");

$ids_arr1 = array_map(function($el){return $el['id'];}, $month_top);
$ids_str1 = implode(',', $ids_arr1);

$where_and = '';
if($ids_str1) $where_and = "steam_de.id NOT IN($ids_str1) AND";


$strategie_top = arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							steam_de.id,
							genres,
							o_reviews
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE $where_and ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND genres LIKE '%strategie%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$ids_arr2 = array_map(function($el){return $el['id'];}, $strategie_top);
$ids_arr2 = array_merge($ids_arr1,$ids_arr2);
$ids_str2 = implode(',', $ids_arr2);
$where_and = '';
if($ids_str2) $where_and = "steam_de.id NOT IN($ids_str2) AND";


$abenteuer_top = arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							steam_de.id,
							genres,
							o_reviews
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE $where_and ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND genres LIKE '%abenteuer%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$ids_arr3 = array_map(function($el){return $el['id'];}, $abenteuer_top);
$ids_arr3 = array_merge($ids_arr2,$ids_arr3);
$ids_str3 = implode(',', $ids_arr3);
$where_and = '';
if($ids_str3) $where_and = "steam_de.id NOT IN($ids_str3) AND";


$rpg_top = arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							steam_de.id,
							genres,
							o_reviews
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE $where_and ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND genres LIKE '%rpg%' 
		ORDER BY o_reviews DESC LIMIT $limit");

$ids_arr4 = array_map(function($el){return $el['id'];}, $rpg_top);
$ids_arr4 = array_merge($ids_arr3,$ids_arr4);
$ids_str4 = implode(',', $ids_arr4);
$where_and = '';
if($ids_str4) $where_and = "steam_de.id NOT IN($ids_str4) AND";


$top_2015 = arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							steam_de.id,
							genres
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE $where_and ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND year = 2015 
		ORDER BY o_reviews DESC LIMIT $limit");


$tops_arr = ['month_top' => $month_top,
				  'strategie_top' => $strategie_top,
				  'abenteuer_top' => $abenteuer_top,
				  'rpg_top' => $rpg_top,
				  'top_2015' => $top_2015];

file_put_contents(__DIR__.'/adds/filter-tops.json', json_encode($tops_arr));


sa($tops_arr);