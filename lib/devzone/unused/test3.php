<pre>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$a = obj(QS)->set('foo','bar')->set('foo','baz')->del(0,'bar')->give();



$ebayObj = new Ebay_shopping2();

//$res = $ebayObj->GetCategorySpecifics(139973);

$s =   '<ItemSpecifics>
			  <NameValueList>
				    <Name> Plattform </Name>
				    <Value> PC </Value>
				    <Value> MAC </Value>
				    <Value> Linux </Value>
				    <!-- ... more Value values allowed here ... -->
			  </NameValueList>
			  <!-- ... more NameValueList nodes allowed here ... -->
		</ItemSpecifics>';

$item_id = '121769917931';

$specifics = [
	'Plattform' => ['win','mac','linux'],
	'Genre' => ['Brutal','Gewalt','Action','Abenteuer','Indie','RPG','Simulation','Early Access'],
	'USK-Einstufung' => 'USK ab 3',
	'Language' => ['Deutsch','Englisch','Französisch','Spanisch','Italienisch','Russisch','Polnisch','Japanisch','Koreanisch'],
];

// $specifics = [
// 	'Plattform' => ['win','mac','linux'],
// 	'Genre' => ['Abenteuer','Indie'],
// 	'USK-Einstufung' => 'USK ab 3',
// ];

$res = $ebayObj->UpdateCategorySpecifics($item_id, $specifics);

print_r($res);

?>
</pre>

