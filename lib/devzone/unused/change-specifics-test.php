<pre>
<?php

	$ebayObj = new Ebay_shopping2();

	$specifics = [];

	$specifics['USK-Einstufung'] = 'USK ab 12';

	//$specifics['PEGI-Einstufung'] = 'PEGI ab '.$steam_arr['pegi_age'];

	$specifics['Plattform'] = 'PC';

	$specifics['Genre'] = 'Arcade,Brettspiele,Kampfspiele';

	$specifics['Herausgeber'] = 'WildTangent';

	$specifics['Marke'] = 'WildTangent';

	$specifics['Regionalcode'] = 'Regionalcode-frei';

	$specifics['Language'] = 'Englisch';

	$specifics['Downloade Site'] = 'http://store.steampowered.com';

	$specifics['Spielmodus'] = 'Einzelspieler';

	$specifics['Besonderheiten'] = 'Download-Code';

	$specifics['Tags'] = 'RPG';

	$specifics['Erscheinungsjahr'] = '2014';

	$output = '<pre>'.print_r($specifics,1).'</pre>';

	$res = $ebayObj->UpdateCategorySpecifics('111981605961', $specifics);

	unset($res['Fees']);

	echo $output,'<pre>'.print_r($res,1).'</pre>';
//68
?>
</pre>