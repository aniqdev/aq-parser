<pre>
<?php

$a = obj(QS)->set('foo','bar')->del(0,'bar')->give();

var_dump($a);

function ech($value='')
{
		echo 'value';
}
print_r(ArrayDB("SELECT count(*) as count FROM ebay_orders ORDER BY id DESC"));


$tabs = file_get_contents('csv/tabs.txt');
// $sems = str_replace("\t", ';', $tabs);
$comas = str_replace(".", ',', $tabs);
// file_put_contents('csv/tabs_sems.csv', $sems);
file_put_contents('csv/tabs_comas.txt', $comas);
file_put_contents('csv/tabs_comas.csv', $comas);

echo "qwe ech()";
?>
</pre>