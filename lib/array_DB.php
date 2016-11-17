<?php
require_once __DIR__.'/../config.php';

spl_autoload_register(function($class)
{
	include_once __DIR__ . '/classes/' . $class . '.class.php';
});

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
						if($c['encoding'] === 'windows-1251'){
								foreach ($array[$i] as &$cell) {
										$cell = iconv('UTF-8', 'Windows-1251', $cell);
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


/**
* 
*/
function get_a3_smtp_object(){
	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.strato.de';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'a3@gig-games.de';                 // SMTP username
	$mail->Password = A3_GIG_MAIL_PWD;                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption,
	$mail->Port = 465;
	$mail->CharSet = "UTF-8";                               // TCP port to connect to
	$mail->setFrom('a3@gig-games.de', 'GiG-Games');
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
    $urls = implode('<br>'.PHP_EOL, $urls);
    if ($found) {
    	return $urls;
    }else{
    	return $text;
    }
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
	// германия, австрия, швейцария, нидерланды
	if ($ca === 'DE' || $ca === 'AT' || $ca === 'CH' || $ca === 'NL') {
		$msg_email = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="DE" AND ebay_or_mail="mail" LIMIT 1')[0]['message'];
	}else{
		@$msg_email = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="'.$ca.'" AND ebay_or_mail="mail" LIMIT 1')[0]['message'];
	}
	if (!$msg_email) {
		$msg_email = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="mail" LIMIT 1')[0]['message'];
	}


	// германия, австрия, швейцария, нидерланды
	if ($ca === 'DE' || $ca === 'AT' || $ca === 'CH' || $ca === 'NL') {
		$msg_ebay = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="DE" AND ebay_or_mail="ebay" LIMIT 1')[0]['message'];
	}else{
		@$msg_ebay = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="'.$ca.'" AND ebay_or_mail="ebay" LIMIT 1')[0]['message'];
	}
	if (!$msg_ebay) {
		$msg_ebay = arrayDB('SELECT * FROM ebay_inv_messages WHERE country_alias="EN" AND ebay_or_mail="ebay" LIMIT 1')[0]['message'];
	}


	$item_title = $order_info['item_title'];
	if($pos = stripos($item_title, '(pc)')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'steam')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'gog')) $item_title = substr($item_title, 0, $pos-1);
	if($pos = stripos($item_title, 'uplay')) $item_title = substr($item_title, 0, $pos-1);

	$product = iconv('CP1251', 'UTF-8', $product);
	$product = get_steam_key_from_text($product);
	$product = get_urls_from_text($product);

	$msg_email = str_replace('{{PRODUCT}}', $product, $msg_email);
	$msg_ebay = str_replace('{{EMAIL}}', $order_info['bayer_email'], $msg_ebay);

	if (strpos($msg_email, 'account/ackgift') !== false || strpos($msg_email, 'undle.com') !== false) {
		$msg_email = str_replace('Activation link/key', 'Activation link', $msg_email);
		$msg_email = preg_replace('/Aktivierungsschl.+ssel\/link/', 'Aktivierungslink', $msg_email);
	}else{
		$msg_email = str_replace('Activation link/key', 'Activation key', $msg_email);
		$msg_email = preg_replace('/Aktivierungsschl.+ssel\/link/', 'Aktivierungsschlüssel', $msg_email);
	}
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

?>