<?php
require_once __DIR__.'/../config.php';

spl_autoload_register(function($class)
{
	include_once __DIR__ . '/classes/' . $class . '.class.php';
});

if (function_exists('apc_load_constants')) {
    function define_array($key, $arr)
    {   if (!apc_load_constants($key)) {
            apc_define_constants($key, $arr); } }
} else { function define_array($key, $arr)
    { foreach ($arr as $name => $value) define($name, $value); } }

//in your code you just write something like this:
define_array('CLASSES', Array(
			'QS' => 'QueryString',
			));

// функция для работы с SQLite3
function aqSqlite($query,$multiquery = false){
		
		$db = new SQLite3(__DIR__.'/../sqlite/mydb.db');
		$results = $db->query($query);
		$res = [];
		if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0) {
				while ($row = $results->fetchArray(SQLITE3_ASSOC)) { // SQLITE3_BOTH, SQLITE3_ASSOC, SQLITE3_NUM
						$res[] = $row;
				}
		}

		$db->close();
		return $res;
}

function aqMysqli($query, $multiquery = false){

		if(!$query) return DB::getInstance();
		if ($multiquery){

				$mysqli = new mysqli(db_HOST, db_USER, db_PASS, db_NAME);

				if ($mysqli->connect_errno) die ($mysqli->connect_error);

				$mysqli->set_charset( "utf8" );
				
				$res = $mysqli->multi_query($query);

				$mysqli->close();

				return $res;

		}else{

				if (stripos($query, 'select') === 0 || stripos($query, 'show') === 0 || stripos($query, 'describe') === 0) {
						return DB::getInstance()->get_results($query);
				}else{
						return DB::getInstance()->query($query);
				}
				

		}

}


function arrayDB($query = '', $multiquery = false){
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

function aqs_get_page_title()
{
	$action = isset($_GET['action']) ? 'P | ' . $_GET['action'] : 'Aqs-Parser';
	$action = str_replace('-', ' ', $action);
	return ucwords($action);
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
function writeExcel($file_path, $inputArr, $sheetIndex = 0){

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
//===================================================================================
// $inputArr = [1=>['A'=>'value']]
function saveExcel($load_file, $save_file, $inputArr, $sheetIndex = 0){

	if (!is_array($inputArr)) return;

	$Xlsvsfkii_Failik = PHPExcel_IOFactory::load($load_file);
	$Xlsvsfkii_Failik->setActiveSheetIndex($sheetIndex);

	foreach ($inputArr as $k1 => $row) {
		foreach ($row as $k2 => $cell) {
			$Xlsvsfkii_Failik->getActiveSheet()->setCellValue($k2.$k1, $cell);
		}
	}

	switch (strtolower(pathinfo($load_file)['extension'])) {
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
	$Zapisat->save($save_file);
	 
	unset($Xlsvsfkii_Failik);
	unset($Zapisat);
}
//===================================================================================
// возвращает двумерный массив с первыми CSV файла
function csvToArr($file_path='', $options = []){

		$config = array(
				'delimetr' => ';',
				'encoding' => 'utf-8',
				'max_str' => false,
				'del_first' => false,
				'output' => []
				);
		$c = array_merge ( $config, $options );
		$fh = fopen($file_path,'r') or die($php_errormsg);
		$res = []; $i = 0;
		
		while (!feof($fh)) {

				$str = fgetcsv($fh, 0, $c['delimetr']);

				$i++;
				if($c['del_first'] && $i === 1) continue;

				if(strtolower($c['encoding']) != 'utf-8' && $str) 
						foreach ($str as &$cell) 
								$cell = iconv($c['encoding'], 'UTF-8', $cell);


				$str2 = [];
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

function sa($array = [], $save = false){
	if ($save) return '<pre>' . print_r($array, true) . '</pre>';
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
		$extension = pathinfo($ret['result'], PATHINFO_EXTENSION);
		// если товар - текстовый файл
		if (strtolower($extension) === 'txt') {
			$ret['typegood'] = '3';
			$ret['result'] = file_get_contents($ret['result']);
			if($ret['result'] === false) $ret['success'] = false;
		}
	}

	return $ret;
}


function get_steam_key_from_text($text){
	// ищет от 3х до 5и групп по 5 символов разделенных дефисом
	if (preg_match('/[A-Z0-9]{3,5}(-[A-Z0-9]{5}){2,4}/', $text, $matches)) {
		return $matches[0];
	// ищет 5 групп по 4 символа разделенных дефисом
	}elseif(preg_match('/[A-Z0-9]{4}(-[A-Z0-9]{4}){4}/', $text, $matches)){
		return $matches[0];
	// ищет BK-4EX43E-QRRUBJ-CVR6XN-ZBY85N
	}elseif(preg_match('/[A-Z0-9]{2}(-[A-Z0-9]{6}){4}/', $text, $matches)){
		return $matches[0];
	}else{
		return $text;
	}
}


function get_urls_from_text($text){

    $found = preg_match_all('#\b(https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#ism', $text, $urls);
    $urls = $urls[1];
    $urls = array_unique($urls);
    foreach ($urls as &$urlll){
    	$urlll = preg_replace('/\?redeemer.*/', '', $urlll);
    	if(filter_var($urlll, FILTER_VALIDATE_URL)){
    		$urlll = '<a style="color: #d09c3c;" target="_blank" href="'.$urlll.'">'.$urlll.'</a>';
    	}
    }
    $urls = implode('<br>'.PHP_EOL, $urls);
    if ($found) {
    	return $urls;
    }else{
    	return $text;
    }
}

function get_messages_for_send_producr($ca = 'EN', $ebay_or_mail = 'mail'){

	if (is_trusted_country($ca)) $msg_email = get_text_template($ebay_or_mail, 'DE');

	else $msg_email = get_text_template($ebay_or_mail, $ca);

	if (!$msg_email) $msg_email = get_text_template($ebay_or_mail, 'EN');

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


function clean_ebay_title($title){
	$words_to_del = array(
		'(PC)',' PC ','-Region free-','Region free','Multilanguage','steam',
		'Multilang','Regfree','ENGLISH','-','–','&','Uplay','Game Of The Year Edition',
		'DLC','regfr','Add On','Addon',' goty','Regionfree',':',"’s","'s","'","’",'Uplay');
    return trim(preg_replace('/\s+/', ' ', str_ireplace( $words_to_del, ' ', $title)));
}


function clean_ebay_title2($title)
{
	$rr = ['/\sPC\s.+/i', '/\ssteam\sd.+/i', '/\ssteam\sli.+/i', '/\ssteam\seu.+/i'];
    $title_clean = preg_replace( $rr, ' ', $title);
    $title_clean = str_ireplace( 'Add-On', ' ', $title_clean);
    return trim(preg_replace('/\s+/', ' ', $title_clean));
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
function view($view, $data = []){

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


function add_comment_to_order($gig_order_id, $text, $notify = true){
	if($gig_order_id < 1) return false;

	 $text = _esc($text);
	if (is_array($gig_order_id)) {
		$gig_order_id = $gig_order_id[0];
		$gig_order_item_id = $gig_order_id[1];

		arrayDB("UPDATE ebay_order_items
		SET item_comment='$text'
		WHERE id='$gig_order_item_id'");
	}

	arrayDB("UPDATE ebay_orders
	SET comment='$text',
		ExecutionMethod='cancelled',
		`show`='yes'
	WHERE id='$gig_order_id'");

	if($notify) AutomaticGroupBot::sendMessage(date('H:i:s').' New order #'.$gig_order_id);
}


function add_comment_to_Woo_order($gig_order_id, $text, $notify = true){
	if($gig_order_id < 1) return false;

	 $text = _esc($text);
	if (is_array($gig_order_id)) {
		$gig_order_id = $gig_order_id[0];
		$gig_order_item_id = $gig_order_id[1];

		arrayDB("UPDATE woo_order_items
		SET item_comment='$text'
		WHERE id='$gig_order_item_id'");
	}

	arrayDB("UPDATE woo_orders
	SET comment='$text',
		ExecutionMethod='cancelled',
		`show`='yes'
	WHERE id='$gig_order_id'");

	if($notify) AutomaticGroupBot::sendMessage(date('H:i:s').' New order #'.$gig_order_id);
}


function date_shorter($date){
	if($date == 0) return '';
	return (new DateTime($date))->format('d-m-y H:i');
}


function date_shorter_dots($date){
	if($date == 0) return '';
	return (new DateTime($date))->format('d.m.y H:i');
}


function formula($rurprice,$exrate) {
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

	if(!$count) $count = (int)arrayDB("SELECT count(id) FROM `$table_name`")[0]['count(id)'];
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
	      <a href="?'.obj(QS)->SET('offset',$offset_prev)->SET('limit',$limit)->give().'" aria-label="Previous">
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
			$str .= '<li><a href="'.aqs_hrefR(['offset'=>$inoffset,'limit'=>$limit]).'" title="'.$a.'-'.$b.'">'.$num.'</a></li>';
		}
	}
	$str .= '<li>
	      <a href="?'.obj(QS)->SET('offset',$offset_next)->SET('limit',$limit)->give().'" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	  </ul><br><b>'.$epilog.'</b>'.
	'</nav>';

	echo $str;


	return $offset.','.$limit;
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


function clean_url_from_query($url){
	return preg_replace('/\?.*$/', '', $url);
}

function clean_steam_url($url){
	return preg_replace('#https?(://store\.steampowered\.com/.+?/.+?/).*#', "http$1", $url);
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
		    $price       = $result->items[$i]->price_usd;
		    $description = $result->items[$i]->description;

   			$nameLow = mb_convert_case($name, MB_CASE_LOWER, "UTF-8");
   			$descLow = mb_convert_case($description, MB_CASE_LOWER, "UTF-8");

			$bool1 = (stripos($nameLow,'free') !== false || stripos($nameLow,'row') !== false || stripos($nameLow,'bundle') !== false);
			$bool2 = (stripos($descLow,'free') !== false || stripos($descLow,'row') !== false || stripos($descLow,'bundle') !== false);
			$bool3 = ( stripos($nameLow,'ccount') === false 
					&& stripos($nameLow,'ккаунт') === false 
					&& stripos($nameLow,'cis') === false
					&& stripos($nameLow,' RU)') === false
					&& stripos($nameLow,' RU )') === false
					&& stripos($nameLow,'(steam gift | ru)') === false 
					&& stripos($nameLow,'(steam|rus)') === false 
					&& stripos($descLow,'украина') === false);

			$bool4 = BlackListFilter($blacklist,$itemID);
			$bool5 = BlackListFilter($blacksell,$sellID);

			if (($bool1 || $bool2) && $bool3 && $bool4 && $bool5) {

				$arrItem[$k] = array();	
	    		$arrItem[$k]['itemID'] = $itemID;
			    $arrItem[$k]['name'] = $name;
			    $arrItem[$k]['price'] = $price;
			    $arrItem[$k]['price_rur'] = $result->items[$i]->price_rur;
			    $arrItem[$k]['price_usd'] = $result->items[$i]->price_usd;
			    $arrItem[$k]['price_eur'] = $result->items[$i]->price_eur;
			    $arrItem[$k]['sellID'] = $sellID;
			    $k++;
			}

		} // for i
		return $arrItem;

} // getResultsFromApi()


// для функции uasort()
function _sortN($a,$b){
	if ((float)$a['price'] === (float)$b['price']) return 0;
    return ((float)$a['price'] < (float)$b['price']) ? -1 : 1;

 	// return (float)$a['price']-(float)$b['price']; // depricated
}


// ALTER TABLE `items`
// 	ADD COLUMN `item1_price_rur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item1_price`,
// 	ADD COLUMN `item1_price_usd` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item1_price_rur`,
// 	ADD COLUMN `item1_price_eur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item1_price_usd`,
// 	ADD COLUMN `item2_price_rur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item2_price`,
// 	ADD COLUMN `item2_price_usd` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item2_price_rur`,
// 	ADD COLUMN `item2_price_eur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item2_price_usd`,
// 	ADD COLUMN `item3_price_rur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item3_price`,
// 	ADD COLUMN `item3_price_usd` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item3_price_rur`,
// 	ADD COLUMN `item3_price_eur` DECIMAL(6,2) NOT NULL DEFAULT '0.00' AFTER `item3_price_usd`;
function _savePaltiRuToBase(&$arrItem, &$game_id, &$scan, $table = 'items', $operation = 'update'){

	$item1_id = 0; $item1_name = 'No results'; $item1_price = 0; $item1_desc = 'No results';
	$item2_id = 0; $item2_name = 'No results'; $item2_price = 0; $item2_desc = 'No results';
	$item3_id = 0; $item3_name = 'No results'; $item3_price = 0; $item3_desc = 'No results';
	$item1_price_rur = 0; $item2_price_rur = 0; $item3_price_rur = 0;
	$item1_price_usd = 0; $item2_price_usd = 0; $item3_price_usd = 0;
	$item1_price_eur = 0; $item2_price_eur = 0; $item3_price_eur = 0;

	usort ($arrItem, '_sortN');
	if (isset($arrItem[0])) {
		$item1_id    = _esc(trim(strip_tags($arrItem[0]['itemID'])));
		$item1_name  = _esc(trim(strip_tags($arrItem[0]['name'])));
		$item1_price = _esc(trim(strip_tags($arrItem[0]['price'])));
		$item1_desc  = _esc(trim(strip_tags($arrItem[0]['sellID'])));
		$item1_price_rur = _esc(trim(strip_tags($arrItem[0]['price_rur'])));
		$item1_price_usd = _esc(trim(strip_tags($arrItem[0]['price_usd'])));
		$item1_price_eur = _esc(trim(strip_tags($arrItem[0]['price_eur'])));
	}
	if (isset($arrItem[1])) {
		$item2_id    = _esc(trim(strip_tags($arrItem[1]['itemID'])));
	 	$item2_name  = _esc(trim(strip_tags($arrItem[1]['name'])));
		$item2_price = _esc(trim(strip_tags($arrItem[1]['price'])));
		$item2_desc  = _esc(trim(strip_tags($arrItem[1]['sellID'])));
		$item2_price_rur = _esc(trim(strip_tags($arrItem[1]['price_rur'])));
		$item2_price_usd = _esc(trim(strip_tags($arrItem[1]['price_usd'])));
		$item2_price_eur = _esc(trim(strip_tags($arrItem[1]['price_eur'])));
	}
	if (isset($arrItem[2])) {
		$item3_id    = _esc(trim(strip_tags($arrItem[2]['itemID'])));
	 	$item3_name  = _esc(trim(strip_tags($arrItem[2]['name'])));
		$item3_price = _esc(trim(strip_tags($arrItem[2]['price'])));
		$item3_desc  = _esc(trim(strip_tags($arrItem[2]['sellID'])));
		$item3_price_rur = _esc(trim(strip_tags($arrItem[2]['price_rur'])));
		$item3_price_usd = _esc(trim(strip_tags($arrItem[2]['price_usd'])));
		$item3_price_eur = _esc(trim(strip_tags($arrItem[2]['price_eur'])));
	}

	$set = "SET game_id     = '$game_id',
				item1_id    = '$item1_id',
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
				item3_desc  = '$item3_desc',
				 item1_price_rur = '$item1_price_rur',
				 item1_price_usd = '$item1_price_usd',
				 item1_price_eur = '$item1_price_eur',
				 item2_price_rur = '$item2_price_rur',
				 item2_price_usd = '$item2_price_usd',
				 item2_price_eur = '$item2_price_eur',
				 item3_price_rur = '$item3_price_rur',
				 item3_price_usd = '$item3_price_usd',
				 item3_price_eur = '$item3_price_eur'";

	$exist = arrayDB("SELECT id from $table where game_id = '$game_id' order by id desc");
	if ($exist && $operation === 'update') {
		$id = $exist[0]['id'];
		$query = "UPDATE $table	$set WHERE game_id = '$game_id'";
		arrayDB($query);
	}else{
		$query = "INSERT INTO $table $set, scan = '$scan'";
		arrayDB($query);
	}
	return $query;
}


function _requestFilter($request){
    $request = str_ireplace([':',"'s","'",'!','.','®','™','’s','Sid Meier'], ' ', $request);
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

	$specifics['Downloade Site'] = 'steampowered';

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

	if($steam_arr['year']) $specifics['Erscheinungsjahr'] = $steam_arr['year'];

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


// используется в getjson-steam-de
function get_steam_miracle_where($table = 'steam_de'){
	return "(old_price > 1.5 OR reg_price > 1.5)
		AND item1_price > 0 
		AND `$table`.ebay_id = '' 
		AND IF(old_price > 0, old_price, reg_price)*0.9 > ROUND(((steam_items.item1_price/(select value from aq_settings where name = 'exrate'))*1.00952+0.4165)/(1-(0.1+0.019+0.078)*1.19),2)";
}
// используется в steam-list
function get_steam_miracle_where2($table = 'steam_de'){
	return "(old_price > 1.5 OR reg_price > 1.5)
		AND item1_price > 0 
		AND link NOT IN(select steam_link from games where steam_link <>'')
		AND IF(old_price > 0, old_price, reg_price)*0.9 > ROUND(((steam_items.item1_price/(select value from aq_settings where name = 'exrate'))*1.00952+0.4165)/(1-(0.1+0.019+0.078)*1.19),2)";
}


function add_dlc_addon_to_desc($steam_arr, $lang = false){

	$dlc_texts = [
		'de' => 'Dieses Produkt benötigt zum Spielen die Steam-Version des Hauptspiels',
		'en' => 'This content requires the base game',
		'fr' => 'Questo contenuto ha bisogno del gioco di base',
		'es' => 'Este contenido requiere el juego base',
		'it' => 'Ce contenu nécessite le jeu de base',
	];

	$sub_texts = [
		'de' => 'In diesem Paket enthaltenen Artikel',
		'en' => 'Items included in this package',
		'fr' => 'Articles inclus dans ce package',
		'es' => 'Artículos incluidos en este pack',
		'it' => 'Oggetti inclusi in questo pacchetto',
	];

	if(!isset($dlc_texts[$lang])) $lang = 'de';
	
	if ($steam_arr['main_game_link']) {
		// $l = '<h4 class="gig-dlc-hh">'.$dlc_texts[$lang].' <a target="_blank" href="'.$steam_arr['main_game_link'].'">'.$steam_arr['main_game_title'].'.</a></h4>';
		$l = '<h4 class="gig-dlc-hh">'.$dlc_texts[$lang].' <a>'.$steam_arr['main_game_title'].'.</a></h4>';
		$steam_arr['desc'] = $l.$steam_arr['desc'];

	}elseif ($steam_arr['includes']) {
		$includes_arr = explode(',', $steam_arr['includes']);
		$titles_arr = explode('<br>', $steam_arr['desc']);
		$x = '<h4 class="gig-sub-hh">'.$sub_texts[$lang].':</h4>';
		foreach ($includes_arr as $k => $sid) {
			// $x .= '<a class="gig-sub-link" target="_blank" href="http://store.steampowered.com/app/'.$sid.'/">'.$titles_arr[$k].'</a><br>';
			$x .= '<a class="gig-sub-link">'.$titles_arr[$k].'</a><br>';
		}
		$steam_arr['desc'] = $x;
	}

	return $steam_arr['desc'];
}


function add_notice_to_desc($steam_arr)
{
	if(!$steam_arr['notice']) return '';
	return '<div class="gig-notice">' . str_replace(['\r\n',"\r\n"], '<br>', $steam_arr['notice']) . '</div><!--notice-end-->';
}


function getOrderArray($o = []){

	$c = array_merge(['NumberOfDays'=>1,'SortingOrder'=>'Ascending','PageNumber'=>'1'],$o);

	$ord_obj = new EbayOrders;

	$ord_arr = $ord_obj->getOrders($c);

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


function is_eve($ebay_id)
{
	return in_array($ebay_id, [ '121962440805',
								'121962439324',
								'112090853589',
								'112353147792',]);
}


function is_eve_by_title($title='')
{
	foreach ([
		' plex',
		'milliarden isk',
		'skill injector'
	] as $eve_sub) {
		if(stripos($title, $eve_sub) !== false) return true;
	}
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


function get_item_specifics($ebay_id, $name_keys = false)
{
	//'IncludeSelector'=> Details,Description,ItemSpecifics,TextDescription
	$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'ItemSpecifics']);

	$specs = array_values(array_filter($res['Item']['ItemSpecifics']['NameValueList'], function($item){

		if (strpos($item['Name'], 'Rück') !== false) return false;
		return true;
	}));

	if ($name_keys) {
		$specs_keys = [];
		foreach ($specs as $val) {
			$specs_keys[$val['Name']] = $val['Value'];
		}
		return $specs_keys;
	}

	return $specs;
}


function parse_item_specifics($item_id='')
{
	if(!$item_id) return false;
	$str = file_get_contents('http://www.ebay.de/itm/'.$item_id);
	$dom = str_get_html($str);
	if(!$dom) return false;
	$res = $dom->find('.attrLabels');
	if(!$res) return false;
	$specs = [];
	foreach ($res as $key => $value) {
		$specs[str_replace(':','',trim($value->plaintext))] = trim($value->nextSibling()->plaintext);
	}
	unset($specs['Artikelzustand']);
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
	    'Chinesisch (traditionell)' => 'CN',
	    'Slowakisch' => 'SK',
	];

	if(!$country) return $countries;

	if(isset($countries[$country])) return $countries[$country];
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

		global $_ERRORS;
		$c = array_merge([
			'as_array'=>false,
			'IncludeSelector'=>'Details', //Details,Description,ItemSpecifics,TextDescription
			],$o);

		$url = 'http://open.api.ebay.com/shopping';
		$url .= '?callname=GetSingleItem';
		$url .= '&responseencoding=JSON';
		$url .= '&appid='.EBAY_API_KEY;
		$url .= '&siteid=77';
		$url .= '&version=515';
		$url .= '&ItemID='.$itemId;
		$url .= '&IncludeSelector='.$c['IncludeSelector'];


		// Открываем файл с помощью установленных выше HTTP-заголовков
		$json = file_get_contents($url);
		// sa(json_decode($json, true));
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


function tab_active($get, $val, $css_class = 'active'){
	if(isset($_GET[$get]) && $_GET[$get] == $val) echo $css_class;
}


function post_curl($url, $post = []){
	$ch = curl_init($url);
	curl_setopt_array($ch, [
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_POST => true,
	    CURLOPT_POSTFIELDS => http_build_query($post)
	]);
	$resp = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($resp,1);
	if ($decoded !== null) {
		return $decoded;
	}
	return $resp;
}

function round_hood_price($price)
{
	if (!$price) return false;

    $round = array(
        1 => 0, 2 => 0, 3 => 5, 4 => 5, 5 => 5,
        6 => 5, 7 => 5, 8 => 9, 9 => 9, 0 => 0,
    );
	$price = round($price, 2);
	if ($price < 5) $price = $price - 0.05;
	else			$price = $price * 0.99;
	
	$price = number_format($price, 2);
	$price = substr_replace($price, $round[+$price[strlen($price)-1]],-1);
	return $price;
}


function add_hours($date, $hours)
{
	return (new DateTime($date))
		->add(date_interval_create_from_date_string($hours.' hour'))
		->format('Y-m-d H:i:s');
}


function hood_date_format($time_string='')
{
	if(!$time_string) return '0000-00-00 00:00:00';

	$time_string = str_replace(['um',"\r\n",'.'], ['','','-'], $time_string);

	$dt = DateTime::CreateFromFormat("d-m-y H:i:s", $time_string);
	return $dt->format('Y-m-d H:i:s');
}

function draw_messages_submenu($page = 'ebay')
{
?>
<hr><div class="op-tab-navigator">
	<div class="op-tab <?= ($page === 'ebay')?'active':'';?>">
		<a href="?action=ebay-messages&show=all" class="op-tab-link">
			<span class="glyphicon glyphicon-envelope"></span>
			eBay messages
		</a>
		<a href="?action=ebay-messages&show=not_answerd" class="js-ebay-count msg-count-badge">.</a>
	</div>
	<div class="op-tab <?= ($page === 'hood')?'active':'';?>">
		<a href="?action=hood-messages&show=all" class="op-tab-link">
			<span class="glyphicon glyphicon-envelope"></span>
			Hood messages
		</a>
		<a href="?action=hood-messages&show=not_answerd" class="js-hood-count msg-count-badge">.</a>
	</div>
	<div class="op-tab <?= ($page === 'email')?'active':'';?>">
		<a href="?action=ebay-messages" class="op-tab-link">
			<i class="glyphicon glyphicon-envelope">&nbsp;</i>
			Email messages
		</a>
	</div>
</div><hr>
<?php
}


// возвращает результаты с ПОСЛЕДНЕГО ПАРСА (может не вернуть ничего если парс не полный)
function get_suitables($ebay_id = ''){
    if(!$ebay_id) return [];
    return arrayDB("SELECT * FROM (SELECT * FROM items WHERE scan = (select scan from items order by id desc limit 1)) as its
    JOIN games
    ON games.id=its.game_id
    WHERE ebay_id = '$ebay_id'");
}

// в отличие от get_suitables, эта версия берет ПОСЛЕДНИЙ ДОСТУПНЫЙ РЕЗУЛЬТАТ
function get_suitables2($ebay_id = ''){
    if(!$ebay_id) return [];
    $games = arrayDB("SELECT id FROM games WHERE ebay_id = '$ebay_id'");
    $ret = [];
    foreach ($games as $game) {
    	$game_id = $game['id'];
    	$ret = array_merge($ret,  arrayDB("SELECT * FROM items WHERE game_id = '$game_id' ORDER BY id DESC LIMIT 1"));
    }
    return $ret;
}

// в отличие от get_suitables, эта версия берет ПОСЛЕДНИЙ ДОСТУПНЫЙ РЕЗУЛЬТАТ
function get_suitables2_Woo($product_id = ''){
    if(!$product_id) return [];
    $games = arrayDB("SELECT id FROM games WHERE woo_id = '$product_id'");
    $ret = [];
    foreach ($games as $game) {
    	$game_id = $game['id'];
    	$res = arrayDB("SELECT * FROM items WHERE game_id = '$game_id' ORDER BY id DESC LIMIT 1");
    	if($res) $ret[] = $res[0];
    }
    return $ret;
}


function draw_unread()
{
return '<div href="?action=ebay-messages&show=not_answerd" class="all-msg-count-badge">
            <a href="?action=ebay-messages&show=not_answerd" class="js-ebay-count" title="ebay messages">.</a> /
            <a href="?action=hood-messages&show=not_answerd" class="js-hood-count" title="hood messages">.</a>
        </div>';
}


function get_table_name($query='')
{	
	if (stripos($query, 'select') === false) return false;
    $res = preg_match('/from.*?\b(\S+)\b/', $query, $result);
    if(isset($result[1])) return(trim($result[1]));
    else return false;
}


function _op_href2($arr = []){
	$qs_obj = new QueryString();
	foreach ($arr as $key => $val) $qs_obj->set($key,$val);
	echo '?'.$qs_obj->give();
}


function op_tab_navigator()
{ ?>
<div class="op-tab-navigator">
	<div class="op-tab <?php tab_active('list_type','all');?>">
		<a class="op-tab-link" href="<?php _op_href2(['action'=>'orders-page','list_type'=>'all','q'=>'0','order_id'=>'0','modal_type'=>'info']);?>">
			<i class="glyphicon glyphicon-star">&nbsp;</i>
			All orders
		</a>
	</div>
	<div class="op-tab <?php tab_active('list_type','paid');?>">
		<a class="op-tab-link" href="<?php _op_href2(['action'=>'orders-page','list_type'=>'paid','q'=>'0','order_id'=>'0','modal_type'=>'info']);?>">
			<i class="glyphicon glyphicon-euro">&nbsp;</i>
			Paid orders
		</a>
	</div>
	<div class="op-tab <?php tab_active('list_type','shipped');?>">
		<a class="op-tab-link" href="<?php _op_href2(['action'=>'orders-page','list_type'=>'shipped','q'=>'0','order_id'=>'0','modal_type'=>'info']);?>">
			<i class="glyphicon glyphicon-euro">&nbsp;</i>
			Shipped orders
		</a>
	</div>
	<div class="op-tab <?php tab_active('action','hood-orders');?>">
		<a class="op-tab-link" href="?action=hood-orders&offset=0&limit=100">
			<i class="glyphicon glyphicon-euro">&nbsp;</i>
			Hood orders
		</a>
	</div>
</div>
<?php }


function get_top_sales($options)
{
	$default = [
				'limit' => '10',
			];
	$c = array_merge ( $default, $options );

	$limit = (int)$c['limit'];
	return arrayDB("SELECT tt.*, ebay_games.title_clean, ebay_games.picture_hash, steam_de.ebay_price
		FROM (select title,price,ebay_id,shipped_time,count(*) as count 
				from ebay_order_items
				where shipped_time > NOW() - INTERVAL 1 MONTH
				group by ebay_id) tt
	JOIN ebay_games
	ON tt.ebay_id = ebay_games.item_id
	JOIN steam_de
	ON tt.ebay_id = steam_de.ebay_id
	WHERE picture_hash <> ''
	order by count desc
	limit $limit");
}


function one_month_top($limit = 10)
{
	return get_top_sales(['limit'=>$limit]);
}


function get_top_by_genre($genre, $limit = 10)
{
	$genre = _esc($genre);
	$limit = (int)$limit;
	return arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							genres,
							o_reviews
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND genres LIKE '%$genre%' 
		ORDER BY o_reviews DESC LIMIT $limit");
}


function get_top_2015($limit = 10)
{
	$limit = (int)$limit;
	return arrayDB("SELECT  ebay_games.title_clean, 
							ebay_games.picture_hash, 
							steam_de.ebay_price,
							steam_de.ebay_id,
							genres
		FROM steam_de
		JOIN ebay_games
		ON steam_de.ebay_id = ebay_games.item_id
		WHERE ebay_id <> '' AND instock = 'yes' AND picture_hash <> '' AND year = 2015 
		ORDER BY o_reviews DESC LIMIT $limit");
}


function fill_email_item_panel($msg_email = false, $top_arr = [])
{
	if(!$msg_email) return false;

	if(!$top_arr) $top_arr = one_month_top();
	if(!$top_arr) return $msg_email;

	$msg_email = str_ireplace(['{{PRICE1}}','{{PRICE2}}','{{PRICE3}}','{{PRICE4}}'],
					[$top_arr[0]['price'],$top_arr[1]['price'],$top_arr[2]['price'],$top_arr[3]['price']], $msg_email);
	$msg_email = str_ireplace(['{{IMAGE1}}','{{IMAGE2}}','{{IMAGE3}}','{{IMAGE4}}'],
					[$top_arr[0]['picture_hash'],$top_arr[1]['picture_hash'],$top_arr[2]['picture_hash'],$top_arr[3]['picture_hash']], $msg_email);
	$msg_email = str_ireplace(['{{TITLE1}}','{{TITLE2}}','{{TITLE3}}','{{TITLE4}}'],
					[$top_arr[0]['title'],$top_arr[1]['title'],$top_arr[2]['title'],$top_arr[3]['title']], $msg_email);
	$msg_email = str_ireplace(['{{EBAY_ID1}}','{{EBAY_ID2}}','{{EBAY_ID3}}','{{EBAY_ID4}}'],
					[$top_arr[0]['ebay_id'],$top_arr[1]['ebay_id'],$top_arr[2]['ebay_id'],$top_arr[3]['ebay_id']], $msg_email);

	return $msg_email;
}


function get_email_slug(){

	return time().'-'.md5(rand(101,998));
}


function product_html($title, $product){
	
	return '<div style="color:#feb32c;font-weight:bold;margin-bottom:5px;padding-left:10px">'.$title.':</div>
			<div style="color:#fff;margin-bottom:10px;padding-left:10px">'.$product.'</div>';
}


function get_item_price_from_ebay_results($itemid)
{
	if(!$itemid) return 0;
	$option = [];
	$option[] = @arrayDB("SELECT price1 as price,`time` FROM ebay_results WHERE itemid1 = '$itemid' order by id desc limit 1")[0];
	$option[] = @arrayDB("SELECT price2 as price,`time` FROM ebay_results WHERE itemid2 = '$itemid' order by id desc limit 1")[0];
	$option[] = @arrayDB("SELECT price3 as price,`time` FROM ebay_results WHERE itemid3 = '$itemid' order by id desc limit 1")[0];
	$option[] = @arrayDB("SELECT price4 as price,`time` FROM ebay_results WHERE itemid4 = '$itemid' order by id desc limit 1")[0];
	$option[] = @arrayDB("SELECT price5 as price,`time` FROM ebay_results WHERE itemid5 = '$itemid' order by id desc limit 1")[0];

	$fresh = 0;
	$price = 0;
	foreach ($option as $key => $value) {
	  if (isset($value['time']) && (new DateTime($value['time']))->getTimestamp() > $fresh) {
	    $fresh = (new DateTime($value['time']))->getTimestamp();
	    $price = $value['price'];
	  }
	}
	return $price;
}


function get_item_price_from_ebay_orders($itemid)
{
	if(!$itemid) return 0;
	$itemid = _esc($itemid);
	$res = arrayDB("SELECT price FROM ebay_order_items WHERE ebay_id = '$itemid' ORDER BY id DESC LIMIT 1");

	$price = 0;
	if ($res) $price = $res[0]['price'];
	return $price;
}


function ajax_recovery_item($itemid, $steam_link) // запись со steam_de
{
	$ret = [];

	$steam_link = _esc($steam_link);

	$steam_de = arrayDB("SELECT steam_de.*,steam.usk_links as pegi_links,steam.usk_age as pegi_age 
						FROM steam_de
						LEFT JOIN steam
						ON steam_de.link = steam.link
						WHERE steam_de.link = '$steam_link' LIMIT 1");

	if ($steam_de) {
		$steam_de = $steam_de[0];
	}else{
		return ['success' => 0, 'resp' => 'no steam_de!'];
	}

	$item = [
	    'Title' => 'Название',
	    'CategoryID' => '139973',
	    'Quantity' => 3,
	    'ConditionID' => 1000,
	    'Currency' => 'EUR', 
	    'Description' => 'Дескрипшн',
	    'price' => '9.99',
	    'PictureURL' => [],
	    'BestOfferEnabled' => 'true',
	    'SalesTaxPercent' => 0,
	    'ListingDuration' => 'GTC',
	    'specific' => [],
	    'StoreCategory1' => '10866044010',
	];

	// Цена товара
	$item['price'] = get_item_price_from_ebay_results($itemid);

	if (!$item['price']) get_item_price_from_ebay_orders($itemid);

	if (!$item['price']){
		arrayDB("UPDATE games SET extra_field = 'no item price' WHERE steam_link = '$steam_link'");
		return ['success' => 0, 'resp' => 'no item price!'];
	} 

	$eBay_obj = new Ebay_shopping2();

	$app_id = $steam_de['appid'];
	$app_sub = $steam_de['type'];
	if($app_sub === 'dlc') $app_sub = 'app';
	$img_generator_url = 'http://hot-body.net/img-generator/?app_id='.$app_id.'&app_sub='.$app_sub.'&ramka_september_2017=true';
	$img_generator_res = file_get_contents($img_generator_url);
	$img_generator_res = json_decode($img_generator_res,1);
	if (!$img_generator_res['msg']) {
		arrayDB("UPDATE games SET extra_field = 'no image' WHERE steam_link = '$steam_link'");
		return ['success' => 0, 'resp' => 'img generator error!', 'img_gen_resp' => $img_generator_res, 'img_generator_url' => $img_generator_url];
	}

	// steam-images checker
	$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
	$chr = json_decode($checker, true);

	// Ниазвание товара
	$item['Title'] = add_words_to_game_name($steam_de['title']);

	// Картинки
	$item['PictureURL'][] = $img_generator_res['image_link'];
	if (in_array('big1.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big1.jpg';
	if (in_array('big2.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big2.jpg';
	if (in_array('big3.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big3.jpg';
	if (in_array('big4.jpg', $chr))	$item['PictureURL'][] = 'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/big4.jpg';


	// Описание товара
	$desc_obj = new CreateDesc2017($itemid);

	if (!$desc_obj->getSteamLink2())	return ['success' => 0, 'resp' => 'no steam link',
		'text' => $desc_obj->error_text, 'sl' => $desc_obj->_steam_link];

	$desc_obj->setImagesArr([
			in_array('small1.jpg',$chr)?'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small1.jpg':'http://parser.gig-games.de/images/no-image-available.png',
			in_array('small2.jpg',$chr)?'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small2.jpg':'http://parser.gig-games.de/images/no-image-available.png',
			in_array('small3.jpg',$chr)?'http://parser.gig-games.de/steam-images/'.$app_sub.'s-'.$app_id.'/small3.jpg':'http://parser.gig-games.de/images/no-image-available.png',
		]);

	if (!$desc_obj->readSteamDe())  return ['success' => 0, 'resp' => 'no readSteamDe'];
	if (!$desc_obj->readSteamEn())  return ['success' => 0, 'resp' => 'no readSteamEn'];
	if (!$desc_obj->readSteamFr())	return ['success' => 0, 'resp' => 'no readSteamFr'];
	if (!$desc_obj->readSteamEs())	return ['success' => 0, 'resp' => 'no readSteamEs'];
	if (!$desc_obj->readSteamIt())	return ['success' => 0, 'resp' => 'no readSteamIt'];

	if (!$desc_obj->getDataArray())	return ['success' => 0, 'resp' => 'no getDataArray!'];

	if(!$desc = $desc_obj->getNewFullDesc()) return ['success' => 0, 'resp' => 'no getNewFullDesc!'];
	$item['Description'] = $desc;

	// Спецификации
	$item['specific'] = build_item_specifics_array($steam_de);

	$res = $eBay_obj->addItem($item);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure' && isset($res['ItemID'])) {
		$id = $steam_de['id'];

		$new_ebay_id = _esc($res['ItemID']);
		// НАДО ПОДУМАТЬ
		arrayDB("UPDATE games SET ebay_id = '$new_ebay_id' WHERE old_ebay_id = '$itemid'");

		$success = 1;
	}else{
		$success = 0;
	}
	unset($res['Fees']);
	unset($item['Description']);
	global $_ERRORS;
	return ['success' => $success,
			'item' => $item,
			'resp' => $res,
			'errors' => $_ERRORS];
}


function temp_sep($need_id, &$games)
{
	foreach ($games as $game) {
		if ($game['ebay_id'] === $need_id) {
			return '';
		}
	}
	return 'highlight" title="игра не закреплена в системе';
}


function getSslPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


function isItemOnPlati($item_id)
{	
	$page = getSslPage('https://plati.market/itm/'.$item_id);
	$dom = str_get_html($page);
	$is_class_there = $dom->find('.goods_order_form_quanuty');
	return(!!$is_class_there);
}



function _get_files($dir='.')
{
	$files = [];
	if ($handle = opendir($dir)){
		while(false !== ($item = readdir($handle))){
			if (is_file("$dir/$item")) {
				$files[] = "$dir/$item";
			}elseif (is_dir("$dir/$item") && ($item != ".") && ($item != "..")){
				$files = array_merge($files, get_filess("$dir/$item"));
			}
		}
		closedir($handle);
	}
	return $files; 
}


function send_identify_message(&$order, $country_alias)
{
	$email = $order['BuyerEmail'];
	$check = arrayDB("SELECT id FROM ebay_orders WHERE BuyerEmail = '$email' AND is_notified = 'yes' AND ExecutionMethod = 'manually'");
	if ($check) return false;
	// германия, австрия, швейцария, люксембург, нидерланды
	if (is_trusted_country($country_alias)) {
		$identify_msg_body = get_text_template('identify_messages', 'DE');
	}else{
		$identify_msg_body = get_text_template('identify_messages', 'EN');
	}

	$gig_order_id = (int)$order['gig_order_id'];
	arrayDB("UPDATE ebay_orders SET is_notified = 'yes' WHERE id = '$gig_order_id'");

	return (new EbayOrders())->SendMessage($order['BuyerUserID'], $order['ebay_id'], 'Please identify yourself', htmlspecialchars($identify_msg_body));
}


function is_trusted_country($country_alias = ''){

	return in_array($country_alias, [
		'DE', // германия
		'AT', // австрия
		'CH', // швейцария
		'NL', // нидерланды
		'LU', // люксембург
	]);
}


function aqs_pagination_api($offset, $limit, $count, $opts = []){

	$c = array_merge ( [
			'visible_pages' => 5,
	], $opts );

	if($limit > 500) $limit = 500;
	$offset_prev = $offset - $limit;
	if($offset_prev < 0) $offset_prev = 0;
	$offset_next = $offset + $limit;
	if($offset_next > $count) $offset_next = $offset;
	//var_dump($count/$limit);
	$str =
	'<ul class="pagination pagination-sm">
	    <li>
	      <a title="'.$offset_prev.'">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>';
	for ($i=-$c['visible_pages']; $i <= $c['visible_pages']; $i++) {
		$inoffset = $offset + ($limit * $i);
		$num = floor($inoffset / $limit + 1);
		$b = $count - $inoffset;
		$a = $b - $limit + 1;
		if($a < 0) $a = 1;
		if ($inoffset < 0) {
			continue;
		}elseif($inoffset > $count) {
			break;
		}elseif($inoffset == $offset) {
			$str .= '<li class="active"><a class="curren" title="current page">'.$num.'</a></li>';
		}else{
			$str .= '<li><a title="'.$inoffset.'">'.$num.'</a></li>';
		}
	}
	$str .= '<li>
	      <a title="'.$offset_next.'">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	  </ul>';

	return $str;
}


function get_username_with_IP($ip = 0)
{	
	if($ip) return substr(md5($ip), 0, 10);
	return substr(md5($_SERVER['REMOTE_ADDR']), 0, 10);
}


function insert_filter_log($action = '')
{
	// не логируем мой IP
	if($_SERVER['REMOTE_ADDR'] === '37.46.229.203') return false;
	$user = get_username_with_IP();
	if ($action === 'enter') {
		arrayDB("INSERT INTO filter_log (user,enter) VALUES ('$user','enter')");
	}
	if ($action === 'query') {
		if ($_POST['search']) {
			$search = _esc($_POST['search']);
			arrayDB("INSERT INTO filter_log (user,search) VALUES ('$user','$search')");
			return;
		}
		$genres = '';
		if(@$_POST['fields']['genres']) $genres = _esc(implode(',', $_POST['fields']['genres']));
		$tags = '';
		if(@$_POST['fields']['tags']) $tags = _esc(implode(',', $_POST['fields']['tags']));
		$specs = '';
		if(@$_POST['fields']['specs']) $specs = _esc(implode(',', $_POST['fields']['specs']));
		$langs = '';
		if(@$_POST['fields']['lang']) $langs = _esc(implode(',', $_POST['fields']['lang']));
		$os = '';
		if(@$_POST['fields']['os']) $os = _esc(implode(',', $_POST['fields']['os']));
		$app_dlc = '';
		if($_POST['type']) $app_dlc = _esc($_POST['type']);
		$year = $_POST['year']>0?("'"._esc($_POST['year'])."'"):'null';
		$year_from = $_POST['year_from']>0?("'"._esc($_POST['year_from'])."'"):'null';
		$year_to = $_POST['year_to']>0?("'"._esc($_POST['year_to'])."'"):'null';
		$reviews_from = isset($_POST['max_reviews'][0])?("'"._esc(@$_POST['max_reviews'][0])."'"):'null';
		$reviews_to = isset($_POST['max_reviews'][1])?("'"._esc(@$_POST['max_reviews'][1])."'"):'null';
		$rating_from = isset($_POST['rating'][0])?("'"._esc(@$_POST['rating'][0])."'"):'null';
		$rating_to = isset($_POST['rating'][1])?("'"._esc(@$_POST['rating'][1])."'"):'null';
		$our_price_from = isset($_POST['our_price'][0])?("'"._esc(@$_POST['our_price'][0])."'"):'null';
		$our_price_to = isset($_POST['our_price'][1])?("'"._esc(@$_POST['our_price'][1])."'"):'null';
		$steam_price_from = isset($_POST['steam_price'][0])?("'"._esc(@$_POST['steam_price'][0])."'"):'null';
		$steam_price_to = isset($_POST['steam_price'][1])?("'"._esc(@$_POST['steam_price'][1])."'"):'null';
		$advantage_from = isset($_POST['advantage'][0])?("'"._esc(@$_POST['advantage'][0])."'"):'null';
		$advantage_to = isset($_POST['advantage'][1])?("'"._esc(@$_POST['advantage'][1])."'"):'null';
		$search = _esc($_POST['search']);
		$sorting = _esc($_POST['order_by']);
		$sql = "INSERT INTO filter_log (
			user,
			genres,
			tags,
			specs,
			app_dlc,
			langs,
			os,
			year,
			year_from,
			year_to,
			reviews_from,
			reviews_to,
			rating_from,
			rating_to,
			our_price_from,
			our_price_to,
			steam_price_from,
			steam_price_to,
			advantage_from,
			advantage_to,
			search,
			sorting
		) VALUES (
			'$user',
			'$genres',
			'$tags',
			'$specs',
			'$app_dlc',
			'$langs',
			'$os',
			$year,
			$year_from,
			$year_to,
			$reviews_from,
			$reviews_to,
			$rating_from,
			$rating_to,
			$our_price_from,
			$our_price_to,
			$steam_price_from,
			$steam_price_to,
			$advantage_from,
			$advantage_to,
			'$search',
			'$sorting')";
		arrayDB($sql);
		// sa($sql);
	}
	if ($action === 'game') {
		$ebay_id = _esc($_POST['ebay_id']);
		$ebay_title = _esc($_POST['ebay_title']);
		arrayDB("INSERT INTO filter_log (user,ebay_id,ebay_title) VALUES ('$user','$ebay_id','$ebay_title')");
	}
}


function get_filter_chart_json()
{
	$res = arrayDB("SELECT DATE_FORMAT(created_at, '%d-%m') as `date`, user, enter, ebay_id FROM filter_log WHERE created_at > NOW() - INTERVAL 27 DAY");

	$ret = [['date','enters','clicks']];

	$temp = [];
	foreach ($res as $k => $val) {
		$temp[$val['date']]['users'][$val['user']] = 1;
		if ($val['ebay_id']) {
			@$temp[$val['date']]['clicks'] += 1;
		}
	}
	foreach ($temp as $date => $v) {
		$ret[] = [$date, count($v['users']), $v['clicks']];
	}
	return json_encode($ret);
}


function draw_table_with_sql_results($res, $first_row_thead = false)
{
	if (is_array($res) && $res) {
		echo '<table class="ppp-table-collapse"><tr>';
		if($first_row_thead){
			echo '<th>№</th>';
			foreach ($res[0] as $key => $value) echo "<th>",$key,"</th>";
		}else{
			echo '<td>1</td>';
			foreach ($res[0] as $val) echo "<td>",$val,"</td>";
		}
		echo "</tr>";
		foreach ($res as $kr => $row) {
			if(!$first_row_thead && $kr === 0) continue;
			echo '<tr><td>',$kr+1,'</td>';
			foreach ($row as $kc => $col) {
				echo '<td>',$col,'</td>';
			}
			echo '</tr>';
		}
	}else{
		print_r($res);
	}
}


// функция для страницы cdvet price checker и cdvet quantity updater
function pc_add_to_items_arr(&$items_arr, $elements_arr)
{
	$last_index = count($items_arr) - 1;

	if($items_arr && count($items_arr[$last_index]) < 4){
		$items_arr[$last_index][] = $elements_arr;
	}else{
		$items_arr[] = [$elements_arr];
	}
}


// функция для страницы price checker
function pc_add_to_log($item, $feed_item, $msg, $err_no){

	$ebay_id = $item['ebay_id'];
	$shop_id = $item['shop_id'];
	$ebay_title = _esc($item['title']);
	$shop_title = $feed_item ? _esc($feed_item[4]) : '';
	$msg = _esc($msg);
	global $_ERRORS;
	$errors = _esc(json_encode($_ERRORS));
	arrayDB("INSERT INTO cdvet_checker_log(ebay_id,shop_id,ebay_title,shop_title,msg,err_no,ERRORS)
				VALUES('$ebay_id','$shop_id','$ebay_title','$shop_title','$msg','$err_no','$errors')");
}


function get_language_by_table($steam_table = 'steam_de'){

	return [
	    'steam_de' => 'german',
	    'steam_en' => 'english',
	    'steam_fr' => 'french',
	    'steam_es' => 'spanish',
	    'steam_it' => 'italian',
	    'steam_ru' => 'russian',
	][$steam_table];
}


function get_steam_context($steam_table = 'steam_de')
{
	$options = array('http' => array('method' => "GET", 'header' => "Accept-language: en-US\r\n" . "Cookie: Steam_Language=".get_language_by_table($steam_table)."; mature_content=1; birthtime=238921201; lastagecheckage=28-July-1977\r\n"));
	return stream_context_create($options);
}


function get_text_template($category, $tpl_name = false)
{
	$category = _esc($category);
	$tpl_name = _esc($tpl_name);
	return $tpl_name ? 
		@arrayDB("SELECT tpl_text FROM text_templates WHERE category = '$category' AND tpl_name = '$tpl_name'")[0]['tpl_text'] :
		arrayDB("SELECT tpl_name,tpl_text FROM text_templates WHERE category = '$category'");
}


function ef_build_post_data($page, $entires = 200){
		return '<?xml version="1.0" encoding="utf-8"?>
		<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
		    <eBayAuthToken>'.EBAY_GIG_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ErrorLanguage>en_US</ErrorLanguage>
		  <WarningLevel>High</WarningLevel>
		  <GranularityLevel>Coarse</GranularityLevel>
		  <EndTimeFrom>'.date('Y-m-d\TH:i:s.B\Z', time()-2592000*2).'</EndTimeFrom>
		  <EndTimeTo>'.date('Y-m-d\TH:i:s.B\Z', time()+2592000*2).'</EndTimeTo>
		  <IncludeWatchCount>true</IncludeWatchCount>
		  <Pagination> 
		  	<PageNumber>'.$page.'</PageNumber>
		    <EntriesPerPage>'.$entires.'</EntriesPerPage> 
		  </Pagination> 
		</GetSellerListRequest>';
}


function cdvet_GetList_post_data($page, $entires = 200){
		return '<?xml version="1.0" encoding="utf-8"?>
		<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		  <RequesterCredentials>
		    <eBayAuthToken>'.EBAY_CDVET_TOKEN.'</eBayAuthToken>
		  </RequesterCredentials>
		  <ErrorLanguage>en_US</ErrorLanguage>
		  <WarningLevel>High</WarningLevel>
		  <GranularityLevel>Coarse</GranularityLevel>
		  <EndTimeFrom>'.date('Y-m-d\TH:i:s.B\Z', time()-2592000*2).'</EndTimeFrom>
		  <EndTimeTo>'.date('Y-m-d\TH:i:s.B\Z', time()+2592000*2).'</EndTimeTo>
		  <IncludeWatchCount>true</IncludeWatchCount>
		  <Pagination> 
		  	<PageNumber>'.$page.'</PageNumber>
		    <EntriesPerPage>'.$entires.'</EntriesPerPage> 
		  </Pagination> 
		</GetSellerListRequest>';
}


function ef_get_milticurl_handler(){

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetSellerList",
		"X-EBAY-API-SITEID: 77",
		"Content-Type: text/xml");

	$multi_curl = new \Curl\MultiCurl();
	$multi_curl->setOpt(CURLOPT_HTTPHEADER , $headers);
	$multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER , 0);
	$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION , 1);
	$multi_curl->setOpt(CURLOPT_TIMEOUT , 60);
	$multi_curl->error(function($instance) {
		global $_ERRORS;
	    $_ERRORS[] = $instance->errorMessage;
	});
	return $multi_curl;
}



function insert_comas($text, $max_len = 63, $delimeter = ',')
{
	$text_arr = explode(' ', $text);
	$new_str = '';
	$temp = '';
	$word_len = 0;
	foreach ($text_arr as $word) {
		$len = strlen($temp.' '.$word);
		if($len+$word_len < $max_len){
			$temp .= ' '.$word;
		}else{
			$temp .= $delimeter.' '.$word;
			$new_str .= $temp;
			$temp = '';
			$word_len = strlen($word);
		}
	}
	$new_str .= $temp;
	$new_str = str_replace(',,', ',', $new_str);
	$new_str = preg_replace('/,$/', '', trim($new_str));
	return str_replace(PHP_EOL.',', ','.PHP_EOL, $new_str);
}


function cut_text($text, $max_len = 63)
{
	$text_arr = explode(' ', $text);
	$new_str = '';

	foreach ($text_arr as $word) {
		if(strlen($new_str.' '.$word) < $max_len){
			$new_str .= ' '.$word;
		}
	}
	$new_str = str_replace(',,', ',', $new_str);
	return str_replace(PHP_EOL.',', ','.PHP_EOL, $new_str);
}


function ebay_messages_thumbnail(&$msg){
	if($msg['e_MediaURL']){
		$names = explode(',', $msg['e_MediaName']);
		$ret = '';
		foreach (explode(',', $msg['e_MediaURL']) as $k => $e_MediaURL) {
			preg_match('/\/z\/(.+?)\//', $e_MediaURL, $matches);
			$big_pic_url = 'https://i.ebayimg.com/images/g/'.$matches[1].'/s-l1600.jpg';
			$ret .= '<a href="'.$big_pic_url.'" class="thumbnail js-show-thumbnail" title="'.$names[$k].'">
				      <img src="'.$e_MediaURL.'" alt="...">
				    </a>';
		}
		return $ret;
	}
	else return '';
}


function get_media_data($MessageMedia, $el_name = 'MediaURL')
{
	if(!$MessageMedia) return '';

	if (isset($MessageMedia[0])) {
		$MessageMedia = array_column($MessageMedia, $el_name);
		return implode(',', $MessageMedia);
	}else{
		return $MessageMedia[$el_name];
	}
}


function user_star_sign(&$msg)
{
	if(!array_key_exists('is_trusted', $msg)) return '';

	if(isset($msg['e_Correspondent'])) $user_id = $msg['e_Correspondent'];
	else $user_id = $msg['BuyerUserID'];

	if ($msg['is_trusted']){
		$star_class = 'glyphicon-star';
		$star_title = 'unmarck as trusted';
	}else{
		$star_class = 'glyphicon-star-empty';
		$star_title = 'marck as trusted';
	}
	return '<button name='.$user_id.' class="trusted-user-star glyphicon '.$star_class.'" title="'.$star_title.'"></button>';
}


function user_alert_sign(&$msg)
{
	if(!array_key_exists('is_problematic', $msg)) return '';

	if(isset($msg['e_Correspondent'])) $user_id = $msg['e_Correspondent'];
	else $user_id = $msg['BuyerUserID'];

	if ($msg['is_problematic']){
		$star_class = 'glyphicon-alert is_problematic';
		$star_title = 'unmarck as problematic';
	}else{
		$star_class = 'glyphicon-alert';
		$star_title = 'marck as problematic';
	}
	return '<button name='.$user_id.' class="js-bad-user-alert glyphicon '.$star_class.'" title="'.$star_title.'"></button>';
}


function set_trusted_user(){

	$user_id = _esc($_POST['user_id']);
	$is_trusted = ($_POST['is_trusted'] === 'true') ? 1 : 0;

	$check = arrayDB("SELECT id FROM ebay_users WHERE user_id = '$user_id'");

	if($check) arrayDB("UPDATE ebay_users SET is_trusted = '$is_trusted' WHERE user_id = '$user_id'");
	else arrayDB("INSERT INTO ebay_users (user_id,is_trusted) VALUES ('$user_id', $is_trusted)");

	global $_ERRORS;
	return json_encode($_ERRORS);
}


function set_problematic_user(){

	$user_id = _esc($_POST['user_id']);
	$is_problematic = ($_POST['is_problematic'] === 'true') ? 1 : 0;

	$check = arrayDB("SELECT id FROM ebay_users WHERE user_id = '$user_id'");

	if($check) arrayDB("UPDATE ebay_users SET is_problematic = '$is_problematic' WHERE user_id = '$user_id'");
	else arrayDB("INSERT INTO ebay_users (user_id,is_problematic) VALUES ('$user_id', $is_problematic)");

	global $_ERRORS;
	return $ERRORS;
}


function is_trusted_user($user_id){
	$user_id = _esc($user_id);
	$res = arrayDB("SELECT is_trusted FROM ebay_users WHERE user_id = '$user_id'");
	if ($res) return $res[0]['is_trusted'];
	else return false;
}


function is_problematic_user($user_id){
	$user_id = _esc($user_id);
	$res = arrayDB("SELECT is_problematic FROM ebay_users WHERE user_id = '$user_id'");
	if ($res) return $res[0]['is_problematic'];
	else return false;
}


function GetItemsAwaitingFeedbacks(){

	$order_arr = [];
	$res = EbayOrders::GetItemsAwaitingFeedbackRequest(['PageNumber' => '1']);
	// sa($res);

	$sql = '';
	foreach ($res['ItemsAwaitingFeedback']['TransactionArray']['Transaction'] as &$item) {
		if(defined('DEV_MODE')) $order_arr[] = $item;
		$UserID = _esc(@$item['Buyer']['UserID']);
		$ItemID = _esc($item['Item']['ItemID']);
		$Title = _esc($item['Item']['Title']);
		$CommentType = _esc(@$item['FeedbackLeft']['CommentType']);
		$TransactionID = _esc($item['TransactionID']);
		$OrderLineItemID = _esc($item['OrderLineItemID']);
		$EndTime = _esc($item['Item']['ListingDetails']['EndTime']);
		if (arrayDB("SELECT id FROM awaiting_orders WHERE TransactionID = '$TransactionID' AND ItemID = '$ItemID'")){
			arrayDB("UPDATE awaiting_orders SET last_seen = CURRENT_TIMESTAMP WHERE TransactionID = '$TransactionID' AND ItemID = '$ItemID'");
		}else{
			arrayDB("INSERT INTO awaiting_orders (UserID,ItemID,Title,CommentType,TransactionID,OrderLineItemID,EndTime)
		VALUES ('$UserID','$ItemID','$Title','$CommentType','$TransactionID','$OrderLineItemID','$EndTime')");
		}
	}

	$pages = $res['ItemsAwaitingFeedback']['PaginationResult']['TotalNumberOfPages'];
	if(!$pages) return;

	for ($i=2; $i <= $pages; $i++){
		// if($i > 3) break; // трех страниц нам хватит
		$res = EbayOrders::GetItemsAwaitingFeedbackRequest(['PageNumber' => $i]);
		if(!isset($res['ItemsAwaitingFeedback']['TransactionArray']['Transaction'][0])){
			$res['ItemsAwaitingFeedback']['TransactionArray']['Transaction'] = [$res['ItemsAwaitingFeedback']['TransactionArray']['Transaction']];
		}
		$sql = '';
		foreach ($res['ItemsAwaitingFeedback']['TransactionArray']['Transaction'] as &$item) {
			if(defined('DEV_MODE')) $order_arr[] = $item;
			$UserID = _esc(@$item['Buyer']['UserID']);
			$ItemID = _esc($item['Item']['ItemID']);
			$Title = _esc($item['Item']['Title']);
			$CommentType = _esc(@$item['FeedbackLeft']['CommentType']);
			$TransactionID = _esc($item['TransactionID']);
			$OrderLineItemID = _esc($item['OrderLineItemID']);
			$EndTime = _esc($item['Item']['ListingDetails']['EndTime']);
			if (arrayDB("SELECT id FROM awaiting_orders WHERE TransactionID = '$TransactionID' AND ItemID = '$ItemID'")){
				arrayDB("UPDATE awaiting_orders SET last_seen = CURRENT_TIMESTAMP WHERE TransactionID = '$TransactionID' AND ItemID = '$ItemID'");
			}else{
				arrayDB("INSERT INTO awaiting_orders (UserID,ItemID,Title,CommentType,TransactionID,OrderLineItemID,EndTime)
			VALUES ('$UserID','$ItemID','$Title','$CommentType','$TransactionID','$OrderLineItemID','$EndTime')");
			}
		}
	}

	return($order_arr);
}


function set_bonus_sent()
{
	$sql_id = (int)$_POST['sql_id'];
	arrayDB("UPDATE awaiting_orders SET bonus_sent = 1 WHERE id = '$sql_id'");
}


function private_page_link($hash)
{
	return 'https://gig-games.de/private.php?page='.$hash;
}

function private_mail_link($hash){
	return 'https://gig-games.de/private.php?mail='.$hash;
}


function get_facebook_paragraph($ebay_id = 0, $ca = 'DE')
{
	if(!$ebay_id) return '';
	if(is_trusted_country($ca)){
		$ret = file_get_contents(ROOT.'/Files/facebook_paragraph_DE.html');
	}elseif(file_exists(ROOT.'/Files/facebook_paragraph_DE-'.$ca.'.html')) {
		$ret = file_get_contents(ROOT.'/Files/facebook_paragraph_DE-'.$ca.'.html');
	}else{
		$ret = file_get_contents(ROOT.'/Files/facebook_paragraph_EN.html');
	}
	$ret = str_replace('ebay_id=0', 'ebay_id='.$ebay_id, $ret);
	return $ret;
}


function mail_link_block($secret_hash, $country_alias = 'DE')
{
	if (is_trusted_country($country_alias)) return '<div class="qq-block0">
		<a class="qq-open-email-link" href="https://gig-games.de/private.php?mail='.$secret_hash.'" target="_blank" style="max-width:640px;margin:auto;color:#2199e8;font-weight:400;line-height:1.3;font-family:Helvetica,Arial,sans-serif;text-align:center;text-decoration:none;display:block;background-color:#fff;padding:10px">E-Mail wird nicht richtig angezeigt? Klicken Sie bitte hier.</a>
	</div>';

	else return '<div class="qq-block0">
		<a class="qq-open-email-link" href="https://gig-games.de/private.php?mail='.$secret_hash.'" target="_blank" style="max-width:640px;margin:auto;color:#2199e8;font-weight:400;line-height:1.3;font-family:Helvetica,Arial,sans-serif;text-align:center;text-decoration:none;display:block;background-color:#fff;padding:10px">E-mail is not displayed correctly? Please click here.</a>
	</div>';
}


function cdvet_filter_search()
{
	cdvet_filter_log_insert();
	return Cdvet::filter_search();
}


function cdvet_filter_search_site()
{
	return Cdvet::filter_search_site();
}


function get_cdvet_cats()
{
	if($_POST['enter_log']) cdvet_filter_log_insert(true);
	$res = arrayDB("SELECT * FROM cdvet_cats WHERE id NOT IN(52,53,55,56)");
	$ret0 = [];$ret1 = [];
	foreach ($res as $key => $val) {
		$ret0[$val['section']][$val['shop_category']] = $val;
		$ret1[$val['section']][] = $val;
	}
	$ret2= array_column($res, 'image', 'eBayShopKAtegorieID');
	$ret3= array_column($res, 'image', 'shop_category');
	return json_encode(['by_sections_by_shopcat'=>$ret0,
						'by_sections'=>$ret1,
						'by_ebay_cat'=>$ret2,
						'by_cdvet_cat'=>$ret3]);
}


function cdvet_filter_menu()
{
	return '<pre style="white-space:pre-wrap;">'.file_get_contents(ROOT.'/lib/adds/cdvet-'.$_POST['menu_item'].'.html').'</pre>';
}


function cdvet_filter_log_insert($enter_only = false)
{
	if($_SERVER['REMOTE_ADDR'] === '37.46.229.203') return;
	$user = get_username_with_IP();

	if ($enter_only) {
		arrayDB("INSERT INTO cdvet_filter_log (`user`,`enter`) VALUES('$user','enter')");
		return;
	}

	if(!isset($_POST['log'])) return;

	$field = _esc($_POST['log']['field']);
	$value = _esc($_POST['log']['value']);

	if(@$_POST['log']['enter']){
		arrayDB("INSERT INTO cdvet_filter_log (`user`,`enter`,`$field`) VALUES('$user','enter','$value')");
	}else{
		arrayDB("INSERT INTO cdvet_filter_log (`user`,`$field`) VALUES('$user','$value')");
	}
}



function get_mail2018_template($country_alias)
{
	if (is_trusted_country($country_alias)) {
		return get_text_template('mail2018', 'DE');
	}else{
		return get_text_template('mail2018', 'EN');
	}
}


function save_steam_offset($steam_table, $offset)
{
	$res = file_get_contents(ROOT.'/lib/adds/steam_offsets.json');
	$res = json_decode($res, true);
	$res[$steam_table] = $offset;
	$res = json_encode($res);
	file_put_contents(ROOT.'/lib/adds/steam_offsets.json', $res);
}


function get_steam_offsets($steam_table = false)
{
	$res = file_get_contents(ROOT.'/lib/adds/steam_offsets.json');
	$res = json_decode($res, true);
	if($steam_table) return $res[$steam_table];
	return $res;
}


function steam_images_count($app_id, $app_sub)
{
	$checker = file_get_contents('http://parser.gig-games.de/steam-images-checker.php?app_id='.$app_id.'&app_sub='.$app_sub);
	return count(json_decode($checker, true));
}


function black_white_list()
{
	$game_id = _esc($_POST['game_id']);
	$ebay_id = _esc($_POST['ebay_id']);
	$title = _esc($_POST['title']);
	$category = _esc($_POST['category']);

	if (!$game_id || !$ebay_id || !$category) return;

	$check1 = arrayDB("SELECT id FROM ebay_black_white_list WHERE game_id = '$game_id' AND ebay_id = '$ebay_id'");

	if ($_POST['category'] === 'white') {
		$check2 = arrayDB("SELECT id FROM ebay_prices WHERE item_id = '$ebay_id'");
	}

	if ($_POST['category'] === 'black') {
		$check2 = arrayDB("SELECT id FROM games WHERE id = '$game_id' AND ebay_id = '$ebay_id'");
	}

	if (!$check1 && !$check2) {
		echo arrayDB("INSERT INTO ebay_black_white_list (game_id,ebay_id,title,category) VALUES('$game_id','$ebay_id','$title','$category')");
	}
}


function ebay_reparse_one($game_id = 0, $reparse_one = true)
{
	global $_ERRORS;
	$url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/lib/ebay_getprices.php';

	$game_id = _esc($game_id ? $game_id : $_POST['game_id']);

	$post = [
		'ebay_getprices' => 'true',
		'start' => '1',
		'end' => '10',
		'game_id' => $_POST['game_id'],
	];

	if($reparse_one) $ret = post_curl($url, $post);
	else $ret = [];

	$value = arrayDB("SELECT * FROM ebay_results WHERE game_id = '$game_id' ORDER BY id DESC LIMIT 1")[0];
	if(!$value) return '[]';

	$ret['ebay_info'] = $value;
	$ret['ebay_info']['game_id'] = $game_id;
	$ret['ebay_info']['gigs'] = [];
	$ret['ebay_info']['wls'] = [];

	$ids_arr = arrayDB("SELECT item_id FROM ebay_prices");
	$ids_arr = array_column($ids_arr, 'item_id');

	$white_list = arrayDB("SELECT game_id,ebay_id FROM ebay_black_white_list WHERE category = 'white'");
	$wl = []; foreach ($white_list as $val) $wl[$val['game_id']][] = $val['ebay_id'];

	$ret['ebay_info']['gigs'][0] = $gig1 = in_array($value['itemid1'], $ids_arr) ? 'gig' : '';
	$ret['ebay_info']['gigs'][1] = $gig2 = in_array($value['itemid2'], $ids_arr) ? 'gig' : '';
	$ret['ebay_info']['gigs'][2] = $gig3 = in_array($value['itemid3'], $ids_arr) ? 'gig' : '';
	$ret['ebay_info']['gigs'][3] = $gig4 = in_array($value['itemid4'], $ids_arr) ? 'gig' : '';
	$ret['ebay_info']['gigs'][4] = $gig5 = in_array($value['itemid5'], $ids_arr) ? 'gig' : '';

	$ret['ebay_info']['wls'][0] = $wl1 = (@$wl[$value['game_id']] && in_array($value['itemid1'], $wl[$value['game_id']])) ? 'white' : '';
	$ret['ebay_info']['wls'][1] = $wl2 = (@$wl[$value['game_id']] && in_array($value['itemid2'], $wl[$value['game_id']])) ? 'white' : '';
	$ret['ebay_info']['wls'][2] = $wl3 = (@$wl[$value['game_id']] && in_array($value['itemid3'], $wl[$value['game_id']])) ? 'white' : '';
	$ret['ebay_info']['wls'][3] = $wl4 = (@$wl[$value['game_id']] && in_array($value['itemid4'], $wl[$value['game_id']])) ? 'white' : '';
	$ret['ebay_info']['wls'][4] = $wl5 = (@$wl[$value['game_id']] && in_array($value['itemid5'], $wl[$value['game_id']])) ? 'white' : '';

	$ret['tds_str'] =  '<td class="'.$gig1.' '.$wl1.'" iid="'.$value['itemid1'].'" title="'.$value['title1'].'">'.$value['price1'].'</td>
						<td class="'.$gig2.' '.$wl2.'" iid="'.$value['itemid2'].'" title="'.$value['title2'].'">'.$value['price2'].'</td>
						<td class="'.$gig3.' '.$wl3.'" iid="'.$value['itemid3'].'" title="'.$value['title3'].'">'.$value['price3'].'</td>
						<td class="'.$gig4.' '.$wl4.'" iid="'.$value['itemid4'].'" title="'.$value['title4'].'">'.$value['price4'].'</td>
						<td class="'.$gig5.' '.$wl5.'" iid="'.$value['itemid5'].'" title="'.$value['title5'].'">'.$value['price5'].'</td>';
	
	$ret['errors'] = $_ERRORS;

	return json_encode($ret);
}


function get_ebay_black_list($game_id)
{
	$game_id = _esc($game_id);

	$res = arrayDB("SELECT ebay_id FROM ebay_black_white_list WHERE game_id = '$game_id' AND category = 'black'");

	return array_column($res, 'ebay_id');
}


function delete_old_records($table, $column)
{
	$table = _esc($table);
	return arrayDB("DELETE FROM `$table` WHERE `$table`.`$column` < NOW() - INTERVAL 4 WEEK");
}


function delete_old_records_2($table, $limit = 5)
{
	$res = arrayDB("SELECT scan from `$table` group by scan");

	if (count($res) > $limit) {
		$scan = $res[0]['scan'];
		arrayDB("DELETE FROM `$table` WHERE scan = '$scan'");
		delete_old_records_2($table);
	}
}


function ak_get_games()
{
	if(!$_POST['query']) return '[]';
	$game = _esc(trim($_POST['query']));
	$ret = arrayDB("SELECT id,title_clean,item_id,price,picture_hash FROM ebay_prices WHERE status = 'Active' AND title LIKE '%$game%' ORDER BY title_clean LIMIT 7");
	// $ret = array_column($ret, 'title');

	// $ret = array_map(function($el)
	// {
	// 	$el['title_clean'] = htmlspecialchars($el['title_clean']);
	// 	return $el;
	// }, $ret);
	return json_encode($ret);
}


function ak_get_sellers()
{
	if(!$_POST['query']) return '[]';
	$game = _esc(trim($_POST['query']));
	$ret = arrayDB("SELECT username FROM ak_sellers WHERE username LIKE '%$game%' LIMIT 5");
	$ret = array_column($ret, 'username');
	return json_encode($ret);
}


function ak_add_seller()
{
	global $_ERRORS;
	$username = _esc($_POST['username']);
	$info = _esc($_POST['info']);

	$check = arrayDB("SELECT id FROM ak_sellers WHERE username = '$username'");

	if(!$check) arrayDB("INSERT INTO ak_sellers (username,info) VALUES('$username','$info')");

	return json_encode([
		'post' => $_POST,
		'report' => $check ? 'seller exists!' : 'seller added!',
		'report_status' => $check ? 'warning' : 'success',
		'errors' => $_ERRORS,
	]);
}




function ak_add_key()
{
	global $_ERRORS;
//     [function] => ak_add_key
//     [game_name] => National Flags - Heads Pack
//     [seller] => rty
//     [keys] => gfh
//     [price] => 67
//     [ebay_id] => 
	$sqled = [];
	$good = true;
	$report = 'keys added!';
	$report_status = 'success';

	$flds = ['ebay_id','game_name','seller','keys','price'];

	foreach ($flds as $field) {
		if(!$_POST[$field]){
			$good = false;
			$report = 'require field: ' . $field;
			$report_status = 'warning';
		}
		// if(!is_array($_POST[$field])) $flds[$field] = _esc($_POST[$field]);
		$flds[$field] = _esc(trim($_POST[$field]));
	}

	foreach (explode(PHP_EOL, $_POST['keys']) as $steam_key) {
		$steam_key = _esc(trim($steam_key));
		if(!strlen($steam_key)) continue;
		$check = arrayDB("SELECT id FROM ak_keys WHERE `steam_key` = '$steam_key'");
		if(!$check){
			$sqled[] = arrayDB("INSERT INTO ak_keys (`steam_key`,ebay_id,game_name,seller,price)
				VALUES('$steam_key','$flds[ebay_id]','$flds[game_name]','$flds[seller]','$flds[price]')");
		}else{
			$report .= '<br>key allready exist:'.$steam_key;
		}
	}
	return json_encode([
		'post' => $_POST,
		'report' => $report,
		'report_status' => $report_status,
		'sqled' => $sqled,
		'errors' => $_ERRORS,
	]);
}


function get_warehouse($ebay_id)
{
	$res = arrayDB("SELECT * FROM ak_keys WHERE ebay_id = '$ebay_id' AND status = 'active' ORDER BY price");
	if ($res) {
		return $res[0];
	}else{
		return false;
	}
}


function warehouse_status_sold($warehouse_id, $gig_order_id, $gig_order_item_id)
{
	arrayDB("UPDATE ak_keys 
		SET status = 'sold',
			gig_order_id = '$gig_order_id',
			gig_order_item_id = '$gig_order_item_id'
		WHERE id = '$warehouse_id'");
}


function ak_get_prices()
{
	$ebay_id = _esc($_POST['ebay_id']);

	$wh_price = @get_warehouse($ebay_id)['price'];

	$suitables = get_suitables2($ebay_id);
	$platiru_price = @$suitables[0]['item1_price'];

	return 'wh: '.($wh_price ? $wh_price.'p' : 'none').
		' | plati.ru: '.($platiru_price ? $platiru_price.'p' : 'none');
}


function ak_change_key_price()
{	
	global $_ERRORS;

	$key_id = _esc($_POST['key_id']);
	$new_price = _esc($_POST['new_price']);

	arrayDB("UPDATE ak_keys SET price = '$new_price' WHERE id = '$key_id'");

	return json_encode([
		'post' => $_POST,
		'report' => '',
		'report_status' => 'success',
		'errors' => $_ERRORS,
	]);
}


// callback for array_walk_recursive in ge_get_ebay_item_info
function ge_cb(&$item)
{
    $item = htmlspecialchars($item);
}

// ge_ prefix for game-editor page
function ge_get_ebay_item_info()
{
	$ebay_id = $_POST['ebay_id'];

	//'IncludeSelector'=> Details,Description,ItemSpecifics,TextDescription
	$res = getSingleItem($ebay_id, ['as_array'=>true,'IncludeSelector'=>'Details,ItemSpecifics,Description']);


	array_walk_recursive($res['Item']['ItemSpecifics']['NameValueList'], 'ge_cb');
	$res['Item']['ItemSpecifics']['NameValueList'] = array_values(array_filter($res['Item']['ItemSpecifics']['NameValueList'], function($item){
		
		if (strpos($item['Name'], 'Rück') !== false) return false;
		return true;
	}));
	// $res['Item']['Description'] = htmlspecialchars($res['Item']['Description']);

	// массив с именами спецификаций в качастке ключей
	$res['specs_name_keys'] = [];
	foreach ($res['Item']['ItemSpecifics']['NameValueList'] as $spec) {
		$res['specs_name_keys'][$spec['Name']] = $spec;
	}

	return json_encode($res);
}


function ge_get_additional_specifics()
{
	$cat_id = (int)$_POST['cat_id'];
	$a_specs = get_category_specifics_sorted($cat_id);

	$ret['a_specs'] = $a_specs;
	$ret['a_specs_name_keys'] = [];
	foreach ($ret['a_specs'] as &$spec) {
		$ret['a_specs_name_keys'][$spec['Name']] = $spec;
		
		$tooltip = print_r($spec['ValidationRules'], true);
		$tooltip = substr($tooltip, 6);
		$spec['tooltip'] = $ret['a_specs_name_keys'][$spec['Name']]['tooltip'] = htmlspecialchars($tooltip);
		
		$values = print_r($spec['Value'], true);
		$values = substr($values, 6);
		$ret['a_specs_name_keys'][$spec['Name']]['values'] = htmlspecialchars($values);
	}
	return json_encode($ret);
}


function ge_update_base_data()
{
	// return json_encode($_POST);
	if (!$_POST['ebay_id']) {
		$r_type = 'danger';
		$r_text = '<b>Success!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}
	$_POST['PrimaryCategory'] = trim($_POST['PrimaryCategory']);
	$res = EbayGigGames::setTokenByName($_POST['plattform'])->updateItemBaseData([
		'ItemID' => $_POST['ebay_id'],
		'Title' => $_POST['title'],
		'StartPrice' => $_POST['price'],
		'Quantity' => $_POST['quantity'],
		'PrimaryCategoryID' => ($_POST['PrimaryCategory_old'] !== $_POST['PrimaryCategory']) ? $_POST['PrimaryCategory'] : '',
	]);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);

}


function ge_update_specifics()
{
	// return json_encode($_POST);
	if (!$_POST['ebay_id']) {
		$r_type = 'danger';
		$r_text = '<b>Warning!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$_POST['ebay_id'],
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}
	$_POST['specs'] = array_filter($_POST['specs'], function($el){return $el[0];});
	$res = EbayGigGames::setTokenByName($_POST['plattform'])
	         ->updateItemSpecifics($_POST['ebay_id'], $_POST['specs']);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$_POST['ebay_id'],
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);
}


function ge_update_description()
{
	if(!$_POST['ebay_id'] || !$_POST['description']){
		$r_type = 'danger';
		$r_text = '<b>Warning!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}

	$res = EbayGigGames::setTokenByName($_POST['plattform'])
	         ->updateItemDescription($_POST['ebay_id'], $_POST['description']);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);
}


function ge_update_subtitle()
{
	if(!$_POST['ebay_id'] || !$_POST['subtitle']){
		$r_type = 'danger';
		$r_text = '<b>Warning!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}

	$res = EbayGigGames::setTokenByName($_POST['plattform'])
	         ->updateItemSubtitle($_POST['ebay_id'], $_POST['subtitle']);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);
}


function ge_update_gelery_type()
{
	if(!$_POST['ebay_id'] || !$_POST['GalleryType'] || !$_POST['GalleryDuration']){
		$r_type = 'danger';
		$r_text = '<b>Warning!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}

	$res = EbayGigGames::setTokenByName($_POST['plattform'])
	         ->updateItemGalleryType($_POST['ebay_id'], [
	         	'GalleryType' => $_POST['GalleryType'],
	         	'GalleryDuration' => $_POST['GalleryDuration'],
	         ]);

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);
}


function ge_update_pictures()
{
	if(!$_POST['ebay_id'] || !$_POST['pic_urls']){
		$r_type = 'danger';
		$r_text = '<b>Warning!</b> there is no ebay id';
		return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=> null,
						'report'=>galert($r_type, $r_text)]);
	}

	$res = EbayGigGames::setTokenByName($_POST['plattform'])
	         ->updateItemGalleryURLs($_POST['ebay_id'], array_filter($_POST['pic_urls']));

	if (isset($res['Ack']) && $res['Ack'] !== 'Failure') {
		$r_type = 'success';
		$r_text = '<b>Success!</b> Saved!';
	}else{
		$r_type = 'warning';
		$r_text = '<b>Warning!</b> something wrong!<br>Please contact the administrator.';
	}
	return json_encode(['ebay_id'=>$ebay_id,
						'post'=>$_POST,
						'ebay_resp'=>$res,
						'report'=>galert($r_type, $r_text)]);
}



// код для мгновенных сообщений
// пример использования:
// $('#report_screen').append(data.report);
function galert($type,$text) {
	return '<div class="alert alert-'.$type.' alert-dismissible height-anim" role="alert">
  	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
  	</button>'.$text.'</div>';
}


function get_product_list_test($plattform = 'gig-games')
{
	global $_ERRORS;

	// Requests in parallel with callback functions.
			// curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$multi_curl = new \Curl\MultiCurl();

	$headers = array("X-EBAY-API-COMPATIBILITY-LEVEL: 967",
	    'X-EBAY-API-DEV-NAME: c1f2f124-1232-4bc4-bf9e-8166329ce649',
	    'X-EBAY-API-APP-NAME: Konstant-Projekt1-PRD-bae576df5-1c0eec3d',
	    'X-EBAY-API-CERT-NAME: PRD-ae576df59071-a52d-4e1b-8b78-9156',
		"X-EBAY-API-CALL-NAME: GetSellerList",
		"X-EBAY-API-SITEID: 77",
		"Content-Type: text/xml");
	$multi_curl->setHeader('X-EBAY-API-COMPATIBILITY-LEVEL','967');
	$multi_curl->setHeader('X-EBAY-API-DEV-NAME','c1f2f124-1232-4bc4-bf9e-8166329ce649');
	$multi_curl->setHeader('X-EBAY-API-APP-NAME','Konstant-Projekt1-PRD-bae576df5-1c0eec3d');
	$multi_curl->setHeader('X-EBAY-API-CERT-NAME','PRD-ae576df59071-a52d-4e1b-8b78-9156');
	$multi_curl->setHeader('X-EBAY-API-CALL-NAME','GetSellerList');
	$multi_curl->setHeader('X-EBAY-API-SITEID','77');
	$multi_curl->setHeader('Content-Type','text/xml');

	// MultiCurl::setHeader($key, $value)
	// MultiCurl::setHeaders($headers)
	// $multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER , 0);
	// $multi_curl->setOpt(CURLOPT_FOLLOWLOCATION , 1);
	//$_GET['counter'] = 0;
	$_GET['i'] = 1;
	$multi_curl->success(function($instance) {
		// $instance->url
		// $instance->response
		// sa('count => '.$_GET['i']);
		$res = json_decode(json_encode($instance->response),true);
		// sa($res);
		if(!isset($res['ItemArray']['Item'][0])) $res['ItemArray']['Item'] = [$res['ItemArray']['Item']];
		foreach ($res['ItemArray']['Item'] as $item) {
			unset($item['ShippingDetails']);
			unset($item['Storefront']);
			unset($item['BestOfferDetails']);
			unset($item['ReturnPolicy']);
			unset($item['SellerProfiles']);
			unset($item['ListingDetails']);
			$_GET['item_arr'][] = $item;
		    //-----------------------------------------------------
			// $_GET['item_arr'][$item['ItemID']] = ['t' => $item['Title'],
			// 								's' => $item['SellingStatus']['ListingStatus']];
		}
		if ($_GET['i'] === 1) {
			$_GET['PaginationResult'] = $res['PaginationResult'];
		}
		$_GET['i']++;
	});
	$multi_curl->error(function($instance) {
		global $_ERRORS;
	    $_ERRORS[] = $instance->errorMessage;
	});

	$api_url = 'https://api.ebay.com/ws/api.dll';
	$post_data = EbayGigGames::setTokenByName($plattform)
		->GetSellerListRequestPostData($page=1, $entires=200);
	$multi_curl->addPost($api_url, $post_data);

	$multi_curl->start(); // Blocks until all items in the queue have been processed.

	$pages = $_GET['PaginationResult']['TotalNumberOfPages'];
	// $pages = '5';
	for ($i=2; $i <= $pages; $i++) {
		$post_data = EbayGigGames::setTokenByName($plattform)
			->GetSellerListRequestPostData($i, 200);
		$multi_curl->addPost($api_url, $post_data);
	}
	$multi_curl->start(); // Blocks until all items in the queue have been processed.

	// $_GET['tits'] = [];
	// foreach ($_GET['item_arr'] as $item) {
	// 	if($item['s'] === 'Active') $_GET['tits'][] = $item['t'];
	// }

	// $_GET['item_arr'] = array_filter($_GET['item_arr'],function($item)
	// {
	// 	// return ($item['s'] === 'Completed');
	// 	return ($item['s'] === 'Completed' && !in_array($item['t'], $_GET['tits']));
	// });
	// $_GET['item_arr'] = unique_multidim_array($_GET['item_arr'], 't');
	// sa($_GET['item_arr']);
	// sa($_GET['tits']);
	// sa($_GET['PaginationResult']);
	// sa($_GET['no_unic']);
	return $_GET['item_arr'];
	echo json_encode([
		'completed_arr' => $_GET['item_arr'],
		'errors' =>  $_ERRORS,
	]);
}


function get_css_link($file_name)
{
	return '<link rel="stylesheet" href="css/'.$file_name.'.css?t='.date('d-m-y_H:i:s',filemtime ('css/'.$file_name.'.css')).'">';
}


// [Name] => Herstellungsland und -region
// [ValidationRules] => Array
//     (
//         [ValueType] => Text
//         [MaxValues] => 1
//         [SelectionMode] => SelectionOnly
//         [VariationSpecifics] => Disabled
//     )
// [Value] => Array
//     (
//         [0] => Unbekannt
//         [1] => Afghanistan
//         ...
function get_category_specifics_sorted($categoryId)
{
	$arr = [];
	if(!$categoryId) return $arr;
	$res = EbayGigGames::GetCategorySpecifics($categoryId);
	if ($res['Recommendations']['NameRecommendation']) {
		foreach ($res['Recommendations']['NameRecommendation'] as $value) {
			if (isset($value['ValueRecommendation'])) {
				$rules = [];
				if(!isset($value['ValueRecommendation'][0])){
					$value['ValueRecommendation'] = [$value['ValueRecommendation']];
				}
				foreach ($value['ValueRecommendation'] as $val) {
					$rules[] = $val['Value'];
				}
				unset($value['ValueRecommendation']);
				$value['Value'] = $rules;
			}else{
				$value['Value'] = [];
			}
			$arr[] = $value;
		}
	}
	return $arr;
}


function tasks_json()
{
	return file_get_contents('csv/tasks.json');
}

function slugify($string, $replace = array(), $delimiter = '-') {
  // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
  if (!extension_loaded('iconv')) {
    throw new Exception('iconv module not loaded');
  }
  // Save the old locale and set the new locale to UTF-8
  $oldLocale = setlocale(LC_ALL, '0');
  setlocale(LC_ALL, 'en_US.UTF-8');
  $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  if (!empty($replace)) {
    $clean = str_replace((array) $replace, ' ', $clean);
  }
  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
  $clean = strtolower($clean);
  $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
  $clean = trim($clean, $delimiter);
  // Revert back to the old locale
  setlocale(LC_ALL, $oldLocale);
  return $clean;
}


if (!function_exists('steam_to_gig')) {
	function steam_to_gig($appid)
	{
		if(!$appid) return 0;
		return (int)$appid + 555555;
	}
}

if (!function_exists('gig_to_steam')){
	function gig_to_steam($appid)
	{
		if(!$appid) return 0;
		return (int)$appid - 555555;
	}
}



function get_gig_game_link_2(&$steam_game, $home_url = 'https://gig-games.de')
{
	return $home_url.'/game/?type='.$steam_game['type'].'&appid='.steam_to_gig($steam_game['appid']).'&title='.get_gig_game_url_title($steam_game['title']);
}


//  aqs sanitize  START =========================================================
//taken from wordpress
function gig_utf8_uri_encode( $utf8_string, $length = 0 ) {
	$unicode = '';
	$values = array();
	$num_octets = 1;
	$unicode_length = 0;

	$string_length = strlen( $utf8_string );
	for ($i = 0; $i < $string_length; $i++ ) {

		$value = ord( $utf8_string[ $i ] );

		if ( $value < 128 ) {
			if ( $length && ( $unicode_length >= $length ) )
				break;
			$unicode .= chr($value);
			$unicode_length++;
		} else {
			if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

			$values[] = $value;

			if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
				break;
			if ( count( $values ) == $num_octets ) {
				if ($num_octets == 3) {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
					$unicode_length += 9;
				} else {
					$unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
					$unicode_length += 6;
				}

				$values = array();
				$num_octets = 1;
			}
		}
	}

	return $unicode;
}

//taken from wordpress
function gig_seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

//function sanitize_title_with_dashes taken from wordpress
function gig_sanitize($title) {
	$title = strip_tags($title);
	// Preserve escaped octets.
	$title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
	// Remove percent signs that are not part of an octet.
	$title = str_replace('%', '', $title);
	// Restore octets.
	$title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

	if (gig_seems_utf8($title)) {
		if (function_exists('mb_strtolower')) {
			$title = mb_strtolower($title, 'UTF-8');
		}
		$title = gig_utf8_uri_encode($title, 200);
	}

	$title = strtolower($title);
	$title = preg_replace('/&.+?;/', '', $title); // kill entities
	$title = str_replace('.', '-', $title);
	$title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
	$title = preg_replace('/\s+/', '-', $title);
	$title = preg_replace('|-+|', '-', $title);
	$title = trim($title, '-');

	return $title;
}
//
//  aqs sanitize  END =========================================================
//
function get_gig_game_url_title($title)
{
	return 'preisvergleich-cd-steam-key-'.gig_sanitize($title);
}

function get_gig_game_link($home_url, &$steam_game)
{
	return $home_url.'/game/?type='.$steam_game['type'].'&appid='.steam_to_gig($steam_game['appid']).'&title='.get_gig_game_url_title($steam_game['title']);
}

function aqs_file_get_html($url, $use_include_path = false, $context = null){
	return str_get_html(file_get_contents($url, $use_include_path, $context));
}



function get_steam_images_dir_path($type, $appid)
{
	// return ROOT.'/steam-images/'.$type.'/'.implode('/', str_split($appid));
	return ROOT.'/steam-images/'.($type==='dlc'?'app':$type).'s-'.$appid;
}


function get_steam_images_dir_url($type, $appid)
{
	return 'https://parser.gig-games.de/steam-images/'.($type==='dlc'?'app':$type).'s-'.$appid;
}


function iz_mobile()
{
	foreach (['iPhone', 'Android', 'webOS', 'BlackBerry', 'iPod', 'Nokia'] as $os) {
	  if (strpos($_SERVER['HTTP_USER_AGENT'], $os) !== false) {
	    return true;
	  }
	}
	return false;
}

// Returns SimpleXML Safe XML keeping the elements attributes as well
function sanitizeXML($xml_content, $xml_followdepth=true){

    if (preg_match_all('%<((\w+)\s?.*?)>(.+?)</\2>%si', $xml_content, $xmlElements, PREG_SET_ORDER)) {

        $xmlSafeContent = '';

        foreach($xmlElements as $xmlElem){
            $xmlSafeContent .= '<'.$xmlElem['1'].'>';
            if (preg_match('%<((\w+)\s?.*?)>(.+?)</\2>%si', $xmlElem['3'])) {
                $xmlSafeContent .= sanitizeXML($xmlElem['3'], false);
            }else{
                $xmlSafeContent .= htmlspecialchars($xmlElem['3'],ENT_NOQUOTES);
            }
            $xmlSafeContent .= '</'.$xmlElem['2'].'>';
        }

        if(!$xml_followdepth)
            return $xmlSafeContent;
        else
            return "<?xml version='1.0' encoding='UTF-8'?>".$xmlSafeContent;

    } else {
        return htmlspecialchars($xml_content,ENT_NOQUOTES);
    }

}


function ajax_gift_keys_add_key()
{
	return Gift_keys::save_key();
}


function save_domens($word, $offset = 0)
{	
	$url = 'https://www.google.com/search?ved=0ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQxdoBCFw&hl=ru&yv=3&q='.urlencode($word).'&lr=&tbm=isch&ei=SnBBXebPD_GorgSjrYcI&vet=10ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQuT0IQCgB.SnBBXebPD_GorgSjrYcI.i&ijn=4&start='.$offset.'&asearch=ichunk&async=_id:rg_s,_pms:s,_fmt:pc';

	$data = getSslPage($url);

	preg_match_all('/"rh":"(.+?)"/', $data, $matches);

	if (isset($matches[1]) && $matches[1]) {
		$domens_arr = $matches[1];

		foreach ($domens_arr as $domen) {
			$domen = _esc(trim($domen));
			$arr = explode('.', $domen);
			$zone = array_pop($arr);
			$check = arrayDB("SELECT id FROM gp_domens_mi WHERE domen = '$domen'");
			if($check){
				$id = $check[0]['id'];
				arrayDB("UPDATE gp_domens_mi SET keywords = '$word' WHERE id = '$id'");
			}else{
				arrayDB("INSERT INTO gp_domens_mi SET domen = '$domen', zone = '$zone', keywords = '$word'");
			}
		}

		$count = save_domens($word, $offset + 100);
		return $count + count($domens_arr);
	}else{
		return 0;
	}	
}


function save_domens_multi($word)
{
	$_GET['gp_count'] = 0;
	$_GET['gp_inserted'] = 0;
	$_GET['gp_multiquery'] = [];
	$_GET['gp_word'] = $word;

	$multi_curl = new \Curl\MultiCurl();

	$multi_curl->success(function($instance) {

		preg_match_all('/"rh":"(.+?)"/', $instance->response, $matches);

		if (isset($matches[1]) && $matches[1]) {
			$domens_arr = $matches[1];

			foreach ($domens_arr as $domen) {
				$domen = _esc(trim($domen));
				$arr = explode('.', $domen);
				$zone = array_pop($arr);
				$word = $_GET['gp_word'];
				$check = arrayDB("SELECT id FROM gp_domens_mi WHERE domen = '$domen'");
				if($check){
					// $id = $check[0]['id'];
					// arrayDB("UPDATE gp_domens_mi SET keywords = '$word' WHERE id = '$id'");
				}else{
					$_GET['gp_inserted'] += 1;
					$_GET['gp_multiquery'][] = "INSERT IGNORE INTO gp_domens_mi SET domen = '$domen', zone = '$zone', keywords = '$word'";
				}
			}

			$_GET['gp_count'] += count($domens_arr);
		}
	});

	$multi_curl->error(function($instance) {
		global $_ERRORS;
		$_ERRORS[] = 'THAT WAS multi_curl ERROR!!!';
	    $_ERRORS[] = $instance->errorMessage;
	});

	for ($offs=0; $offs < 701; $offs += 100) { 
		$url = get_google_url($word, $offs);
		$multi_curl->addGet($url);
	}

	$multi_curl->start();

	arrayDB(implode(';', $_GET['gp_multiquery']), true);

	return $_GET['gp_count'];
}


function get_google_url($word, $offset = 0)
{
	return 'https://www.google.com/search?ved=0ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQxdoBCFw&hl=ru&yv=3&q='.urlencode($word).'&lr=&tbm=isch&ei=SnBBXebPD_GorgSjrYcI&vet=10ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQuT0IQCgB.SnBBXebPD_GorgSjrYcI.i&ijn=4&start='.$offset.'&asearch=ichunk&async=_id:rg_s,_pms:s,_fmt:pc';
}


function get_setting($name='')
{
	$name = _esc($name);
	$res = arrayDB("SELECT value FROM aq_settings WHERE name = '$name' LIMIT 1");

	if ($res) {
		return $res[0]['value'];
	}else{
		return '';
	}
}


function set_setting($name = '', $value = '')
{
	$name = _esc($name);
	$value = _esc($value);

	$check = arrayDB("SELECT id FROM aq_settings WHERE name = '$name'");
	if ($check) {
		return arrayDB("UPDATE aq_settings SET value = '$value' WHERE name = '$name'");
	}else{
		return arrayDB("INSERT INTO aq_settings SET name = '$name', value = '$value'");
	}
}


function what_currency($itemid)
{
	$res = arrayDB("SELECT * FROM items WHERE item1_id = '$itemid' ORDER BY id DESC LIMIT 1");

	if ($res) {
		$item = $res[0];

		$exrate_wmr = get_setting('exrate_wmr');
		$exrate_wmz = get_setting('exrate_wmz');

		$currency = 'WMZ';
		$min_price = 999999;
		if ($item['item1_price_eur'] > 0 && $item['item1_price_eur'] < $min_price) {
			$currency = 'WME';
			$min_price = $item['item1_price_eur'];
		}
		if ($item['item1_price_rur'] > 0 && ($item['item1_price_rur'] / $exrate_wmr) < $min_price) {
			$currency = 'WMR';
			$min_price = $item['item1_price_rur'] / $exrate_wmr;
		}
		if ($item['item1_price_usd'] > 0 && ($item['item1_price_usd'] / $exrate_wmz) < $min_price) {
			$currency = 'WMZ';
		}
		return $currency;
	}else{
		return 'WMZ';
	}
}


function ajax_get_eve_data()
{

	$arr = [
      ['Date', 'Tranquility', 'Singularity'],
    ];

	$res = arrayDB("SELECT * FROM eve_server_status where id mod 15 = 0 ORDER BY id DESC LIMIT 500");

    $res = array_reverse($res);

    foreach ($res as $rec) {
    	$arr[] = [
    		$rec['created_at'],
    		(int)$rec['tr_players'],
    		(int)$rec['si_players'],
    	];
    }

	echo json_encode([
		'status' => 'OK',
		'data' => $arr,
	]);
}


function script_title($file_path)
{
	$file_name = basename($file_path, '.php');

	$file_name = str_replace('-', ' ', $file_name);
	
	return strtoupper($file_name);
}


function woocommerce_constrictor()
{
	return new \Automattic\WooCommerce\Client(
						'https://gig-games.de/', // Your store URL
						WOO_CK, // Your consumer key
						WOO_CS, // Your consumer secret
						[
				        'wp_api' => true,
				        'version' => 'wc/v3',
						 'query_string_auth' => true,
						 ]
				);
}


function do_woocommerce_api_request($method, $endpoint, $data = [])
{
	global $_ERRORS;
	$res = '';
	try{
		$res = woocommerce_constrictor()->$method($endpoint, $data);
	}catch (Exception $e) {
			$_ERRORS[] =  $e->getMessage();
	}
	return([
		'res' => $res,
		'errors' => $_ERRORS,
	]);
}


function ajax_hot_do_woocommerce_api_request()
{
	return json_encode(
		do_woocommerce_api_request($_POST['method'], $_POST['endpoint'], $_POST['data'])
	);
}


function _esc_attr($str)
{
	 htmlspecialchars($str, ENT_QUOTES);
}




function get_moda_women_cats()
{
	$final_res = [];

	$res3 = arrayDB("SELECT * from moda_cats where CategoryParentID= '260010'");
	foreach ($res3 as $val3) {
		$res4 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val3['CategoryID']}'");
		if ($res4) {
			foreach ($res4 as $val4) {
				$res5 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val4['CategoryID']}'");
				if ($res5) {
					foreach ($res5 as $val5) {
						$res6 = arrayDB("SELECT * from moda_cats where CategoryParentID= '{$val5['CategoryID']}'");
						if ($res6) {
							foreach ($res6 as $val6) {
								$final_res[] = $val6;
							}
						}else{
							$final_res[] = $val5;
						}
					}
				}else{
					$final_res[] = $val4;
				}
			}
		}else{
			$final_res[] = $val3;
		}
	}

	return($final_res);
}