<?php


// $desc = 'MicroAgrar MK Euter akut Direkt leistet einen wichtigen Beitrag zur Versorgung des Organismus mit den wichtigen Spurenelementen Kupfer, Mangan und Zink um das physiologische Gleichgewicht, auch bei Veränderungen der Zellzahlen, zu verbessern.'


// $desc_arr = explode(',', $desc);

// foreach ($desc_arr as $part) {
// 	# code...
// }






// return;
$long_desc = 'suchen sind signifikante Steigerungen der Milchleistungen zwischen dem 50. und 150. Laktationstag festgestellt worden. Dabei im Besonderen bei &auml;lteren K&uuml;hen mit mindestens 2 Laktationen.</font></div><div align="left"><font face="Arial" size="2" color="#000000">Eine optimale Versorgung mit Mineralstoffen, Spurenelementen und ausgesuchten Kr&auml;utern unterst&uuml;tzt die physiologischen Organfunktionen und Stoffwechselvorg&auml;nge und ist Voraussetzung f&uuml;r ein gesundes Leben.</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000">Mineralerg&auml;nzungsfuttermittel f&uuml;r Milchk&uuml;he</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusammensetzung</u></b>: Algenkalk, Bierhefe, Seealgenmehl, Traubenkerne extrahiert, Malzkeime, Brennnessel, Mariendistel</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Analytische Bestandteile und Gehalte</u></b>: Rohprotein 4,4%, Rohfaser 3,8%, Rohasche 72,7%, Rohfett &lt;0,2%, Natrium 0,80%, Calcium 6,75%, Phosphor 0,10%, Magnesium 0,85%, salzs&auml;ureunl&ouml;sliche Asche 50,9%</font></div><div align="left"><font face="Arial" size="2" color="#000000"></font>&nbsp;</div><div align="left"><font face="Arial" size="2" color="#000000"><b><u>Zusatzstoffe je kg:</u></b> technologische Zusatzstoffe: Bentonit 1m558i 289g, Klinoptilolith sediment&auml';



sa(htmlspecialchars($long_desc));


$long_desc = get_gehalte($long_desc);
// $long_desc = get_zusammen($long_desc);

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
