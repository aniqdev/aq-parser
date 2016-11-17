<pre><?php

var_dump(md5('kajmad'));
$wl = arrayDB("SELECT page_action FROM gig_users_page_list WHERE list_name='shoper' AND white_or_black='white'");
foreach ($wl as &$qwasd) $qwasd = $qwasd['page_action'];
print_r($wl);

$_POST['asd'] = 'asd';

$a = isset($_POST['asd']) ? $_POST['asd'] : 'qwe';

// $receive_item_link = 'https://shop.digiseller.ru/xml/purchase.asp?id_i=53822543&uid=A82DA4F635934723895655D9990EEE77';
// $receive_item_str = file_get_contents($receive_item_link);
// $receive_item_str = iconv('utf-8', 'cp1251', $receive_item_str);
// $receive_item_object = simplexml_load_string($receive_item_str);
// //$receive_item_object = json_decode(json_encode(simplexml_load_string($receive_item_str)));
// print_r($receive_item_object);
// var_dump((string)$receive_item_object->text);


	$ebay_obj = new Ebay_shopping2();
	//$msgs_body = $ebay_obj->GetInboxMessageBody('svenjazicke85');
	// $msgs_body = $ebay_obj->GetOutboxMessageBody('83584926840');
	// print_r($msgs_body);
$t1 = time();
	sleep(250);
	var_dump('waked:');
	var_dump(time()-$t1);


?></pre>