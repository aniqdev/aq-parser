<?php
ini_get('safe_mode') or set_time_limit(1200); // Указываем скрипту, чтобы не обрывал связь.

$report = [];
//=============================================================================================
// считаем ценовое преимущество
$report[] = arrayDB("UPDATE steam_de set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_en set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_fr set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_es set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;
	UPDATE steam_it set advantage = ROUND((old_price-ebay_price)/old_price*100, 2) where ebay_id <> '' and old_price > 0;", true);

$report[] = arrayDB("UPDATE steam_de set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_en set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_fr set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_es set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;
	UPDATE steam_it set advantage = ROUND((reg_price-ebay_price)/reg_price*100, 2) where ebay_id <> '' and old_price = 0 and reg_price > 0;", true);

$report[] = arrayDB("UPDATE steam_de set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_en set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_fr set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_es set advantage = -1 where ebay_id <> '' and reg_price = 0;
	UPDATE steam_it set advantage = -1 where ebay_id <> '' and reg_price = 0;", true);


sa($report);
sa($_ERRORS);









?>