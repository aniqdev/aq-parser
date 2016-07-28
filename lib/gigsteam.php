<?php
function arrayDB($query,$multiquery = false){

    $host         = 'rdbms.strato.de';
    $name         = 'U2213198';
    $password     = 'kajmada1';
    $nameDatabase = 'DB2213198';

    $mysqli = new mysqli($host, $name, $password, $nameDatabase);
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    }
    if ($multiquery )$res = $mysqli->multi_query($query);
    else $res = $mysqli->query($query);
    if (is_object ($res)) {


        while ($row = $res->fetch_assoc()) {
            $arr[] = $row;
        }
        $mysqli->close();
        return $arr;
    }
    else $mysqli->close();
} // arrayDB

$marks = arrayDB('SELECT DISTINCT mark FROM slist ORDER BY mark DESC');
$mark = $marks[0]['mark'];

	$order = 'price';
	$by    = 'DESC';
	$limit = 80;
	$query = "SELECT title,price,link,rating,views FROM slist WHERE mark=$mark ORDER BY $order $by LIMIT $limit";
	$res = arrayDB($query);




echo json_encode($res);



?>