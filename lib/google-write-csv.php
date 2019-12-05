<?php ini_get('safe_mode') or set_time_limit(300); // Указываем скрипту, чтобы не обрывал связь.

if (isset($_POST['action']) && $_POST['action'] === 'iterate') {
	header('Content-Type: application/json');

	$offset = (int)$_POST['offset'];
	$table = @$_GET['table'] ? $_GET['table'] : 'gp_domens_mi';
	$where = "";

	if($offset == 0) arrayDB("UPDATE $table SET flag = 'done1' WHERE flag = 'done2'");

	$count = arrayDB("SELECT count(*) FROM $table $where")[0]['count(*)'];

	$list = arrayDB("SELECT * FROM $table $where LIMIT $offset , 1000");


	//=============================================================================
	
	$fp = fopen('file.csv', 'w');

	foreach ($list as $fields) {
	    fputcsv($fp, $fields);
	}

	fclose($fp);
	
	//=============================================================================


	echo json_encode([
		'offset' => $offset,
		'count' => $count,
		'amount' => $amount,
		'inserted' => $_GET['gp_inserted'],
		'res' => $res[0],
		'resp' => $word . ' ( ' . $_GET['gp_inserted'] . ' / ' . $amount . ' )',
		'ERRORS' => $_ERRORS,
	]);
}


// sa($_ERRORS);

if($_POST) return;
?>
<style>
	
</style>

<h3>Google parser</h3>
<form id="js_go_form" class="go-form">
    <button name="aaa" value="bbb" type="button" class="js-go-btn">Go!</button>
</form><br><br><br>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>

<script>
function it_ins_msg(msg) {
	$( "#message li:first" ).before( "<li>"+msg+"</li>" );
	if($('#message li').length > 100) {
		$('#message li:last').remove();
	}
}
var from = 0;
var to = 200000;
function send_post(offset) {
	$.post('ajax.php' + window.location.search,
		{action:'iterate', offset:offset},
		function (data) {
			if (offset < data.count && offset < to) {
				it_ins_msg(offset + ' : ' + data.resp);
				send_post(offset+1);
			}else{
				$('.loading').removeClass('inaction');
				it_ins_msg('Done!');
				it_ins_msg( "или что-то пошло не так" );
			}
		}, 'json');
}
$('.js-go-btn').on('click', function() {
	$(this).attr('disabled','true');
	send_post(from);
});
</script>



<?php


return;
$count = save_domens('семья');

sa($count);


return;
sa(urlencode('asd asd'));
sa(rawurlencode('asd asd'));

return;
$url = "https://www.google.com/search?ved=0ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQxdoBCFw&hl=ru&yv=3&q=wp+content&lr=&tbm=isch&ei=SnBBXebPD_GorgSjrYcI&vet=10ahUKEwjmnKSd_N7jAhVxlIsKHaPWAQEQuT0IQCgB.SnBBXebPD_GorgSjrYcI.i&ijn=4&start=100&asearch=ichunk&async=_id:rg_s,_pms:s,_fmt:pc";

// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_USERAGENT, "");
// curl_setopt($ch, CURLOPT_FAILONERROR, 1);
// curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt($ch, CURLOPT_REFERER, "http://www.google.ru/"); 
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
// curl_setopt($ch, CURLOPT_TIMEOUT, 30);
// curl_setopt($ch, CURLOPT_POST, 0);

// $data = $data_title = $data_description = curl_exec($ch);
// curl_close($ch);

$data = getSslPage($url);

$res = preg_match_all('/"rh":"(.+?)"/', $data, $matches);

var_dump($res);

// echo $data_url;

sa($matches[1]);

// preg_match_all("/<cite>(.+?)<\/cite>/is",$data_url,$matches_url); 
// preg_match_all('/<h3 class="r">(.+?)<\/h3>/is',$data_title,$matches_title);
// preg_match_all('/<span class="st">(.+?)<\/span>/is',$data_description,$matches_description);

// $text_title = implode("[space]", $matches_title[0]);
// $text_title = strip_tags($text_title);
// $text_title_array = explode("[space]", $text_title);


// $description = implode("[space]",$matches_description[0]);
// $description = strip_tags($description);
// $description_array = explode("[space]", $description);

// $url_array = $matches_url[1];

// $final_array['titles'] = $text_title_array;
// $final_array['urls'] = $url_array;
// $final_array['descriptions'] = $description_array;
 
?>
