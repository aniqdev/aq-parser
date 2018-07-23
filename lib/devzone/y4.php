<?php
ini_get('safe_mode') or set_time_limit(1200); // Указываем скрипту, чтобы не обрывал связь.








	$one_week_ago = date('Y-m-d H:i:s', time()-(60*60*24*7));

sa($one_week_ago);


return;
			$res = implode(', ',
				array_filter(array_map(function($el){
					return get_country_code($el);},
					array_filter(explode(',','Englisch,Französisach,,Italienisch,Spanisch,Russisch,Chinesisch (traditionell),Polnisch,Japanisch,Koresanisch,Ukrainisch')))));
			sa($res);


return;
$res = arrayDB("select * from ebay_orders where id = 14228")[0];

sa($res);

$address = json_decode($res['ShippingAddress'], true);

sa($address);

var_dump(is_trusted_country($address['Country']));

	$trusted_coutries = [
		'DE', // германия
		'AT', // австрия
		'CH', // швейцария
		'NL', // нидерланды
		'BE', // бельгия
		'LU', // люксембург
	];

	var_dump(in_array(json_decode(arrayDB("SELECT * from ebay_orders where id = 14228")[0]['ShippingAddress'], true)['Country'], [
		'DE', // германия
		'AT', // австрия
		'CH', // швейцария
		'NL', // нидерланды
		'BE', // бельгия
		'LU', // люксембург
	]));




return;
$src = 'http://cdn.edgecast.steamstatic.com/steam/apps/767000/ss_594570d7e4d4792e6d6dad1505b8084f3a27a40b.1920x1080.jpg?t=1513126365';
copy($src, __DIR__.'/asd.jpg');
            $src = parse_url( $src, PHP_URL_QUERY );
            sa($src);

            $src = str_replace('url=', '', $src);

            sa($src);




return;
$_GET['old_prices'] = arrayDB("SELECT ebay_id,ebay_price,instock FROM steam_de WHERE ebay_id <> ''");

$_GET['old_prices'] = array_column($_GET['old_prices'], null, 'ebay_id');

sa($_GET['old_prices']);


return;
$feed_old = arrayDB("SELECT * FROM cdvet_feed");

$feed_old = array_column($feed_old, null, 'shop_id');

sa($feed_old);


return;
$csv = csvToArr('http://www.cdvet.de/backend/export/index/productckeck?feedID=20&hash=5b1c9a571cf947e366411cddc68d9129', ['max_str' => 10,'encoding' => 'windows-1250']);

// array_walk($csv, function (&$val, &$key)
// {
// 	$key = $val[0];
// 	// $val = 'test';
// });

$csv = array_column($csv, null, 0);

sa($csv);



return;
$str = 'Mit &Auml;therischen &Ouml;len ';

sa($str);

sa(htmlspecialchars_decode($str));

sa(html_entity_decode($str));





return;
$item = post_curl('http://parser/ajax.php?action=add-cdvet', 
			['action'=>'get_xcel_info', 'row'=>2]);

// sa($item);

$desc = Cdvet::prepare_description($item);

echo $desc;


return;
// $desc = 'MicroAgrar MK Euter akut Direkt leistet einen wichtigen Beitrag zur Versorgung des Organismus mit den wichtigen Spurenelementen Kupfer, Mangan und Zink um das physiologische Gleichgewicht, auch bei Veränderungen der Zellzahlen, zu verbessern.'


// $desc_arr = explode(',', $desc);

// foreach ($desc_arr as $part) {
// 	# code...
// }






// return;
$long_desc = 'suchen sind signifikante Steigerungen der Milchleistungen zwischen dem 50. und 150. Laktationstag festgestellt worden. Dabei im Besonderen bei &auml;lteren K&uuml;hen mit mindestens 2 Laktationen.</font></div><div align="left"><font face="Arial" size="2" color="#000000">Eine optimale Versorgung mit Mineralstoffen, Spurenelementen und ausgesuchten Kr&auml;utern unterst&uuml;tzt die physiologischen Organfunktionen und Stoffwechselvorg&auml;nge und ist Voraussetzung f&uuml;r ein gesundes Leben.</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000">Mineralerg&auml;nzungsfuttermittel f&uuml;r Milchk&uuml;he</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusammensetzung</u></b>: Algenkalk, Bierhefe, Seealgenmehl, Traubenkerne extrahiert, Malzkeime, Brennnessel, Mariendistel</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Analytische Bestandteile und Gehalte</u></b>: Rohprotein 4,4%, Rohfaser 3,8%, Rohasche 72,7%, Rohfett &lt;0,2%, Natrium 0,80%, Calcium 6,75%, Phosphor 0,10%, Magnesium 0,85%, salzs&auml;ureunl&ouml;sliche Asche 50,9%</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusatzstoffe je kg:</u></b> technologische Zusatzstoffe: Bentonit 1m558i 289g, Klinoptilolith sediment&auml';
$long_desc2 = 'suchen sind signifikante Steigerungen der Milchleistungen zwischen dem 50. und 150. Laktationstag festgestellt worden. Dabei im Besonderen bei &auml;lteren K&uuml;hen mit mindestens 2 Laktationen.</font></div><div align="left"><font face="Arial" size="2" color="#000000">Eine optimale Versorgung mit Mineralstoffen, Spurenelementen und ausgesuchten Kr&auml;utern unterst&uuml;tzt die physiologischen Organfunktionen und Stoffwechselvorg&auml;nge und ist Voraussetzung f&uuml;r ein gesundes Leben.</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000">Mineralerg&auml;nzungsfuttermittel f&uuml;r Milchk&uuml;he</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusammensetzung</u></b>: Algenkalk, Bierhefe, Seealgenmehl, Traubenkerne extrahiert, Malzkeime, Brennnessel, Mariendistel</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Analytische Bestandteile und Gehalte</u></b>: Rohprotein 4,4%, Rohfaser 3,8%, Rohasche 72,7%, Rohfett &lt;0,2%, Natrium 0,80%, Calcium 6,75%, Phosphor 0,10%, Magnesium 0,85%, salzs&auml;ureunl&ouml;sliche Asche 50,9%</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusatzstoffe je kg:</u></b> technologische Zusatzstoffe: Bentonit 1m558i 289g, Klinoptilolith sediment&auml';

// $long_desc = 'asdas qwer q3r3';

sa(htmlspecialchars($long_desc));


$long_desc = Cdvet::get_gehalte($long_desc);
// $long_desc = Cdvet::get_zusammen($long_desc);

sa(htmlspecialchars($long_desc));


return;
$ebay_obj = new Ebay_shopping2();

$steam_id = '348540';

$urls = [
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/ramka.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/1.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/2.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/3.jpg',
	'http://hot-body.net/img-generator/folders/v'.$steam_id.'/4.jpg',
];

sa($urls);

$add = $ebay_obj->updateItemPictureDetails('122580528510', $urls);

sa($add);



?>
