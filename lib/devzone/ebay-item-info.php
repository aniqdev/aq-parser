<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.



$ebay_id = '122873574286';

//'IncludeSelector'=> Details,Description,ItemSpecifics,TextDescription
$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Details,ItemSpecifics,TextDescription']);

sa($res);
