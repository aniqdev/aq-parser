<?php

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