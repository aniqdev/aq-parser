<?php ini_get('safe_mode') or set_time_limit(1300);




$a = [0=>2,1=>3,2=>4];

$b = [2=>'7',7=>'8',8=>'9'];

$a =  array_merge($a, $b);

sa($a);




return;
$items = arrayDB("SELECT *, UNIX_TIMESTAMP(endTime) as timest from moda_list where flag = 'dataparsed1' order by id desc limit 300");
?>
<style>
fg,fb{
    height: 10px;
    width: 10px;
    display: inline-block;
    background: lightgreen;
}
fb{
    background: lightcoral;
}
</style>
<div class="container"><br><br>
    <table class="table">
    <?php
    foreach ($items as $key => $item) {
        $mark = $item['timest'] > time() ? '<fg></fg>' : '<fb></fb>';
        echo "<tr>";
        echo "<td>{$item['itemId']}</td>";
        echo "<td><a href='https://www.ebay.de/itm/{$item['itemId']}' target='_blank'>{$item['title']}</a></td>";
        echo "<td>{$item['ListingType']}</td>";
        echo "<td>{$item['endTime']}</td>";
        echo "<td>{$mark}</td>";
        echo "</tr>";
    }
    ?>
    </table>
</div>
<?php


return;
    
    $resp = Ebay_shopping2::getSingleItem_moda($itemId = '382532184636', $as_array = 1);

    var_dump($itemId);
    
    sa($resp);




return;
    $items = arrayDB("SELECT * from moda_list where flag = 'dataparsed1' and ListingType = ''");

    foreach ($items as $moda) {
        $moda_id = $moda['id'];
        $ListingType = get_moda_meta($moda_id, $meta_key = 'ListingType');
        $ListingType = _esc($ListingType);
        arrayDB("UPDATE moda_list SET ListingType = '$ListingType' where id = $moda_id");
    }

sa(count($items));



return;
define('CRM_HOST', 'b24-1cbkwk.bitrix24.ru'); // Домен срм системы
define('CRM_PORT', '443'); 
define('CRM_PATH', '/crm/configs/import/lead.php'); 
define('CRM_LOGIN', 'thenav@mail.ru');  // логин
define('CRM_PASSWORD', 'kajmad'); // пароль
 
/********************************************************************************************/
$test = true;




if ($_SERVER['REQUEST_METHOD'] == 'POST' || $test){
 
    // получаем данные из полей и задаем название лида
     
    $postData = array(
        'TITLE' => 'Форма обратной связи', // сохраняем нашу метку и формируем заголовок лида
        'NAME' => '_NAME',   // сохраняем имя
        'PHONE_WORK' =>'_PHONE_WORK', // сохраняем телефон
        'EMAIL_WORK' => 'asd@asd.df', // сохраняем почту
        'UF_CRM_1443598721' => '_UF_CRM_1443598721', // сохраняем ИНН
    );
 
    // авторизация, проверка логина и пароля
    if (defined('CRM_AUTH'))
    {
        $postData['AUTH'] = CRM_AUTH;
    }
    else
    {
        $postData['LOGIN'] = CRM_LOGIN;
        $postData['PASSWORD'] = CRM_PASSWORD;
    }
 
    $fp = fsockopen("ssl://".CRM_HOST, CRM_PORT, $errno, $errstr, 30);
    if ($fp)
    {
        // формируем и шифруем строку с данными из формы
        $strPostData = '';
        foreach ($postData as $key => $value) $strPostData .= ($strPostData == '' ? '' : '&').$key.'='.urlencode($value);
            $str = "POST ".CRM_PATH." HTTP/1.0\r\n";
            $str .= "Host: ".CRM_HOST."\r\n";
            $str .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $str .= "Content-Length: ".strlen($strPostData)."\r\n";
            $str .= "Connection: close\r\n\r\n";
 
        $str .= $strPostData;

        sa($str);
 
        // отправляем запрос в срм систему
        fwrite($fp, $str );
        $result = '';
        while (!feof($fp))
        {
            $result .= fgets($fp, 128);
        }
        fclose($fp);
 
        $response = explode("\r\n\r\n", $result);
        $output = '<pre>'.print_r($response[1], 1).'</pre>';
    }
    else
    {
        echo 'Connection Failed! '.$errstr.' ('.$errno.')';
    }
}