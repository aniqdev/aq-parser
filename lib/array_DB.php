<?php
require_once __DIR__.'/../config.php';

spl_autoload_register(function($class)
{
	include_once __DIR__ . '/classes/' . $class . '.class.php';
});

if (function_exists('apc_load_constants')) {
    function define_array($key, $arr, $case_sensitive = true)
    {   if (!apc_load_constants($key, $case_sensitive)) {
            apc_define_constants($key, $arr, $case_sensitive); } }
} else { function define_array($key, $arr, $case_sensitive = true)
    { foreach ($arr as $name => $value) define($name, $value, $case_sensitive); } }

//in your code you just write something like this:

define_array('CLASSES', Array(
			'QS' => 'QueryString',
			'TWO' => 2,
			'THREE' => 3,
			));

// функция для работы с SQLite3
function aqSqlite($query,$multiquery = false){
		
		$db = new SQLite3(__DIR__.'/../sqlite/mydb.db');
		$results = $db->query($query);
		$res = array();
		if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0) {
				while ($row = $results->fetchArray(SQLITE3_ASSOC)) { // SQLITE3_BOTH, SQLITE3_ASSOC, SQLITE3_NUM
						$res[] = $row;
				}
		}

		$db->close();
		return $res;
}

function aqMysqli($query, $multiquery = false){

		if ($multiquery){

				$mysqli = new mysqli(db_HOST, db_USER, db_PASS, db_NAME);

				if ($mysqli->connect_errno) die ($mysqli->connect_error);

				$mysqli->set_charset( "utf8" );
				
				$res = $mysqli->multi_query($query);

				$mysqli->close();

		}else{

				if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0 || stripos($query, 'describe') === 0) {
						return DB::getInstance()->get_results($query);
				}else{
						return DB::getInstance()->query($query);
				}
				

		}

}


function arrayDB($query,$multiquery = false){
		if (USE_DB === 'sqlite') {
				return aqSqlite(trim($query));
		}elseif (USE_DB === 'mysqli') {
				return aqMysqli(trim($query),$multiquery);
		}else{
				echo "data base aq_error!";
		}
} // arrayDB

function _esc($str){
		return DB::getInstance()->escape($str);
}

function BlackListFilter(&$blacklist,&$itemID){
		foreach ($blacklist as $v) if ($itemID == $v['item_id']) return false;
		return true;
}

$_ERRORS = array();
// error handler function
function aqErrorHandler($errno, $errstr, $errfile, $errline){

		global $_ERRORS;
		if (!(error_reporting() & $errno)) {
				// This error code is not included in error_reporting
				return;
		}

		switch ($errno) {
		case E_USER_ERROR:
				$text = "<b>My ERROR</b> [$errno] $errstr <br>\n";
				$text .= "  Fatal error on line $errline in file $errfile <br>\n";
				$text .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ") <br>\n";
				$text .= "Aborting...";
				$_ERRORS[]['text'] = $text;
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_ERROR';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;
				exit(1);
				break;

		case E_USER_WARNING:
				$_ERRORS[]['text'] = "<b>My WARNING</b> [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_WARNING';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;

				break;

		case E_USER_NOTICE:
				$_ERRORS[]['text'] = "<b>My NOTICE</b> [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'USER_NOTICE';
				$_ERRORS[count($_ERRORS)-1]['errline'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file_name'] = $errfile;
				break;

		default:
				$_ERRORS[]['text'] = "Unknown error type: [$errno] $errstr";
				$_ERRORS[count($_ERRORS)-1]['type'] = 'UNKNOW_TYPE';
				$_ERRORS[count($_ERRORS)-1]['line'] = "$errline";
				$_ERRORS[count($_ERRORS)-1]['file'] = $errfile;
				break;
		}

		/* Don't execute PHP internal error handler */
		return true;
}
// set to the user defined error handler
set_error_handler("aqErrorHandler");



function setGigGamesIdsToFile(){

		$seller = 'gig-games';
		$ebay_obj = new Ebay_shopping2();
		$result_arr = $ebay_obj->getSellerInfo($seller);

		if ($result_arr['status'] === 'OK') {
				$ids_arr = array();
				for ($i=1; $i <= $result_arr['totalPages']; $i++) { 
						$items = $ebay_obj->getProductsBySeller($seller, $i);
						foreach ($items['items'] as $item) {
								$ids_arr[$item['itemId']] = $item['itemId'];
						}
				}
				file_put_contents(__DIR__.'/../settings/ids_arr.txt', serialize($ids_arr));

		}elseif($result_arr['status'] === 'error'){
				$aq_page1_msg = $result_arr['errorMsg'];

		}

} // setGigGamesIdsToFile


function setGigGamesIdsToFileT(){

		$seller = 'gig-games';
		$ebay_obj = new Ebay_shopping2();
		$result_arr = $ebay_obj->getSellerInfo($seller);
		print_r($result_arr);
		print_r($ebay_obj->getProductsBySeller($seller, 1));
		if ($result_arr['status'] === 'OK') {
				$ids_arr = array();
				// for ($i=1; $i <= $result_arr['totalPages']; $i++) { 
				//     $items = $ebay_obj->getProductsBySeller($seller, $i);
				//     var_dump($i);
				//     var_dump(count($items['items']));
				//     foreach ($items['items'] as $item) {
				//         if (isset($ids_arr[$item['itemId']])) {
				//             print_r($item);
				//             print_r($ids_arr);
				//             break 2;
				//             break;
				//         }
				//         $ids_arr[$item['itemId']] = $item;
				//     }
				// }
				// var_dump(count($ids_arr));
				// print_r($ids_arr);
				// file_put_contents(__DIR__.'/../settings/ids_arr.txt', serialize($ids_arr));

		}elseif($result_arr['status'] === 'error'){
				$aq_page1_msg = $result_arr['errorMsg'];

		}

}

// возвращает количество строк в CSV файле
// какобычно индекс последней строки на 1 меньше
function csvCount($file_path){
		$i = 0;
		$fh = fopen($file_path,'r') or die($php_errormsg); 
		while (!feof($fh)) { 
				fgets($fh);
						$i++;
		}
		fclose($fh) or die($php_errormsg);
		return $i;
}


// возвращает массив строки CSV файла по индексу
function csvGetRowByIndex($file_path, $index=0, $delimetr=',', $encoding='windows-1251'){
		$z = 0; $str = array();
		$i = $index; //нужная строка 
		$fh = fopen($file_path,'r') or die($php_errormsg); 
		while ((! feof($fh)) && ($z <= $i)) { 
				
				if ($z === $i) {
						$str = fgetcsv($fh, 0, $delimetr);
						if($encoding === 'windows-1251') foreach ($str as &$cell) $cell = iconv('Windows-1251', 'UTF-8', $cell);
				}else fgets($fh);
				$z++;
		}
		fclose($fh) or die($php_errormsg);
		return $str; 
}


function readExcel($path, $sheet = 0){
		
		// Открываем файл
		$xls = PHPExcel_IOFactory::load($path);
		// Устанавливаем индекс активного листа
		$xls->setActiveSheetIndex($sheet);
		// Получаем активный лист
		$sheet = $xls->getActiveSheet();
		 
		$Excel_table = array();
		// Получили строки и обойдем их в цикле
		$rowIterator = $sheet->getRowIterator();
		foreach ($rowIterator as $kR=>$row) {
				// Получили ячейки текущей строки и обойдем их в цикле
				$cellIterator = $row->getCellIterator();

				$Excel_table[$kR] = array();
				foreach ($cellIterator as $kC=>$cell) {
						$Excel_table[$kR][$kC] = $cell->getCalculatedValue();
				}
		}
		return $Excel_table;
}


// ============== Пример использования функции 
// В cell и value пердавать либо 2 строки либо 2 массива 
// $cell = array('G3','H3','I3','J3','K3','L3','M3');
// $value = array('Фото яндекса','м1','д1','м2','д2','м3','д3');
// writeCell(FILES_DIR.'file.xls', $cell, $value);
function writeCell($file_path, $cell, $value){
		$Xlsvsfkii_Failik = PHPExcel_IOFactory::load($file_path);
		$Xlsvsfkii_Failik->setActiveSheetIndex(0);

if (is_array($cell) && is_array($value)) {
		foreach ($cell as $k => $onecell) {
				$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($onecell, $value[$k]);
		}
}else{
		$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($cell, $value);
}

		switch (strtolower(pathinfo($file_path)['extension'])) {
				case 'csv':
						$writeType = 'CSV';
						break;
				case 'xls':
						$writeType = 'Excel5';
						break;
				case 'xlsx':
						$writeType = 'Excel2007';
						break;
				
				default:
						$writeType = 'Excel2007';
						break;
		}
		$Zapisat = PHPExcel_IOFactory::createWriter($Xlsvsfkii_Failik, $writeType);
		$Zapisat->save($file_path);
		 
		unset($Xlsvsfkii_Failik);
		unset($Zapisat);
}

//===================================================================================
//===================================================================================
// $inputArr = [1=>['A'=>'value']]
function writeExcel($file_path, $inputArr, $sheetIndex){

	if (!is_array($inputArr)) return;

	$Xlsvsfkii_Failik = PHPExcel_IOFactory::load($file_path);
	$Xlsvsfkii_Failik->setActiveSheetIndex($sheetIndex);

	foreach ($inputArr as $k1 => $row) {
		foreach ($row as $k2 => $cell) {
			$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($k2.$k1, $cell);
		}
	}

	switch (strtolower(pathinfo($file_path)['extension'])) {
			case 'csv':
					$writeType = 'CSV';
					break;
			case 'xls':
					$writeType = 'Excel5';
					break;
			case 'xlsx':
					$writeType = 'Excel2007';
					break;
			
			default:
					$writeType = 'Excel2007';
					break;
	}
	$Zapisat = PHPExcel_IOFactory::createWriter($Xlsvsfkii_Failik, $writeType);
	$Zapisat->save($file_path);
	 
	unset($Xlsvsfkii_Failik);
	unset($Zapisat);
}
//===================================================================================
//===================================================================================

// возвращает двумерный массив с первыми CSV файла
function csvToArr($file_path='', $options = array()){

		$config = array(
				'delimetr' => ';',
				'encoding' => 'utf-8',
				'max_str' => false,
				'del_first' => false,
				'output' => array()
				);
		$c = array_merge ( $config, $options );
		$fh = fopen($file_path,'r') or die($php_errormsg);
		$res = array(); $i = 0;
		
		while (!feof($fh)) {

				$str = fgetcsv($fh, 0, $c['delimetr']);

				$i++;
				if($c['del_first'] && $i === 1) continue;

				if(strtolower($c['encoding']) != 'utf-8' && $str) 
						foreach ($str as &$cell) 
								$cell = iconv($c['encoding'], 'UTF-8', $cell);


				$str2 = array();
				if($str)
				foreach ($c['output'] as $okey => $oval)
						$str2[$oval] = $str[$okey];

				if($str2) $res[] = $str2;
				elseif($str) $res[] = $str; 
				
				if($c['max_str'] && $i > $c['max_str']) break;

		}

		fclose($fh) or die($php_errormsg);

		return $res;
}

function arrToCsv($array, $file_path = 'result.csv', $options = array()){
		
		$config = array(
				'delimetr' => ',',
				'encoding' => 'utf-8',
				'keys_first_row' => true
				);
		$c = array_merge ( $config, $options );

				$count = count($array);
				$keys = array();
				$fp = fopen($file_path, 'w');
				if(!$fp) die('Не удалось получить доступ к '.$file_name);
				foreach ($array[0] as $key => $value) {
						$keys[] = $key;
				}
				if($c['encoding'] === 'windows-1251'){
						foreach ($keys as &$kcell) {
								$kcell = iconv('UTF-8', 'Windows-1251', $kcell);
						}
				}
				if($c['keys_first_row']) fputcsv($fp, $keys, $c['delimetr']);
				for ($i=0; $i < $count; $i++) {
						if($c['encoding'] != 'utf-8'){
								foreach ($array[$i] as &$cell) {
										$cell = iconv('UTF-8', $c['encoding'], $cell);
								}
						}
						fputcsv($fp, $array[$i], $c['delimetr']);
				}
				fclose($fp);
}

function showArray($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
}

function sa($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
}


/**
* 
*/
function get_a3_smtp_object(){ // 1
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'gig-games.de';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'a3@gig-games.de';                 // SMTP username
	$mail->Password = A3_GIG_MAIL_PWD;                           // SMTP password
	//$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption,
	$mail->SMTPAutoTLS = false;
	$mail->Port = 25;
	$mail->CharSet = "utf-8";                               // TCP port to connect to
	$mail->setFrom('a3@gig-games.de', 'GiG-Games');
	$mail->isHTML(true);                                  // Set email format to HTML

	return $mail;
}


function get_store_smtp_object(){ // 2
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gig-games.de';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'support@gig-games.de';                 // SMTP username
	$mail->Password = SUPPORT_GIG_MAIL_PWD;                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption,
	$mail->Port = 465;
	$mail->CharSet = "UTF-8";                               // TCP port to connect to
	$mail->setFrom('support@gig-games.de', 'GiG-Games');
	$mail->isHTML(true);                                  // Set email format to HTML

	return $mail;
}


function get_item_xml($receive_item_link){

	$ret = [];
	libxml_use_internal_errors(true);
	$receive_item_str = file_get_contents($receive_item_link);
	$receive_item_str = iconv('utf-8', 'cp1251', $receive_item_str);
	$receive_item_object = simplexml_load_string($receive_item_str);
	if($receive_item_object === false) return ['success'=>false,'text'=>'не загрузился xml'];

	$receive_item_array = (array)$receive_item_object;

	if ($receive_item_array['retval'] !== '0') {
		$ret['success'] = false;
		$ret['text'] = $receive_item_array['retdesc'];
		return $ret;
	}

	$ret['success'] = 'OK';
	if ($receive_item_array['typegood'] == '1') {
		$ret['typegood'] = '1';
		$ret['result'] = (string)$receive_item_object->text;
	}else{
		$ret['typegood'] = '2';
		$ret['link_tag'] = '<a href="'.(string)$receive_item_object->file->link.'" target="_blank">'.(string)$receive_item_object->file->name_in.'</a>';
		$ret['result'] = (string)$receive_item_object->file->link;
	}

	return $ret;
}


function get_steam_key_from_text($text){
	if (preg_match('/[A-Z0-9]{5}(-[A-Z0-9]{5}){2,4}/', $text, $matches)) {
		return $matches[0];
	}elseif(preg_match('/[A-Z0-9]{4}(-[A-Z0-9]{4}){4}/', $text, $matches)){
		return $matches[0];
	}else{
		return $text;
	}
}


function get_urls_from_text($text){

    $found = preg_match_all('#\b(https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#ism', $text, $urls);
    $urls = $urls[1];
    $urls = array_unique($urls);
    foreach ($urls as &$urlll) $urlll = preg_replace('/\?redeemer.*/', '', $urlll);
    $urls = implode('<br>'.PHP_EOL, $urls);
    if ($found) {
    	return $urls;
    }else{
    	return $text;
    }
}

function get_messages_for_send_producr($ca = 'EN', $ebay_or_mail = 'mail'){

	// германия, австрия, швейцария, нидерланды
	if ($ca === 'DE' || $ca === 'AT' || $ca === 'CH' || $ca === 'NL') {
		$msg_email = arrayDB("SELECT * FROM ebay_inv_messages WHERE country_alias='DE' AND ebay_or_mail='$ebay_or_mail' LIMIT 1")[0]['message'];
	}else{
		@$msg_email = arrayDB("SELECT * FROM ebay_inv_messages WHERE country_alias='$ca' AND ebay_or_mail='$ebay_or_mail' LIMIT 1")[0]['message'];
	}
	if (!$msg_email) {
		$msg_email = arrayDB("SELECT * FROM ebay_inv_messages WHERE country_alias='EN' AND ebay_or_mail='$ebay_or_mail' LIMIT 1")[0]['message'];
	}

	return $msg_email;
}


function cut_steam_from_title($item_title=''){

	if($pos = stripos($item_title, '(pc)')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'steam')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'gog')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'uplay')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'origin')) $item_title = substr($item_title, 0, $pos-1);

	return $item_title;
}


function key_link_replacer($msg_email=''){

	if (strpos($msg_email, 'account/ackgift') !== false || strpos($msg_email, 'undle.com') !== false) {
		$msg_email = str_replace('Activation link/key', 'Activation link', $msg_email);
		$msg_email = preg_replace('/Aktivierungsschl.+ssel\/link/', 'Aktivierungslink', $msg_email);
	}else{
		$msg_email = str_replace('Activation link/key', 'Activation key', $msg_email);
		$msg_email = preg_replace('/Aktivierungsschl.+ssel\/link/', 'Aktivierungsschlüssel', $msg_email);
	}

	return $msg_email;
}


function get_order_data_for_senders($order_id, $item_id){

	$order_info = arrayDB("SELECT * FROM ebay_orders WHERE id='$order_id'");
	if(!$order_info) return false;
	$order_info = $order_info[0];
	$bayer_address = json_decode($order_info['ShippingAddress'], true);
	$goods_titles = [];
	foreach (json_decode($order_info['goods'], true) as $go) {
		$goods_titles[$go['itemid']] = $go['title'];
	}

	$ret['item_id'] = $item_id;
	$ret['ebay_orderid'] = $order_info['order_id'];
	$ret['item_title'] = $goods_titles[$item_id];
	$ret['bayer_email'] = $order_info['BuyerEmail'];
	$ret['bayer_country_alias'] = $bayer_address['Country'];
	$ret['bayer_UserID'] = $order_info['BuyerUserID'];

	return $ret;
}


function sugest_send_product($product){

	$order_info = get_order_data_for_senders($_POST['tch-order-orderid'], $_POST['tch-order-itemid']);
	echo "<pre>";
	print_r($order_info);
	echo "</pre>";

	if(!$order_info) return false;
	$ca = $order_info['bayer_country_alias'];

	$msg_email = get_messages_for_send_producr($ca, 'mail');
	$msg_ebay = get_messages_for_send_producr($ca, 'ebay');

	$item_title = cut_steam_from_title($order_info['item_title']);

	//$product = iconv('CP1251', 'UTF-8', $product);
	$product = get_steam_key_from_text($product);
	$product = get_urls_from_text($product);

	$msg_email = str_replace('{{PRODUCT}}', $product, $msg_email);
	$msg_ebay = str_replace('{{EMAIL}}', $order_info['bayer_email'], $msg_ebay);

	$msg_email = key_link_replacer($msg_email);
	?>
	<br>
	<div class="container">
		<form class="row" method="POST" id="js-inv-sendemail-form">

			<div class="col-sm-6">
				<input type="hidden" name="sendemail">
				<input type="hidden" name="ebay_orderid" value="<?php echo $order_info['ebay_orderid'];?>">
				<input type="hidden" name="user_email" value="<?php echo $order_info['bayer_email'];?>">
				<input type="text" class="form-control" name="email_subject" value="Activation data for <?php echo $item_title;?>"><br>
				<textarea class="form-control" name="email_body" id="editor1" cols="30" rows="11" resize="both"><?php echo $msg_email; ?></textarea><br>
				<button class="glyphicon btn btn-success outline" type="submit">Send All</button>
				<button class="glyphicon btn btn-primary outline pull-right" id="js-inv-sendemail" type="button">Send Email</button>
			</div>

			<div class="col-sm-6">
				<input type="hidden" name="sendebay">
				<input type="hidden" name="ebay_user" value="<?php echo $order_info['bayer_UserID'];?>">
				<input type="hidden" name="ebay_item" value="<?php echo $order_info['item_id'];?>">
				<input type="text" class="form-control" name="ebay_subject" value="Activation data for <?php echo $item_title;?>"><br>
				<textarea class="form-control" name="ebay_body" id="" cols="30" rows="11"><?php echo $msg_ebay; ?></textarea><br>
				<button class="btn btn-info outline glyphicon pull-right" id="js-inv-sendebay" type="button">Send Message</button>
			</div>

		</form>
	</div>
	<hr>

	<script src="ckeditor/ckeditor.js"></script>
	<script>
	    // Replace the <textarea id="editor1"> with a CKEditor
	    // instance, using default configuration.
	    //CKEDITOR.replace( 'editor1' );
	</script>
	<?php

}


function array_walk_callback_clean_ebay_titles(&$title){

	$words_to_del = array(
		'(PC)',' PC ','-Region free-','Region free','Multilanguage','steam',
		'Multilang','Regfree','ENGLISH','-','–','&','Uplay','Game Of The Year Edition',
		'DLC','regfr','Add On','Addon',' goty','Regionfree',':',"’s","'s","'","’",'Uplay');
    $title = str_ireplace( $words_to_del, ' ', $title);
    $title = trim(preg_replace('/\s+/', ' ', $title));

}

// преобразует многомерный массив в одномерный
// ключи в значениях которых массивы не сохраняются
function array_collapser($array){

	$ret = [];
	foreach ($array as $k => $val) {
		if (!is_array($val)) $ret[$k] = $val;
		else $ret = array_merge($ret, array_collapser($val));
	}
	return $ret;
}

// function use custom blade templater from laravel 5.1
// more info: https://laravel.com/docs/5.1/blade
// example: view('ebay-messages/send-form',['data'=>$data]);
function view($view, $data){

	$views = __DIR__ . '/../views';
	$cache = __DIR__ . '/../cache';

	$blade = new Philo\Blade\Blade($views, $cache);
	echo $blade->view()->make($view, $data)->render();
	unset($blade);
}


function obj($obj_name = false)
{
	if($obj_name) return new $obj_name();
}


function query_to_orders_page($options = []){
	
	$query_arr = array_merge([
	'action'=>'orders-page',
	'list_type'=>'all',
	'order_id'=>'0',
	'modal_type'=>'info',
	'item_id'=>'0',
	'offset'=>'0',
	'limit'=>'100'],$options);

	return http_build_query($query_arr);
}


function add_comment_to_order($gig_order_id, $text, $show = 'yes'){
	if($gig_order_id < 1) return false;

	if (is_array($gig_order_id)) {
		$gig_order_item_id = $gig_order_id[1];
		$gig_order_id = $gig_order_id[0];

		arrayDB("UPDATE ebay_order_items
		SET item_comment='$text'
		WHERE id='$gig_order_item_id'");
	}

	arrayDB("UPDATE ebay_orders
	SET comment='$text',
		ExecutionMethod='cancelled',
		`show`='yes'
	WHERE id='$gig_order_id'");
}


function date_shorter($date){
	if($date == 0) return '';
	return (new DateTime($date))->format('d-m-y H:i');
}


function date_shorter_dots($date){
	if($date == 0) return '';
	return (new DateTime($date))->format('d.m.y H:i');
}


function formula ($rurprice,$exrate) {
	if($exrate <= 0) return 0;
	$rurprice = (float)$rurprice;
	$res = (($rurprice/$exrate)*1.00952+0.4165)/(1-(0.1+0.019+0.078)*1.19);
	if($res < 1.5) $res = 1.5;

	return round($res, 2);
}


function aqs_hrefR($arr = []){
	$qs_obj = new QueryString();
	foreach ($arr as $key => $val) $qs_obj->set($key,$val);
	return '?'.$qs_obj->give();
}


function aqs_pagination($table_name, $count = false){

	if(!$count) $count = (int)arrayDB("SELECT count(id) as count FROM `$table_name`")[0]['count'];
	$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
	$limit = @$_GET['limit'] ? (int)$_GET['limit'] : 10;
	if($limit > 500) $limit = 500;
	$offset_prev = $offset - $limit;
	if($offset_prev < 0) $offset_prev = 0;
	$offset_next = $offset + $limit;
	if($offset_next > $count) $offset_next = $offset;
	//var_dump($count/$limit);
	$str =
	'<nav aria-label="Page navigation" class="navigation">
	  <ul class="pagination op-pagination">
	    <li>
	      <a href="?'.obj(QS)->SET('offset',$offset_prev)->give().'" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>';
	for ($i=-4; $i <= 4; $i++) {
		$inoffset = $offset + ($limit * $i);
		$num = floor($inoffset / $limit + 1);
		$b = $count - $inoffset;
		$a = $b - $limit + 1;
		if($a < 0) $a = 1;
		if ($inoffset < 0) {
			continue;
		}elseif ($inoffset > $count) {
			break;
		} elseif ($inoffset == $offset) {
			$str .= '<li class="active"><a class="curren" title="'.$a.'-'.$b.'">'.$num.'</a></li>';
			$epilog = $a.'-'.$b.' of '.$count.' results';
		}else{
			$str .= '<li><a href="'.aqs_hrefR(['offset'=>$inoffset]).'" title="'.$a.'-'.$b.'">'.$num.'</a></li>';
		}
	}
	$str .= '<li>
	      <a href="?'.obj(QS)->SET('offset',$offset_next)->give().'" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	  </ul><br><b>'.$epilog.'</b>'.
	'</nav>';

	echo $str;

	// далее определяем лимит для внешнего запроса
	if(isset($_GET['offset']) && isset($_GET['limit'])){
		$limit = (int)$_GET['offset'].','.(int)$_GET['limit'];
	}else{
		$limit = '10';
	}
	return $limit;
}


function devzone_link(){

	if($_SESSION['username'] === 'namenav') return '<li><a href="?action=devzone"><small>devzone</small></a></li>';
}


function dz($r){

	if($_SESSION['username'] === 'namenav') return (string)$r;
}


function int_in_range($num, $min, $max){
	
	if ($num < $min) return $min;
	if ($num > $max) return $max;
	return $num;
}


function add_one_hour($date, $add_hours = '1 hour'){
	return date_create($date)
	->add(date_interval_create_from_date_string($add_hours))
	->format('Y-m-d H:i:s');
}


function object_to_array($data){
	
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}


function is_utf8($str){
	$c=0; $b=0;
	$bits=0;
	$len=strlen($str);
	for($i=0; $i<$len; $i++)
	{
		$c=ord($str[$i]);
		if($c > 128)
		{
			if(($c >= 254)) return false;
			elseif($c >= 252) $bits=6;
			elseif($c >= 248) $bits=5;
			elseif($c >= 240) $bits=4;
			elseif($c >= 224) $bits=3;
			elseif($c >= 192) $bits=2;
			else return false;
			if(($i+$bits) > $len) return false;
			while($bits > 1)
			{
				$i++;
				$b=ord($str[$i]);
				if($b < 128 || $b > 191) return false;
				$bits--;
			}
		}
	}
	return true;
}


function clean_url_from_query($url=''){
	return preg_replace('/\?.*$/', '', $url);
}

function clean_steam_url($url=''){
	return preg_replace('#(http://store\.steampowered\.com/.+?/.+?/).+#', "$1", $url);
}

function kostyl($str='')
{
	return htmlspecialchars_decode(htmlspecialchars($str));
}


function _getResultsFromApi(&$result, &$blacklist, &$blacksell){
	
		$k = 0;
		//print_r($result);
		//$result = json_decode($result);
		$iQ = $result->total;
		if ($iQ > 500) $iQ = 500;

		$arrItem = array();
		for($i = 0; $i < $iQ; $i++){
			
			$itemID      = $result->items[$i]->id;
			$sellID      = $result->items[$i]->seller_id;
		    $name        = $result->items[$i]->name;
		    $price       = $result->items[$i]->price_rur;
		    $description = $result->items[$i]->description;

   			$nameLow = mb_convert_case($name, MB_CASE_LOWER, "UTF-8");
   			$descLow = mb_convert_case($description, MB_CASE_LOWER, "UTF-8");

			$bool1 = (stripos($nameLow,'free') !== false || stripos($nameLow,'row') !== false || stripos($nameLow,'bundle') !== false);
			$bool2 = (stripos($descLow,'free') !== false || stripos($descLow,'row') !== false || stripos($descLow,'bundle') !== false);
			$bool3 = (stripos($nameLow,'ccount') === false && stripos($nameLow,'ккаунт') === false && stripos($nameLow,'cis') === false && stripos($descLow,'украина') === false);

			$bool4 = BlackListFilter($blacklist,$itemID);
			$bool5 = BlackListFilter($blacksell,$sellID);

			if (($bool1 || $bool2) && $bool3 && $bool4 && $bool5) {

				$arrItem[$k] = array();	
	    		$arrItem[$k]['itemID'] = $itemID;
			    $arrItem[$k]['name'] = $name;
			    $arrItem[$k]['price'] = $price;
			    $arrItem[$k]['sellID'] = $sellID;
			    $k++;
			}

		} // for i
		return $arrItem;

} // getResultsFromApi()


// для функции uasort()
function _sortN($a,$b){ return $a['price']-$b['price'];}


function _savePaltiRuToBase(&$arrItem, &$game_id, &$scan, $table = 'items', $operation = 'update'){

	$item1_id = 0; $item1_name = 'No results'; $item1_price = 0; $item1_desc = 'No results';
	$item2_id = 0; $item2_name = 'No results'; $item2_price = 0; $item2_desc = 'No results';
	$item3_id = 0; $item3_name = 'No results'; $item3_price = 0; $item3_desc = 'No results';

	usort ($arrItem, '_sortN');
	if (isset($arrItem[0])) {
		$item1_id    = _esc(trim(strip_tags($arrItem[0]['itemID'])));
		$item1_name  = _esc(trim(strip_tags($arrItem[0]['name'])));
		$item1_price = _esc(trim(strip_tags($arrItem[0]['price'])));
		$item1_desc  = _esc(trim(strip_tags($arrItem[0]['sellID'])));
	}
	if (isset($arrItem[1])) {
		$item2_id    = _esc(trim(strip_tags($arrItem[1]['itemID'])));
	 	$item2_name  = _esc(trim(strip_tags($arrItem[1]['name'])));
		$item2_price = _esc(trim(strip_tags($arrItem[1]['price'])));
		$item2_desc  = _esc(trim(strip_tags($arrItem[1]['sellID'])));
	}
	if (isset($arrItem[2])) {
		$item3_id    = _esc(trim(strip_tags($arrItem[2]['itemID'])));
	 	$item3_name  = _esc(trim(strip_tags($arrItem[2]['name'])));
		$item3_price = _esc(trim(strip_tags($arrItem[2]['price'])));
		$item3_desc  = _esc(trim(strip_tags($arrItem[2]['sellID'])));
	}

	$exist = arrayDB("SELECT id from $table where game_id = '$game_id' order by id desc");
	if ($exist && $operation === 'update') {
		$id = $exist[0]['id'];
		$query = "UPDATE $table
				SET item1_id    = '$item1_id',
					item1_name  = '$item1_name',
					item1_price = '$item1_price',
					item1_desc  = '$item1_desc',
					item2_id    = '$item2_id',
					item2_name  = '$item2_name',
					item2_price = '$item2_price',
					item2_desc  = '$item2_desc',
					item3_id    = '$item3_id',
					item3_name  = '$item3_name',
					item3_price = '$item3_price',
					item3_desc  = '$item3_desc'
				WHERE id = '$id'";
		arrayDB($query);
	}else{
		$query = "INSERT INTO $table 
				VALUES( null,'$game_id','$item1_id','$item1_name','$item1_price','$item1_desc',
										'$item2_id','$item2_name','$item2_price','$item2_desc',
										'$item3_id','$item3_name','$item3_price','$item3_desc','$scan',null)";
		arrayDB($query);
	}
	return $query;
}


function _requestFilter($request){
    $request = str_ireplace([':',"'s","'",'!','.'], ' ', $request);
    return trim(preg_replace('/\s+/', ' ', $request));
}


function _strictFilter(&$request, &$arrayIn){
    
    $arrayOut = array();
    $requestArr = explode(' ', $request);
    foreach ($arrayIn as &$game) {
        foreach ($requestArr as $reqWord) {
            $pos1 = stripos($game['name'], $reqWord);
            if($pos1 === false) continue 2;
        }
        $arrayOut[] = $game;
    }
    return $arrayOut;

} // strictFilter()



function _requestToArrChacker($pare, $reqArr){

    $k = 0;
    foreach ($reqArr as $nameOld) {
        $nameNew1 = str_ireplace($pare[0], $pare[1], $nameOld);
        if(!in_array($nameNew1, $reqArr)) $reqArr[] = $nameNew1;
        $k++; if($k == 64) break;
    }
    return $reqArr;
}


function _requestToArr(&$request){

    $configJSON = file_get_contents(__DIR__.'/../settings/platiru_settings.json');
	$configArr = json_decode($configJSON, true);

    $reqArr = array($request);
    foreach ($configArr as &$pare){
        $reqArr = _requestToArrChacker($pare, $reqArr);
        $reqArr = _requestToArrChacker(array_reverse($pare), $reqArr); 
    }


    return $reqArr;

} // requestToArr()


function _removeTradeMarks($str = ''){
	return str_ireplace(['®','™'], '', $str);
}


function build_item_specifics_array($steam_arr = []){

	if(!$steam_arr) return [];

	$specifics = [];

	if($steam_arr['usk_links']) {
		$specifics['USK-Einstufung'] = 'USK ab '.$steam_arr['usk_age'];
	}else{
		$specifics['USK-Einstufung'] = 'Unbekannt';
	}

	if($steam_arr['pegi_links']) {
		$specifics['PEGI-Einstufung'] = 'PEGI ab '.$steam_arr['pegi_age'];
	}

	if($steam_arr['os']){
		$from = ['win','mac','linux','htcvive','oculusrift','razerosvr'];
		$to = ['PC','Mac','Linux','HTC Vive','Oculus Rift','Razer OSVR'];
		$specifics['Plattform'] = str_ireplace($from, $to, $steam_arr['os']);
	}

	if($steam_arr['genres']){

		$genres_from = ['Simulation', // 1
			'Strategie', // 2
			'Action', // 3
			'Indie', // 4
			'RPG', // 5
			'Bildung', // 6
			'Animation & Modellierung', // 7
			'Design & Illustration', // 8
			'Fotobearbeitung', // 9
			'MMO', // 10
			'Brutal', // 11
			'Gewalt'];

		$genres_to = ['Simulationen', // 1
			'Strategiespiele,Battle', // 2
			'Action/Abenteuer', // 3
			"Arcade,Jump 'n' Run", // 4
			'Rollenspiele', // 5
			'Familie & Kinder', // 6
			'Familie & Kinder', // 7
			'Familie & Kinder', // 8
			'Familie & Kinder', // 9
			'Battle,Action/Abenteuer', // 10
			'Kampfspiele,Battle', // 11
			'Action/Abenteuer,Kampfspiele']; 

		$steam_arr['genres'] = str_ireplace($genres_from, $genres_to, $steam_arr['genres']);

		if(strpos($steam_arr['tags'], 'Shooter') !== false) $steam_arr['genres'] .= ',Shooter';
		if(strpos($steam_arr['tags'], 'Fighting') !== false) $steam_arr['genres'] .= ",Beat 'Em Up";
		if(strpos($steam_arr['tags'], 'Brettspiel') !== false) $steam_arr['genres'] .= ',Brettspiele';
		if(strpos($steam_arr['tags'], 'Zombies') !== false) $steam_arr['genres'] .= ',Survival Horror';

		$steam_arr['genres'] = substr($steam_arr['genres'], 0, 65);
		$steam_arr['genres'] = preg_replace('/,[^,]*$/', '', $steam_arr['genres']);
		$specifics['Genre'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['genres']))));
	}

	if($steam_arr['publisher']){
		$specifics['Herausgeber'] = $steam_arr['publisher'];
	}

	if($steam_arr['developer']){
		$specifics['Marke'] = $steam_arr['developer'];
	}

	$specifics['Regionalcode'] = 'Regionalcode-frei';

	if($steam_arr['lang']){
		$steam_arr['lang'] = str_ireplace('#lang_german;', 'Deutsch', $steam_arr['lang']);
		// $steam_arr['lang'] = substr($steam_arr['lang'], 0, 65);
		// $steam_arr['lang'] = preg_replace('/,[^,]*$/', '', $steam_arr['lang']);
		// $specifics['Language'] = implode(',', array_flip(array_flip(explode(',', $steam_arr['lang']))));

		$specifics['Language'] = explode(',', $steam_arr['lang']);
	}

	$specifics['Downloade Site'] = 'http://store.steampowered.com';

	$mods = [
		'Einzelspieler' => 1,
		'Koop' => 2,
		'Plattformübergreifender Mehrspieler' => 3,
		'Mehrspieler' => 4,
		'Mehrspielermodus (online)' => 5,
		'Online-Koop' => 6,
		'MMO' => 7,
	];
	$specifics['Spielmodus'] = [];
	foreach (explode(',', $steam_arr['specs']) as $s => $spec) {
		if(isset($mods[$spec])) $specifics['Spielmodus'][] = $spec;
	}

	unset($mods['Einzelspieler']);
	$specifics['Besonderheiten'] = 'Download-Code';
	$is_multi = false;
	foreach (explode(',', $steam_arr['specs']) as $s => $spec) {
		if(isset($mods[$spec])) $is_multi = true;
	}
	if($is_multi) $specifics['Besonderheiten'] .= ', Multi-player';

	$specifics['Tags'] = []; // первые 5
	if($steam_arr['tags']){
		foreach (explode(',', $steam_arr['tags']) as $t => $tag) {
			$specifics['Tags'][] = $tag;
			if($t > 3) break;
		}
	}

	$specifics['Erscheinungsjahr'] = $steam_arr['year'];

	return $specifics;
}


function add_words_to_game_name($game_name = '')
{
	if(!$game_name) return '';
	$new_title = $game_name.' PC spiel Steam Download Digital Link DE/EU/USA Key Code Gift';
	$new_title = trim(preg_replace('/\s+/', ' ', $new_title));
	$new_title = str_ireplace('Â', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Gift', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Digital', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Code', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' spiel', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Download', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace('DE/', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Link', '', $new_title);
	if (strlen($new_title) > 80) $new_title = str_ireplace(' Key', '', $new_title);

	return $new_title;
}


function get_steam_miracle_where(){
	return "item1_price > 0 AND is_on_ebay = 'no' AND IF(old_price > 0, old_price, reg_price)*0.9 > ROUND(((steam_items.item1_price/(select value from aq_settings where name = 'exrate'))*1.00952+0.4165)/(1-(0.1+0.019+0.078)*1.19),2)";
}


function add_dlc_addon_to_desc($steam_arr, $lang = false){

	$dlc_texts = [
		'de' => 'Dieses Produkt benötigt zum Spielen die Steam-Version des Hauptspiels',
		'en' => 'This content requires the base game',
		'fr' => 'Questo contenuto ha bisogno del gioco di base',
		'es' => 'Este contenido requiere el juego base',
		'it' => 'Ce contenu nécessite le jeu de base',
	];

	if(!isset($dlc_texts[$lang])) $lang = 'de';

	$sub_texts = [
		'de' => 'In diesem Paket enthaltenen Artikel',
		'en' => 'Items included in this package',
		'fr' => 'Articles inclus dans ce package',
		'es' => 'Artículos incluidos en este pack',
		'it' => 'Oggetti inclusi in questo pacchetto',
	];

	if(!isset($sub_texts[$lang])) $lang = 'de';
	
	if ($steam_arr['main_game_link']) {
		$l = '<h4 class="gig-dlc-hh">'.$dlc_texts[$lang].' <a target="_blank" href="'.$steam_arr['main_game_link'].'">'.$steam_arr['main_game_title'].'.</a></h4>';
		$steam_arr['desc'] = $l.$steam_arr['desc'];

	}elseif ($steam_arr['includes']) {
		$includes_arr = explode(',', $steam_arr['includes']);
		$titles_arr = explode('<br>', $steam_arr['desc']);
		$x = '<h4 class="gig-sub-hh">'.$sub_texts[$lang].':</h4>';
		foreach ($includes_arr as $k => $sid) {
			$x .= '<a class="gig-sub-link" target="_blank" href="http://store.steampowered.com/app/'.$sid.'/">'.$titles_arr[$k].'</a><br>';
		}
		$steam_arr['desc'] = $x;
	}

	return $steam_arr['desc'];
}


function getOrderArray(){

	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders(['NumberOfDays'=>1,'SortingOrder'=>'Ascending','PageNumber'=>'1']);

	//$ord_arr = $ord_obj->getOrders(['order_status'=>'Completed','OrderIDArray'=>['216865269010']]);

	// $ord_arr = $ord_obj->getOrders(['order_status'=>'All',
	// 	'CreateTimeFrom'=>date('Y-m-d\TH:i:s.000\Z', time()-(2*24*60*60)),
	// 	'CreateTimeTo'=>date('Y-m-d\TH:i:s.000\Z', time())]);

	if(!isset($ord_arr['Ack']))
		return ['success'=>0,'text'=>'нет данных от ebay api'];

	if($ord_arr['Ack'] === 'Failure')
		return ['success'=>0,'text'=>'ebay api вернул fail','Errors'=>$ord_arr];

	if(empty($ord_arr['OrderArray']))
		return ['success'=>0,'text'=>'нет заказов'];

	if(isset($ord_arr['OrderArray']['Order']['OrderID']))
		$ord_arr['OrderArray']['Order'] = [$ord_arr['OrderArray']['Order']];
	
	// echo "<pre>";
	// print_r($ord_arr['OrderArray']['Order'][0]);
	// echo "</pre>";
	return ['success'=>'OK','ord_arr'=>$ord_arr['OrderArray']['Order']];
}


function ajax_mark_as_paid($OrderID, $status = 'true'){
	
	$ebayObj = new EbayOrders();
	if($OrderID > 0){}else return false;
	// MarkAsShipped($OrderID, $status = 'true')
	$ret = $ebayObj->MarkAsPaid($OrderID, $status);
	if ($ret['Ack'] == 'Success') {
		if ($status === 'true') {
			arrayDB("UPDATE ebay_orders SET PaidTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
		}else{
			arrayDB("UPDATE ebay_orders SET PaidTime=0 WHERE order_id='$OrderID'");
		}
	}
	return $ret;
}


function ajax_mark_as_shipped($OrderID, $status = 'true'){
	
	$ebayObj = new EbayOrders();
	if($OrderID > 0){}else return false;
	// MarkAsShipped($OrderID, $status = 'true')
	$ret = $ebayObj->MarkAsShipped($OrderID, $status);
	if ($ret['Ack'] == 'Success') {
		$podzapros = "SELECT id from ebay_orders where order_id = '$OrderID' order by id desc limit 1";
		if ($status === 'true') {
			arrayDB("UPDATE ebay_orders SET ShippedTime=CURRENT_TIMESTAMP WHERE order_id='$OrderID'");
			if (isset($_POST['ebay_order_item_id'])) {
				$ebay_order_item_id = _esc($_POST['ebay_order_item_id']);
				arrayDB("UPDATE ebay_order_items SET shipped_time=CURRENT_TIMESTAMP WHERE id='$ebay_order_item_id'");
			}else{
				arrayDB("UPDATE ebay_order_items SET shipped_time=CURRENT_TIMESTAMP WHERE gig_order_id=($podzapros)");
			}
		}else{
			arrayDB("UPDATE ebay_orders SET ShippedTime=0 WHERE order_id='$OrderID'");
			if (isset($_POST['ebay_order_item_id'])) {
				$ebay_order_item_id = _esc($_POST['ebay_order_item_id']);
				arrayDB("UPDATE ebay_order_items SET shipped_time=0 WHERE id='$ebay_order_item_id'");
			}else{
				arrayDB("UPDATE ebay_order_items SET shipped_time=0 WHERE gig_order_id=($podzapros)");
			}
		}
	}
	return $ret;
}


function is_shipped($order){
	if ($order['ShippedTime'] > $order['shipped_time']) {
		return 'is-shipped';
	}
}


//проверяем наличие товара на плати.ру и коректируем количествое на ибее
function check_tem_on_plati_and_up_to_three($plati_id, $ebay_id)
{
  $platiObj = new PlatiRuBuy();
  $ebayObj = new Ebay_shopping2();
  
  if ($platiObj->isItemOnPlati($plati_id)) {
    return $ebayObj->updateQuantity($ebay_id, 3);
  }else{
    return $ebayObj->removeFromSale($ebay_id);
  }
}


function is_order_blocked($gig_order_id)
{
	$gig_order_id = (int)$gig_order_id;
	$block_ip = $_SERVER['REMOTE_ADDR'];
	$block_time = time()-300;
	$res = arrayDB("SELECT id FROM ebay_orders WHERE id='$gig_order_id' AND block_ip<>'$block_ip' AND block_time>'$block_time'");
	if($res) return true;
	else return false;
}


function block_order($gig_order_id)
{
	if(is_order_blocked($gig_order_id)) return false;
	$gig_order_id = (int)$gig_order_id;
	$block_ip = $_SERVER['REMOTE_ADDR'];
	$block_time = time();
	arrayDB("UPDATE ebay_orders SET block_ip='$block_ip', block_time='$block_time' WHERE id='$gig_order_id'");
	return true;
}


function activation_data_for($country)
{
	if ($country === 'DE') {
		return 'Ihre Bestellung: ';
	}else{
		return 'Your games: ';
	}
}


function get_sales_chart_json()
{
	$res = arrayDB("SELECT DATE_FORMAT(ShippedTime, '%d-%m') as date ,count(*) as count FROM ebay_orders WHERE ShippedTime > NOW() - INTERVAL 27 DAY GROUP BY day(ShippedTime) order by ShippedTime");
	$ret = [['date','sales']];
	foreach ($res as $k => $val) {
		if($k === 0) continue;
		$ret[] = [$val['date'], +$val['count']];
	}
	return json_encode($ret);
}


function _get_steam_images($steam_link = ''){

	if(!$steam_link) return [];
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: de\r\n" . "Cookie: Steam_Language=german; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
	$context = stream_context_create($options);
	$dom = file_get_html($steam_link, false, $context);

	$srcs = [];
	foreach ($dom->find('a[href*=1920x1080]') as $k => $img) {
		$src = $img->getAttribute('href');
		$src = parse_url( $src, PHP_URL_QUERY );
		$src = str_replace(['url=','!1920x1080'], ['','600x338'], $src);
		//echo '<img src="',$src,'">';
		$srcs[] = $src;
		if($k > 3) break;
	}
	return $srcs;
}


function is_eve($ebay_item_id)
{
	$plexes = [ '121962440805',
				'121962439324',
				'112090853589',
				'112353147792',];
	if (in_array($ebay_item_id, $plexes)) return true;
	return false;
}


function _get_urls_of_real_img($item_id){

	$res = Ebay_shopping2::getSingleItem($item_id, JSON_OBJECT_AS_ARRAY);
	if($res['Ack'] === 'Failure'){ echo 'Failure'; return 0; }
	$PictureURL = $res['Item']['PictureURL'][0];
	$found = preg_match('#/[^s]/(.+)/#', $PictureURL, $matches);
	if(!$found) return 0;
	$url_of_real_img = 'http://i.ebayimg.com/images/g/'.$matches[1].'/s-l500.jpg';
	return $url_of_real_img;
}


function _get_item_specs($item_id=''){

	if(!$item_id) return false;
	$res = Ebay_shopping2::getSingleItem($item_id);
	$specifics = json_decode($res, true)['Item']['ItemSpecifics']['NameValueList'];
	$specs = [];
	foreach ($specifics as $val) $specs[$val['Name']] = $val['Value'][0];
	return $specs;
}


function parse_item_specifics($item_id='')
{
	if(!$item_id) return false;
	$dom = file_get_html('http://www.ebay.de/itm/'.$item_id);
	$res = $dom->find('.attrLabels');
	$specs = [];
	foreach ($res as $key => $value) {
		$specs[str_replace(':','',trim($value->plaintext))] = trim($value->nextSibling()->plaintext);
	}
	return $specs;
}


function in_multi_array($needle, $haystack)
{
	foreach ($haystack as $value) {
		if (is_array($value)) {
			if (in_multi_array($needle, $value)) {
				return true;
			}
		}else{
			if(strtolower($needle) === strtolower($value)) return true;
		}
	}
	return false;
}


function convert_settings($res)
{
	$ret = [];
	foreach ($res as $v) $ret[$v['name']] = $v['value'];
	return $ret;
}


function get_settins_by_category($cat)
{
	if(!$cat) return false;
	$cat = _esc($cat);
	$settings = arrayDB("SELECT name,value FROM aq_settings WHERE category = '$cat'");
	return convert_settings($settings);
}


function set_settings_to_category($cat, $settings)
{
	if(!$cat) return false;
	$cat = _esc($cat);
	foreach ($settings as $key => $value) {
		$key = _esc($key);
		$value = _esc($value);
		if (arrayDB("SELECT id FROM aq_settings WHERE category = '$cat' AND name = '$key'")) {
			arrayDB("UPDATE aq_settings SET value = '$value' WHERE category = '$cat' AND name = '$key'");
		}else{
			arrayDB("INSERT INTO aq_settings (name, value, category) VALUES('$key','$value','$cat')");
		}
	}
}

function get_country_code($country = false)
{
	$countries = [
	    'Englisch' => 'EN',
	    'Deutsch' => 'DE',
	    'Französisch' => 'FR',
	    'Italienisch' => 'IT',
	    'Spanisch' => 'SP',
	    'Arabisch' => 'AR',
	    'Bulgarisch' => 'BG',
	    'Tschechisch' => 'CS',
	    'Dänisch' => 'DA',
	    'Niederländisch' => 'NL',
	    'Finnisch' => 'FI',
	    'Griechisch' => 'GR',
	    'Ungarisch' => 'HU',
	    'Japanisch' => 'JP',
	    'Koreanisch' => 'KO',
	    'Norwegisch' => 'NO',
	    'Polnisch' => 'PL',
	    'Portugiesisch' => 'PT',
	    'Brasilianisches Portugiesisch' => 'PT',
	    'Rumänisch' => 'RO',
	    'Russisch' => 'RU',
	    'Chinesisch (vereinfacht)' => 'CN',
	    'Schwedisch' => 'SV',
	    'Thai' => 'TH',
	    'Chinesisch (traditionell)' => 'CN',
	    'Türkisch' => 'TR',
	    'Ukrainisch' => 'UA',
	    'Chinesisch(vereinfacht)' => 'CN',
	    'BrasilianischesPortugiesisch' => 'PT',
	    'Chinesisch(traditionell)' => 'CN',
	    'Slowakisch' => 'SK',
	];
	if($country){
		if(isset($countries[$country])) return $countries[$country];
	} 
	else return $countries;
}

function get_steam_languages()
{
	$res = arrayDB("SELECT lang FROM steam_de");
	//sa($res);
	$genres = [];
	foreach ($res as $k => $v) {
		$genreses = explode(',', $v['lang']);
		foreach ($genreses as $val) {
			if($val && strpos($val, '#') === false && strpos($val, 'alle') === false){
				$genres[$val] = get_country_code($val);
			}
		}
	}
	return $genres;
}


function getSingleItem($itemId, $o = []){

		$c = array_merge([
			'as_array'=>false,
			'IncludeSelector'=>'Details', //Details,Description,ItemSpecifics,TextDescription
			],$o);

		$url = 'http://open.api.ebay.com/shopping';
		$url .= '?callname=GetSingleItem';
		$url .= '&responseencoding=JSON';
		// $url .= '&appid=Aniq6478a-a8de-47dd-840b-8abca107e57';
		$url .= '&appid=Konstant-Projekt1-PRD-bae576df5-1c0eec3d';
		$url .= '&siteid=77';
		$url .= '&version=515';
		$url .= '&ItemID='.$itemId;
		$url .= '&IncludeSelector='.$c['IncludeSelector'];


		// Открываем файл с помощью установленных выше HTTP-заголовков
		$json = file_get_contents($url);
		if($c['as_array']) return json_decode($json, true);
		return $json;
}

function os_shorter($oss)
{
	$ret = [];
	if(stripos($oss, 'win') !== false) $ret[] = 'Win';
	if(stripos($oss, 'mac') !== false) $ret[] = 'Mac';
	if(stripos($oss, 'linux') !== false) $ret[] = 'Linux';
	return implode(', ', $ret);
}

function hoodItemSync($items_arr = [])
{
	$myCurl = curl_init('http://hood.gig-games.de/api/import');
	curl_setopt_array($myCurl, [
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => http_build_query($items_arr)
	]);
	$response = curl_exec($myCurl);
	curl_close($myCurl);
	$ret = json_decode($response,1);

	foreach ($ret as $key => $element) {
		if($element['status'] === 'success'){
			$ebay_id = _esc($key);
			$hood_id = isset($element['idAuction']) ? $element['idAuction'] : '';
			arrayDB("UPDATE games SET hood_id= '$hood_id' WHERE ebay_id = '$ebay_id'");
			$check = arrayDB("SELECT * FROM hood_last_update WHERE ebay_id = '$ebay_id'");
			if ($check) {
				arrayDB("UPDATE hood_last_update SET last_update = CURRENT_TIMESTAMP WHERE ebay_id = '$ebay_id'");
			}else{
				arrayDB("INSERT INTO hood_last_update (ebay_id,last_update)VALUES($ebay_id,CURRENT_TIMESTAMP)");
			}
			
		}
	}
	return $ret;
}


function tab_active($get, $val){
	if(isset($_GET[$get]) && $_GET[$get] == $val) echo 'active';
}


function post_curl($url, $post){
	$ch = curl_init($url);
	curl_setopt_array($ch, [
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => http_build_query($post)
	]);
	$resp = curl_exec($ch);
	curl_close($ch);
	if ($decoded = json_decode($resp,1)) {
		return $decoded;
	}
	return $resp;
}



?>