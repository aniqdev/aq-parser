<pre><?php

// $res = getSingleItem(122359122164,['as_array'=>true,'IncludeSelector'=>'Description']);

// $full_desc = $res['Item']['Description'];

// //sa($res['Item']['Title']);

// $dom = str_get_html($full_desc);

// $game_data = [];

// $game_data['title'] = $res['Item']['Title'];
// $game_data['desc_title'] = $dom->find('.gig-tittle',0)->find('h2',0)->plaintext;
// $game_data['gig-img3d'] = $dom->find('.gig-img3d',0)->src;
// $game_data['gig-img1'] = $dom->find('.gig-img1',0)->src;
// $game_data['gig-img2'] = $dom->find('.gig-img2',0)->src;
// $game_data['gig-img3'] = $dom->find('.gig-img3',0)->src;
// $game_data['gig-about'] = $dom->find('.gig-about',0)->innertext;

// print_r(getSingleItem(111538503643,['as_array'=>true]));

// $langs = 'Deutsch,Englisch,Französisch,Italienisch,Spanisch,Arabisch,Bulgarisch,Tschechisch,Dänisch,Niederländisch,Finnisch,Griechisch,Ungarisch,Japanisch,Koreanisch,Norwegisch,Polnisch,Portugiesisch,Brasilianisches Portugiesisch,Rumänisch,Russisch,Chinesisch (vereinfacht),Schwedisch,Thai,Chinesisch (traditionell),Türkisch,Ukrainisch';

// $lanf_aliases = get_country_code();

// sa(implode(',',array_map(function($el){return get_country_code($el);},explode(',',$langs))));

// $oss = 'win,mac,linux,htcvive,oculusrift,razerosvr';

// print_r(os_shorter($oss));

print_r(json_decode('{"payer":"","payer_last":18,"names":{"1":{"name":"Эндрю","price":88.33},"2":{"name":"Готвянский","price":163.33},"3":{"name":"Сеня","price":103.33}},"purchase_name":"","purchase_price":"","purchases":{"1":{"name":"Бухло","price":"120","count":2},"2":{"name":"Хавка","price":"150","count":2},"3":{"name":"АТБ","price":"85","count":3}},"checkboxes":{"i1":{"j2":true,"j1":true},"i2":{"j1":false,"j2":true,"j3":true},"i3":{"j1":true,"j2":true,"j3":true}}}',1));

?></pre>

