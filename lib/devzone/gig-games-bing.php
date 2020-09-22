<?php


$json_str = '';
if (isset($_POST['submit'])) {
    _do_post_submit();
}elseif (isset($_POST['ajax'])) {
    _do_post_ajax();
    return;
}else{
    sa($_POST);
    $json_str = _do_no_post();
}

function _do_post_ajax()
{
    echo json_encode([
        'post' => $_POST,
        'json_str' => _do_no_post(),
    ]);
}
function _do_post_submit()
{
    $json_str = trim($_POST['text']);

    $url = 'https://ssl.bing.com​/webmaster/api.svc/json/SubmitUrlbatch?​siteurl='.urlencode('https://gig-games.de').'&apikey=0badb4b83e1a4a01b56d287782c7247a';

    $post_res = post_curl($url, $json_str, ['Content-Type: application/json; charset=utf-8']);

    xa($post_res);
}
function _do_no_post()
{   
    $offset = @$_REQUEST['offset'] ? (int)$_REQUEST['offset'] : 0;

	$res = is_dev() ? [] : arrayDB("SELECT * from steam_de limit $offset,500");

	$json_arr = [
		'siteUrl' => 'https://gig-games.de',
        'urlList' => [],
	];

	foreach ($res as $steam_game) {
		$json_arr['urlList'][] = get_gig_game_link($json_arr['siteUrl'], $steam_game);
	}

	// sa(count($json_arr['urlList']));

	if($res) $json_str = json_encode($json_arr, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES); // JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    else $json_str = '';

	return $json_str;
}

$offset = @$_GET['offset'] ? (int)$_GET['offset'] : 0;
$new_query_str = obj(QS)->set('offset', $offset + 500)->give();
?>
<div class="container-fluid" style="max-width: 1400px;">
    <form method="POST" class="form-inline">
        <textarea id="ggb_textarea" class="form-control" name="text" id="" cols="30" rows="20" autofocus style="width: 100%;"><?= $json_str;  ?></textarea><br><br>
        <input type="text" class="form-control" id="offset_inp" name="offset" value="<?= isset($_POST['offset']) ? (int)$_POST['offset'] : 0; ?>">
        <button class="btn btn-success" onclick="_doAction()" type="button">Next</button>
        <button class="btn btn-primary" name="submit">Send</button>
<!--         <a href="?<?= $new_query_str; ?>" class="btn btn-success">Next>></a>
        <button class="btn btn-default" onclick="document.all.ggb_textarea.select();document.execCommand('copy');" type="button">copy to clipboard</button> -->
        <script>
            function _doAction() {
                var offset = document.all.offset_inp.value;
                $.post('https://parser.gig-games.de/ajax-cross.php?action=devzone/gig-games-bing',
                    {ajax:1, test:'111222333', offset: offset }, function(data){
                        console.log(data)
                        document.all.offset_inp.value = +offset + 500
                        document.all.ggb_textarea.value = data.json_str
                    }, 'json')
            }
        </script>
    </form>
</div>