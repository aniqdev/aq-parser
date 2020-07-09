<?php



$res = file_get_contents('https://m.tiktok.com/api/item_list/?count=30&id=6659703603265273862&type=1&secUid=MS4wLjABAAAA2SgYerI2EXaTG15-hvRri322can6T9giHaNYPfaZ5HsvywBa0YU2NeqWB9fmin2m&maxCursor=0&minCursor=0&sourceType=8&appId=1233&region=UA&language=ru&verifyFp=verify_kamkqmav_MnFx5SbG_DfSH_4iWf_Bip8_wqhrIkD8TT08&_signature=sdMPJAAgEB0VAB51XzdJJ7HTDjAAO8n');

sa($res);


return;
/** Отправляем GET запрос на  https://www.instagram.com**/
 $curl = curl_init();
 curl_setopt_array($curl, [
        CURLOPT_URL => 'https://www.instagram.com',
        CURLOPT_HEADER => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_HTTPHEADER => ['user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) 
        AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36'],
 ]);
 $response = curl_exec($curl);
 sa($response);
 $headers  = curl_getinfo($curl);
 /** обрезаем лишнее из headers **/
 $header_content = substr($response, 0, $headers['header_size']);
 curl_close($curl);
/**
Для нас важен кукис csrftoken</b>, его мы устанавливаем в header x-csrftoken для дальнейшего запроса авторизации. 

Парсим куки:
**/
$cookie = [];
preg_match_all("/Set-Cookie:\s*(?<cookie>[^=]+=[^;]+)/mi", $header_content, $matches);
foreach ($matches['cookie'] as $c) {
            if ($c = str_replace(['sessionid=""', 'target=""'], '', $c)) {
                $c = explode('=', $c);
                $cookie = array_merge($cookie, [trim($c[0]) => trim($c[1])]);
            }
        }
if (isset($cookie['csrftoken'])) {
/**
проверяем вернул установил нам инстаграм кукис csrftoken
если нет куки возможно ваш IP или Прокси в черных списках.
**/
}

sa($cookie);