<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use voku\db\DB;


function ip_checker($statement)
{
    $db = DB::getInstance(db_HOST, db_USER, db_PASS, db_NAME);

    $ret = ['status' => 'good', 'msg' => ''];
    $e404 = '<h2 style="color: #e54f4f; text-align: center; font-size:100px">404</h2>';
    $wait = '<div style="font-family:arial; text-align:center;">wait for 5 minutes till the next try</div>';

    if(isset($_GET['ip']) &  $_GET['ip']) $ip = $_GET['ip'];
    else $ip = $_SERVER['REMOTE_ADDR'];
    $time = time();

	if ($statement === 'good') {
  		$result = $db->query("SELECT * FROM gig_ip_list WHERE ip = '$ip'");
  		$record = $result->fetchArray();
  		if ($record && $record['counter'] >= 5 && $time - $record['updated_at'] < 60*5) {
  			$ret ['status'] = 'banned';
  			$ret ['msg'] = $e404 . $wait;
  		}elseif($record) {
  			$ret ['msg'] = $record['counter'];
  		}
	}

	if ($statement === 'bad') {
  		$result = $db->query("SELECT * FROM gig_ip_list WHERE ip = '$ip'");
  		$record = $result->fetchArray();
  		$ret ['status'] = 'warning';
  		// if ($record && $record['counter'] < 5 && $time - $record['updated_at'] < 60) {
  		// 	$db->query("UPDATE gig_ip_list SET counter = counter + 1, updated_at = '$time' WHERE ip = '$ip'");
  		// }
  		$ret ['msg'] = $e404 . '<div style="font-family:arial; text-align:center;"><p>Bitte prüfen Sie nochmal den Link. Offensichtlich haben Sie ihn fehlerhaft kopiert.  
Sie haben noch <b>' . (5 - @$record['counter']) . '</b> Versuche</p>

<p>Please check the link again. Obviously you have copied it incorrectly.
You still have <b>' . (5 - @$record['counter']) . '</b> attempts</p></div>';
  		
  		if (!$record) { // добавляем запись, еще есть попытки

  			$db->query("INSERT INTO gig_ip_list (ip,counter,updated_at) VALUES ('$ip','1','$time')");

  		}elseif ($record['counter'] < 5 && $time - $record['updated_at'] < 60) { // еще есть попытки

  			$db->query("UPDATE gig_ip_list SET counter = counter + 1, updated_at = '$time' WHERE ip = '$ip'");

  		}elseif ($record['counter'] >= 5 && $time - $record['updated_at'] < 60*5) { // ip в бане

  			$db->query("UPDATE gig_ip_list SET updated_at = '$time' WHERE ip = '$ip'");
  			$ret ['status'] = 'banned';
  			$ret ['msg'] = $e404 . $wait;

  		}else{ // выходим из бана, сбрасываем счетчик, еще есть попытки

  			$db->query("UPDATE gig_ip_list SET counter = 1, updated_at = '$time' WHERE ip = '$ip'");

  		}
	}

	return $ret;
}


function C_hello($request, $response, $args) {
    $name = $args['name'];
    echo("Hello: $name");

    // return $response;
}


function C_index($request, $response)
{
 //  $db = DB::getInstance(db_HOST, db_USER, db_PASS, db_NAME);
  
 //  $ip = $_SERVER['REMOTE_ADDR'];
	// $result = $db->query("SELECT * FROM gig_ip_list WHERE ip = '$ip'");
	// $record = $result->fetchArray();
	// if (!$record) {
 //    sa('if');
 //    sa($record);
	// }else{

	// }
	echo '<h2 style="color: red; text-align: center; font-size:50px">404</h2>';


  //echo '<h2 style="color: red; text-align: center;">This page only for inside use</h2>';
}

function C_email($request, $response, $args)
{
	$db = DB::getInstance(db_HOST, db_USER, db_PASS, db_NAME);
	$email_slug = $db->escape(substr($args['email_slug'], 0, 100));

	$result = $db->query("SELECT body_html FROM gig_email_saver WHERE email_slug = '$email_slug' ORDER BY id DESC LIMIT 1");
	$result = $result->fetchArray();

	if ($result) {
		$check = ip_checker('good');
		if ($check['status'] === 'banned') {
			echo $check['msg'];
		}else{
			echo $result['body_html'];
		}
	}else{
		$check = ip_checker('bad');
		echo $check['msg'];
	}
}


function C_private($request, $response, $args)
{  
  $db = DB::getInstance(db_HOST, db_USER, db_PASS, db_NAME);
  $secret_hash = $db->escape(substr($args['secret_hash'], 0, 100));

  $result = $db->query("SELECT * FROM private_pages WHERE secret_hash = '$secret_hash'");
  $result = $result->fetchArray();

  $PRODUCT = $result['PRODUCT'];
  $EMAIL_SLUG = $result['EMAIL_SLUG'];
  $USER_EMAIL = $result['USER_EMAIL'];

  if(is_trusted_country($result['country'])) $private_page = get_text_template('private_page', 'DE');
  else $private_page = get_text_template('private_page', 'EN');

  $private_page = str_ireplace('{{PRODUCT}}', $PRODUCT, $private_page);
  $private_page = str_ireplace('{{EMAIL_SLUG}}', $EMAIL_SLUG, $private_page);
  $private_page = str_ireplace('{{USER_EMAIL}}', $USER_EMAIL, $private_page);
  $private_page = fill_email_item_panel($private_page);

  if ($result) {
    $check = ip_checker('good');
    if ($check['status'] === 'banned') {
      echo $check['msg'];
    }else{
      echo $private_page;
    }
  }else{
    $check = ip_checker('bad');
    echo $check['msg']; 
  }
  // arrayDB("INSERT INTO private_pages (EMAIL_SLUG,USER_EMAIL,PRODUCT) 
  //   VALUES ('"._esc($email_slug)."','"._esc($order['BuyerEmail'])."','"._esc($product_list)."')");
}


?>