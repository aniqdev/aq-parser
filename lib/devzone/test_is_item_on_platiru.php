<pre><?php

function is_item_on_platiru($item_id)
{
	$dom = file_get_html('http://www.plati.com/itm/'.$item_id);
	$is_class_there = $dom->find('.goods_order_form_quanuty');
	return(!!$is_class_there);
}
	$item_id1 = '2186678';
	$item_id2 = '1797717';

var_dump(is_item_on_platiru($item_id1));

$plObj = new PlatiRuBuy;

var_dump($plObj->isItemOnPlati($item_id2));


print_r($_SESSION);

?></pre>