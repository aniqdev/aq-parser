<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['table'])):
require_once('array_DB.php');
$id = _esc(trim($_POST['id']));
$name = _esc(trim($_POST['name']));
$table = _esc(trim($_POST['table']));
arrayDB("UPDATE $table SET name='$name' WHERE id='$id'");
echo $table;
endif; // if($_GET['table'])

?>
