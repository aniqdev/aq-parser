<?php





$res = arrayDB("SELECT CategoryID,CategoryLevel,CategoryName,CategoryName_DE,CategoryParentID from moda_cats where type = 'women'");

// sa($final_res);
echo '<div class="container">';
draw_table_with_sql_results($res, true);
echo '</div>';


return;
foreach ($final_res as $key => $val) {
	arrayDB("UPDATE moda_cats SET type = 'women' WHERE id = '$val[id]'");
}

function get_moda_women_cats()
{
	$final_res = [];

	$res3 = arrayDB("SELECT * from moda_cats where CategoryParentID= '260010'");
	foreach ($res3 as $val3) {
		$res4 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val3['CategoryID']}'");
		sa($res4);
		if ($res4) {
			foreach ($res4 as $val4) {
				$res5 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val4['CategoryID']}'");
				if ($res5) {
					foreach ($res5 as $val5) {
						$res6 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val5['CategoryID']}'");
						if ($res6) {
							foreach ($res6 as $val6) {
								$final_res[] = $val6;
							}
						}else{
							$final_res[] = $val5;
						}
					}
				}else{
					$final_res[] = $val4;
				}
			}
		}else{
			$final_res[] = $val3;
		}
	}

	return($final_res);
}



return;
    $ItemSpecifics = array (
        0 => 
        array (
          'Name' => 'Rücknahme akzeptiert',
          'Value' => 
          array (
            0 => 'Verbraucher haben das Recht, den Artikel unter den angegebenen Bedingungen zurückzugeben.',
          ),
        ),
        1 => 
        array (
          'Name' => 'Nach Erhalt des Artikels sollte Ihr Käufer innerhalb der folgenden Frist den Kauf widerrufen oder den Rückgabeprozess einleiten',
          'Value' => 
          array (
            0 => '1 Monat',
          ),
        ),
        2 => 
        array (
          'Name' => 'Rücknahme  - Weitere Angaben',
          'Value' =>  
          array (
            0 => 'Widerrufsbelehrung
Widerrufsrecht
Sie haben das Recht, binnen vierzehn Tagen ohne Angabe von Gründen diesen Vertrag zu widerrufen.
Die Widerrufsfrist beträgt vierzehn Tage ab dem Tag, an dem Sie oder ein von Ihnen benannter Dritter, der nicht der Beförderer ist, die Waren in Besitz genommen haben bzw. hat.Um Ihr Widerrufsrecht auszuüben, müssen Sie uns ( Jocieville Samporna Purok 12, 9000 Cagayan de Oro, Phillipinen Telefon: +63 9279314551 Fax32222494446 E-Mail:sjocieville@yahoo.com ) mittels einer eindeutigen Erklärung (z. B. ein mit der Post versandter Brief, Telefax oder E-Mail) über Ihren Entschluss, diesen Vertrag zu widerrufen, informieren. Sie können dafür das beigefügte Muster-Widerrufsformular verwenden, das jedoch nicht vorgeschrieben ist. Sie können das Muster-Widerrufsformular oder eine andere eindeutige Erklärung auch auf unserer Webseite, www.islandpearl.com elektronisch ausfüllen und übermitteln. Machen Sie von dieser Möglichkeit Gebrauch, so werden wir Ihnen unverzüglich (z. B. per E-Mail) eine Bestätigung über den Eingang eines solchen Widerrufs übermitteln.Zur Wahrung der Widerrufsfrist reicht es aus, dass Sie die Mitteilung über die Ausübung des Widerrufsrechts vor Ablauf der Widerrufsfrist absenden.
Folgen des Widerrufs
Wenn Sie diesen Vertrag widerrufen, haben wir Ihnen alle Zahlungen, die wir von Ihnen erhalten haben, einschließlich der Lieferkosten (mit Ausnahme der zusätzlichen Kosten, die sich daraus ergeben, dass Sie eine andere Art der Lieferung als die von uns angebotene, günstigste Standardlieferung gewählt haben), unverzüglich und spätestens binnen vierzehn Tagen ab dem Tag zurückzuzahlen, an dem die Mitteilung über Ihren Widerruf dieses Vertrags bei uns eingegangen ist. Für diese Rückzahlung verwenden wir dasselbe Zahlungsmittel, das Sie bei der ursprünglichen Transaktion eingesetzt haben, es sei denn, mit Ihnen wurde ausdrücklich etwas anderes vereinbart; in keinem Fall werden Ihnen wegen dieser Rückzahlung Entgelte berechnet.Wir können die Rückzahlung verweigern, bis wir die Waren wieder zurückerhalten haben oder bis Sie den Nachweis erbracht haben, dass Sie die Waren zurückgesandt haben, je nachdem, welches der frühere Zeitpunkt ist.Sie haben die Waren unverzüglich und in jedem Fall spätestens binnen vierzehn Tagen ab dem Tag, an dem Sie uns über den Widerruf dieses Vertrags unterrichten, an … uns oder an [hier sind gegebenenfalls der Name und die Anschrift der von Ihnen zur Entgegennahme der Waren ermächtigten Person einzufügen] zurückzusenden oder zu übergeben. Die Frist ist gewahrt, wenn Sie die Waren vor Ablauf der Frist von vierzehn Tagen absenden.Wir tragen die Kosten der Rücksendung der Waren.Sie müssen für einen etwaigen Wertverlust der Waren nur aufkommen, wenn dieser Wertverlust auf einen zur Prüfung der Beschaffenheit, Eigenschaften und Funktionsweise der Waren nicht notwendigen Umgang mit ihnen zurückzuführen ist.Muster-Widerrufsformular(Wenn Sie den Vertrag widerrufen wollen, dann füllen Sie bitte dieses Formular aus und senden Sie es zurück.)
An [hier ist der Name, die Anschrift und gegebenenfalls die Telefaxnummer und E-Mail-Adresse des Unternehmers durch den Unternehmer einzufügen]
Hiermit widerrufe(n) ich/wir (*) den von mir/uns (*) abgeschlossenen Vertrag über den Kauf der folgenden Waren (*)/die Erbringung der folgenden Dienstleistung (*)
Bestellt am (*)/erhalten am (*)
Name des/der Verbraucher(s)
Anschrift des/der Verbraucher(s)
Unterschrift des/der Verbraucher(s) (nur bei Mitteilung auf Papier)
Datum
(*) Unzutreffendes streichen',
          ),
        ),
        3 => 
        array (
          'Name' => 'Rücksendekosten trägt',
          'Value' => 
          array (
            0 => 'Verkäufer trägt die Kosten der Rücksendung der Waren',
          ),
        ),
        4 => 
        array (
          'Name' => 'Anlass',
          'Value' => 
          array (
            0 => 'Business',
          ),
        ),
        5 => 
        array (
          'Name' => 'Größe',
          'Value' => 
          array (
            0 => '35-38  39-42  42-46',
          ),
        ),
    );



sa(gmp_prepare_specs($ItemSpecifics));

function gmp_prepare_specs($ItemSpecifics)
{
	if(is_array($ItemSpecifics) && isset($ItemSpecifics[0])) {
		return array_values(array_filter($ItemSpecifics, function($value)
		{
			return (stripos($value['Name'], 'Rück') === false) ? true : false;
		}));
	}else{
		return $ItemSpecifics;
	}
}




return;
$asdd = null;

echo "asd '$asdd' asd";

$pic_url_arr = array (
  0 => 'https://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/RjQAAOSwyQtVzK3Y/$_57.JPG?set_id=880000500F',
  1 => 'https://i.ebayimg.com/00/s/MTIwMFgxMjAw/z/RjQAAOdsSwyQtVzK3Y/$_57.JPG?set_id=880000500F',
);

$res = gmp_get_picture_hashes($pic_url_arr);

sa($res);

function gmp_get_picture_hashes($pic_url_arr)
{
	$pic_hashes = [];
	if ($pic_url_arr) {
		foreach ($pic_url_arr as $pic_url) {
			if (preg_match('#/[^s]/(.+)/#', $pic_url, $matches)) {
				$pic_hashes[] = $matches[1];
			}
		}
	}
	return implode(',', $pic_hashes);
}

return;
$res = get_moda_meta($moda_id = 4, $moda_name = false);

sa($res);





return;
set_moda_meta($moda_id = 7, $key_value_list = [
	'asd' => '66666666666  !!1234 245 345 $#%&^*()$',
	'zxc' => 65834568,
	'yyy' => 45745678568
]);
sa(arrayDB()->affected());
sa(arrayDB()->lastid());
sa(arrayDB()->affected());





return;
$categories = json_decode(file_get_contents('csv/eBayArtikel_s2.json'), true);

$sorted_cats = Cdvet::cd_ebay_cat_sort($categories);

$cats_str = "304851|304852|304898|304899|304923|304938|304941|304963|305206|305213|305256|305333|305454|305530|305664|305722|305736|305786|305795|305817|306152|306191|306220|306221|306303|306380|306398|306436|306463|306466|306551|306587|306860|306861|306976|306979|307238|307243|307284|307291";

$cats_str = "300698|300729|300764|300767|300885|301353|301356|301474|302240|302281|302284|302393|302805|302842|302845|302950|303846|303853|303857|303859|304034|304038|304044"
;

$cats = Cdvet::get_ebay_cat($cats_str, $sorted_cats);

sa($cats_str);

// sa($sorted_cats);

sa($cats);





return;
$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					WHERE status = 'processing'
					LIMIT 500");


$orders = arrayDB( "SELECT *
					FROM woo_orders 
					LEFT JOIN woo_order_items
					ON woo_orders.id = woo_order_items.gig_order_id 
					WHERE status = 'processing' 
						AND `show`='no' 
						AND shipped_time = '0'
						AND ExecutionMethod='default' 
					LIMIT 500");

$orders = arrayDB( "SELECT *
                    FROM woo_orders 
                    LEFT JOIN woo_order_items
                    ON woo_orders.id = woo_order_items.gig_order_id 
                    WHERE status = 'processing'
                    AND `show` = 'yes' 
                    LIMIT 500");

sa(count($orders));
foreach ($orders as $key => $order) {
	sa($order);
	// var_dump(is_eve_by_title($order['name']));
}


sa(count($orders));
sa($orders);

return
define('TRUSTED_CLIENTS_FILE', 'Files/_test2.json');

$client_id = '8108';

$is_trusted = is_trusted($client_id);

var_dump($is_trusted);

set_trusted($client_id, false);

$is_trusted = is_trusted($client_id);

var_dump($is_trusted);


// for ($i=0; $i < 10000; $i++) {
// 	set_trusted(rand(1000, 10000), false);
// }




function is_trusted($client_id)
{
	$clients_arr = file(TRUSTED_CLIENTS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	return in_array((int)$client_id, $clients_arr);
}


function set_trusted($client_id, $is_trusted)
{
	$clients_arr = file(TRUSTED_CLIENTS_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	if ($is_trusted) {
		$clients_arr[] = $client_id;
	}else{
		$clients_arr = array_flip($clients_arr);
		unset($clients_arr[$client_id]);
		$clients_arr = array_flip($clients_arr);
	}

	file_put_contents(TRUSTED_CLIENTS_FILE, implode(PHP_EOL, $clients_arr));
}

return;
$looking_for = '4050';


echo "<hr>";

$arr = '';

for ($i=0; $i < 10000; $i++) { 
	$arr .= rand(1000, 10000) . PHP_EOL;
}

$start = microtime(true);

$res = file_put_contents('Files/_test2.json', $arr);

var_dump($res);

$arr = file('Files/_test2.json');
var_dump(in_array((int)$looking_for, $arr));

for ($i=0; $i < 10; $i++) { 
	echo ",".$arr[$i];
}

$time = microtime(true) - $start;
sa($time);




echo "<hr>";



$arr = [];

for ($i=0; $i < 10000; $i++) { 
	$arr[] = rand(1000, 10000);
}

$start = microtime(true);

$res = file_put_contents('Files/_test1.json', json_encode($arr));

var_dump($res);

$file = file_get_contents('Files/_test1.json');

$arr = json_decode($file);
var_dump(in_array($looking_for, $arr));

for ($i=0; $i < 10; $i++) { 
	echo ",".$arr[$i];
}

$time = microtime(true) - $start;
sa($time);

echo "<hr>";