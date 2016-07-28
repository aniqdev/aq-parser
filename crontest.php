<?php

$a = file_get_contents('crontest.txt');
$d = date('l jS \of F Y h:i:s A');
$a .= "$d\r\n";
file_put_contents('crontest.txt', $a);