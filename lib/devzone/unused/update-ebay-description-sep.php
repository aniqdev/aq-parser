<?php ini_get('safe_mode') or set_time_limit(5000); // Указываем скрипту, чтобы не обрывал связь.

function change_contact(&$desc, $str, $l){
	$desc = preg_replace('/lng-'.$l.'(.+?)triple-support.+?gig-triple/s',
							 'lng-de${1}triple-support">Support 24H/7</div>
								<div class="triple-cont triple-mail"></div>
								<div class="triple-cont triple-new">'.$str.'</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="gig-triple', $desc);
}

$items = arrayDB("SELECT * from games where ebay_id <> ''");


$ebayObj = new Ebay_shopping2();


$contact_de = 'Wenn Sie Fragen, Anregungen oder unsere Unterstützung bei der Aktivierung brauchen, können Sie uns jederzeit kontaktieren. In der Regel werden die Anfragen innerhalb eines Tages bearbeitet.';

$contact_en = 'If you have any questions, suggestions or support, please do not hesitate to contact us. As a rule, the inquiries are processed within one day.';

$contact_fr = "Si vous avez des questions, des suggestions ou des conseils, n'hésitez pas à nous contacter. En règle générale, les enquêtes sont traitées dans un jour.";

$contact_es = 'Si tiene alguna pregunta, sugerencia o apoyo, no dude en ponerse en contacto con nosotros. Por regla general, las consultas se procesan en un día.';

$contact_it = 'Se hai domande, suggerimenti o assistenza, non esitate a contattarci. Di norma, le richieste vengono elaborate entro un giorno.';


foreach ($items as $key => $val) {
	echo '<hr><b>'.$key.'</b><br> <a href="http://www.ebay.de/itm/'.$val['ebay_id'].'" target="_blank">'.$val['name'].'</a><br>';
	if($key < 0 || $key > 4500 || $val['extra_field2'] === 'DescSepUpdated2'){
		sa('Continued! Allready updated.');
		continue;
	}


	$res = getSingleItem($val['ebay_id'], ['as_array'=>true,'IncludeSelector'=>'Description']);
	if($res['Ack'] !== 'Success'){
		sa($res);
		continue;
	}
	$description = $res['Item']['Description'];

	$ebay_id = $val['ebay_id'];
	$title = _esc($val['name']);
	$full_desc = _esc($description);
	arrayDB("INSERT INTO ebay_data 
		(ebay_id,title,full_desc)
		VALUES
		('$ebay_id','$title','$full_desc')");
	// ================================================================================
	// 	$description = preg_replace('/gig-quelle">(.+?)e:(.+?)<!--link-end-->/',
	// 						 'gig-quelle">$1e: steampowered</a><!--link-end-->', $description);

	// $description = preg_replace('/<a target="_blank" href="http:\/\/store.steampowered(.*)">/',
	// 						 '<a>', $description);


	// $description = preg_replace('/a\) Steam herunterladen(.+?)<br>b\)/s',
	// 						 "a) Laden Sie das Steaminstallationdatei herunter.<br>\r\nb)", $description);

	// $description = preg_replace('/a\) steam download(.+)<br>/',
	// 						 'a) Download the Steam installation file.<br>', $description);

	// $description = preg_replace('/a\) t(.+)<br>/',
	// 						 "a) Téléchargez le fichier d'installation Steam.<br>", $description);

	// $description = preg_replace('/a\) steamprogramme descarg(.+)<br>/',
	// 						 'a) Descargue el archivo de instalación de Steam.<br>', $description);

	// $description = preg_replace('/a\) steamprogramme scaricar(.+)<br>/',
	// 						 'a) Scaricare il file di installazione di Steam.<br>', $description);

	// change_contact($description, $contact_de, 'de');
	// change_contact($description, $contact_en, 'en');
	// change_contact($description, $contact_fr, 'fr');
	// change_contact($description, $contact_es, 'es');
	// change_contact($description, $contact_it, 'it');

	$description = preg_replace('/<a href="http:\/\/koeln-webstudio.+?<\/a>/s',
		'<div href="http://koeln-webstudio.de/" title="koeln-webstudio" class="koeln-logo" target="_blank">
			<div class="gig-created">created by</div>
			<img src="http://hot-body.net/gig-less/images/visit_card.png" alt="koeln-webstudio">
			<div class="gig-entwick">Entwicklung<br> Marketing<br> Design</div>
		</div>', $description);

	$description = preg_replace('/([\.!])[^\.!]+?support@gig-games\.de.+?<\/p>/',
			'$1</p>', $description);


	$description = preg_replace('/<div class="col-sm-3"><div class="gig-slider-block.+?<\/div><br><\/div>/s',
			'', $description);
	$description = preg_replace('/gig-bottom-panel.+?<\/h2>/s',
			'gig-bottom-panel row">', $description);

	$description = preg_replace('/<div class="icol-xs-2"><div class="gig-slider-block.+?<\/div><\/div>/s',
			'', $description);

	// ========================================================================================

	if(strlen($description) < 68000){
		sa('strlen < 68000');
		continue;
	}
	$res = $ebayObj->updateItemDescription($val['ebay_id'], $description);
	unset($res['Fees']);
	sa($res);

	$id = $val['id'];
	if(isset($res['Ack']) && $res['Ack'] === 'Success'){
		arrayDB("UPDATE games SET extra_field2='DescSepUpdated2' WHERE id = '$id'");
	}
}




?>