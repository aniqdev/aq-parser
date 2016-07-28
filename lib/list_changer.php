<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['table'])):
require_once('array_DB.php');
$id = mysql_escape_string(trim($_POST['id']));
$name = mysql_escape_string(trim($_POST['name']));
$table = mysql_escape_string(trim($_POST['table']));
arrayDB("UPDATE $table SET name='$name' WHERE id='$id'");
echo $table;
endif; // if($_GET['table'])

?>
