<?php

echo "<pre>".print_r(['asdf'=>'qwer','zxcv'=>234],1),'</pre>';
echo "<hr>";
var_dump(!![0]);
echo "<hr>";


$date = date_create_from_format('Y-m-d\TH:i:s', substr('2016-09-13T00:00:00.000Z',0,-5));
echo "<pre>";
var_dump($date);
echo "</pre>";
echo $stamp = date_timestamp_get($date);
var_dump(date('j-M-Y', $stamp));

echo "<hr>";
echo "<hr>";
// Instantiate a DateTime with microseconds.
$d = new DateTime('2016-09-13T00:00:05.000Z');

echo "<hr>";
// Output the microseconds.
echo $d->format('U'); // 012345
echo "<pre>";
//print_r($a = (array)$d);
echo $d->date,"<br>";
echo $a['date'];
echo "</pre>";

echo "<hr>";
// Output the date with microseconds.
//echo $d->format('Y-m-d H:i:s'); // 2011-01-01T15:03:01.012345
echo "<br>";
echo $d->date; // 2011-01-01T15:03:01.012345